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

class geolocation extends common
{


	const VERSION = '1.3';
	const REALNAME = 'Géolocalisation';
	const DATADIRECTORY = self::DATA_DIR . 'geolocation/';

	const SORT_ASC = 'SORT_ASC';
	const SORT_DSC = 'SORT_DSC';
	const SORT_HAND = 'SORT_HAND';


	public static $locations = [];
	public static $locationsId = [];
	public static $locationsCenter = [];

	public static $actions = [
		'config' => self::GROUP_EDITOR,
		'delete' => self::GROUP_EDITOR,
		'dirs' => self::GROUP_EDITOR,
		'add' => self::GROUP_EDITOR,
		'edit' => self::GROUP_EDITOR,
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

		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true
		) {
			$locations = $this->getData(['module', $this->getUrl(0), 'content']);
			if (is_null($locations)) {
				$this->setData(['module', $this->getUrl(0), 'content', []]);
			} elseif (!empty($locations)) {
				foreach ($locations as $locationId => $locationData) {
					self::$locations[] = [
						$locationData['name'],
						$locationData['lat'],
						$locationData['long'],
						template::button('locationConfigEdit' . $locationId, [
							'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $locationId,
							'value' => template::ico('pencil'),
							'help' => 'Configuration'
						]),
						template::button('galleryConfigDelete' . $locationId, [
							'class' => 'galleryConfigDelete buttonRed',
							'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $locationId,
							'value' => template::ico('trash'),
							'help' => 'Supprimer'
						])

					];
				}
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'title' => helper::translate('Configuration'),
			'view' => 'config'
		]);
	}

	/**
	 * Ajout d'une localisation 
	 */
	public function add()
	{
		// Soumission du formulaire d'ajout d'une galerie
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			if ($this->getInput('locationAddName', null, true)) {

				$locationId = helper::increment($this->getInput('locationAddName', helper::FILTER_ID, true), (array) $this->getData(['module', $this->getUrl(0), 'content']));

				// Description
				$description = $this->getInput('locationAddDescription', helper::FILTER_STRING_SHORT, true);
				// Protége les slashs pour la génération du JSON
				$description = addslashes($description);
				// Supprime les caractères de contrôle
				$description = preg_replace('/[\x00-\x1F\x7F]/u', '', $description);

				// Coordonnées
				// makeFloat assure la compatibilité avec les versions de Zwii dont le helper n'a pas été actualisé
				$lat = $this->makeFloat($this->getInput('locationAddLat', null, true));
				$long = $this->makeFloat($this->getInput('locationAddLong', null, true));

				// Enregistrement
				$this->setData([
					'module',
					$this->getUrl(0),
					'content',
					$locationId,
					[
						'name' => $this->getInput('locationAddName', null, true),
						'description' => $description,
						'lat' => $lat,
						'long' => $long,
					]
				]);
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
			'title' => helper::translate('Nouvelle localisation'),
			'view' => 'add',
			'vendor' => [
				'tinymce'
			],
		]);

	}

	/**
	 * Ajout d'une localisation 
	 */
	public function edit()
	{
		// Soumission du formulaire d'ajout d'une galerie
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Description
			$description = $this->getInput('locationEditDescription', helper::FILTER_STRING_SHORT, true);
			// Protége les slashs pour la génération du JSON
			$description = addslashes($description);
			// Supprime les caractères de contrôle
			$description = preg_replace('/[\x00-\x1F\x7F]/u', '', $description);

			// Coordonnées
			//makeFloat assure la compatibilité avec les versions de Zwii dont le helper n'a pas été actualisé
			$lat = $this->makeFloat($this->getInput('locationEditLat', null, true));
			$long = $this->makeFloat($this->getInput('locationEditLong', null, true));

			$this->setData([
				'module',
				$this->getUrl(0),
				'content',
				$this->getUrl(2),
				[
					'name' => $this->getInput('locationEditName', null, true),
					'description' => $description,
					'lat' => $lat,
					'long' => $long,
				]
			]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Nouvel localisation créé'),
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Edition'),
			'view' => 'edit',
			'vendor' => [
				'tinymce'
			],
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
				'notification' => helper::translate('Evenement effacé'),
				'state' => true
			]);
		}
	}


	/**
	 * Accueil (deux affichages en un pour éviter une url à rallonge)
	 */
	public function index()
	{

		// Mise à jour des données de module
		$this->update();

		$locations = $this->getData(['module', $this->getUrl(0), 'content']);

		// Initialise la feuille de style
		if (empty($this->getData(['page', $this->getUrl(0), 'css']))) {
			$this->initCss();
		}

		// Affichage du template si les données sont disponibles
		if (is_null($locations)) {
			$this->setData(['module', $this->getUrl(0), 'content', []]);
			// Initialise la feuille de style
		} elseif (!empty($locations)) {
			// Lecture des données
			foreach ($locations as $locationsId => $datas) {
				self::$locations[] = $datas;
			}

			// Calcul du point central 
			// Calculer le centre géographique
			$totalLat = 0;
			$totalLong = 0;
			$count = count(self::$locations);

			foreach (self::$locations as $coordinate) {
				$totalLat += $coordinate["lat"];
				$totalLong += $coordinate["long"];
			}

			$centerLat = $totalLat / $count;
			$centerLong = $totalLong / $count;

			// Calculer la distance maximale au centre pour déterminer le niveau de zoom
			$maxDistance = 0;
			foreach (self::$locations as $coordinate) {
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

				$zoomLevel = $this->getZoomLevel($maxDistance);

				self::$locationsCenter = array(
					'lat' => $centerLat,
					'long' => $centerLong,
					'zoom' => $zoomLevel
				);
			}
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

	// compatibilité avec les versio de Zwii < 13.3.05 dont le filtre FLOAT ne fonctionne pas
	private function makeFloat($coordinate)
	{

		$coordinate = str_replace(',', '.', $coordinate);  // Remplacer les virgules par des points
		$coordinate = filter_var($coordinate, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$coordinate = (float) $coordinate;
		return $coordinate;
	}

	// Fonction pour convertir les coordonnées GPS au format décimal
	private function gps_decimal($coordinate, $hemisphere)
	{
		// Extrait les degrés, minutes et secondes
		$degrees = count($coordinate) > 0 ? $this->gps2Num($coordinate[0]) : 0;
		$minutes = count($coordinate) > 1 ? $this->gps2Num($coordinate[1]) : 0;
		$seconds = count($coordinate) > 2 ? $this->gps2Num($coordinate[2]) : 0;

		// Convertit les degrés, minutes et secondes en décimal
		$decimal = $degrees + ($minutes / 60) + ($seconds / 3600);

		// Si l'hémisphère est au Sud ou à l'Ouest, les coordonnées sont négatives
		$decimal *= ($hemisphere == 'S' || $hemisphere == 'W') ? -1 : 1;

		return $decimal;
	}

	// Fonction pour convertir les coordonnées GPS en nombre
	private function gps2Num($coordPart)
	{
		$parts = explode('/', $coordPart);
		if (count($parts) <= 0)
			return 0;
		if (count($parts) == 1)
			return $parts[0];
		return floatval($parts[0]) / floatval($parts[1]);
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
