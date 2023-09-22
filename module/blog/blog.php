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

class blog extends common
{

	const VERSION = '7.1';
	const REALNAME = 'Blog';
	const DELETE = true;
	const UPDATE = '0.0';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	const EDIT_OWNER = 'owner';
	const EDIT_GROUP = 'group';
	const EDIT_ALL = 'all';

	public static $actions = [
		'add' => self::GROUP_EDITOR,
		'comment' => self::GROUP_EDITOR,
		'commentApprove' => self::GROUP_EDITOR,
		'commentDelete' => self::GROUP_EDITOR,
		'commentDeleteAll' => self::GROUP_EDITOR,
		'config' => self::GROUP_EDITOR,
		'option' => self::GROUP_EDITOR,
		'delete' => self::GROUP_EDITOR,
		'edit' => self::GROUP_EDITOR,
		'index' => self::GROUP_VISITOR,
		'rss' => self::GROUP_VISITOR
	];

	public static $articles = [];

	// Signature de l'article
	public static $articleSignature = '';

	// Signature du commentaire
	public static $editCommentSignature = '';

	public static $comments = [];

	public static $nbCommentsApproved = 0;

	public static $commentsDelete;

	// Signatures des commentaires déjà saisis
	public static $commentsSignature = [];

	public static $pages;

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

	// Nombre d'objets par page
	public static $ArticlesListed = [
		1 => '1 article',
		2 => '2 articles',
		4 => '4 articles',
		6 => '6 articles',
		8 => '8 articles',
		10 => '10 articles',
		12 => '12 articles'
	];

	//Paramètre longueur maximale des commentaires en nb de caractères
	public static $commentsLength = [
		100 => '100 signes',
		250 => '250 signes',
		500 => '500 signes',
		750 => '750 signes'
	];

	public static $articlesLenght = [
		0 => 'Articles complets',
		600 => '600 signes',
		800 => '800 signes',
		1000 => '1000 signes',
		1200 => '1200 signes',
		1400 => '1400 signes',
		1600 => '1600 signes',
		1800 => '1800 signes',
	];

	public static $articlesLayout = [
		false => 'Classique',
		true => 'Moderne',
	];

	// Permissions d'un article
	public static $articleConsent = [
		self::EDIT_ALL => 'Tous les groupes',
		self::EDIT_GROUP => 'Groupe du propriétaire',
		self::EDIT_OWNER => 'Propriétaire'
	];

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

	// Nombre d'articles dans la page de config:
	public static $itemsperPage = 8;


	public static $users = [];



	/**
	 * Mise à jour du module
	 * Appelée par les fonctions index et config
	 */
	private function update()
	{
		// Initialisation
		if (is_null($this->getData(['module', $this->getUrl(0), 'config', 'versionData']))) {
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '0.0']);
		}
		// Version 5.0
		if (version_compare($this->getData(['module', $this->getUrl(0), 'config', 'versionData']), '5.0', '<')) {
			$this->setData(['module', $this->getUrl(0), 'config', 'itemsperPage', 6]);
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '5.0']);
		}
		// Version 6.0
		if (version_compare($this->getData(['module', $this->getUrl(0), 'config', 'versionData']), '6.0', '<')) {
			$this->setData(['module', $this->getUrl(0), 'config', 'feeds', false]);
			$this->setData(['module', $this->getUrl(0), 'config', 'feedsLabel', '']);
			$this->setData(['module', $this->getUrl(0), 'config', 'articlesLenght', 0]);
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '6.0']);
		}
		// Version 6.5
		if (version_compare($this->getData(['module', $this->getUrl(0), 'config', 'versionData']), '6.5', '<')) {
			$this->setData(['module', $this->getUrl(0), 'config', 'dateFormat', '%d %B %Y']);
			$this->setData(['module', $this->getUrl(0), 'config', 'timeFormat', '%H:%M']);
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '6.5']);
		}
	}




	/**
	 * Flux RSS
	 */
	public function rss()
	{
		// Inclure les classes
		include_once 'module/blog/vendor/FeedWriter/Item.php';
		include_once 'module/blog/vendor/FeedWriter/Feed.php';
		include_once 'module/blog/vendor/FeedWriter/RSS2.php';
		include_once 'module/blog/vendor/FeedWriter/InvalidOperationException.php';

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
		$articleIdsPublishedOns = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC');
		$articleIdsStates = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'state', 'SORT_DESC');
		foreach ($articleIdsPublishedOns as $articleId => $articlePublishedOn) {
			if ($articlePublishedOn <= time() and $articleIdsStates[$articleId]) {
				// Miniature
				$parts = pathinfo($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'picture']));
				$thumb = 'mini_' . $parts['basename'];
				// Créer les articles du flux
				$newsArticle = $feeds->createNewItem();
				// Signature de l'article
				$author = $this->signature($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'userId']));
				$newsArticle->addElementArray([
					'title' => $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'title']),
					'link' => helper::baseUrl() . $this->getUrl(0) . '/' . $articleId,
					'description' => '<img src="' . helper::baseUrl(false) . self::FILE_DIR . 'thumb/' . $thumb
					. '" alt="' . $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'title'])
					. '" title="' . $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'title'])
					. '" />' .
					$this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'content']),
				]);
				$newsArticle->setAuthor($author, 'no@mail.com');
				$newsArticle->setId(helper::baseUrl() . $this->getUrl(0) . '/' . $articleId);
				$newsArticle->setDate(date('r', $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'publishedOn'])));
				if (file_exists($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'picture']))) {
					$imageData = getimagesize(helper::baseUrl(false) . self::FILE_DIR . 'thumb/' . $thumb);
					$newsArticle->addEnclosure(
						helper::baseUrl(false) . self::FILE_DIR . 'thumb/' . $thumb,
						$imageData[0] * $imageData[1],
						$imageData['mime']
					);
				}
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
	public function add()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Modification de l'userId
			if ($this->getUser('group') === self::GROUP_ADMIN) {
				$newuserid = $this->getInput('blogAddUserId', helper::FILTER_STRING_SHORT, true);
			} else {
				$newuserid = $this->getUser('id');
			}
			// Incrémente l'id de l'article
			$articleId = helper::increment($this->getInput('blogAddPermalink'), $this->getData(['page']));
			$articleId = helper::increment($articleId, (array) $this->getData(['module', $this->getUrl(0)]));
			$articleId = helper::increment($articleId, array_keys(self::$actions));
			// Crée l'article
			$this->setData([
				'module',
				$this->getUrl(0),
				'posts',
				$articleId,
				[
					'content' => $this->getInput('blogAddContent', null),
					'picture' => $this->getInput('blogAddPicture', helper::FILTER_STRING_SHORT),
					'hidePicture' => $this->getInput('blogAddHidePicture', helper::FILTER_BOOLEAN),
					'pictureSize' => $this->getInput('blogAddPictureSize', helper::FILTER_STRING_SHORT),
					'picturePosition' => $this->getInput('blogAddPicturePosition', helper::FILTER_STRING_SHORT),
					'publishedOn' => $this->getInput('blogAddPublishedOn', helper::FILTER_DATETIME, true),
					'state' => $this->getInput('blogAddState', helper::FILTER_BOOLEAN),
					'title' => $this->getInput('blogAddTitle', helper::FILTER_STRING_SHORT, true),
					'userId' => $newuserid,
					'editConsent' => $this->getInput('blogAddConsent') === self::EDIT_GROUP ? $this->getUser('group') : $this->getInput('blogAddConsent'),
					'commentMaxlength' => $this->getInput('blogAddCommentMaxlength'),
					'commentApproved' => $this->getInput('blogAddCommentApproved', helper::FILTER_BOOLEAN),
					'commentClose' => $this->getInput('blogAddCommentClose', helper::FILTER_BOOLEAN),
					'commentNotification' => $this->getInput('blogAddCommentNotification', helper::FILTER_BOOLEAN),
					'commentGroupNotification' => $this->getInput('blogAddCommentGroupNotification', helper::FILTER_INT),
					'comment' => []
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
				'tinymce',
				'furl'
			],
			'view' => 'add'
		]);
	}

	/**
	 * Liste des commentaires
	 */
	public function comment()
	{
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			$comments = $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment']);
			self::$commentsDelete = template::button('blogCommentDeleteAll', [
				'class' => 'blogCommentDeleteAll buttonRed',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/commentDeleteAll/' . $this->getUrl(2),
				'value' => 'Tout effacer'
			]);
			// Ids des commentaires par ordre de création
			$commentIds = array_keys(helper::arrayColumn($comments, 'createdOn', 'SORT_DESC'));
			// Pagination
			$pagination = helper::pagination($commentIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage']));
			// Liste des pages
			self::$pages = $pagination['pages'];
			// Commentaires en fonction de la pagination
			for ($i = $pagination['first']; $i < $pagination['last']; $i++) {
				// Met en forme le tableau
				$comment = $comments[$commentIds[$i]];
				// Bouton d'approbation
				$buttonApproval = '';
				// Compatibilité avec les commentaires des versions précédentes, les valider
				$comment['approval'] = array_key_exists('approval', $comment) === false ? true : $comment['approval'];
				if ($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'commentApproved']) === true) {
					$buttonApproval = template::button('blogCommentApproved' . $commentIds[$i], [
						'class' => $comment['approval'] === true ? 'blogCommentRejected buttonGreen' : 'blogCommentApproved buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/commentApprove/' . $this->getUrl(2) . '/' . $commentIds[$i],
						'value' => $comment['approval'] === true ? 'A' : 'R',
						'help' => $comment['approval'] === true ? 'Approuvé' : 'Rejeté',
					]);
				}
				self::$dateFormat = $this->getData(['module', $this->getUrl(0), 'config', 'dateFormat']);
				self::$timeFormat = $this->getData(['module', $this->getUrl(0), 'config', 'timeFormat']);
				self::$comments[] = [
					helper::dateUTF8(self::$dateFormat, $comment['createdOn']) . ' - ' . helper::dateUTF8(self::$timeFormat, $comment['createdOn']),
					$comment['content'],
					$comment['userId'] ? $this->getData(['user', $comment['userId'], 'firstname']) . ' ' . $this->getData(['user', $comment['userId'], 'lastname']) : $comment['author'],
					$buttonApproval,
					template::button('blogCommentDelete' . $commentIds[$i], [
						'class' => 'blogCommentDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/commentDelete/' . $this->getUrl(2) . '/' . $commentIds[$i],
						'value' => template::ico('trash')
					])
				];
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => helper::translate('Gestion des commentaires'),
				'view' => 'comment'
			]);
		}
	}

	/**
	 * Suppression de commentaire
	 */
	public function commentDelete()
	{
		// Le commentaire n'existe pas
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true ||
			$this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment', $this->getUrl(3)]) === null
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Suppression
		else {
			$this->deleteData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment', $this->getUrl(3)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment/' . $this->getUrl(2),
				'notification' => helper::translate('Commentaire supprimé'),
				'state' => true
			]);
		}
	}

	/**
	 * Suppression de tous les commentairess de l'article $this->getUrl(2)
	 */
	public function commentDeleteAll()
	{
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Suppression
		else {
			$this->setData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment', []]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment',
				'notification' => helper::translate('Commentaires supprimés'),
				'state' => true
			]);
		}
	}

	/**
	 * Approbation oou désapprobation de commentaire
	 */
	public function commentApprove()
	{
		// Le commentaire n'existe pas
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true ||
			$this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment', $this->getUrl(3)]) === null
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Inversion du statut
		else {
			$approved = !$this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment', $this->getUrl(3), 'approval']);
			$this->setData([
				'module', $this->getUrl(0),
				'posts', $this->getUrl(2),
				'comment', $this->getUrl(3),
				[
					'author' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment', $this->getUrl(3), 'author']),
					'content' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment', $this->getUrl(3), 'content']),
					'createdOn' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment', $this->getUrl(3), 'createdOn']),
					'userId' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment', $this->getUrl(3), 'userId']),
					'approval' => $approved
				]
			]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment/' . $this->getUrl(2),
				'notification' => $approved ? helper::translate('Commentaire approuvé') : helper::translate('Commentaire rejeté'),
				'state' => $approved
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
		// Ids des articles par ordre de publication
		$articleIds = array_keys(helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC'));
		// Gestion des droits d'accès
		$filterData = [];
		foreach ($articleIds as $key => $value) {
			if (
				( // Propriétaire
					$this->getData(['module', $this->getUrl(0), 'posts', $value, 'editConsent']) === self::EDIT_OWNER
					and ($this->getData(['module', $this->getUrl(0), 'posts', $value, 'userId']) === $this->getUser('id')
						or $this->getUser('group') === self::GROUP_ADMIN)
				)

				or (
					// Groupe
					$this->getData(['module', $this->getUrl(0), 'posts', $value, 'editConsent']) !== self::EDIT_OWNER
					and $this->getUser('group') >= $this->getData(['module', $this->getUrl(0), 'posts', $value, 'editConsent'])
				)
				or (
					// Tout le monde
					$this->getData(['module', $this->getUrl(0), 'posts', $value, 'editConsent']) === self::EDIT_ALL
				)
			) {
				$filterData[] = $value;
			}
		}
		$articleIds = $filterData;
		// Pagination
		$pagination = helper::pagination($articleIds, $this->getUrl(), self::$itemsperPage);
		// Liste des pages
		self::$pages = $pagination['pages'];
		// Format de temps
		self::$dateFormat = $this->getData(['module', $this->getUrl(0), 'config', 'dateFormat']);
		self::$timeFormat = $this->getData(['module', $this->getUrl(0), 'config', 'timeFormat']);
		// Articles en fonction de la pagination
		for ($i = $pagination['first']; $i < $pagination['last']; $i++) {
			// Nombre de commentaires à approuver et approuvés
			$approvals = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'comment']), 'approval', 'SORT_DESC');
			if (is_array($approvals)) {
				$a = array_values($approvals);
				$toApprove = count(array_keys($a, false));
				$approved = count(array_keys($a, true));
			} else {
				$toApprove = 0;
				$approved = count($this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'comment']));
			}
			// Met en forme le tableau
			self::$articles[] = [
				'<a href="' . helper::baseurl() . $this->getUrl(0) . '/' . $articleIds[$i] . '" target="_blank" >' .
				$this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'title']) .
				'</a>',
				helper::dateUTF8(self::$dateFormat, $this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'publishedOn'])) . ' - ' . helper::dateUTF8(self::$timeFormat, $this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'publishedOn'])),
				self::$states[$this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i], 'state'])],
				// Bouton pour afficher les commentaires de l'article
				template::button('blogConfigComment' . $articleIds[$i], [
					'class' => ($toApprove || $approved) > 0 ? '' : 'buttonGrey',
					'href' => ($toApprove || $approved) > 0 ? helper::baseUrl() . $this->getUrl(0) . '/comment/' . $articleIds[$i] : '',
					'value' => $toApprove > 0 ? $toApprove . '/' . $approved : $approved,
					'help' => ($toApprove || $approved) > 0 ? 'Éditer  / Approuver un commentaire' : ''
				]),
				template::button('blogConfigEdit' . $articleIds[$i], [
					'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $articleIds[$i],
					'value' => template::ico('pencil')
				]),
				template::button('blogConfigDelete' . $articleIds[$i], [
					'class' => 'blogConfigDelete buttonRed',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $articleIds[$i],
					'value' => template::ico('trash')
				])
			];
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Configuration du module'),
			'view' => 'config'
		]);
	}

	public function option()
	{

		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			$this->setData([
				'module', $this->getUrl(0),
				'config',
				[
					'feeds' => $this->getInput('blogOptionShowFeeds', helper::FILTER_BOOLEAN),
					'feedsLabel' => $this->getInput('blogOptionFeedslabel', helper::FILTER_STRING_SHORT),
					'layout' => $this->getInput('blogOptionArticlesLayout', helper::FILTER_BOOLEAN),
					'articlesLenght' => $this->getInput('blogOptionArticlesLayout', helper::FILTER_BOOLEAN) === false ? $this->getInput('blogOptionArticlesLenght', helper::FILTER_INT) : 0,
					'itemsperPage' => $this->getInput('blogOptionItemsperPage', helper::FILTER_INT, true),
					'dateFormat' => $this->getInput('blogOptionDateFormat'),
					'timeFormat' => $this->getInput('blogOptionTimeFormat'),
					'versionData' => $this->getData(['module', $this->getUrl(0), 'config', 'versionData']),
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
			'title' => helper::translate('Options de configuration'),
			'view' => 'option'
		]);
	}


	/**
	 * Suppression
	 */
	public function delete()
	{
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
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// L'article n'existe pas
		if ($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// L'article existe
		else {
			// Soumission du formulaire
			if (
				$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
				$this->isPost()
			) {
				if ($this->getUser('group') === self::GROUP_ADMIN) {
					$newuserid = $this->getInput('blogEditUserId', helper::FILTER_STRING_SHORT, true);
				} else {
					$newuserid = $this->getUser('id');
				}
				$articleId = $this->getInput('blogEditPermalink', null, true);
				// Incrémente le nouvel id de l'article
				if ($articleId !== $this->getUrl(2)) {
					$articleId = helper::increment($articleId, $this->getData(['page']));
					$articleId = helper::increment($articleId, $this->getData(['module', $this->getUrl(0), 'posts']));
					$articleId = helper::increment($articleId, array_keys(self::$actions));
				}
				$this->setData([
					'module',
					$this->getUrl(0),
					'posts',
					$articleId,
					[
						'title' => $this->getInput('blogEditTitle', helper::FILTER_STRING_SHORT, true),
						'comment' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment']),
						'content' => $this->getInput('blogEditContent', null),
						'picture' => $this->getInput('blogEditPicture', helper::FILTER_STRING_SHORT),
						'hidePicture' => $this->getInput('blogEditHidePicture', helper::FILTER_BOOLEAN),
						'pictureSize' => $this->getInput('blogEditPictureSize', helper::FILTER_STRING_SHORT),
						'picturePosition' => $this->getInput('blogEditPicturePosition', helper::FILTER_STRING_SHORT),
						'publishedOn' => $this->getInput('blogEditPublishedOn', helper::FILTER_DATETIME, true),
						'state' => $this->getInput('blogEditState', helper::FILTER_BOOLEAN),
						'userId' => $newuserid,
						'editConsent' => $this->getInput('blogEditConsent') === self::EDIT_GROUP ? $this->getUser('group') : $this->getInput('blogEditConsent'),
						'commentMaxlength' => $this->getInput('blogEditCommentMaxlength'),
						'commentApproved' => $this->getInput('blogEditCommentApproved', helper::FILTER_BOOLEAN),
						'commentClose' => $this->getInput('blogEditCommentClose', helper::FILTER_BOOLEAN),
						'commentNotification' => $this->getInput('blogEditCommentNotification', helper::FILTER_BOOLEAN),
						'commentGroupNotification' => $this->getInput('blogEditCommentGroupNotification', helper::FILTER_INT)
					]
				]);
				// Supprime l'ancien article
				if ($articleId !== $this->getUrl(2)) {
					$this->deleteData(['module', $this->getUrl(0), 'posts', $this->getUrl(2)]);
				}
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
				// Les membres ne sont pas éditeurs, les exclure de la liste
				if ($this->getData(['user', $userId, 'group']) < self::GROUP_EDITOR) {
					unset(self::$users[$userId]);
				}
				$userFirstname = $userFirstname . ' ' . $this->getData(['user', $userId, 'lastname']) . ' (' . self::$groupEdits[$this->getData(['user', $userId, 'group'])] . ')';
			}
			unset($userFirstname);
			// Valeurs en sortie
			$this->addOutput([
				'title' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'title']),
				'vendor' => [
					'flatpickr',
					'tinymce',
					'furl'
				],
				'view' => 'edit'
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
				// Soumission du formulaire
				if (
					$this->isPost()
				) {
					// Check la captcha
					if (
						$this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
						//AND $this->getInput('blogArticlecaptcha', helper::FILTER_INT) !== $this->getInput('blogArticlecaptchaFirstNumber', helper::FILTER_INT) + $this->getInput('blogArticlecaptchaSecondNumber', helper::FILTER_INT))
						and password_verify($this->getInput('blogArticleCaptcha', helper::FILTER_INT), $this->getInput('blogArticleCaptchaResult')) === false
					) {
						self::$inputNotices['blogArticleCaptcha'] = 'Incorrect';
					}
					// Crée le commentaire
					$commentId = helper::increment(uniqid(), $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment']));
					$content = $this->getInput('blogArticleContent', false);
					$this->setData([
						'module', $this->getUrl(0),
						'posts', $this->getUrl(1),
						'comment',
						$commentId,
						[
							'author' => $this->getInput('blogArticleAuthor', helper::FILTER_STRING_SHORT, empty($this->getInput('blogArticleUserId')) ? TRUE : FALSE),
							'content' => $content,
							'createdOn' => time(),
							'userId' => $this->getInput('blogArticleUserId'),
							'approval' => !$this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'commentApproved']) // true commentaire publié false en attente de publication
						]
					]);
					// Envoi d'une notification aux administrateurs
					// Init tableau
					$to = [];
					// Liste des destinataires
					foreach ($this->getData(['user']) as $userId => $user) {
						if ($user['group'] >= $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'commentGroupNotification'])) {
							$to[] = $user['mail'];
							$firstname[] = $user['firstname'];
							$lastname[] = $user['lastname'];
						}
					}
					// Envoi du mail $sent code d'erreur ou de réussite
					$notification = $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'commentApproved']) === true ? 'Commentaire déposé en attente d\'approbation' : 'Commentaire déposé';
					if ($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'commentNotification']) === true) {
						$error = 0;
						foreach ($to as $key => $adress) {
							$sent = $this->sendMail(
								$adress,
								'Nouveau commentaire déposé',
								'Bonjour' . ' <strong>' . $firstname[$key] . ' ' . $lastname[$key] . '</strong>,<br><br>' .
								'L\'article <a href="' . helper::baseUrl() . $this->getUrl(0) . '/	' . $this->getUrl(1) . '">' . $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'title']) . '</a> a  reçu un nouveau commentaire.<br><br>',
								null,
								$this->getData(['config', 'smtp', 'from'])
							);
							if ($sent === false)
								$error++;
						}
						// Valeurs en sortie
						$this->addOutput([
							'redirect' => helper::baseUrl() . $this->getUrl() . '#comment',
							'notification' => ($error === 0 ? $notification . '<br/>Une notification a été envoyée.' : $notification . '<br/> Erreur de notification : ' . $sent),
							'state' => ($sent === true ? true : null)
						]);
					} else {
						// Valeurs en sortie
						$this->addOutput([
							'redirect' => helper::baseUrl() . $this->getUrl() . '#comment',
							'notification' => $notification,
							'state' => true
						]);
					}
				}
				// Ids des commentaires approuvés par ordre de publication
				$commentsApproved = $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment']);
				if ($commentsApproved) {
					foreach ($commentsApproved as $key => $value) {
						if ($value['approval'] === false)
							unset($commentsApproved[$key]);
					}
					// Ligne suivante si affichage du nombre total de commentaires approuvés sous l'article
					self::$nbCommentsApproved = count($commentsApproved);
				}
				$commentIds = array_keys(helper::arrayColumn($commentsApproved, 'createdOn', 'SORT_DESC'));
				// Pagination
				$pagination = helper::pagination($commentIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage']), '#comment');
				// Liste des pages
				self::$pages = $pagination['pages'];
				// Signature de l'article
				self::$articleSignature = $this->signature($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'userId']));
				// Signature du commentaire édité
				if ($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')) {
					self::$editCommentSignature = $this->signature($this->getUser('id'));
				}
				// Commentaires en fonction de la pagination
				for ($i = $pagination['first']; $i < $pagination['last']; $i++) {
					// Signatures des commentaires
					$e = $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment', $commentIds[$i], 'userId']);
					if ($e) {
						self::$commentsSignature[$commentIds[$i]] = $this->signature($e);
					} else {
						self::$commentsSignature[$commentIds[$i]] = $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment', $commentIds[$i], 'author']);
					}
					// Données du commentaire si approuvé
					if ($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment', $commentIds[$i], 'approval']) === true) {
						self::$comments[$commentIds[$i]] = $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment', $commentIds[$i]]);
					}
				}
				// Format de temps
				self::$dateFormat = $this->getData(['module', $this->getUrl(0), 'config', 'dateFormat']);
				self::$timeFormat = $this->getData(['module', $this->getUrl(0), 'config', 'timeFormat']);
				// Valeurs en sortie
				$this->addOutput([
					'showBarEditButton' => true,
					'title' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'title']),
					'vendor' => [
						'tinymce'
					],
					'view' => 'article'
				]);
			}
		}
		// Liste des articles
		else {
			// Ids des articles par ordre de publication
			$articleIdsPublishedOns = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC');
			$articleIdsStates = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'state', 'SORT_DESC');
			$articleIds = [];
			foreach ($articleIdsPublishedOns as $articleId => $articlePublishedOn) {
				if ($articlePublishedOn <= time() and $articleIdsStates[$articleId]) {
					$articleIds[] = $articleId;
					// Nombre de commentaires approuvés par article
					self::$comments[$articleId] = 0;
					if (is_array($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'comment']))) {
						foreach ($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'comment']) as $commentId => $commentValue) {
							if ($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'comment', $commentId, 'approval'])) {
								self::$comments[$articleId] = self::$comments[$articleId] + 1;
							}
						}
					}
				}
			}
			// Pagination
			$pagination = helper::pagination($articleIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage']), '#article');
			// Liste des pages
			self::$pages = $pagination['pages'];
			// Articles en fonction de la pagination
			for ($i = $pagination['first']; $i < $pagination['last']; $i++) {
				self::$articles[$articleIds[$i]] = $this->getData(['module', $this->getUrl(0), 'posts', $articleIds[$i]]);
			}
			// Format de temps
			self::$dateFormat = $this->getData(['module', $this->getUrl(0), 'config', 'dateFormat']);
			self::$timeFormat = $this->getData(['module', $this->getUrl(0), 'config', 'timeFormat']);
			// Valeurs en sortie
			$this->addOutput([
				'showBarEditButton' => true,
				'showPageContent' => true,
				'view' => 'index'
			]);
		}
	}

	/**
	 * Retourne la signature d'un utilisateur
	 */
	public function signature($userId)
	{
		switch ($this->getData(['user', $userId, 'signature'])) {
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