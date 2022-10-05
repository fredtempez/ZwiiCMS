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
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @author Sylvain Lelièvre <lelievresylvain@free.fr>
 * @copyright Copyright (C) 2020-2021, Sylvain Lelièvre
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

class addon extends common {

	public static $actions = [
		'index' => self::GROUP_ADMIN,
		'delete' => self::GROUP_ADMIN,
		'export' => self::GROUP_ADMIN,
		'import' => self::GROUP_ADMIN,
		'store' => self::GROUP_ADMIN,
		'item' => self::GROUP_ADMIN,
		'upload' => self::GROUP_ADMIN,
		'uploadItem'=> self::GROUP_ADMIN
	];

	// URL des modules
	const BASEURL_STORE = 'https://store.zwiicms.fr/';
	const MODULE_STORE = '?modules/';

	// Gestion des modules
	public static $modInstal = [];

	// pour tests
	public static $valeur = [];

	// le catalogue
	public static $storeList = [];
	public static $storeItem = [];


	/*
	* Effacement d'un module installé et non utilisé
	*/
	public function delete() {

		// Jeton incorrect
		if ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'addon',
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
				'redirect' => helper::baseUrl() . 'addon',
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
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => $state['notification'],
				'state' => $state['success']
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Téléverser un module',
			'view' => 'upload'
		]);
	}

	/***
	 * Installation  d'un module par le catalogue
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
				'redirect' => helper::baseUrl()  . 'addon/store',
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
			$inPages = helper::arrayColumn($this->getData(['page']),'moduleId', 'SORT_DESC');
			foreach( $inPages as $key=>$value){
				$inPagesTitle[ $this->getData(['page', $key, 'title' ]) ] = $value;
			}
			// Parcourir les données des modules
			foreach ($store as $key=>$value) {
				// Module non installé
				$ico = template::ico('download');
				$class = '';
				// Le module est installé
				if (array_key_exists($key,$infoModules) === true) {
					$class = 'buttonGreen';
					$ico = template::ico('update');
				}
				// Le module est installé et utilisé
				if (in_array($key,$inPages) === true) {
					$class = 'buttonRed';
					$ico =  template::ico('update');
				}
				self::$storeList [] = [
					$store[$key]['category'],
					'<a href="' . self::BASEURL_STORE . self::MODULE_STORE . $key . '" target="_blank" >'.$store[$key]['title'].'</a>',
					$store[$key]['version'],
					mb_detect_encoding(\PHP81_BC\strftime('%d %B %Y', $store[$key]['versionDate']), 'UTF-8', true)
					? \PHP81_BC\strftime('%d %B %Y', $store[$key]['versionDate'])
					: utf8_encode(\PHP81_BC\strftime('%d %B %Y', $store[$key]['versionDate'])),
					implode(', ', array_keys($inPagesTitle,$key)),
					template::button('moduleExport' . $key, [
							'class' => $class,
							'href' => helper::baseUrl(). $this->getUrl(0) . '/uploadItem/' . $key.'/' . $_SESSION['csrf'],// appel de fonction vaut exécution, utiliser un paramètre
							'value' => $ico
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
		self::$storeItem ['fileDate'] = mb_detect_encoding(\PHP81_BC\strftime('%d %B %Y',self::$storeItem ['fileDate']), 'UTF-8', true)
										? \PHP81_BC\strftime('%d %B %Y', self::$storeItem ['fileDate'])
										: utf8_encode(\PHP81_BC\strftime('%d %B %Y', self::$storeItem ['fileDate']));
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

		// Lister les modules
		// $infoModules[nom_module]['realName'], ['version'], ['update'], ['delete'], ['dataDirectory']
		$infoModules = helper::getModules();

		// Clés moduleIds dans les pages
		$inPages = helper::arrayColumn($this->getData(['page']),'moduleId', 'SORT_DESC');
		foreach( $inPages as $key=>$value){
			$inPagesTitle[ $this->getData(['page', $key, 'title' ]) ] = $value;
		}

		// Parcourir les données des modules
		foreach ($infoModules as $key=>$value) {
			// Construire le tableau de sortie
			self::$modInstal[] = [
				$key,
				$infoModules[$key]['realName'],
				$infoModules[$key]['version'],
				implode(', ', array_keys($inPagesTitle,$key)),
				//|| ('delete',$infoModules[$key]) && $infoModules[$key]['delete'] === true && implode(', ',array_keys($inPages,$key)) === ''
				$infoModules[$key]['delete'] === true  && implode(', ',array_keys($inPages,$key)) === ''
											? template::button('moduleDelete' . $key, [
													'class' => 'moduleDelete buttonRed',
													'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $key . '/' . $_SESSION['csrf'],
													'value' => template::ico('cancel')
												])
											: '',
				implode(', ',array_keys($inPages,$key)) !== ''
											? template::button('moduleExport' . $key, [
												'href' => helper::baseUrl(). $this->getUrl(0) . '/export/' . $key . '/' . $_SESSION['csrf'],// appel de fonction vaut exécution, utiliser un paramètre
												'value' => template::ico('download')
												])
											: '',
				implode(', ',array_keys($inPages,$key)) === ''
											? template::button('moduleExport' . $key, [
												'href' => helper::baseUrl(). $this->getUrl(0) . '/import/' . $key . '/' . $_SESSION['csrf'],// appel de fonction vaut exécution, utiliser un paramètre
												'value' => template::ico('upload')
												])
											: ''
			];
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Gestion des modules',
			'view' => 'index'
		]);
	}

	/*
	* Export des données d'un module externes ou interne à module.json
	*/
	public function export(){
		// Jeton incorrect
		if ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'addon',
				'state' => false,
				'notification' => 'Action non autorisée'
			]);
		}
		else {
			// Lire les données du module
			$infoModules = helper::getModules();
			// Créer un dossier par défaut
			$tmpFolder = self::TEMP_DIR . uniqid();
			//$tmpFolder = self::TEMP_DIR . 'test';
			if (!is_dir($tmpFolder)) {
				mkdir($tmpFolder, 0755);
			}
			// Clés moduleIds dans les pages
			$inPages = helper::arrayColumn($this->getData(['page']),'moduleId', 'SORT_DESC');
			// Parcourir les pages utilisant le module
			foreach (array_keys($inPages,$this->getUrl(2)) as $pageId) {
				// Export des pages hébergeant le module
				$pageParam[$pageId] = $this->getData(['page',$pageId]);
				// Export du contenu de la page
				//$pageContent[$pageId] = file_get_contents(self::DATA_DIR . self::$i18n . '/content/' . $this->getData(['page', $pageId, 'content']));
				$pageContent[$pageId] = $this->getPage($pageId, self::$i18n);
				// Export de fr/module.json
				$moduleId = 'fr/module.json';
				$moduleDir = str_replace('site/data/','',$infoModules[$this->getUrl(2)]['dataDirectory']);
				// Création de l'arborescence des langues
				// Pas de nom dossier de langue - dossier par défaut
				$t = explode ('/',$moduleId);
				if ( is_array($t)) {
					$lang = 'fr';
				} else {
					$lang = $t[0];
				}
				// Créer le dossier temporaire si inexistant sinon le nettoie et le créer
				if (!is_dir($tmpFolder . '/' . $lang)) {
					mkdir ($tmpFolder . '/' . $lang, 0755, true);
				} else {
					$this->removeDir($tmpFolder . '/' . $lang);
					mkdir ($tmpFolder . '/' . $lang, 0755, true);
				}
				// Créer le dossier temporaire des données du  module
				if ($infoModules[$this->getUrl(2)]['dataDirectory']) {
					if (!is_dir($tmpFolder . '/' . $moduleDir)) {
						mkdir ($tmpFolder . '/' . $moduleDir, 0755, true) ;
					}
				}
				// Sauvegarde si données non vides
				$tmpData [$pageId] = $this->getData(['module',$pageId ]);
				if ($tmpData [$pageId] !== null) {
					file_put_contents($tmpFolder . '/' . $moduleId, json_encode($tmpData));
				}
				// Export des données localisées dans le dossier de données du module
				if ($infoModules[$this->getUrl(2)]['dataDirectory'] &&
					is_dir($infoModules[$this->getUrl(2)]['dataDirectory'])) {
						$this->copyDir ($infoModules[$this->getUrl(2)]['dataDirectory'], $tmpFolder . '/' . $moduleDir);
				}
			}
			// Enregistrement des pages dans le dossier de langue identique à module
			if (!file_exists($tmpFolder . '/' . $lang . '/page.json')) {
				file_put_contents($tmpFolder . '/' . $lang . '/page.json', json_encode($pageParam));
				mkdir ($tmpFolder . '/' . $lang . '/content', 0755);
				file_put_contents($tmpFolder . '/' . $lang . '/content/' . $this->getData(['page', $pageId, 'content']), $pageContent);
			}
			// création du zip
			$fileName =  $this->getUrl(2) . '.zip';
			$this->makeZip ($fileName, $tmpFolder, []);
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
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'addon',
					'notification' => 'Quelque chose s\'est mal passé',
					'state' => false
				]);
			}
		}
	}

	/*
	* Importer des données d'un module externes ou interne à module.json
	*/
	public function import(){
		// Jeton incorrect
		if ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'addon',
				'state' => false,
				'notification' => 'Action non autorisée'
			]);
		}
		else {
			// Soumission du formulaire
			if($this->isPost()) {
				// Récupérer le fichier et le décompacter
				$zipFilename =	$this->getInput('addonImportFile', helper::FILTER_STRING_SHORT, true);
				$tempFolder = uniqid();
				mkdir (self::TEMP_DIR . $tempFolder, 0755);
				$zip = new ZipArchive();
				if ($zip->open(self::FILE_DIR . 'source/' . $zipFilename) === TRUE) {
					$zip->extractTo(self::TEMP_DIR  . $tempFolder );
				}
				// Import des données localisées page.json et module.json
				// Pour chaque dossier localisé
				$dataTarget = array();
				$dataSource = array();
				// Liste des pages de même nom dans l'archive et le site
				$list = '';
				foreach (self::$i18nList as $key=>$value) {
					// Les Pages et les modules
					foreach (['page','module'] as $fileTarget){
						if (file_exists(self::TEMP_DIR . $tempFolder . '/' .$key . '/' . $fileTarget . '.json')) {
							// Le dossier de langue existe
							// faire la fusion
							$dataSource  = json_decode(file_get_contents(self::TEMP_DIR . $tempFolder . '/' .$key . '/' . $fileTarget . '.json'), true);
							// Des pages de même nom que celles de l'archive existent
							if( $fileTarget === 'page' ){
								foreach( $dataSource as $keydataSource=>$valuedataSource ){
									foreach( $this->getData(['page']) as $keypage=>$valuepage ){
										if( $keydataSource === $keypage){
											$list === '' ? $list .= ' '.$this->getData(['page', $keypage, 'title']) : $list .= ', '.$this->getData(['page', $keypage, 'title']);
										}
									}
								}
							}
							$dataTarget  = json_decode(file_get_contents(self::DATA_DIR . $key . '/' . $fileTarget . '.json'), true);
							$data [$fileTarget] = array_merge($dataTarget[$fileTarget], $dataSource);
							if( $list === ''){
								file_put_contents(self::DATA_DIR . '/' .$key . '/' . $fileTarget . '.json', json_encode( $data ,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|LOCK_EX) );
							}
							// copie du contenu de la page
							$this->copyDir (self::TEMP_DIR . $tempFolder . '/' .$key . '/content', self::DATA_DIR . '/' .$key . '/content');
							// Supprimer les fichiers importés
							unlink (self::TEMP_DIR . $tempFolder . '/' .$key . '/' . $fileTarget . '.json');
						}
					}
				}

				// Import des fichiers placés ailleurs que dans les dossiers localisés.
				$this->copyDir (self::TEMP_DIR . $tempFolder,self::DATA_DIR );

				// Supprimer le dossier temporaire
				$this->removeDir(self::TEMP_DIR . $tempFolder);
				$zip->close();
				if( $list !== '' ){
					 $success = false;
					strpos( $list, ',') === false ? $notification = 'Import impossible la page suivante doit être renommée :'.$list : $notification = 'Import impossible les pages suivantes doivent être renommées :'.$list;
				}
				else{
					 $success = true;
					 $notification = 'Import réussi';
				}
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'addon',
					'state' => $success,
					'notification' => $notification
				]);
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Importer des données de module',
				'view' => 'import'
			]);
		}
	}

}
