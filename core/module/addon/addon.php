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
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

class addon extends common {

	public static $actions = [
		'index' => self::GROUP_ADMIN,
		'moduleDelete' => self::GROUP_ADMIN,
		'exportModuleData' => self::GROUP_ADMIN,
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
			if( $this->removeDir('./module/'.$this->getUrl(2) ) === true){
				$success = true;
				$notification = 'Module '.$this->getUrl(2) .' effacé du dossier /module/, il peut rester des données dans d\'autres dossiers';
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
				is_array($infoModules[$key]['dataDirectory']) && implode(', ',array_keys($inPages,$key)) !== ''
											? template::button('moduleExport' . $key, [
												'class' => 'buttonBlue',
												'href' => helper::baseUrl(). $this->getUrl(0) . '/exportModuleData/' . $key,// appel de fonction vaut exécution, utiliser un paramètre
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
							while (($file = readdir($dh)) !== false) {
								$moduleName = $file;
							}
							closedir($dh);
						}
						// Module normalisé ?
						if( is_file( $moduleDir.'/'.$moduleName.'/'.$moduleName.'.php' ) AND is_file( $moduleDir.'/'.$moduleName.'/view/index/index.php' ) ){

							// Lecture de la version du module pour validation de la mise à jour
							// Pour une version <= version installée l'utilisateur doit cocher 'Mise à jour forcée'
							$version = '0.0';
							$file = file_get_contents( $moduleDir.'/'.$moduleName.'/'.$moduleName.'.php');
							$file = str_replace(' ','',$file);
							$file = str_replace("\t",'',$file);
							$pos1 = strpos($file, 'constVERSION');
							if( $pos1 !== false){
								$posdeb = strpos($file, "'", $pos1);
								$posend = strpos($file, "'", $posdeb + 1);
								$version = substr($file, $posdeb + 1, $posend - $posdeb - 1);
							}

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
							$validMaj = $infoModules[$moduleName]['update'] && ( $newVersion || $checkValidMaj);

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
								if( $infoModules[$moduleName]['update'] === false){
									$notification = ' Mise à jour par ce procédé interdite par le concepteur du module';
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
	* Création récursive d'un zip
	* https://makitweb.com/how-to-create-and-download-a-zip-file-with-php/
	*/
	private function createZip($zip,$dir){
		if (is_dir($dir)){
			if ($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false){
					// If file
					if (is_file($dir.$file)) {
						if($file != '' && $file != '.' && $file != '..'){
						   $zip->addFile($dir.$file);
						}
					}
					else{
						// If directory
						if(is_dir($dir.$file) ){
							if($file != '' && $file != '.' && $file != '..'){
								// Add empty directory
								$zip->addEmptyDir($dir.$file);
								$folder = $dir.$file.'/';
								// Read data of the folder
								$this->createZip($zip,$folder);
							}
						}
					}
				}
				closedir($dh);
			}
		}
	}

	/*
	* Export des données d'un module externes ou interne à module.json
	*/
	public function exportModuleData(){
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
				foreach ($infoModules[$this->getUrl(2)]['dataDirectory'] as $moduleId) {
					/**
					 * Données module.json ?
					 */
					if (strpos($moduleId,'module.json')) {
						// Création de l'arborescence des langues
						// Pas de nom dossier de langue - dossier par défaut
						$t = explode ('/',$moduleId);
						if ( is_array($t)) {
							$path = 'fr';
						} else {
							$path = $t[0];
						}
						// Créer le dossier si inexistant
						if (!is_dir($tmpFolder . '/' . $path)) {
							mkdir ($tmpFolder . '/' . $path);
						}
						// Sauvegarde si données non vides
						$tmpData [$pageId] = $this->getData(['module',$pageId ]);
						if ($tmpData [$pageId] !== null) {
							file_put_contents($tmpFolder . '/' . $moduleId, json_encode($tmpData));
						}
					/**
					 * Données dans un json personnalisé, le sauvegarder
					 */
					} else {
						if (file_exists(self::DATA_DIR . '/' .  $moduleId) &&
							!file_exists($tmpFolder . '/' . $moduleId ) ) {
							copy ( self::DATA_DIR . '/' .  $moduleId, $tmpFolder . '/' . $moduleId );
						}
					}
				}
			}
			// création du zip
			$fileName = self::TEMP_DIR . '/' . $this->geturl(2) . 'zip';
			$this->createZip($fileName,$tmpFolder);
			if (file_exists($fileName)) {
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="' . $fileName . '"');
				header('Content-Length: ' . filesize($fileName));
				readfile( $fileName);
				// Valeurs en sortie
				$this->addOutput([
					'display' => self::DISPLAY_RAW
				]);
				//unlink($filename);
				//$this->removeDir($tmpFolder);
				// Valeurs en sortie

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
