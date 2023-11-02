<?php

/**
 * This file is part of Zwii.
 *
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

class plugin extends common
{

	public static $actions = [
		'index' => self::GROUP_ADMIN,
		'delete' => self::GROUP_ADMIN,
		'save' => self::GROUP_ADMIN,
		'store' => self::GROUP_ADMIN,
		'item' => self::GROUP_ADMIN,
		// détail d'un objet
		'upload' => self::GROUP_ADMIN,
		// Téléverser catalogue
		'uploadItem' => self::GROUP_ADMIN // Téléverser par archive
	];

	// URL des modules
	const BASEURL_STORE = 'https://store.zwiicms.fr/';
	const MODULE_STORE = '?modules/';


	// Gestion des modules
	public static $modulesData = [];
	public static $modulesOrphan = [];
	public static $modulesInstalled = [];

	// pour tests
	public static $valeur = [];

	// le catalogue
	public static $storeList = [];
	public static $storeItem = [];

	// Liste de pages
	public static $pagesList = [];


	/*
	 * Effacement d'un module installé et non utilisé
	 */
	public function delete()
	{

		// Action interdite
		if ($this->getUser('permission', __CLASS__, __FUNCTION__) !== true) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Suppression des dossiers
			$infoModules = helper::getModules();
			$module = $this->getUrl(2);
			//Liste des dossiers associés au module non effacés
			if (
				is_dir('./module/' . $module) &&
				$this->deleteDir('./module/' . $module) === true
			) {
				$success = true;
				$notification = 'Module ' . $module . ' désinstallé';
				if (($infoModules[$this->getUrl(2)]['dataDirectory'])) {
					if (
						is_dir($infoModules[$this->getUrl(2)]['dataDirectory'])
					) {
						$s = $this->deleteDir($infoModules[$this->getUrl(2)]['dataDirectory']);
						$notification = $s === false ? sprintf(helper::translate('Le module %s est désinstallé, il reste peut-être des données dans %s'), $module, $infoModules[$this->getUrl(2)]['dataDirectory']) : $notification;
					}
				}
			} else {
				$success = false;
				$notification = helper::translate('La suppression a échoué');
			}

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'plugin',
				'notification' => $notification,
				'state' => $success
			]);
		}
	}

	/***
	 * Installation d'un module
	 * Fonction utilisée par upload et storeUpload
	 */
	private function install($moduleFileName, $checkValid)
	{

		// Dossier temporaire
		$tempFolder = uniqid() . '/';

		/**
		 * Désarchivage
		 */
		$zip = new ZipArchive();
		if ($zip->open($moduleFileName) === true) {

			//Création du dossier temporaire et extraction
			if (!is_dir(self::TEMP_DIR . $tempFolder)) {
				mkdir(self::TEMP_DIR . $tempFolder, 0755);
			}
			$zip->extractTo(self::TEMP_DIR . $tempFolder);

			/**
			 * Lecture du descripteur de ressource
			 * $module ['name'] = id du module, correspond à la classe
			 * $module ['realname'] = Nom complet du module
			 * $module ['version'] = version du module
			 * $module ['dirs'] @array
			 * 		'dossier' => 'destination',
			 * 		'download" => 'module/download'
			 */

			if (file_exists(self::TEMP_DIR . $tempFolder . 'enum.json')) {
				$module = json_decode(file_get_contents(self::TEMP_DIR . $tempFolder . 'enum.json'), true);
			} else {
				// Message de retour
				$this->deleteDir(self::TEMP_DIR . $tempFolder);
				$zip->close();
				return ([
					'success' => false,
					'notification' => helper::translate('Archive invalide, le descripteur est absent')
				]);
			}
			/**
			 * Validation des informations du descripteur
			 */
			if (isset($module['dirs'])) {
				foreach ($module['dirs'] as $src => $dest) {
					// Vérification de la présence des dossier décrits
					if (!is_dir(self::TEMP_DIR . $tempFolder . $src)) {
						// Message de retour
						$this->deleteDir(self::TEMP_DIR . $tempFolder);
						$zip->close();
						return ([
							'success' => false,
							'notification' => helper::translate('Archive invalide, les dossiers ne correspondent pas au descripteur')
						]);
					}
					// Interdire l'écriture dans le dossier core
					if (strstr($dest, 'core') !== false) {
						// Message de retour
						$this->deleteDir(self::TEMP_DIR . $tempFolder);
						$zip->close();
						return ([
							'success' => false,
							'notification' => helper::translate('Archive invalide, l\'écriture dans le dossier core est interdite')
						]);
					}
				}
			}

			/**
			 * Validation de la présence du fichier de base du module
			 */
			if (!file_exists(self::TEMP_DIR . $tempFolder . $module['name'] . '.php')) {
				// Message de retour
				$this->deleteDir(self::TEMP_DIR . $tempFolder);
				$zip->close();
				return ([
					'success' => false,
					'notification' => helper::translate('Archive invalide, le fichier de classe est absent')
				]);
			}


			/**
			 * Le module est-il déjà installé ?
			 * Si oui lire le numéro de version et le stocker dans $versionInstalled
			 */
			if (is_file(self::MODULE_DIR . $module['name'] . '/' . $module['name'] . '.php')) {
				$c = helper::getModules();
				if (array_key_exists($module['name'], $c)) {
					$versionInstalled = $c[$module['name']]['version'];
				}
			}

			// Le module est installé, contrôle de la version
			$installOk = false;
			if (isset($versionInstalled) === false) {
				$installOk = true;
			} elseif (version_compare($module['version'], $versionInstalled) >= 0) {
				$installOk = true;
			} else {
				if (version_compare($module['version'], $versionInstalled) === -1) {
					// Contrôle du forçage
					if ($this->getInput('configModulesCheck', helper::FILTER_BOOLEAN) === true) {
						$installOk = true;
					} else {
						// Message de retour
						$this->deleteDir(self::TEMP_DIR . $tempFolder);
						$zip->close();
						return ([
							'success' => false,
							'notification' => helper::translate('La version installée est plus récente')
						]);
					}
				}
			}

			// Installation ou mise à jour du module valides
			if ($installOk) {
				// Copie du module
				$success = $this->copyDir(self::TEMP_DIR . $tempFolder, self::MODULE_DIR . $module['name']);
				// Copie récursive des dossiers externes
				if (is_array($module['dataDirectory'])) {
					foreach ($module['dataDirectory'] as $src => $dest) {
						if (!is_dir(self::TEMP_DIR . $tempFolder . $src)) {
							mkdir(self::TEMP_DIR . $tempFolder . $src);
						}
						$success = $success || $this->copyDir(self::TEMP_DIR . $tempFolder . $src, $dest);
					}
				}
				// Message de retour
				$t = isset($versionInstalled) ? helper::translate('actualisé') : helper::translate('installé');
				$this->deleteDir(self::TEMP_DIR . $tempFolder);
				$zip->close();
				return ([
					'success' => $success,
					'notification' => $success
					? sprintf(helper::translate('Le module %s a été %s'), $module['name'], $t)
					: helper::translate('Erreur inconnue, le module n\'est pas installé')
				]);
			} else {
				// Supprimer le dossier temporaire
				$this->deleteDir(self::TEMP_DIR . $tempFolder);
				$zip->close();
				return ([
					'success' => false,
					'notification' => helper::translate('Erreur inconnue, le module n\'est pas installé')
				]);

			}
		} else {
			// Message de retour
			return ([
				'success' => false,
				'notification' => helper::translate('Impossible d\'ouvrir l\'archive')
			]);
		}
	}

	/***
	 * Installation d'un module à partir du gestionnaire de fichier
	 */
	public function upload()
	{
		// Soumission du formulaire

		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Installation d'un module
			$checkValidMaj = $this->getInput('configModulesCheck', helper::FILTER_BOOLEAN);
			$zipFilename = $this->getInput('configModulesInstallation', helper::FILTER_STRING_SHORT);
			if ($zipFilename !== '') {
				$state = $this->install(self::FILE_DIR . 'source/' . $zipFilename, $checkValidMaj);
			}
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'plugin',
				'notification' => $state['notification'],
				'state' => $state['success']
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Installer un module'),
			'view' => 'upload'
		]);
	}

	/***
	 * Installation  d'un module depuis le catalogue
	 */
	public function uploadItem()
	{
		// Action interdite
		if ($this->getUser('permission', __CLASS__, __FUNCTION__) !== true) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Récupérer le module en ligne
			$moduleName = $this->getUrl(2);
			// Informations sur les module en ligne
			$store = json_decode(helper::getUrlContents(self::BASEURL_STORE . self::MODULE_STORE . 'list'), true);
			// Url du module à télécharger
			$moduleFilePath = $store[$moduleName]['file'];
			// Télécharger le fichier
			$moduleData = helper::getUrlContents(self::BASEURL_STORE . self::FILE_DIR . 'source/' . $moduleFilePath);
			// Extraire de l'arborescence
			$d = explode('/', $moduleFilePath);
			$moduleFile = $d[count($d) - 1];
			// Créer le dossier modules
			if (!is_dir(self::FILE_DIR . 'source/modules')) {
				mkdir(self::FILE_DIR . 'source/modules', 0755);
			}
			// Sauver les données du fichiers
			file_put_contents(self::FILE_DIR . 'source/modules/' . $moduleFile, $moduleData);

			// Installation directe
			if (file_exists(self::FILE_DIR . 'source/modules/' . $moduleFile)) {
				$r = $this->install(self::FILE_DIR . 'source/modules/' . $moduleFile, false);
			} else {
				$r['notification'] = helper::translate('Erreur inconnue, le module n\'est pas installé');
				$r['success'] = false;
			}
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'plugin/store',
				'notification' => $r['notification'],
				'state' => $r['success']
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Catalogue de modules'),
			'view' => 'store'
		]);
	}

	/**
	 * Catalogue des modules sur le site ZwiiCMS.fr
	 */
	public function store()
	{
		$store = json_decode(helper::getUrlContents(self::BASEURL_STORE . self::MODULE_STORE . 'list'), true);

		if ($store) {
			// Modules installés
			$infoModules = helper::getModules();

			// Clés moduleIds dans les pages
			$inPages = helper::arrayColumn($this->getData(['page']), 'moduleId', 'SORT_DESC');

			// Parcourir les données des modules
			foreach ($store as $key => $value) {
				if (empty($key)) {
					continue;
				}
				$pageInfos = array_keys($inPages, $key);
				// Module non installé
				$ico = template::ico('download');
				$class = '';
				$help = 'Télécharger le module dans le gestionnaire de fichiers';
				// Le module est installé
				if (array_key_exists($key, $infoModules) === true) {
					$class = 'buttonGreen';
					$ico = template::ico('update');
					$help = 'Mettre à jour le module orphelin';
				}
				// Le module est installé et utilisé
				if (in_array($key, $inPages) === true) {
					$class = 'buttonRed';
					$ico = template::ico('update');
					$help = 'Mettre à jour le module attaché, une sauvegarde des données de module est recommandée !';
				}
				self::$storeList[] = [
					$store[$key]['category'],
					'<a href="' . self::BASEURL_STORE . self::MODULE_STORE . $key . '" target="_blank" >' . $store[$key]['title'] . '</a>',
					$store[$key]['version'],
					helper::dateUTF8('%d %B %Y', $store[$key]['versionDate'], self::$i18nContent),
					implode(' - ', $pageInfos),
					template::button('moduleExport' . $key, [
						'class' => $class,
						'href' => helper::baseUrl() . $this->getUrl(0) . '/uploadItem/' . $key,
						'value' => $ico,
						'help' => $help
					])
				];
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Catalogue de modules'),
			'view' => 'store'
		]);
	}

	/**
	 * Détail d'un objet du catalogue
	 */
	public function item()
	{
		$store = json_decode(helper::getUrlContents(self::BASEURL_STORE . self::MODULE_STORE . 'list'), true);
		self::$storeItem = $store[$this->getUrl(2)];
		self::$storeItem['fileDate'] = helper::dateUTF8('%d %B %Y', self::$storeItem['fileDate'], self::$i18nContent);
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Module ' . self::$storeItem['title']),
			'view' => 'item'
		]);
	}

	/**
	 * Gestion des modules
	 */
	public function index()
	{

		$i18nSites = [];
		// Tableau des langues rédigées
		foreach (self::$languages as $key => $value) {
			// tableau des langues installées
			if (
				is_dir(self::DATA_DIR . $key)
				&& file_exists(self::DATA_DIR . $key . '/page.json')
				&& file_exists(self::DATA_DIR . $key . '/module.json')
			) {
				$i18nSites[$key] = $value;
			}
		}

		// Lister les modules installés
		$infoModules = helper::getModules();

		// Parcourir les langues du site traduit et recherche les modules affectés à des pages
		$pagesInfos = [];

		foreach ($i18nSites as $keyi18n => $valuei18n) {

			// Clés moduleIds dans les pages de la langue
			$pages = json_decode(file_get_contents(self::DATA_DIR . $keyi18n . '/page.json'), true);

			// Extraire les clés des modules
			$pagesModules[$keyi18n] = array_filter(helper::arrayColumn($pages['page'], 'moduleId', 'SORT_DESC'), 'strlen');

			// Générer la liste des pages avec module de la langue par défaut
			foreach ($pagesModules[$keyi18n] as $key => $value) {
				if (!empty($value)) {
					$pagesInfos[$keyi18n][$key]['pageId'] = $key;
					$pagesInfos[$keyi18n][$key]['title'] = $pages['page'][$key]['title'];
					$pagesInfos[$keyi18n][$key]['moduleId'] = $value;
				}
			}
		}

		// Recherche des modules orphelins dans toutes les langues
		$orphans = $installed = array_flip(array_keys($infoModules));
		foreach ($i18nSites as $keyi18n => $valuei18n) {
			// Générer la liste des modules orphelins
			foreach ($infoModules as $key => $value) {
				// Supprimer les éléments affectés
				if (array_search($key, $pagesModules[$keyi18n])) {
					unset($orphans[$key]);
				}
			}
		}
		$orphans = array_flip($orphans);

		//  Mise en forme du tableau des modules orphelins
		if (isset($orphans)) {
			foreach ($orphans as $key) {
				// Construire le tableau de sortie
				self::$modulesOrphan[] = [
					$infoModules[$key]['realName'],
					$key,
					$infoModules[$key]['version'],
					'',
					$infoModules[$key]['delete'] === true
					? template::button('moduleDelete' . $key, [
						'class' => 'moduleDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $key,
						'value' => template::ico('trash'),
						'help' => 'Supprimer le module'
					])
					: '',

				];
			}
		}

		// Modules installés non orphelins
		//  Mise en forme du tableau des modules utilisés
		if (isset($installed)) {
			foreach (array_flip($installed) as $key) {
				// Construire le tableau de sortie
				self::$modulesInstalled[] = [
					$infoModules[$key]['realName'],
					$key,
					$infoModules[$key]['version'],
					'',
					template::button('moduleSave' . $key, [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/save/filemanager/' . $key,
						'value' => template::ico('download-cloud'),
						'help' => 'Sauvegarder le module dans le gestionnaire de fichiers'
					]),
					template::button('moduleDownload' . $key, [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/save/download/' . $key,
						'value' => template::ico('download'),
						'help' => 'Sauvegarder et télécharger le module'
					])

				];
			}
		}


		// Mise en forme du tableau des modules employés dans les pages
		// Avec les commandes de sauvegarde et de restauration
		self::$modulesData[] = [];
		if (
			isset($pagesInfos)
		) {
			foreach ($i18nSites as $keyi18n => $valuei18n) {
				if (isset($pagesInfos[$keyi18n])) {
					foreach ($pagesInfos[$keyi18n] as $keyPage => $value) {
						if (isset($infoModules[$pagesInfos[$keyi18n][$keyPage]['moduleId']])) {
							// Construire le tableau de sortie
							self::$modulesData[] = [
								$infoModules[$pagesInfos[$keyi18n][$keyPage]['moduleId']]['realName'] . '&nbsp(' . $pagesInfos[$keyi18n][$keyPage]['moduleId'] . ')',
								$infoModules[$pagesInfos[$keyi18n][$keyPage]['moduleId']]['version'],
								template::flag($keyi18n, '20px') . '&nbsp<a href ="' . helper::baseUrl() . $keyPage . '" target="_blank">' . $pagesInfos[$keyi18n][$keyPage]['title'] . ' (' . $keyPage . ')</a>',
								template::button('dataExport' . $keyPage, [
									'href' => helper::baseUrl() . $this->getUrl(0) . '/dataExport/filemanager/' . self::$i18nContent . '/' . $pagesInfos[$keyi18n][$keyPage]['moduleId'] . '/' . $keyPage,
									// appel de fonction vaut exécution, utiliser un paramètre
									'value' => template::ico('download-cloud'),
									'help' => 'Sauvegarder les données du module dans le gestionnaire de fichiers'
								]),
								template::button('dataExport' . $keyPage, [
									'href' => helper::baseUrl() . $this->getUrl(0) . '/dataExport/download/' . self::$i18nContent . '/' . $pagesInfos[$keyi18n][$keyPage]['moduleId'] . '/' . $keyPage,
									// appel de fonction vaut exécution, utiliser un paramètre
									'value' => template::ico('download'),
									'help' => 'Sauvegarder et télécharger les données du module'
								]),
								template::button('dataDelete' . $keyPage, [
									'href' => helper::baseUrl() . $this->getUrl(0) . '/dataDelete/' . self::$i18nContent . '/' . $pagesInfos[$keyi18n][$keyPage]['moduleId'] . '/' . $keyPage,
									// appel de fonction vaut exécution, utiliser un paramètre
									'value' => template::ico('trash'),
									'class' => 'buttonRed dataDelete',
									'help' => 'Détacher le module de la page',
								])
							];
						}
					}
				}
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Gestion des modules'),
			'view' => 'index'
		]);
	}


	/**
	 * Sauvegarde un module sans les données
	 */
	public function save()
	{
		// Action interdite
		if ($this->getUser('permission', __CLASS__, __FUNCTION__) !== true) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Créer un dossier temporaire
			$tmpFolder = self::TEMP_DIR . uniqid() . '/';
			if (!is_dir($tmpFolder)) {
				mkdir($tmpFolder, 0755);
			}

			$action = $this->getUrl(2);
			$moduleId = $this->getUrl(3);

			// Descripteur de l'archive
			$infoModule = helper::getModules();

			//Nom de l'archive
			$fileName = $moduleId . str_replace('.', '-', $infoModule[$moduleId]['version']) . '.zip';

			// Régénération du descripteur du module
			file_put_contents(self::MODULE_DIR . $moduleId . '/enum.json', json_encode($infoModule[$moduleId], JSON_UNESCAPED_UNICODE));

			// Construire l'archive
			$this->makeZip($tmpFolder . $fileName, self::MODULE_DIR . $moduleId);

			switch ($action) {
				case 'filemanager':
					if (is_dir(self::FILE_DIR . 'source/modules') === false) {
						mkdir(self::FILE_DIR . 'source/modules');
					}
					$success = copy($tmpFolder . $fileName, self::FILE_DIR . 'source/modules/' . $fileName);
					// Valeurs en sortie
					$this->addOutput([
						'redirect' => helper::baseUrl() . 'plugin',
						'notification' => $success ? helper::translate('Archive copiée dans le dossier Modules du gestionnaire de fichier') : helper::translate('Erreur de copie'),
						'state' => $success
					]);
					break;
				case 'download':
					// Téléchargement du ZIP
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Transfer-Encoding: binary');
					header('Content-Disposition: attachment; filename="' . $fileName . '"');
					header('Content-Length: ' . filesize($tmpFolder . $fileName));
					readfile($tmpFolder . $fileName);
					exit();
			}
			// Nettoyage
			unlink(self::TEMP_DIR . $fileName);
			$this->deleteDir($tmpFolder);
		}
	}

}