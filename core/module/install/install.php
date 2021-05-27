<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2021, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */


class install extends common {

	public static $actions = [
		'index' => self::GROUP_VISITOR,
		'steps' => self::GROUP_ADMIN,
		'update' => self::GROUP_ADMIN
	];


	public static $newVersion;


	/**
	 * Installation
	 */
	public function index() {
		// Accès refusé
		if($this->getData(['user']) !== []) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Accès autorisé
		else {
			// Soumission du formulaire
			if($this->isPost()) {
				$success = true;
				// Double vérification pour le mot de passe
				if($this->getInput('installPassword', helper::FILTER_STRING_SHORT, true) !== $this->getInput('installConfirmPassword', helper::FILTER_STRING_SHORT, true)) {
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
						'password' => $this->getInput('installPassword', helper::FILTER_PASSWORD, true)
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
				// Installation du site de test
				if ($this->getInput('installDefaultData',helper::FILTER_BOOLEAN) === FALSE) {
					$this->initData('page','fr',true);
					$this->initData('module','fr',true);
					$this->setData(['module', 'blog', 'posts', 'mon-premier-article', 'userId', $userId]);
					$this->setData(['module', 'blog', 'posts', 'mon-deuxieme-article', 'userId', $userId]);
					$this->setData(['module', 'blog', 'posts', 'mon-troisieme-article', 'userId', $userId]);
				}
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
				unlink(self::TEMP_DIR . 'files.tar.gz');
				unlink(self::TEMP_DIR . 'files.tar');
				// Copie des favicons
				copy('core/module/install/ressource/favicon.ico', self::FILE_DIR . 'source/favicon.ico');
				copy('core/module/install/ressource/faviconDark.ico', self::FILE_DIR . 'source/favicon.ico');
				// Stocker le dossier d'installation
				$this->setData(['core', 'baseUrl', helper::baseUrl(false,false) ]);
				// Créer sitemap
				$this->createSitemap();
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl(false),
					'notification' => $sent === true ? 'Installation terminée' : $sent,
					'state' => ($sent === true &&  $success === true) ? true : null
				]);
				}
			}

			// Valeurs en sortie
			$this->addOutput([
				'display' => self::DISPLAY_LAYOUT_LIGHT,
				'title' => 'Installation',
				'view' => 'index'
			]);
		}
	}

	/**
	 * Étapes de mise à jour
	 */
	public function steps() {
		switch($this->getInput('step', helper::FILTER_INT)) {
			// Préparation
			case 1:
				$success = true;
				// RAZ la mise à jour auto
				$this->setData(['core','updateAvailable', false]);
				// Backup du dossier Data
				helper::autoBackup(self::BACKUP_DIR,['backup','tmp','file']);
				// Sauvegarde htaccess
				if ($this->getData(['config','autoUpdateHtaccess'])) {
					$success = copy('.htaccess', '.htaccess' . '.bak');
				}
				// Nettoyage des fichiers d'installation précédents
				if(file_exists(self::TEMP_DIR.'update.tar.gz') && $success) {
					$success = unlink(self::TEMP_DIR.'update.tar.gz');
				}
				if(file_exists(self::TEMP_DIR.'update.tar') && $success) {
					$success = unlink(self::TEMP_DIR.'update.tar');
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
				// Téléchargement depuis le serveur de ZwiiCMS
				// URL de téléchargement sur le site
				//$success = (file_put_contents(self::TEMP_DIR.'update.tar.gz', helper::urlGetContents('https://zwiicms.fr/update/' . common::ZWII_UPDATE_CHANNEL . '/update.tar.gz')) !== false);
				// URL sur le git hub
				//$newVersion = helper::urlGetContents('https://zwiicms.fr/update/' . common::ZWII_UPDATE_CHANNEL . '/version');
				$success = (file_put_contents(self::TEMP_DIR.'update.tar.gz', helper::urlGetContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/update.tar.gz')) !== false);
				// Valeurs en sortie
				$this->addOutput([
					'display' => self::DISPLAY_JSON,
					'content' => [
						'success' => $success,
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
					$pharData = new PharData(self::TEMP_DIR.'update.tar.gz');
					$pharData->decompress();
					// Installation
					$pharData->extractTo(__DIR__ . '/../../../', null, true);
				} catch (Exception $e) {
					$success = $e->getMessage();
				}
				// Nettoyage du dossier
				if(file_exists(self::TEMP_DIR.'update.tar.gz')) {
					unlink(self::TEMP_DIR.'update.tar.gz');
				}
				if(file_exists(self::TEMP_DIR.'update.tar')) {
					unlink(self::TEMP_DIR.'update.tar');
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
				if ($rewrite === "true") {
					$success = (file_put_contents(
						'.htaccess',
						PHP_EOL .
						'<ifModule mod_rewrite.c>' . PHP_EOL .
						"\tRewriteEngine on" . PHP_EOL .
						"\tRewriteBase " . helper::baseUrl(false, false) . PHP_EOL .
						"\tRewriteCond %{REQUEST_FILENAME} !-f" . PHP_EOL .
						"\tRewriteCond %{REQUEST_FILENAME} !-d" . PHP_EOL .
						"\tRewriteRule ^(.*)$ index.php?$1 [L]" . PHP_EOL .
						'</ifModule>',
						FILE_APPEND
					) !== false);
				}
				// Recopie htaccess
				if ($this->getData(['config','autoUpdateHtaccess']) &&
					$success && file_exists( '.htaccess.bak')
				) {
						// L'écraser avec le backup
						$success = copy( '.htaccess.bak' ,'.htaccess' );
						// Effacer l ebackup
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
	public function update() {
		// Nouvelle version
		self::$newVersion = helper::urlGetContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/version');
		// Valeurs en sortie
		$this->addOutput([
			'display' => self::DISPLAY_LAYOUT_LIGHT,
			'title' => 'Mise à jour',
			'view' => 'update'
		]);
	}


}