<?php

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */


class install extends common {

	public static $actions = [
		'index' => self::GROUP_VISITOR,
		'steps' => self::GROUP_ADMIN,
		'update' => self::GROUP_ADMIN,
		'removeAll' => self::GROUP_ADMIN
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
				// Créer les dossiers
				if (!is_dir(self::FILE_DIR.'source/banniere/')) {
					mkdir(self::FILE_DIR.'source/banniere/');}
				if (!is_dir(self::FILE_DIR.'thumb/banniere/')) {
					mkdir(self::FILE_DIR.'thumb/banniere/');
					}
				// Copier les fichiers
				copy('core/module/install/ressource/file/source/banniere960.jpg',self::FILE_DIR.'source/banniere/banniere960.jpg');
				copy('core/module/install/ressource/file/thumb/banniere960.jpg',self::FILE_DIR.'thumb/banniere/banniere960.jpg');
				// Copie des icônes
				copy('core/module/install/ressource/file/source/favicon.ico',self::FILE_DIR.'source/favicon.ico');
				copy('core/module/install/ressource/file/source/faviconDark.ico',self::FILE_DIR.'source/faviconDark.ico');
				// Configure certaines données par défaut
				if ($this->getInput('installDefaultData',helper::FILTER_BOOLEAN) === FALSE) {
					$this->initData('page','fr',true);
					$this->initData('module','fr',true);
					$this->setData(['module', 'blog', 'mon-premier-article', 'userId', $userId]);
					$this->setData(['module', 'blog', 'mon-deuxieme-article', 'userId', $userId]);
					$this->setData(['module', 'blog', 'mon-troisieme-article', 'userId', $userId]);
				}
				// Stocker le dossier d'installation
				$this->setData(['core', 'baseUrl', helper::baseUrl(false,false) ]);
				// Générer un fichier  robots.txt
				$this->createRobots();
				// Créer sitemap
				$this->createSitemap();
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl(false),
					'notification' => ($sent === true ? 'Installation terminée' : $sent),
					'state' => ($sent === true ? true : null)
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
				// Téléchargement depuis le serveur de Zwii
				$success = (file_put_contents(self::TEMP_DIR.'update.tar.gz', helper::urlGetContents('https://zwiicms.fr/update/' . common::ZWII_UPDATE_CHANNEL . '/update.tar.gz')) !== false);
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
		self::$newVersion = helper::urlGetContents('http://zwiicms.com/update/' . common::ZWII_UPDATE_CHANNEL . '/version');
		// Valeurs en sortie
		$this->addOutput([
			'display' => self::DISPLAY_LAYOUT_LIGHT,
			'title' => 'Mise à jour',
			'view' => 'update'
		]);
	}

	/**
	* Effacer un dossier non vide.
	*/
	private function removeAll ( $path ) {
		foreach ( new DirectoryIterator($path) as $item ):
			if ( $item->isFile() ) unlink($item->getRealPath());
			if ( !$item->isDot() && $item->isDir() ) $this->removeAll($item->getRealPath());
		endforeach;

		rmdir($path);
	}

}