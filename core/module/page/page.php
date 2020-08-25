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
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 */

class page extends common {

	public static $actions = [
		'add' => self::GROUP_MODERATOR,
		'delete' => self::GROUP_MODERATOR,
		'edit' => self::GROUP_MODERATOR
	];
	public static $pagesNoParentId = [
		'' => 'Aucune'
	];
	public static $pagesBarId = [
		'' => 'Aucune'
	];
	public static $moduleIds = [];
	// Nom des modules
	public static $moduleNames = [
		'news'			=> 'Nouvelles',
		'blog' 			=> 'Blog',
		'form' 			=> 'Formulaire',
		'gallery' 		=> 'Galerie',
		'redirection' 	=> 'Redirection',
		'search'        => 'Recherche'
	];
	public static $typeMenu = [
		'text' => 'Texte',
		'icon' => 'Icône',
		'icontitle' => 'Icône avec bulle de texte'
	];
	// Position du module
	public static $modulePosition = [
		'bottom' => 'En bas',
		'top'    => 'En haut',
		'free'   => 'Libre'
	];
	public static $pageBlocks = [
		'12'    => 'Page standard',
		'4-8'   => 'Barre 1/3 - page 2/3',
		'8-4'   => 'Page 2/3 - barre 1/3',
		'3-9'   => 'Barre 1/4 - page 3/4',
		'9-3'   => 'Page 3/4 - barre 1/4',
		'3-6-3' => 'Barre 1/4 - page 1/2 - barre 1/4',
		'2-7-3' => 'Barre 2/12 - page 7/12 - barre 3/12 ',
		'3-7-2' => 'Barre 3/12 - page 7/12 - barre 2/12 ',
		'bar'	=> 'Barre latérale'
	];
	public static $displayMenu = [
		'none'	=> 'Aucun',
		'parents' 	=> 'Le menu',
		'children'	=> 'Le sous-menu de la page parente'
	];

	/**
	 * Création
	 */
	public function add() {
		$pageTitle = 'Nouvelle page';
		$pageId = helper::increment(helper::filter($pageTitle, helper::FILTER_ID), $this->getData(['page']));
		$this->setData([
			'page',
			$pageId,
			[
				'typeMenu' => 'text',
				'iconUrl' => '',
                'disable' => false,
				'content' => 'Contenu de votre nouvelle page.',
				'hideTitle' => false,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => '',
				'parentPageId' => '',
				'modulePosition' => 'bottom',
				'position' => 0,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => $pageTitle,
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => '0',
				'hideMenuSide' => false,
				'hideMenuHead' => false,
				'hideMenuChildren' => false
			]
		]);
		// Met à jour le site map
		$this->createSitemap('all');
		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl() . $pageId,
			'notification' => 'Nouvelle page créée',
			'state' => true
		]);
	}

	/**
	 * Suppression
	 */
	public function delete() {
		// $url prend l'adresse sans le token
		$url = explode('&',$this->getUrl(2));
		// La page n'existe pas
		if($this->getData(['page', $url[0]]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Impossible de supprimer la page d'accueil
		elseif($url[0] === $this->getData(['config', 'homePageId'])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'page/edit/' . $url[0],
				'notification' => 'Impossible de supprimer la page d\'accueil'
			]);
		}
		// Jeton incorrect
		elseif(!isset($_GET['csrf'])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'page/edit/' . $url[0],
				'notification' => 'Jeton invalide'
			]);
		}
		elseif ($_GET['csrf'] !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'page/edit/' . $url[0],
				'notification' => 'Suppression non autorisée'
			]);
		}
		// Impossible de supprimer une page contenant des enfants
		elseif($this->getHierarchy($url[0])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'page/edit/' . $url[0],
				'notification' => 'Impossible de supprimer une page contenant des enfants'
			]);
		}
		// Suppression
		else {
			// Met à jour le site map
			$this->createSitemap('all');
			// Effacer la page
			$this->deleteData(['page', $url[0]]);
			$this->deleteData(['module', $url[0]]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl(false),
				'notification' => 'Page supprimée',
				'state' => true
			]);
		}
	}


	/**
	 * Édition
	 */
	public function edit() {
		// La page n'existe pas
		if($this->getData(['page', $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// La page existe
		else {
			// Soumission du formulaire
			if($this->isPost()) {
				// Génére l'ID si le titre de la page a changé
				if ( $this->getInput('pageEditTitle') !== $this->getData(['page',$this->getUrl(2),'title']) ) {
					$pageId = $this->getInput('pageEditTitle', helper::FILTER_ID, true);
				} else {
					$pageId = $this->getUrl(2);
				}
				// un dossier existe du même nom (erreur en cas de redirection)
				if (file_exists($pageId)) {
					$pageId = uniqid($pageId);
				}
				// Si l'id a changée
				if ($pageId !== $this->getUrl(2)) {
					// Incrémente le nouvel id de la page
						$pageId = helper::increment($pageId, $this->getData(['page']));
						$pageId = helper::increment($pageId, self::$coreModuleIds);
						$pageId = helper::increment($pageId, self::$moduleIds);
					// Met à jour les enfants
					foreach($this->getHierarchy($this->getUrl(2)) as $childrenPageId) {
						$this->setData(['page', $childrenPageId, 'parentPageId', $pageId]);
					}
					// Change l'id de page dans les données des modules
					$this->setData(['module', $pageId, $this->getData(['module', $this->getUrl(2)])]);
					$this->deleteData(['module', $this->getUrl(2)]);
					// Si la page correspond à la page d'accueil, change l'id dans la configuration du site
					if($this->getData(['config', 'homePageId']) === $this->getUrl(2)) {
						$this->setData(['config', 'homePageId', $pageId]);
					}
				}
				// Supprime les données du module en cas de changement de module
				if($this->getInput('pageEditModuleId') !== $this->getData(['page', $this->getUrl(2), 'moduleId'])) {
					$this->deleteData(['module', $pageId]);
				}
				// Supprime l'ancienne page si l'id a changée
				if($pageId !== $this->getUrl(2)) {
					$this->deleteData(['page', $this->getUrl(2)]);
				}
				// Si la page est une page enfant, actualise les positions des autres enfants du parent, sinon actualise les pages sans parents
				$lastPosition = 1;
				$hierarchy = $this->getInput('pageEditParentPageId') ? $this->getHierarchy($this->getInput('pageEditParentPageId')) : array_keys($this->getHierarchy());
				$position = $this->getInput('pageEditPosition', helper::FILTER_INT);
				foreach($hierarchy as $hierarchyPageId) {
					// Ignore la page en cours de modification
					if($hierarchyPageId === $this->getUrl(2)) {
						continue;
					}
					// Incrémente de +1 pour laisser la place à la position de la page en cours de modification
					if($lastPosition === $position) {
						$lastPosition++;
					}
					// Change la position
					$this->setData(['page', $hierarchyPageId, 'position', $lastPosition]);
					// Incrémente pour la prochaine position
					$lastPosition++;
				}
				if ($this->getinput('pageEditBlock') !== 'bar') {
					$barLeft = $this->getinput('pageEditBarLeft');
					$barRight = $this->getinput('pageEditBarRight');
					$hideTitle = $this->getInput('pageEditHideTitle', helper::FILTER_BOOLEAN);

				} else {
					// Une barre ne peut pas avoir de barres
					$barLeft = "";
					$barRight = "";
					// Une barre est masquée
					$position = 0;
					$hideTitle = true;
				}
				// Modifie la page ou en crée une nouvelle si l'id a changé
				$this->setData([
					'page',
					$pageId,
					[
						'typeMenu' => $this->getinput('pageTypeMenu'),
						'iconUrl' => $this->getinput('pageIconUrl'),
						'disable'=> $this->getinput('pageEditDisable', helper::FILTER_BOOLEAN),
						'content' => (empty($this->getInput('pageEditContent', null)) ? '<p>&nbsp;</p>' : $this->getInput('pageEditContent', null)),
						'hideTitle' => $hideTitle,
						'breadCrumb' => $this->getInput('pageEditbreadCrumb', helper::FILTER_BOOLEAN),
						'metaDescription' => $this->getInput('pageEditMetaDescription', helper::FILTER_STRING_LONG),
						'metaTitle' => $this->getInput('pageEditMetaTitle'),
						'moduleId' => $this->getInput('pageEditModuleId'),
						'modulePosition' => $this->getInput('configModulePosition'),
						'parentPageId' => $this->getInput('pageEditParentPageId'),
						'position' => $position,
						'group' => $this->getinput('pageEditBlock') !== 'bar' ? $this->getInput('pageEditGroup', helper::FILTER_INT) : 0,
						'targetBlank' => $this->getInput('pageEditTargetBlank', helper::FILTER_BOOLEAN),
						'title' => $this->getInput('pageEditTitle', helper::FILTER_STRING_SHORT),
						'block' => $this->getinput('pageEditBlock'),
						'barLeft' => $barLeft,
						'barRight' => $barRight,
						'displayMenu' => $this->getinput('pageEditDisplayMenu'),
						'hideMenuSide' => $this->getinput('pageEditHideMenuSide', helper::FILTER_BOOLEAN),
						'hideMenuHead' => $this->getinput('pageEditHideMenuHead', helper::FILTER_BOOLEAN),
						'hideMenuChildren' => $this->getinput('pageEditHideMenuChildren', helper::FILTER_BOOLEAN),
					]
				]);
				// Barre renommée : changement le nom de la barre dans les pages mères
				if ($this->getinput('pageEditBlock') === 'bar') {
					foreach ($this->getHierarchy() as $eachPageId=>$parentId) {
						if ($this->getData(['page',$eachPageId,'barRight']) === $this->getUrl(2)) {
							$this->setData(['page',$eachPageId,'barRight',$pageId]);
						}
						if ($this->getData(['page',$eachPageId,'barLeft']) === $this->getUrl(2)) {
							$this->setData(['page',$eachPageId,'barLeft',$pageId]);
						}
						foreach ($parentId as $childId) {
							if ($this->getData(['page',$childId,'barRight']) === $this->getUrl(2)) {
								$this->setData(['page',$childId,'barRight',$pageId]);
							}
							if ($this->getData(['page',$childId,'barLeft']) === $this->getUrl(2)) {
								$this->setData(['page',$childId,'barLeft',$pageId]);
							}
						}
					}
				}
				// Met à jour le site map
				$this->createSitemap('all');
				// Redirection vers la configuration
				if($this->getInput('pageEditModuleRedirect', helper::FILTER_BOOLEAN)) {
					// Valeurs en sortie
					$this->addOutput([
						'redirect' => helper::baseUrl() . $pageId . '/config',
						'state' => true
					]);
				}
				// Redirection vers la page
				else {
					// Valeurs en sortie
					$this->addOutput([
						'redirect' => helper::baseUrl() . $pageId,
						'notification' => 'Modifications enregistrées',
						'state' => true
					]);
				}
			}
			// Liste des modules
			$moduleIds = [];
			$iterator = new DirectoryIterator('module/');
			foreach($iterator as $fileInfos) {
				if(is_file($fileInfos->getPathname() . '/' . $fileInfos->getFilename() . '.php')) {
					if (array_key_exists($fileInfos->getBasename(),self::$moduleNames)) {
						$moduleIds[$fileInfos->getBasename()] = self::$moduleNames[$fileInfos->getBasename()];
					} else {
						$moduleIds[$fileInfos->getBasename()] = ucfirst($fileInfos->getBasename());
					}
				}
			}
			self::$moduleIds = 	$moduleIds;
			asort(self::$moduleIds);
			self::$moduleIds = array_merge( ['' => 'Aucun'] , self::$moduleIds);
			// Pages sans parent
			foreach($this->getHierarchy() as $parentPageId => $childrenPageIds) {
				if($parentPageId !== $this->getUrl(2)) {
					self::$pagesNoParentId[$parentPageId] = $this->getData(['page', $parentPageId, 'title']);
				}
			}
			// Pages barre latérales
			foreach($this->getHierarchy(null,false,true) as $parentPageId => $childrenPageIds) {
					if($parentPageId !== $this->getUrl(2) &&
						$this->getData(['page', $parentPageId, 'block']) === 'bar') {
						self::$pagesBarId[$parentPageId] = $this->getData(['page', $parentPageId, 'title']);
					}
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => $this->getData(['page', $this->getUrl(2), 'title']),
				'vendor' => [
					'tinymce'
				],
				'view' => 'edit'
			]);
		}
	}

}
