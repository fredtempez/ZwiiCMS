<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class news extends common
{

	const VERSION = '5.3';
	const REALNAME = 'News';
	const DATADIRECTORY = self::DATA_DIR . 'news/';

	public static $actions = [
		'add' => self::GROUP_EDITOR,
		'config' => self::GROUP_EDITOR,
		// Edition des news
		'option' => self::GROUP_EDITOR,
		// paramétrage des news
		'delete' => self::GROUP_EDITOR,
		'edit' => self::GROUP_EDITOR,
		'index' => self::GROUP_VISITOR,
		'rss' => self::GROUP_VISITOR
	];

	public static $news = [];

	public static $comments = [];

	public static $pages;

	public static $states = [
		false => 'Brouillon',
		true => 'Publié'
	];

	public static $users = [];

	// Nombre d'objets par page
	public static $itemsList = [
		4 => '4 articles',
		8 => '8 articles',
		12 => '12 articles',
		16 => '16 articles',
		22 => '22 articles'
	];
	// Nombre de colone par page
	public static $columns = [
		12 => '1 colonne',
		6 => '2 colonnes',
		4 => '3 colonnes',
		3 => '4 colonnes'
	];
	public static $nbrCol = 1;

	public static $height = [
		-1 => 'Article complet',
		1000 => '1000 caractères',
		800 => '800 caractères',
		600 => '600 caractères',
		400 => '400 caractères',
		200 => '200 caractères',
	];

	public static $borderWidth = [
		0 => 'Aucune',
		'0.1em' => 'Très fine',
		'0.15em' => 'Fine',
		'0.2em' => 'Très petite',
		'0.25em' => 'Petite',
	];

	public static $borderStyle = [
		'none' => 'Aucune',
		'solid' => 'Bordure'
	];

	// Signature de l'article
	public static $articleSignature = '';
	// Nombre d'articles dans la page de config:
	public static $itemsperPage = 8;

	public static $dateFormats = [
		'%d %B %Y' => 'DD MMMM YYYY',
		'%d/%m/%Y' => 'DD/MM/YYYY',
		'%m/%d/%Y' => 'MM/DD/YYYY',
		'%d/%m/%y' => 'DD/MM/YY',
		'%m/%d/%y' => 'MM/DD/YY',
		'%d-%m-%Y' => 'DD-MM-YYYY',
		'%m-%d-%Y' => 'MM-DD-YYYY',
		'%d-%m-%y' => 'DD-MM-YY',
		'%m-%d-%y' => 'MM-DD-YY',
	];
	public static $timeFormats = [
		'%H:%M' => 'HH:MM',
		'%I:%M %p' => "HH:MM tt",
	];

	public static $timeFormat = '';
	public static $dateFormat = '';

	/**
	 * Flux RSS
	 */
	public function rss()
	{
		// Inclure les classes
		include_once 'module/news/vendor/FeedWriter/Item.php';
		include_once 'module/news/vendor/FeedWriter/Feed.php';
		include_once 'module/news/vendor/FeedWriter/RSS2.php';
		include_once 'module/news/vendor/FeedWriter/InvalidOperationException.php';

		date_default_timezone_set('UTC');

		$feeds = new \FeedWriter\RSS2();

		// En-tête
		$feeds->setTitle($this->getData(['page', $this->getUrl(0), 'title']));
		$feeds->setLink(helper::baseUrl() . $this->getUrl(0));
		$feeds->setDescription($this->getData(['page', $this->getUrl(0), 'metaDescription']));
		$feeds->setChannelElement('language', 'fr-FR');
		$feeds->setDate(date('r', time()));
		$feeds->addGenerator();
		// Corps des articles
		$newsIdsPublishedOns = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC');
		$newsIdsStates = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'state', 'SORT_DESC');
		foreach ($newsIdsPublishedOns as $newsId => $newsPublishedOn) {
			if ($newsPublishedOn <= time() and $newsIdsStates[$newsId]) {
				$newsArticle = $feeds->createNewItem();
				$author = $this->signature($this->getData(['module', $this->getUrl(0), 'posts', $newsId, 'userId']));
				$newsArticle->addElementArray([
					'title' => $this->getData(['module', $this->getUrl(0), 'posts', $newsId, 'title']),
					'link' => helper::baseUrl() . $this->getUrl(0) . '/' . $newsId . '#' . $newsId,
					'description' => $this->getData(['module', $this->getUrl(0), 'posts', $newsId, 'content'])
				]);
				$newsArticle->setAuthor($author, 'no@mail.com');
				$newsArticle->setId(helper::baseUrl() . $this->getUrl(0) . '/' . $newsId . '#' . $newsId);
				$newsArticle->setDate(date('r', $this->getData(['module', $this->getUrl(0), 'posts', $newsId, 'publishedOn'])));
				$feeds->addItem($newsArticle);
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'display' => self::DISPLAY_RSS,
			'content' => $feeds->generateFeed(),
			'view' => 'rss'
		]);
	}

	/**
	 * Ajout d'un article
	 */
	public function add()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Crée la news
			$newsId = helper::increment($this->getInput('newsAddTitle', helper::FILTER_ID), (array) $this->getData(['module', $this->getUrl(0), 'posts']));
			$publishedOn = $this->getInput('newsAddPublishedOn', helper::FILTER_DATETIME, true);
			$publishedOff = $this->getInput('newsAddPublishedOff') ? $this->getInput('newsAddPublishedOff', helper::FILTER_DATETIME) : '';
			$this->setData([
				'module', $this->getUrl(0),
				'posts',
				$newsId,
				[
					'content' => $this->getInput('newsAddContent', null),
					'publishedOn' => $publishedOn,
					'publishedOff' => $publishedOff,
					'state' => $this->getInput('newsAddState', helper::FILTER_BOOLEAN),
					'title' => $this->getInput('newsAddTitle', helper::FILTER_STRING_SHORT, true),
					'userId' => $this->getInput('newsAddUserId', helper::FILTER_ID, true)
				]
			]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Nouvel article créé'),
				'state' => true
			]);
		}
		// Liste des utilisateurs
		self::$users = helper::arrayColumn($this->getData(['user']), 'firstname');
		ksort(self::$users);
		foreach (self::$users as $userId => &$userFirstname) {
			$userFirstname = $userFirstname . ' ' . $this->getData(['user', $userId, 'lastname']);
		}
		unset($userFirstname);
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Rédiger un article'),
			'vendor' => [
				'flatpickr',
				'tinymce'
			],
			'view' => 'add'
		]);
	}

	/**
	 * Configuration
	 */
	public function config()
	{

		// Mise à jour des données de module
		$this->update();

		// Ids des news par ordre de publication
		$newsIds = array_keys(helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC'));
		// Pagination fixe
		$pagination = helper::pagination($newsIds, $this->getUrl(), self::$itemsperPage);
		// Liste des pages
		self::$pages = $pagination['pages'];
		// Format de temps
		self::$dateFormat = $this->getData(['module', $this->getUrl(0), 'config', 'dateFormat']);
		self::$timeFormat = $this->getData(['module', $this->getUrl(0), 'config', 'timeFormat']);
		// News en fonction de la pagination
		for ($i = $pagination['first']; $i < $pagination['last']; $i++) {
			// Met en forme le tableau
			$dateOn = helper::dateUTF8(self::$dateFormat, $this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'publishedOn']), self::$siteContent) . ' - ' . helper::dateUTF8(self::$timeFormat, $this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'publishedOn']), self::$siteContent);
			if ($this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'publishedOff'])) {
				$dateOff = helper::dateUTF8(self::$dateFormat, $this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'publishedOff']), self::$siteContent) . ' - ' . helper::dateUTF8(self::$timeFormat, $this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'publishedOff']), self::$siteContent);
			} else {
				$dateOff = helper::translate('Permanent');
			}
			self::$news[] = [
				$this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'title']),
				$dateOn,
				$dateOff,
				helper::translate(self::$states[$this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'state'])]),
				template::button('newsConfigEdit' . $newsIds[$i], [
					'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $newsIds[$i],
					'value' => template::ico('pencil')
				]),
				template::button('newsConfigDelete' . $newsIds[$i], [
					'class' => 'newsConfigDelete buttonRed',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $newsIds[$i],
					'value' => template::ico('trash')
				])
			];
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Configuration du module'),
			'view' => 'config',
			'vendor' => [
				'tinycolorpicker'
			]
		]);
	}

	public function option()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {

			// Générer la feuille de CSS
			$style = '.newsFrame {';
			$style .= 'border:' . $this->getInput('newsThemeBorderStyle', helper::FILTER_STRING_SHORT) . ' ' . $this->getInput('newsThemeBorderColor') . ' ' . $this->getInput('newsThemeBorderWidth', helper::FILTER_STRING_SHORT) . ';';
			$style .= 'background-color:' . $this->getInput('newsThemeBackgroundColor') . ';';
			$style .= '}';

			// Dossier de l'instance
			if (!is_dir(self::DATADIRECTORY . $this->getUrl(0))) {
				mkdir(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css', 0755, true);
			}

			$success = is_int(file_put_contents(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css', $style));

			// Fin feuille de style

			$this->setData([
				'module', $this->getUrl(0),
				'theme',
				[
					'style' => $success ? self::DATADIRECTORY . $this->getUrl(0) . '/theme.css' : '',
					'borderStyle' => $this->getInput('newsThemeBorderStyle', helper::FILTER_STRING_SHORT),
					'borderColor' => $this->getInput('newsThemeBorderColor'),
					'borderWidth' => $this->getInput('newsThemeBorderWidth', helper::FILTER_STRING_SHORT),
					'backgroundColor' => $this->getInput('newsThemeBackgroundColor')
				]
			]);

			$this->setData([
				'module', $this->getUrl(0),
				'config',
				[
					'feeds' => $this->getInput('newsOptionShowFeeds', helper::FILTER_BOOLEAN),
					'feedsLabel' => $this->getInput('newsOptionFeedslabel', helper::FILTER_STRING_SHORT),
					'itemsperPage' => $this->getInput('newsOptionItemsperPage', helper::FILTER_INT, true),
					'itemsperCol' => $this->getInput('newsOptionItemsperCol', helper::FILTER_INT, true),
					'height' => $this->getInput('newsOptionHeight', helper::FILTER_INT, true),
					'dateFormat' => $this->getInput('newsOptionDateFormat'),
					'timeFormat' => $this->getInput('newsOptionTimeFormat'),
					'buttonBack' => $this->getInput('newsOptionButtonBack'),
					'versionData' => $this->getData(['module', $this->getUrl(0), 'config', 'versionData']),
				]
			]);


			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/option',
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		} else {
			// Ids des news par ordre de publication
			$newsIds = array_keys(helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC'));
			// Pagination
			$pagination = helper::pagination($newsIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage']));
			// Liste des pages
			self::$pages = $pagination['pages'];
			// Format de temps
			self::$dateFormat = $this->getData(['module', $this->getUrl(0), 'config', 'dateFormat']);
			self::$timeFormat = $this->getData(['module', $this->getUrl(0), 'config', 'timeFormat']);
			// News en fonction de la pagination
			for ($i = $pagination['first']; $i < $pagination['last']; $i++) {
				// Met en forme le tableau
				$dateOn = $dateOn = helper::dateUTF8(self::$dateFormat, $this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'publishedOn']), self::$siteContent) . ' - ' . helper::dateUTF8(self::$timeFormat, $this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'publishedOn']), self::$siteContent);
				if ($this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'publishedOff'])) {
					$dateOff = helper::dateUTF8(self::$dateFormat, $this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'publishedOff']), self::$siteContent) . ' - ' . helper::dateUTF8(self::$timeFormat, $this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'publishedOff']), self::$siteContent);
				} else {
					$dateOff = helper::translate('Permanent');
				}
				self::$news[] = [
					$this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'title']),
					$dateOn,
					$dateOff,
					helper::translate(helper::translate(self::$states[$this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'state'])])),
					template::button('newsConfigEdit' . $newsIds[$i], [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $newsIds[$i],
						'value' => template::ico('pencil')
					]),
					template::button('newsConfigDelete' . $newsIds[$i], [
						'class' => 'newsConfigDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $newsIds[$i],
						'value' => template::ico('cancel')
					])
				];
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => helper::translate('Options de configuration'),
				'view' => 'option',
				'vendor' => [
					'tinycolorpicker'
				]
			]);
		}
	}

	/**
	 * Suppression
	 */
	public function delete()
	{
		// La news n'existe pas
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true ||
			$this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2)]) === null
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Suppression
		else {
			$this->deleteData(['module', $this->getUrl(0), 'posts', $this->getUrl(2)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Article supprimé'),
				'state' => true
			]);
		}
	}

	/**
	 * Édition
	 */
	public function edit()
	{
		// La news n'existe pas
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true ||
			$this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2)]) === null
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// La news existe
		else {
			// Soumission du formulaire
			if (
				$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
				$this->isPost()
			) {
				// Si l'id a changée
				$newsId = $this->getInput('newsEditTitle', helper::FILTER_ID, true);
				if ($newsId !== $this->getUrl(2)) {
					// Incrémente le nouvel id de la news
					$newsId = helper::increment($newsId, $this->getData(['module', $this->getUrl(0), 'posts']));
					// Supprime l'ancien news
					$this->deleteData(['module', $this->getUrl(0), 'posts', $this->getUrl(2)]);
				}
				$publishedOn = $this->getInput('newsEditPublishedOn', helper::FILTER_DATETIME, true);
				$publishedOff = $this->getInput('newsEditPublishedOff') ? $this->getInput('newsEditPublishedOff', helper::FILTER_DATETIME) : '';
				$this->setData([
					'module', $this->getUrl(0),
					'posts',
					$newsId,
					[
						'content' => $this->getInput('newsEditContent', null),
						'publishedOn' => $publishedOn,
						'publishedOff' => $publishedOff < $publishedOn ? '' : $publishedOff,
						'state' => $this->getInput('newsEditState', helper::FILTER_BOOLEAN),
						'title' => $this->getInput('newsEditTitle', helper::FILTER_STRING_SHORT, true),
						'userId' => $this->getInput('newsEditUserId', helper::FILTER_ID, true)
					]
				]);
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
					'notification' => helper::translate('Modifications enregistrées'),
					'state' => true
				]);
			}
			// Liste des utilisateurs
			self::$users = helper::arrayColumn($this->getData(['user']), 'firstname');
			ksort(self::$users);
			foreach (self::$users as $userId => &$userFirstname) {
				$userFirstname = $userFirstname . ' ' . $this->getData(['user', $userId, 'lastname']);
			}
			unset($userFirstname);
			// Valeurs en sortie
			$this->addOutput([
				'title' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'title']),
				'vendor' => [
					'flatpickr',
					'tinymce'
				],
				'view' => 'edit'
			]);
		}
	}

	/**
	 * Accueil
	 */
	public function index()
	{

		// Mise à jour des données de module
		$this->update();

		// Affichage d'un article
		if (
			$this->getUrl(1)
			// Protection pour la pagination, un ID ne peut pas être un entier, une page oui
			and intval($this->getUrl(1)) === 0
		) {
			// L'article n'existe pas
			if ($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1)]) === null) {
				// Valeurs en sortie
				$this->addOutput([
					'access' => false
				]);
			}
			// L'article existe
			else {
				self::$articleSignature = $this->signature($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'userId']));
				// Valeurs en sortie
				$this->addOutput([
					'showBarEditButton' => true,
					'title' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'title']),
					'view' => 'article'
				]);
			}
		} else {
			// Affichage index
			// Ids des news par ordre de publication
			$newsIdsPublishedOns = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC');
			$newsIdsStates = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'state', 'SORT_DESC');
			$newsIds = [];
			foreach ($newsIdsPublishedOns as $newsId => $newsPublishedOn) {
				$newsIdsPublishedOff = $this->getData(['module', $this->getUrl(0), 'posts', $newsId, 'publishedOff']);
				if (
					$newsPublishedOn <= time() and
					$newsIdsStates[$newsId] and
						// date de péremption tenant des champs non définis
					(!is_integer($newsIdsPublishedOff) or
						$newsIdsPublishedOff > time()
					)
				) {
					$newsIds[] = $newsId;
				}
			}
			// Pagination selon le layout
			$pagination = helper::pagination($newsIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage']));
			// Nombre de colonnes
			self::$nbrCol = $this->getData(['module', $this->getUrl(0), 'config', 'itemsperCol']);
			// Liste des pages
			self::$pages = $pagination['pages'];
			// News en fonction de la pagination
			for ($i = $pagination['first']; $i < $pagination['last']; $i++) {
				self::$news[$newsIds[$i]] = $this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i]]);
				// Longueur de la news affichée
				if (
					$this->getData(['module', $this->getUrl(0), 'config', 'height']) !== -1
					&& strlen($this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'content'])) >= $this->getData(['module', $this->getUrl(0), 'config', 'height'])
				) {
					// Contenu raccourci
					$content = substr($this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'content']), 0, $this->getData(['module', $this->getUrl(0), 'config', 'height']));
					// Ne pas couper un mot
					$lastSpace = strrpos($content, ' ', -1);
					self::$news[$newsIds[$i]]['content'] = substr(strip_tags($content, '<br><p><img>'), 0, $lastSpace);
				}
				// Mise en forme de la signature
				self::$news[$newsIds[$i]]['userId'] = $this->signature($this->getData(['module', $this->getUrl(0), 'posts', $newsIds[$i], 'userId']));
			}
			self::$dateFormat = $this->getData(['module', $this->getUrl(0), 'config', 'dateFormat']);
			self::$timeFormat = $this->getData(['module', $this->getUrl(0), 'config', 'timeFormat']);
			// Valeurs en sortie
			$this->addOutput([
				'showBarEditButton' => true,
				'showPageContent' => true,
				'view' => 'index',
			]);
		}
	}

	/**
	 * Mise à jour du module
	 * Appelée par les fonctions index et config
	 */
	private function update()
	{

		// le module n'est pas initialisé
		if (
			$this->getData(['module', $this->getUrl(0), 'config']) === NULL
			|| $this->getData(['module', $this->getUrl(0), 'theme']) === NULL
			|| !file_exists(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css')
		) {
			$this->init();
		}

		$versionData = $this->getData(['module', $this->getUrl(0), 'config', 'versionData']);
		// Mise à jour 3.2
		if (version_compare($versionData, '3.1', '<')) {
			$this->setData(['module', $this->getUrl(0), 'theme', 'itemsBlur', '0%']);
			// Mettre à jour la version
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '3.2']);
		}
		// Mise à jour 3.3
		if (version_compare($versionData, '3.3', '<')) {
			if (is_dir(self::DATADIRECTORY . 'pages/')) {
				// Déplacer les données du dossier Pages
				$this->copyDir(self::DATADIRECTORY . 'pages/' . $this->getUrl(0), self::DATADIRECTORY . $this->getUrl(0));
				$this->deleteDir(self::DATADIRECTORY . 'pages/');
				$style = $this->getData(['module', $this->getUrl(0), 'theme', 'style']);
				$this->setData(['module', $this->getUrl(0), 'theme', 'style', str_replace('pages/', '', $style)]);
			}
			// Mettre à jour la version
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '3.3']);
		}
		// Mise à jour 4.4
		if (version_compare($versionData, '3.4', '<')) {
			// Effacer le style précédent
			unlink(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css');
			$this->deleteData(['module', $this->getUrl(0), 'theme']);
			// Le générer
			$this->init();
			// Mettre à jour la version
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '3.4']);
		}
		// Mise à jour 4.4
		if (version_compare($versionData, '4.4', '<')) {
			$this->setData(['module', $this->getUrl(0), 'config', 'dateFormat', '%d %B %Y']);
			$this->setData(['module', $this->getUrl(0), 'config', 'timeFormat', '%H:%M']);
			// Mettre à jour la version
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '4.4']);
		}
		// Mise à jour 5.3
		if (version_compare($versionData, '5.3', '<')) {
			$this->setData(['module', $this->getUrl(0), 'config', 'buttonBack', true]);
			// Mettre à jour la version
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '5.3']);
		}

	}

	/**
	 * Initialisation du thème d'un nouveau module
	 */
	private function init()
	{

		$fileCSS = self::DATADIRECTORY . $this->getUrl(0) . '/theme.css';

		// Données du module absentes
		require_once('module/news/ressource/defaultdata.php');
		if ($this->getData(['module', $this->getUrl(0), 'config']) === null) {
			$this->setData(['module', $this->getUrl(0), 'config', init::$defaultData]);
		}
		if ($this->getData(['module', $this->getUrl(0), 'theme']) === null) {
			// Données de thème
			$this->setData(['module', $this->getUrl(0), 'theme', init::$defaultTheme]);
			$this->setData(['module', $this->getUrl(0), 'theme', 'style', self::DATADIRECTORY . $this->getUrl(0) . '/theme.css']);
		}

		// Dossier de l'instance
		if (!is_dir(self::DATADIRECTORY . $this->getUrl(0))) {
			mkdir(self::DATADIRECTORY . $this->getUrl(0), 0755, true);
		}

		// Check la présence de la feuille de style
		if (!file_exists(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css')) {
			// Générer la feuille de CSS
			$style = '.newsFrame {';
			$style .= 'border:' . $this->getData(['module', $this->getUrl(0), 'theme', 'borderStyle']) . ' ' . $this->getData(['module', $this->getUrl(0), 'theme', 'borderColor']) . ' ' . $this->getData(['module', $this->getUrl(0), 'theme', 'borderWidth']) . ';';
			$style .= 'background-color:' . $this->getData(['module', $this->getUrl(0), 'theme', 'backgroundColor']) . ';';
			$style .= '}';

			// Sauver la feuille de style
			file_put_contents(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css', $style);
			// Stocker le nom de la feuille de style
			$this->setData(['module', $this->getUrl(0), 'theme', 'style', self::DATADIRECTORY . $this->getUrl(0) . '/theme.css']);
		}
	}
}