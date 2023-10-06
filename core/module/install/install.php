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


class install extends common
{

	public static $actions = [
		'index' => self::GROUP_VISITOR,
		"postinstall" => self::GROUP_VISITOR,
		'steps' => self::GROUP_ADMIN,
		'update' => self::GROUP_ADMIN
	];

	// Type de proxy
	public static $proxyType = [
		'tcp://' => 'TCP',
		'http://' => 'HTTP'
	];

	// Thèmes proposés à l'installation
	public static $themes = [];

	public static $newVersion;

	// Fichiers des Interface
	public static $i18nFiles = [];

	/**
	 * Pré-installation - choix de la langue
	 */
	public function index()
	{
		// Accès refusé
		if ($this->getData(['user']) !== []) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}

		// Soumission du formulaire
		if (
			//$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {

			$lang = $this->getInput('installLanguage');
			// Pour la suite  de l'installation
			// setcookie('ZWII_UI', $lang, time() + 3600, helper::baseUrl(false, false), '', false, false);

			$_SESSION['ZWII_UI'] = $this->getInput('installLanguage');

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'install/postinstall'
			]);
		}

		// Liste des langues UI disponibles
		if (is_dir(self::I18N_DIR)) {
			foreach ($this->getData(['language']) as $lang => $value) {
				self::$i18nFiles[$lang] = self::$languages[$lang];
			}
		}

		$this->addOutput([
			'display' => self::DISPLAY_LAYOUT_LIGHT,
			'title' => helper::translate('ZwiiCMS Installation'),
			'view' => 'index'
		]);
	}

	/**
	 * post Installation
	 */
	public function postInstall()
	{
		// Accès refusé
		if ($this->getData(['user']) !== []) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Accès autorisé
		else {
			// Soumission du formulaire
			if (
				//$this->getUser('permission', __CLASS__, __FUNCTION__) !== true &&
				$this->isPost()
			) {

				$success = true;

				// Double vérification pour le mot de passe
				if ($this->getInput('installPassword', helper::FILTER_STRING_SHORT, true) !== $this->getInput('installConfirmPassword', helper::FILTER_STRING_SHORT, true)) {
					self::$inputNotices['installConfirmPassword'] = 'Incorrect';
					$success = false;
				}
				// Utilisateur
				$userFirstname = $this->getInput('installFirstname', helper::FILTER_STRING_SHORT, true);
				$userLastname = $this->getInput('installLastname', helper::FILTER_STRING_SHORT, true);
				$userMail = $this->getInput('installMail', helper::FILTER_MAIL, true);
				$userId = $this->getInput('installId', helper::FILTER_ID, true);

				// Validation de la langue transmise
				self::$i18nUI = $_SESSION['ZWII_UI'];
				self::$i18nUI = array_key_exists(self::$i18nUI, self::$languages) ? self::$i18nUI : 'fr_FR';
				// par défaut le contenu est la langue d'installation
				$_SESSION['ZWII_CONTENT'] = self::$i18nUI;

				// Création du dossier de langue avec le marqueur de langue par défaut
				if (!is_dir(self::DATA_DIR . $_SESSION['ZWII_CONTENT'])) {
					mkdir(self::DATA_DIR . $_SESSION['ZWII_CONTENT']);
					touch(self::DATA_DIR . $_SESSION['ZWII_CONTENT'] . '/.default');
				}

				// Installation du site de test
				if (
					$this->getInput('installDefaultData', helper::FILTER_BOOLEAN) === false
					&& $_SESSION['ZWII_CONTENT'] === 'fr_FR'
				) {
					$sample = true;
				}
				$this->initData('page', $_SESSION['ZWII_CONTENT'], $sample);
				$this->initData('module', $_SESSION['ZWII_CONTENT'], $sample);
				$this->initData('locale', $_SESSION['ZWII_CONTENT'], $sample);

				// Création de l'utilisateur si les données sont complétées.
				// success retour de l'enregistrement des données
				$this->setData([
					'user',
					$userId,
					[
						'firstname' => $userFirstname,
						'forgot' => 0,
						'group' => self::GROUP_ADMIN,
						'lastname' => $userLastname,
						'pseudo' => 'Admin',
						'signature' => 1,
						'mail' => $userMail,
						'password' => $this->getInput('installPassword', helper::FILTER_PASSWORD, true),
						'language' => $_SESSION['ZWII_CONTENT']
					]
				]);

				// Envoie le mail
				// Sent contient true si réussite sinon code erreur d'envoi en clair
				$this->sendMail(
					$userMail,
					'Installation de votre site',
					'Bonjour' . ' <strong>' . $userFirstname . ' ' . $userLastname . '</strong>,<br><br>' .
					'Voici les détails de votre installation.<br><br>' .
					'<strong>URL du site :</strong> <a href="' . helper::baseUrl(false) . '" target="_blank">' . helper::baseUrl(false) . '</a><br>' .
					'<strong>Identifiant du compte :</strong> ' . $this->getInput('installId') . '<br>',
					null,
					'localhost'
				);

				// Nettoyage fr par défaut
				if (
					$_SESSION['ZWII_CONTENT'] !== 'fr_FR'
				) {
					if (is_dir(self::DATA_DIR . 'fr_FR'))
						$this->deleteDir(self::DATA_DIR . 'fr_FR');
				}

				// Sauvegarder la configuration du Proxy
				$this->setData(['config', 'proxyType', $this->getInput('installProxyType')]);
				$this->setData(['config', 'proxyUrl', $this->getInput('installProxyUrl')]);
				$this->setData(['config', 'proxyPort', $this->getInput('installProxyPort', helper::FILTER_INT)]);

				// Images exemples livrées dans tous les cas
				try {
					// Décompression dans le dossier de fichier temporaires
					if (file_exists(self::TEMP_DIR . 'files.tar.gz')) {
						unlink(self::TEMP_DIR . 'files.tar.gz');
					}
					if (file_exists(self::TEMP_DIR . 'files.tar')) {
						unlink(self::TEMP_DIR . 'files.tar');
					}
					copy('core/module/install/ressource/files.tar.gz', self::TEMP_DIR . 'files.tar.gz');
					$pharData = new PharData(self::TEMP_DIR . 'files.tar.gz');
					$pharData->decompress();
					// Installation
					$pharData->extractTo(__DIR__ . '/../../../', null, true);
				} catch (Exception $e) {
					$success = $e->getMessage();
				}

				// Nettoyage
				unlink(self::TEMP_DIR . 'files.tar.gz');
				unlink(self::TEMP_DIR . 'files.tar');

				// Créer le dossier des fontes
				if (!is_dir(self::DATA_DIR . 'font')) {
					mkdir(self::DATA_DIR . 'font');
				}

				// Installation du thème sélectionné
				$dataThemes = json_decode(file_get_contents('core/module/install/ressource/themes/themes.json'), true);
				$dataThemes = $dataThemes['themes'];
				$themeFilename = $dataThemes[$this->getInput('installTheme', helper::FILTER_STRING_SHORT)]['filename'];
				if ($themeFilename !== '') {
					$theme = new theme;
					$theme->import('core/module/install/ressource/themes/' . $themeFilename);
				}

				// Copie des thèmes dans les fichiers
				if (!is_dir(self::FILE_DIR . 'source/theme')) {
					mkdir(self::FILE_DIR . 'source/theme');
				}
				$this->copyDir('core/module/install/ressource/themes', self::FILE_DIR . 'source/theme');
				unlink(self::FILE_DIR . 'source/theme/themes.json');

				// Copie des langues de l'UI et génération de la base de données
				if (is_dir(self::I18N_DIR) === false) {
					mkdir(self::I18N_DIR);
				}

				// Créer la base de données des langues
				// copy('core/module/install/ressource/i18n/language.json', self::DATA_DIR . 'language.json');
				$this->copyDir('core/module/install/ressource/i18n', self::I18N_DIR);
				// unlink(self::I18N_DIR . 'language.json');

				// Fixe l'adresse from pour les envois d'email
				$this->setData(['config', 'smtp', 'from', 'no-reply@' . str_replace('www.', '', $_SERVER['HTTP_HOST'])]);

				// Supprimé à cause de l'écrasement des bases
				//$this->setData(['module', 'blog', 'posts', 'mon-premier-article', 'userId', $userId]);
				//$this->setData(['module', 'blog', 'posts', 'mon-deuxieme-article', 'userId', $userId]);
				//$this->setData(['module', 'blog', 'posts', 'mon-troisieme-article', 'userId', $userId]);

				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl(),
					'notification' => helper::translate('Installation terminée'),
					'state' => true
				]);
			}

			// Affichage du formulaire

			// Récupération de la liste des thèmes
			$dataThemes = json_decode(file_get_contents('core/module/install/ressource/themes/themes.json'), true);
			$dataThemes = $dataThemes['themes'];
			self::$themes = helper::arrayColumn($dataThemes, 'name');

			// Valeurs en sortie
			$this->addOutput([
				'display' => self::DISPLAY_LAYOUT_LIGHT,
				'title' => helper::translate('ZwiiCMS Installation'),
				'view' => 'postinstall'
			]);
		}
	}

	/**
	 * Étapes de mise à jour
	 */
	public function steps()
	{
		// Action interdite
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			switch ($this->getInput('step', helper::FILTER_INT)) {
				// Préparation
				case 1:
					$success = true;
					$message = '';
					// RAZ la mise à jour auto
					$this->setData(['core', 'updateAvailable', false]);
					// Backup du dossier Data
					helper::autoBackup(self::BACKUP_DIR, ['backup', 'tmp', 'file']);
					// Sauvegarde htaccess
					if ($this->getData(['config', 'autoUpdateHtaccess'])) {
						$success = copy('.htaccess', '.htaccess' . '.bak');
						$message = $success ? '' : 'Erreur de copie du fichier htaccess';
					}
					// Nettoyage des fichiers d'installation précédents
					if (file_exists(self::TEMP_DIR . 'update.tar.gz') && $success) {
						$success = unlink(self::TEMP_DIR . 'update.tar.gz');
						$message = $success ? '' : 'Impossible d\'effacer la mise à jour précédente';
					}
					if (file_exists(self::TEMP_DIR . 'update.tar') && $success) {
						$success = unlink(self::TEMP_DIR . 'update.tar');
						$message = $success ? '' : 'Impossible d\'effacer la mise à jour précédente';
					}
					// Valeurs en sortie
					$this->addOutput([
						'display' => self::DISPLAY_JSON,
						'content' => [
							'success' => $success,
							'data' => $success ? null : json_encode($message, JSON_UNESCAPED_UNICODE)
						]
					]);
					break;
				// Téléchargement
				case 2:
					file_put_contents(self::TEMP_DIR . 'update.tar.gz', helper::getUrlContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/update.tar.gz'));
					$md5origin = helper::getUrlContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/update.md5');
					$md5origin = explode(' ', $md5origin);
					$md5target = md5_file(self::TEMP_DIR . 'update.tar.gz');
					// Vérifier si les checksums correspondent
					if ($md5origin[0] === $md5target) {
						$success = true;
						$message = "";
					} else {
						$success = false;
						$message = json_encode('Erreur de téléchargement ou de somme de contrôle', JSON_UNESCAPED_UNICODE);
						if (file_exists(self::TEMP_DIR . 'update.tar.gz')) {
							unlink(self::TEMP_DIR . 'update.tar.gz');
							http_response_code(500);
						}
					}

					// Valeurs en sortie
					$this->addOutput([
						'display' => self::DISPLAY_JSON,
						'content' => [
							'success' => $success,
							'data' => $message
						]
					]);
					break;
				// Installation
				case 3:
					$success = true;
					// Check la réécriture d'URL avant d'écraser les fichiers
					$rewrite = helper::checkRewrite();
					// Décompression et installation
					try {
						// Décompression dans le dossier de fichier temporaires
						$pharData = new PharData(self::TEMP_DIR . 'update.tar.gz');
						$pharData->decompress();
						// Installation
						$pharData->extractTo(__DIR__ . '/../../../', null, true);
					} catch (Exception $e) {
						$success = false;
						http_response_code(500);
					}
					// Nettoyage du dossier
					if (file_exists(self::TEMP_DIR . 'update.tar.gz')) {
						unlink(self::TEMP_DIR . 'update.tar.gz');
					}
					if (file_exists(self::TEMP_DIR . 'update.tar')) {
						unlink(self::TEMP_DIR . 'update.tar');
					}
					// Valeurs en sortie
					$this->addOutput([
						'display' => self::DISPLAY_JSON,
						'content' => [
							'success' => $success,
							'data' => $rewrite
						]
					]);
					break;
				// Configuration
				case 4:
					$success = true;
					$message = '';
					$rewrite = $this->getInput('data');

					/**
					 * Restaure le fichier htaccess
					 */
					// Recopie htaccess
					if (
						$this->getData(['config', 'autoUpdateHtaccess']) === true
					) {
						// L'écraser avec le backup
						$success = copy('.htaccess.bak', '.htaccess');
						if ($success === false) {
							$message = helper::translate('La copie de sauvegarde du fichier htaccess n\'a pas été restaurée !');
							http_response_code(500);
						}
						// Effacer le backup
						unlink('.htaccess.bak');
					} else {
						/**
						 * Restaure la réécriture d'URL
						 */
						if ($rewrite === 'true') { // Ajout des lignes dans le .htaccess
							$fileContent = file_get_contents('.htaccess');
							$rewriteData = PHP_EOL .
								'# URL rewriting' . PHP_EOL .
								'<IfModule mod_rewrite.c>' . PHP_EOL .
								"\tRewriteEngine on" . PHP_EOL .
								"\tRewriteBase " . helper::baseUrl(false, false) . PHP_EOL .
								"\tRewriteCond %{REQUEST_FILENAME} !-f" . PHP_EOL .
								"\tRewriteCond %{REQUEST_FILENAME} !-d" . PHP_EOL .
								"\tRewriteRule ^(.*)$ index.php?$1 [L]" . PHP_EOL .
								'</IfModule>' . PHP_EOL .
								'# URL rewriting' . PHP_EOL;
							$fileContent = str_replace('# URL rewriting', $rewriteData, $fileContent);
							$success = file_put_contents(
								'.htaccess',
								$fileContent
							);
						}
					}

					/**
					 * Met à jour les dictionnaires des langues depuis les nouveaux modèles installés
					 */
					require_once('core/module/install/ressource/defaultdata.php');
					$installedLanguages = $this->getData(['language']);
					$defaultLanguages = init::$defaultData['language'];
					foreach ($installedLanguages as $key => $value) {

						//var_dump( $defaultLanguages[$key]['date'] > $value['date'] );
						if (
							isset($defaultLanguages[$key]['date']) &&
							$defaultLanguages[$key]['date'] > $value['date'] &&
							isset($defaultLanguages[$key]['version']) &&
							$defaultLanguages[$key]['version'] >= $value['version']

						) {
							copy('core/module/install/ressource/i18n/' . $key . '.json', self::I18N_DIR . $key . '.json');
							$this->setData(['language', $key, $defaultLanguages[$key]]);
						}
					}

					// Valeurs en sortie
					$this->addOutput([
						'display' => self::DISPLAY_JSON,
						'content' => [
							'success' => $success,
							'data' => json_encode($message, JSON_UNESCAPED_UNICODE)
						]
					]);
			}
		}
	}

	/**
	 * Mise à jour
	 */
	public function update()
	{
		// Action interdite
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Nouvelle version
			self::$newVersion = helper::getUrlContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/version');
			// Valeurs en sortie
			$this->addOutput([
				'display' => self::DISPLAY_LAYOUT_LIGHT,
				'title' => helper::translate('Mise à jour'),
				'view' => 'update'
			]);
		}
	}

}