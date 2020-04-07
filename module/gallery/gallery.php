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
 * @link http://zwiicms.com/
 */

class gallery extends common {

	public static $actions = [
		'config' => self::GROUP_MODERATOR,
		'delete' => self::GROUP_MODERATOR,
		'dirs' => self::GROUP_MODERATOR,
		'edit' => self::GROUP_MODERATOR,
		'filter' => self::GROUP_MODERATOR,
		'index' => self::GROUP_VISITOR		
	];

	public static $sort = [
		'SORT_ASC' => 'Alphabétique ',
		'SORT_DSC' => 'Alphabétique inversé',
		'none' => 'Aucun tri',
	];

	public static $directories = [];

	public static $firstPictures = [];

	public static $galleries = [];

	public static $galleriesId = [];

	public static $pictures = [];

	public static $thumbs = [];

	const GALLERY_VERSION = '2.0';	


	public function filter() {
	// Traitement du tri
		$data = explode('&',($this->getInput('galleryConfigFilterResponse')));
		$data = str_replace('galleryTable%5B%5D=','',$data);
		for($i=0;$i<count($data);$i++) {
			$this->setData(['module', $this->getUrl(0), $data[$i], [
				'config' => [
					'name' => $this->getData(['module',$this->getUrl(0),$data[$i],'config','name']),
					'directory' => $this->getData(['module',$this->getUrl(0),$data[$i],'config','directory']),
					'homePicture' => $this->getData(['module',$this->getUrl(0),$data[$i],'config','homePicture']),
					'sort' => $this->getData(['module',$this->getUrl(0),$data[$i],'config','sort']),
					'position' => $i + 1 
				],
				'legend' => $this->getData(['module',$this->getUrl(0),$data[$i],'legend'])
			]]);
		}		
		$this->saveData();
		// Valeurs en sortie
		// Recharge la page
		header('Refresh: 0;url='. helper::baseUrl() . $this->getUrl() );	
	}

	/**
	 * Configuration
	 */
	public function config() {
		// Tri des galeries 
		$g = $this->getData(['module', $this->getUrl(0)]);
		$p = helper::arrayCollumn(helper::arrayCollumn($g,'config'),'position');
		asort($p,SORT_NUMERIC);		
		$galleries = [];
		foreach ($p as $positionId => $item) {
			$galleries [$positionId] = $g[$positionId];			
		}
		// Traitement de l'affichage
		if($galleries) {	
			foreach($galleries as $galleryId => $gallery) {
				// Erreur dossier vide
				if(is_dir($gallery['config']['directory'])) {
					if(count(scandir($gallery['config']['directory'])) === 2) {
						$gallery['config']['directory'] = '<span class="galleryConfigError">' . $gallery['config']['directory'] . ' (dossier vide)</span>';
					}
				}
				// Erreur dossier supprimé
				else {
					$gallery['config']['directory'] = '<span class="galleryConfigError">' . $gallery['config']['directory'] . ' (dossier introuvable)</span>';
				}
				// Met en forme le tableau
				self::$galleries[] = [	
					template::ico('sort'),				
					$gallery['config']['name'],
					$gallery['config']['directory'],
					template::button('galleryConfigEdit' . $galleryId , [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $galleryId  . '/' . $_SESSION['csrf'],
						'value' => template::ico('pencil')
					]),
					template::button('galleryConfigDelete' . $galleryId, [
						'class' => 'galleryConfigDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $galleryId . '/' . $_SESSION['csrf'],
						'value' => template::ico('cancel')
					])
				];
				// Tableau des id des galleries pour le drag and drop
				self::$galleriesId[] = $galleryId;
			}
		}
		// Soumission du formulaire

		if($this->isPost()) {
			if ($this->getInput('galleryConfigFilterResponse')) {
				self::filter();
			} else {
				$galleryId = helper::increment($this->getInput('galleryConfigName', helper::FILTER_ID, true), (array) $this->getData(['module', $this->getUrl(0)]));
				// La première image est celle de la couverture de l'album
				$directory = $this->getInput('galleryConfigDirectory', helper::FILTER_STRING_SHORT, true);
				$iterator = new DirectoryIterator($directory);				
				foreach($iterator as $fileInfos) {
					if($fileInfos->isDot() === false AND $fileInfos->isFile() AND @getimagesize($fileInfos->getPathname())) {						
						// Créer la miniature si manquante
						if (!file_exists( str_replace('source','thumb',$fileInfos->getPathname()) . '/' . self::THUMBS_SEPARATOR  . strtolower($fileInfos->getFilename()))) {
							$this->makeThumb($fileInfos->getPathname(),
											str_replace('source','thumb',$fileInfos->getPath()) .  '/' . self::THUMBS_SEPARATOR  . strtolower($fileInfos->getFilename()),
											self::THUMBS_WIDTH);
						}
						// Miniatures 
						$homePicture = file_exists( str_replace('source','thumb',$directory) . '/' . self::THUMBS_SEPARATOR  . strtolower($fileInfos->getFilename())) 
							?  self::THUMBS_SEPARATOR .  strtolower($fileInfos->getFilename())
							:  strtolower($fileInfos->getFilename());
					break;
					}
				}

				$this->setData(['module', $this->getUrl(0), $galleryId, [
					'config' => [
						'name' => $this->getInput('galleryConfigName'),
						'directory' => $this->getInput('galleryConfigDirectory', helper::FILTER_STRING_SHORT, true),
						'homePicture' => $homePicture,
						'sort' => $this->getInput('galleryConfigSort'),
						'position' => count($this->getData(['module',$this->getUrl(0)])) + 1
					],
					'legend' => []
				]]);
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(),
					'notification' => 'Modifications enregistrées',
					'state' => true
				]);
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Configuration du module',
			'view' => 'config',
			'vendor' => [
				'tablednd'
			]
		]);
	}

	/**
	 * Suppression
	 */
	public function delete() {
		// $url prend l'adresse sans le token	
		// La galerie n'existe pas
		if($this->getData(['module', $this->getUrl(0), $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Jeton incorrect
		if ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Suppression  non autorisée'
			]);
		}		
		// Suppression
		else {
			$this->deleteData(['module', $this->getUrl(0), $this->getUrl(2)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Galerie supprimée',
				'state' => true
			]);
		}
	}

	/**
	 * Liste des dossiers
	 */
	public function dirs() {
		// Valeurs en sortie
		$this->addOutput([
			'display' => self::DISPLAY_JSON,
			'content' => galleriesHelper::scanDir(self::FILE_DIR.'source')
		]);
	}

	/**
	 * Édition
	 */
	public function edit() {
		// Jeton incorrect
		if ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Action  non autorisée'
			]);
		}			
		// La galerie n'existe pas
		if($this->getData(['module', $this->getUrl(0), $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// La galerie existe
		else {
			// Soumission du formulaire
			if($this->isPost()) {
				// Si l'id a changée
				$galleryId = $this->getInput('galleryEditName', helper::FILTER_ID, true);
				if($galleryId !== $this->getUrl(2)) {
					// Incrémente le nouvel id de la galerie
					$galleryId = helper::increment($galleryId, $this->getData(['module', $this->getUrl(0)]));
					// Supprime l'ancienne galerie
					$this->deleteData(['module', $this->getUrl(0), $this->getUrl(2)]);
				}
				// légendes
				$legends = [];
				foreach((array) $this->getInput('legend', null) as $file => $legend) {
					$file = str_replace('.','',$file);
					$legends[$file] = helper::filter($legend, helper::FILTER_STRING_SHORT);
				}
				// Photo de la page de garde de l'album
				$homePicture = array_keys($this->getInput('homePicture', null));
				// Sauvegarder
				$this->setData(['module', $this->getUrl(0), $galleryId, [
					'config' => [
						'name' => $this->getInput('galleryEditName', helper::FILTER_STRING_SHORT, true),
						'directory' => $this->getInput('galleryEditDirectory', helper::FILTER_STRING_SHORT, true),
						'homePicture' => $homePicture[0],
						'sort' => $this->getInput('galleryEditSort'),
						'position' => count($this->getData(['module',$this->getUrl(0)])) + 1
					],
					'legend' => $legends
				]]);
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
					'notification' => 'Modifications enregistrées',
					'state' => true
				]);
			}
			// Met en forme le tableau
			$directory = $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'config', 'directory']);
			if(is_dir($directory)) {
				$iterator = new DirectoryIterator($directory);
				foreach($iterator as $fileInfos) {
					if($fileInfos->isDot() === false AND $fileInfos->isFile() AND @getimagesize($fileInfos->getPathname())) {
						// Créer la miniature si manquante
						if (!file_exists( str_replace('source','thumb',$fileInfos->getPathname()) . '/' . self::THUMBS_SEPARATOR  . strtolower($fileInfos->getFilename()))) {
							$this->makeThumb($fileInfos->getPathname(),
											str_replace('source','thumb',$fileInfos->getPath()) .  '/' . self::THUMBS_SEPARATOR  . strtolower($fileInfos->getFilename()),
											self::THUMBS_WIDTH);
						}
						self::$pictures[$fileInfos->getFilename()] = [
							$fileInfos->getFilename(),
							template::checkbox( 'homePicture[' . $fileInfos->getFilename() . ']', true, '', [ 
								'checked' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2),'config', 'homePicture']) === $fileInfos->getFilename() ? true : false,
								'class' => 'homePicture'

							]),	
							template::text('legend[' . $fileInfos->getFilename() . ']', [
								'value' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'legend', str_replace('.','',$fileInfos->getFilename())])
							]),
							'<a href="'. str_replace('source','thumb',$directory)  . '/mini_' . $fileInfos->getFilename() .'" rel="data-lity" data-lity=""><img src="'. str_replace('source','thumb',$directory) . '/' . $fileInfos->getFilename() .  '"></a>'
						];
					}
				}
				// Tri des images par ordre alphabétique
				switch ($this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'config', 'sort'])) {
					case 'none':
						break;
					case 'SORT_DSC':
						krsort(self::$pictures,SORT_NATURAL);
						break;													
					case 'SORT_ASC':
					default:
						ksort(self::$pictures,SORT_NATURAL);
						break;
				}	
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'config', 'name']),
				'view' => 'edit'
			]);
		}
	}

	/**
	 * Accueil (deux affichages en un pour éviter une url à rallonge)
	 */
	public function index() {
		// Images d'une galerie
		if($this->getUrl(1)) {
			// La galerie n'existe pas
			if($this->getData(['module', $this->getUrl(0), $this->getUrl(1)]) === null) {
				// Valeurs en sortie
				$this->addOutput([
					'access' => false
				]);
			}
			// La galerie existe
			else {
				// Images de la galerie
				$directory = $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'config', 'directory']);			
				if(is_dir($directory)) {
					$iterator = new DirectoryIterator($directory);
					foreach($iterator as $fileInfos) {
						if($fileInfos->isDot() === false AND $fileInfos->isFile() AND @getimagesize($fileInfos->getPathname())) {
							self::$pictures[$directory . '/' . $fileInfos->getFilename()] = $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'legend', str_replace('.','',$fileInfos->getFilename())]);
							// Créer la miniature si manquante
							if (!file_exists( str_replace('source','thumb',$fileInfos->getPathname()) . '/' . self::THUMBS_SEPARATOR  . strtolower($fileInfos->getFilename()))) {
								$this->makeThumb($fileInfos->getPathname(),
												str_replace('source','thumb',$fileInfos->getPath()) .  '/' . self::THUMBS_SEPARATOR  . strtolower($fileInfos->getFilename()),
												self::THUMBS_WIDTH);
							}							
							// Définir la Miniature
							self::$thumbs[$directory . '/' . $fileInfos->getFilename()] = file_exists( str_replace('source','thumb',$directory) . '/' . self::THUMBS_SEPARATOR  . strtolower($fileInfos->getFilename())) 
								? str_replace('source','thumb',$directory) . '/' . self::THUMBS_SEPARATOR .  strtolower($fileInfos->getFilename())
								: str_replace('source','thumb',$directory) . '/' .  strtolower($fileInfos->getFilename());
						}
					}
					// Tri des images par ordre alphabétique
					switch ($this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'config', 'sort'])) {
						case 'none':
							break;
						case 'SORT_DSC':
							krsort(self::$pictures,SORT_NATURAL);
							break;													
						case 'SORT_ASC':
						default:
							ksort(self::$pictures,SORT_NATURAL);
							break;
					}					
				}
				// Affichage du template
				if(self::$pictures) {
					// Valeurs en sortie
					$this->addOutput([
						'showBarEditButton' => true,
						'title' => $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'config', 'name']),
						/* Désactivé car SLB est actif pour tout le site
						'vendor' => [
							'simplelightbox'
						],*/
						'view' => 'gallery'
					]);
				}
				// Pas d'image dans la galerie
				else {
					// Valeurs en sortie
					$this->addOutput([
						'access' => false
					]);
				}
			}

		}
		// Liste des galeries
		else {
			// Tri des galeries suivant l'ordre défini
			$g = $this->getData(['module', $this->getUrl(0)]);
			$p = helper::arrayCollumn(helper::arrayCollumn($g,'config'),'position');
			asort($p,SORT_NUMERIC);		
			$galleries = [];
			foreach ($p as $positionId => $item) {
				$galleries [$positionId] = $g[$positionId];			
			}
			// Construire le tableau
			foreach((array) $galleries as $galleryId => $gallery) {
				if(is_dir($gallery['config']['directory'])) {
					$iterator = new DirectoryIterator($gallery['config']['directory']);
					foreach($iterator as $fileInfos) {
						if($fileInfos->isDot() === false AND $fileInfos->isFile() AND @getimagesize($fileInfos->getPathname())) {
							self::$galleries[$galleryId] = $gallery;
							// Créer la miniature si manquante
							if (!file_exists( str_replace('source','thumb',$fileInfos->getPathname()) . '/' . self::THUMBS_SEPARATOR  . strtolower($fileInfos->getFilename()))) {
								$this->makeThumb($fileInfos->getPathname(),
												str_replace('source','thumb',$fileInfos->getPath()) .  '/' . self::THUMBS_SEPARATOR  . strtolower($fileInfos->getFilename()),
												self::THUMBS_WIDTH);
							}	
							// Définir l'image de couverture
							self::$firstPictures[$galleryId] = file_exists( str_replace('source','thumb',$gallery['config']['directory']) . '/' . self::THUMBS_SEPARATOR  . strtolower($gallery['config']['homePicture'])) 
								? str_replace('source','thumb',$gallery['config']['directory']) . '/' . self::THUMBS_SEPARATOR .  strtolower($gallery['config']['homePicture'])
								: str_replace('source','thumb',$gallery['config']['directory']) . '/' .  strtolower($gallery['config']['homePicture']);
							continue(2);
						}
					}
				}
			}
			// Valeurs en sortie
			$this->addOutput([
				'showBarEditButton' => true,
				'showPageContent' => true,
				'view' => 'index'
			]);
		}
	}

}

class galleriesHelper extends helper {

	/**
	 * Scan le contenu d'un dossier et de ses sous-dossiers
	 * @param string $dir Dossier à scanner
	 * @return array
	 */
	public static function scanDir($dir) {
		$dirContent = [];
		$iterator = new DirectoryIterator($dir);
		foreach($iterator as $fileInfos) {
			if($fileInfos->isDot() === false AND $fileInfos->isDir()) {
				$dirContent[] = $dir . '/' . $fileInfos->getBasename();
				$dirContent = array_merge($dirContent, self::scanDir($dir . '/' . $fileInfos->getBasename()));
			}
		}
		return $dirContent;
	}
}