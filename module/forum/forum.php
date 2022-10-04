<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

class forum extends common {

	const VERSION = '0.1';
	const REALNAME = 'Forum';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	const EDIT_OWNER = 'owner';
	const EDIT_GROUP = 'group';
	const EDIT_ALL = 'all';

	public static $actions = [
		'add' => self::GROUP_MODERATOR,
		'comment' => self::GROUP_MODERATOR,
		'commentApprove' => self::GROUP_MODERATOR,
		'commentDelete' => self::GROUP_MODERATOR,
		'commentDeleteAll' => self::GROUP_MODERATOR,
		'config' => self::GROUP_MODERATOR,
		'option' => self::GROUP_MODERATOR,
		'delete' => self::GROUP_MODERATOR,
		'edit' => self::GROUP_MODERATOR,
		'index' => self::GROUP_VISITOR,
		'rss' => self::GROUP_VISITOR
	];

	public static $sujets = [];

	// Signature de l'sujet
	public static $sujetSignature = '';

	// Signature du Réponse
	public static $editCommentSignature = '';

	public static $comments = [];

	public static $nbCommentsApproved = 0;

	public static $commentsDelete;

	// Signatures des réponses déjà saisis
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
	public static $SujetsListed = [
		1 => '1 sujet',
		2 => '2 sujets',
		4 => '4 sujets',
		6 => '6 sujets',
		8 => '8 sujets',
		10 => '10 sujets',
		12 => '12 sujets'
	];

	//Paramètre longueur maximale des réponses en nb de caractères
	public static $commentLength = [
		100 => '100 signes',
		250 => '250 signes',
		500 => '500 signes',
		750 => '750 signes'
	];

	public static $sujetsLenght = [
		0 => 'Intégralité des sujets,  disposition moderne',
		200 => '200 signes',
		400 => '400 signes',
		600 => '600 signes',
		800 => '800 signes'
	];

	// Permissions d'un sujet
	public static $sujetConsent = [
		self::EDIT_ALL 		   => 'Tous les groupes',
		self::EDIT_GROUP       => 'Groupe du propriétaire',
		self::EDIT_OWNER       => 'Propriétaire'
	];


	// Nombre d'sujets dans la page de config:
	public static $itemsperPage = 8;


	public static $users = [];



	/**
	 * Mise à jour du module
	 * Appelée par les fonctions index et config
	 */
	private function update() {
		// Initialisation
		if (version_compare($this->getData(['module', $this->getUrl(0), 'config', 'versionData']), '0.0', '<') ) {
			$this->setData(['module', $this->getUrl(0), 'config', 'feeds', true]);
			$this->setData(['module', $this->getUrl(0), 'config', 'feedsLabel', 'Flux RSS']);
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData','4.0']);
		}
		// Version 5.0
		if (version_compare($this->getData(['module', $this->getUrl(0), 'config', 'versionData']), '5.0', '<') ) {
			$this->setData(['module', $this->getUrl(0), 'config', 'itemsperPage', 6]);
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData','5.0']);
		}
		// Version 6.0
		if (version_compare($this->getData(['module', $this->getUrl(0), 'config', 'versionData']), '6.0', '<') ) {
			$this->setData(['module', $this->getUrl(0), 'config', 'sujetsLenght', 0]);
			$this->setData(['module', $this->getUrl(0), 'config', 'commentMaxlength', 250]);
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData','6.0']);
		}
	}



	/**
	 * Flux RSS
	 */
	public function rss() {
		// Inclure les classes
		include_once 'module/blog/vendor/FeedWriter/Item.php';
		include_once 'module/blog/vendor/FeedWriter/Feed.php';
		include_once 'module/blog/vendor/FeedWriter/RSS2.php';
		include_once 'module/blog/vendor/FeedWriter/InvalidOperationException.php';

		date_default_timezone_set('UTC');
		$feeds = new \FeedWriter\RSS2();

		// En-tête
		$feeds->setTitle($this->getData (['page', $this->getUrl(0), 'title']));
		$feeds->setLink(helper::baseUrl() . $this->getUrl(0));
		$feeds->setDescription($this->getData (['page', $this->getUrl(0), 'metaDescription']));
		$feeds->setChannelElement('language', 'fr-FR');
		$feeds->setDate(date('r',time()));
		$feeds->addGenerator();
		// Corps des sujets
		$sujetIdsPublishedOns = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC');
		$sujetIdsStates = helper::arrayColumn($this->getData(['module', $this->getUrl(0),'posts']), 'state', 'SORT_DESC');
		foreach( $sujetIdsPublishedOns as $sujetId => $sujetPublishedOn ) {
			if( $sujetPublishedOn <= time() AND $sujetIdsStates[$sujetId]			 ) {
				// Miniature
				$parts = explode('/',$this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'picture']));
				$thumb = str_replace ($parts[(count($parts)-1)],'mini_' . $parts[(count($parts)-1)], $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'picture']));
				// Créer les sujets du flux
				$newsSujet = $feeds->createNewItem();
				// Signature de l'sujet
				$author = $this->signature($this->getData(['module', $this->getUrl(0),  'posts', $sujetId, 'userId']));
				$newsSujet->addElementArray([
					'title' 		=> $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'title']),
					'link' 			=> helper::baseUrl() .$this->getUrl(0) . '/' . $sujetId,
					'description' 	=> '<img src="' . helper::baseUrl() . self::FILE_DIR . $thumb
									 . '" alt="' . $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'title'])
									 . '" title="' . $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'title'])
									 . '" />' .
									 $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'content']),
				]);
				$newsSujet->setAuthor($author,'no@mail.com');
				$newsSujet->setId(helper::baseUrl() .$this->getUrl(0) . '/' . $sujetId);
				$newsSujet->setDate(date('r', $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'publishedOn'])));
				if ( file_exists($this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'picture'])) ) {
					$imageData = getimagesize(helper::baseUrl(false) .  self::FILE_DIR . 'thumb/' .  $thumb);
				$newsSujet->addEnclosure( helper::baseUrl(false) . self::FILE_DIR . 'thumb/'  . $thumb,
											$imageData[0] * $imageData[1],
											$imageData['mime']
					);
				}
				$feeds->addItem($newsSujet);
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
			// Modification de l'userId
			if($this->getUser('group') === self::GROUP_ADMIN){
				$newuserid = $this->getInput('blogAddUserId', helper::FILTER_STRING_SHORT, true);
			}
			else{
				$newuserid = $this->getUser('id');
			}
			// Incrémente l'id de l'sujet
			$sujetId = helper::increment($this->getInput('blogAddPermalink'), $this->getData(['page']));
			$sujetId = helper::increment($sujetId, (array) $this->getData(['module', $this->getUrl(0)]));
			$sujetId = helper::increment($sujetId, array_keys(self::$actions));
			// Crée l'sujet
			$this->setData(['module',
				$this->getUrl(0),
				'posts',
				$sujetId, [
					'content' => $this->getInput('blogAddContent', null),
					'publishedOn' => $this->getInput('blogAddPublishedOn', helper::FILTER_DATETIME, true),
					'state' => $this->getInput('blogAddState', helper::FILTER_BOOLEAN),
					'title' => $this->getInput('blogAddTitle', helper::FILTER_STRING_SHORT, true),
					'userId' => $newuserid,
					'editConsent' =>  $this->getInput('blogAddConsent') === self::EDIT_GROUP ? $this->getUser('group') : $this->getInput('blogAddConsent'),
					'commentMaxlength' => $this->getInput('blogAddCommentMaxlength'),
					'commentApproved' => $this->getInput('blogAddCommentApproved', helper::FILTER_BOOLEAN),
					'commentClose' => $this->getInput('blogAddCommentClose', helper::FILTER_BOOLEAN),
					'commentNotification'  => $this->getInput('blogAddCommentNotification', helper::FILTER_BOOLEAN),
					'commentGroupNotification' => $this->getInput('blogAddCommentGroupNotification', helper::FILTER_INT),
					'comment' => []
				]
			]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Nouvel sujet créé',
				'state' => true
			]);
		}
		// Liste des utilisateurs
		self::$users = helper::arrayColumn($this->getData(['user']), 'firstname');
		ksort(self::$users);
		foreach(self::$users as $userId => &$userFirstname) {
			$userFirstname = $userFirstname . ' ' . $this->getData(['user', $userId, 'lastname']);
		}
		unset($userFirstname);
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Nouveau sujet',
			'vendor' => [
				'flatpickr',
				'tinymce',
				'furl'
			],
			'view' => 'add'
		]);
	}

	/**
	 * Liste des réponses
	 */
	public function comment() {
		$comments = $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2),'comment']);
		self::$commentsDelete =	template::button('blogCommentDeleteAll', [
					'class' => 'blogCommentDeleteAll buttonRed',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/commentDeleteAll/' . $this->getUrl(2).'/' . $_SESSION['csrf'] ,
					'ico' => 'trash',
					'value' => 'Tout effacer'
		]);
		// Ids des réponses par ordre de création
		$commentIds = array_keys(helper::arrayColumn($comments, 'createdOn', 'SORT_DESC'));
		// Pagination
		$pagination = helper::pagination($commentIds, $this->getUrl(),$this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage']) );
		// Liste des pages
		self::$pages = $pagination['pages'];
		// Réponses en fonction de la pagination
		for($i = $pagination['first']; $i < $pagination['last']; $i++) {
			// Met en forme le tableau
			$comment = $comments[$commentIds[$i]];
			// Bouton d'approbation
			$buttonApproval = '';
			// Compatibilité avec les réponses des versions précédentes, les valider
			$comment['approval'] = array_key_exists('approval', $comment) === false ? true : $comment['approval'] ;
			if ( $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(2),'commentApproved']) === true) {
				$buttonApproval = template::button('blogCommentApproved' . $commentIds[$i], [
					'class' => $comment['approval'] === true ? 'blogCommentRejected buttonGreen' : 'blogCommentApproved buttonRed' ,
					'href' => helper::baseUrl() . $this->getUrl(0) . '/commentApprove/' . $this->getUrl(2) . '/' . $commentIds[$i] . '/' . $_SESSION['csrf'] ,
					'value' => $comment['approval'] === true ? 'A' : 'R'
				]);
			}
			self::$comments[] = [
				mb_detect_encoding(PHP81_BC\strftime('%d %B %Y - %H:%M', $comment['createdOn']), 'UTF-8', true)
				? PHP81_BC\strftime('%d %B %Y - %H:%M', $comment['createdOn'])
				: utf8_encode(PHP81_BC\strftime('%d %B %Y - %H:%M', $comment['createdOn'])),
				$comment['content'],
				$comment['userId'] ? $this->getData(['user', $comment['userId'], 'firstname']) . ' ' . $this->getData(['user', $comment['userId'], 'lastname']) : $comment['author'],
				$buttonApproval,
				template::button('blogCommentDelete' . $commentIds[$i], [
					'class' => 'blogCommentDelete buttonRed',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/commentDelete/' . $this->getUrl(2) . '/' . $commentIds[$i] . '/' . $_SESSION['csrf'] ,
					'value' => template::ico('trash')
				])
			];
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Gestion des réponses : '. $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(2), 'title']),
			'view' => 'comment'
		]);
	}

	/**
	 * Suppression de Réponse
	 */
	public function commentDelete() {
		// Le Réponse n'existe pas
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
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment/'.$this->getUrl(2),
				'notification' => 'Réponse supprimé',
				'state' => true
			]);
		}
	}

	/**
	 * Suppression de tous les réponses de l'sujet $this->getUrl(2)
	 */
	public function commentDeleteAll() {
		// Jeton incorrect
		if ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . $this->getUrl(0) . '/config',
				'notification' => 'Action non autorisée'
			]);
		}
		// Suppression
		else {
			$this->setData(['module', $this->getUrl(0),  'posts', $this->getUrl(2), 'comment',[] ]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment',
				'notification' => 'Réponses supprimés',
				'state' => true
			]);
		}
	}

	/**
	 * Approbation oou désapprobation de Réponse
	 */
	public function commentApprove() {
		// Le Réponse n'existe pas
		if($this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(2), 'comment', $this->getUrl(3)]) === null) {
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
		// Inversion du statut
		else {
			$approved = !$this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(2), 'comment', $this->getUrl(3), 'approval']) ;
			$this->setData(['module', $this->getUrl(0),  'posts', $this->getUrl(2), 'comment', $this->getUrl(3), [
				'author' => $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(2), 'comment', $this->getUrl(3), 'author']),
				'content' => $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(2), 'comment', $this->getUrl(3), 'content']),
				'createdOn' => $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(2), 'comment', $this->getUrl(3), 'createdOn']),
				'userId' => $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(2), 'comment', $this->getUrl(3), 'userId']),
				'approval' => $approved
			]]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment/'.$this->getUrl(2),
				'notification' =>  $approved ?  'Réponse approuvé' : 'Réponse rejeté',
				'state' => $approved
			]);
		}
	}

	/**
	 * Configuration
	 */
	public function config() {

		// Ids des sujets par ordre de publication
		$sujetIds = array_keys(helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC'));
		// Gestion des droits d'accès
		$filterData=[];
		foreach ($sujetIds as $key => $value) {
			if (
				(  // Propriétaire
					$this->getData(['module',  $this->getUrl(0), 'posts', $value,'editConsent']) === self::EDIT_OWNER
					AND ( $this->getData(['module',  $this->getUrl(0), 'posts', $value,'userId']) === $this->getUser('id')
					OR $this->getUser('group') === self::GROUP_ADMIN )
				)

				OR (
					// Groupe
					$this->getData(['module',  $this->getUrl(0), 'posts',  $value,'editConsent']) !== self::EDIT_OWNER
					AND $this->getUser('group') >=  $this->getData(['module',$this->getUrl(0), 'posts', $value,'editConsent'])
				)
				OR (
					// Tout le monde
					$this->getData(['module',  $this->getUrl(0), 'posts',  $value,'editConsent']) === self::EDIT_ALL
				)
			) {
				$filterData[] = $value;
			}
		}
		$sujetIds = $filterData;
		// Pagination
		$pagination = helper::pagination($sujetIds, $this->getUrl(),self::$itemsperPage);
		// Liste des pages
		self::$pages = $pagination['pages'];
		// Sujets en fonction de la pagination
		for($i = $pagination['first']; $i < $pagination['last']; $i++) {
			// Nombre de réponses à approuver et approuvés
			$approvals = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts',  $sujetIds[$i], 'comment' ]),'approval', 'SORT_DESC');
			if ( is_array($approvals) ) {
				$a = array_values($approvals);
				$toApprove = count(array_keys($a,false));
				$approved = count(array_keys($a,true));
			} else {
				$toApprove = 0;
				$approved = count($this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i],'comment']));
			}
			// Met en forme le tableau
			$date = mb_detect_encoding(PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0),  'posts', $sujetIds[$i], 'publishedOn'])), 'UTF-8', true)
				? PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'publishedOn']))
				: utf8_encode(PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'publishedOn'])));
			$heure =   mb_detect_encoding(PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'publishedOn'])), 'UTF-8', true)
			? PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'publishedOn']))
			: utf8_encode(PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'publishedOn'])));
			self::$sujets[] = [
				'<a href="' . helper::baseurl() . $this->getUrl(0) . '/' . $sujetIds[$i] . '" target="_blank" >' .
				$this->getData(['module', $this->getUrl(0),  'posts', $sujetIds[$i], 'title']) .
				'</a>',
				$date .' à '. $heure,
				self::$states[$this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'state'])],
				// Bouton pour afficher les réponses de l'sujet
				template::button('blogConfigComment' . $sujetIds[$i], [
					'class' => ($toApprove || $approved ) > 0 ?  '' : 'buttonGrey' ,
					'href' => ($toApprove || $approved ) > 0 ? helper::baseUrl() . $this->getUrl(0) . '/comment/' . $sujetIds[$i] : '',
					'value' => $toApprove > 0 ? $toApprove . '/' . $approved : $approved,
					'help' =>  ($toApprove || $approved ) > 0 ?  'Éditer  / Approuver les réponses' : ''
				]),
				template::button('blogConfigEdit' . $sujetIds[$i], [
					'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $sujetIds[$i] . '/' . $_SESSION['csrf'],
					'value' => template::ico('pencil'),
					'help' => 'Éditer l\'sujet'
				]),
				template::button('blogConfigDelete' . $sujetIds[$i], [
					'class' => 'blogConfigDelete buttonRed',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $sujetIds[$i] . '/' . $_SESSION['csrf'],
					'value' => template::ico('trash'),
					'help' => 'Effacer l\'sujet'
				])
			];
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Configuration du module',
			'view' => 'config'
		]);
	}

	public function option() {
		// Mise à jour des données de module
		$this->update();
		// Soumission du formulaire
		if($this->isPost()) {
			$this->setData(['module', $this->getUrl(0), 'config',[
				'feeds' 	 => $this->getInput('blogOptionShowFeeds',helper::FILTER_BOOLEAN),
				'feedsLabel' => $this->getInput('blogOptionFeedslabel',helper::FILTER_STRING_SHORT),
				'itemsperPage' => $this->getInput('blogOptionItemsperPage', helper::FILTER_INT,true),
				'sujetsLenght'=> $this->getInput('blogOptionSujetsLenght', helper::FILTER_INT),
				'versionData' => $this->getData(['module', $this->getUrl(0), 'config', 'versionData']),
				]]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/option',
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		} else {
			// Ids des sujets par ordre de publication
			$sujetIds = array_keys(helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'publishedOn', 'SORT_DESC'));
			// Gestion des droits d'accès
			$filterData=[];
			foreach ($sujetIds as $key => $value) {
				if (
					(  // Propriétaire
						$this->getData(['module',  $this->getUrl(0), 'posts', $value,'editConsent']) === self::EDIT_OWNER
						AND ( $this->getData(['module',  $this->getUrl(0), 'posts', $value,'userId']) === $this->getUser('id')
						OR $this->getUser('group') === self::GROUP_ADMIN )
					)

					OR (
						// Groupe
						$this->getData(['module',  $this->getUrl(0), 'posts',  $value,'editConsent']) !== self::EDIT_OWNER
						AND $this->getUser('group') >=  $this->getData(['module',$this->getUrl(0), 'posts', $value,'editConsent'])
					)
					OR (
						// Tout le monde
						$this->getData(['module',  $this->getUrl(0), 'posts',  $value,'editConsent']) === self::EDIT_ALL
					)
				) {
					$filterData[] = $value;
				}
			}
			$sujetIds = $filterData;
			// Pagination
			$pagination = helper::pagination($sujetIds, $this->getUrl(),$this->getData(['module', $this->getUrl(0),'config', 'itemsperPage']));
			// Liste des pages
			self::$pages = $pagination['pages'];
			// Sujets en fonction de la pagination
			for($i = $pagination['first']; $i < $pagination['last']; $i++) {
				// Nombre de réponses à approuver et approuvés
				$approvals = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts',  $sujetIds[$i], 'comment' ]),'approval', 'SORT_DESC');
				if ( is_array($approvals) ) {
					$a = array_values($approvals);
					$toApprove = count(array_keys($a,false));
					$approved = count(array_keys($a,true));
				} else {
					$toApprove = 0;
					$approved = count($this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i],'comment']));
				}
				// Met en forme le tableau
				$date = mb_detect_encoding(PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0),  'posts', $sujetIds[$i], 'publishedOn'])), 'UTF-8', true)
					? PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'publishedOn']))
					: utf8_encode(PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'publishedOn'])));
				$heure =   mb_detect_encoding(PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'publishedOn'])), 'UTF-8', true)
				? PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'publishedOn']))
				: utf8_encode(PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'publishedOn'])));
				self::$sujets[] = [
					'<a href="' . helper::baseurl() . $this->getUrl(0) . '/' . $sujetIds[$i] . '" target="_blank" >' .
					$this->getData(['module', $this->getUrl(0),  'posts', $sujetIds[$i], 'title']) .
					'</a>',
					$date .' à '. $heure,
					self::$states[$this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i], 'state'])],
					// Bouton pour afficher les réponses de l'sujet
					template::button('blogConfigComment' . $sujetIds[$i], [
						'class' => ($toApprove || $approved ) > 0 ?  '' : 'buttonGrey' ,
						'href' => ($toApprove || $approved ) > 0 ? helper::baseUrl() . $this->getUrl(0) . '/comment/' . $sujetIds[$i] : '',
						'value' => $toApprove > 0 ? $toApprove . '/' . $approved : $approved
					]),
					template::button('blogConfigEdit' . $sujetIds[$i], [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $sujetIds[$i] . '/' . $_SESSION['csrf'],
						'value' => template::ico('pencil')
					]),
					template::button('blogConfigDelete' . $sujetIds[$i], [
						'class' => 'blogConfigDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $sujetIds[$i] . '/' . $_SESSION['csrf'],
						'value' => template::ico('cancel')
					])
				];
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Options de configuration',
				'view' => 'option'
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
				'notification' => 'Sujet supprimé',
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
		// L'sujet n'existe pas
		if($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// L'sujet existe
		else {
			// Soumission du formulaire
			if($this->isPost()) {
				if($this->getUser('group') === self::GROUP_ADMIN){
					$newuserid = $this->getInput('blogEditUserId', helper::FILTER_STRING_SHORT, true);
				}
				else{
					$newuserid = $this->getUser('id');
				}
				$sujetId = $this->getInput('blogEditPermalink', null, true);
				// Incrémente le nouvel id de l'sujet
				if($sujetId !== $this->getUrl(2)) {
					$sujetId = helper::increment($sujetId, $this->getData(['page']));
					$sujetId = helper::increment($sujetId, $this->getData(['module', $this->getUrl(0),'posts']));
					$sujetId = helper::increment($sujetId, array_keys(self::$actions));
				}
				$this->setData(['module',
					$this->getUrl(0),
					'posts',
					$sujetId, [
						'title' => $this->getInput('blogEditTitle', helper::FILTER_STRING_SHORT, true),
						'comment' => $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(2), 'comment']),
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
						'commentNotification'  => $this->getInput('blogEditCommentNotification', helper::FILTER_BOOLEAN),
						'commentGroupNotification' => $this->getInput('blogEditCommentGroupNotification', helper::FILTER_INT)
					]
				]);
				// Supprime l'ancien sujet
				if($sujetId !== $this->getUrl(2)) {
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
			self::$users = helper::arrayColumn($this->getData(['user']), 'firstname');
			ksort(self::$users);
			foreach(self::$users as $userId => &$userFirstname) {
			// Les membres ne sont pas éditeurs, les exclure de la liste
				if ( $this->getData(['user', $userId, 'group']) < self::GROUP_MODERATOR) {
					unset(self::$users[$userId]);
				}
				$userFirstname = $userFirstname . ' ' . $this->getData(['user', $userId, 'lastname']) . ' (' .  self::$groupEdits[$this->getData(['user', $userId, 'group'])] . ')';
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
	public function index() {
		// Mise à jour des données de module
		$this->update();
		// Affichage d'un sujet
		if(
			$this->getUrl(1)
			// Protection pour la pagination, un ID ne peut pas être un entier, une page oui
			AND intval($this->getUrl(1)) === 0
		) {
			// L'sujet n'existe pas
			if($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1)]) === null) {
				// Valeurs en sortie
				$this->addOutput([
					'access' => false
				]);
			}
			// L'sujet existe
			else {
				// Soumission du formulaire
				if($this->isPost()) {
					// Check la captcha
					if(
						$this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
						//AND $this->getInput('blogSujetcaptcha', helper::FILTER_INT) !== $this->getInput('blogSujetcaptchaFirstNumber', helper::FILTER_INT) + $this->getInput('blogSujetcaptchaSecondNumber', helper::FILTER_INT))
						AND password_verify($this->getInput('blogSujetCaptcha', helper::FILTER_INT), $this->getInput('blogSujetCaptchaResult') ) === false )
					{
						self::$inputNotices['blogSujetCaptcha'] = 'Incorrect';
					}
					// Crée le Réponse
					$commentId = helper::increment(uniqid(), $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(1), 'comment']));
					$content = $this->getInput('blogSujetContent', false);
					$this->setData(['module', $this->getUrl(0),  'posts', $this->getUrl(1), 'comment', $commentId, [
						'author' => $this->getInput('blogSujetAuthor', helper::FILTER_STRING_SHORT, empty($this->getInput('blogSujetUserId')) ? TRUE : FALSE),
						'content' => $content,
						'createdOn' => time(),
						'userId' => $this->getInput('blogSujetUserId'),
						'approval' => !$this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'commentApproved']) // true Réponse publié false en attente de publication
					]]);
					// Envoi d'une notification aux administrateurs
					// Init tableau
					$to = [];
					// Liste des destinataires
					foreach($this->getData(['user']) as $userId => $user) {
						if ($user['group'] >= $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(1), 'commentGroupNotification']) ) {
							$to[] = $user['mail'];
							$firstname[] = $user['firstname'];
							$lastname[] = $user['lastname'];
						}
					}
					// Envoi du mail $sent code d'erreur ou de réussite
					$notification = $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(1), 'commentApproved']) === true ? 'Réponse déposé en attente d\'approbation': 'Réponse déposé';
					if ($this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(1), 'commentNotification']) === true) {
						$error = 0;
						foreach($to as $key => $adress){
							$sent = $this->sendMail(
								$adress,
								'Nouveau Réponse déposé',
								'Bonjour' . ' <strong>' . $firstname[$key] . ' ' . $lastname[$key] . '</strong>,<br><br>' .
								'L\'sujet <a href="' . helper::baseUrl() . $this->getUrl(0) . '/	' . $this->getUrl(1) . '">' . $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'title']) . '</a> a  reçu un nouveau Réponse.<br><br>',
								''
							);
							if( $sent === false) $error++;
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
				// Ids des réponses approuvés par ordre de publication
				$commentsApproved = $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment']);
				if ($commentsApproved) {
					foreach( $commentsApproved as $key => $value){
						if($value['approval']===false) unset($commentsApproved[$key]);
					}
					// Ligne suivante si affichage du nombre total de réponses approuvés sous l'sujet
					self::$nbCommentsApproved = count($commentsApproved);
				}
				$commentIds = array_keys(helper::arrayColumn($commentsApproved, 'createdOn', 'SORT_DESC'));
				// Pagination
				$pagination = helper::pagination($commentIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage']),'#comment');
				// Liste des pages
				self::$pages = $pagination['pages'];
				// Signature de l'sujet
				self::$sujetSignature = $this->signature($this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(1), 'userId']));
				// Signature du Réponse édité
				if($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')) {
					self::$editCommentSignature = $this->signature($this->getUser('id'));
				}
				// Réponses en fonction de la pagination
				for($i = $pagination['first']; $i < $pagination['last']; $i++) {
					// Signatures des réponses
					$e = $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(1), 'comment', $commentIds[$i],'userId']);
					if ($e) {
						self::$commentsSignature[$commentIds[$i]] = $this->signature($e);
					} else {
						self::$commentsSignature[$commentIds[$i]] = $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(1), 'comment', $commentIds[$i],'author']);
					}
					// Données du Réponse si approuvé
					if ($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment', $commentIds[$i],'approval']) === true ) {
						self::$comments[$commentIds[$i]] = $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'comment', $commentIds[$i]]);
					}
				}
				// Valeurs en sortie
				$this->addOutput([
					'showBarEditButton' => true,
					'title' => $this->getData(['module', $this->getUrl(0),  'posts', $this->getUrl(1), 'title']),
					'vendor' => [
						'tinymce'
					],
					'view' => 'sujet'
				]);
			}

		}
		// Liste des sujets
		else {
			// Ids des sujets par ordre de publication
			$sujetIdsPublishedOns = helper::arrayColumn($this->getData(['module', $this->getUrl(0),'posts']), 'publishedOn', 'SORT_DESC');
			$sujetIdsStates = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'posts']), 'state', 'SORT_DESC');
			$sujetIds = [];
			foreach($sujetIdsPublishedOns as $sujetId => $sujetPublishedOn) {
				if($sujetPublishedOn <= time() AND $sujetIdsStates[$sujetId]) {
					$sujetIds[] = $sujetId;
					// Nombre de réponses approuvés par sujet
					self::$comments [$sujetId] = count ( $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'comment']));
				}
			}
			// Pagination
			$pagination = helper::pagination($sujetIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage']));
			// Liste des pages
			self::$pages = $pagination['pages'];
			// Sujets en fonction de la pagination
			for($i = $pagination['first']; $i < $pagination['last']; $i++) {
				self::$sujets[$sujetIds[$i]] = $this->getData(['module', $this->getUrl(0), 'posts', $sujetIds[$i]]);
			}
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
	public function signature($userId) {
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
