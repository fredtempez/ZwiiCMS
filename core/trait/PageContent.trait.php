<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International 
 * @Copyright (C) 2026, Frédéric Tempez
 * @Licensed under the GNU General Public License v3.0 or later.
 * @link http://zwiicms.fr/
 */
trait PageContent {

	/**
	 * Lire les données de la page
	 * @param string pageId
	 * @param string langue
	 * @return string contenu de la page
	 */
	public function getPage($page, $lang)
	{
		// Le nom de la ressource et le fichier de contenu sont définis :
		if (
			$this->getData(['page', $page, 'content']) !== '' &&
			file_exists(self::DATA_DIR . $lang . '/content/' . $this->getData(['page', $page, 'content']))
		) {
			return file_get_contents(self::DATA_DIR . $lang . '/content/' . $this->getData(['page', $page, 'content']));
		} else {
			return 'Aucun contenu trouvé.';
		}
	}

	/**
	 * Ecrire les données de la page
	 * @param string pageId
	 * @param string contenu de la page
	 * @return int nombre d'octets écrits ou erreur
	 */
	public function setPage($page, $value, $lang)
	{
		return $this->secure_file_put_contents(self::DATA_DIR . $lang . '/content/' . $page . '.html', $value);
	}

    	/**
	 * Effacer les données de la page
	 * @param string pageId
	 * @return bool statut de l'effacement
	 */
	public function deletePage($page, $lang)
	{
		return unlink(self::DATA_DIR . $lang . '/content/' . $this->getData(['page', $page, 'content']));
	}

    	/**
	 * Accède à la liste des pages parents et de leurs enfants
	 * @param int $parentId Id de la page parent
	 * @param bool $onlyVisible Affiche seulement les pages visibles
	 * @param bool $onlyBlock Affiche seulement les pages de type barre
	 * @return array
	 */
	public function getHierarchy($parentId = null, $onlyVisible = true, $onlyBlock = false)
	{
		$hierarchy = $onlyVisible ? $this->hierarchy['visible'] : $this->hierarchy['all'];
		$hierarchy = $onlyBlock ? $this->hierarchy['bar'] : $hierarchy;
		// Enfants d'un parent
		if ($parentId) {
			if (array_key_exists($parentId, $hierarchy)) {
				return $hierarchy[$parentId];
			} else {
				return [];
			}
		}
		// Parents et leurs enfants
		else {
			return $hierarchy;
		}
	}

	/**
	 * Fonction pour construire le tableau des pages
	 * Appelée par le core uniquement
	 */
	private function buildHierarchy()
	{
		$pages = Helper::arrayColumn($this->getData(['page']), 'position', 'SORT_ASC');
		// Parents
		foreach ($pages as $pageId => $pagePosition) {
			if (
				// Page parent
				$this->getData(['page', $pageId, 'parentPageId']) === '' and
					// Ignore les pages dont l'utilisateur n'a pas accès
				($this->getData(['page', $pageId, 'role']) === self::ROLE_VISITOR or
					($this->isConnected() === true and
							// and $this->getUser('role') >= $this->getData(['page', $pageId, 'role'])
							// Modification qui tient compte du profil de la page
						($this->getUser('role') * self::MAX_PROFILS + $this->getUser('profil')) >= ($this->getData(['page', $pageId, 'role']) * self::MAX_PROFILS + $this->getData(['page', $pageId, 'profil']))))
			) {
				if ($pagePosition !== 0) {
					$this->hierarchy['visible'][$pageId] = [];
				}
				if ($this->getData(['page', $pageId, 'block']) === 'bar') {
					$this->hierarchy['bar'][$pageId] = [];
				}
				$this->hierarchy['all'][$pageId] = [];
			}
		}
		// Enfants
		foreach ($pages as $pageId => $pagePosition) {
			if (
				// Page parent
				$parentId = $this->getData(['page', $pageId, 'parentPageId']) and
					// Ignore les pages dont l'utilisateur n'a pas accès
				(
					(
						$this->getData(['page', $pageId, 'role']) === self::ROLE_VISITOR and
						$this->getData(['page', $parentId, 'role']) === self::ROLE_VISITOR
					) or (
						$this->isConnected() === true and
						$this->getUser('role') * self::MAX_PROFILS + $this->getUser('profil')
					) >= ($this->getData(['page', $pageId, 'role']) * self::MAX_PROFILS + $this->getData(['page', $pageId, 'profil']))
				)
			) {
				if ($pagePosition !== 0) {
					$this->hierarchy['visible'][$parentId][] = $pageId;
				}
				if ($this->getData(['page', $pageId, 'block']) === 'bar') {
					$this->hierarchy['bar'][$pageId] = [];
				}
				$this->hierarchy['all'][$parentId][] = $pageId;
			}
		}
	}

	/**
	 * Génère un fichier json avec la liste des pages
	 */
	private function tinyMcePages()
	{
		// Sauve la liste des pages pour TinyMCE
		$parents = [];
		$rewrite = (Helper::checkRewrite()) ? '' : '?';

		// Boucle de recherche des pages actives avec hiérarchie
		foreach ($this->getHierarchy() as $parentId => $childIds) {
			// Ne traiter que les pages racines (parentPageId vide)
			if ($this->getData(['page', $parentId, 'parentPageId']) !== '') {
				continue;
			}

			if ($this->getData(['page', $parentId]) && $this->getData(['page', $parentId, 'block']) !== 'bar') {
				// Ajouter la page parent
				$pageEntry = [
					'title' => html_entity_decode($this->getData(['page', $parentId, 'title']), ENT_QUOTES),
					'value' => $rewrite . $parentId,
					'menu' => []
				];

				// Ajouter les enfants récursivement
				$this->addTinyMceChildren($pageEntry['menu'], $childIds, $rewrite, 1);

				$parents[] = $pageEntry;
			}
		}
		// Sitemap et Search
		$children = [];
		$children[] = [
			'title' => 'Rechercher dans le site',
			'value' => $rewrite . 'search'
		];
		$children[] = [
			'title' => 'Plan du site',
			'value' => $rewrite . 'sitemap'
		];
		$parents[] = [
			'title' => 'Pages spéciales',
			'value' => '#',
			'menu' => $children
		];

		// Enregistrement : 3 tentatives
		for ($i = 0; $i < 3; $i++) {
			if (file_put_contents('core/vendor/tinymce/link_list.json', json_encode($parents, JSON_UNESCAPED_UNICODE), LOCK_EX) !== false) {
				break;
			}
			// Pause de 10 millisecondes
			usleep(10000);
		}
	}

    	/**
	 * Génère un fichier un fichier sitemap.xml
	 * https://github.com/icamys/php-sitemap-generator
	 * all : génère un site map complet
	 * Sinon contient id de la page à créer
	 * @param string Valeurs possibles
	 */
	public function updateSitemap()
	{
		// Le drapeau prend true quand au moins une page est trouvée
		$flag = false;

		// Rafraîchit la liste des pages après une modification de pageId notamment
		$this->buildHierarchy();

		// Actualise la liste des pages pour TinyMCE
		$this->tinyMcePages();

		// require_once 'core/vendor/sitemap/SitemapGenerator.php';

		$timezone = $this->getData(['config', 'timezone']);
		$outputDir = getcwd();
		$sitemap = new \Icamys\SitemapGenerator\SitemapGenerator(Helper::baseurl(false), $outputDir);

		// will create also compressed (gzipped) sitemap : option buguée
		// $sitemap->enableCompression();

		// determine how many urls should be put into one file
		// according to standard protocol 50000 is maximum value (see http://www.sitemaps.org/protocol.html)
		$sitemap->setMaxUrlsPerSitemap(50000);

		// sitemap file name
		$sitemap->setSitemapFileName('sitemap.xml');

		// Set the sitemap index file name
		$sitemap->setSitemapIndexFileName('sitemap-index.xml');

		$datetime = new DateTime(date('c'));
		$datetime->format(DateTime::ATOM);  // Updated ISO8601

		foreach ($this->getHierarchy() as $parentPageId => $childrenPageIds) {
			// Exclure les barres et les pages non publiques et les pages masquées
			if (
				$this->getData(['page', $parentPageId, 'role']) !== 0 ||
				$this->getData(['page', $parentPageId, 'block']) === 'bar'
			) {
				continue;
			}
			// Page désactivée, traiter les sous-pages sans prendre en compte la page parente.
			if ($this->getData(['page', $parentPageId, 'disable']) !== true) {
				// Cas de la page d'accueil ne pas dupliquer l'URL
				$pageId = ($parentPageId !== $this->getData(['locale', 'homePageId'])) ? $parentPageId : '';
				$sitemap->addUrl('/' . $pageId, $datetime);
				$flag = true;
			}
			// Articles du blog
			if (
				$this->getData(['page', $parentPageId, 'moduleId']) === 'blog' &&
				!empty($this->getData(['module', $parentPageId])) &&
				$this->getData(['module', $parentPageId, 'posts'])
			) {
				foreach ($this->getData(['module', $parentPageId, 'posts']) as $articleId => $article) {
					if ($this->getData(['module', $parentPageId, 'posts', $articleId, 'state']) === true) {
						$date = $this->getData(['module', $parentPageId, 'posts', $articleId, 'publishedOn']);
						$sitemap->addUrl('/' . $parentPageId . '/' . $articleId, DateTime::createFromFormat('U', $date));
					}
				}
			}
			// Sous-pages
			foreach ($childrenPageIds as $childKey) {
				if ($this->getData(['page', $childKey, 'role']) !== 0 || $this->getData(['page', $childKey, 'disable']) === true) {
					continue;
				}
				// Cas de la page d'accueil ne pas dupliquer l'URL
				$pageId = ($childKey !== $this->getData(['locale', 'homePageId'])) ? $childKey : '';
				$sitemap->addUrl('/' . $childKey, $datetime);
				$flag = true;

				// La sous-page est un blog
				if (
					$this->getData(['page', $childKey, 'moduleId']) === 'blog' &&
					!empty($this->getData(['module', $childKey]))
				) {
					foreach ($this->getData(['module', $childKey, 'posts']) as $articleId => $article) {
						if ($this->getData(['module', $childKey, 'posts', $articleId, 'state']) === true) {
							$date = $this->getData(['module', $childKey, 'posts', $articleId, 'publishedOn']);
							$sitemap->addUrl('/' . $childKey . '/' . $articleId, new DateTime("@{$date}", new DateTimeZone($timezone)));
						}
					}
				}
			}
		}

		if ($flag === false) {
			return false;
		}

		// Flush all stored urls from memory to the disk and close all necessary tags.
		$sitemap->flush();

		// Move flushed files to their final location. Compress if the option is enabled.
		$sitemap->finalize();

		// Update robots.txt file in output directory

		if ($this->getData(['config', 'seo', 'robots']) === true) {
			if (file_exists('robots.txt')) {
				unlink('robots.txt');
			}
			$sitemap->updateRobots();
		} else {
			$this->secure_file_put_contents('robots.txt', 'User-agent: *' . PHP_EOL . 'Disallow: /');
		}

		// Submit your sitemaps to Google, Yahoo, Bing and Ask.com
		if (empty($this->getData(['config', 'proxyType']) . $this->getData(['config', 'proxyUrl']) . ':' . $this->getData(['config', 'proxyPort']))) {
			$sitemap->submitSitemap();
		}

		return (file_exists('sitemap.xml') && file_exists('robots.txt'));
	}

    	/**
	 * Ajoute récursivement les enfants pour TinyMCE
	 * @param array &$menu Référence au menu à construire
	 * @param array $childrenPageIds IDs des enfants
	 * @param string $rewrite URL rewrite
	 * @param int $depth Profondeur actuelle
	 */
	private function addTinyMceChildren(&$menu, $childrenPageIds, $rewrite, $depth = 1)
	{
		$maxDepth = 3; // Profondeur maximale pour TinyMCE

		if ($depth >= $maxDepth) {
			return;
		}

		foreach ($childrenPageIds as $childId) {
			// Vérifier que la page enfant existe
			if ($this->getData(['page', $childId])) {
				// Vérifier si cette page n'est pas déjà dans le menu pour éviter les doublons
				$alreadyExists = false;
				foreach ($menu as $existingItem) {
					if ($existingItem['value'] === $rewrite . $childId) {
						$alreadyExists = true;
						break;
					}
				}
				if ($alreadyExists) {
					continue;
				}

				$indent = str_repeat('── ', $depth);
				$childEntry = [
					'title' => $indent . html_entity_decode($this->getData(['page', $childId, 'title']), ENT_QUOTES),
					'value' => $rewrite . $childId,
					'menu' => []
				];

				// Récupérer les enfants de cette page en cherchant les pages avec ce parent
				$grandChildren = [];
				foreach ($this->getData(['page']) as $pageId => $pageData) {
					if ($pageData['parentPageId'] === $childId) {
						$grandChildren[] = $pageId;
					}
				}

				// Ajouter les enfants récursivement
				if (!empty($grandChildren)) {
					$this->addTinyMceChildren($childEntry['menu'], $grandChildren, $rewrite, $depth + 1);
				}

				$menu[] = $childEntry;
			}
		}
	}

}