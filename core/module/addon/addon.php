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
 * @copyright Copyright (C) 2018-2021, Frédéric Tempez
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
		'storeDownload'=> self::GROUP_ADMIN
	];

	const URL_STORE = 'http://zwiicms.fr/?modules/list';
	const BASEURL_STORE = 'http://zwiicms.fr/';

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
			$list = '';
			foreach( $infoModules[$module]['dataDirectory'] as $moduleId){
				if (strpos($moduleId,'module.json') === false && strpos($moduleId,'page.json') === false) {
					$list === '' ? $list = self::DATA_DIR.$moduleId : $list .= ', '.self::DATA_DIR. $moduleId;
				}
			}
			if( $this->removeDir('./module/'.$module ) === true){
				$success = true;
				if( $list === ''){
					$notification = 'Module '.$module .' désinstallé';
				}
				else{
					$notification = 'Module '.$module .' désinstallé, il reste des données dans '.$list;
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
	 * Installation manuel d'un module par téléchargement
	 */
	public function upload() {
		// Soumission du formulaire
		if($this->isPost()) {
			// Installation d'un module
			$success = true;
			$checkValidMaj = $this->getInput('configModulesCheck', helper::FILTER_BOOLEAN);
			$zipFilename =	$this->getInput('configModulesInstallation', helper::FILTER_STRING_SHORT);
			if( $zipFilename !== ''){
				$tempFolder = 'datamodules';//uniqid();
				$zip = new ZipArchive();
				if ($zip->open(self::FILE_DIR.'source/'.$zipFilename) === TRUE) {
					$notification = 'Archive ouverte';
					mkdir (self::TEMP_DIR . $tempFolder);
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
							if( $infoModules[$moduleName]['update'] >= $update ) $valUpdate = true;

							// Module déjà installé ?
							$moduleInstal = false;
							foreach( self::$modInstal as $key=>$value){
								if($moduleName === $value[0]){
									$moduleInstal = true;
								}
							}

							// Validation de la maj si autorisation du concepteur du module ET
							// ( Version plus récente OU Check de forçage )
							$valNewVersion = floatval($version);
							$valInstalVersion = floatval( $infoModules[$moduleName]['version'] );
							$newVersion = false;
							if( $valNewVersion > $valInstalVersion ) $newVersion = true;
							$validMaj = $valUpdate && ( $newVersion || $checkValidMaj);

							// Nouvelle installation ou mise à jour du module
							if( ! $moduleInstal ||  $validMaj ){
								// Copie récursive des dossiers
								$this -> custom_copy( self::TEMP_DIR . $tempFolder, './' );
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
			}

			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => $notification,
				'state' => $success
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Téléverser un module',
			'view' => 'upload'
		]);
	}

	/**
	 * Catalogue des modules sur le site ZwiiCMS.fr
	 */
	public function store() {
		$store = json_decode(helper::urlGetContents(self::URL_STORE), true);
		if ($store) {
			// Modules installés
			$infoModules = helper::getModules();
			// Clés moduleIds dans les pages
			$inPages = helper::arrayCollumn($this->getData(['page']),'moduleId', 'SORT_DESC');
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
					'<a href="' . helper::baseurl() . $this->getUrl(0) . '/item/' . $key . '" rel="data-lity">'.$store[$key]['title'].'</a>',
					$store[$key]['fileVersion'],
					mb_detect_encoding(strftime('%d %B %Y', $store[$key]['fileDate']), 'UTF-8', true)
					? strftime('%d %B %Y', $store[$key]['fileDate'])
					: utf8_encode(strftime('%d %B %Y', $store[$key]['fileDate'])),
					implode(', ', array_keys($inPagesTitle,$key)),
					template::button('moduleExport' . $key, [
							'class' => $class,
							'href' => helper::baseUrl(). $this->getUrl(0) . '/installModule/' . $key.'/' . $_SESSION['csrf'],// appel de fonction vaut exécution, utiliser un paramètre
							'value' => $ico
							])
					 ];


			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Catalogue de modules',
			'view' => 'store'
		]);
	}

	/**
	 * Détail d'un objet du catalogue
	 */
	public function item() {
		$store = json_decode(helper::urlGetContents(self::URL_STORE), true);
		self::$storeItem = $store [$this->getUrl(2)] ;
		self::$storeItem ['fileDate'] = mb_detect_encoding(strftime('%d %B %Y',self::$storeItem ['fileDate']), 'UTF-8', true)
										? strftime('%d %B %Y', self::$storeItem ['fileDate'])
										: utf8_encode(strftime('%d %B %Y', self::$storeItem ['fileDate']));
		// Valeurs en sortie
		$this->addOutput([
			'title' =>'Module ' . self::$storeItem['title'],
			'view' => 'item',
			'display' => self::DISPLAY_LAYOUT_LIGHT
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
		$inPages = helper::arrayCollumn($this->getData(['page']),'moduleId', 'SORT_DESC');
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
				is_array($infoModules[$key]['dataDirectory']) && implode(', ',array_keys($inPages,$key)) !== ''
											? template::button('moduleExport' . $key, [
												'href' => helper::baseUrl(). $this->getUrl(0) . '/export/' . $key,// appel de fonction vaut exécution, utiliser un paramètre
												'value' => template::ico('download')
												])
											: '',
				is_array($infoModules[$key]['dataDirectory']) && implode(', ',array_keys($inPages,$key)) === ''
											? template::button('moduleExport' . $key, [
												'href' => helper::baseUrl(). $this->getUrl(0) . '/import/' . $key.'/' . $_SESSION['csrf'],// appel de fonction vaut exécution, utiliser un paramètre
												'value' => template::ico('upload')
												])
											: ''
			];
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Modules installés',
			'view' => 'index'
		]);
	}

	/*
	* Copie récursive de dossiers
	*
	*/
	private function custom_copy($src, $dst) {
		// open the source directory
		$dir = opendir($src);
		// Make the destination directory if not exist
		if (!is_dir($dst)) {
			mkdir($dst);
		}
		// Loop through the files in source directory
		while( $file = readdir($dir) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ){
					// Recursively calling custom copy function
					// for sub directory
					$this -> custom_copy($src . '/' . $file, $dst . '/' . $file);
				}
				else {
					copy($src . '/' . $file, $dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}


	/*
	* Export des données d'un module externes ou interne à module.json
	*/
	public function export(){
		// Lire les données du module
		$infoModules = helper::getModules();
		// Créer un dossier par défaut
		$tmpFolder = self::TEMP_DIR . uniqid();
		//$tmpFolder = self::TEMP_DIR . 'test';
		if (!is_dir($tmpFolder)) {
			mkdir($tmpFolder);
		}
		// Clés moduleIds dans les pages
		$inPages = helper::arrayCollumn($this->getData(['page']),'moduleId', 'SORT_DESC');
		// Parcourir les pages utilisant le module
		foreach (array_keys($inPages,$this->getUrl(2)) as $pageId) {
			// Export des pages hébergeant le module
			$pageContent[$pageId] = $this->getData(['page',$pageId]);
			// Export de fr/module.json
			$moduleId = 'fr/module.json';
			// Création de l'arborescence des langues
			// Pas de nom dossier de langue - dossier par défaut
			$t = explode ('/',$moduleId);
			if ( is_array($t)) {
				$lang = 'fr';
			} else {
				$lang = $t[0];
			}
			// Créer le dossier si inexistant
			if (!is_dir($tmpFolder . '/' . $lang)) {
				mkdir ($tmpFolder . '/' . $lang);
			}
			// Sauvegarde si données non vides
			$tmpData [$pageId] = $this->getData(['module',$pageId ]);
			if ($tmpData [$pageId] !== null) {
				file_put_contents($tmpFolder . '/' . $moduleId, json_encode($tmpData));
			}
			// Export des données localisées dans des dossiers
			foreach ($infoModules[$this->getUrl(2)]['dataDirectory'] as $dirId) {
					if ( file_exists(self::DATA_DIR . '/' .  $dirId)
						&& !file_exists($tmpFolder . '/' . $dirId ) ) {
							$this->custom_copy ( self::DATA_DIR . '/' .  $dirId, $tmpFolder . '/' . $dirId );
					}
			}
		}
		// Enregistrement des pages dans le dossier de langue identique à module

		if (!file_exists($tmpFolder . '/' . $lang . '/page.json')) {
			file_put_contents($tmpFolder . '/' . $lang . '/page.json', json_encode($pageContent));
		}

		// création du zip
		$fileName =  $this->getUrl(2) . '.zip';
		$this->makeZip ($fileName, $tmpFolder, []);
		if (file_exists($fileName)) {
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
			header('Content-Length: ' . filesize($fileName));
			readfile( $fileName);
			// Valeurs en sortie
			$this->addOutput([
				'display' => self::DISPLAY_RAW
			]);
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
				mkdir (self::TEMP_DIR . $tempFolder);
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
							// Supprimer les fichiers importés
							unlink (self::TEMP_DIR . $tempFolder . '/' .$key . '/' . $fileTarget . '.json');
						}
					}
				}

				// Import des fichiers placés ailleurs que dans les dossiers localisés.
				$this->custom_copy (self::TEMP_DIR . $tempFolder,self::DATA_DIR );

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
