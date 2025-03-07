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
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @author Sylvain Lelièvre <lelievresylvain@free.fr>
 * @copyright Copyright (C) 2020-2021, Sylvain Lelièvre
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 *
 */

class search extends common
{

	const VERSION = '3.3';
	const REALNAME = 'Recherche';
	const DATADIRECTORY = self::DATA_DIR . 'search/';

	public static $actions = [
		'index' => self::ROLE_VISITOR,
		'config' => self::ROLE_EDITOR
	];

	// Variables pour l'affichage des résultats
	public static $resultList = '';
	public static $resultError = '';
	public static $resultTitle = '';

	// Variables pour le dialogue avec le formulaire
	public static $motclef = '';
	public static $motentier = true;
	public static $previewLength = [
		100 => '100 caractères',
		200 => '200 caractères',
		300 => '300 caractères',
		400 => '400 caractères',
	];


	/**
	 * Mise à jour du module
	 * Appelée par les fonctions index et config
	 */
	private function update()
	{


		// le module n'est pas initialisé
		if (
			$this->getData(['module', $this->getUrl(0), 'config']) === NULL
			|| $this->getData(['module', $this->getUrl(0), 'theme']) === NULL
			|| !file_exists(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css')
		) {
			$this->init();
		}

		$versionData = $this->getData(['module', $this->getUrl(0), 'config', 'versionData']);

		// Mise à jour 2.2
		if (version_compare($versionData, '2.2', '<')) {
			if (is_dir(self::DATADIRECTORY . 'pages/')) {
				// Déplacer les données du dossier Pages
				$this->copyDir(self::DATADIRECTORY . 'pages/' . $this->getUrl(0), self::DATADIRECTORY . $this->getUrl(0));
				$this->deleteDir(self::DATADIRECTORY . 'pages/');
			}
			// Mettre à jour la version
			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '2.2']);
		}
	}

	/**
	 * Initialisation du module
	 */
	private function init()
	{


		$fileCSS = self::DATADIRECTORY . $this->getUrl(0) . '/theme.css';

		if ($this->getData(['module', $this->getUrl(0)]) === null) {
			// Données du module
			require_once('module/search/ressource/defaultdata.php');
			$this->setData(['module', $this->getUrl(0), 'config', init::$defaultConfig]);
			// Données de thème
			$this->setData(['module', $this->getUrl(0), 'theme', init::$defaultTheme]);
			$this->setData(['module', $this->getUrl(0), 'theme', 'style', self::DATADIRECTORY . $this->getUrl(0) . '/theme.css']);
			// Recharger la page pour éviter une config vide
			header("Refresh:0");
		}

		// Dossier de l'instance
		if (!is_dir(self::DATADIRECTORY . $this->getUrl(0))) {
			mkdir(self::DATADIRECTORY . $this->getUrl(0), 0755, true);
		}

		// Check la présence de la feuille de style
		if (!file_exists(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css')) {
			// Générer la feuille de CSS
			$style = '.keywordColor {background: ' . $this->getData(['module', $this->getUrl(0), 'theme', 'keywordColor']) . ';}';
			// Sauver la feuille de style
			file_put_contents(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css', $style);
			// Stocker le nom de la feuille de style
			$this->setData(['module', $this->getUrl(0), 'theme', 'style', $fileCSS]);
		}
	}


	// Configuration vide
	public function config()
	{

		// Mise à jour des données de module
		$this->update();

		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {

			// Générer la feuille de CSS
			$style = '.keywordColor {background:' . $this->getInput('searchKeywordColor') . ';}';

			$success = is_int(file_put_contents(self::DATADIRECTORY . $this->getUrl(0) . '/theme.css', $style));
			// Fin feuille de style

			// Soumission du formulaire
			$this->setData([
				'module', $this->getUrl(0),
				'config',
				[
					'submitText' => $this->getInput('searchSubmitText'),
					'placeHolder' => $this->getInput('searchPlaceHolder'),
					'resultHideContent' => $this->getInput('searchResultHideContent', helper::FILTER_BOOLEAN),
					'previewLength' => $this->getInput('searchPreviewLength', helper::FILTER_INT),
					'versionData' => $this->getData(['module', $this->getUrl(0), 'config', 'versionData'])
				]
			]);
			$this->setData([
				'module', $this->getUrl(0),
				'theme',
				[
					'keywordColor' => $this->getInput('searchKeywordColor'),
					'style' => $success ? self::DATADIRECTORY . $this->getUrl(0) . '/theme.css' : '',
				]
			]);


			// Valeurs en sortie, affichage du formulaire
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => $success ? 'Modifications enregistrées' : 'Modifications non enregistrées !',
				'state' => $success
			]);
		}
		// Valeurs en sortie, affichage du formulaire
		$this->addOutput([
			'title' => helper::translate('Configuration du module'),
			'view' => 'config',
			'vendor' => [
				'tinycolorpicker'
			]
		]);
	}

	public function index()
	{

		// Initialise un module non configuré
		$this->init();

		if (
			$this->isPost()
		) {
			//Initialisations variables
			$success = true;
			$result = [];
			$notification = '';
			$total = '';

			// Récupération du mot clef passé par le formulaire de ...view/index.php, avec caractères accentués
			self::$motclef = $this->getInput('searchMotphraseclef');
			// Variable de travail, on conserve la variable globale pour l'affichage du résultat
			$motclef = self::$motclef;

			// Suppression des mots < 3  caractères et des articles > 2 caractères de la chaîne $motclef
			$arraymotclef = explode(' ', $motclef);
			$motclef = '';
			foreach ($arraymotclef as $key => $value) {
				if (strlen($value) > 2 && $value !== 'les' && $value !== 'des' && $value !== 'une' && $value !== 'aux')
					$motclef .= $value . ' ';
			}
			// Suppression du dernier ' '
			if ($motclef !== '')
				$motclef = substr($motclef, 0, strlen($motclef) - 1);

			// Récupération de l'état de l'option mot entier passé par le même formulaire
			self::$motentier = $this->getInput('searchMotentier', helper::FILTER_BOOLEAN);

			if ($motclef !== '') {
				foreach ($this->getHierarchy(null, false, null) as $parentId => $childIds) {
					if (
						$this->getData(['page', $parentId, 'disable']) === false &&
						$this->getUser('role') >= $this->getData(['page', $parentId, 'role']) &&
						$this->getData(['page', $parentId, 'block']) !== 'bar'
					) {
						$url = $parentId;
						$titre = $this->getData(['page', $parentId, 'title']);
						//$content = file_get_contents(self::DATA_DIR . self::$siteContent . '/content/' . $this->getData(['page', $parentId, 'content']));
						$content = $this->getPage($parentId, self::$siteContent);
						$content = $titre . ' ' . $content;
						// Pages sauf pages filles et articles de blog
						$tempData = $this->occurrence($url, $titre, $content, $motclef, self::$motentier);
						if (is_array($tempData)) {
							$result[] = $tempData;
						}
					}

					foreach ($childIds as $childId) {
						// Sous page
						if (
							$this->getData(['page', $childId, 'disable']) === false &&
							$this->getUser('role') >= $this->getData(['page', $parentId, 'role']) &&
							$this->getData(['page', $parentId, 'block']) !== 'bar'
						) {
							$url = $childId;
							$titre = $this->getData(['page', $childId, 'title']);
							//$content = file_get_contents(self::DATA_DIR . self::$siteContent . '/content/' . $this->getData(['page', $childId, 'content']));
							$content = $this->getPage($childId, self::$siteContent);
							$content = $titre . ' ' . $content;
							//Pages filles
							$tempData = $this->occurrence($url, $titre, $content, $motclef, self::$motentier);
							if (is_array($tempData)) {
								$result[] = $tempData;
							}
						}

						// Articles d'une sous-page blog ou de news
						if ($this->getData(['module', $childId, 'posts'])) {
							foreach ($this->getData(['module', $childId, 'posts']) as $articleId => $article) {
								if ($this->getData(['module', $childId, 'posts', $articleId, 'state']) === true) {
									$url = $childId . '/' . $articleId;
									$titre = $article['title'];
									$contenu = ' ' . $titre . ' ' . $article['content'];
									// Articles de sous-page de type blog
									$tempData = $this->occurrence($url, $titre, $contenu, $motclef, self::$motentier);
									if (is_array($tempData)) {
										$result[] = $tempData;
									}
								}
							}
						}
					}

					// Articles d'un blog ou de news
					if ($this->getData(['module', $parentId, 'posts'])) {

						foreach ($this->getData(['module', $parentId, 'posts']) as $articleId => $article) {
							if ($this->getData(['module', $parentId, 'posts', $articleId, 'state']) === true) {
								$url = $parentId . '/' . $articleId;
								$titre = $article['title'];
								$contenu = ' ' . $titre . ' ' . $article['content'];
								$tempData = $this->occurrence($url, $titre, $contenu, $motclef, self::$motentier);
								if (is_array($tempData)) {
									$result[] = $tempData;
								}
							}
						}
					}
				}
				// Message de synthèse de la recherche
				if (count($result) === 0) {
					self::$resultTitle = helper::translate('Aucun résultat');
					self::$resultError = helper::translate('Avez-vous pensé aux accents ?');
				} else {
					self::$resultError = '';
					//self::$resultTitle = sprintf(' %s',helper::translate('Résultat de votre recherche'));
					rsort($result);
					foreach ($result as $key => $value) {
						$r[] = $value['preview'];
					}
					// Générer une chaine de caractères
					self::$resultList = implode("", $r);
				}
			}

			// Valeurs en sortie, affichage du résultat
			$this->addOutput([
				'view' => 'index',
				'showBarEditButton' => true,
				'showPageContent' => !$this->getData(['module', $this->getUrl(0), 'config', 'resultHideContent']),
				'style' => file_exists($this->getData(['module', $this->getUrl(0), 'theme', 'style']))
				? $this->getData(['module', $this->getUrl(0), 'theme', 'style'])
				: ''
			]);
		} else {
			// Valeurs en sortie, affichage du formulaire
			$this->addOutput([
				'view' => 'index',
				'showBarEditButton' => true,
				'showPageContent' => true
			]);
		}
	}


	// Fonction de recherche des occurrences dans $contenu
	// Renvoie le résultat sous forme de chaîne
	private function occurrence($url, $titre, $contenu, $motclef, $motentier)
	{
		// Nettoyage de $contenu : on enlève tout ce qui est inclus entre < et >
		$contenu = preg_replace('/<[^>]*>/', ' ', $contenu);
		// Accentuation
		$contenu = html_entity_decode($contenu);

		// Découper le chaîne en tenant compte des quillemets
		$a = str_getcsv(html_entity_decode($motclef), ' ');

		// Construire la clé de recherche selon options de recherche
		$keywords = '/(';

		foreach ($a as $key => $value) {

			$keywords .= $motentier === true ? $value . '|' : '\b' . $value . '\b|';
		}
		$keywords = substr($keywords, 0, strlen($keywords) - 1);
		$keywords .= ')/i';
		$keywords = str_replace('+', ' ', $keywords);

		// Rechercher
		$valid = preg_match_all($keywords, $contenu, $matches, PREG_OFFSET_CAPTURE);
		if ($valid > 0) {
			if (($matches[0][0][1]) > 0) {
				$resultat = sprintf('<h2><a  href="./?%s" target="_blank" rel="noopener">%s (%s)</a></h2>', $url, $titre, count($matches[0]));
				// Création de l'aperçu
				// Eviter de découper avec une valeur négative
				$d = $matches[0][0][1] - 50 < 0 ? 1 : $matches[0][0][1] - 50;
				// Rechercher l'espace le plus proche
				$d = $d >= 1 ? strpos($contenu, ' ', $d) : $d;
				// Découper l'aperçu
				$t = substr($contenu, $d, $this->getData(['module', $this->getUrl(0), 'config', 'previewLength']));
				// Applique une mise en évidence
				$t = preg_replace($keywords, '<span class= "keywordColor">\1</span>', $t);
				// Sauver résultat
				$resultat .= '<p class="searchResult">' . $t . '...</p>';

				//}
				return ([
					'matches' => count($matches[0]),
					'preview' => $resultat
				]);
			}
		}
	}
}