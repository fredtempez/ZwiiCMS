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

class blog extends common {

	public static $actions = [
		'add' => self::GROUP_MODERATOR,
		'comment' => self::GROUP_MODERATOR,
		'commentDelete' => self::GROUP_MODERATOR,
		'config' => self::GROUP_MODERATOR,
		'delete' => self::GROUP_MODERATOR,
		'edit' => self::GROUP_MODERATOR,
		'index' => self::GROUP_VISITOR,
		'rss' => self::GROUP_VISITOR
	];

	public static $articles = [];

	public static $comments = [];

	public static $pages;

	public static $rssUrl;

	public static $rssLabel;

	public static $states = [
		false => 'Brouillon',
		true => 'Publié'
	];

	public static $pictureSizes = [
		'20' => 'Très petite',
		'30' => 'Petite',
		'40' => 'Grande',
		'50' => 'Très Grande',
		'100' => 'Pleine largeur',
	];

	public static $picturePositions = [
		'left' => 'À gauche',
		'right' => 'À droite ',
	];


	public static $users = [];

	const BLOG_VERSION = '2.99';

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
		$feeds->setTitle($this->getData (['page', $this->getUrl(0), 'posts','title']));
		$feeds->setLink(helper::baseUrl() . $this->getUrl(0));
		$feeds->setDescription(html_entity_decode(strip_tags($this->getData (['page', $this->getUrl(0), 'metaDescription']))));
		$feeds->setChannelElement('language', 'fr-FR');
		$feeds->setDate(time());
		$feeds->addGenerator();
		// Corps des articles
		$articleIdsPublishedOns = helper::arrayCollumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC');
		$articleIdsStates = helper::arrayCollumn($this->getData(['module', $this->getUrl(0),'posts']), 'state', 'SORT_DESC');
		foreach($articleIdsPublishedOns as $articleId => $articlePublishedOn) {
			if($articlePublishedOn <= time() AND $articleIdsStates[$articleId]) {
				// Miniature
				$parts = explode('/',$this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'picture']));
				$thumb = str_replace ($parts[(count($parts)-1)],'mini_' . $parts[(count($parts)-1)], $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'picture']));
				// Créer les articles du flux
				$newsArticle = $feeds->createNewItem();
				$newsArticle->addElementArray([
					'title' => strip_tags($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'title']) ),
					'link' => helper::baseUrl() .$this->getUrl(0) . '/' . $articleId,
					'description' => html_entity_decode(strip_tags($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'content']))),
					'addEnclosure' => helper::baseUrl() . self::FILE_DIR . $thumb
				]);
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
			// Incrémente l'id de l'article
			$articleId = helper::increment($this->getInput('blogAddTitle', helper::FILTER_ID), $this->getData(['page']));
			$articleId = helper::increment($articleId, (array) $this->getData(['module', $this->getUrl(0)]));
			$articleId = helper::increment($articleId, array_keys(self::$actions));
			// Crée l'article
			$this->setData(['module', $this->getUrl(0), 'posts', $articleId, [
				'closeComment' => $this->getInput('blogAddCloseComment', helper::FILTER_BOOLEAN),
				'mailNotification'  => $this->getInput('blogAddMailNotification', helper::FILTER_BOOLEAN),
				'groupNotification' => 	$this->getInput('blogAddGroupNotification', helper::FILTER_INT),
				'comment' => [],
				'content' => $this->getInput('blogAddContent', null),
				'picture' => $this->getInput('blogAddPicture', helper::FILTER_STRING_SHORT, true),
				'hidePicture' => $this->getInput('blogAddHidePicture', helper::FILTER_BOOLEAN),
				'pictureSize' => $this->getInput('blogAddPictureSize', helper::FILTER_STRING_SHORT),
				'picturePosition' => $this->getInput('blogAddPicturePosition', helper::FILTER_STRING_SHORT),
				'publishedOn' => $this->getInput('blogAddPublishedOn', helper::FILTER_DATETIME, true),
				'state' => $this->getInput('blogAddState', helper::FILTER_BOOLEAN),
				'title' => $this->getInput('blogAddTitle', helper::FILTER_STRING_SHORT, true),
				'userId' => $this->getInput('blogAddUserId', helper::FILTER_ID, true)
			]]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Nouvel article créé',
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
			'title' => 'Nouvel article',
			'vendor' => [
				'flatpickr',
				'tinymce'
			],
			'view' => 'add'
		]);
	}

	/**
	 * Liste des commentaires
	 */
	public function comment() {
		// Liste les commentaires
		$comments = [];
		foreach((array) $this->getData(['module', $this->getUrl(0)]) as $articleId => $article) {
			foreach($article['comment'] as &$comment) {
				$comment['articleId'] = $articleId;
			}
			$comments += $article['comment'];
		}
		// Ids des commentaires par ordre de création
		$commentIds = array_keys(helper::arrayCollumn($comments, 'createdOn', 'SORT_DESC'));
		// Pagination
		$pagination = helper::pagination($commentIds, $this->getUrl(),$this->getData(['config','itemsperPage']));
		// Liste des pages
		self::$pages = $pagination['pages'];
		// Commentaires en fonction de la pagination
		for($i = $pagination['first']; $i < $pagination['last']; $i++) {
			// Met en forme le tableau
			$comment = $comments[$commentIds[$i]];
			self::$comments[] = [
				mb_detect_encoding(strftime('%d %B %Y - %H:%M', $comment['createdOn']), 'UTF-8', true)
				? strftime('%d %B %Y - %H:%M', $comment['createdOn'])
				: utf8_encode(strftime('%d %B %Y - %H:%M', $comment['createdOn'])),
				$comment['content'],
				$comment['userId'] ? $this->getData(['user', $comment['userId'], 'firstname']) . ' ' . $this->getData(['user', $comment['userId'], 'lastname']) : $comment['author'],
				template::button('blogCommentDelete' . $commentIds[$i], [
					'class' => 'blogCommentDelete buttonRed',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/comment-delete/' . $comment['articleId'] . '/' . $commentIds[$i] . '/' . $_SESSION['csrf'] ,
					'value' => template::ico('cancel')
				])
			];
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Gestion des commentaires',
			'view' => 'comment'
		]);
	}

	/**
	 * Suppression de commentaire
	 */
	public function commentDelete() {
		// Le commentaire n'existe pas
		if($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment', $this->getUrl(3)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Jeton incorrect
		elseif ($this->getUrl(4) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . $this->getUrl(0) . '/config',
				'notification' => 'Action non autorisée'
			]);
		}
		// Suppression
		else {
			$this->deleteData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment', $this->getUrl(3)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment',
				'notification' => 'Commentaire supprimé',
				'state' => true
			]);
		}
	}

	/**
	 * Configuration
	 */
	public function config() {
		// Soumission du formulaire
		if($this->isPost()) {
			$this->setData(['module', $this->getUrl(0), 'config',[
				'feeds' 	 => $this->getInput('blogConfigShowFeeds',helper::FILTER_BOOLEAN),
				'feedsLabel' => $this->getInput('blogConfigFeedslabel',helper::FILTER_STRING_SHORT)
				]]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		} else {
			// Ids des articles par ordre de publication
			$articleIds = array_keys(helper::arrayCollumn($this->getData(['module', $this->getUrl(0),'posts']), 'publishedOn', 'SORT_DESC'));
			// Supprimer le bloc config
			// Pagination
			$pagination = helper::pagination($articleIds, $this->getUrl(),$this->getData(['config','itemsperPage']));
			// Liste des pages
			self::$pages = $pagination['pages'];
			// Articles en fonction de la pagination
			for($i = $pagination['first']; $i < $pagination['last']; $i++) {
				// Met en forme le tableau
				$date = mb_detect_encoding(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'publishedOn'])), 'UTF-8', true)
					? strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'publishedOn']))
					: utf8_encode(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'publishedOn'])));
				$heure =   mb_detect_encoding(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'publishedOn'])), 'UTF-8', true)
				? strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'publishedOn']))
				: utf8_encode(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'publishedOn'])));
				self::$articles[] = [
					$this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'title']),
					$date .' à '. $heure,
					self::$states[$this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'state'])],
					template::button('blogConfigEdit' . $articleIds[$i], [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $articleIds[$i] . '/' . $_SESSION['csrf'],
						'value' => template::ico('pencil')
					]),
					template::button('blogConfigDelete' . $articleIds[$i], [
						'class' => 'blogConfigDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $articleIds[$i] . '/' . $_SESSION['csrf'],
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
		if($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2)]) === null) {
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
			$this->deleteData(['module', $this->getUrl(0), 'posts', $this->getUrl(2)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Article supprimé',
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
		// L'article n'existe pas
		if($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// L'article existe
		else {
			// Soumission du formulaire
			if($this->isPost()) {
				$articleId = $this->getInput('blogEditTitle', helper::FILTER_ID, true);
				// Incrémente le nouvel id de l'article
				if($articleId !== $this->getUrl(2)) {
					$articleId = helper::increment($articleId, $this->getData(['page']));
					$articleId = helper::increment($articleId, $this->getData(['module', $this->getUrl(0)]));
					$articleId = helper::increment($articleId, array_keys(self::$actions));
				}
				$this->setData(['module', $this->getUrl(0), 'posts', $articleId, [
					'closeComment' => $this->getInput('blogEditCloseComment'),
					'mailNotification'  => $this->getInput('blogEditMailNotification', helper::FILTER_BOOLEAN),
					'groupNotification' => $this->getInput('blogEditGroupNotification', helper::FILTER_INT),
					'comment' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment']),
					'content' => $this->getInput('blogEditContent', null),
					'picture' => $this->getInput('blogEditPicture', helper::FILTER_STRING_SHORT, true),
					'hidePicture' => $this->getInput('blogEditHidePicture', helper::FILTER_BOOLEAN),
					'pictureSize' => $this->getInput('blogEditPictureSize', helper::FILTER_STRING_SHORT),
					'picturePosition' => $this->getInput('blogEditPicturePosition', helper::FILTER_STRING_SHORT),
					'publishedOn' => $this->getInput('blogEditPublishedOn', helper::FILTER_DATETIME, true),
					'state' => $this->getInput('blogEditState', helper::FILTER_BOOLEAN),
					'title' => $this->getInput('blogEditTitle', helper::FILTER_STRING_SHORT, true),
					'userId' => $this->getInput('blogEditUserId', helper::FILTER_ID, true)
				]]);
				// Supprime l'ancien article
				if($articleId !== $this->getUrl(2)) {
					$this->deleteData(['module', $this->getUrl(0), 'posts', $this->getUrl(2)]);
				}
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
	 * Accueil (deux affichages en un pour éviter une url à rallonge)
	 */
	public function index() {
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
				// Soumission du formulaire
				if($this->isPost()) {
					// Check la captcha
					if(
						$this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
						//AND $this->getInput('blogArticlecaptcha', helper::FILTER_INT) !== $this->getInput('blogArticlecaptchaFirstNumber', helper::FILTER_INT) + $this->getInput('blogArticlecaptchaSecondNumber', helper::FILTER_INT))
						AND password_verify($this->getInput('blogArticleCaptcha', helper::FILTER_INT), $this->getInput('blogArticleCaptchaResult') ) === false )
					{
						self::$inputNotices['blogArticleCaptcha'] = 'Incorrect';
					}
					// Crée le commentaire
					$commentId = helper::increment(uniqid(), $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment']));
					$this->setData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment', $commentId, [
						'author' => $this->getInput('blogArticleAuthor', helper::FILTER_STRING_SHORT, empty($this->getInput('blogArticleUserId')) ? TRUE : FALSE),
						'content' => $this->getInput('blogArticleContent', helper::FILTER_STRING_SHORT, true),
						'createdOn' => time(),
						'userId' => $this->getInput('blogArticleUserId'),
					]]);

					// Envoi d'une notification aux administrateurs
					// Init tableau
					$to = [];
					// Liste des destinataires
					foreach($this->getData(['user']) as $userId => $user) {
						if ($user['group'] >= $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'groupNotification']) ) {
							$to[] = $user['mail'];
						}
					}
					// Envoi du mail $sent code d'erreur ou de réussite
					if ($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'mailNotification']) === true) {
						$sent = $this->sendMail(
							$to,
							'Nouveau commentaire',
							'Bonjour' . ' <strong>' . $user['firstname'] . ' ' . $user['lastname'] . '</strong>,<br><br>' .
							'Nouveau commentaire déposé sur la page "' . $this->getData(['page', $this->getUrl(0), 'posts', 'title']) . '" :<br><br>',
							''
						);
						// Valeurs en sortie
						$this->addOutput([
							'redirect' => helper::baseUrl() . $this->getUrl() . '#comment',
							//'notification' => 'Commentaire ajouté',
							//'state' => true
							'notification' => ($sent === true ? 'Commentaire ajouté et une notification envoyée' : 'Commentaire ajouté, erreur de notification : <br/>' . $sent),
							'state' => ($sent === true ? true : null)
						]);

					} else {
						// Valeurs en sortie
						$this->addOutput([
							'redirect' => helper::baseUrl() . $this->getUrl() . '#comment',
							'notification' => 'Commentaire ajouté',
							'state' => true
						]);
					}

				}
				// Ids des commentaires par ordre de publication
				$commentIds = array_keys(helper::arrayCollumn($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment']), 'createdOn', 'SORT_DESC'));
				// Pagination
				$pagination = helper::pagination($commentIds, $this->getUrl(),$this->getData(['config','itemsperPage']),'#comment');
				// Liste des pages
				self::$pages = $pagination['pages'];
				// Commentaires en fonction de la pagination
				for($i = $pagination['first']; $i < $pagination['last']; $i++) {
					self::$comments[$commentIds[$i]] = $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment', $commentIds[$i]]);
				}
				self::$rssUrl =  helper::baseUrl() . $this->getUrl(0) . '/rss';
				self::$rssLabel = $this->getData(['module', $this->getUrl(0), 'config','feedsLabel']);
				// Valeurs en sortie
				$this->addOutput([
					'showBarEditButton' => true,
					'title' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'title']),
					'view' => 'article'
				]);
			}

		}
		// Liste des articles
		else {
			// Ids des articles par ordre de publication
			$articleIdsPublishedOns = helper::arrayCollumn($this->getData(['module', $this->getUrl(0),'posts']), 'publishedOn', 'SORT_DESC');
			$articleIdsStates = helper::arrayCollumn($this->getData(['module', $this->getUrl(0), 'posts']), 'state', 'SORT_DESC');
			$articleIds = [];
			foreach($articleIdsPublishedOns as $articleId => $articlePublishedOn) {
				if($articlePublishedOn <= time() AND $articleIdsStates[$articleId]) {
					$articleIds[] = $articleId;
				}
			}
			// Pagination
			$pagination = helper::pagination($articleIds, $this->getUrl(),$this->getData(['config','itemsperPage']));
			// Liste des pages
			self::$pages = $pagination['pages'];
			// Articles en fonction de la pagination
			for($i = $pagination['first']; $i < $pagination['last']; $i++) {
				self::$articles[$articleIds[$i]] = $this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i]]);
			}
			self::$rssUrl =  helper::baseUrl() . $this->getUrl(0) . '/rss';
			self::$rssLabel = $this->getData(['module', $this->getUrl(0), 'config','feedsLabel']);
			// Valeurs en sortie
			$this->addOutput([
				'showBarEditButton' => true,
				'showPageContent' => true,
				'view' => 'index'
			]);
		}
	}
}