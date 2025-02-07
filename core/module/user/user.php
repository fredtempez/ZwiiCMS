<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class user extends common
{

	public static $actions = [
		'add' => self::ROLE_ADMIN,
		'delete' => self::ROLE_ADMIN,
		'import' => self::ROLE_ADMIN,
		'index' => self::ROLE_ADMIN,
		'template' => self::ROLE_ADMIN,
		'edit' => self::ROLE_MEMBER,
		'logout' => self::ROLE_MEMBER,
		'forgot' => self::ROLE_VISITOR,
		'login' => self::ROLE_VISITOR,
		'auth' => self::ROLE_VISITOR,
		'reset' => self::ROLE_VISITOR,
		'profil' => self::ROLE_ADMIN,
		'profilEdit' => self::ROLE_ADMIN,
		'profilAdd' => self::ROLE_ADMIN,
		'profilDelete' => self::ROLE_ADMIN,
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

	public static $userGroups = [];

	public static $userProfils = [];
	public static $userProfilsComments = [];

	public static $userLongtime = false;

	public static $separators = [
		';' => ';',
		',' => ',',
		':' => ':'
	];

	public static $languagesInstalled = [];

	public static $sharePath = [
		'/site/file/source/'
	];

	public static $roleProfils = [
		self::ROLE_MEMBER => 'Membre',
		self::ROLE_EDITOR => 'Éditeur',
	];

	public static $listModules = [];

	public static $profils = [];

	public static $alphabet = [];

	public static $usersGroups = [
		'all' => 'Tous'
	];

	/**
	 * Ajout
	 */
	public function add()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// L'identifiant d'utilisateur est indisponible
			$userId = $this->getInput('userAddId', helper::FILTER_ID, true);
			if ($this->getData(['user', $userId])) {
				self::$inputNotices['userAddId'] = 'Identifiant déjà utilisé';
			}
			// Double vérification pour le mot de passe
			if ($this->getInput('userAddPassword', helper::FILTER_STRING_SHORT, true) !== $this->getInput('userAddConfirmPassword', helper::FILTER_STRING_SHORT, true)) {
				self::$inputNotices['userAddConfirmPassword'] = 'Les mots de passe ne sont pas indentiques';
			}

			// Crée l'utilisateur
			$userFirstname = $this->getInput('userAddFirstname', helper::FILTER_STRING_SHORT, true);
			$userLastname = $this->getInput('userAddLastname', helper::FILTER_STRING_SHORT, true);
			$userMail = $this->getInput('userAddMail', helper::FILTER_MAIL, true);
			$pseudo = $this->getInput('userAddPseudo', helper::FILTER_STRING_SHORT, true);
			$signature = $this->getInput('userAddSignature', helper::FILTER_INT, true);
			$password = $this->getInput('userAddPassword', helper::FILTER_PASSWORD, true);

			// Profil
			$role = $this->getInput('userAddGroup', helper::FILTER_INT, true);
			$profil = 0;
			if ($role === 1 || $role === 2) {
				$profil = $this->getInput('userAddProfil' . $role, helper::FILTER_INT);
			}

			// Stockage des données
			$this->setData([
				'user',
				$userId,
				[
					'firstname' => $userFirstname,
					'forgot' => 0,
					'role' => $role,
					'profil' => $profil,
					'lastname' => $userLastname,
					'pseudo' => $pseudo,
					'signature' => $signature,
					'mail' => $userMail,
					'password' => $password,
					'connectFail' => null,
					'connectTimeout' => null,
					'accessUrl' => null,
					'accessTimer' => null,
					'accessCsrf' => null,
					'language' => $this->getInput('userAddLanguage', helper::FILTER_STRING_SHORT),
					'tags' => $this->getInput('userAddTags', helper::FILTER_STRING_SHORT),
				]
			]);

			// Envoie le mail
			$sent = true;
			if (
				$this->getInput('userAddSendMail', helper::FILTER_BOOLEAN) &&
				self::$inputNotices === []
			) {
				$sent = $this->sendMail(
					$userMail,
					'Compte créé sur ' . $this->getData(['config', 'title']),
					'Bonjour <strong>' . $userFirstname . ' ' . $userLastname . '</strong>,<br><br>' .
					'Un administrateur vous a créé un compte sur le site ' . $this->getData(['config', 'title']) . '. Vous trouverez ci-dessous les détails de votre compte.<br><br>' .
					'<strong>Identifiant du compte :</strong> ' . $this->getInput('userAddId') . '<br>' .
					'<small>Nous ne conservons pas les mots de passe, en conséquence nous vous conseillons de conserver ce message tant que vous ne vous êtes pas connecté. Vous pourrez modifier votre mot de passe après votre première connexion.</small>',
					null,
					$this->getData(['config', 'smtp', 'from'])
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
		self::$languagesInstalled = $this->getData(['language']);
		if (self::$languagesInstalled) {
			foreach (self::$languagesInstalled as $lang => $datas) {
				self::$languagesInstalled[$lang] = self::$languages[$lang];
			}
		}

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
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true ||
			// L'utilisateur n'existe pas
			$this->getData(['user', $this->getUrl(2)]) === null
			// Role insuffisant
			and ($this->getUrl('role') < self::ROLE_EDITOR)
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
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
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			if (
				// L'utilisateur n'existe pas
				$this->getData(['user', $this->getUrl(2)]) === null
				// Droit d'édition
				and (
						// Impossible de s'auto-éditer
					($this->getUser('id') === $this->getUrl(2)
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
					$this->isPost()
				) {
					$oldPassword = $this->getData(['user', $this->getUrl(2), 'password']);
					// Double vérification pour le mot de passe
					if ($this->getUser('role') < self::ROLE_ADMIN) {
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
					} else {
						if (
							!empty($this->getInput('userEditNewPassword'))
							&& $this->getInput('userEditNewPassword') === $this->getInput('userEditConfirmPassword')
						) {
							$newPassword = $this->getInput('userEditNewPassword', helper::FILTER_PASSWORD);
							// Déconnexion de l'utilisateur si il change le mot de passe de son propre compte
							if ($this->getUser('id') === $this->getUrl(2)) {
								helper::deleteCookie('ZWII_USER_ID');
								helper::deleteCookie('ZWII_USER_PASSWORD');
							}
						}
					}

					// Modification du rôle
					if (
						$this->getUser('role') === self::ROLE_ADMIN
						and $this->getUrl(2) !== $this->getUser('id')
					) {
						$newGroup = $this->getInput('userEditGroup', helper::FILTER_INT, true);
					} else {
						$newGroup = $this->getData(['user', $this->getUrl(2), 'role']);
					}
					// Modification de nom Prénom
					if ($this->getUser('role') === self::ROLE_ADMIN) {
						$newfirstname = $this->getInput('userEditFirstname', helper::FILTER_STRING_SHORT, true);
						$newlastname = $this->getInput('userEditLastname', helper::FILTER_STRING_SHORT, true);
					} else {
						$newfirstname = $this->getData(['user', $this->getUrl(2), 'firstname']);
						$newlastname = $this->getData(['user', $this->getUrl(2), 'lastname']);
					}
					// Profil
					$profil = 0;
					if ($newGroup === 1 || $newGroup === 2) {
						$profil = $this->getInput('userEditProfil' . $newGroup, helper::FILTER_INT);
					}
					// Modifie l'utilisateur
					$this->setData([
						'user',
						$this->getUrl(2),
						[
							'firstname' => $newfirstname,
							'forgot' => 0,
							'role' => $newGroup,
							'profil' => $profil,
							'lastname' => $newlastname,
							'pseudo' => $this->getInput('userEditPseudo', helper::FILTER_STRING_SHORT, true),
							'signature' => $this->getInput('userEditSignature', helper::FILTER_INT, true),
							'mail' => $this->getInput('userEditMail', helper::FILTER_MAIL, true),
							'password' => empty($newPassword) ? $oldPassword : $newPassword,
							'connectFail' => $this->getData(['user', $this->getUrl(2), 'connectFail']),
							'connectTimeout' => $this->getData(['user', $this->getUrl(2), 'connectTimeout']),
							'accessUrl' => $this->getData(['user', $this->getUrl(2), 'accessUrl']),
							'accessTimer' => $this->getData(['user', $this->getUrl(2), 'accessTimer']),
							'accessCsrf' => $this->getData(['user', $this->getUrl(2), 'accessCsrf']),
							'files' => $this->getInput('userEditFiles', helper::FILTER_BOOLEAN),
							'language' => $this->getInput('userEditLanguage', helper::FILTER_STRING_SHORT),
							'tags' => $this->getInput('userEditTags', helper::FILTER_STRING_SHORT),
							'authKey' => $this->getData(['user', $this->getUrl(2), 'authKey']),
						]
					]);
					// Redirection spécifique si l'utilisateur change son mot de passe
					if ($this->getUser('id') === $this->getUrl(2) and $this->getInput('userEditNewPassword')) {
						$redirect = helper::baseUrl() . 'user/login/' . str_replace('/', '_', $this->getUrl());
					}
					// Redirection si retour en arrière possible
					elseif ($this->getUser('role') === self::ROLE_ADMIN) {
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
				self::$languagesInstalled = $this->getData(['language']);
				if (self::$languagesInstalled) {
					foreach (self::$languagesInstalled as $lang => $datas) {
						self::$languagesInstalled[$lang] = self::$languages[$lang];
					}
				}

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
					'title' => $this->getData(['user', $this->getUrl(2), 'firstname']) . ' ' . $this->getData(['user', $this->getUrl(2), 'lastname']),
					'view' => 'edit'
				]);
			}
		}
	}

	/**
	 * Mot de passe perdu
	 */
	public function forgot()
	{
		// Soumission du formulaire
		if (
			$this->isPost()
		) {
			$userId = $this->getInput('userForgotId', helper::FILTER_ID, true);
			$sent = false;
			if ($this->getData(['user', $userId])) {
				// Enregistre la date de la demande dans le compte utilisateur
				$this->setData(['user', $userId, 'forgot', time()]);
				// Crée un id unique pour la réinitialisation
				$uniqId = md5(json_encode($this->getData(['user', $userId, 'forgot'])));
				// Envoi le mail
				$sent = $this->sendMail(
					$this->getData(['user', $userId, 'mail']),
					'Réinitialisation de votre mot de passe',
					'Bonjour <strong>' . $this->getData(['user', $userId, 'firstname']) . ' ' . $this->getData(['user', $userId, 'lastname']) . '</strong>,<br><br>' .
					'Vous avez demandé à changer le mot de passe lié à votre compte. Vous trouverez ci-dessous un lien vous permettant de modifier celui-ci.<br><br>' .
					'<a href="' . helper::baseUrl() . 'user/reset/' . $userId . '/' . $uniqId . '" target="_blank">' . helper::baseUrl() . 'user/reset/' . $userId . '/' . $uniqId . '</a><br><br>' .
					'<small>Si nous n\'avez pas demandé à réinitialiser votre mot de passe, veuillez ignorer ce mail.</small>',
					null,
					$this->getData(['config', 'smtp', 'from'])
				);

			}
			// Valeurs en sortie
			$this->addOutput([
				'notification' => $sent === true ? helper::translate('Un mail a été envoyé pour confirmer la réinitialisation') : helper::translate('Le mail de réinitialisation ne peut pas être envoyé, contactez l\'administrateur'),
				'state' => ($sent === true ? true : false),
				'redirect' => helper::baseUrl()
			]);
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
		// Liste des rôles et des profils
		$usersGroups = $this->getData(['profil']);
		foreach ($usersGroups as $roleId => $roleValue) {
			switch ($roleId) {
				case "-1":
				case "0":
					break;
				case "3":
					self::$usersGroups['30'] = 'Administrateur';
					$profils['30'] = 0;
					break;
				case "1":
				case "2":
					foreach ($roleValue as $profilId => $profilValue) {
						if ($profilId) {
							self::$usersGroups[$roleId . $profilId] = sprintf(helper::translate('Rôle %s - Profil %s'), self::$rolePublics[$roleId], $profilValue['name']);
							$profils[$roleId . $profilId] = 0;
						}
					}
			}
		}

		// Liste alphabétique
		self::$alphabet = range('A', 'Z');
		$alphabet = range('A', 'Z');
		self::$alphabet = array_combine($alphabet, self::$alphabet);
		self::$alphabet = array_merge(['all' => 'Toute'], self::$alphabet);

		// Liste des membres
		$userIdsLastNames = helper::arrayColumn($this->getData(['user']), 'lastname');
		ksort($userIdsLastNames);
		foreach ($userIdsLastNames as $userId => $userLastNames) {
			if ($this->getData(['user', $userId, 'role'])) {

				// Compte les rôles
				if (isset($profils[$this->getData(['user', $userId, 'role']) . $this->getData(['user', $userId, 'profil'])])) {
					$profils[$this->getData(['user', $userId, 'role']) . $this->getData(['user', $userId, 'profil'])]++;
				}

				// Filtres
				if ($this->isPost()) {
					// Role et profils
					$role = (string) $this->getData(['user', $userId, 'role']);
					$profil = (string) $this->getData(['user', $userId, 'profil']);
					$firstName = $this->getData(['user', $userId, 'firstname']);
					$lastName = $this->getData(['user', $userId, 'lastname']);
					if (
						$this->getInput('userFilterGroup', helper::FILTER_INT) > 0
						&& $this->getInput('userFilterGroup', helper::FILTER_STRING_SHORT) !== $role . $profil
					)
						continue;
					// Première lettre du prénom
					if (
						$this->getInput('userFilterFirstName', helper::FILTER_STRING_SHORT) !== 'all'
						&& $this->getInput('userFilterFirstName', helper::FILTER_STRING_SHORT) !== strtoupper(substr($firstName, 0, 1))
					)
						continue;
					// Première lettre du nom
					if (
						$this->getInput('userFilterLastName', helper::FILTER_STRING_SHORT) !== 'all'
						&& $this->getInput('userFilterLastName', helper::FILTER_STRING_SHORT) !== strtoupper(substr($lastName, 0, 1))
					)
						continue;
				}



				// Formatage de la liste
				self::$users[] = [
					$userId,
					sprintf('%s %s',$userLastNames, $this->getData(['user', $userId, 'firstname'])),
					helper::translate(self::$roles[(int) $this->getData(['user', $userId, 'role'])]),
					empty($this->getData(['profil', $this->getData(['user', $userId, 'role']), $this->getData(['user', $userId, 'profil']), 'name']))
					? helper::translate(self::$roles[(int) $this->getData(['user', $userId, 'role'])])
					: $this->getData(['profil', $this->getData(['user', $userId, 'role']), $this->getData(['user', $userId, 'profil']), 'name']),
					$this->getData(['user', $userId, 'tags']),
					template::button('userEdit' . $userId, [
						'href' => helper::baseUrl() . 'user/edit/' . $userId,
						'value' => template::ico('pencil'),
						'help' => 'Éditer'
					]),
					template::button('userDelete' . $userId, [
						'class' => 'userDelete buttonRed',
						'href' => helper::baseUrl() . 'user/delete/' . $userId,
						'value' => template::ico('trash'),
						'help' => 'Supprimer'
					])
				];

			}
		}

		// Ajoute les effectifs aux profils du sélecteur
		foreach (self::$usersGroups as $roleId => $roleValue) {
			if ($roleId === 'all') {
				self::$usersGroups['all'] = self::$usersGroups['all'] . ' (' . array_sum($profils) . ')';
			} else {
				self::$usersGroups[$roleId] = self::$usersGroups[$roleId] . ' (' . $profils[$roleId] . ')';
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Utilisateurs'),
			'view' => 'index',
			'vendor' => [
				'datatables'
			]
		]);
	}

	/**
	 * Table des rôles
	 */
	public function profil()
	{

		// Ne pas supprimer un profil utililsé
		// recherche les membres du rôle 
		$roles = helper::arrayColumn($this->getData(['user']), 'role');
		$roles = array_keys($roles, $this->getUrl(2));
		$profilUsed = true;
		// Stoppe si le profil est affecté
		foreach ($roles as $userId) {
			if ((string) $this->getData(['user', $userId, 'profil']) === $this->getUrl(3)) {
				$profilUsed = false;
			}
		}
		foreach ($this->getData(['profil']) as $roleId => $roleData) {
			// Membres sans permissions spécifiques
			if (
				$roleId == self::ROLE_BANNED ||
				$roleId == self::ROLE_VISITOR ||
				$roleId == self::ROLE_ADMIN
			) {
				self::$userGroups[$roleId] = [
					$roleId,
					helper::translate($roleData['name']),
					nl2br(helper::translate($roleData['comment'])),
					template::button('profilEdit' . $roleId, [
						'value' => template::ico('pencil'),
						'help' => 'Éditer',
						'disabled' => $roleData['readonly'],
					]),
					template::button('profilDelete' . $roleId, [
						'value' => template::ico('trash'),
						'help' => 'Supprimer',
						'disabled' => $roleData['readonly'],
					])
				];
			} elseif (
				$roleId == self::ROLE_MEMBER ||
				$roleId == self::ROLE_EDITOR
			) {
				// Enumérer les sous rôles MEMBER et MODERATOR
				foreach ($roleData as $profilId => $profilData) {
					self::$userGroups[$roleId . '.' . $profilId] = [
						$roleId . '-' . $profilId,
						helper::translate(self::$roles[$roleId]) . '<br />Profil : ' . helper::translate($profilData['name']),
						nl2br(helper::translate($profilData['comment'])),
						template::button('profilEdit' . $roleId . $profilId, [
							'href' => helper::baseUrl() . 'user/profilEdit/' . $roleId . '/' . $profilId,
							'value' => template::ico('pencil'),
							'help' => 'Éditer',
							'disabled' => $profilData['readonly'],
						]),
						template::button('profilDelete' . $roleId . $profilId, [
							'class' => 'profilDelete buttonRed',
							'href' => helper::baseUrl() . 'user/profilDelete/' . $roleId . '/' . $profilId,
							'value' => template::ico('trash'),
							'help' => 'Supprimer',
							'disabled' => $profilData['permanent'] && $profilUsed,
						])
					];
				}
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Profils des rôles'),
			'view' => 'profil'
		]);
	}

	/**
	 * Edition d'un rôle
	 */
	public function profilEdit()
	{

		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {

			// Effacer les données du numéro de profil ancien
			$role = $this->getInput('profilEditGroup', helper::FILTER_STRING_SHORT, true);
			// Les profils 1 sont désactivés dans le formulaire
			$profil = empty($this->getInput('profilEditProfil')) ? '1' : $this->getInput('profilEditProfil');
			$oldProfil = $this->getInput('profilEditOldProfil', helper::FILTER_STRING_SHORT);
			// Gère le chemin
			$fileManager = $this->getInput('profilEditFileManager', helper::FILTER_BOOLEAN);
			$path = $this->getInput('profilEditPath');
			if (
				$role < self::ROLE_MEMBER
				&& $fileManager
				&& empty($path)
			) {
				$fileManager = false;
			}
			// Effacer l'ancien profil si le rang a changé
			if (
				$profil !== $oldProfil &&
				$this->deleteData(['profil', $role, $oldProfil])
			) {
				$this->deleteData(['profil', $role, $oldProfil]);
			}
			// Données du formulaire
			$data = [
				'name' => $this->getInput('profilEditName', helper::FILTER_STRING_SHORT, true),
				'readonly' => false,
				'permanent' => $profil === '1' ? true : false,
				'comment' => $this->getInput('profilEditComment', helper::FILTER_STRING_SHORT, true),
				'filemanager' => $fileManager,
				'file' => [
					'download' => $this->getInput('profilEditDownload', helper::FILTER_BOOLEAN),
					'edit' => $this->getInput('profilEditEdit', helper::FILTER_BOOLEAN),
					'create' => $this->getInput('profilEditCreate', helper::FILTER_BOOLEAN),
					'rename' => $this->getInput('profilEditRename', helper::FILTER_BOOLEAN),
					'upload' => $this->getInput('profilEditUpload', helper::FILTER_BOOLEAN),
					'delete' => $this->getInput('profilEditDelete', helper::FILTER_BOOLEAN),
					'preview' => $this->getInput('profilEditPreview', helper::FILTER_BOOLEAN),
					'duplicate' => $this->getInput('profilEditDuplicate', helper::FILTER_BOOLEAN),
					'extract' => $this->getInput('profilEditExtract', helper::FILTER_BOOLEAN),
					'copycut' => $this->getInput('profilEditCopycut', helper::FILTER_BOOLEAN),
					'chmod' => $this->getInput('profilEditChmod', helper::FILTER_BOOLEAN),
				],
				'folder' => [
					'create' => $this->getInput('profilEditFolderCreate', helper::FILTER_BOOLEAN),
					'delete' => $this->getInput('profilEditFolderDelete', helper::FILTER_BOOLEAN),
					'rename' => $this->getInput('profilEditFolderRename', helper::FILTER_BOOLEAN),
					'copycut' => $this->getInput('profilEditFolderCopycut', helper::FILTER_BOOLEAN),
					'chmod' => $this->getInput('profilEditFolderChmod', helper::FILTER_BOOLEAN),
					'path' => preg_replace('/^\\./', '', $path), // Supprime le point pour préserver le chemin
				],
				'page' => [
					'add' => $this->getInput('profilEditPageAdd', helper::FILTER_BOOLEAN),
					'edit' => $this->getInput('profilEditPageEdit', helper::FILTER_BOOLEAN),
					'delete' => $this->getInput('profilEditPageDelete', helper::FILTER_BOOLEAN),
					'duplicate' => $this->getInput('profilEditPageDuplicate', helper::FILTER_BOOLEAN),
					'module' => $this->getInput('profilEditPageModule', helper::FILTER_BOOLEAN),
					'cssEditor' => $this->getInput('profilEditPagecssEditor', helper::FILTER_BOOLEAN),
					'jsEditor' => $this->getInput('profilEditPagejsEditor', helper::FILTER_BOOLEAN),
				],
				'user' => [
					'edit' => $this->getInput('profilEditUserEdit', helper::FILTER_BOOLEAN),
				]
			];

			// Données des modules
			$dataModules = helper::getModules();
			if (is_array($dataModules)) {
				foreach ($dataModules as $moduleId => $moduleValue) {
					if (file_exists('module/' . $moduleId . '/profil/main/edit.inc.php')) {
						include('module/' . $moduleId . '/profil/main/edit.inc.php');
						if (is_array($moduleData[$moduleId])) {
							$data = array_merge($data, [$moduleId => $moduleData[$moduleId]]);
						}
					}
				}
			}

			//Sauvegarder le données
			$this->setData([
				'profil',
				$role,
				$profil,
				$data
			]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'user/profil',
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}

		// Chemin vers les dossiers du gestionnaire de fichier
		self::$sharePath = $this->getSubdirectories('site/file/source');
		self::$sharePath = array_flip(self::$sharePath);
		self::$sharePath = array_merge(['site/file/source/' => 'Tous les dossiers'], self::$sharePath);
		self::$sharePath = array_merge([null => 'Aucun dossier'], self::$sharePath);

		// Liste des modules installés
		self::$listModules = helper::getModules();
		self::$listModules = array_keys(self::$listModules);

		// Charge les dialogues du module pour afficher les traductions
		foreach (self::$listModules as $moduleId) {
			if (
				is_dir(self::MODULE_DIR . $moduleId . '/i18n')
				&& file_exists(self::MODULE_DIR . $moduleId . '/i18n/' . self::$i18nUI . '.json')
			) {
				$d = json_decode(file_get_contents(self::MODULE_DIR . $moduleId . '/i18n/' . self::$i18nUI . '.json'), true);
				self::$dialog = array_merge(self::$dialog, $d);
			}
		}
		// Tri alphabétique
		sort(self::$listModules);

		/**
		 * Génération des profils disponibles
		 * Tableau des profils attribués
		 * Extraire les numéros de profils
		 * Générer un tableau $p des profils possibles selon le plafond
		 * Ne garder que la différence sauf le profil de l'utilisateur édité que l'on ajoute
		 */
		self::$profils = $this->getData(['profil', $this->getUrl(2)]);
		// Supprime le profil utilisateur 
		unset(self::$profils[$this->getUrl(3)]);
		self::$profils = array_keys(self::$profils);
		$p = range(1, self::MAX_PROFILS - 1);
		self::$profils = array_diff($p, self::$profils);
		sort(self::$profils);
		// Restructure le tableau pour faire correspondre la clé et la valeur
		$p = array();
		foreach (self::$profils as $key => $value) {
			$p[$value] = $value;
		}
		self::$profils = $p;

		// Valeurs en sortie;
		$this->addOutput([
			'title' => sprintf(helper::translate('Édition du profil %s'), $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'name'])),
			'view' => 'profilEdit'
		]);
	}

	/**
	 * Ajouter un profil de permission
	 */

	public function profilAdd()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Nombre de profils de ce rôle
			$role = $this->getInput('profilAddGroup');
			$profil = count($this->getData(['profil', $role]));
			// Gère le chemin
			$fileManager = $this->getInput('profilAddFileManager', helper::FILTER_BOOLEAN);
			$path = $this->getInput('profilAddPath');
			if (
				$role < self::ROLE_MEMBER
				&& $fileManager
				&& empty($path)
			) {
				$fileManager = false;
			}

			if ($profil < self::MAX_PROFILS) {
				$profil = (string) ($profil + 1);
				// Données du formulaire
				$data = [
					'name' => $this->getInput('profilAddName', helper::FILTER_STRING_SHORT, true),
					'readonly' => false,
					'permanent' => false,
					'comment' => $this->getInput('profilAddComment', helper::FILTER_STRING_SHORT, true),
					'filemanager' => $fileManager,
					'file' => [
						'download' => $this->getInput('profilAddDownload', helper::FILTER_BOOLEAN),
						'edit' => $this->getInput('profilAddEdit', helper::FILTER_BOOLEAN),
						'create' => $this->getInput('profilAddCreate', helper::FILTER_BOOLEAN),
						'rename' => $this->getInput('profilAddRename', helper::FILTER_BOOLEAN),
						'upload' => $this->getInput('profilAddUpload', helper::FILTER_BOOLEAN),
						'delete' => $this->getInput('profilAddDelete', helper::FILTER_BOOLEAN),
						'preview' => $this->getInput('profilAddPreview', helper::FILTER_BOOLEAN),
						'duplicate' => $this->getInput('profilAddDuplicate', helper::FILTER_BOOLEAN),
						'extract' => $this->getInput('profilAddExtract', helper::FILTER_BOOLEAN),
						'copycut' => $this->getInput('profilAddCopycut', helper::FILTER_BOOLEAN),
						'chmod' => $this->getInput('profilAddChmod', helper::FILTER_BOOLEAN),
					],
					'folder' => [
						'create' => $this->getInput('profilAddFolderCreate', helper::FILTER_BOOLEAN),
						'delete' => $this->getInput('profilAddFolderDelete', helper::FILTER_BOOLEAN),
						'rename' => $this->getInput('profilAddFolderRename', helper::FILTER_BOOLEAN),
						'copycut' => $this->getInput('profilAddFolderCopycut', helper::FILTER_BOOLEAN),
						'chmod' => $this->getInput('profilAddFolderChmod', helper::FILTER_BOOLEAN),
						'path' => preg_replace('/^\\./', '', $path), // Supprime le point pour préserver le chemin
					],
					'page' => [
						'add' => $this->getInput('profilAddPageAdd', helper::FILTER_BOOLEAN),
						'edit' => $this->getInput('profilAddPageEdit', helper::FILTER_BOOLEAN),
						'delete' => $this->getInput('profilAddPageDelete', helper::FILTER_BOOLEAN),
						'duplicate' => $this->getInput('profilAddPageDuplicate', helper::FILTER_BOOLEAN),
						'module' => $this->getInput('profilAddPageModule', helper::FILTER_BOOLEAN),
						'cssEditor' => $this->getInput('profilAddPagecssEditor', helper::FILTER_BOOLEAN),
						'jsEditor' => $this->getInput('profilAddPagejsEditor', helper::FILTER_BOOLEAN),
					],
					'user' => [
						'edit' => $this->getInput('profilAddUserEdit', helper::FILTER_BOOLEAN),
					]
				];

				// Données des modules
				$dataModules = helper::getModules();
				if (is_array($dataModules)) {
					foreach ($dataModules as $moduleId => $moduleValue) {
						if (file_exists('module/' . $moduleId . '/profil/main/add.inc.php')) {
							include('module/' . $moduleId . '/profil/main/add.inc.php');
							if (is_array($moduleData[$moduleId])) {
								$data = array_merge($data, [$moduleId => $moduleData[$moduleId]]);
							}
						}
					}
				}

				// Sauvegarder les données
				$this->setData([
					'profil',
					$role,
					$profil,
					$data
				]);
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'user/profil',
					'notification' => helper::translate('Modifications enregistrées'),
					'state' => true
				]);
			} else {

				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'user/profil',
					'notification' => helper::translate('Nombre de profils maximum : ') . self::MAX_PROFILS,
					'state' => false
				]);
			}
		}

		// Chemin vers les dossiers du gestionnaire de fichier
		self::$sharePath = $this->getSubdirectories('site/file/source');
		self::$sharePath = array_flip(self::$sharePath);
		self::$sharePath = array_merge(['site/file/source/' => 'Tous les dossiers'], self::$sharePath);
		self::$sharePath = array_merge([null => 'Aucun dossier'], self::$sharePath);

		// Liste des modules installés
		self::$listModules = helper::getModules();
		self::$listModules = array_keys(self::$listModules);

		// Charge les dialogues du module pour afficher les traductions
		foreach (self::$listModules as $moduleId) {
			if (
				is_dir(self::MODULE_DIR . $moduleId . '/i18n')
				&& file_exists(self::MODULE_DIR . $moduleId . '/i18n/' . self::$i18nUI . '.json')
			) {
				$d = json_decode(file_get_contents(self::MODULE_DIR . $moduleId . '/i18n/' . self::$i18nUI . '.json'), true);
				self::$dialog = array_merge(self::$dialog, $d);
			}
		}
		// Tri alphabétique
		sort(self::$listModules);

		// Valeurs en sortie;
		$this->addOutput([
			'title' => "Ajouter un profil",
			'view' => 'profilAdd'
		]);
	}

	/**
	 * Effacement de profil
	 */

	public function profilDelete()
	{
		// Ne pas supprimer un profil utililsé
		// recherche les membres du rôle 
		$roles = helper::arrayColumn($this->getData(['user']), 'role');
		$roles = array_keys($roles, $this->getUrl(2));
		$flag = true;
		// Stoppe si le profil est affecté
		foreach ($roles as $userId) {
			if ((string) $this->getData(['user', $userId, 'profil']) === $this->getUrl(3)) {
				$flag = false;
			}
		}
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true ||
			$this->getData(['profil', $this->getUrl(2), $this->getUrl(3)]) === null ||
			$this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'permanent']) === true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
			// Suppression
		} else {
			if ($flag) {
				$this->deleteData(['profil', $this->getUrl(2), $this->getUrl(3)]);
			}

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/profil',
				'notification' => $flag ? helper::translate('Profil supprimé') : helper::translate('Action interdite'),
				'state' => $flag
			]);
		}
	}

	/**
	 * Connexion
	 */
	public function login()
	{
		// Soumission du formulaire
		$logStatus = '';
		if (
			$this->isPost()
		) {
			// Lire Id du compte
			$userId = $this->getInput('userLoginId', helper::FILTER_ID, true);
			$notification = '';
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
					$this->getData(['user', $userId, 'connectTimeout']) + $this->getData(['config', 'connect', 'timeout']) < time()
					and $this->getData(['user', $userId, 'connectFail']) === $this->getData(['config', 'connect', 'attempt'])
				) {
					$this->setData(['user', $userId, 'connectFail', 0], false);
					$this->setData(['user', $userId, 'connectTimeout', 0], false);
				}
				// Check la présence des variables et contrôle du blocage du compte si valeurs dépassées
				// Vérification du mot de passe et du rôle
				if (
					($this->getData(['user', $userId, 'connectTimeout']) + $this->getData(['config', 'connect', 'timeout'])) < time()
					and $this->getData(['user', $userId, 'connectFail']) < $this->getData(['config', 'connect', 'attempt'])
					and password_verify(html_entity_decode($this->getInput('userLoginPassword', helper::FILTER_STRING_SHORT, true)), $this->getData(['user', $userId, 'password']))
					and $this->getData(['user', $userId, 'role']) >= self::ROLE_MEMBER
					and $captcha === true
				) {

					// RAZ des compteurs de blocage
					$this->setData(['user', $userId, 'connectFail', 0], false);
					$this->setData(['user', $userId, 'connectTimeout', 0], false);

					// Accès multiples avec le même compte
					$this->setData(['user', $userId, 'accessCsrf', $_SESSION['csrf']], false);

					// Valeurs en sortie lorsque le site est en maintenance et que l'utilisateur n'est pas administrateur
					if (
						$this->getData(['config', 'maintenance'])
						and $this->getData(['user', $userId, 'role']) < self::ROLE_ADMIN
					) {
						$this->addOutput([
							'notification' => helper::translate('Seul un administrateur peut se connecter lors d\'une maintenance'),
							'redirect' => helper::baseUrl(),
							'state' => false
						]);
					} else {
						/**
						 * Le site n'est pas en maintenance
						 * Double authentification en cas de saisie correcte 
						 */
						// Clé d'authenfication utilisée pour lier le compte au cookie au lieu de stocker le hash du mot de passe
						$authKey = uniqid('', true) . bin2hex(random_bytes(8));
						// Clé pour la double validation
						$keyByMail = rand(100000, 999999);
						// La page d'authentification est vide
						$authRedirect = '';
						if (
							$this->getData(['config', 'connect', 'mailAuth']) > 0
							&& $this->getData(['user', $userId, 'role']) >= $this->getData(['config', 'connect', 'mailAuth'])
						) {
							/**
							 * Envoi d'un email contenant une clé 
							 * Stockage de la clé dans le compte de l'utilisateur
							 */

							$sent = $this->sendMail(
								$this->getData(['user', $userId, 'mail']),
								'Validation de la connexion à votre compte',
								'<p>Clé de validation à saisir dans le formulaire de connexion :</p>' .
								'<h1><center>' . $keyByMail . '</center></h1>',
								null,
								$this->getData(['config', 'smtp', 'from'])
							);

							// L'email a été envoyé avec succès, redirection vers la page de double authentification
							if ($sent === true) {
								// Journalisation
								$logStatus = helper::translate('Envoi du message d\'authentification');
								// Redirection vers la page d'authentification
								$authRedirect = 'user/auth/';
								// Stocker la clé envoyée par email
								$this->setData(['user', $userId, 'authKey', $keyByMail]);
								$notification = sprintf('Clé d\'authentification envoyée à votre adresse mail %s', $this->getData(['user', $userId, 'mail']));
							} else {
								// Impossible d'envoyer le message
								// Double authentification désactivée
								$this->setData(['config', 'connect', 'mailAuth', 0]);
								$this->setData(['user', $userId, 'authKey', $authKey]);
								// Journalisation 
								$this->saveLog($sent);
								$notification = sprintf(helper::translate('Bienvenue %s %s'), $this->getData(['user', $userId, 'firstname']), $this->getData(['user', $userId, 'lastname']));
							}
						} else {
							$logStatus = helper::translate('Connexion réussie');
							$notification = sprintf(helper::translate('Bienvenue %s %s'), $this->getData(['user', $userId, 'firstname']), $this->getData(['user', $userId, 'lastname']));
							$this->setData(['user', $userId, 'authKey', $authKey]);
						}

						// Validité du cookie
						$expire = $this->getInput('userLoginLongTime', helper::FILTER_BOOLEAN) === true ? strtotime("+1 year") : 0;
						switch ($this->getInput('userLoginLongTime', helper::FILTER_BOOLEAN)) {
							case false:
								// Cookie de session
								setcookie('ZWII_USER_ID', $userId, $expire, helper::baseUrl(false, false), '', helper::isHttps(), true);
								//setcookie('ZWII_USER_PASSWORD', $this->getData(['user', $userId, 'password']), $expire, helper::baseUrl(false, false), '', helper::isHttps(), true);

								// Connexion par clé
								setcookie('ZWII_AUTH_KEY', $authKey, $expire, helper::baseUrl(false, false), '', helper::isHttps(), true);
								break;
							default:
								// Cookie persistant
								setcookie('ZWII_USER_ID', $userId, $expire, helper::baseUrl(false, false));
								//setcookie('ZWII_USER_PASSWORD', $this->getData(['user', $userId, 'password']), $expire, helper::baseUrl(false, false));

								// Connexion par clé
								setcookie('ZWII_AUTH_KEY', $authKey, $expire, helper::baseUrl(false, false));
								break;
						}

						$pageId = $this->getUrl(2);
						if (
							$this->getData(['config', 'page404']) === $pageId
							|| $this->getData(['config', 'page403']) === $pageId
						) {
							$pageId = '';
						}
						$redirect = ($pageId && strpos($pageId, 'user_reset') !== 0) ? helper::baseUrl() . $authRedirect . str_replace('_', '/', str_replace('__', '#', $pageId)) : helper::baseUrl() . $authRedirect;
						// Valeurs en sortie
						$this->addOutput([
							'notification' => $notification,
							'redirect' => $redirect,
							'state' => true
						]);
					}
					// Sinon notification d'échec
				} else {
					$notification = helper::translate('Captcha, identifiant ou mot de passe incorrects');
					$logStatus = $captcha === true ? helper::translate('Erreur de mot de passe') : helper::translate('Erreur de captcha');
					// Cas 1 le nombre de connexions est inférieur aux tentatives autorisées : incrément compteur d'échec
					if ($this->getData(['user', $userId, 'connectFail']) < $this->getData(['config', 'connect', 'attempt'], false)) {
						$this->setData(['user', $userId, 'connectFail', $this->getdata(['user', $userId, 'connectFail']) + 1], false);
					}
					// Cas 2 la limite du nombre de connexion est atteinte : placer le timer
					if ($this->getdata(['user', $userId, 'connectFail']) == $this->getData(['config', 'connect', 'attempt'])) {
						$this->setData(['user', $userId, 'connectTimeout', time()], false);
					}
					// Cas 3 le délai de bloquage court
					if ($this->getData(['user', $userId, 'connectTimeout']) + $this->getData(['config', 'connect', 'timeout']) > time()) {
						$notification = sprintf(helper::translate('Accès bloqué %d minutes'), ($this->getData(['config', 'connect', 'timeout']) / 60));
					}

					// Valeurs en sortie
					$this->addOutput([
						'notification' => $notification,
					]);
				}
			}
			// Force la sauvegarde
			$this->saveDB('user');
		}
		// Journalisation
		$this->saveLog($logStatus);

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
	 * 
	 * Validation de la connexion par email
	 * @return void
	 */
	public function auth()
	{
		// Soumission du formulaire
		if (
			$this->isPost()
		) {
			// Vérifier la clé saisie
			$targetKey = $this->getData(['user', $this->getUser('id'), 'authKey']);
			$inputKey = $this->getInput('userAuthKey', helper::FILTER_INT);
			// Redirection
			$pageId = $this->getUrl(2);
			$redirect = $pageId ? helper::baseUrl() . $pageId : helper::baseUrl();
			if (
				// La clé est valide ou le message n'ayant pas été expédié, la double authentification est désactivée
				$targetKey === $inputKey || $this->getData(['config', 'connect', 'mailAuth', 0]) === 0
			) {

				// La fiche de l'utilisateur contient la clé d'authentification
				$this->setData(['user', $this->getUser('id'), 'authKey', $this->getInput('ZWII_AUTH_KEY')]);
				// Journalisation
				$this->saveLog('Connexion réussie');
				// Utilisateur connecté
				$userId = $this->getUser('id');
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => $redirect,
					'notification' => sprintf(helper::translate('Bienvenue %s %s'), $this->getData(['user', $userId, 'firstname']), $this->getData(['user', $userId, 'lastname'])),
					'state' => true
				]);
			} else {

				// Supprime la clé stockée et le temps limite
				$this->deleteData(['user', $this->getUser('id'), 'authKey']);
				// Réinitialiser le compteur de temps
				$this->setData(['user', $this->getUser('id'), 'connectTimeout', 0]);

				// Détruit les cookies d'authenfication
				helper::deleteCookie('ZWII_USER_ID');
				helper::deleteCookie('ZWII_AUTH_KEY');

				// Détruit la session
				session_destroy();

				// Journalisation
				$this->saveLog('Erreur de vérification de la clé envoyée par email ' . $this->getUser('id'));
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => $redirect,
					'notification' => helper::translate('La clé est incorrecte'),
					'state' => false
				]);
			}
		} else {
			// Valeurs en sortie
			$this->addOutput([
				'title' => helper::translate('Double authentification'),
				'view' => 'auth',
				'display' => self::DISPLAY_LAYOUT_LIGHT,
			]);
		}
	}

	/**
	 * Déconnexion
	 */
	public function logout()
	{
		// Détruit les cookies d'authenfication
		helper::deleteCookie('ZWII_USER_ID');
		helper::deleteCookie('ZWII_AUTH_KEY');
		// Détruit la session
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
			// Lien de réinitialisation trop vieux (24 heures)
			or $this->getData(['user', $this->getUrl(2), 'forgot']) === null
			or (int) explode('_', $this->getData(['user', $this->getUrl(2), 'forgot']))[0] + 86400 < time()
			// Clé unique incorrecte
			or $this->getUrl(3) !== $this->getData(['user', $this->getUrl(2), 'forgot'])
		) {
			$this->saveLog(
				' Erreur de réinitialisation de mot de passe ' . $this->getUrl(2) .
				' Compte : ' . $this->getData(['user', $this->getUrl(2)]) .
				' Temps : ' . ($this->getData(['user', $this->getUrl(2), 'forgot']) === null ? 'Clé manquante' : ((int) explode('_', $this->getData(['user', $this->getUrl(2), 'forgot']))[0] + 86400 < time() ? 'Temps dépassé' : 'Temps valide')) .
				' Clé : ' . ($this->getUrl(3) !== $this->getData(['user', $this->getUrl(2), 'forgot']) ? 'Clé invalide' : 'Clé valide')
			);
	
			// Message d'erreur en cas de problème de réinitialisation de mot de passe
			$message = $this->getData(['user', $this->getUrl(2)]) === null
				? ' Utilisateur inconnu '
				: '';
			$message = $this->getData(['user', $this->getUrl(2), 'forgot']) === null
				? ' Clé manquante '
				: $message;
			$message = (int) explode('_', $this->getData(['user', $this->getUrl(2), 'forgot']))[0] + 86400 < time()
				? ' Temps dépassé '
				: $message;
			$message = $this->getUrl(3) !== $this->getData(['user', $this->getUrl(2), 'forgot'])
				? ' Clé invalide '
				: $message;
	
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseurl(),
				'notification' => helper::translate('Impossible de réinitialiser le mot de passe de ce compte !') . $message,
				'state' => false
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
					$this->setData(['user', $this->getUrl(2), 'password', $newPassword], false);
					// Réinitialise la date de la demande
					$this->setData(['user', $this->getUrl(2), 'forgot', 0], false);
					// Réinitialise le blocage
					$this->setData(['user', $this->getUrl(2), 'connectFail', 0], false);
					$this->setData(['user', $this->getUrl(2), 'connectTimeout', 0], false);
					// Sauvegarde la base manuellement
					$this->saveDB('user');
					// Valeurs en sortie
					$this->addOutput([
						'notification' => helper::translate('Nouveau mot de passe enregistré'),
						'redirect' => helper::baseUrl() . 'user/login/' . str_replace('/', '_', $this->getUrl()),
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
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Lecture du CSV et construction du tableau
			$file = $this->getInput('userImportCSVFile', helper::FILTER_STRING_SHORT, true);
			$filePath = self::FILE_DIR . 'source/' . $file;
			if ($file and file_exists($filePath)) {
				// Analyse et extraction du CSV
				$rows = array_map(function ($row) {
					return str_getcsv($row, $this->getInput('userImportSeparator'));
				}, file($filePath));
				$header = array_shift($rows);
				$csv = array();
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
						and array_key_exists('role', $item)
						and array_key_exists('profil', $item)
						and array_key_exists('email', $item)
						and array_key_exists('passe', $item)
						and array_key_exists('tags', $item)
						and isset($item['id'])
						and isset($item['nom'])
						and isset($item['prenom'])
						and isset($item['email'])
						and isset($item['role'])
						and isset($item['profil'])
						and isset($item['passe'])
						and isset($item['tags'])
					) {
						// Validation du rôle
						$item['role'] = (int) $item['role'];
						$item['profil'] = (int) $item['profil'];
						$item['role'] = ($item['role'] >= self::ROLE_BANNED and $item['role'] <= self::ROLE_ADMIN)
							? $item['role'] : 1;
						// L'utilisateur existe
						$userId = helper::filter($item['id'], helper::FILTER_ID);
						if ($this->getData(['user', $userId])) {
							// Notification du doublon
							$item['notification'] = template::ico('cancel');
							// Création du tableau de confirmation
							self::$users[] = [
								$userId,
								$item['nom'],
								$item['prenom'],
								self::$roles[$item['role']],
								empty($this->getData(['profil', $this->getData(['user', $userId, 'role']), $this->getData(['user', $userId, 'profil']), 'name']))
								? helper::translate(self::$roles[(int) $this->getData(['user', $userId, 'role'])])
								: $this->getData(['profil', $this->getData(['user', $userId, 'role']), $this->getData(['user', $userId, 'profil']), 'name']),
								$item['prenom'],
								helper::filter($item['email'], helper::FILTER_MAIL),
								$item['tags'],
								$item['notification']
							];
							// L'utilisateur n'existe pas
						} else {
							// Nettoyage de l'identifiant
							// Enregistre le user
							$create = $this->setData([
								'user',
								$userId,
								[
									'firstname' => $item['prenom'],
									'forgot' => 0,
									'role' => $item['role'],
									'profil' => $item['profil'],
									'lastname' => $item['nom'],
									'mail' => $item['email'],
									'pseudo' => $item['prenom'],
									'signature' => 1,
									// Pseudo
									'password' => helper::filter($item['passe'], helper::FILTER_PASSWORD),
									// A modifier à la première connexion
									"connectFail" => null,
									"connectTimeout" => null,
									"accessUrl" => null,
									"accessTimer" => null,
									"accessCsrf" => null,
									'tags' => $item['tags']
								]
							], false);
							// Icône de notification
							$item['notification'] = $create ? template::ico('check') : template::ico('cancel');
							// Envoi du mail
							if (
								$create
								and $this->getInput('userImportNotification', helper::FILTER_BOOLEAN) === true
							) {
								$sent = $this->sendMail(
									$item['email'],
									'Compte créé sur ' . $this->getData(['config', 'title']),
									'Bonjour <strong>' . $item['prenom'] . ' ' . $item['nom'] . '</strong>,<br><br>' .
									'Un administrateur vous a créé un compte sur le site ' . $this->getData(['config', 'title']) . '. Vous trouverez ci-dessous les détails de votre compte.<br><br>' .
									'<strong>Identifiant du compte :</strong> ' . $userId . '<br>' .
									'<small>Un mot de passe provisoire vous été attribué, à la première connexion cliquez sur Mot de passe Oublié.</small>',
									null,
									$this->getData(['config', 'smtp', 'from'])
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
								self::$roles[$item['role']],
								empty($this->getData(['profil', $this->getData(['user', $userId, 'role']), $this->getData(['user', $userId, 'profil']), 'name']))
								? helper::translate(self::$roles[(int) $this->getData(['user', $userId, 'role'])])
								: $this->getData(['profil', $this->getData(['user', $userId, 'role']), $this->getData(['user', $userId, 'profil']), 'name']),
								$item['prenom'],
								$item['email'],
								$item['tags'],
								$item['notification']
							];
						}
					}

				}
				// Force la sauvegarde
				$this->saveDB('user');
				if (empty(self::$users)) {
					$notification = helper::translate('Rien à importer, erreur de format ou fichier incorrect');
					$success = false;
				} else {
					$notification = helper::translate('Importation effectuée');
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
	public function template()
	{
		if ($this->getUser('permission', __CLASS__, __FUNCTION__) === true) {
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

	/**
	 * Liste les dossier contenus dans RFM
	 */
	private function getSubdirectories($dir, $basePath = '')
	{
		$subdirs = array();
		// Ouvrez le répertoire spécifié
		$dh = opendir($dir);
		// Parcourez tous les fichiers et répertoires dans le répertoire
		while (($file = readdir($dh)) !== false) {
			// Ignorer les entrées de répertoire parent et actuel
			if ($file == '.' || $file == '..') {
				continue;
			}
			// Construisez le chemin complet du fichier ou du répertoire
			$path = $dir . '/' . $file;
			// Vérifiez si c'est un répertoire
			if (is_dir($path)) {
				// Construisez la clé et la valeur pour le tableau associatif
				$key = $basePath === '' ? ucfirst($file) : $basePath . '/' . $file;
				$value = $path . '/';
				// Ajouter la clé et la valeur au tableau associatif
				$subdirs[$key] = $value;
				// Appeler la fonction récursivement pour ajouter les sous-répertoires
				$subdirs = array_merge($subdirs, $this->getSubdirectories($path, $key));
			}
		}
		// Fermez le gestionnaire de dossier
		closedir($dh);
		return $subdirs;
	}

}