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
 * @copyright Copyright (C) 2020-2021, Sylvain Lelièvre
  * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @author Sylvain Lelièvre <lelievresylvain@free.fr>
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

class plugin extends common {

	public static $actions = [
		'index' => self::GROUP_ADMIN,
		'delete' => self::GROUP_ADMIN,
		'save' => self::GROUP_ADMIN, // Sauvegarde le module dans un fichier ZIP ou dans le gestionnaire
		'dataExport' => self::GROUP_ADMIN, // Fonction muette d'exportation
		'dataImport' => self::GROUP_ADMIN, // les données d'un module
		'dataDelete' => self::GROUP_ADMIN,
		'store' => self::GROUP_ADMIN,
		'item' => self::GROUP_ADMIN, // détail d'un objet
		'upload' => self::GROUP_ADMIN, // Téléverser catalogue
		'uploadItem'=> self::GROUP_ADMIN // Téléverser par archive
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
	public function delete() {

		// Jeton incorrect
		if ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'plugin',
				'state' => false,
				'notification' => 'Action non autorisée'
			]);
		}
		else{
			// Suppression des dossiers
			$infoModules = helper::getModules();
			$module = $this->getUrl(2);
			//Liste des dossiers associés au module non effacés
			if( $this->removeDir('./module/'.$module ) === true   ){
				$success = true;
				$notification = 'Module '. $module .' désinstallé';
				if(($infoModules[$this->getUrl(2)]['dataDirectory']) ) {
					if (
							is_dir($infoModules[$this->getUrl(2)]['dataDirectory'])
							&& !$this->removeDir($infoModules[$this->getUrl(2)]['dataDirectory'])
						){
						$notification = 'Module '.$module .' désinstallé, il reste des données dans ' . $infoModules[$this->getUrl(2)]['dataDirectory'];
					}
				}
			}
			else{
				$success = false;
				$notification = 'La suppression a échouée';
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
	private function install ($moduleName, $checkValid){
		$tempFolder = 'datamodules';//uniqid();
		$zip = new ZipArchive();
		if ($zip->open($moduleName) === TRUE) {
			$notification = 'Archive ouverte';
			mkdir (self::TEMP_DIR . $tempFolder, 0755);
			$zip->extractTo(self::TEMP_DIR . $tempFolder );
			// Archive de module ?
			$success = false;
			$notification = 'Ce n\'est pas l\'archive d\'un module !';
			$moduleDir = self::TEMP_DIR . $tempFolder . '/module';
			$moduleName = '';
			if ( is_dir( $moduleDir )) {
				// Lire le nom du module
				if ($dh = opendir( $moduleDir )) {
					while ( false !== ($file = readdir($dh)) ) {
						if ($file != "." && $file != "..") {
							$moduleName = $file;
						}
					}
					closedir($dh);
				}
				// Module normalisé ?
				if( is_file( $moduleDir.'/'.$moduleName.'/'.$moduleName.'.php' ) AND is_file( $moduleDir.'/'.$moduleName.'/view/index/index.php' ) ){

					// Lecture de la version et de la validation d'update du module pour validation de la mise à jour
					// Pour une version <= version installée l'utilisateur doit cocher 'Mise à jour forcée'
					$version = '0.0';
					$update = '0.0';
					$valUpdate = false;
					$file = file_get_contents( $moduleDir.'/'.$moduleName.'/'.$moduleName.'.php');
					$file = str_replace(' ','',$file);
					$file = str_replace("\t",'',$file);
					$pos1 = strpos($file, 'constVERSION');
					if( $pos1 !== false){
						$posdeb = strpos($file, "'", $pos1);
						$posend = strpos($file, "'", $posdeb + 1);
						$version = substr($file, $posdeb + 1, $posend - $posdeb - 1);
					}
					$pos1 = strpos($file, 'constUPDATE');
					if( $pos1 !== false){
						$posdeb = strpos($file, "'", $pos1);
						$posend = strpos($file, "'", $posdeb + 1);
						$update = substr($file, $posdeb + 1, $posend - $posdeb - 1);
					}
					// Si version actuelle >= version indiquée dans UPDATE la mise à jour est validée
					$infoModules = helper::getModules();
					if( $infoModules[$moduleName]['update'] >= $update ) $valUpdate = true;

					// Module déjà installé ?
					$moduleInstal = false;
					foreach($infoModules as $key=>$value ){
						if($moduleName === $key){
							$moduleInstal = true;
						}
					}

					// Validation de la maj si autorisation du concepteur du module ET
					// ( Version plus récente OU Check de forçage )
					$valNewVersion = floatval($version);
					$valInstalVersion = floatval( $infoModules[$moduleName]['version'] );
					$newVersion = false;
					if( $valNewVersion > $valInstalVersion ) $newVersion = true;
					$validMaj = $valUpdate && ( $newVersion || $checkValid);

					// Nouvelle installation ou mise à jour du module
					if( ! $moduleInstal ||  $validMaj ){
						// Copie récursive des dossiers
						$this->copyDir( self::TEMP_DIR . $tempFolder, './' );
						$success = true;
						if( ! $moduleInstal ){
							$notification = 'Module '.$moduleName.' installé';
						}
						else{
							$notification = 'Module '.$moduleName.' mis à jour';
						}
					}
					else{
						$success = false;
						if( $valNewVersion == $valInstalVersion){
							$notification = ' Version détectée '.$version.' = à celle installée '.$infoModules[$moduleName]['version'];
						}
						else{
							$notification = ' Version détectée '.$version.' < à celle installée '.$infoModules[$moduleName]['version'];
						}
						if( $valUpdate === false){
							if( $infoModules[$moduleName]['update'] === $update ){
								$notification = ' Mise à jour par ce procédé interdite par le concepteur du module';
							}
							else{
								$notification = ' Mise à jour par ce procédé interdite, votre version est trop ancienne';
							}
						}
					}
				}
			}
			// Supprimer le dossier temporaire même si le module est invalide
			$this->removeDir(self::TEMP_DIR . $tempFolder);
			$zip->close();
		} else {
			// erreur à l'ouverture
			$success = false;
			$notification = 'Impossible d\'ouvrir l\'archive';
		}
		return(['success' => $success,
				'notification'=> $notification
		]);
	}

	/***
	 * Installation d'un module à partir du gestionnaire de fichier
	 */
	public function upload() {
		// Soumission du formulaire
		if($this->isPost()) {
			// Installation d'un module
			$checkValidMaj = $this->getInput('configModulesCheck', helper::FILTER_BOOLEAN);
			$zipFilename =	$this->getInput('configModulesInstallation', helper::FILTER_STRING_SHORT);
			if( $zipFilename !== ''){
				$success = [
					'success' => false,
					'notification'=> ''
				];
				$state = $this->install(self::FILE_DIR.'source/'.$zipFilename, $checkValidMaj);
			}
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'plugin',
				'notification' => $state['notification'],
				'state' => $state['success']
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Installer un module',
			'view' => 'upload'
		]);
	}

	/***
	 * Installation  d'un module depuis le catalogue
	 */
	public function uploadItem() {
		// Jeton incorrect
		if ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'store',
				'state' => false,
				'notification' => 'Action non autorisée'
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
			$d = explode('/',$moduleFilePath);
			$moduleFile = $d[count($d)-1];
			// Créer le dossier modules
			if (!is_dir(self::FILE_DIR . 'source/modules')) {
				mkdir (self::FILE_DIR . 'source/modules', 0755);
			}
			// Sauver les données du fichiers
			file_put_contents(self::FILE_DIR . 'source/modules/' . $moduleFile, $moduleData);

			/**
			* $if( $moduleFile !== ''){
			*	$success = [
			*		'success' => false,
			*		'notification'=> ''
			*	];
			*	$state = $this->install(self::FILE_DIR.'source/modules/'.$moduleFile, false);
			*}
			*/
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'plugin/store',
				'notification' => $moduleFile . ' téléchargé dans le dossier modules du gestionnaire de fichiers',
				'state' => true
			]);

		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Catalogue de modules',
			'view' => 'store'
		]);
	}

	/**
	 * Catalogue des modules sur le site ZwiiCMS.fr
	 */
	public function store() {
		$store = json_decode(helper::getUrlContents(self::BASEURL_STORE . self::MODULE_STORE . 'list'), true);
		if ($store) {
			// Modules installés
			$infoModules = helper::getModules();
			// Clés moduleIds dans les pages
			$inPages = helper::arraycolumn($this->getData(['page']),'moduleId', 'SORT_DESC');
			foreach( $inPages as $key=>$value){
				$pagesInfos[ $this->getData(['page', $key, 'title' ]) ] = $value;
			}
			// Parcourir les données des modules
			foreach ($store as $key=>$value) {
				// Module non installé
				$ico = template::ico('download');
				$class = '';
				$help = 'Télécharger le module dans le gestionnaire de fichiers';
				// Le module est installé
				if (array_key_exists($key,$infoModules) === true) {
					$class = 'buttonGreen';
					$ico = template::ico('update');
					$help = 'Mettre à jour le module orphelin';
				}
				// Le module est installé et utilisé
				if (in_array($key,$inPages) === true) {
					$class = 'buttonRed';
					$ico =  template::ico('update');
					$help = 'Mettre à jour le module attaché, une sauvegarde des données de module est recommandée !';
				}
				self::$storeList [] = [
					$store[$key]['category'],
					'<a href="' . self::BASEURL_STORE . self::MODULE_STORE . $key . '" target="_blank" >'.$store[$key]['title'].'</a>',
					$store[$key]['version'],
					mb_detect_encoding(strftime('%d %B %Y', $store[$key]['versionDate']), 'UTF-8', true)
					? strftime('%d %B %Y', $store[$key]['versionDate'])
					: utf8_encode(strftime('%d %B %Y', $store[$key]['versionDate'])),
					implode(', ', array_keys($pagesInfos,$key)),
					template::button('moduleExport' . $key, [
							'class' => $class,
							'href' => helper::baseUrl(). $this->getUrl(0) . '/uploadItem/' . $key.'/' . $_SESSION['csrf'],// appel de fonction vaut exécution, utiliser un paramètre
							'value' => $ico,
							'help' => $help
							])
				];
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Catalogue de modules en ligne',
			'view' => 'store'
		]);
	}

	/**
	 * Détail d'un objet du catalogue
	 */
	public function item() {
		$store = json_decode(helper::getUrlContents(self::BASEURL_STORE . self::MODULE_STORE . 'list'), true);
		self::$storeItem = $store [$this->getUrl(2)] ;
		self::$storeItem ['fileDate'] = mb_detect_encoding(strftime('%d %B %Y',self::$storeItem ['fileDate']), 'UTF-8', true)
										? strftime('%d %B %Y', self::$storeItem ['fileDate'])
										: utf8_encode(strftime('%d %B %Y', self::$storeItem ['fileDate']));
		// Valeurs en sortie
		$this->addOutput([
			'title' =>'Module ' . self::$storeItem['title'],
			'view' => 'item'
		]);
	}

	/**
	 * Gestion des modules
	 */
	public function index() {

		// Tableau des langues rédigées
		foreach (self::$i18nList as $key => $value) {
			if ($this->getData(['config','i18n', $key]) === 'site' ||
				$key === 'fr') {
				$i18nSites[$key] = $value;
			}
		}
		// Lister les modules installés
		$infoModules = helper::getModules();

		// Parcourir les langues du site traduit et recherche les modules affectés à des pages
		foreach ($i18nSites as $keyi18n=>$valuei18n) {

			// Clés moduleIds dans les pages de la langue
			$pages = json_decode(file_get_contents(self::DATA_DIR . $keyi18n . '/' . 'page.json'), true);

			// Extraire les clés des modules
			$pagesModules [$keyi18n] = array_filter(helper::arraycolumn($pages['page'],'moduleId', 'SORT_DESC'), 'strlen');

			// Générer ls liste des pages avec module pour la sauvegarde ou le backup
			foreach( $pagesModules [$keyi18n] as $key=>$value ) {
				if (!empty($value)) {
					$pagesInfos [$keyi18n] [$key] ['pageId'] = $key ;
					$pagesInfos [$keyi18n] [$key] ['title'] = $this->getData(['page', $key, 'title' ]) ;
					$pagesInfos [$keyi18n] [$key] ['moduleId'] = $value;
				}
			}
		}


		// Recherche des modules orphelins dans toutes les langues
		$orphans = $installed = array_flip(array_keys ($infoModules));
		foreach ($i18nSites as $keyi18n=>$valuei18n) {
			// Générer la liste des modules orphelins
			foreach ($infoModules as $key=>$value) {
				// Supprimer les éléments affectés
				if (array_search($key, $pagesModules[$keyi18n]) ) {
					unset($orphans [$key]);
				}

			}
		}
		$orphans = array_flip($orphans);

		//  Mise en forme du tableau des modules orphelins
		if (isset($orphans)) {
			foreach ($orphans as $key) {
				// Construire le tableau de sortie
				self::$modulesOrphan [] = [
					$infoModules [$key] ['realName'],
					$key,
					$infoModules [$key] ['version'],
					'',
					$infoModules[$key] ['delete'] === true
						? template::button('moduleDelete' . $key, [
								'class' => 'moduleDelete buttonRed',
								'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' .$key . '/' . $_SESSION['csrf'],
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
				self::$modulesInstalled [] = [
					$infoModules [$key] ['realName'],
					$key,
					$infoModules [$key] ['version'],
					'',
					template::button('moduleSave' . $key, [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/save/filemanager/' .$key . '/' . $_SESSION['csrf'],
						'value' => template::ico('download-cloud'),
						'help' => 'Sauvegarder le module dans le gestionnaire de fichiers'
					]),
					template::button('moduleDownload' . $key, [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/save/download/' .$key . '/' . $_SESSION['csrf'],
						'value' => template::ico('download'),
						'help' => 'Sauvegarder et télécharger le module'
					])

				];
			}
		}


		// Mise en forme du tableau des modules employés dans les pages
		// Avec les commandes de sauvegarde et de restauration
		//foreach ($pagesInfos as $keyi18n=>$valueI18n) {

			$keyi18n = self::$i18n;
			$valueI18n = $pagesInfos[self::$i18n];
			foreach ($valueI18n as $keyPage=>$value) {
				// Construire le tableau de sortie
				self::$modulesData[] = [
					$infoModules[$pagesInfos[$keyi18n][$keyPage]['moduleId']] ['realName'],
					$pagesInfos[$keyi18n][$keyPage]['moduleId'],
					$infoModules[$pagesInfos [$keyi18n][$keyPage]['moduleId']] ['version'],
					//template::flag($keyi18n, '20px'),
					'<a href ="' . helper::baseUrl() . $keyPage .  '" target="_blank">' . $pagesInfos [$keyi18n][$keyPage]['title'] . ' (' .$keyPage . ')</a>',
					template::button('dataExport' . $keyPage, [
													'href' => helper::baseUrl(). $this->getUrl(0) . '/dataExport/' . $keyi18n . '/' . $pagesInfos[$keyi18n][$keyPage]['moduleId'] . '/' . $keyPage . '/' . $_SESSION['csrf'],// appel de fonction vaut exécution, utiliser un paramètre
													'value' => template::ico('download'),
													'help' => 'Exporter les données du module'
					]),
					template::button('dataDelete' . $keyPage, [
													'href' => helper::baseUrl(). $this->getUrl(0) . '/dataDelete/' . $keyi18n . '/' . $pagesInfos[$keyi18n][$keyPage]['moduleId'] . '/' . $keyPage . '/' . $_SESSION['csrf'],// appel de fonction vaut exécution, utiliser un paramètre
													'value' => template::ico('trash'),
													'class' => 'buttonRed dataDelete',
													'help' => 'Détacher le module de la page',
													])

				];
			}

		//}

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Gestion des modules',
			'view' => 'index'
		]);
	}


	/**
	 * Sauvegarde un module sans les données
	 */

	 public function save() {
		// Jeton incorrect
		if ($this->getUrl(4) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'plugin',
				'state' => false,
				'notification' => 'Action non autorisée'
			]);
		} else {

			// Créer un dossier temporaire
			$tmpFolder = self::TEMP_DIR . uniqid();
			if (!is_dir($tmpFolder)) {
				mkdir($tmpFolder, 0755);
			}

			//Nom de l'archive
			$fileName =  $this->getUrl(3) . '.zip';
			$this->makeZip ($tmpFolder . '/' . $fileName, 'module/' .  $this->getUrl(3));

			switch ($this->getUrl(2)) {
				case 'filemanager':
					if (!file_exists(self::FILE_DIR . 'source/modules')) {
						mkdir(self::FILE_DIR . 'source/modules');
					}
					$success = copy($tmpFolder . '/' . $fileName , self::FILE_DIR . 'source/modules/' . $this->getUrl(3) . '.zip' );

					// Valeurs en sortie
					$this->addOutput([
						'redirect' => helper::baseUrl() . 'plugin',
						'notification' => $success ? $this->getUrl(3) . '.zip copié dans le dossier Module du gestionnaire de fichier' : 'Erreur de copie',
						'state' => $success
					]);
					break;
				case 'download':
				default:
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . $fileName . '"');
					header('Content-Length: ' . filesize($tmpFolder . '/' . $fileName));
					ob_clean();
					ob_end_flush();
					readfile( $tmpFolder . '/' .$fileName);
					exit();
					break;
			}
			// Nettoyage
			unlink($tmpFolder . '/' . $fileName);
			$this->removeDir($tmpFolder);
		}
	 }


	/*
	* Détacher un module d'une page en supprimant les données du module
	* 2 : i18n id
	* 3 : moduleId
	* 4 : pageId
	* 5 : CSRF
	*/
	public function dataDelete() {
		// Jeton incorrect
		if ($this->getUrl(5) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'plugin',
				'state' => false,
				'notification' => 'Action non autorisée'
			]);
		} else {
			$this->setData(['page', $this->getUrl(4), 'moduleId', '']);
			$this->deleteData(['module', $this->getUrl(4)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'plugin',
				'notification' => 'Le module ' .  $this->getUrl(3) . ' de la page '.  $this->getUrl(4) . ' a été supprimé.',
				'state' => true
			]);
		}


	}


	/*
	* Export des données d'un module
	* Structure de l'adresse reçue
	* 2 : i18n id
	* 3 : moduleId
	* 4 : pageId
	* 5 : CSRF
	*/
	public function dataExport() {
		// Jeton incorrect
		if ($this->getUrl(5) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'plugin',
				'state' => false,
				'notification' => 'Action non autorisée'
			]);
		} else {

			// Créer un dossier temporaire
			$tmpFolder = self::TEMP_DIR . uniqid();
			if (!is_dir($tmpFolder)) {
				mkdir($tmpFolder, 0755);
			}


			// Copie des infos sur le module
			$modulesData = json_decode(file_get_contents(self::DATA_DIR . $this->getUrl(2) . '/module.json' ), true);
			$moduleData = $modulesData['module'] [$this->getUrl(4)];
			$success = file_put_contents ($tmpFolder . '/module.json', json_encode($moduleData));

			// Le dossier du module s'il existe
			if (is_dir(self::DATA_DIR . $this->getUrl(3) . '/' . $this->getUrl(4) ) ) {
				// Copier le dossier des données
				$success .= $this->copyDir(self::DATA_DIR . $this->getUrl(3) . '/' . $this->getUrl(4), $tmpFolder);
			}

			// Descripteur de l'archive
			$infoModule = helper::getModules();
			$success .= file_put_contents ($tmpFolder . '/descripteur.json', json_encode(  [$this->getUrl(3) => $infoModule [$this->getUrl(3)]] ));


			// création du zip
			if ($success)
			{
				$fileName =  $this->getUrl(2) . '-' .  $this->getUrl(3) . '-' . $this->getUrl(4) . '.zip';
				$this->makeZip ($fileName, $tmpFolder);
				if (file_exists($fileName)) {
					ob_start();
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . $fileName . '"');
					header('Content-Length: ' . filesize($fileName));
					ob_clean();
					ob_end_flush();
					readfile( $fileName);
					unlink($fileName);
					$this->removeDir($tmpFolder);
					exit();
				}
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'plugin',
					'notification' => 'Quelque chose s\'est mal passé',
					'state' => false
				]);
			}
		}
	}

	/*
	* Importer des données d'un module externes ou interne à module.json
	*/
	public function dataImport(){

		// Soumission du formulaire d'importation du module dans une page libre
		if($this->isPost()) {
			// Récupérer le fichier et le décompacter
			$zipFilename =	$this->getInput('pluginImportFile', helper::FILTER_STRING_SHORT, true);
			$targetPage = $this->getInput('pluginImportPage', helper::FILTER_STRING_SHORT, true);
			$tempFolder = uniqid();

			// Extraction dans un dossier temporaire
			mkdir (self::TEMP_DIR . $tempFolder, 0755);
			$zip = new ZipArchive();
			if ($zip->open(self::FILE_DIR . 'source/' . $zipFilename) === TRUE) {
				$zip->extractTo(self::TEMP_DIR  . $tempFolder );
			}

			// Lire le descripteur
			$descripteur = json_decode(file_get_contents(self::TEMP_DIR  . $tempFolder . '/descripteur.json'), true);

			// Lecture des données du module
			$moduleData = json_decode(file_get_contents(self::TEMP_DIR  . $tempFolder . '/module.json'), true );
			// Chargement des données du module importé
			$this->setData(['module', $targetPage, $moduleData ]);
			// Intégration des données du module importé dans la page
			$this->setData(['page', $targetPage ,'moduleId', array_key_first($descripteur) ]);

			// Supprimer le dossier temporaire
			$this->removeDir(self::TEMP_DIR . $tempFolder);
			$zip->close();



			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'plugin',
				'state' => true,
				'notification' => 'Import des données effectué'
			]);
		}
		// Bouton d'importation des données d'un module spécifique
		if (count(explode('/',$this->getUrl())) === 6) {
				// Jeton incorrect
				if ($this->getUrl(3) !== $_SESSION['csrf']) {
					// Valeurs en sortie
					$this->addOutput([
						'redirect' => helper::baseUrl()  . 'plugin',
						'state' => false,
						'notification' => 'Action non autorisée'
					]);
				}

			// Traitement

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'plugin',
				'state' => true,
				'notification' => 'Okay'
			]);
		}


		/**
		 * Liste des pages sans module
		 * et ne sont pas des barres latérales
		 */
		self::$pagesList = $this->getHierarchy(null, null, null);
		foreach(self::$pagesList as $page => $pageId) {
			if ($this->getData(['page',$page,'block']) === 'bar' ||
				//$this->getData(['page',$page,'disable']) === true ||
				$this->getData(['page',$page,'moduleId']) !== ''
			) {
				unset(self::$pagesList[$page]);
			}
		}
		self::$pagesList = array_keys(self::$pagesList);

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Importer des données de module',
			'view' => 'dataImport'
		]);
	}



}
