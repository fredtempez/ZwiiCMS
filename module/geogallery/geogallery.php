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

class geogallery extends common
{

	const VERSION = '1.3';
	const REALNAME = 'GéoGalerie';
	const DATADIRECTORY = self::DATA_DIR . 'geogallery/';

	const SORT_ASC = 'SORT_ASC';
	const SORT_DSC = 'SORT_DSC';
	const SORT_HAND = 'SORT_HAND';

	public static $galleries = [];

	public static $galleriesId = [];

	public static $pictures = [];

	public static $picturesId = [];

	public static $galleriesCenter = [];

	public static $actions = [
		'config' => self::GROUP_EDITOR,
		'delete' => self::GROUP_EDITOR,
		'dirs' => self::GROUP_EDITOR,
		'edit' => self::GROUP_EDITOR,
		'add' => self::GROUP_EDITOR,
		'index' => self::ROLE_VISITOR
	];


	/**
	 * Mise à jour du module
	 * Appelée par les fonctions index et config
	 */
	private function update()
	{

		//$versionData = $this->getData(['module', $this->getUrl(0), 'config', 'versionData']);

	}


	/**
	 * Configuration
	 */
	public function config()
	{

		// Mise à jour des données de module
		$this->update();

		//Affichage de la galerie triée
		$galleries = $this->getData(['module', $this->getUrl(0), 'content']);

		// Traitement de l'affichage
		if ($galleries) {
			foreach ($galleries as $galleryId => $gallery) {
				// Erreur dossier vide
				if (is_dir($gallery['config']['directory'])) {
					if (count(scandir($gallery['config']['directory'])) === 2) {
						$gallery['config']['directory'] = '<span class="galleryConfigError">' . $gallery['config']['directory'] . ' (dossier vide)</span>';
					}
				}
				// Erreur dossier supprimé
				else {
					$gallery['config']['directory'] = '<span class="galleryConfigError">' . $gallery['config']['directory'] . ' (dossier introuvable)</span>';
				}
				// Met en forme le tableau
				self::$galleries[] = [
					$gallery['config']['name'],
					$gallery['config']['directory'],
					template::button('galleryConfigEdit' . $galleryId, [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $galleryId,
						'value' => template::ico('pencil'),
						'help' => 'Configuration'
					]),
					template::button('galleryConfigDelete' . $galleryId, [
						'class' => 'galleryConfigDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $galleryId,
						'value' => template::ico('trash'),
						'help' => 'Supprimer'
					])
				];
				// Tableau des id des galleries pour le drag and drop
				self::$galleriesId[] = $galleryId;
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Configuration'),
			'view' => 'config'
		]);
	}

	/**
	 * Ajout d'une galerie
	 */
	public function add()
	{
		// Soumission du formulaire d'ajout d'une galerie
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			$galleryId = $this->getInput('galleryAddName', null, true);
			$success = false;
			if ($galleryId) {
				$galleryId = helper::increment($this->getInput('galleryAddName', helper::FILTER_ID, true), (array) $this->getData(['module', $this->getUrl(0), 'content']));
				// définir une vignette par défaut
				$directory = $this->getInput('galleryAddDirectory', helper::FILTER_STRING_SHORT, true);
				$iterator = new DirectoryIterator($directory);
				$i = 0;
				foreach ($iterator as $fileInfos) {
					if ($fileInfos->isDot() === false and $fileInfos->isFile() and @getimagesize($fileInfos->getPathname())) {
						$i += 1;
						// Créer la miniature si manquante
						if (!file_exists(str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename()))) {
							$this->makeThumb(
								$fileInfos->getPathname(),
								str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename()),
								self::THUMBS_WIDTH
							);
						}
						break;
					}
				}
				// Le dossier de la galerie est vide
				if ($i > 0) {
					$this->setData([
						'module',
						$this->getUrl(0),
						'content',
						$galleryId,
						[
							'config' => [
								'name' => $this->getInput('galleryAddName'),
								'directory' => $this->getInput('galleryAddDirectory', helper::FILTER_STRING_SHORT, true),
							],
							'legend' => []
						]
					]);
					$success = true;
				} else {
					self::$inputNotices['galleryAddDirectory'] = "Le dossier sélectionné ne contient aucune image";
					$success = false;
				}
			}

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);

		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Nouvelle galerie'),
			'view' => 'add'
		]);

	}

	/**
	 * Suppression
	 */
	public function delete()
	{
		// La galerie n'existe pas
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true ||
			$this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2)]) === null
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Suppression
		else {
			$this->deleteData(['module', $this->getUrl(0), 'content', $this->getUrl(2)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Galerie effacée'),
				'state' => true
			]);
		}
	}

	/**
	 * Liste des dossiers
	 */
	public function dirs()
	{
		// Valeurs en sortie
		$this->addOutput([
			'display' => self::DISPLAY_JSON,
			'content' => geogalleriesHelper::scanDir(self::FILE_DIR . 'source')
		]);
	}

	/**
	 * Édition
	 */
	public function edit()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// légendes
			$legend = (array) $this->getInput('legend', null);
			foreach ($legend as $file => $data) {
				$legends[str_replace('.', '', $file)] = empty($data) ? $file : helper::filter($data, helper::FILTER_STRING_SHORT);
			}
			// Sauvegarder
			$this->setData([
				'module',
				$this->getUrl(0),
				'content',
				$this->getUrl(2),
				[
					'config' => [
						// Données mises à jour par les options
						'name' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'name']),
						'directory' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'directory']),
					],
					'legend' => $legends
				]
			]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}
		// La galerie n'existe pas
		if ($this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// La galerie existe
		else {
			// Met en forme le tableau
			$directory = $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'directory']);
			if (is_dir($directory)) {
				$iterator = new DirectoryIterator($directory);
				foreach ($iterator as $fileInfos) {
					if ($fileInfos->isDot() === false and $fileInfos->isFile() and @getimagesize($fileInfos->getPathname())) {
						// Créer la miniature RFM si manquante
						if (!file_exists(str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . strtolower($fileInfos->getFilename()))) {
							$this->makeThumb(
								$fileInfos->getPathname(),
								str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . strtolower($fileInfos->getFilename()),
								122
							);
						}
						// Obtenir les métadonnées EXIF de l'image
						$exif = exif_read_data($fileInfos->getPath() . '/' . $fileInfos->getFilename());
						$latitude = 'Donnée absente';
						$longitude = 'Donnée absente';
						// Vérifier si les données EXIF contiennent des informations de géolocalisation
						if (!empty($exif['GPSLatitude']) || !empty($exif['GPSLongitude'])) {
							// Coordonnées de latitude
							$latitude = $this->gps_decimal($exif['GPSLatitude'], $exif['GPSLatitudeRef']);

							// Coordonnées de longitude
							$longitude = $this->gps_decimal($exif['GPSLongitude'], $exif['GPSLongitudeRef']);
						}

						self::$pictures[str_replace('.', '', $fileInfos->getFilename())] = [
							//$this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'position', str_replace('.', '', $fileInfos->getFilename())]) + 1,
							$fileInfos->getFilename(),
							template::text('legend[' . $fileInfos->getFilename() . ']', [
								'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'legend', str_replace('.', '', $fileInfos->getFilename())])
							]),
							'Lat: ' . round($latitude, 5) . ' - Long:' . round($longitude, 5),

							'<a href="https://www.google.com/maps?q=' . $latitude . ',' . $longitude . '" data-lity><img src="module/geogallery/vendor/leaflet/images/marker-icon.png" class="marker"></a> ',
							//'<a href="https://www.openstreetmap.org/?mlat=' . $latitude . '&mlon=' . $longitude . '#map=8/' . $latitude . '/longitude" target="_blank"><img src="module/geogallery/vendor/leaflet/images/marker-icon.png" class="marker"></a> ', 
							'<a href="' . str_replace('source', 'thumb', $directory) . '/' . self::THUMBS_SEPARATOR . $fileInfos->getFilename() . '" rel="data-lity" data-lity=""><img src="' . str_replace('source', 'thumb', $directory) . '/' . $fileInfos->getFilename() . '"></a>',
						];
						self::$picturesId[] = str_replace('.', '', $fileInfos->getFilename());
					}
				}
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => sprintf(helper::translate('Galerie %s '), $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'name'])),
			'view' => 'edit'
		]);
	}

	/**
	 * Accueil (deux affichages en un pour éviter une url à rallonge)
	 */
	public function index()
	{

		// Mise à jour des données de module
		$this->update();

		// Initialise la feuille de style
		if (empty($this->getData(['page', $this->getUrl(0), 'css']))) {
			$this->initCss();
		}

		// Liste des galeries
		$locations = $this->getData(['module', $this->getUrl(0), 'content']);
		if (is_null($locations)) {
			// initialisation de la BDD
			$this->setData(['module', $this->getUrl(0), 'content', []]);
			// Construit les données pour le js
		} elseif (!empty($locations)) {
			$galleries = array_keys($this->getData(['module', $this->getUrl(0), 'content']));
			foreach ($galleries as $key => $gallery) {
				$directory = $this->getData(['module', $this->getUrl(0), 'content', $gallery, 'config', 'directory']);
				if (is_dir($directory)) {
					$iterator = new DirectoryIterator($directory);

					foreach ($iterator as $fileInfos) {

						if ($fileInfos->isDot() === false and $fileInfos->isFile() and @getimagesize($fileInfos->getPathname())) {

							// Créer la miniature si manquante
							if (!file_exists(str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename()))) {
								$this->makeThumb(
									$fileInfos->getPathname(),
									str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename()),
									self::THUMBS_WIDTH
								);
							}

							$exif = exif_read_data($fileInfos->getPath() . '/' . $fileInfos->getFilename());

							// Vérifier si les données EXIF contiennent des informations de géolocalisation
							if (!empty($exif['GPSLatitude']) || !empty($exif['GPSLongitude'])) {
								// Coordonnées de latitude
								$latitude = $this->gps_decimal($exif['GPSLatitude'], $exif['GPSLatitudeRef']);

								// Coordonnées de longitude
								$longitude = $this->gps_decimal($exif['GPSLongitude'], $exif['GPSLongitudeRef']);
								// Coordonnées
								self::$galleries[] = [
									'lat' => $latitude,
									'long' => $longitude,
									'img' => $fileInfos->getPath() . '/' . strtolower($fileInfos->getFilename()),
									'thumb' => str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename()),
									'label' => is_null($this->getData(['module', $this->getUrl(0), 'content', $gallery, 'legend', str_replace('.', '', $fileInfos->getFilename())]))
										? ''
										: $this->getData(['module', $this->getUrl(0), 'content', $gallery, 'legend', str_replace('.', '', $fileInfos->getFilename())])
								];
							}
						}
					}
				}
			}


			// Calculer le centre géographique
			$totalLat = 0;
			$totalLong = 0;
			$count = count(self::$galleries);

			foreach (self::$galleries as $coordinate) {
				$totalLat += $coordinate["lat"];
				$totalLong += $coordinate["long"];
			}

			$centerLat = $totalLat / $count;
			$centerLong = $totalLong / $count;

			// Calculer la distance maximale au centre pour déterminer le niveau de zoom
			$maxDistance = 0;
			foreach (self::$galleries as $coordinate) {
				if (
					is_numeric($centerLat)
					&& is_numeric($centerLong)
					&& $coordinate["lat"]
					&& $coordinate["long"]
				) {
					$distance = $this->haversineGreatCircleDistance($centerLat, $centerLong, $coordinate["lat"], $coordinate["long"]);
					if ($distance > $maxDistance) {
						$maxDistance = $distance;
					}
				}

			}

			$zoomLevel = $this->getZoomLevel($maxDistance);

			self::$galleriesCenter = array(
				'lat' => $centerLat,
				'long' => $centerLong,
				'zoom' => $zoomLevel
			);
		}

		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index',
			'vendor' => [
				'leaflet'
			],
		]);
	}


	private function gps_decimal($coordinate, $hemisphere)
	{
		// Extrait les degrés, minutes et secondes et force la conversion en flottant
		$degrees = count($coordinate) > 0 ? (float)$this->gps2Num($coordinate[0]) : 0.0;
		$minutes = count($coordinate) > 1 ? (float)$this->gps2Num($coordinate[1]) : 0.0;
		$seconds = count($coordinate) > 2 ? (float)$this->gps2Num($coordinate[2]) : 0.0;

		// Convertit les degrés, minutes et secondes en décimal (assure le type flottant)
		$decimal = $degrees + ($minutes / 60.0) + ($seconds / 3600.0);

		// Si l'hémisphère est au Sud ou à l'Ouest, les coordonnées sont négatives
		if ($hemisphere == 'S' || $hemisphere == 'W') {
			$decimal *= -1.0; // Multiplie par -1.0 pour assurer le type flottant
		}
	
		return $decimal;
	}


	private function gps2Num($coordPart)
	{
		$parts = explode('/', $coordPart);
		if (count($parts) <= 0) {
			return 0.0; // Retourne 0.0 explicitement en flottant
		}
		if (count($parts) == 1) {
			return (float)$parts[0]; // Convertit en flottant même s'il y a un seul élément
		}
		return floatval($parts[0]) / floatval($parts[1]); // Résultat de la division en flottant
		}


	// Fonction pour calculer la distance entre deux points géographiques
	private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
	{
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);

		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;

		$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
			cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
		return $angle * $earthRadius;
	}

	// Déterminer le niveau de zoom
// Cette fonction est une approximation pour le calcul du zoom
	private function getZoomLevel($maxDistance)
	{
		$maxZoom = 21; // Le zoom maximal pour Leaflet
		$earthCircumference = 40075; // La circonférence de la Terre en km

		for ($zoom = $maxZoom; $zoom >= 0; $zoom--) {
			if ($maxDistance < ($earthCircumference / pow(2, $zoom))) {
				return $zoom;
			}
		}
		return 0;
	}

	// Page de module vide
	private function initCss()
	{
		// Feuille de styles
		$cssContent =
			'#map {
				height: 500px;
				width: auto;
			}
			.leaflet-popup-content {
				text-align: center;
			}';
		$this->setData(['page', $this->getUrl(0), 'css', $cssContent]);
	}

}

class geogalleriesHelper extends helper
{

	/**
	 * Scan le contenu d'un dossier et de ses sous-dossiers
	 * @param string $dir Dossier à scanner
	 * @return array
	 */
	public static function scanDir($dir)
	{
		$dirContent = [];
		$iterator = new DirectoryIterator($dir);
		foreach ($iterator as $fileInfos) {
			if ($fileInfos->isDot() === false and $fileInfos->isDir()) {
				$dirContent[] = $dir . '/' . $fileInfos->getBasename();
				$dirContent = array_merge($dirContent, self::scanDir($dir . '/' . $fileInfos->getBasename()));
			}
		}
		return $dirContent;
	}
}