<?php

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author  Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 *
 */

class slider extends common
{

	public static $actions = [
		'config' => self::GROUP_MODERATOR,
		'update' => self::GROUP_MODERATOR,
		'theme' => self::GROUP_MODERATOR,
		'delete' => self::GROUP_MODERATOR,
		'dirs' => self::GROUP_MODERATOR,
		'index' => self::GROUP_VISITOR
	];

	const VERSION = '6.2';
	const REALNAME = 'Carrousel';
	const DELETE = true;
	const UPDATE = '0.0';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	public static $directories = [];

	public static $firstPictures = [];

	public static $galleries = [];

	public static $pictures = [];

	public static $pageList = [];

	//Visibilité des boutons de navigation
	public static $namespace = [
		'white-btns' => 'Blancs',
		'centered-btns' => 'Noirs',
		'transparent-btns' => 'Bandes invisibles',
		'large-btns' => 'Bandes grises',
	];

	// Pager
	public static $pager = [
		true => 'Puces visibles',
		false => 'Puces invisibles'
	];

	public static $auto = [
		true => 'Active',
		false => 'Inactive'
	];

	// Largeur
	public static $screenWidth = [
		640 => '640 pixels',
		720 => '720 pixels',
		768 => '768 pixels',
		800 => '800 pixels',
		854 => '854 pixels',
		1024 => '1024 pixels',
		1280 => '1280 pixels',
		1400 => '1400 pixels',
		1600 => '1600 pixels',
		1920 => '1920 pixels',
		0 => 'Largeur de l\'écran'
	];
	public static $selectedMaxwidth = 0;

	// Transition
	public static $speed = [
		'500' => '500 ms',
		'1000' => '1 s',
		'1500' => '1.5 s',
		'2000' => '2 s',
		'2500' => '2.5 s',
		'3000' => '3 s',
		'3500' => '3.5 s'
	];

	// Imeout
	public static $timeout = [
		'500' => '500 ms',
		'1000' => '1 s',
		'1500' => '1.5 s',
		'2000' => '2 s',
		'3000' => '3 s',
		'5000' => '5 s',
		'7000' => '7 s',
		'10000' => '10 s'
	];

	//Visibilité de la légende
	public static $visibilite_legende = [
		'survol' => 'Au survol',
		'toujours' => 'Toujours visible',
		'jamais' => 'Jamais visible'
	];

	//Position de la légende
	public static $position_legende = [
		'haut' => 'En haut',
		'bas' => 'En bas'
	];

	//Temps d'apparition légende et boutons
	public static $apparition = [
		'opacity 0.2s ease-in' => '0.2s',
		'opacity 0.5s ease-in' => '0.5s',
		'opacity 1s ease-in' => '1s',
		'opacity 2s ease-in' => '2s'
	];


	//Choix du tri
	public static $sort = [
		'asc' => 'Alphabétique naturel',
		'dsc' => 'Alphabétique naturel inverse',
		'rand' => 'Aléatoire',
		'none' => 'Par défaut, sans tri',
	];

	/**
	 * Mise à jour du dossier
	 */
	public function update()
	{
		// Soumission du formulaire
		if ($this->isPost()) {
			$this->setData([
				'module',
				$this->getUrl(0),
				'directory',
				$this->getInput('galleryUpdateDirectory', helper::FILTER_STRING_SHORT, true)
			]);
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Configuration de la galerie',
			'view' => 'update'
		]);
	}

	/**
	 * Configuration
	 */
	public function config()
	{
		// Initialise le module
		$this->init();

		// Liste des pages active à l'exclusion des barres latérales
		$pagesId = $this->getHierarchy(null, false, null);
		$excludeBar = $this->getHierarchy(null, false, true);
		$pagesId = array_diff_key($pagesId, $excludeBar);

		// Construit le tableau pour le select du formulaire
		foreach ($pagesId as $parentKey => $parentValue) {
			self::$pageList[$parentKey] = $this->getData(['page', $parentKey, 'title']);
			foreach ($parentValue as $childKey) {
				self::$pageList[$childKey] = $this->getData(['page', $childKey, 'title']);
			}
		}
		// Aucun choix
		self::$pageList = array_merge([0 => ''], self::$pageList);

		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {

			$inputs['legends'] = $this->getInput('legends', null);
			$inputs['uri'] = $this->getInput('sliderHref', null);

			// Supprime les points devant les extensions des clés à cause du système de BDD
			foreach ($inputs as $keyinputs => $valuesinputs) {
				foreach ($valuesinputs as $keyinput => $valueinput) {
					$datas[$keyinputs][str_replace('.', '', $keyinput)] = $valueinput;
				}
			}

			$this->setData([
				'module',
				$this->getUrl(0),
				[
					'directory' => $this->getData(['module', $this->getUrl(0), 'directory']),
					'theme' => $this->getData(['module', $this->getUrl(0), 'theme']),
					'legends' => $datas['legends'],
					'uri' => $datas['uri']
				]
			]);
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);

		}

		// Met en forme le tableau
		$directory = $this->getData(['module', $this->getUrl(0), 'directory']);
		if ($directory && is_dir($directory)) {
			$iterator = new DirectoryIterator($directory);
			foreach ($iterator as $fileInfos) {
				if ($fileInfos->isDot() === false and $fileInfos->isFile() and @getimagesize($fileInfos->getPathname())) {
					self::$pictures[$fileInfos->getFilename()] = [
						$fileInfos->getFilename(),
						template::text('legends[' . $fileInfos->getFilename() . ']', [
							'value' => empty($this->getData(['module', $this->getUrl(0), 'legends', str_replace('.', '', $fileInfos->getFilename())]))
								? ''
								: $this->getData(['module', $this->getUrl(0), 'legends', str_replace('.', '', $fileInfos->getFilename())])
						]),
						template::select('sliderHref[' . $fileInfos->getFilename() . ']', self::$pageList, [
							'selected' => empty($this->getData(['module', $this->getUrl(0), 'uri', str_replace('.', '', $fileInfos->getFilename())]))
								? ''
								: $this->getData(['module', $this->getUrl(0), 'uri', str_replace('.', '', $fileInfos->getFilename())])
						]),
						'<a href="' . str_replace('source', 'thumb', $directory) . '/' . self::THUMBS_SEPARATOR . $fileInfos->getFilename() . '" rel="data-lity" data-lity=""><img src="' . str_replace('source', 'thumb', $directory) . '/' . $fileInfos->getFilename() . '"></a>'
					];
				}
			}
			// Tri des images pour affichage de la liste dans la page d'édition
			switch ($this->getData(['module', $this->getUrl(0), 'theme', 'tri'])) {
				case 'dsc':
					krsort(self::$pictures, SORT_NATURAL | SORT_FLAG_CASE);
					break;
				case 'asc':
					ksort(self::$pictures, SORT_NATURAL | SORT_FLAG_CASE);
					break;
				case 'rand':
				case 'none':
				default:
					break;
			}
		}


		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Configuration du module',
			'view' => 'config'
		]);
	}

	/**
	 * Suppression
	 */
	public function delete()
	{
		// $url prend l'adresse sans le token
		// La galerie n'existe pas
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true ||
			$this->getData(['module', $this->getUrl(0), $this->getUrl(2)]) === null
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
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
	public function dirs()
	{
		// Valeurs en sortie
		$this->addOutput([
			'display' => self::DISPLAY_JSON,
			'content' => $this->scanSubDir(self::FILE_DIR . 'source')
		]);
	}


	/**
	 * Édition
	 */
	public function theme()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {

			// Equilibrer les durées
			$speed = $this->getInput('sliderThemespeed', helper::FILTER_INT);
			$timeout = $this->getInput('sliderThemeDiapoTime', helper::FILTER_INT);
			if ($speed >= $timeout) {
				// Valeurs en sortie
				$notification = 'La durée de transition doit inférieure à la durée de l`\'image fixe';
				$state = false;
			} else {

				$this->setData([
					'module',
					$this->getUrl(0),
					[
						'theme' => [
							'pager' => $this->getInput('sliderThemePager', helper::FILTER_BOOLEAN),
							'auto' => $this->getInput('sliderThemeAuto', helper::FILTER_BOOLEAN),
							'maxWidth' => $this->getInput('sliderThememaxWidth', helper::FILTER_INT),
							'speed' => $speed,
							'timeout' => $timeout,
							'namespace' => $this->getInput('sliderThemeNameSpace', helper::FILTER_STRING_SHORT),
							'sort' => $this->getInput('sliderThemeTri', helper::FILTER_STRING_SHORT),
						],
						'directory' => $this->getData(['module', $this->getUrl(0), 'directory']),
						'legends' => $this->getData(['module', $this->getUrl(0), 'legends']),
						'uri' => $this->getData(['module', $this->getUrl(0), 'uri']),
					]
				]);
				$notification = 'Modifications enregistrées';
				$state = true;
			}

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/theme',
				'notification' => $notification,
				'state' => $state
			]);
		}

		// Sélection largeur de l'écran
		self::$selectedMaxwidth = array_key_exists($this->getData(['module', $this->getUrl(0), 'theme', 'maxWidth']), self::$screenWidth)
			? $this->getData(['module', $this->getUrl(0), 'theme', 'maxWidth'])
			: 0;

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Thème',
			'view' => 'theme'
		]);
	}

	/**
	 * Fonction index() modifiée par rapport au module Gallery
	 */
	public function index()
	{

		$galleryId = $this->getUrl(0);
		$directory = $this->getData(['module', $galleryId, 'directory']);

		// Images de la galerie
		if ($directory && is_dir($directory)) {
			$iterator = new DirectoryIterator($directory);
			foreach ($iterator as $fileInfos) {
				if ($fileInfos->isDot() === false and $fileInfos->isFile() and @getimagesize($fileInfos->getPathname())) {
					self::$pictures[$directory . '/' . $fileInfos->getFilename()] = [
						'legend' => $this->getData(['module', $galleryId, 'legends', str_replace('.', '', $fileInfos->getFilename())]),
						'uri' => $this->getData(['module', $galleryId, 'uri', str_replace('.', '', $fileInfos->getFilename())]),
					];
					//self::$pictures['uri'][$directory . '/' . $fileInfos->getFilename()] = ;
				}
			}

			// Tri des images par ordre alphabétique, alphabétique inverse, aléatoire ou pas
			switch ($this->getData(['module', $galleryId, 'config', 'tri'])) {
				case 'SORT_DSC':
					krsort(self::$pictures, SORT_NATURAL | SORT_FLAG_CASE);
					break;
				case 'SORT_ASC':
					ksort(self::$pictures, SORT_NATURAL | SORT_FLAG_CASE);
					break;
				case 'RAND':
					break;
				case 'NONE':
					break;
				default:
					break;
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'vendor' => [
				'slider'
			],
			'view' => 'index'
		]);
	}

	/**
	 * Scan le contenu d'un dossier et de ses sous-dossiers
	 * @param string $dir Dossier à scanner
	 * @return array
	 */
	private function scanSubDir($dir)
	{
		$dirContent = [];
		$iterator = new DirectoryIterator($dir);
		foreach ($iterator as $fileInfos) {
			if ($fileInfos->isDot() === false and $fileInfos->isDir()) {
				$dirContent[] = $dir . '/' . $fileInfos->getBasename();
				$dirContent = array_merge($dirContent, $this->scanSubDir($dir . '/' . $fileInfos->getBasename()));
			}
		}
		return $dirContent;
	}

	private function init()
	{
		if (is_null($this->getData(['module', $this->getUrl(0), 'theme']))) {

			$this->setData([
				'module',
				$this->getUrl(0),
				[
					'theme' => [
						'pager' => true,
						'auto' => true,
						'maxWidth' => '1280',
						'speed' => 1000,
						'timeout' => 3000,
						'namespace' => 'centered-btns',
						'tri' => 'RAND',
					],
					'directory' => null,
					'legends' => [],
					'uri' => [],
				]
			]);
		}
	}

}