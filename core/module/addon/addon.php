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
		'moduleDelete' => self::GROUP_ADMIN,
		'export' => self::GROUP_ADMIN,
		'import' => self::GROUP_ADMIN
	];

	// Gestion des modules
	public static $modInstal = [];

	// pour tests
	public static $valeur = [];

	/*
	* Effacement d'un module installé et non utilisé
	*/
	public function moduleDelete() {

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
					if (!$this->removeDir($infoModules[$this->getUrl(2)]['dataDirectory'])){
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
				//array_key_exists('delete',$infoModules[$key]) && $infoModules[$key]['delete'] === true && implode(', ',array_keys($inPages,$key)) === ''
				$infoModules[$key]['delete'] === true  && implode(', ',array_keys($inPages,$key)) === ''
											? template::button('moduleDelete' . $key, [
													'class' => 'moduleDelete buttonRed',
													'href' => helper::baseUrl() . $this->getUrl(0) . '/moduleDelete/' . $key . '/' . $_SESSION['csrf'],
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

		// Retour du formulaire ?
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
			}

			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => $notification,
				'state' => $success
			]);
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
					mkdir ($tmpFolder . '/' . $lang, 0777, true);
				} else {
					$this->removeDir($tmpFolder . '/' . $lang);
					mkdir ($tmpFolder . '/' . $lang, 0777, true);
				}
				// Créer le dossier temporaire des données du  module
				if ($infoModules[$this->getUrl(2)]['dataDirectory']) {
					if (!is_dir($tmpFolder . '/' . $moduleDir)) {
						mkdir ($tmpFolder . '/' . $moduleDir, 0777, true) ;
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
		else{
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
