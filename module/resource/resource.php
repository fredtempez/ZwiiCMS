<?php

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */
class resource extends common
{
	const VERSION = '2.04';
	const REALNAME = 'Partage';
	const DATADIRECTORY = '';  // Contenu localisé inclus par défaut (page.json et module.json)

	public static $actions = [
		'config' => self::ROLE_EDITOR,
		'index' => self::ROLE_VISITOR,
		'dirs' => self::ROLE_EDITOR,
	];

	// Contenu du chemin sélectionné
	public static $folders = '';

	public static $sharePath = [
		'site/file/source/'
	];

	public static $iconPack = [
		'cla' => 'Classique',
		'hct' => 'Contraste élevé',
		'sqo' => 'Carré',
		'viv' => 'Vivid'
	];

	public static $iconSize = [
		'md' => 'Standard',
		'lg' => 'Moyenne',
		'xl' => 'Grande'
	];

	public static $target = [
		'data-lity' => 'Images dans une lightbox (lity), sinon téléchargement',
		'blank' => 'Nouvel onglet',
		'download' => 'Téléchargement forcé',
	];

	public function index()
	{

		// Générer l'affichage
		self::$folders = $this->getFolderContent($this->getData(['module', $this->getUrl(0), 'path']), $this->getData(['module', $this->getUrl(0)]));
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
					'path' => $this->getInput('resourceConfigPath'),
					'title' => $this->getInput('resourceConfigTitle'),
					'sort' => $this->getInput('resourceConfigSort', helper::FILTER_BOOLEAN),
					'subfolder' => $this->getInput('resourceConfigSubfolder', helper::FILTER_BOOLEAN),
					'folder' => $this->getInput('resourceConfigFolder', helper::FILTER_BOOLEAN),
					'details' => $this->getInput('resourceConfigDetails', helper::FILTER_BOOLEAN),
					'folderstate' => $this->getInput('resourceConfigFolderState', helper::FILTER_BOOLEAN),
					'expandcontrol' => $this->getInput('resourceConfigExpandControl', helper::FILTER_BOOLEAN),
					'iconpack' => $this->getInput('resourceConfigiconPack'),
					'iconsize' => $this->getInput('resourceConfigIconSize'),
					'target' => $this->getInput('resourceConfigTarget'),
				]
			]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}

		// Valeurs en sortie
		$this->addOutput([
			'view' => 'config'
		]);
	}

	private function getFolderContent($chemin, $config = [])
	{
		$showSubFolder = isset($config['subfolder']) ? $config['subfolder'] : true;
		$sort = isset($config['sort']) ? $config['sort'] : true;
		$showDetails = isset($config['details']) ? $config['details'] : false;
		$initialFolderState = isset($config['folderstate']) ? $config['folderstate'] : 'collapsed';
		$iconPack = isset($config['iconpack']) || !empty($config['iconpack']) ? $config['iconpack'] : 'classic';
		$iconSize = isset($config['iconsize']) || !empty($config['iconsize']) ? $config['iconsize'] : 'md';
		$target = isset($config['target']) || !empty($config['target']) ? $config['target'] : 'data-lity';

		// Vérifier si le chemin existe et est un dossier
		if (
			!empty($chemin) &&
			is_dir($chemin)
		) {
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
						$items .= '<li class="fiv-' . $iconPack . ' fiv-icon-folder ' . $folderClass . ' fiv-size-' . $iconSize . ' "><span class="toggle">' . $subDirectory . '</span><ul class="sub-items"';
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
					$fileExtension = pathinfo($fileFullPath, PATHINFO_EXTENSION);  // Obtenir l'extension du fichier
					$fileExtensionClass = strtolower($fileExtension);  // Convertir l'extension en minuscules pour une classe CSS
					if ($showDetails) {
						$fileSize = filesize($fileFullPath);
						$fileSizeFormatted = $this->formatSizeUnits($fileSize);
						$fileInfo = '<div class="file-info-container">' . date('d/m/Y H:i', filemtime($fileFullPath)) . ' - ' . $fileSizeFormatted . '</div>';
					}

					// Redirige la cible vers un téléchargement lorsque le format n'est pas supporté par Lity pour éviter unefenêtre Lity vide.
					// Génére l'attribut _blank
					switch ($target) {
						case 'data-lity':
							$target = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg', 'ico', 'webp', 'avif'])
								? 'data-lity'
								: 'download';
							break;
						case 'blank':
							$target = 'target="_blank"';
							break;
					}

					// Ajouter des balises d'ancrage autour du nom de fichier pour le rendre cliquable
					$items .= '<li class=" fiv-' . $iconPack . ' fiv-icon-' . $fileExtensionClass . ' fiv-size-' . $iconSize . ' "><a href="' . $fileFullPath . '" ' . $target . ' ><div class="fileContainer"><div class="file-name">' . $file . '</div>' . $fileInfo . '</div></a></li>';
				}

				// Fermer la liste
				$items .= '</ul>';

				return $items;
			}
		}

		return '';
	}

	private function formatSizeUnits($bytes)
	{
		$units = array('octets', 'Ko', 'Mo', 'Go', 'To');
		$i = 0;
		while ($bytes >= 1024) {
			$bytes /= 1024;
			$i++;
		}
		return round($bytes, 2) . ' ' . $units[$i];
	}

	public function dirs()
	{
		// Déterminer le $rootPath à explorer
		$rootPath = $this->getUserPath();
		// Liste des dossiers
		$sharePath = helper::getSubdirectories($rootPath);
		// Tri des dossiers sur la clé
		ksort($sharePath);
		$sharePath = array_flip($sharePath);
		// Parcours des dossiers
		foreach ($sharePath as $path => $name) {
			$sharePath[$path] = [
				'name' => $name,
				'path' => $path
			];
		}
		// Valeurs en sortie
		$this->addOutput([
			'display' => self::DISPLAY_JSON,
			'content' => array_values($sharePath)
		]);
	}

}
