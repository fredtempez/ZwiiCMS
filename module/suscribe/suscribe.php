<?php

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.com/
 */

class suscribe extends common
{

	const VERSION = '2.9';
	const REALNAME = 'Auto Inscription';
	const DELETE = true;
	const UPDATE = '0.0';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	const STATUS_EMAIL_AWAITING = 0;
	const STATUS_EMAIL_VALID = 1;
	const STATUS_ACCOUNT_AWAITING = 2;
	const STATUS_ACCOUNT_VALID = 3;
	public static $statusGroups = [
		self::STATUS_EMAIL_AWAITING => 'Email non confirmé',
		self::STATUS_EMAIL_VALID => 'Email valide',
		self::STATUS_ACCOUNT_AWAITING => 'Email valide, en attente de confirmation',
		self::STATUS_ACCOUNT_VALID => 'Email valide, compte activé',
	];

	public static $actions = [
		'index' => self::ROLE_VISITOR,
		'validate' => self::ROLE_VISITOR,
		'config' => self::ROLE_EDITOR,
		'users' => self::ROLE_EDITOR,
		'delete' => self::ROLE_EDITOR,
		'edit' => self::ROLE_EDITOR
	];

	public static $layout = [
		'inputRowContainer1' => 'Un élément par ligne',
		'inputRowContainer2' => 'Deux éléments par ligne',
		'inputRowContainer4' => 'Quatre éléments par ligne',
	];

	public static $timeLimit = [
		2 => '2 minutes',
		5 => '5 minutes',
		10 => '10 minutes'
	];

	public static $users = [];

	public static $roles = [];
	public static $userProfils = [];
	public static $userProfilsComments = [];

	/**
	 * Liste des utilisateurs en attente
	 */
	public function users()
	{
		$userIdsFirstnames = helper::arraycollumn($this->getData(['module', $this->getUrl(0), 'users']), 'firstname');
		ksort($userIdsFirstnames);
		foreach ($userIdsFirstnames as $userId => $userFirstname) {
			self::$users[] = [
				$userId,
				$userFirstname . ' ' . $this->getData(['module', $this->getUrl(0), 'users', $userId, 'lastname']),
				self::$statusGroups[$this->getData(['module', $this->getUrl(0), 'users', $userId, 'status'])],
				helper::dateUTF8(date('Y-m-d G:i'), $this->getData(['module', $this->getUrl(0), 'users', $userId, 'timer'])),
				template::button('registrationUserEdit' . $userId, [
					'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $userId,
					'value' => template::ico('pencil')
				]),
				template::button('registrationUserDelete' . $userId, [
					'class' => 'userDelete red',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $userId,
					'value' => template::ico('cancel')
				])
			];
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Inscription en attente',
			'view' => 'users'
		]);
	}


	/**
	 * Édition
	 */
	public function edit()
	{
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Accès refusé
		if (
			// L'utilisateur n'existe pas
			$this->getData(['module', $this->getUrl(0), 'users', $this->getUrl(2)]) === null
			// Droit d'édition
			and (
					// Impossible de s'auto-éditer
				(
					$this->getUser('id') === 'user'
					and $this->getUrl('role') <= self::ROLE_VISITOR
				)
				// Impossible d'éditer un autre utilisateur
				or ($this->getUrl('role') < self::ROLE_EDITOR)
			)
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Accès autorisé
		else {
			// Soumission du formulaire
			if (
				$this->getUser('permission', __CLASS__, __FUNCTION__) === true
				&& $this->isPost()
			) {
				// Créer le user dans la base
				$this->setData([
					'user',
					$this->getUrl(2),
					[
						'firstname' => $this->getData(['module', $this->getUrl(0), 'users', $this->getUrl(2), 'firstname']),
						'forgot' => 0,
						'role' => $this->getInput('registrationUserEditGroup', helper::FILTER_INT),
						// Le profil vaut 0 pour les amdins et 1 pour les autres membres, profil par défaut.
						'profil' => $this->getInput('registrationUserEditGroup', helper::FILTER_INT) === self::ROLE_ADMIN
							? 0 : 1,
						'lastname' => $this->getData(['module', $this->getUrl(0), 'users', $this->getUrl(2), 'lastname']),
						'mail' => $this->getData(['module', $this->getUrl(0), 'users', $this->getUrl(2), 'mail']),
						'password' => $this->getData(['module', $this->getUrl(0), 'users', $this->getUrl(2), 'password']),
						'tags' => $this->getInput('registrationUserLabel', helper::FILTER_STRING_SHORT),
					]
				]);
				// Notifier le user 
				$this->sendMail(
					$this->getData(['module', $this->getUrl(0), 'users', 'mail']),
					'Approbation de l\'inscription',
					'<p>' . $this->getdata(['module', $this->getUrl(0), 'config', 'mailValidateContent']) . '</p>',
					null,
					$this->getData(['config', 'smtp', 'from'])
				);
				// Supprimer le user de la base temporaire,
				$this->deleteData(['module', $this->getUrl(0), 'users', $this->getUrl(2)]);
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/users',
					'notification' => 'Modifications enregistrées',
					'state' => true
				]);
			}
			// Changement temporaire de libellé du rôle 0
			self::$roles = self::$roleEdits;
			self::$roles[self::ROLE_BANNED] = 'En attente d\'approbation';

			// Profils disponibles
			foreach ($this->getData(['profil']) as $profilId => $profilData) {
				if ($profilId < self::ROLE_MEMBER) {
					continue;
				}
				if ($profilId === self::ROLE_ADMIN) {
					self::$userProfils[$profilId][self::ROLE_ADMIN] = $profilData['name'];
					self::$userProfilsComments[$profilId][self::ROLE_ADMIN] = $profilData['comment'];
					continue;
				}
				foreach ($profilData as $key => $value) {
					self::$userProfils[$profilId][$key] = $profilData[$key]['name'];
					self::$userProfilsComments[$profilId][$key] = $profilData[$key]['name'] . ' : ' . $profilData[$key]['comment'];
				}
			}

			// Valeurs en sortie
			$this->addOutput([
				'title' => $this->getData(['module', $this->getUrl(0), 'users', 'firstname']) . ' ' . $this->getData(['user', $this->getUrl(0), 'lastname']),
				'view' => 'edit'
			]);
		}
	}


	/**
	 * Suppression
	 */
	public function delete()
	{
		// Accès refusé
		if (
			// L'utilisateur n'existe pas
			$this->getData(['module', $this->getUrl(0), 'users', $this->getUrl(2)]) === null
			// Rôle insuffisant
			&& $this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Suppression
		else {
			$this->deleteData(['module', $this->getUrl(0), 'users', $this->getUrl(2)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/users',
				'notification' => 'Inscription supprimée',
				'state' => true
			]);
		}
	}


	/**
	 * Ajout
	 */
	public function index()
	{
		/**
		 * Traitement du formulaire
		 * Stocke les données du formulaire dans le module
		 * Envoie le mail de vérification de l'email
		 */
		// Soumission du formulaire
		if ($this->isPost()) {
			// Contrôler la validité du domaine saisi parmi les domaines valides
			$email_to_check = $this->getInput('registrationAddMail', helper::FILTER_MAIL, true);
			// Le domaine saisi est invalide si un filtre existe
			if (
				$this->getData(['module', $this->getUrl(0), 'config', 'filter']) !== '' &&
				$email_to_check !== ''
			) {

				// Récupérer la liste des domaines valides depuis la configuration et supprimer les espaces autour
				$filter = trim($this->getData(['module', $this->getUrl(0), 'config', 'filter']));

				// Vérifier si la liste contient plusieurs domaines ou un seul, puis supprimer les espaces pour chaque domaine
				$valid_domains = strpos($filter, ';') === false
					? [trim($filter)]  // Si un seul domaine, on supprime les espaces et on le met dans un tableau
					: array_map('trim', explode(';', $filter));  // Si plusieurs domaines, on les explose en tableau et supprime les espaces

				// Extraire le domaine de l'adresse email à vérifier
				$email_domain = explode('@', $email_to_check)[1];
				// Vérifier si le domaine de l'email est dans la liste des domaines valides
				if (!in_array($email_domain, $valid_domains)) {
					self::$inputNotices['registrationAddMail'] = helper::translate('Ce domaine n\'est pas autorisé');
				}
			}
			// Email valide, on continue le traitement 
			if (self::$inputNotices === []) {
				// Drapeau de contrôle des données saisies.
				$check = true;
				$sentMailtoUser = false;
				// L'identifiant d'utilisateur est indisponible
				$userId = $this->getInput('registrationAddId', helper::FILTER_ID, true);
				if (is_array($this->getData(['user', $userId]))) {
					self::$inputNotices['registrationAddId'] = helper::translate('Identifiant déjà utilisé');
					$check = false;
				}
				// Le compte existe déjà
				foreach ($this->getData(['user']) as $usersId => $user) {
					if ($user['mail'] === $this->getInput('registrationAddMail', helper::FILTER_MAIL, true)) {
						self::$inputNotices['registrationAddMail'] =  helper::translate('Vous ne pouvez pas utilisez cet email');
						$check = false;
						break;
					}
				}
				// Données de l'utilisateur
				$userFirstname = $this->getInput('registrationAddFirstname', helper::FILTER_STRING_SHORT, true);
				$userLastname = $this->getInput('registrationAddLastname', helper::FILTER_STRING_SHORT, true);
				$userMail = $this->getInput('registrationAddMail', helper::FILTER_MAIL, true);
				// Pas de nom saisi
				if (
					empty($userFirstname) ||
					empty($userLastname)
				) {
					$check = false;
				}
				// Si tout est ok
				if ($check === true) {
					//  Enregistrement du compte dans les données du module
					$auth = uniqid('', true);
					$this->setData([
						'module',
						$this->getUrl(0),
						'users',
						$userId,
						[
							'firstname' => $userFirstname,
							'lastname' => $userLastname,
							'mail' => $userMail,
							'password' => '',
							// pas de rôle afin de le différencier dans la liste des users
							'timer' => time(),
							'pseudo' => $userId,
							'auth' => $auth,
							'status' => self::STATUS_EMAIL_AWAITING
						]
					]);
					// Mail d'avertissement aux administrateurs
					// Utilisateurs dans le rôle admin
					$to = [];
					foreach ($this->getData(['user']) as $key => $user) {
						if ($user['role'] == self::ROLE_ADMIN) {
							$to[] = $user['mail'];
						}
					}
					// Envoi du mail à l'administrateur
					if ($check && is_array($to)) {
						$this->sendMail(
							$to,
							'Auto-inscription sur le site ' . $this->getData(['config', 'title']),
							'<p>Un nouveau membre s\'est inscrit, son email est en attente de  validation</p>' .
							'<p><strong>Identifiant du compte :</strong> ' . $userId . ' (' . $userFirstname . ' ' . $userLastname . ')<br>' .
							'<strong>Email  :</strong> ' . $userMail . '</p>' .
							'<a href="' . helper::baseUrl() . 'user/login/' . strip_tags(str_replace('/', '_', $this->getUrl(0) . '/users')) . '">Validation de l\'inscription</a>',
							null,
							$this->getData(['config', 'smtp', 'from'])
						);
					}

					// Mail de confirmation à l'utilisateur
					// forger le lien de vérification
					$validateLink = helper::baseUrl(true) . $this->getUrl() . '/validate/' . $userId . '/' . $auth;
					// Envoi
					if ($check) {
						$sentMailtoUser = $this->sendMail(
							$userMail,
							'Confirmation de votre inscription',
							'<p>' . $this->getdata(['module', $this->getUrl(0), 'config', 'mailRegisterContent']) . '</p>' .
							'<p><a href="' . $validateLink . '">Activer votre compte<a/>' . '</p>' .
							'<p>ou copiez collez le lien suivant dans un navigateur : ' . $validateLink . '</p>'
							,
							null,
							$this->getData(['config', 'smtp', 'from'])
						);
					}
				}
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl(),
					//'redirect' => $validateLink,
					'notification' => $sentMailtoUser ? "Un mail vous a été envoyé pour confirmer votre inscription" : 'Quelque chose n\'a pas fonctionné !',
					'state' => $sentMailtoUser ? true : false
				]);
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Inscription',
			'view' => 'index',
			'showBarEditButton' => true,
			'showPageContent' => true
		]);
	}

	/**
	 * Vérification de l'email
	 */
	public function validate()
	{
		// Soumission du formulaire
		if ($this->isPost()) {
			// Vérifie la session + l'id + le timer
			$check = true;
			$notification = 'Bienvenue sur le site ' . $this->getData(['config', 'title']);
			$userId = $this->getUrl(2);
			$auth = $this->getUrl(3);
			// la validité du lien est dépassé
			if (
				(time() - $this->getData(['module', $this->getUrl(0), 'users', $userId, 'timer']))
				>= $this->getdata(['module', $this->getUrl(0), 'config', 'timeOut'])
			) {
				$check = false;
				$notification = 'La validité du lien est dépassée !';
			}
			// La clé est incorrecte ou le compte n'est pas en attente de validation
			if (
				$check
				&& $auth !== $this->getData(['module', $this->getUrl(0), 'users', $userId, 'auth'])
				&& $this->getData(['module', $this->getUrl(0), 'users', $userId, 'status']) !== self::STATUS_EMAIL_AWAITING
			) {
				$check = false;
				$notification = 'Les données saisies sont incorrectes !';
			}
			// Double vérification pour le mot de passe
			if (
				$check
				&& $this->getInput('registrationValidPassword', helper::FILTER_STRING_SHORT, true) !== $this->getInput('registrationValidConfirmPassword', helper::FILTER_STRING_SHORT, true)
			) {
				self::$inputNotices['registrationValidConfirmPassword'] = 'Les mots de passe ne sont pas identiques';
				$check = false;
			}
			if ($check) {
				if (
					// Pas d'approbation par un administrateur
					$this->getData(['module', $this->getUrl(0), 'config', 'approval']) === false
				) {
					// Créer le compte
					$this->setData([
						'user',
						$userId,
						[
							'firstname' => $this->getData(['module', $this->getUrl(0), 'users', $userId, 'firstname']),
							'lastname' => $this->getData(['module', $this->getUrl(0), 'users', $userId, 'lastname']),
							'mail' => $this->getData(['module', $this->getUrl(0), 'users', $userId, 'mail']),
							'password' => $this->getInput('registrationValidPassword', helper::FILTER_PASSWORD, true),
							'role' => self::ROLE_MEMBER,
							'profil' => 1,
							'forgot' => 0,
							'pseudo' => $userId,
							'signature' => 1,
							'language' => self::$siteContent,
						]
					]);
					// Modifier le statut dans le module
					$this->deleteData(['module', $this->getUrl(0), 'users', $userId]);
					$notification = 'Votre inscription est confirmée';
				} else {
					// Approbation nécessaire
					$this->setData(['module', $this->getUrl(0), 'users', $userId, 'status', self::STATUS_ACCOUNT_AWAITING]);
					$notification = 'L\'inscription doit être approuvée par un administrateur';
					// Stocker le mot de passe temporairement 
					$this->setData([
						'module',
						$this->getUrl(0),
						'users',
						$userId,
						'password',
						$this->getInput('registrationValidPassword', helper::FILTER_PASSWORD, true),
					]);

				}
			}

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => $check ? helper::baseUrl() . $this->getdata(['module', $this->getUrl(0), 'config', 'pageSuccess']) : helper::baseUrl() . $this->getdata(['module', $this->getUrl(0), 'config', 'pageError']),
				'notification' => $notification,
				'state' => $check
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Saisie du mot de passe',
			'view' => 'validate'
		]);
	}

	/**
	 * Module de configuration
	 */
	public function config()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true
			&& $this->isPost()
		) {

			// Lire les options et les enregistrer
			$this->setData([
				'module',
				$this->getUrl(0),
				'config',
				[
					'timeOut' => $this->getInput('registrationConfigTimeOut', helper::FILTER_INT) * 60,
					'pageSuccess' => $this->getInput('registrationConfigSuccess'),
					'pageError' => $this->getInput('registrationConfigError'),
					'approval' => $this->getInput('registrationConfigState', helper::FILTER_BOOLEAN),
					'mailRegisterContent' => $this->getInput('registrationconfigMailRegisterContent', null, true),
					'mailValidateContent' => $this->getInput('registrationconfigMailValidateContent', null, true),
					'layout' => $this->getInput('registrationConfigLayout'),
					'filter' => $this->getInput('registrationConfigFilter', helper::FILTER_STRING_SHORT)
				]
			]);
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Configuration',
			'view' => 'config',
			'vendor' => ['tinymce']
		]);
	}
}

