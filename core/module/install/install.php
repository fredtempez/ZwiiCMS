<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @license GNU General Public License, version 3
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
	public static $themes =   [];

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
		// Accès autorisé
		else {
			// Soumission du formulaire
			if ($this->isPost()) {
				$lang = $this->getInput('installLanguage');
				setcookie('ZWII_UI', $lang, time() + 3600, helper::baseUrl(false, false), '', helper::isHttps(), true);
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'install/postinstall/' . $lang
				]);
			}
		}

		// Liste des langues UI disponibles
		if (is_dir(self::I18N_DIR)) {
			$dir = getcwd();
			chdir(self::I18N_DIR);
			$files = glob('*.json');
			// Ajouter une clé au tableau avec le code de langue
			foreach ($files as $file) {
				// La langue est-elle référencée ?
				if (array_key_exists(basename($file, '.json'), self::$languages)) {
					self::$i18nFiles[basename($file, '.json')] = self::$languages[basename($file, '.json')];
				}
			}
			chdir($dir);
		}

		$this->addOutput([
			'display' => self::DISPLAY_LAYOUT_LIGHT,
			'title' => helper::translate('Installation'),
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
			if ($this->isPost()) {

				$success = true;

				// Validation de la langue transmise
				$lang = array_key_exists($this->getUrl(2), self::$languages) ? $this->getUrl(2) : 'fr_FR';

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

				// Création de l'utilisateur si les données sont complétées.
				// success retour de l'enregistrement des données
				$success = $this->setData([
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
						'language' => $lang
					]
				]);

				// Compte créé, envoi du mail et création des données du site
				if ($success) { // Formulaire complété envoi du mail
					// Envoie le mail
					// Sent contient true si réussite sinon code erreur d'envoi en clair
					$sent = $this->sendMail(
						$userMail,
						'Installation de votre site',
						'Bonjour' . ' <strong>' . $userFirstname . ' ' . $userLastname . '</strong>,<br><br>' .
							'Voici les détails de votre installation.<br><br>' .
							'<strong>URL du site :</strong> <a href="' . helper::baseUrl(false) . '" target="_blank">' . helper::baseUrl(false) . '</a><br>' .
							'<strong>Identifiant du compte :</strong> ' . $this->getInput('installId') . '<br>',
						null
					);

					// Nettoyer les cookies de langue d'une précédente installation
					helper::deleteCookie('ZWII_CONTENT');


					// Effacer le dossier de contenu fr créé par défaut si la langue est différente.

					/*if (
						self::$i18nContent !== 'fr_FR'
						&& is_dir('site/data/fr')
					) {
						$this->removeDir('site/data/fr');
					}*/

					// Installation du site de test
					if ($this->getInput('installDefaultData', helper::FILTER_BOOLEAN) === FALSE) {
						$this->initData('page', self::$i18nContent, true);
						$this->initData('module', self::$i18nContent, true);
						$this->setData(['module', 'blog', 'posts', 'mon-premier-article', 'userId', $userId]);
						$this->setData(['module', 'blog', 'posts', 'mon-deuxieme-article', 'userId', $userId]);
						$this->setData(['module', 'blog', 'posts', 'mon-troisieme-article', 'userId', $userId]);
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
					helper::deleteCookie('ZWII_UI');

					// Créer le dossier des fontes
					if (!is_dir(self::DATA_DIR . 'fonts')) {
						mkdir(self::DATA_DIR . 'fonts');
					}

					// Installation du thème sélectionné
					$dataThemes = file_get_contents('core/module/install/ressource/themes/themes.json');
					$dataThemes = json_decode($dataThemes, true);
					$themeId = $dataThemes[$this->getInput('installTheme', helper::FILTER_STRING_SHORT)]['filename'];
					if ($themeId !== 'default') {
						$theme = new theme;
						$theme->import('core/module/install/ressource/themes/' . $themeId);
					}

					// Copie des thèmes dans les fichiers
					if (!is_dir(self::FILE_DIR . 'source/theme')) {
						mkdir(self::FILE_DIR . 'source/theme');
					}
					$this->copyDir('core/module/install/ressource/themes', self::FILE_DIR . 'source/theme');
					unlink(self::FILE_DIR . 'source/theme/themes.json');

					// Créer sitemap
					$this->createSitemap();
					// Mise à jour de la liste des pages pour TinyMCE
					$this->listPages();

					// Valeurs en sortie
					$this->addOutput([
						'redirect' => helper::baseUrl(false),
						'notification' => $sent === true ? helper::translate('Installation terminée') : $sent,
						'state' => ($sent === true &&  $success === true) ? true : null
					]);
				}
			}

			// Affichage du formulaire

			// Récupération de la liste des thèmes
			$dataThemes = file_get_contents('core/module/install/ressource/themes/themes.json');
			$dataThemes = json_decode($dataThemes, true);
			self::$themes = helper::arrayColumn($dataThemes, 'name');

			// Valeurs en sortie
			$this->addOutput([
				'display' => self::DISPLAY_LAYOUT_LIGHT,
				'title' => helper::translate('Installation'),
				'view' => 'postinstall'
			]);
		}
	}

	/**
	 * Étapes de mise à jour
	 */
	public function steps()
	{
		switch ($this->getInput('step', helper::FILTER_INT)) {
				// Préparation
			case 1:
				$success = true;
				// RAZ la mise à jour auto
				$this->setData(['core', 'updateAvailable', false]);
				// Backup du dossier Data
				helper::autoBackup(self::BACKUP_DIR, ['backup', 'tmp', 'file']);
				// Sauvegarde htaccess
				if ($this->getData(['config', 'autoUpdateHtaccess'])) {
					$success = copy('.htaccess', '.htaccess' . '.bak');
				}
				// Nettoyage des fichiers d'installation précédents
				if (file_exists(self::TEMP_DIR . 'update.tar.gz') && $success) {
					$success = unlink(self::TEMP_DIR . 'update.tar.gz');
				}
				if (file_exists(self::TEMP_DIR . 'update.tar') && $success) {
					$success = unlink(self::TEMP_DIR . 'update.tar');
				}
				// Valeurs en sortie
				$this->addOutput([
					'display' => self::DISPLAY_JSON,
					'content' => [
						'success' => $success,
						'data' => null
					]
				]);
				break;
				// Téléchargement
			case 2:
				file_put_contents(self::TEMP_DIR . 'update.tar.gz', helper::getUrlContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/update.tar.gz'));
				$md5origin = helper::getUrlContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/update.md5');
				$md5origin = (explode(' ', $md5origin));
				$md5target = md5_file(self::TEMP_DIR . 'update.tar.gz');
				// Valeurs en sortie
				$this->addOutput([
					'display' => self::DISPLAY_JSON,
					'content' => [
						'success' => $md5origin[0] === $md5target,
						'data' => null
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
					$success = $e->getMessage();
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
				$rewrite = $this->getInput('data');
				// Réécriture d'URL
				if ($rewrite === "true") {					// Ajout des lignes dans le .htaccess
					$fileContent = file_get_contents('.htaccess');
					$rewriteData = 	PHP_EOL .
						'# URL rewriting' .  PHP_EOL .
						'<IfModule mod_rewrite.c>' . PHP_EOL .
						"\tRewriteEngine on" . PHP_EOL .
						"\tRewriteBase " . helper::baseUrl(false, false) . PHP_EOL .
						"\tRewriteCond %{REQUEST_FILENAME} !-f" . PHP_EOL .
						"\tRewriteCond %{REQUEST_FILENAME} !-d" . PHP_EOL .
						"\tRewriteRule ^(.*)$ index.php?$1 [L]" . PHP_EOL .
						'</IfModule>' . PHP_EOL .
						'# URL rewriting' . PHP_EOL;
					$fileContent = str_replace('# URL rewriting', $rewriteData, $fileContent);
					file_put_contents(
						'.htaccess',
						$fileContent
					);
				}
				// Recopie htaccess
				if (
					$this->getData(['config', 'autoUpdateHtaccess']) &&
					$success && file_exists('.htaccess.bak')
				) {
					// L'écraser avec le backup
					$success = copy('.htaccess.bak', '.htaccess');
					// Effacer le backup
					unlink('.htaccess.bak');
				}
				// Valeurs en sortie
				$this->addOutput([
					'display' => self::DISPLAY_JSON,
					'content' => [
						'success' => $success,
						'data' => null
					]
				]);
				break;
		}
	}

	/**
	 * Mise à jour
	 */
	public function update()
	{
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
