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
 * @copyright Copyright (C) 2018-2024, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class folder extends common
{

	const VERSION = '0.1';
	const REALNAME = 'Partage de dossier';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	public static $actions = [
		'config' => self::GROUP_EDITOR,
		'index' => self::GROUP_VISITOR,
	];

	// Contenu du chemin sélectionné
	public static $folders = '';

	public static $sharePath = [
		'site/file/source/'
	];

	public function index()
	{

		// Configuration de l'affichage
		$config['showsubfolder'] = $this->getData(['module', $this->getUrl(0), 'subfolder']);
		$config['sort'] = $this->getData(['module', $this->getUrl(0), 'sort']);
		$config['showdetails'] = $this->getData(['module', $this->getUrl(0), 'details']);
		$config['initialfolderstate'] = $this->getData(['module', $this->getUrl(0), 'folderstate']);

		// Générer l'affichage
		self::$folders = $this->getFolderContent($this->getData(['module', $this->getUrl(0), 'path']), $config);

		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index'
		]);
	}


	public function config()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			$this->setData([
				'module',
				$this->getUrl(0),
				[
					'path' => preg_replace('/^\\./', '', $this->getInput('folderConfigPath')),
					'title' => $this->getInput('folderConfigTitle'),
					'sort' => $this->getInput('folderConfigSort', helper::FILTER_BOOLEAN),
					'subfolder' => $this->getInput('folderConfigSubfolder', helper::FILTER_BOOLEAN),
					'folder' => $this->getInput('folderConfigFolder', helper::FILTER_BOOLEAN),
					'details' => $this->getInput('folderConfigDetails', helper::FILTER_BOOLEAN),
					'folderstate' => $this->getInput('folderConfigFolderState', helper::FILTER_BOOLEAN),
				]
			]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0),
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);

		}

		self::$sharePath = $this->getSubdirectories('site/file/source');
		self::$sharePath = array_flip(self::$sharePath);

		// Valeurs en sortie
		$this->addOutput([
			'view' => 'config'
		]);
	}

	private function getFolderContent($chemin, $config = [])
	{
		$showSubFolder = isset($config['showsubfolder']) ? $config['showsubfolder'] : true;
		$sort = isset($config['sort']) ? $config['sort'] : true;
		$showDetails = isset($config['showdetails']) ? $config['showdetails'] : false;
		$initialFolderState = isset($config['initialfolderstate']) ? $config['initialfolderstate'] : 'collapsed';
	
		// Vérifier si le chemin existe et est un dossier
		if (is_dir($chemin)) {
			// Ouvrir le dossier
			if ($dh = opendir($chemin)) {
				// Initialiser les tableaux pour les sous-dossiers et les fichiers
				$subDirectories = [];
				$files = [];
	
				// Parcourir les éléments du dossier
				while (($element = readdir($dh)) !== false) {
					// Exclure les éléments spéciaux
					if ($element != '.' && $element != '..') {
						// Construire le chemin complet de l'élément
						$cheminComplet = $chemin . '/' . $element;
	
						// Vérifier si c'est un dossier
						if (is_dir($cheminComplet)) {
							// Ajouter le dossier au tableau des sous-dossiers
							$subDirectories[] = $element;
						} else {
							// Ajouter le fichier au tableau des fichiers
							$files[] = $element;
						}
					}
				}
	
				// Fermer le dossier
				closedir($dh);
	
				// Trier les sous-dossiers et les fichiers si nécessaire
				if ($sort) {
					sort($subDirectories);
					sort($files);
				}
	
				// Initialiser la liste des éléments
				$items = '<ul>';
	
				// Ajouter les sous-dossiers à la liste si configuré pour les afficher
				if ($showSubFolder) {
					foreach ($subDirectories as $subDirectory) {
						$folderClass = '';
						if ($initialFolderState == 'collapsed') {
							$folderClass = 'collapsible';
						}
						$items .= "<li class='directory $folderClass'><span class='toggle'>$subDirectory</span><ul class='sub-items'";
						if ($initialFolderState == 'collapsed') {
							$items .= " style='display:none;'";
						}
						$items .= '>';
						// Appeler récursivement la fonction pour ce sous-dossier
						$items .= $this->getFolderContent($chemin . '/' . $subDirectory, $config);
						$items .= '</ul></li>';
					}
				}
	
				// Ajouter les fichiers à la liste
				foreach ($files as $file) {
					$fileFullPath = $chemin . '/' . $file;
					$fileInfo = '';
					if ($showDetails) {
						$fileSize = filesize($fileFullPath);
						$fileSizeFormatted = $this->formatSizeUnits($fileSize);
						$fileInfo = "<div class='file-info-container'>" . date("d-m-Y H:i", filemtime($fileFullPath)) . " - $fileSizeFormatted</div>";
					}
					$items .= "<li class='file'><a href='$fileFullPath' data-lity>$file</a>$fileInfo</li>";
				}
	
				// Fermer la liste
				$items .= "</ul>";
	
				return $items;
			}
		}
	
		return '';
	}
	



	private function formatSizeUnits($bytes)
	{
		$units = array('octest', 'Ko', 'Mo', 'Go', 'To');
		$i = 0;
		while ($bytes >= 1024) {
			$bytes /= 1024;
			$i++;
		}
		return round($bytes, 2) . ' ' . $units[$i];
	}









	/**
	 * Liste les dossier contenus dans RFM
	 */
	private function getSubdirectories($dir, $basePath = '')
	{
		$subdirs = array();
		// Ouvrez le répertoire spécifié
		$dh = opendir($dir);
		// Parcourez tous les fichiers et répertoires dans le répertoire
		while (($file = readdir($dh)) !== false) {
			// Ignorer les entrées de répertoire parent et actuel
			if ($file == '.' || $file == '..') {
				continue;
			}
			// Construisez le chemin complet du fichier ou du répertoire
			$path = $dir . '/' . $file;
			// Vérifiez si c'est un répertoire
			if (is_dir($path)) {
				// Construisez la clé et la valeur pour le tableau associatif
				$key = $basePath === '' ? ucfirst($file) : $basePath . '/' . $file;
				$value = $path . '/';
				// Ajouter la clé et la valeur au tableau associatif
				$subdirs[$key] = $value;
				// Appeler la fonction récursivement pour ajouter les sous-répertoires
				$subdirs = array_merge($subdirs, $this->getSubdirectories($path, $key));
			}
		}
		// Fermez le gestionnaire de dossier
		closedir($dh);
		return $subdirs;
	}

}