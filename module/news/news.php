<?php

/**
 * This file is part of Zwii.
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

class news extends common {

	const VERSION = '3.0';
	const REALNAME = 'Actualités';
	const DELETE = true;
	const UPDATE = '0.0';
	const DATADIRECTORY =  self::DATA_DIR . 'modules/news/';

	public static $actions = [
		'add' => self::GROUP_MODERATOR,
		'config' => self::GROUP_MODERATOR,
		'delete' => self::GROUP_MODERATOR,
		'edit' => self::GROUP_MODERATOR,
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
	public static $ItemsList = [
		4 => '4 articles',
		8 => '8 articles',
		12 => '12 articles',
		16 => '16 articles',
		22 => '22  articles'
	];
	// Nombre de colone par page
	public static $Columns = [
		12 => '1 Colonne',
		6 => '2 Colonnes',
		4 => '3 Colonnes',
		2 => '4 Colonnes'
	];
	public static $nbrCol = 1;

	public static $ItemsHeight = [
		'200px' 	=> 'Petit',
		'300px' 	=> 'Moyen',
		'400px' 	=> 'Grand'
	];

	// Signature de l'article
	public static $articleSignature = '';


	/**
	 * Flux RSS
	 */
	public function rss() {
		// Inclure les classes
		include_once 'module/news/vendor/FeedWriter/Item.php';
		include_once 'module/news/vendor/FeedWriter/Feed.php';
		include_once 'module/news/vendor/FeedWriter/RSS2.php';
		include_once 'module/news/vendor/FeedWriter/InvalidOperationException.php';

		date_default_timezone_set('UTC');

		$feeds = new \FeedWriter\RSS2();

		// En-tête
		$feeds->setTitle($this->getData (['page', $this->getUrl(0),'title']));
		$feeds->setLink(helper::baseUrl() . $this->getUrl(0));
		$feeds->setDescription($this->getData (['page', $this->getUrl(0), 'metaDescription']));
		$feeds->setChannelElement('language', 'fr-FR');
		$feeds->setDate(date('r',time()));
		$feeds->addGenerator();
		// Corps des articles
		$newsIdsPublishedOns = helper::arrayCollumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC');
		// Articles de la première page uniquement
		$newsIdsPublishedOns = array_slice($newsIdsPublishedOns, 0, $this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage']) );
		$newsIdsStates = helper::arrayCollumn($this->getData(['module', $this->getUrl(0), 'posts']), 'state', 'SORT_DESC');
		foreach($newsIdsPublishedOns as $newsId => $newsPublishedOn) {
			if($newsPublishedOn <= time() AND $newsIdsStates[$newsId]) {
				$newsArticle = $feeds->createNewItem();
				$author = $this->signature($this->getData(['module', $this->getUrl(0),  'posts', $newsId, 'userId']));
				$newsArticle->addElementArray([
					'title' 		=> $this->getData(['module', $this->getUrl(0),'posts', $newsId, 'title']),
					'link' 			=> helper::baseUrl() . $this->getUrl(0) . '/' . $newsId . '#' . $newsId,
					'description' 	=> $this->getData(['module', $this->getUrl(0),'posts', $newsId, 'content'])
				]);
				$newsArticle->setAuthor($author,'no@mail.com');
				$newsArticle->setId(helper::baseUrl() .$this->getUrl(0) . '/' . $newsId . '#' . $newsId);
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
	public function add() {
		// Soumission du formulaire
		if($this->isPost()) {
			// Crée la news
			$newsId = helper::increment($this->getInput('newsAddTitle', helper::FILTER_ID), (array) $this->getData(['module', $this->getUrl(0)]));
			$this->setData(['module', $this->getUrl(0),'posts', $newsId, [
				'content' => $this->getInput('newsAddContent', null),
				'publishedOn' => $this->getInput('newsAddPublishedOn', helper::FILTER_DATETIME, true),
				'state' => $this->getInput('newsAddState', helper::FILTER_BOOLEAN),
				'title' => $this->getInput('newsAddTitle', helper::FILTER_STRING_SHORT, true),
				'userId' => $this->getInput('newsAddUserId', helper::FILTER_ID, true)
			]]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Nouvelle news créée',
				'state' => true
			]);
		}
		// Liste des utilisateurs
		self::$users = helper::arrayCollumn($this->getData(['user']), 'firstname');
		ksort(self::$users);
		foreach(self::$users as $userId => &$userFirstname) {
			$userFirstname = $userFirstname . ' ' . $this->getData(['user', $userId, 'lastname']);
		}
		unset($userFirstname);
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Nouvelle news',
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
	public function config() {

		// Mise à jour des données de module
		$this->update();

		// Soumission du formulaire
		if($this->isPost()) {

			// Générer la feuille de CSS
			$style = '.newsContent {height:' . $this->getInput('newsConfigItemsHeight',helper::FILTER_STRING_SHORT) . ';}';
			// Dossier de l'instance
			if (!is_dir(self::DATADIRECTORY)) {
				mkdir (self::DATADIRECTORY, 0777, true);
			}

			$success = file_put_contents(self::DATADIRECTORY . $this->getUrl(0) . '.css' , $style );
			// Fin feuille de style

			$this->setData(['module', $this->getUrl(0), 'config',[
				'feeds' 	 => $this->getInput('newsConfigShowFeeds',helper::FILTER_BOOLEAN),
				'feedsLabel' => $this->getInput('newsConfigFeedslabel',helper::FILTER_STRING_SHORT),
				'itemsperPage' => $this->getInput('newsConfigItemsperPage', helper::FILTER_INT,true),
				'itemsperCol' => $this->getInput('newsConfigItemsperCol', helper::FILTER_INT,true),
				'itemsHeight' => $this->getInput('newsConfigItemsHeight',helper::FILTER_STRING_SHORT),
				'versionData' => $this->getData(['module', $this->getUrl(0), 'config', 'versionData']),
				'style' => $success ? self::DATADIRECTORY . $this->getUrl(0) . '.css' : ''
				]]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		} else {
			// Ids des news par ordre de publication
			$newsIds = array_keys(helper::arrayCollumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC'));
			// Pagination
			$pagination = helper::pagination($newsIds, $this->getUrl(),$this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage']) );
			// Liste des pages
			self::$pages = $pagination['pages'];
			// News en fonction de la pagination
			for($i = $pagination['first']; $i < $pagination['last']; $i++) {
				// Met en forme le tableau
				$date = mb_detect_encoding(strftime('%d %B %Y',  $this->getData(['module', $this->getUrl(0),'posts', $newsIds[$i], 'publishedOn'])), 'UTF-8', true)
						? strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0),'posts', $newsIds[$i], 'publishedOn']))
						: utf8_encode(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0),'posts', $newsIds[$i], 'publishedOn'])));
				$heure = mb_detect_encoding(strftime('%H:%M',  $this->getData(['module', $this->getUrl(0),'posts', $newsIds[$i], 'publishedOn'])), 'UTF-8', true)
						? strftime('%H:%M', $this->getData(['module', $this->getUrl(0),'posts', $newsIds[$i], 'publishedOn']))
						: utf8_encode(strftime('%H:%M', $this->getData(['module', $this->getUrl(0),'posts', $newsIds[$i], 'publishedOn'])));
				self::$news[] = [
					$this->getData(['module', $this->getUrl(0),'posts', $newsIds[$i], 'title']),
					$date .' à '. $heure,
					self::$states[$this->getData(['module', $this->getUrl(0),'posts', $newsIds[$i], 'state'])],
					template::button('newsConfigEdit' . $newsIds[$i], [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $newsIds[$i]. '/' . $_SESSION['csrf'],
						'value' => template::ico('pencil')
					]),
					template::button('newsConfigDelete' . $newsIds[$i], [
						'class' => 'newsConfigDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $newsIds[$i] . '/' . $_SESSION['csrf'],
						'value' => template::ico('cancel')
					])
				];
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Configuration du module',
				'view' => 'config'
			]);
		}
	}

	/**
	 * Suppression
	 */
	public function delete() {
		// La news n'existe pas
		if($this->getData(['module', $this->getUrl(0),'posts', $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Jeton incorrect
		elseif ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . $this->getUrl(0) . '/config',
				'notification' => 'Action non autorisée'
			]);
		}
		// Suppression
		else {
			$this->deleteData(['module', $this->getUrl(0),'posts', $this->getUrl(2)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'News supprimée',
				'state' => true
			]);
		}
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
		// La news n'existe pas
		if($this->getData(['module', $this->getUrl(0),'posts', $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// La news existe
		else {
			// Soumission du formulaire
			if($this->isPost()) {
				// Si l'id a changée
				$newsId = $this->getInput('newsEditTitle', helper::FILTER_ID, true);
				if($newsId !== $this->getUrl(2)) {
					// Incrémente le nouvel id de la news
					$newsId = helper::increment($newsId, $this->getData(['module', $this->getUrl(0)]));
					// Supprime l'ancien news
					$this->deleteData(['module', $this->getUrl(0),'posts', $this->getUrl(2)]);
				}
				$this->setData(['module', $this->getUrl(0),'posts', $newsId, [
					'content' => $this->getInput('newsEditContent', null),
					'publishedOn' => $this->getInput('newsEditPublishedOn', helper::FILTER_DATETIME, true),
					'state' => $this->getInput('newsEditState', helper::FILTER_BOOLEAN),
					'title' => $this->getInput('newsEditTitle', helper::FILTER_STRING_SHORT, true),
					'userId' => $this->getInput('newsEditUserId', helper::FILTER_ID, true)
				]]);
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
					'notification' => 'Modifications enregistrées',
					'state' => true
				]);
			}
			// Liste des utilisateurs
			self::$users = helper::arrayCollumn($this->getData(['user']), 'firstname');
			ksort(self::$users);
			foreach(self::$users as $userId => &$userFirstname) {
				$userFirstname = $userFirstname . ' ' . $this->getData(['user', $userId, 'lastname']);
			}
			unset($userFirstname);
			// Valeurs en sortie
			$this->addOutput([
				'title' => $this->getData(['module', $this->getUrl(0),'posts', $this->getUrl(2), 'title']),
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
	public function index() {

		// Mise à jour des données de module
		$this->update();
		// Affichage d'un article
		if(
			$this->getUrl(1)
			// Protection pour la pagination, un ID ne peut pas être un entier, une page oui
			AND intval($this->getUrl(1)) === 0
		) {
			// L'article n'existe pas
			if($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1)]) === null) {
				// Valeurs en sortie
				$this->addOutput([
					'access' => false
				]);
			}
			// L'article existe
			else {
				self::$articleSignature = $this->signature($this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(1), 'userId']));
				// Valeurs en sortie
				$this->addOutput([
					'showBarEditButton' => true,
					'title' => $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(1), 'title']),
					'view' => 'article'
				]);

			}
		} else {
			// Affichage index
			// Ids des news par ordre de publication
			$newsIdsPublishedOns = helper::arrayCollumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC');
			$newsIdsStates = helper::arrayCollumn($this->getData(['module', $this->getUrl(0), 'posts']), 'state', 'SORT_DESC');
			$newsIds = [];
			foreach($newsIdsPublishedOns as $newsId => $newsPublishedOn) {
				if($newsPublishedOn <= time() AND $newsIdsStates[$newsId]) {
					$newsIds[] = $newsId;
				}
			}
			// Pagination
			//$pagination = helper::pagination($newsIds, $this->getUrl(),$this->getData(['config','itemsperPage']));
			$pagination = helper::pagination($newsIds, $this->getUrl(),$this->getData(['module', $this->getUrl(0),'config', 'itemsperPage']));
			// Nombre de colonnes
			self::$nbrCol = $this->getData(['module', $this->getUrl(0),'config', 'itemsperCol']);
			// Liste des pages
			self::$pages = $pagination['pages'];
			// News en fonction de la pagination
			for($i = $pagination['first']; $i < $pagination['last']; $i++) {
				self::$news[$newsIds[$i]] = $this->getData(['module', $this->getUrl(0),'posts', $newsIds[$i]]);
				self::$news[$newsIds[$i]]['userId'] = $this->signature($this->getData(['module', $this->getUrl(0),  'posts', $newsIds[$i], 'userId']));
			}
			// Valeurs en sortie
			$this->addOutput([
				'showBarEditButton' => true,
				'showPageContent' => true,
				'view' => 'index',
				'style' => $this->getData(['module', $this->getUrl(0),'config', 'style'])
			]);

		}
	}

	/**
	 * Retourne la signature d'un utilisateur
	 */
	private function signature($userId) {
		switch ($this->getData(['user', $userId, 'signature'])){
			case 1:
				return $userId;
				break;
			case 2:
				return $this->getData(['user', $userId, 'pseudo']);
				break;
			case 3:
				return $this->getData(['user', $userId, 'firstname']) . ' ' . $this->getData(['user', $userId, 'lastname']);
				break;
			case 4:
				return $this->getData(['user', $userId, 'lastname']) . ' ' . $this->getData(['user', $userId, 'firstname']);
				break;
			default:
				return $this->getData(['user', $userId, 'firstname']);
		}
	}

	/**
	 * Mise à jour du module
	 * Appelée par les fonctions index et config
	 */
	private function update() {

		// Initialisation du thème du nouveau module
		$this->initCss($this->getUrl(0));

		// Version 3.0
		if (version_compare($this->getData(['module', $this->getUrl(0), 'config', 'versionData']), '3.0', '<') ) {
			$this->setData(['module', $this->getUrl(0), 'config', 'itemsperPage', 16]);
			$this->setData(['module', $this->getUrl(0), 'config', 'itemsperCol', 6]);
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData','3.0']);

		}
	}

	/**
	 * Initialisation du thème d'un nouveau module
	 */
	private function initCSS($moduleId) {
		// Variable commune
		$fileCSS = self::DATADIRECTORY  . $moduleId . '.css' ;

		// Absence des données CSS
		if ( $this->getData(['module', $moduleId, 'config', 'itemsHeight']) === null ) {

			$this->setData(['module', $moduleId, 'config', 'itemsHeight', '200px']);
		}
		// Absence de la feuille de style
		if (!file_exists(self::DATADIRECTORY . $moduleId . '.css') ) {
			// Générer la feuille de CSS
			$style = '.newsContent {height: 200px;}';

			// Dossier de l'instance
			if (!is_dir(self::DATADIRECTORY)) {
				mkdir (self::DATADIRECTORY, 0777, true);
			}

			// Sauver la feuille de style
			$success = file_put_contents(self::DATADIRECTORY .$moduleId . '.css' , $style );

			// Nom de la feuille de style
			$this->setData(['module', $moduleId, 'config', 'style', self::DATADIRECTORY . $moduleId . '.css']);
		}
	}
}
