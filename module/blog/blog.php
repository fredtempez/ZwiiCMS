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
 * @link http://zwiicms.com/
 */

class blog extends common {

	// Objets
	// Propriétaire - groupe Editeur - groupe Admin
	const EDIT_ALL          = '011'; // Groupes Editeurs et admins
	const EDIT_OWNER_ADMIN  = '101'; // Propriétaire éditeur + groupe admin
	const EDIT_ADMIN        = '001'; // Groupe des admin

	public static $actions = [
		'add' => self::GROUP_MODERATOR,
		'comment' => self::GROUP_MODERATOR,
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

	// Permission d'un article
	public static $articlePermissions = [
		self::EDIT_ALL     	   => 'Editeurs et administrateurs',
		self::EDIT_OWNER_ADMIN => 'Auteur et groupe des administrateurs',
		self::EDIT_ADMIN       => 'Groupe des administrateurs',
	];

	public static $users = [];

	const BLOG_VERSION = '3.00.dev';

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
			$this->setData(['module', $this->getUrl(0), $articleId, [
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
				'userId' => $newuserid,
				'commentMaxlength' => $this->getInput('blogAddlength', null)
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
			self::$comments[] = [
				utf8_encode(strftime('%d %B %Y - %H:%M', $comment['createdOn'])),
				$comment['content'],
				$comment['userId'] ? $this->getData(['user', $comment['userId'], 'firstname']) . ' ' . $this->getData(['user', $comment['userId'], 'lastname']) : $comment['author'],
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
	 * Configuration
	 */
	public function config() {
		// Ids des articles par ordre de publication
		$articleIds = array_keys(helper::arrayCollumn($this->getData(['module', $this->getUrl(0)]), 'publishedOn', 'SORT_DESC'));
		// Pagination
		$pagination = helper::pagination($articleIds, $this->getUrl(),$this->getData(['config','itemsperPage']));
		// Liste des pages
		self::$pages = $pagination['pages'];
		// Articles en fonction de la pagination
		for($i = $pagination['first']; $i < $pagination['last']; $i++) {
			// Nombre de commentaires à approuver et approuvés
			if ( !empty(helper::arrayCollumn($this->getData(['module', $this->getUrl(0),  $articleIds[$i], 'comment' ]),'approval', 'SORT_DESC'))) {
				$a = array_values(helper::arrayCollumn($this->getData(['module', $this->getUrl(0),  $articleIds[$i], 'comment' ]),'approval', 'SORT_DESC'));
				$toApprove = count(array_keys($a,false));
				$approved = count(array_keys($a,true));
			} else {
				$toApprove = 0;
				$approved = count($this->getData(['module', $this->getUrl(0), $articleIds[$i],'comment']));
			}

			// Met en forme le tableau
			self::$articles[] = [
				$this->getData(['module', $this->getUrl(0), $articleIds[$i], 'title']),
				// date('d/m/Y H:i', $this->getData(['module', $this->getUrl(0), $articleIds[$i], 'publishedOn'])),
				utf8_encode(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), $articleIds[$i], 'publishedOn'])))
				.' à '.
				utf8_encode(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), $articleIds[$i], 'publishedOn']))),
				self::$states[$this->getData(['module', $this->getUrl(0), $articleIds[$i], 'state'])],
				// Bouton pour afficher les commentaires de l'article
				template::button('blogConfigComment' . $articleIds[$i], [
					'class' => $toApprove == 0 ?  'buttonGrey' : 'buttonBlue' ,
					'href' => $toApprove > 0 ? helper::baseUrl() . $this->getUrl(0) . '/comment/' . $articleIds[$i] : '',
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
				$this->setData(['module', $this->getUrl(0), $articleId, [
					'closeComment' => $this->getInput('blogEditCloseComment', helper::FILTER_BOOLEAN),
					'mailNotification'  => $this->getInput('blogEditMailNotification', helper::FILTER_BOOLEAN),
					'groupNotification' => $this->getInput('blogEditGroupNotification', helper::FILTER_INT),
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
					'commentMaxlength' => $this->getInput('blogEditCommentMaxlength'),
					'commentApprove' => $this->getInput('blogEditCommentApprove', helper::FILTER_BOOLEAN)
				]]);
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
				$userFirstname = $userFirstname . ' ' . $this->getData(['user', $userId, 'lastname']);
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
					// Check la capcha
					if(
						$this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
						AND $this->getInput('blogArticleCapcha', helper::FILTER_INT) !== $this->getInput('blogArticleCapchaFirstNumber', helper::FILTER_INT) + $this->getInput('blogArticleCapchaSecondNumber', helper::FILTER_INT))
					{
						self::$inputNotices['blogArticleCapcha'] = 'Incorrect';
					}
					// Crée le commentaire
					$commentId = helper::increment(uniqid(), $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'comment']));
					$this->setData(['module', $this->getUrl(0), $this->getUrl(1), 'comment', $commentId, [
						'author' => $this->getInput('blogArticleAuthor', helper::FILTER_STRING_SHORT, empty($this->getInput('blogArticleUserId')) ? TRUE : FALSE),
						'content' => $this->getInput('blogArticleContent', false),
						'createdOn' => time(),
						'userId' => $this->getInput('blogArticleUserId'),
						'approval' => !$this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'commentApprove']) // true commentaire publié false en attente de publication
					]]);
					// Envoi d'une notification aux administrateurs
					// Init tableau
					$to = [];
					// Liste des destinataires
					foreach($this->getData(['user']) as $userId => $user) {
						if ($user['group'] >= $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'groupNotification']) ) {
							$to[] = $user['mail'];
						}
					}
					// Envoi du mail $sent code d'erreur ou de réussite
					if ($this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'mailNotification']) === true) {
						$sent = $this->sendMail(
							$to,
							'Nouveau commentaire',
							'Bonjour' . ' <strong>' . $user['firstname'] . ' ' . $user['lastname'] . '</strong>,<br><br>' .
							'Nouveau commentaire déposé sur la page "' . $this->getData(['page', $this->getUrl(0), 'title']) . '" :<br><br>'.
							$this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'comment', $commentId, 'content']),
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
				$commentIds = array_keys(helper::arrayCollumn($this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'comment']), 'createdOn', 'SORT_DESC'));
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
					// Données du commentaire
					self::$comments[$commentIds[$i]] = $this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'comment', $commentIds[$i]]);
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
