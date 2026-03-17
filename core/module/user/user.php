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
 * @Copyright (C) 2026, Frédéric Tempez
 * @Licensed under the GNU General Public License v3.0 or later.
 * @link http://zwiicms.fr/
 */
class user extends Core
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

	// Paramètres pour choix de la signature
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
			$userId = $this->getInput('userAddId', Helper::FILTER_ID, true);
			if ($this->getData(['user', $userId])) {
				self::$inputNotices['userAddId'] = 'Identifiant déjà utilisé';
			}
			// Double vérification pour le mot de passe
			if ($this->getInput('userAddPassword', Helper::FILTER_STRING_SHORT, true) !== $this->getInput('userAddConfirmPassword', Helper::FILTER_STRING_SHORT, true)) {
				self::$inputNotices['userAddConfirmPassword'] = 'Les mots de passe ne sont pas indentiques';
			}

			// Crée l'utilisateur
			$userFirstname = $this->getInput('userAddFirstname', Helper::FILTER_STRING_SHORT, true);
			$userLastname = $this->getInput('userAddLastname', Helper::FILTER_STRING_SHORT, true);
			$userMail = $this->getInput('userAddMail', Helper::FILTER_MAIL, true);
			$pseudo = $this->getInput('userAddPseudo', Helper::FILTER_STRING_SHORT, true);
			$signature = $this->getInput('userAddSignature', Helper::FILTER_INT, true);
			$password = $this->getInput('userAddPassword', Helper::FILTER_PASSWORD, true);

			// Profil
			$role = $this->getInput('userAddRole', Helper::FILTER_INT, true);
			$profil = 0;
			if ($role === 1 || $role === 2) {
				$profil = $this->getInput('userAddProfil' . $role, Helper::FILTER_INT);
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
					'language' => $this->getInput('userAddLanguage', Helper::FILTER_STRING_SHORT),
					'tags' => $this->getInput('userAddTags', Helper::FILTER_STRING_SHORT),
				]
			]);

			// Envoie le mail
			$sent = true;
			if (
				$this->getInput('userAddSendMail', Helper::FILTER_BOOLEAN) ||
				self::$inputNotices === []
			) {
				$sent = $this->sendMail(
					$userMail,
					'Compte créé sur ' . $this->getData(['config', 'title']),
					'Bonjour <strong>' . $userFirstname . ' ' . $userLastname . '</strong>,<br><br>'
					. 'Un administrateur vous a créé un compte sur le site ' . $this->getData(['config', 'title']) . '. Vous trouverez ci-dessous les détails de votre compte.<br><br>'
					. '<strong>Identifiant du compte :</strong> ' . $this->getInput('userAddId') . '<br>'
					. '<small>Nous ne conservons pas les mots de passe, en conséquence nous vous conseillons de conserver ce message tant que vous ne vous êtes pas connecté. Vous pourrez modifier votre mot de passe après votre première connexion.</small>',
					null,
					$this->getData(['config', 'smtp', 'from'])
				);
			}
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => Helper::baseUrl() . 'user',
				'notification' => $sent === true ? Helper::translate('Utilisateur créé') : $sent,
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
			'title' => Helper::translate('Nouvel utilisateur'),
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
			$this->getData(['user', $this->getUrl(2)]) === null and
				// Groupe insuffisant
			($this->getUser('role') < self::ROLE_EDITOR)
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
				'redirect' => Helper::baseUrl() . 'user',
				'notification' => Helper::translate('Impossible de supprimer votre propre compte')
			]);
		}
		// Suppression
		else {
			$this->deleteData(['user', $this->getUrl(2)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => Helper::baseUrl() . 'user',
				'notification' => Helper::translate('Utilisateur supprimé'),
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
			// Protection contre l'édition de son propre compte
			$targetUserId = $this->getUrl(2);
			$currentUserId = $this->getUser('id');
			$currentUserRole = $this->getUser('role');

			// Seul un admin peut éditer un autre compte que le sien
			if (
				$currentUserRole < self::ROLE_ADMIN &&
				$targetUserId !== $currentUserId
			) {
				$this->addOutput([
					'access' => false,
					'notification' => Helper::translate('Accès interdit')
				]);
				return;
			}
			if (
				// L'utilisateur n'existe pas
				$this->getData(['user', $this->getUrl(2)]) === null and
					// Droit d'édition
				(
						// Impossible de s'auto-éditer
					($this->getUser('id') === $this->getUrl(2) and
						$this->getUser('role') <= self::ROLE_VISITOR) or
						// Impossible d'éditer un autre utilisateur
					($this->getUser('role') < self::ROLE_EDITOR)
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
									$newPassword = $this->getInput('userEditNewPassword', Helper::FILTER_PASSWORD, true);
									// Déconnexion de l'utilisateur si il change le mot de passe de son propre compte
									if ($this->getUser('id') === $this->getUrl(2)) {
										Helper::deleteCookie('ZWII_USER_ID');
										Helper::deleteCookie('ZWII_USER_PASSWORD');
									}
								} else {
									self::$inputNotices['userEditConfirmPassword'] = Helper::translate('Incorrect');
								}
							} else {
								self::$inputNotices['userEditOldPassword'] = Helper::translate('Incorrect');
							}
						}
					} else {
						if (
							!empty($this->getInput('userEditNewPassword')) &&
							$this->getInput('userEditNewPassword') === $this->getInput('userEditConfirmPassword')
						) {
							$newPassword = $this->getInput('userEditNewPassword', Helper::FILTER_PASSWORD);
							// Déconnexion de l'utilisateur si il change le mot de passe de son propre compte
							if ($this->getUser('id') === $this->getUrl(2)) {
								Helper::deleteCookie('ZWII_USER_ID');
								Helper::deleteCookie('ZWII_USER_PASSWORD');
							}
						}
					}

					// Modification du rôle
					if (
						$this->getUser('role') === self::ROLE_ADMIN and
						$this->getUrl(2) !== $this->getUser('id')
					) {
						$newGroup = $this->getInput('userEditRole', Helper::FILTER_INT, true);
					} else {
						$newGroup = $this->getData(['user', $this->getUrl(2), 'role']);
					}
					// Modification de nom Prénom
					if ($this->getUser('role') === self::ROLE_ADMIN) {
						$newfirstname = $this->getInput('userEditFirstname', Helper::FILTER_STRING_SHORT, true);
						$newlastname = $this->getInput('userEditLastname', Helper::FILTER_STRING_SHORT, true);
					} else {
						$newfirstname = $this->getData(['user', $this->getUrl(2), 'firstname']);
						$newlastname = $this->getData(['user', $this->getUrl(2), 'lastname']);
					}
					// Profil
					$profil = 0;
					if ($newGroup === 1 || $newGroup === 2) {
						$profil = $this->getInput('userEditProfil' . $newGroup, Helper::FILTER_INT);
					}
					if ($currentUserRole < self::ROLE_ADMIN) {
						// Empêcher la modification de l'email par un non-admin
						$newMail = $this->getData(['user', $targetUserId, 'mail']);
					} else {
						$newMail = $this->getInput('userEditMail', Helper::FILTER_MAIL, true);
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
							'pseudo' => $this->getInput('userEditPseudo', Helper::FILTER_STRING_SHORT, true),
							'signature' => $this->getInput('userEditSignature', Helper::FILTER_INT, true),
							'mail' => $this->getInput('userEditMail', Helper::FILTER_MAIL, true),
							'password' => empty($newPassword) ? $oldPassword : $newPassword,
							'connectFail' => $this->getData(['user', $this->getUrl(2), 'connectFail']),
							'connectTimeout' => $this->getData(['user', $this->getUrl(2), 'connectTimeout']),
							'accessUrl' => $this->getData(['user', $this->getUrl(2), 'accessUrl']),
							'accessTimer' => $this->getData(['user', $this->getUrl(2), 'accessTimer']),
							'accessCsrf' => $this->getData(['user', $this->getUrl(2), 'accessCsrf']),
							'files' => $this->getInput('userEditFiles', Helper::FILTER_BOOLEAN),
							'language' => $this->getInput('userEditLanguage', Helper::FILTER_STRING_SHORT),
							'tags' => $this->getInput('userEditTags', Helper::FILTER_STRING_SHORT),
							'authKey' => $this->getData(['user', $this->getUrl(2), 'authKey']),
						]
					]);
					// Redirection spécifique si l'utilisateur change son mot de passe
					if ($this->getUser('id') === $this->getUrl(2) and $this->getInput('userEditNewPassword')) {
						$redirect = Helper::baseUrl() . 'user/login/' . str_replace('/', '_', $this->getUrl());
					}
					// Redirection si retour en arrière possible
					elseif ($this->getUser('role') === self::ROLE_ADMIN) {
						$redirect = Helper::baseUrl() . 'user';
					}
					// Redirection normale
					else {
						$redirect = Helper::baseUrl();
					}
					// Valeurs en sortie
					$this->addOutput([
						'redirect' => $redirect,
						'notification' => Helper::translate('Modifications enregistrées'),
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
			$input = $this->getInput('userForgotId', Helper::FILTER_STRING_SHORT, true);
			$sent = false;
			$userId = null;

			// Si l'input est un email, on cherche l'utilisateur correspondant
			if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
				foreach ($this->getData(['user']) as $id => $userData) {
					if (isset($userData['mail']) && strtolower($userData['mail']) === strtolower($input)) {
						$userId = $id;
						break;
					}
				}
			}
			// Sinon on considère que c'est un ID
			else {
				$userId = ltrim($input, '_');
			}
			// Envoie de l'email
			if ($userId && $this->getData(['user', $userId])) {
				// Crée un id unique pour la réinitialisation
				$uniqId = md5(json_encode($this->getData(['user', $userId, 'forgot']) . time() . $userId));
				// Enregistre l'id unique et la date de la demande dans le compte utilisateur
				$this->setData(['user', $userId, 'forgot', time() . '_' . $uniqId]);
				// Envoi le mail
				$sent = $this->sendMail(
					$this->getData(['user', $userId, 'mail']),
					'Réinitialisation de votre mot de passe',
					'<p>Bonjour <strong>' . $this->getData(['user', $userId, 'firstname']) . ' ' . $this->getData(['user', $userId, 'lastname']) . '</strong>,</p>'
					. '<p>Vous avez demandé à changer le mot de passe lié à votre compte. Vous trouverez ci-dessous un lien vous permettant de modifier celui-ci.</p>'
										. '<p>Si vous n\'avez pas demandé à réinitialiser votre mot de passe, veuillez ignorer ce mail.</p>'
					. '<p><a href="' . Helper::baseUrl() . 'user/reset/' . $userId . '/' . $uniqId . '" target="_blank">' . Helper::baseUrl() . 'user/reset/' . $userId . '/' . $uniqId . '</a></p>',
					null,
					$this->getData(['config', 'smtp', 'from'])
				);
			}

			// Valeurs en sortie
			$this->addOutput([
				'notification' => $sent === true ? Helper::translate('Un mail a été envoyé pour confirmer la réinitialisation') : Helper::translate("Le mail de réinitialisation ne peut pas être envoyé, contactez l'administrateur"),
				'state' => ($sent === true ? true : false),
				'redirect' => Helper::baseUrl()
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => Helper::translate('Mot de passe oublié'),
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
				case '-1':
				case '0':
					break;
				case '3':
					self::$usersGroups['30'] = 'Administrateur';
					$profils['30'] = 0;
					break;
				case '1':
				case '2':
					foreach ($roleValue as $profilId => $profilValue) {
						if ($profilId) {
							self::$usersGroups[$roleId . $profilId] = sprintf(Helper::translate('Rôle %s - Profil %s'), self::$rolePublics[$roleId], $profilValue['name']);
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
		$userIdsLastNames = Helper::arrayColumn($this->getData(['user']), 'lastname');
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
						$this->getInput('userFilterRole', Helper::FILTER_INT) > 0 &&
						$this->getInput('userFilterRole', Helper::FILTER_STRING_SHORT) !== $role . $profil
					)
						continue;
					// Première lettre du prénom
					if (
						$this->getInput('userFilterFirstName', Helper::FILTER_STRING_SHORT) !== 'all' &&
						$this->getInput('userFilterFirstName', Helper::FILTER_STRING_SHORT) !== strtoupper(substr($firstName, 0, 1))
					)
						continue;
					// Première lettre du nom
					if (
						$this->getInput('userFilterLastName', Helper::FILTER_STRING_SHORT) !== 'all' &&
						$this->getInput('userFilterLastName', Helper::FILTER_STRING_SHORT) !== strtoupper(substr($lastName, 0, 1))
					)
						continue;
				}

				// Formatage de la liste
					self::$users[] = [
					// Nom
					sprintf('%s %s', $userLastNames, $this->getData(['user', $userId, 'firstname'])),
					// Email
					$this->getData(['user', $userId, 'mail']),
					// Rôle et Profil
					Helper::translate(self::$roles[(int) $this->getData(['user', $userId, 'role'])])
					. '/'
					. (empty($this->getData(['profil', $this->getData(['user', $userId, 'role']), $this->getData(['user', $userId, 'profil']), 'name']))
						? Helper::translate(self::$roles[(int) $this->getData(['user', $userId, 'role'])])
						: $this->getData(['profil', $this->getData(['user', $userId, 'role']), $this->getData(['user', $userId, 'profil']), 'name'])),
					// Tags
					$this->getData(['user', $userId, 'tags']),
					// Dernière connexion
					is_null($this->getData(['user', $userId, 'accessTimer']))
					? ''
					: $this->getData(['user', $userId, 'accessTimer']),
					// Bouton Edit
					Template::ico('pencil', [
						'id' => 'userEdit' . $userId,
						'href' => Helper::baseUrl() . 'user/edit/' . $userId,
						'value' => Template::ico('pencil'),
						'margin' => 'right',
						'help' => 'Éditer',
						'fontSize' => '18px',
					])
					// Bouton supprimée concaténé
					. Template::ico('trash', [
						'id' => 'userDelete' . $userId,
						'class' => 'userDelete icoTextRed',
						'href' => Helper::baseUrl() . 'user/delete/' . $userId,
						'help' => 'Supprimer',
						'fontSize' => '18px',
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
			'title' => Helper::translate('Utilisateurs'),
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

		foreach ($this->getData(['profil']) as $roleId => $roleData) {
			// Membres sans permissions spécifiques
			if (
				$roleId == self::ROLE_BANNED ||
				$roleId == self::ROLE_VISITOR ||
				$roleId == self::ROLE_ADMIN
			) {
				self::$userGroups[$roleId] = [
					$roleId,
					Helper::translate($roleData['name']),
					nl2br(Helper::translate($roleData['comment'])),
					Template::button('profilEdit' . $roleId, [
						'value' => Template::ico('pencil'),
						'help' => 'Éditer',
						'disabled' => $roleData['readonly'],
					]),
					Template::button('profilDelete' . $roleId, [
						'value' => Template::ico('trash'),
						'help' => 'Supprimer',
						'disabled' => $roleData['readonly'],
					])
				];
			} elseif (
				$roleId == self::ROLE_MEMBER ||
				$roleId == self::ROLE_EDITOR
			) {
				$profilInUse = $this->getData(['user']);
				// Extrait les clés profils de $profilInUse
				$profilInUse = array_column($profilInUse, 'profil');
				// Enumérer les sous rôles MEMBER et MODERATOR
				foreach ($roleData as $profilId => $profilData) {
					self::$userGroups[$roleId . '.' . $profilId] = [
						$roleId . '-' . $profilId,
						Helper::translate(self::$roles[$roleId]) . '<br />Profil : ' . Helper::translate($profilData['name']),
						nl2br(Helper::translate($profilData['comment'])),
						Template::button('profilEdit' . $roleId . $profilId, [
							'href' => Helper::baseUrl() . 'user/profilEdit/' . $roleId . '/' . $profilId,
							'value' => Template::ico('pencil'),
							'help' => 'Éditer',
							'disabled' => $profilData['readonly'],
						]),
						Template::button('profilDelete' . $roleId . $profilId, [
							'class' => 'profilDelete buttonRed',
							'href' => Helper::baseUrl() . 'user/profilDelete/' . $roleId . '/' . $profilId,
							'value' => Template::ico('trash'),
							'help' => 'Supprimer',
							'disabled' => $profilData['permanent'] || array_search($profilId, $profilInUse),
						])
					];
				}
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => Helper::translate('Profils des rôles'),
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
			$role = $this->getInput('profilEditRole', Helper::FILTER_STRING_SHORT, true);
			// Les profils 1 sont désactivés dans le formulaire
			$profil = empty($this->getInput('profilEditProfil')) ? '1' : $this->getInput('profilEditProfil');
			$oldProfil = $this->getInput('profilEditOldProfil', Helper::FILTER_STRING_SHORT);
			// Gère le chemin
			$fileManager = $this->getInput('profilEditFileManager', Helper::FILTER_BOOLEAN);
			$path = $this->getInput('profilEditPath');

			// Empêche les roles 1 d'avoir accès au gestionnaire de fichiers
			if (
				$role < self::ROLE_MEMBER
			) {
				$fileManager = false;
			}
			// Données de path si accès interdit
			if ($fileManager === false) {
				$path = 'none';
			}

			// Effacer l'ancien profil si le rang a changé
			if (
				$profil !== $oldProfil ||
				$this->deleteData(['profil', $role, $oldProfil])
			) {
				$this->deleteData(['profil', $role, $oldProfil]);
			}

			// Données du formulaire
			$data = [
				'name' => $this->getInput('profilEditName', Helper::FILTER_STRING_SHORT, true),
				'readonly' => false,
				'permanent' => $profil === '1' ? true : false,
				'comment' => $this->getInput('profilEditComment', Helper::FILTER_STRING_SHORT, true),
				'filemanager' => $fileManager,
				'file' => [
					'download' => $this->getInput('profilEditDownload', Helper::FILTER_BOOLEAN),
					'edit' => $this->getInput('profilEditEdit', Helper::FILTER_BOOLEAN),
					'create' => $this->getInput('profilEditCreate', Helper::FILTER_BOOLEAN),
					'rename' => $this->getInput('profilEditRename', Helper::FILTER_BOOLEAN),
					'upload' => $this->getInput('profilEditUpload', Helper::FILTER_BOOLEAN),
					'delete' => $this->getInput('profilEditDelete', Helper::FILTER_BOOLEAN),
					'preview' => $this->getInput('profilEditPreview', Helper::FILTER_BOOLEAN),
					'duplicate' => $this->getInput('profilEditDuplicate', Helper::FILTER_BOOLEAN),
					'extract' => $this->getInput('profilEditExtract', Helper::FILTER_BOOLEAN),
					'copycut' => $this->getInput('profilEditCopycut', Helper::FILTER_BOOLEAN),
					'chmod' => $this->getInput('profilEditChmod', Helper::FILTER_BOOLEAN),
				],
				'folder' => [
					'create' => $this->getInput('profilEditFolderCreate', Helper::FILTER_BOOLEAN),
					'delete' => $this->getInput('profilEditFolderDelete', Helper::FILTER_BOOLEAN),
					'rename' => $this->getInput('profilEditFolderRename', Helper::FILTER_BOOLEAN),
					'copycut' => $this->getInput('profilEditFolderCopycut', Helper::FILTER_BOOLEAN),
					'chmod' => $this->getInput('profilEditFolderChmod', Helper::FILTER_BOOLEAN),
					'path' => preg_replace('/^\./', '', $path),  // Supprime le point pour préserver le chemin
				],
				'page' => [
					'add' => $this->getInput('profilEditPageAdd', Helper::FILTER_BOOLEAN),
					'edit' => $this->getInput('profilEditPageEdit', Helper::FILTER_BOOLEAN),
					'delete' => $this->getInput('profilEditPageDelete', Helper::FILTER_BOOLEAN),
					'duplicate' => $this->getInput('profilEditPageDuplicate', Helper::FILTER_BOOLEAN),
					'module' => $this->getInput('profilEditPageModule', Helper::FILTER_BOOLEAN),
					'cssEditor' => $this->getInput('profilEditPagecssEditor', Helper::FILTER_BOOLEAN),
					'jsEditor' => $this->getInput('profilEditPagejsEditor', Helper::FILTER_BOOLEAN),
				],
				'user' => [
					'edit' => $this->getInput('profilEditUserEdit', Helper::FILTER_BOOLEAN),
				]
			];

			// Données des modules
			$dataModules = Helper::getModules();
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

			// Sauvegarder le données
			$this->setData([
				'profil',
				$role,
				$profil,
				$data
			]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => Helper::baseUrl() . 'user/profil',
				'notification' => Helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}

		// Chemin vers les dossiers du gestionnaire de fichier
		self::$sharePath = $this->getSubdirectories('site/file/source');
		self::$sharePath = array_flip(self::$sharePath);
		self::$sharePath = array_merge(['site/file/source/' => 'Tous les dossiers'], self::$sharePath);

		// Liste des modules installés
		self::$listModules = Helper::getModules();
		self::$listModules = array_keys(self::$listModules);

		// Charge les dialogues du module pour afficher les traductions
		foreach (self::$listModules as $moduleId) {
			if (
				is_dir(self::MODULE_DIR . $moduleId . '/i18n') &&
				file_exists(self::MODULE_DIR . $moduleId . '/i18n/' . self::$i18nUI . '.json')
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
			'title' => sprintf(Helper::translate('Édition du profil %s'), $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'name'])),
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
			$role = $this->getInput('profilAddRole', Helper::FILTER_STRING_SHORT);
			$profil = count($this->getData(['profil', $role]));
			// Gère le chemin
			$fileManager = $this->getInput('profilAddFileManager', Helper::FILTER_BOOLEAN);
			$path = $this->getInput('profilAddPath');

			// Sécurité supplémentaire pour interdire les fichiers
			if (
				$role < self::ROLE_MEMBER
			) {
				$fileManager = false;
			}
			// Données de path si accès interdit
			if ($fileManager === false) {
				$path = 'none';
			}

			if ($profil < self::MAX_PROFILS) {
				$profil = (string) ($profil + 1);
				// Données du formulaire
				$data = [
					'name' => $this->getInput('profilAddName', Helper::FILTER_STRING_SHORT, true),
					'readonly' => false,
					'permanent' => false,
					'comment' => $this->getInput('profilAddComment', Helper::FILTER_STRING_SHORT, true),
					'filemanager' => $fileManager,
					'file' => [
						'download' => $this->getInput('profilAddDownload', Helper::FILTER_BOOLEAN),
						'edit' => $this->getInput('profilAddEdit', Helper::FILTER_BOOLEAN),
						'create' => $this->getInput('profilAddCreate', Helper::FILTER_BOOLEAN),
						'rename' => $this->getInput('profilAddRename', Helper::FILTER_BOOLEAN),
						'upload' => $this->getInput('profilAddUpload', Helper::FILTER_BOOLEAN),
						'delete' => $this->getInput('profilAddDelete', Helper::FILTER_BOOLEAN),
						'preview' => $this->getInput('profilAddPreview', Helper::FILTER_BOOLEAN),
						'duplicate' => $this->getInput('profilAddDuplicate', Helper::FILTER_BOOLEAN),
						'extract' => $this->getInput('profilAddExtract', Helper::FILTER_BOOLEAN),
						'copycut' => $this->getInput('profilAddCopycut', Helper::FILTER_BOOLEAN),
						'chmod' => $this->getInput('profilAddChmod', Helper::FILTER_BOOLEAN),
					],
					'folder' => [
						'create' => $this->getInput('profilAddFolderCreate', Helper::FILTER_BOOLEAN),
						'delete' => $this->getInput('profilAddFolderDelete', Helper::FILTER_BOOLEAN),
						'rename' => $this->getInput('profilAddFolderRename', Helper::FILTER_BOOLEAN),
						'copycut' => $this->getInput('profilAddFolderCopycut', Helper::FILTER_BOOLEAN),
						'chmod' => $this->getInput('profilAddFolderChmod', Helper::FILTER_BOOLEAN),
						'path' => preg_replace('/^\./', '', $path),  // Supprime le point pour préserver le chemin
					],
					'page' => [
						'add' => $this->getInput('profilAddPageAdd', Helper::FILTER_BOOLEAN),
						'edit' => $this->getInput('profilAddPageEdit', Helper::FILTER_BOOLEAN),
						'delete' => $this->getInput('profilAddPageDelete', Helper::FILTER_BOOLEAN),
						'duplicate' => $this->getInput('profilAddPageDuplicate', Helper::FILTER_BOOLEAN),
						'module' => $this->getInput('profilAddPageModule', Helper::FILTER_BOOLEAN),
						'cssEditor' => $this->getInput('profilAddPagecssEditor', Helper::FILTER_BOOLEAN),
						'jsEditor' => $this->getInput('profilAddPagejsEditor', Helper::FILTER_BOOLEAN),
					],
					'user' => [
						'edit' => $this->getInput('profilAddUserEdit', Helper::FILTER_BOOLEAN),
					]
				];

				// Données des modules
				$dataModules = Helper::getModules();
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
					'redirect' => Helper::baseUrl() . 'user/profil',
					'notification' => Helper::translate('Modifications enregistrées'),
					'state' => true
				]);
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => Helper::baseUrl() . 'user/profil',
					'notification' => Helper::translate('Nombre de profils maximum : ') . self::MAX_PROFILS,
					'state' => false
				]);
			}
		}

		// Chemin vers les dossiers du gestionnaire de fichier
		self::$sharePath = $this->getSubdirectories('site/file/source');
		self::$sharePath = array_flip(self::$sharePath);
		self::$sharePath = array_merge(['site/file/source/' => 'Tous les dossiers'], self::$sharePath);

		// Liste des modules installés
		self::$listModules = Helper::getModules();
		self::$listModules = array_keys(self::$listModules);

		// Charge les dialogues du module pour afficher les traductions
		foreach (self::$listModules as $moduleId) {
			if (
				is_dir(self::MODULE_DIR . $moduleId . '/i18n') &&
				file_exists(self::MODULE_DIR . $moduleId . '/i18n/' . self::$i18nUI . '.json')
			) {
				$d = json_decode(file_get_contents(self::MODULE_DIR . $moduleId . '/i18n/' . self::$i18nUI . '.json'), true);
				self::$dialog = array_merge(self::$dialog, $d);
			}
		}
		// Tri alphabétique
		sort(self::$listModules);

		// Valeurs en sortie;
		$this->addOutput([
			'title' => 'Ajouter un profil',
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
		$roles = Helper::arrayColumn($this->getData(['user']), 'role');
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
				'redirect' => Helper::baseUrl() . $this->getUrl(0) . '/profil',
				'notification' => $flag ? Helper::translate('Profil supprimé') : Helper::translate('Action interdite'),
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
			$userId = $this->getInput('userLoginId', Helper::FILTER_ID, true);
			$notification = '';
			// Check le captcha
			if (
				$this->getData(['config', 'connect', 'captcha']) and
				password_verify($this->getInput('userLoginCaptcha', Helper::FILTER_INT), $this->getInput('userLoginCaptchaResult')) === false
			) {
				$captcha = false;
			} else {
				$captcha = true;
			}
			/** Aucun compte existant */
			if (!$this->getData(['user', $userId])) {
				$logStatus = 'Compte inconnu';
				// Stockage de l'IP
				$this->setData([
					'blacklist',
					$userId,
					[
						'connectFail' => $this->getData(['blacklist', $userId, 'connectFail']) + 1,
						'lastFail' => time(),
						'ip' => Helper::getIp()
					]
				]);
				// Verrouillage des IP
				$ipBlackList = Helper::arrayColumn($this->getData(['blacklist']), 'ip');
				if (
					$this->getData(['blacklist', $userId, 'connectFail']) >= $this->getData(['config', 'connect', 'attempt']) and
					in_array($this->getData(['blacklist', $userId, 'ip']), $ipBlackList)
				) {
					$logStatus = 'Compte inconnu verrouillé';
					// Valeurs en sortie
					$this->addOutput([
						'notification' => Helper::translate('Compte verrouillé'),
						'redirect' => Helper::baseUrl(),
						'state' => false
					]);
				} else {
					// Valeurs en sortie
					$this->addOutput([
						'notification' => Helper::translate('Captcha, identifiant ou mot de passe incorrects')
					]);
				}

				/**
				 * Le compte existe
				 */
			} else {
				// Cas 4 : le délai de  blocage est  dépassé et le compte est au max - Réinitialiser
				if (
					$this->getData(['user', $userId, 'connectTimeout']) + $this->getData(['config', 'connect', 'timeout']) < time() and
					$this->getData(['user', $userId, 'connectFail']) === $this->getData(['config', 'connect', 'attempt'])
				) {
					$this->setData(['user', $userId, 'connectFail', 0], false);
					$this->setData(['user', $userId, 'connectTimeout', 0], false);
				}
				// Check la présence des variables et contrôle du blocage du compte si valeurs dépassées
				// Vérification du mot de passe et du rôle
				if (
					($this->getData(['user', $userId, 'connectTimeout']) + $this->getData(['config', 'connect', 'timeout'])) < time() and
					$this->getData(['user', $userId, 'connectFail']) < $this->getData(['config', 'connect', 'attempt']) and
					password_verify(html_entity_decode($this->getInput('userLoginPassword', Helper::FILTER_STRING_SHORT, true)), $this->getData(['user', $userId, 'password'])) and
					$this->getData(['user', $userId, 'role']) >= self::ROLE_MEMBER and
					$captcha === true
				) {
					// RAZ des compteurs de blocage
					$this->setData(['user', $userId, 'connectFail', 0], false);
					$this->setData(['user', $userId, 'connectTimeout', 0], false);

					// Accès multiples avec le même compte
					$this->setData(['user', $userId, 'accessCsrf', $_SESSION['csrf']], false);

					// Valeurs en sortie lorsque le site est en maintenance et que l'utilisateur n'est pas administrateur
					if (
						$this->getData(['config', 'maintenance']) and
						$this->getData(['user', $userId, 'role']) < self::ROLE_ADMIN
					) {
						$this->addOutput([
							'notification' => Helper::translate("Seul un administrateur peut se connecter lors d'une maintenance"),
							'redirect' => Helper::baseUrl(),
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
							$this->getData(['config', 'connect', 'mailAuth']) > 0 &&
							$this->getData(['user', $userId, 'role']) >= $this->getData(['config', 'connect', 'mailAuth'])
						) {
							/**
							 * Envoi d'un email contenant une clé
							 * Stockage de la clé dans le compte de l'utilisateur
							 */
							$sent = $this->sendMail(
								$this->getData(['user', $userId, 'mail']),
								'Validation de la connexion à votre compte',
								'<p>Clé de validation à saisir dans le formulaire de connexion :</p>'
								. '<h1><center>' . $keyByMail . '</center></h1>',
								null,
								$this->getData(['config', 'smtp', 'from'])
							);

							// L'email a été envoyé avec succès, redirection vers la page de double authentification
							if ($sent === true) {
								// Journalisation
								$logStatus = Helper::translate("Envoi du message d'authentification");
								// Redirection vers la page d'authentification
								$authRedirect = 'user/auth/';
								// Stocker la clé envoyée par email
								$this->setData(['user', $userId, 'authKey', $keyByMail]);
								$notification = sprintf("Clé d'authentification envoyée à votre adresse mail %s", $this->getData(['user', $userId, 'mail']));
							} else {
								// Impossible d'envoyer le message
								// Double authentification désactivée
								$this->setData(['config', 'connect', 'mailAuth', 0]);
								$this->setData(['user', $userId, 'authKey', $authKey]);
								// Journalisation
								$this->saveLog($sent);
								$notification = sprintf(Helper::translate('Bienvenue %s %s'), $this->getData(['user', $userId, 'firstname']), $this->getData(['user', $userId, 'lastname']));
							}
						} else {
							$logStatus = Helper::translate('Connexion réussie');
							$notification = sprintf(Helper::translate('Bienvenue %s %s'), $this->getData(['user', $userId, 'firstname']), $this->getData(['user', $userId, 'lastname']));
							$this->setData(['user', $userId, 'authKey', $authKey]);
						}

						// Validité du cookie
						$expire = $this->getInput('userLoginLongTime', Helper::FILTER_BOOLEAN) === true ? strtotime('+1 year') : 0;
						switch ($this->getInput('userLoginLongTime', Helper::FILTER_BOOLEAN)) {
							case false:
								 // Session classique : stocke l'ID utilisateur en session PHP
								$_SESSION['ZWII_USER_ID'] = $userId;
								// Cookie de session pour le filemanager
								setcookie('ZWII_USER_ID', $userId, $expire, Helper::baseUrl(false, false), '', Helper::isHttps(), true);

								// Cookie d'authentification pour la reconnexion automatique
								setcookie('ZWII_AUTH_KEY', $authKey, 0, Helper::baseUrl(false, false), '', Helper::isHttps(), true);
								break;
							default:
								 // Session classique : stocke l'ID utilisateur en session PHP
								$_SESSION['ZWII_USER_ID'] = $userId;
								// Cookie persistant pour le filemanager
								setcookie('ZWII_USER_ID', $userId, $expire, Helper::baseUrl(false, false));

								/// Cookie d'authentification persistant pour la reconnexion automatique
								setcookie('ZWII_AUTH_KEY', $authKey, $expire, Helper::baseUrl(false, false), '', Helper::isHttps(), true);
								break;
						}

						$pageId = $this->getUrl(2);
						if (
							$this->getData(['config', 'page404']) === $pageId ||
							$this->getData(['config', 'page403']) === $pageId
						) {
							$pageId = '';
						}
						$redirect = ($pageId && strpos($pageId, 'user_reset') !== 0) ? Helper::baseUrl() . $authRedirect . str_replace('_', '/', str_replace('__', '#', $pageId)) : Helper::baseUrl() . $authRedirect;
						// Valeurs en sortie
						$this->addOutput([
							'notification' => $notification,
							'redirect' => $redirect,
							'state' => true
						]);
					}
					// Sinon notification d'échec
				} else {
					$notification = Helper::translate('Captcha, identifiant ou mot de passe incorrects');
					$logStatus = $captcha === true ? Helper::translate('Erreur de mot de passe') : Helper::translate('Erreur de captcha');
					// Cas 1 le nombre de connexions est inférieur aux tentatives autorisées : incrément compteur d'échec
					if ($this->getData(['user', $userId, 'connectFail']) < $this->getData(['config', 'connect', 'attempt'])) {
						$this->setData(['user', $userId, 'connectFail', $this->getdata(['user', $userId, 'connectFail']) + 1]);
					}
					// Cas 2 la limite du nombre de connexion est atteinte : placer le timer
					if ($this->getdata(['user', $userId, 'connectFail']) == $this->getData(['config', 'connect', 'attempt'])) {
						$this->setData(['user', $userId, 'connectTimeout', time()], false);
					}
					// Cas 3 le délai de bloquage court
					if ($this->getData(['user', $userId, 'connectTimeout']) + $this->getData(['config', 'connect', 'timeout']) > time()) {
						$notification = sprintf(Helper::translate('Accès bloqué %d minutes'), ($this->getData(['config', 'connect', 'timeout']) / 60));
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
			'title' => Helper::translate('Connexion'),
			'view' => 'login',
			'display' => self::DISPLAY_LAYOUT_LIGHT,
		]);
	}

	/**
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
			$inputKey = $this->getInput('userAuthKey', Helper::FILTER_INT);
			// Redirection
			$pageId = $this->getUrl(2);
			$redirect = $pageId ? Helper::baseUrl() . $pageId : Helper::baseUrl();
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
					'notification' => sprintf(Helper::translate('Bienvenue %s %s'), $this->getData(['user', $userId, 'firstname']), $this->getData(['user', $userId, 'lastname'])),
					'state' => true
				]);
			} else {
				// Supprime la clé stockée et le temps limite
				$this->deleteData(['user', $this->getUser('id'), 'authKey']);
				// Réinitialiser le compteur de temps
				$this->setData(['user', $this->getUser('id'), 'connectTimeout', 0]);

				// Détruit les cookies d'authenfication
				Helper::deleteCookie('ZWII_USER_ID');
				Helper::deleteCookie('ZWII_AUTH_KEY');

				// Détruit la session
				session_destroy();

				// Journalisation
				$this->saveLog('Erreur de vérification de la clé envoyée par email ' . $this->getUser('id'), true);
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => $redirect,
					'notification' => Helper::translate('La clé est incorrecte'),
					'state' => false
				]);
			}
		} else {
			// Valeurs en sortie
			$this->addOutput([
				'title' => Helper::translate('Double authentification'),
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
		Helper::deleteCookie('ZWII_USER_ID');
		Helper::deleteCookie('ZWII_AUTH_KEY');
		// Détruit la session
		session_destroy();

		// Valeurs en sortie
		$this->addOutput([
			'notification' => Helper::translate('Déconnexion !'),
			'redirect' => Helper::baseUrl(false),
			'state' => true
		]);
	}

	/**
	 * Réinitialisation du mot de passe
	 */
	public function reset()
	{
		// Nettoyer les paramètres URL (protection contre caractères invisibles des webmails)
		$userId = trim(urldecode($this->getUrl(2)));
		$urlKey = trim(urldecode($this->getUrl(3)));
		
		// Récupérer les données utilisateur une seule fois
		$userData = $this->getData(['user', $userId]);
		$forgotData = $userData ? $this->getData(['user', $userId, 'forgot']) : null;
		
		// Validation : l'utilisateur doit exister
		if ($userData === null) {
			$this->saveLog('Reset - Erreur : Utilisateur inconnu [' . $userId . ']', true);
			$this->addOutput([
				'redirect' => Helper::baseUrl(),
				'notification' => Helper::translate('Impossible de réinitialiser le mot de passe de ce compte !') . ' Utilisateur inconnu',
				'state' => false
			]);
			return;
		}
		
		// Validation : le format de forgot doit être valide (timestamp_key)
		if (!is_string($forgotData) || strpos($forgotData, '_') === false) {
			$this->saveLog('Reset - Erreur : Format invalide pour [' . $userId . '] - Data: ' . var_export($forgotData, true), true);
			$this->addOutput([
				'redirect' => Helper::baseUrl(),
				'notification' => Helper::translate('Impossible de réinitialiser le mot de passe de ce compte !') . ' Clé manquante ou invalide',
				'state' => false
			]);
			return;
		}
		
		// Parser le format timestamp_key
		$forgotParts = explode('_', $forgotData, 2);
		$timestamp = (int) $forgotParts[0];
		$storedKey = $forgotParts[1];
		
		// Validation : le lien ne doit pas être expiré (24 heures)
		$isExpired = ($timestamp + 86400) < time();
		if ($isExpired) {
			$this->saveLog('Reset - Erreur : Lien expiré pour [' . $userId . '] - Timestamp: ' . $timestamp . ' - Age: ' . (time() - $timestamp) . 's', true);
			$this->addOutput([
				'redirect' => Helper::baseUrl(),
				'notification' => Helper::translate('Impossible de réinitialiser le mot de passe de ce compte !') . ' Lien expiré (24h maximum)',
				'state' => false
			]);
			return;
		}
		
		// Validation : la clé doit correspondre
		if ($urlKey !== $storedKey) {
			$this->saveLog('Reset - Erreur : Clé invalide pour [' . $userId . '] - Attendu: [' . $storedKey . '] - Reçu: [' . $urlKey . '] - Longueurs: ' . strlen($storedKey) . '/' . strlen($urlKey), true);
			$this->addOutput([
				'redirect' => Helper::baseUrl(),
				'notification' => Helper::translate('Impossible de réinitialiser le mot de passe de ce compte !') . ' Clé invalide',
				'state' => false
			]);
			return;
		}
		
		// Validation réussie - Log succès
		$this->saveLog('Reset - Accès autorisé pour [' . $userId . ']');
		
		// Soumission du formulaire
		if ($this->isPost()) {
			$newPasswordInput = $this->getInput('userResetNewPassword', Helper::FILTER_STRING_SHORT, true);
			$confirmPasswordInput = $this->getInput('userResetConfirmPassword', Helper::FILTER_STRING_SHORT, true);
			
			if ($newPasswordInput) {
				// Vérifier que les deux mots de passe correspondent
				if ($newPasswordInput !== $confirmPasswordInput) {
					self::$inputNotices['userResetConfirmPassword'] = 'Incorrect';
				} else {
					// Hasher et enregistrer le nouveau mot de passe
					$newPassword = $this->getInput('userResetNewPassword', Helper::FILTER_PASSWORD, true);
					$this->setData(['user', $userId, 'password', $newPassword], false);
					// Réinitialiser la demande de reset
					$this->setData(['user', $userId, 'forgot', 0], false);
					// Débloquer le compte
					$this->setData(['user', $userId, 'connectFail', 0], false);
					$this->setData(['user', $userId, 'connectTimeout', 0], false);
					// Sauvegarder
					$this->saveDB('user');
					
					$this->saveLog('Reset - Mot de passe modifié avec succès pour [' . $userId . ']');
					
					// Rediriger vers la page de login
					$this->addOutput([
						'notification' => Helper::translate('Nouveau mot de passe enregistré'),
						'redirect' => Helper::baseUrl() . 'user/login',
						'state' => true
					]);
					return;
				}
			}
		}
		
		// Afficher le formulaire
		$this->addOutput([
			'title' => Helper::translate('Réinitialisation du mot de passe'),
			'view' => 'reset',
			'display' => self::DISPLAY_LAYOUT_LIGHT,
		]);
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
			$file = $this->getInput('userImportCSVFile', Helper::FILTER_STRING_SHORT, true);
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
						array_key_exists('id', $item) and
						array_key_exists('prenom', $item) and
						array_key_exists('nom', $item) and
						array_key_exists('role', $item) and
						array_key_exists('profil', $item) and
						array_key_exists('email', $item) and
						array_key_exists('passe', $item) and
						array_key_exists('tags', $item) and
						isset($item['id']) and
						isset($item['nom']) and
						isset($item['prenom']) and
						isset($item['email']) and
						isset($item['role']) and
						isset($item['profil']) and
						isset($item['passe']) and
						isset($item['tags'])
					) {
						// Validation du rôle
						$item['role'] = (int) $item['role'];
						$item['profil'] = (int) $item['profil'];
						$item['role'] = ($item['role'] >= self::ROLE_BANNED and $item['role'] <= self::ROLE_ADMIN)
							? $item['role']
							: 1;
						// L'utilisateur existe
						$userId = Helper::filter($item['id'], Helper::FILTER_ID);
						if ($this->getData(['user', $userId])) {
							// Notification du doublon
							$item['notification'] = Template::ico('cancel');
							// Création du tableau de confirmation
							self::$users[] = [
								$userId,
								$item['nom'],
								$item['prenom'],
								self::$roles[$item['role']],
								empty($this->getData(['profil', $this->getData(['user', $userId, 'role']), $this->getData(['user', $userId, 'profil']), 'name']))
								? Helper::translate(self::$roles[(int) $this->getData(['user', $userId, 'role'])])
								: $this->getData(['profil', $this->getData(['user', $userId, 'role']), $this->getData(['user', $userId, 'profil']), 'name']),
								$item['prenom'],
								Helper::filter($item['email'], Helper::FILTER_MAIL),
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
									'password' => Helper::filter($item['passe'], Helper::FILTER_PASSWORD),
									// A modifier à la première connexion
									'connectFail' => null,
									'connectTimeout' => null,
									'accessUrl' => null,
									'accessTimer' => null,
									'accessCsrf' => null,
									'tags' => $item['tags']
								]
							], false);
							// Icône de notification
							$item['notification'] = $create ? Template::ico('check') : Template::ico('cancel');
							// Envoi du mail
							if (
								$create and
								$this->getInput('userImportNotification', Helper::FILTER_BOOLEAN) === true
							) {
								$sent = $this->sendMail(
									$item['email'],
									'Compte créé sur ' . $this->getData(['config', 'title']),
									'Bonjour <strong>' . $item['prenom'] . ' ' . $item['nom'] . '</strong>,<br><br>'
									. 'Un administrateur vous a créé un compte sur le site ' . $this->getData(['config', 'title']) . '. Vous trouverez ci-dessous les détails de votre compte.<br><br>'
									. '<strong>Identifiant du compte :</strong> ' . $userId . '<br>'
									. '<small>Un mot de passe provisoire vous été attribué, à la première connexion cliquez sur Mot de passe Oublié.</small>',
									null,
									$this->getData(['config', 'smtp', 'from'])
								);
								if ($sent === true) {
									// Mail envoyé changement de l'icône
									$item['notification'] = Template::ico('mail');
								}
							}
							// Création du tableau de confirmation
							self::$users[] = [
								$userId,
								$item['nom'],
								$item['prenom'],
								self::$roles[$item['role']],
								empty($this->getData(['profil', $this->getData(['user', $userId, 'role']), $this->getData(['user', $userId, 'profil']), 'name']))
								? Helper::translate(self::$roles[(int) $this->getData(['user', $userId, 'role'])])
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
					$notification = Helper::translate('Rien à importer, erreur de format ou fichier incorrect');
					$success = false;
				} else {
					$notification = Helper::translate('Importation effectuée');
					$success = true;
				}
			} else {
				$notification = Helper::translate('Erreur de lecture, vérifiez les permissions');
				$success = false;
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => "Importation d'utilisateurs",
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
