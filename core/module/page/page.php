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
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class page extends common
{

	public static $actions = [
		'add' => self::GROUP_MODERATOR,
		'delete' => self::GROUP_MODERATOR,
		'edit' => self::GROUP_MODERATOR,
		'duplicate' => self::GROUP_MODERATOR,
		'jsEditor' => self::GROUP_MODERATOR,
		'cssEditor' => self::GROUP_MODERATOR
	];
	public static $pagesNoParentId = [
		'' => 'Aucune'
	];
	public static $pagesBarId = [
		'' => 'Aucune'
	];
	public static $moduleIds = [];

	public static $typeMenu = [
		'text' => 'Texte',
		'icon' => 'Icône',
		'icontitle' => 'Icône avec bulle de texte'
	];
	// Position du module
	public static $modulePosition = [
		'bottom' => 'Après le contenu de la page',
		'top'    => 'Avant le contenu de la page',
		'free'   => 'À l\'emplacement du mot clé [MODULE] dans la page'
	];
	public static $pageBlocks = [
		'12'    => 'Page standard',
		'bar'	=> 'Barre latérale',
		'4-8'   => 'Barre 1/3 - page 2/3',
		'8-4'   => 'Page 2/3 - barre 1/3',
		'3-9'   => 'Barre 1/4 - page 3/4',
		'9-3'   => 'Page 3/4 - barre 1/4',
		'3-6-3' => 'Barre 1/4 - page 1/2 - barre 1/4',
		'2-7-3' => 'Barre 2/12 - page 7/12 - barre 3/12',
		'3-7-2' => 'Barre 3/12 - page 7/12 - barre 2/12',
	];
	public static $displayMenu = [
		'none'		=> 'Aucun menu',
		'parents' 	=> 'Le menu horizontal intégral',
		'children'	=> 'Le sous-menu de la page parente'
	];
	public static $extraPosition = [
		false 	=> 'Menu standard',
		true 	=> 'Menu accessoire'
	];


	/**
	 * Duplication
	 */
	public function duplicate()
	{
		// Adresse sans le token
		$url = explode('&', $this->getUrl(2));
		// La page n'existe pas
		if ($this->getData(['page', $url[0]]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} // Jeton incorrect
		elseif (!isset($_GET['csrf'])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'page/edit/' . $url[0],
				'notification' => helper::translate('Jeton invalide')
			]);
		} elseif ($_GET['csrf'] !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'page/edit/' . $url[0],
				'notification' => helper::translate('Suppression interdite')
			]);
		}
		// Duplication de la page
		$pageTitle = $this->getData(['page', $url[0], 'title']);
		$pageId = helper::increment(helper::filter($pageTitle, helper::FILTER_ID), $this->getData(['page']));
		$pageId = helper::increment($pageId, self::$coreModuleIds);
		$pageId = helper::increment($pageId, self::$moduleIds);
		$data = $this->getData([
			'page',
			$url[0]
		]);
		// Ecriture
		$this->setData(['page', $pageId, $data]);
		$notification = helper::translate('Page dupliquée');
		// Duplication du module présent
		if ($this->getData(['page', $url[0], 'moduleId'])) {
			$data = $this->getData([
				'module',
				$url[0]
			]);
			// Ecriture
			$this->setData(['module', $pageId, $data]);
			$notification = helper::translate('Page et module dupliqués');
		}
		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl() . 'page/edit/' . $pageId,
			'notification' => $notification,
			'state' => true
		]);
	}


	/**
	 * Création
	 */
	public function add()
	{
		$pageTitle = 'Nouvelle page';
		$pageId = helper::increment(helper::filter($pageTitle, helper::FILTER_ID), $this->getData(['page']));
		$this->setData([
			'page',
			$pageId,
			[
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' =>  $pageId . '.html',
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
				'shortTitle' => $pageTitle,
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => '0',
				'hideMenuSide' => false,
				'hideMenuHead' => false,
				'hideMenuChildren' => false,
				'js' => '',
				'css' => ''
			]
		]);
		// Creation du contenu de la page
		if (!is_dir(self::DATA_DIR . self::$i18nContent . '/content')) {
			mkdir(self::DATA_DIR . self::$i18nContent . '/content', 0755);
		}
		//file_put_contents(self::DATA_DIR . self::$i18nContent . '/content/' . $pageId . '.html', '<p>Contenu de votre nouvelle page.</p>');
		$this->setPage($pageId, '<p>Contenu de votre nouvelle page.</p>', self::$i18nContent);
		// Met à jour le site map
		$this->createSitemap('all');
		// Mise à jour de la liste des pages pour TinyMCE
		$this->listPages();
		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl() . $pageId,
			'notification' => helper::translate('Nouvelle page créée'),
			'state' => true
		]);
	}

	/**
	 * Suppression
	 */
	public function delete()
	{
		// $url prend l'adresse sans le token
		$url = explode('&', $this->getUrl(2));
		// La page n'existe pas
		if ($this->getData(['page', $url[0]]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}		// Jeton incorrect
		elseif (!isset($_GET['csrf'])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'page/edit/' . $url[0],
				'notification' => helper::translate('Jeton invalide')
			]);
		} elseif ($_GET['csrf'] !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'page/edit/' . $url[0],
				'notification' => helper::translate('Suppression interdite')
			]);
		}
		// Impossible de supprimer la page d'accueil
		elseif ($url[0] === $this->getData(['locale', 'homePageId'])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'config',
				'notification' => helper::translate('Suppression interdite, page active dans la configuration du site')
			]);
		}
		// Impossible de supprimer la page de recherche affectée
		elseif ($url[0] === $this->getData(['locale', 'searchPageId'])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'config',
				'notification' => helper::translate('Suppression interdite, page active dans la configuration du site')
			]);
		}
		// Impossible de supprimer la page des mentions légales affectée
		elseif ($url[0] === $this->getData(['locale', 'legalPageId'])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'config',
				'notification' => helper::translate('Suppression interdite, page active dans la configuration du site')
			]);
		}
		// Impossible de supprimer la page des mentions légales affectée
		elseif ($url[0] === $this->getData(['locale', 'page404'])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'config',
				'notification' => helper::translate('Suppression interdite, page active dans la configuration du site')
			]);
		}
		// Impossible de supprimer la page des mentions légales affectée
		elseif ($url[0] === $this->getData(['locale', 'page403'])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'config',
				'notification' => helper::translate('Suppression interdite, page active dans la configuration du site')
			]);
		}
		// Impossible de supprimer la page des mentions légales affectée
		elseif ($url[0] === $this->getData(['locale', 'page302'])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'config',
				'notification' => helper::translate('Suppression interdite, page active dans la configuration du site')
			]);
		}
		// Jeton incorrect
		elseif (!isset($_GET['csrf'])) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'page/edit/' . $url[0],
				'notification' =>  helper::translate('Jeton invalide')
			]);
		} elseif ($_GET['csrf'] !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'page/edit/' . $url[0],
				'notification' =>  helper::translate('Suppression interdite')
			]);
		}
		// Impossible de supprimer une page contenant des enfants
		elseif ($this->getHierarchy($url[0], null)) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'page/edit/' . $url[0],
				'notification' =>  helper::translate('Impossible de supprimer une page contenant des pages enfants')
			]);
		}
		// Suppression
		else {

			// Effacer le dossier du module
			$moduleId = $this->getData(['page', $url[0], 'moduleId']);
			$modulesData = helper::getModules();
			if (
				array_key_exists($moduleId, $modulesData)
				&&  is_dir($modulesData[$moduleId]['dataDirectory'] . $url[0])
			) {
				$this->removeDir($modulesData[$moduleId]['dataDirectory'] . $url[0]);
			}
			// Effacer la page
			$this->deleteData(['page', $url[0]]);
			if (file_exists(self::DATA_DIR . self::$i18nContent . '/content/' . $url[0] . '.html')) {
				unlink(self::DATA_DIR . self::$i18nContent . '/content/' . $url[0] . '.html');
			}
			$this->deleteData(['module', $url[0]]);
			// Met à jour le site map
			$this->createSitemap('all');
			// Mise à jour de la liste des pages pour TinyMCE
			$this->listPages();
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl(false),
				'notification' => helper::translate('Page supprimée'),
				'state' => true
			]);
		}
	}


	/**
	 * Édition
	 */
	public function edit()
	{
		// La page n'existe pas
		if ($this->getData(['page', $this->getUrl(2)]) === null) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// La page existe
		else {
			// Soumission du formulaire
			if ($this->isPost()) {
				// Si le Title n'est pas vide, premier test pour positionner la notification du champ obligatoire
				if ($this->getInput('pageEditTitle', helper::FILTER_ID, true) !== null && $this->getInput('pageEditTitle') !== '') {
					// Génére l'ID si le titre de la page a changé
					if ($this->getInput('pageEditTitle') !== $this->getData(['page', $this->getUrl(2), 'title'])) {
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
						foreach ($this->getHierarchy($this->getUrl(2), null) as $childrenPageId) {
							$this->setData(['page', $childrenPageId, 'parentPageId', $pageId]);
						}
						// Change l'id de page dans les données des modules
						if ($this->getData(['module', $this->getUrl(2)]) !== null) {
							$this->setData(['module', $pageId, $this->getData(['module', $this->getUrl(2)])]);
							$this->deleteData(['module', $this->getUrl(2)]);
							// Renommer le dossier du module
							$moduleId = $this->getData(['page', $this->getUrl(2), 'moduleId']);
							$modulesData = helper::getModules();
							if (is_dir($modulesData[$moduleId]['dataDirectory'] . $this->getUrl(2))) {
								// Placer la feuille de style dans un dossier au nom de la nouvelle instance
								mkdir($modulesData[$moduleId]['dataDirectory'] . $pageId, 0755);
								copy($modulesData[$moduleId]['dataDirectory'] . $this->getUrl(2), $modulesData[$moduleId]['dataDirectory'] . $pageId);
								$this->removeDir($modulesData[$moduleId]['dataDirectory'] . $this->getUrl(2));
								// Mettre à jour le nom de la feuille de style
								$this->setData(['module', $pageId, 'theme', 'style', $modulesData[$moduleId]['dataDirectory'] . $pageId]);
							}
						}
						// Si la page correspond à la page d'accueil, change l'id dans la configuration du site
						if ($this->getData(['locale', 'homePageId']) === $this->getUrl(2)) {
							$this->setData(['locale', 'homePageId', $pageId]);
						}
					}
					// Supprime les données du module en cas de changement de module
					if ($this->getInput('pageEditModuleId') !== $this->getData(['page', $this->getUrl(2), 'moduleId'])) {
						$this->deleteData(['module', $pageId]);
					}
					// Supprime l'ancienne page si l'id a changée
					if ($pageId !== $this->getUrl(2)) {
						$this->deleteData(['page', $this->getUrl(2)]);
						if (file_exists(self::DATA_DIR . self::$i18nContent . '/content/' . $this->getUrl(2) . '.html')) {
							unlink(self::DATA_DIR . self::$i18nContent . '/content/' . $this->getUrl(2) . '.html');
						}
					}
					// Traitement des pages spéciales affectées dans la config :
					if ($this->getUrl(2) === $this->getData(['locale', 'legalPageId'])) {
						$this->setData(['locale', 'legalPageId', $pageId]);
					}
					if ($this->getUrl(2) === $this->getData(['locale', 'searchPageId'])) {
						$this->setData(['locale', 'searchPageId', $pageId]);
					}
					if ($this->getUrl(2) === $this->getData(['locale', 'page404'])) {
						$this->setData(['locale', 'page404', $pageId]);
					}
					if ($this->getUrl(2) === $this->getData(['locale', 'page403'])) {
						$this->setData(['locale', 'page403', $pageId]);
					}
					if ($this->getUrl(2) === $this->getData(['locale', 'page302'])) {
						$this->setData(['locale', 'page302', $pageId]);
					}
					// Si la page est une page enfant, actualise les positions des autres enfants du parent, sinon actualise les pages sans parents
					$lastPosition = 1;
					$hierarchy = $this->getInput('pageEditParentPageId') ? $this->getHierarchy($this->getInput('pageEditParentPageId')) : array_keys($this->getHierarchy());
					$position = $this->getInput('pageEditPosition', helper::FILTER_INT);
					$extraPosition = $this->getinput('pageEditExtraPosition', helper::FILTER_BOOLEAN);
					foreach ($hierarchy as $hierarchyPageId) {

						// Ne traite que les pages du menu sélectionné
						if ($this->getData(['page', $hierarchyPageId, 'extraPosition']) === $extraPosition) {
							// Ignore la page en cours de modification 
							if ($hierarchyPageId === $this->getUrl(2)) {
								continue;
							}
							// Incrémente de +1 pour laisser la place à la position de la page en cours de modification
							if ($lastPosition === $position) {
								$lastPosition++;
							}
							// Change la position
							$this->setData(['page', $hierarchyPageId, 'position', $lastPosition]);
							// Incrémente pour la prochaine position
							$lastPosition++;
						}
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
					// Une page parent devient orpheline, les pages enfants le devienne pour éviter une incohérence
					if (
						$position === 0 &&
						$position !==  $this->getData(['page', $this->getUrl(2), 'position']) &&
						$this->getinput('pageEditBlock') !== 'bar'
					) {
						foreach ($this->getHierarchy($pageId) as $parentId => $childId) {
							if ($this->getData(['page', $childId, 'parentPageId']) === $pageId) {
								$this->setData(['page', $childId, 'position', 0]);
							}
						}
					}

					// La page est une barre latérale qui a été renommée : changer le nom de la barre dans les pages qui l'utilisent
					if ($this->getinput('pageEditBlock') === 'bar') {
						foreach ($this->getHierarchy() as $eachPageId => $parentId) {
							if ($this->getData(['page', $eachPageId, 'barRight']) === $this->getUrl(2)) {
								$this->setData(['page', $eachPageId, 'barRight', $pageId]);
							}
							if ($this->getData(['page', $eachPageId, 'barLeft']) === $this->getUrl(2)) {
								$this->setData(['page', $eachPageId, 'barLeft', $pageId]);
							}
							foreach ($parentId as $childId) {
								if ($this->getData(['page', $childId, 'barRight']) === $this->getUrl(2)) {
									$this->setData(['page', $childId, 'barRight', $pageId]);
								}
								if ($this->getData(['page', $childId, 'barLeft']) === $this->getUrl(2)) {
									$this->setData(['page', $childId, 'barLeft', $pageId]);
								}
							}
						}
					}

					// Modifie la page ou en crée une nouvelle si l'id a changé
					$this->setData([
						'page',
						$pageId,
						[
							'typeMenu' => $this->getinput('pageTypeMenu'),
							'iconUrl' => $this->getinput('pageIconUrl'),
							'disable' => $this->getinput('pageEditDisable', helper::FILTER_BOOLEAN),
							'content' => $pageId . '.html',
							'hideTitle' => $hideTitle,
							'breadCrumb' => $this->getInput('pageEditbreadCrumb', helper::FILTER_BOOLEAN),
							'metaDescription' => $this->getInput('pageEditMetaDescription', helper::FILTER_STRING_LONG),
							'metaTitle' => $this->getInput('pageEditMetaTitle'),
							'moduleId' => $this->getInput('pageEditModuleId'),
							'modulePosition' => $this->getInput('pageModulePosition'),
							'parentPageId' => $this->getInput('pageEditParentPageId'),
							'position' => $position,
							'group' => $this->getinput('pageEditBlock') !== 'bar' ? $this->getInput('pageEditGroup', helper::FILTER_INT) : 0,
							'targetBlank' => $this->getInput('pageEditTargetBlank', helper::FILTER_BOOLEAN),
							'title' => $this->getInput('pageEditTitle', helper::FILTER_STRING_SHORT),
							'shortTitle' => $this->getInput('pageEditShortTitle', helper::FILTER_STRING_SHORT, true),
							'block' => $this->getinput('pageEditBlock'),
							'barLeft' => $barLeft,
							'barRight' => $barRight,
							'displayMenu' => $this->getinput('pageEditDisplayMenu'),
							'hideMenuSide' => $this->getinput('pageEditHideMenuSide', helper::FILTER_BOOLEAN),
							'hideMenuHead' => $this->getinput('pageEditHideMenuHead', helper::FILTER_BOOLEAN),
							'hideMenuChildren' => $this->getinput('pageEditHideMenuChildren', helper::FILTER_BOOLEAN),
							'extraPosition' => $this->getinput('pageEditExtraPosition', helper::FILTER_BOOLEAN),
							'css' => $this->getData(['page', $this->getUrl(2), 'css']) == null ? '' : $this->getData(['page', $this->getUrl(2), 'css']),
							'js' => $this->getData(['page', $this->getUrl(2), 'js']) == null ? '' : $this->getData(['page', $this->getUrl(2), 'js']),
						]
					]);

					// Creation du contenu de la page
					if (!is_dir(self::DATA_DIR . self::$i18nContent . '/content')) {
						mkdir(self::DATA_DIR . self::$i18nContent . '/content', 0755);
					}
					$content = empty($this->getInput('pageEditContent', null)) ? '<p></p>' : str_replace('<p></p>', '<p>&nbsp;</p>', $this->getInput('pageEditContent', null));
					$this->setPage($pageId, $content, self::$i18nContent);

					// Met à jour le site map
					$this->createSitemap('all');
					// Mise à jour de la liste des pages pour TinyMCE
					$this->listPages();

					// Redirection vers la configuration
					if (
						$this->getInput('pageEditModuleRedirect', helper::FILTER_BOOLEAN)
					) {
						// Valeurs en sortie
						$this->addOutput([
							'redirect' => helper::baseUrl() . $pageId . '/config',
							'state' => true
						]);
						// Redirection vers la page
					} else {
						// Valeurs en sortie
						$this->addOutput([
							'redirect' => helper::baseUrl() . $pageId,
							'notification' => helper::translate('Modifications enregistrées'),
							'state' => true
						]);
					}
				}
			}
			self::$moduleIds = array_merge(['' => 'Aucun'], helper::arrayColumn(helper::getModules(), 'realName', 'SORT_ASC'));			// Pages sans parent
			foreach ($this->getHierarchy() as $parentPageId => $childrenPageIds) {
				if ($parentPageId !== $this->getUrl(2)) {
					self::$pagesNoParentId[$parentPageId] = $this->getData(['page', $parentPageId, 'title']);
				}
			}
			// Pages barre latérales
			foreach ($this->getHierarchy(null, false, true) as $parentPageId => $childrenPageIds) {
				if (
					$parentPageId !== $this->getUrl(2) &&
					$this->getData(['page', $parentPageId, 'block']) === 'bar'
				) {
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

	/**
	 * Éditeur de feuille de style
	 */
	public function cssEditor()
	{
		// Soumission du formulaire
		if ($this->isPost()) {
			$css = $this->getInput('pageCssEditorContent') ===  null ? '': $this->getInput('pageCssEditorContent');	
			// Enregistre le CSS
			$this->setData([
				'page', $this->getUrl(2), 'css',
				$css
			]);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => helper::translate('Modifications enregistrées'),
				'redirect' => helper::baseUrl() . 'page/edit/' . $this->getUrl(2),
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Éditeur CSS'),
			'vendor' => [
				'codemirror'
			],
			'view' => 'cssEditor'
		]);
	}

	/**
	 * Éditeur de feuille de style
	 */
	public function jsEditor()
	{
		// Soumission du formulaire
		if ($this->isPost()) {
			$js = $this->getInput('pageJsEditorContent') === null ? '' : $this->getInput('pageJsEditorContent');
			// Enregistre le JS
			$this->setData([
				'page', $this->getUrl(2), 'js',
				$js
			]);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => helper::translate('Modifications enregistrées'),
				'redirect' => helper::baseUrl() . 'page/edit/' . $this->getUrl(2),
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Éditeur Js'),
			'vendor' => [
				'codemirror'
			],
			'view' => 'jsEditor'
		]);
	}
}
