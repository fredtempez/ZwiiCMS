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
 * @link http://zwiicms.fr/
 */

class news extends common {

	const VERSION = '2.1';
	const REALNAME = 'Actualités';
	const DELETE = true;
	const UPDATE = true;
	const DATADIRECTORY = [
		'fr/module.json'
	];

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
		$newsIdsPublishedOns = array_slice($newsIdsPublishedOns, 0, $this->getData(['config', 'itemsperPage']) );
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
	 * Édition
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
		// Soumission du formulaire
		if($this->isPost()) {
			$this->setData(['module', $this->getUrl(0), 'config',[
				'feeds' 	 => $this->getInput('newsConfigShowFeeds',helper::FILTER_BOOLEAN),
				'feedsLabel' => $this->getInput('newsConfigFeedslabel',helper::FILTER_STRING_SHORT)
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
			$pagination = helper::pagination($newsIds, $this->getUrl(),$this->getData(['config','itemsperPage']));
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
		$pagination = helper::pagination($newsIds, $this->getUrl(),$this->getData(['config','itemsperPage']));
		// Liste des pages
		self::$pages = $pagination['pages'];
		// News en fonction de la pagination
		for($i = $pagination['first']; $i < $pagination['last']; $i++) {
			self::$news[$newsIds[$i]] = $this->getData(['module', $this->getUrl(0),'posts', $newsIds[$i]]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index'
		]);
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
}