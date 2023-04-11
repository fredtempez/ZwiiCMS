<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class user extends common
{

	public static $actions = [
		'add' => self::GROUP_ADMIN,
		'delete' => self::GROUP_ADMIN,
		'import' => self::GROUP_ADMIN,
		'index' => self::GROUP_ADMIN,
		'template' => self::GROUP_ADMIN,
		'edit' => self::GROUP_MEMBER,
		'logout' => self::GROUP_MEMBER,
		'forgot' => self::GROUP_VISITOR,
		'login' => self::GROUP_VISITOR,
		'reset' => self::GROUP_VISITOR,
	];

	public static $users = [];

	//Paramètres pour choix de la signature
	public static $signature = [
		self::SIGNATURE_ID => 'Identifiant',
		self::SIGNATURE_PSEUDO => 'Pseudo',
		self::SIGNATURE_FIRSTLASTNAME => 'Prénom Nom',
		self::SIGNATURE_LASTFIRSTNAME => 'Nom Prénom'
	];

	public static $userId = '';

	public static $userLongtime = false;

	public static $separators = [
		';' => ';',
		',' => ',',
		':' => ':'
	];

	public static $languagesInstalled = [];

	/**
	 * Ajout
	 */
	public function add()
	{
		// Soumission du formulaire
		if ($this->isPost()) {
			$check = true;
			// L'identifiant d'utilisateur est indisponible
			$userId = $this->getInput('userAddId', helper::FILTER_ID, true);
			if ($this->getData(['user', $userId])) {
				self::$inputNotices['userAddId'] = 'Identifiant déjà utilisé';
				$check = false;
			}
			// Double vérification pour le mot de passe
			if ($this->getInput('userAddPassword', helper::FILTER_STRING_SHORT, true) !== $this->getInput('userAddConfirmPassword', helper::FILTER_STRING_SHORT, true)) {
				self::$inputNotices['userAddConfirmPassword'] = 'Incorrect';
				$check = false;
			}
			// Crée l'utilisateur
			$userFirstname = $this->getInput('userAddFirstname', helper::FILTER_STRING_SHORT, true);
			$userLastname = $this->getInput('userAddLastname', helper::FILTER_STRING_SHORT, true);
			$userMail = $this->getInput('userAddMail', helper::FILTER_MAIL, true);

			// Stockage des données
			$this->setData([
				'user',
				$userId,
				[
					'firstname' => $userFirstname,
					'forgot' => 0,
					'group' => $this->getInput('userAddGroup', helper::FILTER_INT, true),
					'lastname' => $userLastname,
					'pseudo' => $this->getInput('userAddPseudo', helper::FILTER_STRING_SHORT, true),
					'signature' => $this->getInput('userAddSignature', helper::FILTER_INT, true),
					'mail' => $userMail,
					'password' => $this->getInput('userAddPassword', helper::FILTER_PASSWORD, true),
					"connectFail" => null,
					"connectTimeout" => null,
					"accessUrl" => null,
					"accessTimer" => null,
					"accessCsrf" => null,
					"files" => $this->getInput('userAddFiles', helper::FILTER_BOOLEAN),
					'language' => $this->getInput('userEditLanguage', helper::FILTER_STRING_SHORT),
				]
			]);

			// Envoie le mail
			$sent = true;
			if ($this->getInput('userAddSendMail', helper::FILTER_BOOLEAN) && $check === true) {
				$sent = $this->sendMail(
					$userMail,
					'Compte créé sur ' . $this->getData(['locale', 'title']),
					'Bonjour <strong>' . $userFirstname . ' ' . $userLastname . '</strong>,<br><br>' .
						'Un administrateur vous a créé un compte sur le site ' . $this->getData(['locale', 'title']) . '. Vous trouverez ci-dessous les détails de votre compte.<br><br>' .
						'<strong>Identifiant du compte :</strong> ' . $this->getInput('userAddId') . '<br>' .
						'<small>Nous ne conservons pas les mots de passe, en conséquence nous vous conseillons de conserver ce message tant que vous ne vous êtes pas connecté. Vous pourrez modifier votre mot de passe après votre première connexion.</small>',
					null,
					$this->getData(['config', 'smtp', 'from']),
				);
			}
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'user',
				'notification' => $sent === true ? helper::translate('Utilisateur créé') : $sent,
				'state' => $sent === true ? true : null
			]);
		}
		// Langues disponibles pour l'interface de l'utilisateur
		if (is_dir(self::I18N_DIR)) {
			$dir = getcwd();
			chdir(self::I18N_DIR);
			$files = glob('*.json');
			chdir($dir);
		}
		foreach ($files as $file) {
			// La langue est-elle référencée ?
			if (array_key_exists(basename($file, '.json'), self::$languages)) {
				self::$languagesInstalled[basename($file, '.json')] = self::$languages[basename($file, '.json')];
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Nouvel utilisateur'),
			'view' => 'add'
		]);
	}

	/**
	 * Suppression
	 */
	public function delete()
	{
		// Accès refusé
		if (
			// L'utilisateur n'existe pas
			$this->getData(['user', $this->getUrl(2)]) === null
			// Groupe insuffisant
			and ($this->getUrl('group') < self::GROUP_MODERATOR)
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Jeton incorrect
		elseif ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'user',
				'notification' => helper::translate('Action interdite')
			]);
		}
		// Bloque la suppression de son propre compte
		elseif ($this->getUser('id') === $this->getUrl(2)) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'user',
				'notification' => helper::translate('Impossible de supprimer votre propre compte')
			]);
		}
		// Suppression
		else {
			$this->deleteData(['user', $this->getUrl(2)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'user',
				'notification' => helper::translate('Utilisateur supprimé'),
				'state' => true
			]);
		}
	}

	/**
	 * Édition
	 */
	public function edit()
	{
		if (
			$this->getUrl(3) !== $_SESSION['csrf']
		) {

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'user',
				'notification' => helper::translate('Action interdite')
			]);
		}
		// Accès refusé
		if (
			// L'utilisateur n'existe pas
			$this->getData(['user', $this->getUrl(2)]) === null
			// Droit d'édition
			and (
				// Impossible de s'auto-éditer
				($this->getUser('id') === $this->getUrl(2)
					and $this->getUrl('group') <= self::GROUP_VISITOR
				)
				// Impossible d'éditer un autre utilisateur
				or ($this->getUrl('group') < self::GROUP_MODERATOR)
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
			if ($this->isPost()) {
				// Double vérification pour le mot de passe
				$newPassword = $this->getData(['user', $this->getUrl(2), 'password']);
				if ($this->getInput('userEditNewPassword')) {
					// L'ancien mot de passe est correct
					if (password_verify(html_entity_decode($this->getInput('userEditOldPassword')), $this->getData(['user', $this->getUrl(2), 'password']))) {
						// La confirmation correspond au mot de passe
						if ($this->getInput('userEditNewPassword') === $this->getInput('userEditConfirmPassword')) {
							$newPassword = $this->getInput('userEditNewPassword', helper::FILTER_PASSWORD, true);
							// Déconnexion de l'utilisateur si il change le mot de passe de son propre compte
							if ($this->getUser('id') === $this->getUrl(2)) {
								helper::deleteCookie('ZWII_USER_ID');
								helper::deleteCookie('ZWII_USER_PASSWORD');
							}
						} else {
							self::$inputNotices['userEditConfirmPassword'] = helper::translate('Incorrect');
						}
					} else {
						self::$inputNotices['userEditOldPassword'] = helper::translate('Incorrect');
					}
				}
				// Modification du groupe
				if (
					$this->getUser('group') === self::GROUP_ADMIN
					and $this->getUrl(2) !== $this->getUser('id')
				) {
					$newGroup = $this->getInput('userEditGroup', helper::FILTER_INT, true);
				} else {
					$newGroup = $this->getData(['user', $this->getUrl(2), 'group']);
				}
				// Modification de nom Prénom
				if ($this->getUser('group') === self::GROUP_ADMIN) {
					$newfirstname = $this->getInput('userEditFirstname', helper::FILTER_STRING_SHORT, true);
					$newlastname = $this->getInput('userEditLastname', helper::FILTER_STRING_SHORT, true);
				} else {
					$newfirstname = $this->getData(['user', $this->getUrl(2), 'firstname']);
					$newlastname = $this->getData(['user', $this->getUrl(2), 'lastname']);
				}
				// Modifie l'utilisateur
				$this->setData([
					'user',
					$this->getUrl(2),
					[
						'firstname' => $newfirstname,
						'forgot' => 0,
						'group' => $newGroup,
						'lastname' => $newlastname,
						'pseudo' => $this->getInput('userEditPseudo', helper::FILTER_STRING_SHORT, true),
						'signature' => $this->getInput('userEditSignature', helper::FILTER_INT, true),
						'mail' => $this->getInput('userEditMail', helper::FILTER_MAIL, true),
						'password' => $newPassword,
						'connectFail' => $this->getData(['user', $this->getUrl(2), 'connectFail']),
						'connectTimeout' => $this->getData(['user', $this->getUrl(2), 'connectTimeout']),
						'accessUrl' => $this->getData(['user', $this->getUrl(2), 'accessUrl']),
						'accessTimer' => $this->getData(['user', $this->getUrl(2), 'accessTimer']),
						'accessCsrf' => $this->getData(['user', $this->getUrl(2), 'accessCsrf']),
						'files' => $this->getInput('userEditFiles', helper::FILTER_BOOLEAN),
						'language' => $this->getInput('userEditLanguage', helper::FILTER_STRING_SHORT),
					]
				]);
				// Redirection spécifique si l'utilisateur change son mot de passe
				if ($this->getUser('id') === $this->getUrl(2) and $this->getInput('userEditNewPassword')) {
					$redirect = helper::baseUrl() . 'user/login/' . str_replace('/', '_', $this->getUrl());
				}
				// Redirection si retour en arrière possible
				elseif ($this->getUser('group') === 3) {
					$redirect = helper::baseUrl() . 'user';
				}
				// Redirection normale
				else {
					$redirect = helper::baseUrl();
				}
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => $redirect,
					'notification' => helper::translate('Modifications enregistrées'),
					'state' => true
				]);
			}

			// Langues disponibles pour l'interface de l'utilisateur
			self::$languagesInstalled = $this->getData(['languages']);
			foreach (self::$languagesInstalled as $lang => $datas) {
				self::$languagesInstalled[$lang] = self::$languages[$lang];
			}
			
			// Valeurs en sortie
			$this->addOutput([
				'title' => $this->getData(['user', $this->getUrl(2), 'firstname']) . ' ' . $this->getData(['user', $this->getUrl(2), 'lastname']),
				'view' => 'edit'
			]);
		}
	}

	/**
	 * Mot de passe perdu
	 */
	public function forgot()
	{
		// Soumission du formulaire
		if ($this->isPost()) {
			$userId = $this->getInput('userForgotId', helper::FILTER_ID, true);
			if ($this->getData(['user', $userId])) {
				// Enregistre la date de la demande dans le compte utilisateur
				$this->setData(['user', $userId, 'forgot', time()]);
				// Crée un id unique pour la réinitialisation
				$uniqId = md5(json_encode($this->getData(['user', $userId])));
				// Envoi le mail
				$sent = $this->sendMail(
					$this->getData(['user', $userId, 'mail']),
					'Réinitialisation de votre mot de passe',
					'Bonjour <strong>' . $this->getData(['user', $userId, 'firstname']) . ' ' . $this->getData(['user', $userId, 'lastname']) . '</strong>,<br><br>' .
						'Vous avez demandé à changer le mot de passe lié à votre compte. Vous trouverez ci-dessous un lien vous permettant de modifier celui-ci.<br><br>' .
						'<a href="' . helper::baseUrl() . 'user/reset/' . $userId . '/' . $uniqId . '" target="_blank">' . helper::baseUrl() . 'user/reset/' . $userId . '/' . $uniqId . '</a><br><br>' .
						'<small>Si nous n\'avez pas demandé à réinitialiser votre mot de passe, veuillez ignorer ce mail.</small>',
					null,
					$this->getData(['config', 'smtp', 'from']),
				);
				// Valeurs en sortie
				$this->addOutput([
					'notification' => ($sent === true ? helper::translate('Un mail a été envoyé pour confirmer la réinitialisation') : $sent),
					'state' => ($sent === true ? true : null)
				]);
			}
			// L'utilisateur n'existe pas
			else {
				// Valeurs en sortie
				$this->addOutput([
					'notification' =>  helper::translate('Utilisateur inexistant')
				]);
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Mot de passe oublié'),
			'view' => 'forgot',
			'display' => self::DISPLAY_LAYOUT_LIGHT,
		]);
	}

	/**
	 * Liste des utilisateurs
	 */
	public function index()
	{
		$userIdsFirstnames = helper::arrayColumn($this->getData(['user']), 'firstname');
		ksort($userIdsFirstnames);
		foreach ($userIdsFirstnames as $userId => $userFirstname) {
			if ($this->getData(['user', $userId, 'group'])) {
				self::$users[] = [
					$userId,
					$userFirstname . ' ' . $this->getData(['user', $userId, 'lastname']),
					helper::translate(self::$groups[$this->getData(['user', $userId, 'group'])]),
					template::button('userEdit' . $userId, [
						'href' => helper::baseUrl() . 'user/edit/' . $userId . '/' . $_SESSION['csrf'],
						'value' => template::ico('pencil'),
						'help' => 'Éditer'
					]),
					template::button('userDelete' . $userId, [
						'class' => 'userDelete buttonRed',
						'href' => helper::baseUrl() . 'user/delete/' . $userId . '/' . $_SESSION['csrf'],
						'value' => template::ico('trash'),
						'help' => 'Supprimer'
					])
				];
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Utilisateurs'),
			'view' => 'index'
		]);
	}

	/**
	 * Connexion
	 */
	public function login()
	{
		// Soumission du formulaire
		$logStatus = '';
		if ($this->isPost()) {
			// Lire Id du compte
			$userId = $this->getInput('userLoginId', helper::FILTER_ID, true);
			// Check le captcha
			if (
				$this->getData(['config', 'connect', 'captcha'])
				and password_verify($this->getInput('userLoginCaptcha', helper::FILTER_INT), $this->getInput('userLoginCaptchaResult')) === false
			) {
				$captcha = false;
			} else {
				$captcha = true;
			}
			/**
			 * Aucun compte existant
			 */
			if (!$this->getData(['user', $userId])) {
				$logStatus = 'Compte inconnu';
				//Stockage de l'IP
				$this->setData([
					'blacklist',
					$userId,
					[
						'connectFail' => $this->getData(['blacklist', $userId, 'connectFail']) + 1,
						'lastFail' => time(),
						'ip' => helper::getIp()
					]
				]);
				// Verrouillage des IP
				$ipBlackList = helper::arrayColumn($this->getData(['blacklist']), 'ip');
				if (
					$this->getData(['blacklist', $userId, 'connectFail']) >= $this->getData(['config', 'connect', 'attempt'])
					and in_array($this->getData(['blacklist', $userId, 'ip']), $ipBlackList)
				) {
					$logStatus = 'Compte inconnu verrouillé';
					// Valeurs en sortie
					$this->addOutput([
						'notification' => helper::translate('Compte verrouillé'),
						'redirect' => helper::baseUrl(),
						'state' => false
					]);
				} else {
					// Valeurs en sortie
					$this->addOutput([
						'notification' => helper::translate('Captcha, identifiant ou mot de passe incorrects')
					]);
				}
				/**
				 * Le compte existe
				 */
			} else {
				// Cas 4 : le délai de  blocage est  dépassé et le compte est au max - Réinitialiser
				if (
					$this->getData(['user', $userId, 'connectTimeout'])  + $this->getData(['config', 'connect', 'timeout']) < time()
					and $this->getData(['user', $userId, 'connectFail']) === $this->getData(['config', 'connect', 'attempt'])
				) {
					$this->setData(['user', $userId, 'connectFail', 0]);
					$this->setData(['user', $userId, 'connectTimeout', 0]);
				}
				// Check la présence des variables et contrôle du blocage du compte si valeurs dépassées
				// Vérification du mot de passe et du groupe
				if (
					($this->getData(['user', $userId, 'connectTimeout']) + $this->getData(['config', 'connect', 'timeout'])) < time()
					and $this->getData(['user', $userId, 'connectFail']) < $this->getData(['config', 'connect', 'attempt'])
					and password_verify(html_entity_decode($this->getInput('userLoginPassword', helper::FILTER_STRING_SHORT, true)), $this->getData(['user', $userId, 'password']))
					and $this->getData(['user', $userId, 'group']) >= self::GROUP_MEMBER
					and $captcha === true
				) {
					// RAZ
					$this->setData(['user', $userId, 'connectFail', 0]);
					$this->setData(['user', $userId, 'connectTimeout', 0]);
					// Expiration
					$expire = $this->getInput('userLoginLongTime', helper::FILTER_BOOLEAN) === true ? strtotime("+1 year") : 0;
					setcookie('ZWII_USER_ID', $userId, $expire, helper::baseUrl(false, false), '', helper::isHttps(), true);
					setcookie('ZWII_USER_PASSWORD', $this->getData(['user', $userId, 'password']), $expire, helper::baseUrl(false, false), '', helper::isHttps(), true);
					// Accès multiples avec le même compte
					$this->setData(['user', $userId, 'accessCsrf', $_SESSION['csrf']]);
					// Valeurs en sortie lorsque le site est en maintenance et que l'utilisateur n'est pas administrateur
					if (
						$this->getData(['config', 'maintenance'])
						and $this->getData(['user', $userId, 'group']) < self::GROUP_ADMIN
					) {
						$this->addOutput([
							'notification' => helper::translate('Seul un administrateur peut se connecter lors d\'une maintenance'),
							'redirect' => helper::baseUrl(),
							'state' => false
						]);
					} else {
						$logStatus = 'Connexion réussie';
						// Valeurs en sortie
						$this->addOutput([
							'notification' => sprintf(helper::translate('Bienvenue %s %s'), $this->getData(['user', $userId, 'firstname']), $this->getData(['user', $userId, 'lastname'])),
							'redirect' => helper::baseUrl() . str_replace('_', '/', str_replace('__', '#', $this->getUrl(2))),
							'state' => true
						]);
					}
					// Sinon notification d'échec
				} else {
					$notification = helper::translate('Captcha, identifiant ou mot de passe incorrects');
					$logStatus = $captcha === true ? 'Erreur de mot de passe' : 'Erreur de captcha';
					// Cas 1 le nombre de connexions est inférieur aux tentatives autorisées : incrément compteur d'échec
					if ($this->getData(['user', $userId, 'connectFail']) < $this->getData(['config', 'connect', 'attempt'])) {
						$this->setData(['user', $userId, 'connectFail', $this->getdata(['user', $userId, 'connectFail']) + 1]);
					}
					// Cas 2 la limite du nombre de connexion est atteinte : placer le timer
					if ($this->getdata(['user', $userId, 'connectFail']) == $this->getData(['config', 'connect', 'attempt'])) {
						$this->setData(['user', $userId, 'connectTimeout', time()]);
					}
					// Cas 3 le délai de bloquage court
					if ($this->getData(['user', $userId, 'connectTimeout'])  + $this->getData(['config', 'connect', 'timeout']) > time()) {
						$notification =  sprintf(helper::translate('Accès bloqué %d minutes'), ($this->getData(['config', 'connect', 'timeout']) / 60));
					}

					// Valeurs en sortie
					$this->addOutput([
						'notification' => $notification
					]);
				}
			}
		}
		// Journalisation
		$dataLog = helper::dateUTF8('%Y %m %d', time()) . ' - ' . helper::dateUTF8('%H:%M', time());
		$dataLog .= helper::getIp($this->getData(['config', 'connect', 'anonymousIp'])) . ';';
		$dataLog .=  empty($this->getInput('userLoginId')) ? ';' : $this->getInput('userLoginId', helper::FILTER_ID)  . ';';
		$dataLog .= $this->getUrl() . ';';
		$dataLog .= $logStatus;
		$dataLog .= PHP_EOL;
		if ($this->getData(['config', 'connect', 'log'])) {
			file_put_contents(self::DATA_DIR . 'journal.log', $dataLog, FILE_APPEND);
		}
		// Stockage des cookies
		if (!empty($_COOKIE['ZWII_USER_ID'])) {
			self::$userId = $_COOKIE['ZWII_USER_ID'];
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Connexion'),
			'view' => 'login',
			'display' => self::DISPLAY_LAYOUT_LIGHT,
		]);
	}

	/**
	 * Déconnexion
	 */
	public function logout()
	{
		helper::deleteCookie('ZWII_USER_ID');
		helper::deleteCookie('ZWII_USER_PASSWORD');
		session_destroy();
		// Valeurs en sortie
		$this->addOutput([
			'notification' => helper::translate('Déconnexion !'),
			'redirect' => helper::baseUrl(false),
			'state' => true
		]);
	}

	/**
	 * Réinitialisation du mot de passe
	 */
	public function reset()
	{
		// Accès refusé
		if (
			// L'utilisateur n'existe pas
			$this->getData(['user', $this->getUrl(2)]) === null
			// Lien de réinitialisation trop vieux
			or $this->getData(['user', $this->getUrl(2), 'forgot']) + 86400 < time()
			// Id unique incorrecte
			or $this->getUrl(3) !== md5(json_encode($this->getData(['user', $this->getUrl(2)])))
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Accès autorisé
		else {
			// Soumission du formulaire
			if ($this->isPost()) {
				// Double vérification pour le mot de passe
				if ($this->getInput('userResetNewPassword')) {
					// La confirmation ne correspond pas au mot de passe
					if ($this->getInput('userResetNewPassword', helper::FILTER_STRING_SHORT, true) !== $this->getInput('userResetConfirmPassword', helper::FILTER_STRING_SHORT, true)) {
						$newPassword = $this->getData(['user', $this->getUrl(2), 'password']);
						self::$inputNotices['userResetConfirmPassword'] = 'Incorrect';
					} else {
						$newPassword = $this->getInput('userResetNewPassword', helper::FILTER_PASSWORD, true);
					}
					// Modifie le mot de passe
					$this->setData(['user', $this->getUrl(2), 'password', $newPassword]);
					// Réinitialise la date de la demande
					$this->setData(['user', $this->getUrl(2), 'forgot', 0]);
					// Réinitialise le blocage
					$this->setData(['user', $this->getUrl(2), 'connectFail', 0]);
					$this->setData(['user', $this->getUrl(2), 'connectTimeout', 0]);
					// Valeurs en sortie
					$this->addOutput([
						'notification' => helper::translate('Nouveau mot de passe enregistré'),
						//'redirect' => helper::baseUrl() . 'user/login/' . str_replace('/', '_', $this->getUrl()),
						'redirect' => helper::baseUrl(),
						'state' => true
					]);
				}
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => helper::translate('Réinitialisation du mot de passe'),
				'view' => 'reset',
				'display' => self::DISPLAY_LAYOUT_LIGHT,

			]);
		}
	}

	/**
	 * Importation CSV d'utilisateurs
	 */
	public function import()
	{
		// Soumission du formulaire
		$notification = '';
		$success = true;
		if ($this->isPost()) {
			// Lecture du CSV et construction du tableau
			$file = $this->getInput('userImportCSVFile', helper::FILTER_STRING_SHORT, true);
			$filePath = self::FILE_DIR . 'source/' . $file;
			if ($file and file_exists($filePath)) {
				// Analyse et extraction du CSV
				$rows   = array_map(function ($row) {
					return str_getcsv($row, $this->getInput('userImportSeparator'));
				}, file($filePath));
				$header = array_shift($rows);
				$csv    = array();
				foreach ($rows as $row) {
					$csv[] = array_combine($header, $row);
				}
				// Traitement des données
				foreach ($csv as $item) {
					// Données valides
					if (
						array_key_exists('id', $item)
						and array_key_exists('prenom', $item)
						and array_key_exists('nom', $item)
						and array_key_exists('groupe', $item)
						and array_key_exists('email', $item)
						and $item['nom']
						and $item['prenom']
						and $item['id']
						and $item['email']
						and $item['groupe']
					) {
						// Validation du groupe
						$item['groupe'] = (int) $item['groupe'];
						$item['groupe'] =   ($item['groupe'] >= self::GROUP_BANNED and $item['groupe'] <= self::GROUP_ADMIN)
							? $item['groupe'] : 1;
						// L'utilisateur existe
						if ($this->getData(['user', helper::filter($item['id'], helper::FILTER_ID)])) {
							// Notification du doublon
							$item['notification'] = template::ico('cancel');
							// Création du tableau de confirmation
							self::$users[] = [
								helper::filter($item['id'], helper::FILTER_ID),
								$item['nom'],
								$item['prenom'],
								self::$groups[$item['groupe']],
								$item['prenom'],
								helper::filter($item['email'], helper::FILTER_MAIL),
								$item['notification']
							];
							// L'utilisateur n'existe pas
						} else {
							// Nettoyage de l'identifiant
							$userId = helper::filter($item['id'], helper::FILTER_ID);
							// Enregistre le user
							$create = $this->setData([
								'user',
								$userId, [
									'firstname' => $item['prenom'],
									'forgot' => 0,
									'group' => $item['groupe'],
									'lastname' => $item['nom'],
									'mail' => $item['email'],
									'pseudo' => $item['prenom'],
									'signature' => 1, // Pseudo
									'password' => uniqid(), // A modifier à la première connexion
									"connectFail" => null,
									"connectTimeout" => null,
									"accessUrl" => null,
									"accessTimer" => null,
									"accessCsrf" => null
								]
							]);
							// Icône de notification
							$item['notification'] = $create  ? template::ico('check') : template::ico('cancel');
							// Envoi du mail
							if (
								$create
								and $this->getInput('userImportNotification', helper::FILTER_BOOLEAN) === true
							) {
								$sent = $this->sendMail(
									$item['email'],
									'Compte créé sur ' . $this->getData(['locale', 'title']),
									'Bonjour <strong>' . $item['prenom'] . ' ' . $item['nom'] . '</strong>,<br><br>' .
										'Un administrateur vous a créé un compte sur le site ' . $this->getData(['locale', 'title']) . '. Vous trouverez ci-dessous les détails de votre compte.<br><br>' .
										'<strong>Identifiant du compte :</strong> ' . $userId . '<br>' .
										'<small>Un mot de passe provisoire vous été attribué, à la première connexion cliquez sur Mot de passe Oublié.</small>',
										null,
										$this->getData(['config', 'smtp', 'from']),
								);
								if ($sent === true) {
									// Mail envoyé changement de l'icône
									$item['notification'] = template::ico('mail');
								}
							}
							// Création du tableau de confirmation
							self::$users[] = [
								$userId,
								$item['nom'],
								$item['prenom'],
								self::$groups[$item['groupe']],
								$item['prenom'],
								$item['email'],
								$item['notification']
							];
						}
					}
				}
				if (empty(self::$users)) {
					$notification =  helper::translate('Rien à importer, erreur de format ou fichier incorrect');
					$success = false;
				} else {
					$notification =  helper::translate('Importation effectuée');
					$success = true;
				}
			} else {
				$notification = helper::translate('Erreur de lecture, vérifiez les permissions');
				$success = false;
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Importation d\'utilisateurs',
			'view' => 'import',
			'notification' => $notification,
			'state' => $success
		]);
	}

	/** 
	 * Télécharge un modèle
	*/
	public function template() {
		$file = 'template.csv';
		$path = 'core/module/user/ressource/';
		// Téléchargement du CSV
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="' . $file . '"');
		header('Content-Length: ' . filesize($path . $file));
		readfile($path . $file);
		exit();
	}
}
