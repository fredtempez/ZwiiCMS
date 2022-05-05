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

class download extends common {

	const VERSION = '2.5';
	const REALNAME = 'Téléchargement';
	const DELETE = true;
	const UPDATE = '0.0';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	// Constantes du module
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
		'delete' => self::GROUP_MODERATOR,
		'edit' => self::GROUP_MODERATOR,
		'stats' => self::GROUP_MODERATOR,
		'statsDeleteAll' => self::GROUP_MODERATOR,
		'categories' => self::GROUP_MODERATOR,
		'categoryEdit' => self::GROUP_MODERATOR,
		'categoryDelete' => self::GROUP_MODERATOR,
		'index' => self::GROUP_VISITOR,
		'rss' => self::GROUP_VISITOR,
		'downloadFile' => self::GROUP_VISITOR,
		'list' =>self::GROUP_VISITOR
	];

	public static $items = [];

	// Signature de l'item
	public static $itemSignature = '';

	// Signature du commentaire
	public static $editCommentSignature = '';

	public static $comments = [];

	public static $nbCommentsApproved = 0;

	public static $commentsDelete;

	// Signatures des commentaires déjà saisis
	public static $commentsSignature = [];

	public static $pages;

	// Nombre de téléchargements
	public static $statSum = 0;

	public static $states = [
		false => 'Brouillon',
		true => 'Publié'
	];

	// Liste des catégories
	public static $categories = [];

	public static $allCategories = '';

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

	//Paramètre longueur maximale des commentaires en nb de caractères
	public static $commentLength = [
		'500' => '500',
		'1000' => '1000',
		'2000' => '2000',
		'5000' => '5000',
		'10000' => '10000'
	];

	// Nombre d'objets par page
	public static $ItemsList = [
		4 => '4 articles',
		8 => '8 articles',
		12 => '12 articles',
		16 => '16 articles',
		22 => '22  articles'
	];

	// Permissions d'un item
	public static $itemConsent = [
		self::EDIT_ALL 		   => 'Tous les groupes',
		self::EDIT_GROUP       => 'Groupe du propriétaire',
		self::EDIT_OWNER       => 'Propriétaire'
	];


	public static $licenses = [
		'none'	=> 'Non définie',
		'cc' 	=> 'Licence libre Creative Common, partage autorisé',
		'gnu' 	=> 'Licence libre GNU, partage autorisé',
		'mit' 	=> 'Licence libre MIT, partage autorisé',
		'owner'	=> 'Licence Propriétaire'
	];

	public static $ressourceType = [
		'file' 		=> 'Fichier',
		'url'  		=> 'URL',
		'content'   => 'Intégrée'
	];

	public static $users = [];

	/**
	 * Mise à jour du module
	 * Appelée par les fonctions index et config
	 */
	private function update() {
		// Version 5.0
		if (version_compare($this->getData(['module', $this->getUrl(0), 'config', 'versionData']), '1.1', '<') ) {
			$this->setData(['module', $this->getUrl(0), 'config', 'itemsperPage', 6]);
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData','1.2']);
		}
	}

	/**
	 * Flux RSS
	 */
	public function rss() {
		// Inclure les classes
		include_once 'module/download/vendor/FeedWriter/Item.php';
		include_once 'module/download/vendor/FeedWriter/Feed.php';
		include_once 'module/download/vendor/FeedWriter/RSS2.php';
		include_once 'module/download/vendor/FeedWriter/InvalidOperationException.php';

		date_default_timezone_set('UTC');
		$feeds = new \FeedWriter\RSS2();

		// En-tête
		$feeds->setTitle($this->getData (['page', $this->getUrl(0), 'title']));
		$feeds->setLink(helper::baseUrl() . $this->getUrl(0));
		$feeds->setDescription($this->getData (['page', $this->getUrl(0), 'metaDescription']));
		$feeds->setChannelElement('language', 'fr-FR');
		$feeds->setDate(date('r',time()));
		$feeds->addGenerator();
		// Corps des items
		$itemIdsPublishedOns = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'items']), 'publishedOn', 'SORT_DESC');
		$itemIdsStates = helper::arrayColumn($this->getData(['module', $this->getUrl(0),'items']), 'state', 'SORT_DESC');
		foreach($itemIdsPublishedOns as $itemId => $itemPublishedOn) {
			if($itemPublishedOn <= time() AND $itemIdsStates[$itemId]) {
				// Miniature
				$parts = explode('/',$this->getData(['module', $this->getUrl(0), 'items', $itemId, 'thumb']));
				$thumb = str_replace ($parts[(count($parts)-1)],'mini_' . $parts[(count($parts)-1)], $this->getData(['module', $this->getUrl(0), 'items', $itemId, 'thumb']));
				// Créer les items du flux
				$newsitem = $feeds->createNewItem();
				// Signature de l'item
				$author = $this->signature($this->getData(['module', $this->getUrl(0),  'items', $itemId, 'userId']));
				$newsitem->addElementArray([
					'title' 		=> $this->getData(['module', $this->getUrl(0), 'items', $itemId, 'title']),
					'link' 			=> helper::baseUrl() .$this->getUrl(0) . '/' . $itemId,
					'description' 	=> '<img src="' . helper::baseUrl() . self::FILE_DIR . $thumb
									 . '" alt="' . $this->getData(['module', $this->getUrl(0), 'items', $itemId, 'title'])
									 . '" title="' . $this->getData(['module', $this->getUrl(0), 'items', $itemId, 'title'])
									 . '" />' .
									 $this->getData(['module', $this->getUrl(0), 'items', $itemId, 'content']),
				]);
				$newsitem->setAuthor($author,'no@mail.com');
				$newsitem->setId(helper::baseUrl() .$this->getUrl(0) . '/' . $itemId);
				$newsitem->setDate(date('r', $this->getData(['module', $this->getUrl(0), 'items', $itemId, 'publishedOn'])));
				$imageData = getimagesize(helper::baseUrl(false) .  self::FILE_DIR . 'thumb/' .  $thumb);
				$newsitem->addEnclosure( helper::baseUrl(false) . self::FILE_DIR . 'thumb/'  . $thumb,
											$imageData[0] * $imageData[1],
											$imageData['mime']
				);
				$feeds->addItem($newsitem);
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
				$newuserid = $this->getInput('downloadAddUserId', helper::FILTER_STRING_SHORT, true);
			}
			else{
				$newuserid = $this->getUser('id');
			}
			// Incrémente l'id de l'item
			$itemId = helper::increment($this->getInput('downloadAddTitle', helper::FILTER_ID), $this->getData(['page']));
			$itemId = helper::increment($itemId, (array) $this->getData(['module', $this->getUrl(0)]));
			$itemId = helper::increment($itemId, array_keys(self::$actions));
			// Crée l'item
			$this->setData(['module',
				$this->getUrl(0),
				'items',
				$itemId, [
					'comment' => $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'comment']),
					'content' => $this->getInput('downloadAddContent', null),
					'thumb' => $this->getInput('downloadAddThumb', helper::FILTER_STRING_SHORT,true),
					'ressourceType' => $this->getInput('downloadAddRessourceType', helper::FILTER_STRING_SHORT),
					'file'	  => $this->getInput('downloadAddFile', helper::FILTER_STRING_SHORT),
					'url'	  => $this->getInput('downloadAddUrl', helper::FILTER_STRING_SHORT),
					'version' => $this->getInput('downloadAddVersion', helper::FILTER_STRING_SHORT),
					'versionDate' => $this->getInput('downloadAddversionDate', helper::FILTER_DATETIME),
					'license' => $this->getInput('downloadAddLicense', helper::FILTER_STRING_SHORT, true),
					'category' => $this->getInput('downloadAddCategorie', helper::FILTER_STRING_SHORT),
					'author' => $this->getInput('downloadAddAuthor', helper::FILTER_STRING_SHORT, true),
					'stats' => [],
					'publishedOn' => $this->getInput('downloadAddPublishedOn', helper::FILTER_DATETIME, true),
					'state' => $this->getInput('downloadAddState', helper::FILTER_BOOLEAN),
					'title' => $this->getInput('downloadAddTitle', helper::FILTER_STRING_SHORT, true),
					'userId' => $newuserid,
					'editConsent' =>  $this->getInput('downloadAddConsent') === self::EDIT_GROUP ? $this->getUser('group') : $this->getInput('downloadAddConsent'),
					'commentMaxlength' => $this->getInput('downloadAddCommentMaxlength'),
					'commentApproved' => $this->getInput('downloadAddCommentApproved', helper::FILTER_BOOLEAN),
					'commentClose' => $this->getInput('downloadAddCommentClose', helper::FILTER_BOOLEAN),
					'commentNotification'  => $this->getInput('downloadAddCommentNotification', helper::FILTER_BOOLEAN),
					'commentGroupNotification' => $this->getInput('downloadAddCommentGroupNotification', helper::FILTER_INT)
				]
			]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Nouvel item créé',
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
		// Liste des catégories
		/*
		if ($this->getData(['module', $this->getUrl(0), 'categories' ]) === NULL OR
		$this->getData(['module', $this->getUrl(0), 'categories' ]) === [] ) {
			// Stocke une catégorie vide
			$this->setData(['module',
				$this->getUrl(0),
				'categories',
				'none',
				'Aucune'
			]);
			// Alimente le formulaire
			self::$categories = ['none' => 'Aucune'];
		} else {
			self::$categories = $this->getData(['module', $this->getUrl(0), 'categories' ]);
		}
		arsort(self::$categories);
		*/
		if ( $this->getData(['module', $this->getUrl(0), 'categories' ]) !== NULL ) {
			self::$categories = $this->getData(['module', $this->getUrl(0), 'categories' ]);
			arsort(self::$categories);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Nouvel item',
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
		$comments = $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2),'comment']);
		self::$commentsDelete =	template::button('downloadCommentDeleteAll', [
					'class' => 'downloadCommentDeleteAll buttonRed',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/commentDeleteAll/' . $this->getUrl(2).'/' . $_SESSION['csrf'] ,
					'ico' => 'cancel',
					'value' => 'Tout effacer'
		]);
		// Ids des commentaires par ordre de création
		$commentIds = array_keys(helper::arrayColumn($comments, 'createdOn', 'SORT_DESC'));
		// Pagination
		$pagination = helper::pagination($commentIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage']));
		// Liste des pages
		self::$pages = $pagination['pages'];
		// Commentaires en fonction de la pagination
		for($i = $pagination['first']; $i < $pagination['last']; $i++) {
			// Met en forme le tableau
			$comment = $comments[$commentIds[$i]];
			// Bouton d'approbation
			$buttonApproval = '';
			// Compatibilité avec les commentaires des versions précédentes, les valider
			$comment['approval'] = array_key_exists('approval', $comment) === false ? true : $comment['approval'] ;
			if ( $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2),'commentApproved']) === true) {
				$buttonApproval = template::button('downloadCommentApproved' . $commentIds[$i], [
					'class' => $comment['approval'] === true ? 'downloadCommentRejected buttonGreen' : 'downloadCommentApproved buttonRed' ,
					'href' => helper::baseUrl() . $this->getUrl(0) . '/commentApprove/' . $this->getUrl(2) . '/' . $commentIds[$i] . '/' . $_SESSION['csrf'] ,
					'value' => $comment['approval'] === true ? 'A' : 'R'
				]);
			}
			self::$comments[] = [
				mb_detect_encoding(strftime('%d %B %Y - %H:%M', $comment['createdOn']), 'UTF-8', true)
				? strftime('%d %B %Y - %H:%M', $comment['createdOn'])
				: utf8_encode(strftime('%d %B %Y - %H:%M', $comment['createdOn'])),
				$comment['content'],
				$comment['userId'] ? $this->getData(['user', $comment['userId'], 'firstname']) . ' ' . $this->getData(['user', $comment['userId'], 'lastname']) : $comment['author'],
				$buttonApproval,
				template::button('downloadCommentDelete' . $commentIds[$i], [
					'class' => 'downloadCommentDelete buttonRed',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/commentDelete/' . $this->getUrl(2) . '/' . $commentIds[$i] . '/' . $_SESSION['csrf'] ,
					'value' => template::ico('cancel')
				])
			];
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Gestion des commentaires : '. $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'title']),
			'view' => 'comment'
		]);
	}

	/**
	 * Suppression de commentaire
	 */
	public function commentDelete() {
		// Le commentaire n'existe pas
		if($this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'comment', $this->getUrl(3)]) === null) {
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
			$this->deleteData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'comment', $this->getUrl(3)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment/'.$this->getUrl(2),
				'notification' => 'Commentaire supprimé',
				'state' => true
			]);
		}
	}

	/**
	 * Suppression de tous les commentaires de l'item $this->getUrl(2)
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
			$this->setData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'comment',[] ]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment',
				'notification' => 'Commentaires supprimés',
				'state' => true
			]);
		}
	}

	/**
	 * Approbation oou désapprobation de commentaire
	 */
	public function commentApprove() {
		// Le commentaire n'existe pas
		if($this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'comment', $this->getUrl(3)]) === null) {
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
			$approved = !$this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'comment', $this->getUrl(3), 'approval']) ;
			$this->setData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'comment', $this->getUrl(3), [
				'author' => $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'comment', $this->getUrl(3), 'author']),
				'content' => $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'comment', $this->getUrl(3), 'content']),
				'createdOn' => $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'comment', $this->getUrl(3), 'createdOn']),
				'userId' => $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'comment', $this->getUrl(3), 'userId']),
				'approval' => $approved
			]]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment/'.$this->getUrl(2),
				'notification' =>  $approved ?  'Commentaire approuvé' : 'Commentaire rejeté',
				'state' => $approved
			]);
		}
	}

	/**
	 * Configuration
	 */
	public function config() {
		// Mise à jour des données de module
		$this->update();
		// Soumission du formulaire
		if($this->isPost()) {
			$this->setData(['module', $this->getUrl(0), 'config',[
				'feeds' 	 => $this->getInput('downloadConfigShowFeeds',helper::FILTER_BOOLEAN),
				'feedsLabel' => $this->getInput('downloadConfigFeedslabel',helper::FILTER_STRING_SHORT),
				'itemsperPage' => $this->getInput('blogConfigItemsperPage', helper::FILTER_INT,true),
				'versionData' => $this->getData(['module', $this->getUrl(0), 'config', 'versionData'])
				]]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		} else {
			// Ids des items par ordre de publication
			$itemIds = array_keys(helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'items']), 'publishedOn', 'SORT_DESC'));
			// Gestion des droits d'accès
			$filterData=[];
			foreach ($itemIds as $key => $value) {
				if (
					(  // Propriétaire
						$this->getData(['module',  $this->getUrl(0), 'items', $value,'editConsent']) === self::EDIT_OWNER
						AND ( $this->getData(['module',  $this->getUrl(0), 'items', $value,'userId']) === $this->getUser('id')
						OR $this->getUser('group') === self::GROUP_ADMIN )
					)

					OR (
						// Groupe
						$this->getData(['module',  $this->getUrl(0), 'items',  $value,'editConsent']) !== self::EDIT_OWNER
						AND $this->getUser('group') >=  $this->getData(['module',$this->getUrl(0), 'items', $value,'editConsent'])
					)
					OR (
						// Tout le monde
						$this->getData(['module',  $this->getUrl(0), 'items',  $value,'editConsent']) === self::EDIT_ALL
					)
				) {
					$filterData[] = $value;
				}
			}
			$itemIds = $filterData;
			// Filtrage des catégories selon le second élément de l'URL si valide
			if ( $this->getUrl(2)
				AND array_key_exists($this->getUrl(2),$this->getData(['module',$this->getUrl(0),'categories']))
				) {
					$filterData=[];
					foreach ($itemIds as $key => $value) {
						if ($this->getData(['module', $this->getUrl(0), 'items', $value,'category']) === $this->getUrl(2) ) {
							$filterData[] = $value;
						}
					}
					$itemIds = $filterData;
			}
			// Pagination
			$pagination = helper::pagination($itemIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage']));
			// Liste des pages
			self::$pages = $pagination['pages'];
			// items en fonction de la pagination
			for($i = $pagination['first']; $i < $pagination['last']; $i++) {
				// Nombre de commentaires à approuver et approuvés
				$approvals = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'items',  $itemIds[$i], 'comment' ]),'approval', 'SORT_DESC');
				if ( is_array($approvals) ) {
					$a = array_values($approvals);
					$toApprove = count(array_keys($a,false));
					$approved = count(array_keys($a,true));
				} else {
					$toApprove = 0;
					$approved = count($this->getData(['module', $this->getUrl(0), 'items', $itemIds[$i],'comment']));
				}
				// Met en forme le tableau
				$date = mb_detect_encoding(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0),  'items', $itemIds[$i], 'versionDate'])), 'UTF-8', true)
					? strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'items', $itemIds[$i], 'versionDate']))
					: utf8_encode(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'items', $itemIds[$i], 'versionDate'])));
				//$heure = mb_detect_encoding(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'items', $itemIds[$i], 'versionDate'])), 'UTF-8', true)
				//	? strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'items', $itemIds[$i], 'versionDate']))
				//	: utf8_encode(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'items', $itemIds[$i], 'versionDate'])));
				$stat = count(helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'items', $itemIds[$i],'stats']), 'time') ) === 0
					? '0'
					: '<a href="' . helper::baseurl() . $this->getUrl(0) . '/stats/' . $itemIds[$i] . '" >' .
					count(helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'items', $itemIds[$i],'stats']), 'time') )  .
					'</a>';
				// Lien toutes les catégories quand le filtre est actif
				if ($this->getUrl(2)) {
					self::$allCategories = '<a href="' . helper::baseurl()  . $this->getUrl(0) . '/config' . '">(toutes)</a>';
				}
				// Tableau des items
				self::$items[] = [
					'<a href="' . helper::baseurl() . $this->getUrl(0) . '/' . $itemIds[$i] . '/' . $_SESSION['csrf']. '">' .
						$this->getData(['module', $this->getUrl(0),  'items', $itemIds[$i], 'title']) .
						'</a>',
					'<a href="' . helper::baseurl() . $this->getUrl(0) . '/config/' . $this->getData(['module', $this->getUrl(0),  'items', $itemIds[$i], 'category']) . '">' .
						$this->getData(['module', $this->getUrl(0), 'categories', $this->getData(['module', $this->getUrl(0),  'items', $itemIds[$i], 'category'])]) .
						'</a>',
					$this->getData(['module', $this->getUrl(0),  'items', $itemIds[$i], 'version']),
					//$date .' à '. $heure,
					$date,
					$stat,
					self::$states[$this->getData(['module', $this->getUrl(0), 'items', $itemIds[$i], 'state'])],
					// Bouton pour afficher les commentaires de l'item
					template::button('downloadConfigComment' . $itemIds[$i], [
						'class' => ($toApprove || $approved ) > 0 ?  'buttonBlue' : 'buttonGrey' ,
						'href' => ($toApprove || $approved ) > 0 ? helper::baseUrl() . $this->getUrl(0) . '/comment/' . $itemIds[$i] : '',
						'value' => $toApprove > 0 ? $toApprove . '/' . $approved : $approved
					]),
					template::button('downloadConfigEdit' . $itemIds[$i], [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $itemIds[$i] . '/' . $_SESSION['csrf'],
						'value' => template::ico('pencil')
					]),
					template::button('downloadConfigDelete' . $itemIds[$i], [
						'class' => 'downloadConfigDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $itemIds[$i] . '/' . $_SESSION['csrf'],
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
		if($this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2)]) === null) {
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
			$this->deleteData(['module', $this->getUrl(0), 'items', $this->getUrl(2)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Item supprimé',
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
		// L'item n'existe pas
		if($this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// L'item existe
		else {
			// Soumission du formulaire
			if($this->isPost()) {
				if($this->getUser('group') === self::GROUP_ADMIN){
					$newuserid = $this->getInput('downloadEditUserId', helper::FILTER_STRING_SHORT, true);
				}
				else{
					$newuserid = $this->getUser('id');
				}
				$itemId = $this->getInput('downloadEditTitle', helper::FILTER_ID, true);
				// Incrémente le nouvel id de l'item
				if($itemId !== $this->getUrl(2)) {
					$itemId = helper::increment($itemId, $this->getData(['page']));
					$itemId = helper::increment($itemId, $this->getData(['module', $this->getUrl(0),'items']));
					$itemId = helper::increment($itemId, array_keys(self::$actions));
				}
				$this->setData(['module',
					$this->getUrl(0),
					'items',
					$itemId, [
						'comment' => $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'comment']),
						'content' => $this->getInput('downloadEditContent', null),
						'ressourceType' => $this->getInput('downloadEditRessourceType', helper::FILTER_STRING_SHORT),
						'file'	  => $this->getInput('downloadEditFile', helper::FILTER_STRING_SHORT),
						'url'	  => $this->getInput('downloadEditUrl', helper::FILTER_STRING_SHORT),
						'thumb' => $this->getInput('downloadEditThumb', helper::FILTER_STRING_SHORT,true),
						'version' => $this->getInput('downloadEditVersion', helper::FILTER_STRING_SHORT),
						'versionDate' => $this->getInput('downloadEditversionDate', helper::FILTER_DATETIME),
						'stats' => $this->getData(['module',$this->getUrl(0), 'items', $this->getUrl(2), 'stats']),
						'license' => $this->getInput('downloadEditLicense', helper::FILTER_STRING_SHORT, true),
						'category' => $this->getInput('downloadEditCategorie', helper::FILTER_STRING_SHORT),
						'author' => $this->getInput('downloadEditAuthor', helper::FILTER_STRING_SHORT, true),
						'publishedOn' => $this->getInput('downloadEditPublishedOn', helper::FILTER_DATETIME, true),
						'state' => $this->getInput('downloadEditState', helper::FILTER_BOOLEAN),
						'title' => $this->getInput('downloadEditTitle', helper::FILTER_STRING_SHORT, true),
						'userId' => $newuserid,
						'editConsent' => $this->getInput('downloadEditConsent') === self::EDIT_GROUP ? $this->getUser('group') : $this->getInput('downloadEditConsent'),
						'commentMaxlength' => $this->getInput('downloadEditCommentMaxlength'),
						'commentApproved' => $this->getInput('downloadEditCommentApproved', helper::FILTER_BOOLEAN),
						'commentClose' => $this->getInput('downloadEditCommentClose', helper::FILTER_BOOLEAN),
						'commentNotification'  => $this->getInput('downloadEditCommentNotification', helper::FILTER_BOOLEAN),
						'commentGroupNotification' => $this->getInput('downloadEditCommentGroupNotification', helper::FILTER_INT)
					]
				]);
				// Supprime l'ancien item
				if($itemId !== $this->getUrl(2)) {
					$this->deleteData(['module', $this->getUrl(0), 'items', $this->getUrl(2)]);
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
			// Liste des catégories
			self::$categories = $this->getData(['module', $this->getUrl(0), 'categories' ]);
			arsort(self::$categories);
			// Valeurs en sortie
			$this->addOutput([
				'title' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'title']),
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
		// Mise à jour des données de module
		$this->update();
		// Affichage d'un item
		if(
			$this->getUrl(1)
			// Protection pour la pagination, un ID ne peut pas être un entier, une page oui
			AND intval($this->getUrl(1)) === 0
			// Ne pas exclure la catégorie
			AND $this->getData(['module', $this->getUrl(0), 'categories', $this->getUrl(1)]) === null
		) {
			// L'item ou la catégorie n'existent pas
			if($this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1)]) === null
				 ) {
				// Valeurs en sortie
				$this->addOutput([
					'access' => false
				]);
			}
			else {
				// Soumission du formulaire
				if($this->isPost()) {
					// Check la captcha
					if(
						$this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
						//AND $this->getInput('downloaditemcaptcha', helper::FILTER_INT) !== $this->getInput('downloaditemcaptchaFirstNumber', helper::FILTER_INT) + $this->getInput('downloaditemcaptchaSecondNumber', helper::FILTER_INT))
						AND password_verify($this->getInput('downloadItemCaptcha', helper::FILTER_INT), $this->getInput('downloadItemCaptchaResult') ) === false )
					{
						self::$inputNotices['downloadItemCaptcha'] = 'Incorrect';
					}
					// Crée le commentaire
					$commentId = helper::increment(uniqid(), $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(1), 'comment']));
					$content = $this->getInput('downloadItemContent', false);
					$this->setData(['module', $this->getUrl(0),  'items', $this->getUrl(1), 'comment', $commentId, [
						'author' => $this->getInput('downloadItemAuthor', helper::FILTER_STRING_SHORT, empty($this->getInput('downloadItemUserId')) ? TRUE : FALSE),
						'content' => $content,
						'createdOn' => time(),
						'userId' => $this->getInput('downloadItemUserId'),
						'approval' => !$this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'commentApproved']) // true commentaire publié false en attente de publication
					]]);
					// Envoi d'une notification aux administrateurs
					// Init tableau
					$to = [];
					// Liste des destinataires
					foreach($this->getData(['user']) as $userId => $user) {
						if ($user['group'] >= $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(1), 'commentGroupNotification']) ) {
							$to[] = $user['mail'];
							$firstname[] = $user['firstname'];
							$lastname[] = $user['lastname'];
						}
					}
					// Envoi du mail $sent code d'erreur ou de réussite
					$notification = $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(1), 'commentApproved']) === true ? 'Commentaire déposé en attente d\'approbation': 'Commentaire déposé';
					if ($this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(1), 'commentNotification']) === true) {
						$error = 0;
						foreach($to as $key => $adress){
							$sent = $this->sendMail(
								$adress,
								'Nouveau commentaire déposé',
								'Bonjour' . ' <strong>' . $firstname[$key] . ' ' . $lastname[$key] . '</strong>,<br><br>' .
								'L\'item <a href="' . helper::baseUrl() . $this->getUrl(0) . '/	' . $this->getUrl(1) . '">' . $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'title']) . '</a> a  reçu un nouveau commentaire.<br><br>',
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
				// Ids des commentaires approuvés par ordre de publication
				$commentsApproved = $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'comment']);
				if ($commentsApproved) {
					foreach( $commentsApproved as $key => $value){
						if($value['approval']===false) unset($commentsApproved[$key]);
					}
					// Ligne suivante si affichage du nombre total de commentaires approuvés sous l'item
					self::$nbCommentsApproved = count($commentsApproved);
				}
				$commentIds = array_keys(helper::arrayColumn($commentsApproved, 'createdOn', 'SORT_DESC'));
				// Pagination
				$pagination = helper::pagination($commentIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage']),'#comment');
				// Nombre de téléchargements
				self::$statSum = count(helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'items',$this->getUrl(1),'stats']), 'time') ) === 0
								? '0'
								: count(helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1),'stats']), 'time') ) ;
				// Liste des pages
				self::$pages = $pagination['pages'];
				// Signature de l'item
				self::$itemSignature = $this->signature($this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(1), 'userId']));
				// Signature du commentaire édité
				if($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')) {
					self::$editCommentSignature = $this->signature($this->getUser('id'));
				}
				// Commentaires en fonction de la pagination
				for($i = $pagination['first']; $i < $pagination['last']; $i++) {
					// Signatures des commentaires
					$e = $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(1), 'comment', $commentIds[$i],'userId']);
					if ($e) {
						self::$commentsSignature[$commentIds[$i]] = $this->signature($e);
					} else {
						self::$commentsSignature[$commentIds[$i]] = $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(1), 'comment', $commentIds[$i],'author']);
					}
					// Données du commentaire si approuvé
					if ($this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'comment', $commentIds[$i],'approval']) === true ) {
						self::$comments[$commentIds[$i]] = $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'comment', $commentIds[$i]]);
					}
				}
				// Valeurs en sortie
				$this->addOutput([
					'showBarEditButton' => true,
					'title' => $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(1), 'title']),
					'vendor' => [
						'tinymce'
					],
					'view' => 'item'
				]);
			}
		}
		// Liste des items
		else {
			// Ids des items par ordre de publication
			$itemIdsPublishedOns = helper::arrayColumn($this->getData(['module', $this->getUrl(0),'items']), 'publishedOn', 'SORT_DESC');
			$itemIdsStates = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'items']), 'state', 'SORT_DESC');
			$itemIds = [];
			foreach($itemIdsPublishedOns as $itemId => $itemPublishedOn) {
				if($itemPublishedOn <= time() AND $itemIdsStates[$itemId]) {
					$itemIds[] = $itemId;
				}
			}
			// Filtrage des catégories
			// Une catégorie et pas un article
			if ( $this->getUrl(1)
				AND array_key_exists($this->getUrl(1),$this->getData(['module',$this->getUrl(0),'categories']))
				) {
					$filterData=[];
					foreach ($itemIds as $key => $value) {
						if ($this->getData(['module', $this->getUrl(0), 'items', $value,'category']) === $this->getUrl(1) ) {
							$filterData[] = $value;
						}
					}
					$itemIds = $filterData;
			}
			// Pagination
			$pagination = helper::pagination($itemIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage']));
			// Liste des pages
			self::$pages = $pagination['pages'];
			// Items en fonction de la pagination
			for($i = $pagination['first']; $i < $pagination['last']; $i++) {
				self::$items[$itemIds[$i]] = $this->getData(['module', $this->getUrl(0), 'items', $itemIds[$i]]);
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
	 * Initie un téléchargement protégé
	 */
	public function downloadFile() {

		if($this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Jeton incorrect
		elseif ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . $this->getUrl(0),
				'notification' => 'Action non autorisée'
			]);
		}
		// Téléchargement
		else {
			$fileName = self::FILE_DIR . 'source/' . $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'file']);
			if (file_exists($fileName)) {
				// Statistiques de téléchargement
				$statId = helper::increment(uniqid(), $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'stats']));
				$this->setData(['module',
					$this->getUrl(0),
					'items',
					$this->getUrl(2),
					'stats',
					$statId, [
						'time' => time(),
						'ip' =>  helper::getIp()
				]]);
				// Formatage http
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($fileName));
				readfile($fileName);
				exit;
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl()  . $this->getUrl(0),
					'notification' => 'Le fichier n\'existe pas',
					'state' => false
				]);
			}
		}
	}

	/**
	 * Ecran de consultation des données statistiques
	 */

	 public function stats() {

		// Construction de la page des statistiques
		$itemIds = array_keys($this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'stats']));
		// Total des téléchargements
		self::$statSum = count ($itemIds);
		// Pagination
		$pagination = helper::pagination($itemIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage']));

		// Liste des pages
		self::$pages = $pagination['pages'];

		for($i = $pagination['first']; $i < $pagination['last']; $i++) {

			// Format des variables
			$date = mb_detect_encoding(strftime('%d %B %Y - %H:%M', $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'stats', $itemIds[$i], 'time'])), 'UTF-8', true)
				? strftime('%d %B %Y - %H:%M',$this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'stats', $itemIds[$i], 'time']))
				: utf8_encode(strftime('%d %B %Y - %H:%M', $this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'stats', $itemIds[$i], 'time'])));

			// Met en forme le tableau
			self::$items[] = [
				$date,
				$this->getData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'stats', $itemIds[$i], 'ip'])
			];

		}
		$this->addOutput([
			'title' => 'Statistiques de téléchargement',
			'view' => 'stats'
		]);
	}

	public function statsDeleteAll() {
		// Validité de la page demandée
		if($this->getData(['module', $this->getUrl(0), 'items']) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Jeton incorrect
		elseif ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . $this->getUrl(0),
				'notification' => 'Action non autorisée'
			]);
		}
		// Téléchargement
		else {
			$this->setData(['module', $this->getUrl(0),  'items', $this->getUrl(2), 'stats', [] ]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . $this->getUrl(0) . '/stats/' . $this->getUrl(2),
				'notification' => 'Purge des statistiques',
				'state' => true
			]);
		}
	}

	/***
	 * Retourne une chaîne json contenant la liste des téléchargements disponibles
	*/
	public function list() {
		$itemIdsPublishedOns = helper::arrayColumn($this->getData(['module', $this->getUrl(0),'items']), 'publishedOn', 'SORT_DESC');
		$itemIdsStates = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'items']), 'state', 'SORT_DESC');
		$itemIds = [];
		foreach($itemIdsPublishedOns as $itemId => $itemPublishedOn) {
			if($itemPublishedOn <= time() AND $itemIdsStates[$itemId]) {
				$itemIds[] = $itemId;
			}
		}
		foreach ($itemIds as $key) {
			self::$items[$key] = [
				'title'   => $this->getData(['module', $this->getUrl(0), 'items', $key, 'title']),
				'content' => $this->getData(['module', $this->getUrl(0), 'items', $key, 'content']),
				'thumb' => $this->getData(['module', $this->getUrl(0), 'items', $key, 'thumb']),
				'file'    =>$this->getData(['module', $this->getUrl(0), 'items', $key, 'file']),
				'version' => $this->getData(['module', $this->getUrl(0), 'items', $key, 'version']),
				'versionDate'   =>$this->getData(['module', $this->getUrl(0), 'items', $key, 'versionDate']),
				'author'   =>$this->getData(['module', $this->getUrl(0), 'items', $key, 'author']),
				'license'   => $this->getData(['module', $this->getUrl(0), 'items', $key, 'license']),
				'category' =>  $this->getData(['module', $this->getUrl(0),'categories',
								$this->getData(['module', $this->getUrl(0), 'items', $key, 'category'])
							])
			];
		}
		$this->addOutput([
			'display' => self::DISPLAY_JSON,
			'content' => self::$items
		]);
	}

	/**
	 * Gestion des catégories d'objets
	 */
	public function categories() {
		// Soumission du formulaire
		if($this->isPost()) {
			// Empêche les doublons
			$itemTitle = helper::increment($this->getInput('categoriesTitle', helper::FILTER_STRING_SHORT), $this->getData(['module', $this->getUrl(0), 'categories']));
			if ($itemTitle === $this->getInput('categoriesTitle', helper::FILTER_STRING_SHORT)) {
				// Incrémente l'id de l'item
				$itemId = helper::increment($this->getInput('categoriesTitle', helper::FILTER_ID), $this->getData(['module', $this->getUrl(0), 'categories']));
				$itemId = helper::increment($itemId, $this->getData(['page']));
				$itemId = helper::increment($itemId, (array) $this->getData(['module', $this->getUrl(0)]));
				$itemId = helper::increment($itemId, array_keys(self::$actions));
				// Crée l'item
				$this->setData(['module',
					$this->getUrl(0),
					'categories',
					$itemId,
					$this->getInput('categoriesTitle', helper::FILTER_STRING_SHORT, true)
				]);
				$notification = 'Nouvelle catégorie créé.';
				$success = true;
			} else {
				$notification = 'Cette catégorie existe déjà !';
				$success = false;
			}

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/categories',
				'notification' => $notification,
				'state' => $success
			]);
		}
		if ($this->getData(['module',$this->getUrl(0),'categories'])) {
			$categories = $this->getData(['module',$this->getUrl(0),'categories']);
			// Ids des catégories par ordre alpha
			$categoriesIds = array_keys($categories);
			// Pagination
			$pagination = helper::pagination($categoriesIds, $this->getUrl(), $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage']));
			// Liste des pages
			self::$pages = $pagination['pages'];
			for($i = $pagination['first']; $i < $pagination['last']; $i++) {
				self::$categories[ $categoriesIds[$i]] = [
					$this->getData(['module', $this->getUrl(0), 'categories', $categoriesIds[$i]]),
					helper::baseUrl() . $this->getUrl(0) . '/' . $categoriesIds[$i],
					template::button('categoriesEdit' . $categoriesIds[$i], [
						'class' => 'categoriesEdit',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/categoryEdit/' .  $categoriesIds[$i] . '/' . $_SESSION['csrf'] ,
						'value' => template::ico('pencil')
					]),
					template::button('categoriesDelete' . $categoriesIds[$i], [
						'class' => 'categoriesDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/categoryDelete/' . $categoriesIds[$i] . '/' . $_SESSION['csrf'] ,
						'value' => template::ico('cancel')
					])
				];
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Catégories',
			'view' => 'categories'
		]);
	}

	/**
	 * Edition d'une catégorie
	 */
	public function categoryEdit() {
		// Soumission du formulaire
		if($this->isPost()) {
			// Id de la catégorie précédente
			$oldItemId = $this->getUrl(2);
			// Empêche les doublons de clé
			$itemTitle = helper::increment($this->getInput('categoryEditTitle', helper::FILTER_STRING_SHORT), $this->getData(['module', $this->getUrl(0), 'categories']));
			if ($itemTitle === $this->getInput('categoryEditTitle', helper::FILTER_STRING_SHORT)) {
				// Incrémente l'id de l'item
				$itemId = helper::increment($this->getInput('categoryEditTitle', helper::FILTER_ID), $this->getData(['module', $this->getUrl(0), 'categories']));
				$itemId = helper::increment($itemId, $this->getData(['page']));
				$itemId = helper::increment($itemId, (array) $this->getData(['module', $this->getUrl(0)]));
				$itemId = helper::increment($itemId, array_keys(self::$actions));
				// Crée la catégorie
				$this->setData(['module',
					$this->getUrl(0),
					'categories',
					$itemId,
					$this->getInput('categoryEditTitle', helper::FILTER_STRING_SHORT, true)
				]);
				// Effacer la catégorie
					$this->deleteData(['module',
					$this->getUrl(0),
					'categories',
					$this->getUrl(2)
				]);
				$notification = 'La catégorie a été éditée.';
				$success = true;

				// Répercuter le changement d'Id
				if ($oldItemId !== $itemId) {
					$i = 0;
					// Mettre à jour les catégories dans items
					$itemIdsPublishedOns = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'items']), 'publishedOn', 'SORT_DESC');
					foreach ($itemIdsPublishedOns as $key => $value) {
						if ($this->getData(['module', $this->getUrl(0), 'items', $key, 'category']) === $oldItemId) {
							$this->setData(['module', $this->getUrl(0), 'items', $key, 'category', $itemId]);
							$i++;
						}
					}
					$notification .= ' ' . $i . ' items ont été actualisés.';
				}
			} else {
				$notification = 'Cette catégorie existe déjà !';
				$success = false;
			}
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/categories',
				'notification' => $notification,
				'state' => $success
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Editer une catégorie',
			'view' => 'categoryEdit'
		]);
	}

	/**
	 * Effacement d'une catégorie
	 */
	public function categoryDelete() {
		// La catégorie n'existe pas
		if($this->getData(['module', $this->getUrl(0),'categories', $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Contrôle du jeton
		elseif ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . $this->getUrl(0) . '/categories',
				'notification' => 'Action non autorisée'
			]);
		} else {
			// Mettre à jour les catégories dans items
			$itemIdsPublishedOns = helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'items']), 'publishedOn', 'SORT_DESC');
			$success = true;
			$i = 0;
			foreach ($itemIdsPublishedOns as $key => $value) {
				if ($this->getData(['module', $this->getUrl(0), 'items', $key, 'category']) === $this->getUrl(2)) {
					$i++;
					$success = false;
				}
			}
			if ($success) {
				// Effacer la catégorie
				$this->deleteData(['module',
					$this->getUrl(0),
					'categories',
					$this->getUrl(2)
				]);
				$notification = 'La catégorie a été supprimée';
			} else {
				$notification = 'Suppression impossible, la catégorie est affectée à ' . $i . ' items.';
			}

			// valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/categories',
				'notification' => $notification,
				'state' => $success
			]);
		}
	}
}