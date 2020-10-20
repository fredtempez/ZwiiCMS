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
		'index' => self::GROUP_VISITOR
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

	//Paramètre longueur maximale des commentaires en nb de caractères
	public static $commentLength = [
		'500' => '500',
		'1000' => '1000',
		'2000' => '2000',
		'5000' => '5000',
		'10000' => '10000'
	];

	// Permissions d'un article
	public static $articleConsent = [
		self::EDIT_ALL 		   => 'Tous les groupes',
		self::EDIT_GROUP       => 'Groupe du propriétaire',
		self::EDIT_OWNER       => 'Propiétaire'
	];


	public static $users = [];

	const BLOG_VERSION = '3.1';

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
			// Incrémente l'id de l'article
			$articleId = helper::increment($this->getInput('blogAddTitle', helper::FILTER_ID), $this->getData(['page']));
			$articleId = helper::increment($articleId, (array) $this->getData(['module', $this->getUrl(0)]));
			$articleId = helper::increment($articleId, array_keys(self::$actions));
			// Crée l'article
			$this->setData(['module',
				$this->getUrl(0),
				$articleId, [
					'comment' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'comment']),
					'content' => $this->getInput('blogAddContent', null),
					'picture' => $this->getInput('blogAddPicture', helper::FILTER_STRING_SHORT, true),
					'hidePicture' => $this->getInput('blogAddHidePicture', helper::FILTER_BOOLEAN),
					'pictureSize' => $this->getInput('blogAddPictureSize', helper::FILTER_STRING_SHORT),
					'picturePosition' => $this->getInput('blogAddPicturePosition', helper::FILTER_STRING_SHORT),
					'publishedOn' => $this->getInput('blogAddPublishedOn', helper::FILTER_DATETIME, true),
					'state' => $this->getInput('blogAddState', helper::FILTER_BOOLEAN),
					'title' => $this->getInput('blogAddTitle', helper::FILTER_STRING_SHORT, true),
					'userId' => $newuserid,
					'editConsent' =>  $this->getInput('blogAddConsent') === self::EDIT_GROUP ? $this->getUser('group') : $this->getInput('blogAddConsent'),
					'commentMaxlength' => $this->getInput('blogAddCommentMaxlength'),
					'commentApproved' => $this->getInput('blogAddCommentApproved', helper::FILTER_BOOLEAN),
					'commentClose' => $this->getInput('blogAddCommentClose', helper::FILTER_BOOLEAN),
					'commentNotification'  => $this->getInput('blogAddCommentNotification', helper::FILTER_BOOLEAN),
					'commentGroupNotification' => $this->getInput('blogAddCommentGroupNotification', helper::FILTER_INT)
				]
			]);
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
		$comments = $this->getData(['module', $this->getUrl(0), $this->getUrl(2),'comment']);
		self::$commentsDelete =	template::button('blogCommentDeleteAll', [
					'class' => 'blogCommentDeleteAll buttonRed',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/commentDeleteAll/' . $this->getUrl(2).'/' . $_SESSION['csrf'] ,
					'ico' => 'cancel',
					'value' => 'Tout effacer'
		]);
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
			// Bouton d'approbation
			$buttonApproval = '';
			// Compatibilité avec les commentaires des versions précédentes, les valider
			$comment['approval'] = array_key_exists('approval', $comment) === false ? true : $comment['approval'] ;
			if ( $this->getData(['module', $this->getUrl(0), $this->getUrl(2),'commentApproved']) === true) {
				$buttonApproval = template::button('blogCommentApproved' . $commentIds[$i], [
					'class' => $comment['approval'] === true ? 'blogCommentReject buttonGreen' : 'blogCommentApproved buttonRed' ,
					'href' => helper::baseUrl() . $this->getUrl(0) . '/commentApprove/' . $this->getUrl(2) . '/' . $commentIds[$i] . '/' . $_SESSION['csrf'] ,
					'value' => $comment['approval'] === true ? 'A' : 'R'
				]);
			}
			self::$comments[] = [
				strftime('%d %B %Y - %H:%M', $comment['createdOn']),
				$comment['content'],
				$comment['userId'] ? $this->getData(['user', $comment['userId'], 'firstname']) . ' ' . $this->getData(['user', $comment['userId'], 'lastname']) : $comment['author'],
				$buttonApproval,
				template::button('blogCommentDelete' . $commentIds[$i], [
					'class' => 'blogCommentDelete buttonRed',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/commentDelete/' . $this->getUrl(2) . '/' . $commentIds[$i] . '/' . $_SESSION['csrf'] ,
					'value' => template::ico('cancel')
				])
			];
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Gestion des commentaires : '. $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'title']),
			'view' => 'comment'
		]);
	}

	/**
	 * Suppression de commentaire
	 */
	public function commentDelete() {
		// Le commentaire n'existe pas
		if($this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'comment', $this->getUrl(3)]) === null) {
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
			$this->deleteData(['module', $this->getUrl(0), $this->getUrl(2), 'comment', $this->getUrl(3)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment/'.$this->getUrl(2),
				'notification' => 'Commentaire supprimé',
				'state' => true
			]);
		}
	}

	/**
	 * Suppression de tous les commentaires de l'article $this->getUrl(2)
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
			$this->setData(['module', $this->getUrl(0), $this->getUrl(2), 'comment',[] ]);
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
		if($this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'comment', $this->getUrl(3)]) === null) {
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
			$this->setData(['module', $this->getUrl(0), $this->getUrl(2), 'comment', $this->getUrl(3), [
				'author' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'comment', $this->getUrl(3), 'author']),
				'content' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'comment', $this->getUrl(3), 'content']),
				'createdOn' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'comment', $this->getUrl(3), 'createdOn']),
				'userId' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'comment', $this->getUrl(3), 'userId']),
				'approval' => !$this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'comment', $this->getUrl(3), 'approval'])
			]]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/comment/'.$this->getUrl(2),
				'notification' =>  $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'comment', $this->getUrl(3), 'approval']) === true ? 'Commentaire rejeté' : 'Commentaire approuvé',
				'state' => !$this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'comment', $this->getUrl(3), 'approval'])
			]);
		}
	}

	/**
	 * Configuration
	 */
	public function config() {
		// Ids des articles par ordre de publication
		$articleIds = array_keys(helper::arrayCollumn($this->getData(['module', $this->getUrl(0)]), 'publishedOn', 'SORT_DESC'));
		// Gestion des droits d'accès
		$filterData=[];
		foreach ($articleIds as $key => $value) {
			if (
				(  // Propriétaire
					$this->getData(['module',  $this->getUrl(0), $value,'editConsent']) === self::EDIT_OWNER
					AND ( $this->getData(['module',  $this->getUrl(0), $value,'userId']) === $this->getUser('id')
					OR $this->getUser('group') === self::GROUP_ADMIN )
				)

				OR (
					// Groupe
					$this->getData(['module',  $this->getUrl(0),  $value,'editConsent']) !== self::EDIT_OWNER
					AND $this->getUser('group') >=  $this->getData(['module',$this->getUrl(0), $value,'editConsent'])
				)
				OR (
					// Tout le monde
					$this->getData(['module',  $this->getUrl(0),  $value,'editConsent']) === self::EDIT_ALL
				)
			) {
				$filterData[] = $value;
			}
		}
		$articleIds = $filterData;
		// Pagination
		$pagination = helper::pagination($articleIds, $this->getUrl(),$this->getData(['config','itemsperPage']));
		// Liste des pages
		self::$pages = $pagination['pages'];
		// Articles en fonction de la pagination
		for($i = $pagination['first']; $i < $pagination['last']; $i++) {
			// Nombre de commentaires à approuver et approuvés
			$approvals = helper::arrayCollumn($this->getData(['module', $this->getUrl(0),  $articleIds[$i], 'comment' ]),'approval', 'SORT_DESC');
			if ( is_array($approvals) ) {
				$a = array_values($approvals);
				$toApprove = count(array_keys($a,false));
				$approved = count(array_keys($a,true));
			} else {
				$toApprove = 0;
				$approved = count($this->getData(['module', $this->getUrl(0), $articleIds[$i],'comment']));
			}
			// Met en forme le tableau
			self::$articles[] = [
				'<a href="' . helper::baseurl() . $this->getUrl(0) . '/' . $articleIds[$i] . '" target="_blank" >' .
				$this->getData(['module', $this->getUrl(0), $articleIds[$i], 'title']) .
				'</a>',
				// date('d/m/Y H:i', $this->getData(['module', $this->getUrl(0), $articleIds[$i], 'publishedOn'])),
				strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), $articleIds[$i], 'publishedOn']))
				.' à '.
				strftime('%H:%M', $this->getData(['module', $this->getUrl(0), $articleIds[$i], 'publishedOn'])),
				self::$states[$this->getData(['module', $this->getUrl(0), $articleIds[$i], 'state'])],
				// Bouton pour afficher les commentaires de l'article
				template::button('blogConfigComment' . $articleIds[$i], [
					'class' => ($toApprove || $approved ) > 0 ?  'buttonBlue' : 'buttonGrey' ,
					'href' => ($toApprove || $approved ) > 0 ? helper::baseUrl() . $this->getUrl(0) . '/comment/' . $articleIds[$i] : '',
					'value' => $toApprove > 0 ? $toApprove . '/' . $approved : $approved
					//'value' => count($this->getData(['module', $this->getUrl(0), $articleIds[$i],'comment']))
				]),
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

	/**
	 * Suppression
	 */
	public function delete() {
		if($this->getData(['module', $this->getUrl(0), $this->getUrl(2)]) === null) {
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
			$this->deleteData(['module', $this->getUrl(0), $this->getUrl(2)]);
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
		if($this->getData(['module', $this->getUrl(0), $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// L'article existe
		else {
			// Soumission du formulaire
			if($this->isPost()) {
				if($this->getUser('group') === self::GROUP_ADMIN){
					$newuserid = $this->getInput('blogEditUserId', helper::FILTER_STRING_SHORT, true);
				}
				else{
					$newuserid = $this->getUser('id');
				}
				$articleId = $this->getInput('blogEditTitle', helper::FILTER_ID, true);
				// Incrémente le nouvel id de l'article
				if($articleId !== $this->getUrl(2)) {
					$articleId = helper::increment($articleId, $this->getData(['page']));
					$articleId = helper::increment($articleId, $this->getData(['module', $this->getUrl(0)]));
					$articleId = helper::increment($articleId, array_keys(self::$actions));
				}
				$this->setData(['module',
					$this->getUrl(0),
					$articleId, [
						'comment' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'comment']),
						'content' => $this->getInput('blogEditContent', null),
						'picture' => $this->getInput('blogEditPicture', helper::FILTER_STRING_SHORT, true),
						'hidePicture' => $this->getInput('blogEditHidePicture', helper::FILTER_BOOLEAN),
						'pictureSize' => $this->getInput('blogEditPictureSize', helper::FILTER_STRING_SHORT),
						'picturePosition' => $this->getInput('blogEditPicturePosition', helper::FILTER_STRING_SHORT),
						'publishedOn' => $this->getInput('blogEditPublishedOn', helper::FILTER_DATETIME, true),
						'state' => $this->getInput('blogEditState', helper::FILTER_BOOLEAN),
						'title' => $this->getInput('blogEditTitle', helper::FILTER_STRING_SHORT, true),
						'userId' => $newuserid,
						'editConsent' => $this->getInput('blogEditConsent') === self::EDIT_GROUP ? $this->getUser('group') : $this->getInput('blogEditConsent'),
						'commentMaxlength' => $this->getInput('blogEditCommentMaxlength'),
						'commentApproved' => $this->getInput('blogEditCommentApproved', helper::FILTER_BOOLEAN),
						'commentClose' => $this->getInput('blogEditCommentClose', helper::FILTER_BOOLEAN),
						'commentNotification'  => $this->getInput('blogEditCommentNotification', helper::FILTER_BOOLEAN),
						'commentGroupNotification' => $this->getInput('blogEditCommentGroupNotification', helper::FILTER_INT)
					]
				]);
				// Supprime l'ancien article
				if($articleId !== $this->getUrl(2)) {
					$this->deleteData(['module', $this->getUrl(0), $this->getUrl(2)]);
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
			// Les membres ne sont pas éditeurs, les exclure de la liste
				if ( $this->getData(['user', $userId, 'group']) < self::GROUP_MODERATOR) {
					unset(self::$users[$userId]);
				}
				$userFirstname = $userFirstname . ' ' . $this->getData(['user', $userId, 'lastname']) . ' (' .  self::$groupEdits[$this->getData(['user', $userId, 'group'])] . ')';
			}
			unset($userFirstname);
			// Valeurs en sortie
			$this->addOutput([
				'title' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'title']),
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
			if($this->getData(['module', $this->getUrl(0), $this->getUrl(1)]) === null) {
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
					$commentId = helper::increment(uniqid(), $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'comment']));
					$content = $this->getInput('blogArticleContent', false);
					$this->setData(['module', $this->getUrl(0), $this->getUrl(1), 'comment', $commentId, [
						'author' => $this->getInput('blogArticleAuthor', helper::FILTER_STRING_SHORT, empty($this->getInput('blogArticleUserId')) ? TRUE : FALSE),
						'content' => $content,
						'createdOn' => time(),
						'userId' => $this->getInput('blogArticleUserId'),
						'approval' => !$this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'commentApproved']) // true commentaire publié false en attente de publication
					]]);
					// Envoi d'une notification aux administrateurs
					// Init tableau
					$to = [];
					// Liste des destinataires
					foreach($this->getData(['user']) as $userId => $user) {
						if ($user['group'] >= $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'commentGroupNotification']) ) {
							$to[] = $user['mail'];
						}
					}
					// Envoi du mail $sent code d'erreur ou de réussite
					$notification = $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'commentApproved']) === true ? 'Commentaire déposé en attente d\'approbation': 'Commentaire déposé';
					if ($this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'commentNotification']) === true) {
						$sent = $this->sendMail(
							$to,
							'Nouveau commentaire',
							'Bonjour,'.'<br/>'. $notification.
							' sur la page "'. $this->getData(['page', $this->getUrl(0), 'title']). '" dans l\'article "'.$this->getUrl(1) .'" :<br/>'.
							$content,
							''
						);
						// Valeurs en sortie
						$this->addOutput([
							'redirect' => helper::baseUrl() . $this->getUrl() . '#comment',
							'notification' => ($sent === true ? $notification . '<br/>Une notification a été envoyée.' : $notification . '<br/> Erreur de notification : ' . $sent),
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
				$commentsApproved = $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'comment']);
				if ($commentsApproved) {
					foreach( $commentsApproved as $key => $value){
						if($value['approval']===false) unset($commentsApproved[$key]);
					}
					// Ligne suivante si affichage du nombre total de commentaires approuvés sous l'article
					self::$nbCommentsApproved = count($commentsApproved);
				}
				$commentIds = array_keys(helper::arrayCollumn($commentsApproved, 'createdOn', 'SORT_DESC'));
				// Pagination
				$pagination = helper::pagination($commentIds, $this->getUrl(),$this->getData(['config','itemsperPage']),'#comment');
				// Liste des pages
				self::$pages = $pagination['pages'];
				// Signature de l'article
				$userIdArticle = $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'userId']);
				switch ($this->getData(['user', $userIdArticle, 'signature'])){
					case 1:
						self::$articleSignature = $userIdArticle;
						break;
					case 2:
						self::$articleSignature = $this->getData(['user', $userIdArticle, 'pseudo']);
						break;
					case 3:
						self::$articleSignature = $this->getData(['user', $userIdArticle, 'firstname']) . ' ' . $this->getData(['user', $userIdArticle, 'lastname']);
						break;
					case 4:
						self::$articleSignature = $this->getData(['user', $userIdArticle, 'lastname']) . ' ' . $this->getData(['user', $userIdArticle, 'firstname']);
						break;
					default:
					self::$articleSignature = $this->getData(['user', $userIdArticle, 'firstname']);
				}
				// Signature du commentaire édité
				if($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')) {
					$useridcomment = $this->getUser('id');
					switch ($this->getData(['user', $useridcomment, 'signature'])){
					case 1:
						self::$editCommentSignature = $useridcomment;
						break;
					case 2:
						self::$editCommentSignature = $this->getData(['user', $useridcomment, 'pseudo']);
						break;
					case 3:
						self::$editCommentSignature = $this->getData(['user', $useridcomment, 'firstname']) . ' ' . $this->getData(['user', $useridcomment, 'lastname']);
						break;
					case 4:
						self::$editCommentSignature = $this->getData(['user', $useridcomment, 'lastname']) . ' ' . $this->getData(['user', $useridcomment, 'firstname']);
						break;
					default:
						self::$editCommentSignature = $this->getData(['user', $useridcomment, 'firstname']);
					}
				}
				// Commentaires en fonction de la pagination
				for($i = $pagination['first']; $i < $pagination['last']; $i++) {
					// Signatures des commentaires
					$e = $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'comment', $commentIds[$i],'userId']);
					if ($e) {
						switch ($this->getData(['user', $e, 'signature'])){
							case 1:
								self::$commentsSignature[$commentIds[$i]] = $e;
								break;
							case 2:
								self::$commentsSignature[$commentIds[$i]] = $this->getData(['user', $e, 'pseudo']);
								break;
							case 3:
								self::$commentsSignature[$commentIds[$i]] = $this->getData(['user', $e, 'firstname']) . ' ' . $this->getData(['user', $e, 'lastname']);
								break;
							case 4:
								self::$commentsSignature[$commentIds[$i]] = $this->getData(['user', $e, 'lastname']) . ' ' . $this->getData(['user', $e, 'firstname']);
								break;
						}
					} else {
						self::$commentsSignature[$commentIds[$i]] = $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'comment', $commentIds[$i],'author']);
					}
					// Données du commentaire si approuvé
					if ($this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'comment', $commentIds[$i],'approval']) === true ) {
						self::$comments[$commentIds[$i]] = $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'comment', $commentIds[$i]]);
					}
				}
				// Valeurs en sortie
				$this->addOutput([
					'showBarEditButton' => true,
					'title' => $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'title']),
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
			$articleIdsPublishedOns = helper::arrayCollumn($this->getData(['module', $this->getUrl(0)]), 'publishedOn', 'SORT_DESC');
			$articleIdsStates = helper::arrayCollumn($this->getData(['module', $this->getUrl(0)]), 'state', 'SORT_DESC');
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
				self::$articles[$articleIds[$i]] = $this->getData(['module', $this->getUrl(0), $articleIds[$i]]);
			}
			// Valeurs en sortie
			$this->addOutput([
				'showBarEditButton' => true,
				'showPageContent' => true,
				'view' => 'index'
			]);
		}
	}
}

