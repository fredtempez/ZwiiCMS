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

class gallery extends common
{


	const VERSION = '4.2';
	const REALNAME = 'Galerie';
	const DATADIRECTORY = self::DATA_DIR . 'gallery/';

	const SORT_ASC = 'SORT_ASC';
	const SORT_DSC = 'SORT_DSC';
	const SORT_HAND = 'SORT_HAND';


	public static $directories = [];

	public static $firstPictures = [];

	public static $galleries = [];

	public static $galleriesId = [];

	public static $pictures = [];

	public static $picturesId = [];

	public static $thumbs = [];

	public static $config = [];

	public static $actions = [
		'config' => self::GROUP_EDITOR,
		'delete' => self::GROUP_EDITOR,
		'dirs' => self::GROUP_EDITOR,
		'sortGalleries' => self::GROUP_EDITOR,
		'sortPictures' => self::GROUP_EDITOR,
		'edit' => self::GROUP_EDITOR,
		'add' => self::GROUP_EDITOR,
		'theme' => self::GROUP_EDITOR,
		'option' => self::GROUP_EDITOR,
		'index' => self::GROUP_VISITOR
	];

	public static $sort = [
		self::SORT_ASC => 'Alphabétique ',
		self::SORT_DSC => 'Alphabétique inverse',
		self::SORT_HAND => 'Manuel'
	];

	public static $galleryThemeFlexAlign = [
		'flex-start' => 'À gauche',
		'center' => 'Au centre',
		'flex-end' => 'À droite',
		'space-around' => 'Distribué avec marges',
		'space-between' => 'Distribué sans marge',
	];

	public static $galleryThemeAlign = [
		'left' => 'À gauche',
		'center' => 'Au centre',
		'right' => 'À droite'
	];

	public static $galleryThemeSizeWidth = [
		'9em' => 'Très petite',
		'12em' => 'Petite',
		'15em' => 'Moyenne',
		'18em' => 'Grande',
		'21em' => 'Très grande',
		'100%' => 'Proportionnelle'
	];

	public static $galleryThemeSizeHeight = [
		'9em' => 'Très petite',
		'12em' => 'Petite',
		'15em' => 'Moyenne',
		'18em' => 'Grande',
		'21em' => 'Très grande'
	];

	public static $galleryThemeLegendHeight = [
		'.125em' => 'Très petite',
		'.25em' => 'Petite',
		'.375em' => 'Moyenne',
		'.5em' => 'Grande',
		'.625em' => 'Très grande'
	];

	public static $galleryThemeBorder = [
		'0em' => 'Aucune',
		'.1em' => 'Très fine',
		'.3em' => 'Fine',
		'.5em' => 'Moyenne',
		'.7em' => 'Epaisse',
		'.9em' => 'Très épaisse'
	];

	public static $galleryThemeOpacity = [
		'1' => 'Aucun ',
		'.9' => 'Très Discrète',
		'.8' => 'Discrète',
		'.7' => 'Moyenne',
		'.6' => 'Forte',
		'.5' => 'Très forte'
	];

	public static $galleryThemeMargin = [
		'0em' => 'Aucune',
		'.1em' => 'Très petite',
		'.3em' => 'Petite',
		'.5em' => 'Moyenne',
		'.7em' => 'Grande',
		'.9em' => 'Très grande'
	];

	public static $galleryThemeRadius = [
		'0em' => 'Aucun',
		'.3em' => 'Très léger',
		'.6em' => 'Léger',
		'.9em' => 'Moyen',
		'1.2em' => 'Important',
		'1.5em' => 'Très important'
	];

	public static $galleryThemeShadows = [
		'0px' => 'Aucune',
		'1px 1px 5px' => 'Très légère',
		'1px 1px 10px' => 'Légère',
		'1px 1px 15px' => 'Moyenne',
		'1px 1px 25px' => 'Importante',
		'1px 1px 50px' => 'Très importante'
	];

	public static $galleryOptionBackPosition = [
		'none' => 'Masqué',
		'top' => 'Au-dessus',
		'both' => 'Au-dessus et en dessous',
		'bottom' => 'En dessous',
	];
	public static $galleryOptionBackAlign = [
		'left' => 'A gauche',
		'center' => 'Au centre',
		'right' => 'A droite',
	];

	public static $formOptionSelect = '';

	/**
	 * Mise à jour du module
	 * Appelée par les fonctions index et config
	 */
	private function update()
	{

		// Initialisation du module, créer les données si elles sont manquantes.
		$this->init();

		$versionData = $this->getData(['module', $this->getUrl(0), 'config', 'versionData']);
		// Mise à jour 3.1
		if (version_compare($versionData, '3.1', '<')) {
			if (is_dir(self::DATADIRECTORY . 'pages/')) {
				// Déplacer les données du dossier Pages
				$this->copyDir(self::DATADIRECTORY . 'pages/' . $this->getUrl(0), self::DATADIRECTORY . $this->getUrl(0));
				$this->deleteDir(self::DATADIRECTORY . 'pages/');
				$style = $this->getData(['module', $this->getUrl(0), 'theme', 'style']);
				$this->setData(['module', $this->getUrl(0), 'theme', 'style', str_replace('pages/', '', $style)]);
			}
			// Mettre à jour la version
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '3.1']);
		}
	}

	/**
	 * Initialisation séparée des éléments absents
	 * Thème
	 * Config
	 * Content
	 */
	private function init()
	{

		// Mise à jour d'une version inférieure, la gallery existe mais pas la variable content
		if (
			$this->getData(['module', $this->getUrl(0)]) &&
			$this->getData(['module', $this->getUrl(0), 'content']) === NULL
		) {

			// Changement de l'arborescence dans module.json
			$data = $this->getData(['module', $this->getUrl(0)]);
			$this->deleteData(['module', $this->getUrl(0)]);
			$this->setData(['module', $this->getUrl(0), 'content', $data]);

			// Effacer les fichiers CSS de l'ancienne version
			if (file_exists('module/gallery/view/index/index.css')) {
				unlink('module/gallery/view/index/index.css');
			}
			if (file_exists('module/gallery/view/gallery/gallery.css')) {
				unlink('module/gallery/view/gallery/gallery.css');
			}
			// Stockage des données du thème de la gallery existant
			if (is_array($this->getData(['theme', 'gallery']))) {
				$data = $this->getData(['theme', 'gallery']);
				$this->deleteData(['theme', 'gallery']);
				$this->setData(['module', $this->getUrl(0), 'theme', $data]);
				// Nom de la feuille de style
				$this->setData(['module', $this->getUrl(0), 'theme', 'style', self::DATADIRECTORY . $this->getUrl(0) . '/theme.css']);
			}
		}

		// Variable commune
		$fileCSS = self::DATADIRECTORY . $this->getUrl(0) . '/theme.css';

		// Check la présence des données de thème
		if ($this->getData(['module', $this->getUrl(0), 'theme']) === null) {
			require_once('module/gallery/ressource/defaultdata.php');
			$this->setData(['module', $this->getUrl(0), 'theme', theme::$defaultTheme]);
			// Nom de la feuille de style
			$this->setData(['module', $this->getUrl(0), 'theme', 'style', $fileCSS]);
		}

		// Check la présence de la feuille de style
		if (!file_exists(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css')) {
			// Dossier de l'instance
			if (!is_dir(self::DATADIRECTORY . $this->getUrl(0))) {
				mkdir(self::DATADIRECTORY . $this->getUrl(0), 0755, true);
			}
			// Générer la feuille de CSS
			$content = file_get_contents('module/gallery/ressource/vartheme.css');
			$themeCss = file_get_contents('module/gallery/ressource/theme.css');

			// Injection des variables
			$content = str_replace('#thumbAlign#', $this->getData(['module', $this->getUrl(0), 'theme', 'thumbAlign']), $content);
			$content = str_replace('#thumbWidth#', $this->getData(['module', $this->getUrl(0), 'theme', 'thumbWidth']), $content);
			$content = str_replace('#thumbHeight#', $this->getData(['module', $this->getUrl(0), 'theme', 'thumbHeight']), $content);
			$content = str_replace('#thumbMargin#', $this->getData(['module', $this->getUrl(0), 'theme', 'thumbMargin']), $content);
			$content = str_replace('#thumbBorder#', $this->getData(['module', $this->getUrl(0), 'theme', 'thumbBorder']), $content);
			$content = str_replace('#thumbBorderColor#', $this->getData(['module', $this->getUrl(0), 'theme', 'thumbBorderColor']), $content);
			$content = str_replace('#thumbOpacity#', $this->getData(['module', $this->getUrl(0), 'theme', 'thumbOpacity']), $content);
			$content = str_replace('#thumbShadows#', $this->getData(['module', $this->getUrl(0), 'theme', 'thumbShadows']), $content);
			$content = str_replace('#thumbShadowsColor#', $this->getData(['module', $this->getUrl(0), 'theme', 'thumbShadowsColor']), $content);
			$content = str_replace('#thumbRadius#', $this->getData(['module', $this->getUrl(0), 'theme', 'thumbRadius']), $content);
			$content = str_replace('#legendAlign#', $this->getData(['module', $this->getUrl(0), 'theme', 'legendAlign']), $content);
			$content = str_replace('#legendHeight#', $this->getData(['module', $this->getUrl(0), 'theme', 'legendHeight']), $content);
			$content = str_replace('#legendTextColor#', $this->getData(['module', $this->getUrl(0), 'theme', 'legendTextColor']), $content);
			$content = str_replace('#legendBgColor#', $this->getData(['module', $this->getUrl(0), 'theme', 'legendBgColor']), $content);

			// Ecriture de la feuille de style
			file_put_contents(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css', $content . $themeCss);
			// Nom de la feuille de style
			$this->setData(['module', $this->getUrl(0), 'theme', 'style', $fileCSS]);
		}

		// Check la présence de la config

		if (
			is_null($this->getData(['module', $this->getUrl(0), 'config', 'showUniqueGallery']))
			|| is_null($this->getData(['module', $this->getUrl(0), 'config', 'backPosition']))
			|| is_null($this->getData(['module', $this->getUrl(0), 'config', 'backAlign']))
			|| is_null($this->getData(['module', $this->getUrl(0), 'config', 'versionData']))
		) {
			require_once('module/gallery/ressource/defaultdata.php');
			$this->setData(['module', $this->getUrl(0), 'config', theme::$defaultData]);
		}

		// Contenu vide de la galerie
		if (!is_array($this->getData(['module', $this->getUrl(0), 'content']))) {
			$this->setData(['module', $this->getUrl(0), 'content', array()]);
		}
	}


	/**
	 * Tri de la liste des galeries
	 *
	 */
	public function sortGalleries()
	{
		if (isset($_POST['response'])) {
			$data = explode('&', $_POST['response']);
			$data = str_replace('galleryTable%5B%5D=', '', $data);
			for ($i = 0; $i < count($data); $i++) {
				$this->setData([
					'module', $this->getUrl(0),
					'content', $data[$i],
					[
						'config' => [
							'name' => $this->getData(['module', $this->getUrl(0), 'content', $data[$i], 'config', 'name']),
							'directory' => $this->getData(['module', $this->getUrl(0), 'content', $data[$i], 'config', 'directory']),
							'homePicture' => $this->getData(['module', $this->getUrl(0), 'content', $data[$i], 'config', 'homePicture']),
							'sort' => $this->getData(['module', $this->getUrl(0), 'content', $data[$i], 'config', 'sort']),
							'position' => $i,
							'fullScreen' => $this->getData(['module', $this->getUrl(0), 'content', $data[$i], 'config', 'fullScreen']),
							'showPageContent' => $this->getData(['module', $this->getUrl(0), 'content', $data[$i], 'config', 'showPageContent'])

						],
						'legend' => $this->getData(['module', $this->getUrl(0), 'content', $data[$i], 'legend']),
						'positions' => $this->getData(['module', $this->getUrl(0), 'content', $data[$i], 'positions'])
					]
				]);
			}
		}
	}

	/**
	 * Tri de la liste des images
	 *
	 */
	public function sortPictures()
	{
		if (isset($_POST['response'])) {
			$galleryName = $_POST['gallery'];
			$data = explode('&', $_POST['response']);
			$data = str_replace('galleryTable%5B%5D=', '', $data);
			// Sauvegarder
			$this->setData([
				'module', $this->getUrl(0),
				'content',
				$galleryName,
				[
					'config' => [
						'name' => $this->getData(['module', $this->getUrl(0), 'content', $galleryName, 'config', 'name']),
						'directory' => $this->getData(['module', $this->getUrl(0), 'content', $galleryName, 'config', 'directory']),
						'homePicture' => $this->getData(['module', $this->getUrl(0), 'content', $galleryName, 'config', 'homePicture']),
						'sort' => $this->getData(['module', $this->getUrl(0), 'content', $galleryName, 'config', 'sort']),
						'position' => $this->getData(['module', $this->getUrl(0), 'content', $galleryName, 'config', 'position']),
						'fullScreen' => $this->getData(['module', $this->getUrl(0), 'content', $galleryName, 'config', 'fullScreen'])

					],
					'legend' => $this->getData(['module', $this->getUrl(0), 'content', $galleryName, 'legend']),
					'positions' => array_flip($data)
				]
			]);
		}
	}


	/**
	 * Configuration
	 */
	public function config()
	{

		// Mise à jour des données de module
		$this->update();

		//Affichage de la galerie triée
		$g = $this->getData(['module', $this->getUrl(0), 'content']);
		$p = helper::arrayColumn(helper::arrayColumn($g, 'config'), 'position');
		asort($p, SORT_NUMERIC);
		$galleries = [];
		foreach ($p as $positionId => $item) {
			$galleries[$positionId] = $g[$positionId];
		}
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
					$gallery['config']['position'] + 1,
					$gallery['config']['name'],
					$gallery['config']['directory'],
					template::button('galleryConfigEdit' . $galleryId, [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $galleryId,
						'value' => template::ico('pencil'),
						'help' => 'Configuration de la galerie '
					]),
					template::button('galleryConfigDelete' . $galleryId, [
						'class' => 'galleryConfigDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $galleryId,
						'value' => template::ico('trash'),
						'help' => 'Supprimer cette galerie'
					])
				];
				// Tableau des id des galleries pour le drag and drop
				self::$galleriesId[] = $galleryId;
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Configuration des galeries'),
			'view' => 'config',
			'vendor' => [
				'tablednd'
			]
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
				$homePicture = '';
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
						// Miniatures
						$homePicture = strtolower($fileInfos->getFilename());
						break;
					}
				}
				// Le dossier de la galerie est vide
				if ($i > 0) {
					$this->setData([
						'module', $this->getUrl(0),
						'content',
						$galleryId,
						[
							'config' => [
								'name' => $this->getInput('galleryAddName'),
								'directory' => $this->getInput('galleryAddDirectory', helper::FILTER_STRING_SHORT, true),
								'homePicture' => $homePicture,
								'sort' => $this->getInput('galleryAddSort'),
								'position' => count($this->getData(['module', $this->getUrl(0), 'content'])) + 1,
								'fullScreen' => $this->getInput('galleryAddFullscreen', helper::FILTER_BOOLEAN),
								'showPageContent' => $this->getInput('galleryAddShowPageContent', helper::FILTER_BOOLEAN)
							],
							'legend' => [],
							'positions' => []
						]
					]);
					$success = true;
				} else {
					self::$inputNotices['galleryAddDirectory'] = "Le dossier sélectionné ne contient aucune image";
					$success = false;
				}
			}

			if ($success) {
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
					'notification' => helper::translate('Galerie ajoutée'),
					'state' => true
				]);
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'title' => helper::translate('Création d\'une galerie'),
					'view' => 'add'
				]);
			}

		} else {
			// Valeurs en sortie
			$this->addOutput([
				'title' => helper::translate('Création d\'une galerie'),
				'view' => 'add'
			]);
		}
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
			'content' => galleriesHelper::scanDir(self::FILE_DIR . 'source')
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
			foreach ((array) $this->getInput('legend', null) as $file => $legend) {
				// Image de couverture par défaut si non définie
				$homePicture = $file;
				$file = str_replace('.', '', $file);
				$legends[$file] = helper::filter($legend, helper::FILTER_STRING_SHORT);

			}
			// Photo de la page de garde de l'album définie dans form
			if (is_array($this->getInput('homePicture', null))) {
				// Extrait la couverture sélectionnée
				$homePicture = array_keys($this->getInput('homePicture', null))[0];
			}
			// Sauvegarder
			$this->setData([
				'module', $this->getUrl(0),
				'content', $this->getUrl(2),
				[
					'config' => [
						'homePicture' => $homePicture,
						// Données mises à jour par les options
						'name' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'name']),
						'directory' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'directory']),
						'sort' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'sort']),
						'position' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'position']),
						'fullScreen' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'fullScreen']),
						'showPageContent' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'showPageContent'])

					],
					'legend' => $legends,
					'positions' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'positions'])
				]
			]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $this->getUrl(2),
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
						self::$pictures[str_replace('.', '', $fileInfos->getFilename())] = [
							$this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'positions', str_replace('.', '', $fileInfos->getFilename())]) + 1,
							$fileInfos->getFilename(),
							template::checkbox('homePicture[' . $fileInfos->getFilename() . ']', true, '', [
								'checked' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'homePicture']) === $fileInfos->getFilename() ? true : false,
								'class' => 'homePicture'
							]),
							template::text('legend[' . $fileInfos->getFilename() . ']', [
								'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'legend', str_replace('.', '', $fileInfos->getFilename())])
							]),
							'<a href="' . str_replace('source', 'thumb', $directory) . '/' . self::THUMBS_SEPARATOR . $fileInfos->getFilename() . '" rel="data-lity" data-lity=""><img src="' . str_replace('source', 'thumb', $directory) . '/' . $fileInfos->getFilename() . '"></a>'
						];
						self::$picturesId[] = str_replace('.', '', $fileInfos->getFilename());
					}
				}
				// Tri des images
				switch ($this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'sort'])) {
					case self::SORT_HAND:
						$positions = $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'positions']);
						if ($positions) {
							foreach ($positions as $key => $value) {
								if (array_key_exists($key, self::$pictures)) {
									$tempPictures[$key] = self::$pictures[$key];
									$tempPicturesId[] = $key;
								}
							}
							// Images ayant été ajoutées dans le dossier mais non triées
							foreach (self::$pictures as $key => $value) {
								if (!array_key_exists($key, $tempPictures)) {
									$tempPictures[$key] = self::$pictures[$key];
									$tempPicturesId[] = $key;
								}
							}
							self::$pictures = $tempPictures;
							self::$picturesId = $tempPicturesId;
						}
						break;
					case self::SORT_ASC:
						ksort(self::$pictures, SORT_NATURAL);
						sort(self::$picturesId, SORT_NATURAL);
						break;
					case self::SORT_DSC:
						krsort(self::$pictures, SORT_NATURAL);
						rsort(self::$picturesId, SORT_NATURAL);
						break;
				}
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => sprintf(helper::translate('Configuration de la galerie %s '), $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'name'])),
				'view' => 'edit',
				'vendor' => [
					'tablednd'
				]
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
		// Une seule galerie, bifurquer sur celle-ci
		$gallery = $this->getData(['module', $this->getUrl(0), 'config', 'showUniqueGallery']) === true &&
			count($this->getData(['module', $this->getUrl(0), 'content'])) === 1
			? array_key_first($this->getData(['module', $this->getUrl(0), 'content']))
			: $this->getUrl(1);
		if ($gallery) {
			// La galerie n'existe pas
			if ($this->getData(['module', $this->getUrl(0), 'content', $gallery]) === null) {
				// Valeurs en sortie
				$this->addOutput([
					'access' => false
				]);
			}
			// La galerie existe
			else {
				// Images de la galerie
				$directory = $this->getData(['module', $this->getUrl(0), 'content', $gallery, 'config', 'directory']);
				// Définir les options
				self::$config['homePicture'] = $this->getData(['module', $this->getUrl(0), 'content', $gallery, 'config', 'homePicture']);
				self::$config['fullScreen'] = $this->getData(['module', $this->getUrl(0), 'content', $gallery, 'config', 'fullScreen']) === true ? 'fullScreen' : '';
				self::$config['backPositionTop'] = ($this->getData(['module', $this->getUrl(0), 'config', 'backPosition']) === 'top' ||
					$this->getData(['module', $this->getUrl(0), 'config', 'backPosition']) === 'both')
					? ''
					: 'displayNone';
				self::$config['backPositionBottom'] = ($this->getData(['module', $this->getUrl(0), 'config', 'backPosition']) === 'bottom' ||
					$this->getData(['module', $this->getUrl(0), 'config', 'backPosition']) === 'both')
					? ''
					: 'displayNone';
				self::$config['backAlign'] = 'textAlign' . ucfirst($this->getData(['module', $this->getUrl(0), 'config', 'backAlign']));

				// Annulation de l'apparition des boutons si la galerie est unique
				self::$config['backPositionTop'] = ($this->getData(['module', $this->getUrl(0), 'config', 'showUniqueGallery']) === true
					&& count($this->getData(['module', $this->getUrl(0), 'content'])) === 1)
					? 'displayNone'
					: self::$config['backPositionTop'];
				self::$config['backPositionBottom'] = ($this->getData(['module', $this->getUrl(0), 'config', 'showUniqueGallery']) === true
					&& count($this->getData(['module', $this->getUrl(0), 'content'])) === 1)
					? 'displayNone'
					: self::$config['backPositionBottom'];

				if (is_dir($directory)) {
					$iterator = new DirectoryIterator($directory);
					foreach ($iterator as $fileInfos) {
						if ($fileInfos->isDot() === false and $fileInfos->isFile() and @getimagesize($fileInfos->getPathname())) {
							self::$pictures[$directory . '/' . $fileInfos->getFilename()] = $this->getData(['module', $this->getUrl(0), 'content', $gallery, 'legend', str_replace('.', '', $fileInfos->getFilename())]);
							$picturesSort[$directory . '/' . $fileInfos->getFilename()] = $this->getData(['module', $this->getUrl(0), 'content', $gallery, 'positions', str_replace('.', '', $fileInfos->getFilename())]);
							// Créer la miniature si manquante
							if (!file_exists(str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename()))) {
								$this->makeThumb(
									$fileInfos->getPathname(),
									str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename()),
									self::THUMBS_WIDTH
								);
							}
							// Définir la Miniature
							self::$thumbs[$directory . '/' . $fileInfos->getFilename()] = file_exists(str_replace('source', 'thumb', $directory) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename()))
								? str_replace('source', 'thumb', $directory) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename())
								: str_replace('source', 'thumb', $directory) . '/' . strtolower($fileInfos->getFilename());

						}
					}
					// Tri des images par ordre alphabétique
					switch ($this->getData(['module', $this->getUrl(0), 'content', $gallery, 'config', 'sort'])) {
						case self::SORT_HAND:
							asort($picturesSort);
							if ($picturesSort) {
								foreach ($picturesSort as $name => $position) {
									$temp[$name] = self::$pictures[$name];
								}
								self::$pictures = $temp;
								break;
							}
						case self::SORT_DSC:
							krsort(self::$pictures, SORT_NATURAL);
							break;
						case self::SORT_ASC:
						default:
							ksort(self::$pictures, SORT_NATURAL);
							break;
					}
				}
				// Affichage du template
				if (self::$pictures) {
					// Valeurs en sortie
					$this->addOutput([
						'showBarEditButton' => true,
						'title' => $this->getData(['module', $this->getUrl(0), 'content', $gallery, 'config', 'name']),
						'view' => 'gallery',
						'style' => file_exists($this->getData(['module', $this->getUrl(0), 'theme', 'style']))
						? $this->getData(['module', $this->getUrl(0), 'theme', 'style'])
						: '',
						'showPageContent' => $this->getData(['module', $this->getUrl(0), 'content', $gallery, 'config', 'showPageContent']),
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
			$g = $this->getData(['module', $this->getUrl(0), 'content']);
			$p = helper::arrayColumn(helper::arrayColumn($g, 'config'), 'position');
			asort($p, SORT_NUMERIC);
			$galleries = [];
			foreach ($p as $positionId => $item) {
				$galleries[$positionId] = $g[$positionId];
			}
			// Construire le tableau
			foreach ((array) $galleries as $galleryId => $gallery) {
				if (is_dir($gallery['config']['directory'])) {
					$iterator = new DirectoryIterator($gallery['config']['directory']);
					foreach ($iterator as $fileInfos) {
						if ($fileInfos->isDot() === false and $fileInfos->isFile() and @getimagesize($fileInfos->getPathname())) {

							self::$galleries[$galleryId] = $gallery;
							// L'image de couverture est-elle supprimée ?
							if (file_exists($gallery['config']['directory'] . '/' . $gallery['config']['homePicture'])) {
								// Créer la miniature si manquante
								if (!file_exists(str_replace('source', 'thumb', $gallery['config']['directory']) . '/' . self::THUMBS_SEPARATOR . strtolower($gallery['config']['homePicture']))) {
									$this->makeThumb(
										$gallery['config']['directory'] . '/' . str_replace(self::THUMBS_SEPARATOR, '', $gallery['config']['homePicture']),
										str_replace('source', 'thumb', $gallery['config']['directory']) . '/' . self::THUMBS_SEPARATOR . strtolower($gallery['config']['homePicture']),
										self::THUMBS_WIDTH
									);
								}
								// Définir l'image de couverture
								self::$firstPictures[$galleryId] = file_exists(str_replace('source', 'thumb', $gallery['config']['directory']) . '/' . self::THUMBS_SEPARATOR . strtolower($gallery['config']['homePicture']))
									? str_replace('source', 'thumb', $gallery['config']['directory']) . '/' . self::THUMBS_SEPARATOR . strtolower($gallery['config']['homePicture'])
									: str_replace('source', 'thumb', $gallery['config']['directory']) . '/' . strtolower($gallery['config']['homePicture']);
							} else {
								// homePicture contient une image invalide, supprimée ou déplacée
								// Définir l'image de couverture, première image disponible
								$this->makeThumb(
									$fileInfos->getPath() . '/' . $fileInfos->getFilename(),
									str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename()),
									self::THUMBS_WIDTH
								);
								self::$firstPictures[$galleryId] = file_exists(str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename()))
									? str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . self::THUMBS_SEPARATOR . strtolower($fileInfos->getFilename())
									: str_replace('source', 'thumb', $fileInfos->getPath()) . '/' . strtolower($fileInfos->getFilename());
							}
						}
						continue(1);
					}
				}
			}
			// Valeurs en sortie
			$this->addOutput([
				'showBarEditButton' => true,
				'showPageContent' => true,
				'view' => 'index',
				'style' => file_exists($this->getData(['module', $this->getUrl(0), 'theme', 'style']))
				? $this->getData(['module', $this->getUrl(0), 'theme', 'style'])
				: ''
			]);
		}
	}

	/**
	 * Thème de la galerie
	 */
	public function theme()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Dossier de l'instance
			if (!is_dir(self::DATADIRECTORY . $this->getUrl(0))) {
				mkdir(self::DATADIRECTORY . $this->getUrl(0), 0755, true);
			}
			$this->setData([
				'module', $this->getUrl(0),
				'theme',
				[
					'thumbAlign' => $this->getinput('galleryThemeThumbAlign', helper::FILTER_STRING_SHORT),
					'thumbWidth' => $this->getinput('galleryThemeThumbWidth', helper::FILTER_STRING_SHORT),
					'thumbHeight' => $this->getinput('galleryThemeThumbHeight', helper::FILTER_STRING_SHORT),
					'thumbMargin' => $this->getinput('galleryThemeThumbMargin', helper::FILTER_STRING_SHORT),
					'thumbBorder' => $this->getinput('galleryThemeThumbBorder', helper::FILTER_STRING_SHORT),
					'thumbBorderColor' => $this->getinput('galleryThemeThumbBorderColor', helper::FILTER_STRING_SHORT),
					'thumbOpacity' => $this->getinput('galleryThemeThumbOpacity', helper::FILTER_STRING_SHORT),
					'thumbShadows' => $this->getinput('galleryThemeThumbShadows', helper::FILTER_STRING_SHORT),
					'thumbShadowsColor' => $this->getinput('galleryThemeThumbShadowsColor', helper::FILTER_STRING_SHORT),
					'thumbRadius' => $this->getinput('galleryThemeThumbRadius', helper::FILTER_STRING_SHORT),
					'legendHeight' => $this->getinput('galleryThemeLegendHeight', helper::FILTER_STRING_SHORT),
					'legendAlign' => $this->getinput('galleryThemeLegendAlign', helper::FILTER_STRING_SHORT),
					'legendTextColor' => $this->getinput('galleryThemeLegendTextColor', helper::FILTER_STRING_SHORT),
					'legendBgColor' => $this->getinput('galleryThemeLegendBgColor', helper::FILTER_STRING_SHORT),
					'showUniqueGallery' => $this->getinput('galleryThemeShowUniqueGallery', helper::FILTER_BOOLEAN),
					'style' => self::DATADIRECTORY . $this->getUrl(0) . '/theme.css',
				]
			]);
			// Création des fichiers CSS
			$content = file_get_contents('module/gallery/ressource/vartheme.css');
			$themeCss = file_get_contents('module/gallery/ressource/theme.css');
			// Injection des variables
			$content = str_replace('#thumbAlign#', $this->getinput('galleryThemeThumbAlign'), $content);
			$content = str_replace('#thumbWidth#', $this->getinput('galleryThemeThumbWidth'), $content);
			$content = str_replace('#thumbHeight#', $this->getinput('galleryThemeThumbHeight'), $content);
			$content = str_replace('#thumbMargin#', $this->getinput('galleryThemeThumbMargin'), $content);
			$content = str_replace('#thumbBorder#', $this->getinput('galleryThemeThumbBorder'), $content);
			$content = str_replace('#thumbBorderColor#', $this->getinput('galleryThemeThumbBorderColor'), $content);
			$content = str_replace('#thumbOpacity#', $this->getinput('galleryThemeThumbOpacity'), $content);
			$content = str_replace('#thumbShadows#', $this->getinput('galleryThemeThumbShadows'), $content);
			$content = str_replace('#thumbShadowsColor#', $this->getinput('galleryThemeThumbShadowsColor'), $content);
			$content = str_replace('#thumbRadius#', $this->getinput('galleryThemeThumbRadius'), $content);
			$content = str_replace('#legendAlign#', $this->getinput('galleryThemeLegendAlign'), $content);
			$content = str_replace('#legendHeight#', $this->getinput('galleryThemeLegendHeight'), $content);
			$content = str_replace('#legendTextColor#', $this->getinput('galleryThemeLegendTextColor'), $content);
			$content = str_replace('#legendBgColor#', $this->getinput('galleryThemeLegendBgColor'), $content);
			$success = is_int(file_put_contents(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css', $content . $themeCss));
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl() . '/theme',
				'notification' => $success ? 'Modifications enregistrées' : 'Modifications non enregistrées !',
				'state' => $success
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Thème'),
			'view' => 'theme',
			'vendor' => [
				'tinycolorpicker'
			]
		]);
	}

	/**
	 * Option de configuration de la galerie
	 */
	public function option()
	{
		/**
		 * Options applicables à toutes les galeries du module
		 */
		if ($this->getUrl(2) === 'galleries') {
			// Soumission du formulaire
			if (
				$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
				$this->isPost()
			) {

				// Sauver la configuration de la galerie
				$this->setData([
					'module', $this->getUrl(0),
					'config',
					[
						'showUniqueGallery' => $this->getinput('galleriesOptionShowUniqueGallery', helper::FILTER_BOOLEAN),
						'backPosition' => $this->getinput('galleryOptionBackPosition'),
						'backAlign' => $this->getinput('galleryOptionBackAlign'),
						'versionData' => $this->getData(['module', $this->getUrl(0), 'config', 'versionData']),
					]
				]);

				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl() . '/option',
					'notification' => 'Modifications enregistrées',
					'state' => true
				]);
			}
			// Routage du formulaire
			self::$formOptionSelect = 'galleries';
			// Valeurs en sortie
			$this->addOutput([
				'title' => helper::translate('Options de configuration'),
				'view' => 'option'
			]);
			/**
			 * Enregistre les options de configuration de la galerie d'images sélectionnée
			 */
		} elseif ($this->getUrl(2) === 'gallery') {
			// Soumission du formulaire
			if (
				$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
				$this->isPost()
			) {

				// Le nom de la galerie est vide c'est le nom dans l'url qui est pris en compte
				$galleryId = !empty($this->getInput('galleryEditName')) ? $this->getInput('galleryEditName', helper::FILTER_ID, true) : $this->getUrl(3);

				// Sauvegarde des Valeurs non affectées par ce formulaire
				$homePicture = $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(3), 'config', 'homePicture']);
				$position = $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(3), 'config', 'position']);
				$legend = $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(3), 'legend']);
				$positions = $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(3), 'positions']);

				// Le nom de la galerie n'est pas celui dans la BDD
				if ($galleryId !== $this->getUrl(3)) {
					// Incrémente le nouvel id de la galerie
					$galleryId = helper::increment($galleryId, $this->getData(['module', $this->getUrl(0), 'content']));
					// Supprime l'ancienne galerie
					$this->deleteData(['module', $this->getUrl(0), 'content', $this->getUrl(3)]);
				}
				// Sauvegarder
				if ($this->getInput('galleryEditName')) {
					$this->setData([
						'module', $this->getUrl(0),
						'content',
						$galleryId,
						[
							'config' => [
								'name' => $this->getInput('galleryEditName', helper::FILTER_STRING_SHORT, true),
								'directory' => $this->getInput('galleryEditDirectory', helper::FILTER_STRING_SHORT, true),
								'sort' => $this->getInput('galleryEditSort'),
								'fullScreen' => $this->getInput('galleryEditFullscreen', helper::FILTER_BOOLEAN),
								'showPageContent' => $this->getInput('galleryEditShowPageContent', helper::FILTER_BOOLEAN),
								// Données lues à partir du formulaire edit de la galerie avec la liste des images
								'homePicture' => $homePicture,
								'position' => $position

							],
							'legend' => $legend,
							'positions' => $positions
						]
					]);
				}
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $galleryId,
					'notification' => helper::translate('Modifications enregistrées'),
					'state' => true
				]);
			}
			// Routage du formulaire
			self::$formOptionSelect = 'gallery';
			// Valeurs en sortie
			$this->addOutput([
				'title' => sprintf(helper::translate('Options de la galerie %s'), ucfirst($this->getUrl(3))),
				'view' => 'option'
			]);
		} else {
			// Valeurs en sortie par défaut
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Action interdite')
			]);
		}
	}

}

class galleriesHelper extends helper
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