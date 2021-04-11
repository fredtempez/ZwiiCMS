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
 * @copyright Copyright (C) 2018-2021, Frédéric Tempez
 * @author Sylvain Lelièvre <lelievresylvain@free.fr>
 * @copyright Copyright (C) 2020-2021, Sylvain Lelièvre
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 *
 */

class search extends common {

	const VERSION = '2.0';
	const REALNAME = 'Recherche';
	const DELETE = true;
	const UPDATE = '0.0';
	const DATADIRECTORY = self::DATA_DIR . 'modules/search/';

	public static $actions = [
		'index' => self::GROUP_VISITOR,
		'config' => self::GROUP_MODERATOR
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
	private function update() {

		// Déplacement des données d'une version ultérieure
		if ($this->getData(['module', $this->getUrl(0), 'previewLength']) ) {
			$data = $this->getData(['module', $this->getUrl(0)]);
			// Feuille de style
			$fileCSS = self::DATADIRECTORY  . $this->getUrl(0) . '.css' ;
			$this->setData(['module', $this->getUrl(0), 'config', [
				'submitText' => $this->getData(['module', $this->getUrl(0), 'submitText']),
				'placeHolder' => $this->getData(['module', $this->getUrl(0), 'placeHolder']),
				'resultHideContent' => $this->getData(['module', $this->getUrl(0), 'resultHideContent']),
				'previewLength' => $this->getData(['module', $this->getUrl(0), 'previewLength']),
				'versionData' => '2.0'
			]]);
			$this->setData(['module', $this->getUrl(0), 'theme', [
				'keywordColor' => $this->getData(['module', $this->getUrl(0), 'keywordColor']),
				'style' => $fileCSS
			]]);

			// Dossier de l'instance
			if (!is_dir(self::DATADIRECTORY)) {
				mkdir (self::DATADIRECTORY, 0777, true);
			}
			// Générer la feuille de CSS
			$style = '.keywordColor {background: ' . $this->getData(['module', $this->getUrl(0), 'theme', 'keywordColor']) . ';}';
			// Sauver la feuille de style
			$success = file_put_contents( $fileCSS, $style);
			// Nettoyage des données précédentes
			$this->deleteData(['module', $this->getUrl(0), 'submitText']);
			$this->deleteData(['module', $this->getUrl(0), 'placeHolder']);
			$this->deleteData(['module', $this->getUrl(0), 'resultHideContent']);
			$this->deleteData(['module', $this->getUrl(0), 'previewLength']);
			$this->deleteData(['module', $this->getUrl(0), 'keywordColor']);

			$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '2.0']);
		}
	}

	/**
	 * Initialisation du module
	 */
	private function init($moduleId){
		// Variable commune
		$fileCSS = self::DATADIRECTORY  . $moduleId . '.css' ;

		// Données du module 
		require_once('module/search/ressource/defaultdata.php');
		$this->setData(['module', $moduleId, 'config',init::$defaultData ]);
		// Données de thème
		$this->setData(['module', $moduleId, 'theme',init::$defaultTheme ]);

		// Générer la feuille de CSS
		$style = '.keywordColor {background: ' . $this->getData([ 'module', $moduleId, 'theme', 'keywordColor'  ]) . ';}';

		// Dossier de l'instance
		if (!is_dir(self::DATADIRECTORY)) {
			mkdir (self::DATADIRECTORY, 0777, true);
		}

		// Sauver la feuille de style
		file_put_contents(self::DATADIRECTORY .$moduleId . '.css' , $style );

		// Stocker le nom de la feuille de style
		$this->setData(['module', $moduleId, 'theme', 'style', self::DATADIRECTORY . $moduleId . '.css']);
	}


	// Configuration vide
	public function config() {

		// Mise à jour des données de module
		$this->update();

		// Initialisation d'un nouveau module
		if ($this->getData(['module', $this->getUrl(0)]) === null) {
			$this->init($this->getUrl(0));
		}

		if($this->isPost())  {

			// Générer la feuille de CSS
			$style = '.keywordColor {background:' . $this->getInput('searchKeywordColor') . ';}';
			// Dossier de l'instance
			if (!is_dir(self::DATADIRECTORY)) {
				mkdir (self::DATADIRECTORY , 0777, true);
			}

			$success = file_put_contents(self::DATADIRECTORY . $this->getUrl(0) . '.css' , $style );
			// Fin feuille de style

			// Soumission du formulaire
			$this->setData(['module', $this->getUrl(0), 'config',[
				'submitText' => $this->getInput('searchSubmitText'),
				'placeHolder' => $this->getInput('searchPlaceHolder'),
				'resultHideContent' => $this->getInput('searchResultHideContent',helper::FILTER_BOOLEAN),
				'previewLength' => $this->getInput('searchPreviewLength',helper::FILTER_INT),
				'versionData' => $this->getData(['module', $this->getUrl(0), 'config', 'versionData'])
			]]);
			$this->setData(['module', $this->getUrl(0), 'theme',[
				'keywordColor' => $this->getInput('searchKeywordColor'),
				'style' => $success ? self::DATADIRECTORY . $this->getUrl(0) . '.css' : '',
			]]);


			// Valeurs en sortie, affichage du formulaire
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => $success !== FALSE ? 'Modifications enregistrées' : 'Modifications non enregistrées !',
				'state' => $success !== FALSE
			]);

		}
		// Valeurs en sortie, affichage du formulaire
		$this->addOutput([
			'title' => 'Configuration du module',
			'view' => 'config',
			'vendor' => [
				'tinycolorpicker'
			]
		]);
	}

	public function index() {

		// Mise à jour des données de module
		$this->update();

		// Initialisation d'un nouveau module
		if ($this->getData(['module', $this->getUrl(0)]) === null) {
			$this->init($this->getUrl(0));
		}

		if($this->isPost())  {
			//Initialisations variables
			$success = true;
			$result = [];
			$notification = '';
			$total='';

			// Récupération du mot clef passé par le formulaire de ...view/index.php, avec caractères accentués
			self::$motclef=$this->getInput('searchMotphraseclef');
			// Variable de travail, on conserve la variable globale pour l'affichage du résultat
			$motclef = self::$motclef;

			// Traduction du mot clé si le script Google Trad est actif
			// Le multi langue est sélectionné
			if (  $this->getData(['config','translate','scriptGoogle']) === true
			AND
				// et la traduction de la langue courante est automatique
				(   isset($_COOKIE['googtrans'])
					AND ( $this->getData(['config','translate', substr($_COOKIE['googtrans'],4,2)]) === 'script'
					// Ou traduction automatique
						OR 	$this->getData(['config','translate','autoDetect']) === true )
				)
			// Cas des pages d'administration
			// Pas connecté
			AND ( $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
				// Ou connecté avec option active
				OR ($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
					AND $this->getData(['config','translate','admin']) === true
					)
				)
			AND !isset($_COOKIE['ZWII_I18N_SITE'])
			)
			{
				// Découper la chaîne
				$f = str_getcsv($motclef, ' ');
				// Supprimer les espaces et les guillemets
				$f = str_replace(' ','',$f);
				$f = str_replace('"','',$f);
				// Lire le cookie GoogTrans et déterminer les langues cibles
				$language['origin'] = substr($_COOKIE['googtrans'],4,2);
				$language['target'] = substr($_COOKIE['googtrans'],1,2);
				if ($language['target'] !== $language['origin']) {
					foreach ($f as $key => $value) {
						$e = $this->translate($language['origin'],$language['target'],$value);
						$motclef = str_replace($value,$e,$motclef);
					}
				}
			}

			// Suppression des mots < 3  caractères et des articles > 2 caractères de la chaîne $motclef
			$arraymotclef = explode(' ', $motclef);
			$motclef = '';
			foreach($arraymotclef as $key=>$value){
				if( strlen($value)>2 && $value!=='les' && $value!=='des' && $value!=='une' && $value!=='aux') $motclef.=$value.' ';
			}
			// Suppression du dernier ' '
			if($motclef !== '') $motclef = substr($motclef,0, strlen($motclef)-1);

			// Récupération de l'état de l'option mot entier passé par le même formulaire
			self::$motentier=$this->getInput('searchMotentier', helper::FILTER_BOOLEAN);

			if ($motclef !== '' ) {
				foreach($this->getHierarchy(null,false,null) as $parentId => $childIds) {
					if ($this->getData(['page', $parentId, 'disable']) === false  &&
                        $this->getUser('group') >= $this->getData(['page', $parentId, 'group']) &&
                        $this->getData(['page', $parentId, 'block']) !== 'bar') 	{
						$url = $parentId;
						$titre = $this->getData(['page', $parentId, 'title']);
						$contenu =  ' ' . $titre . ' ' . $this->getData(['page', $parentId, 'content']);
						// Pages sauf pages filles et articles de blog
						$tempData  = $this->occurrence($url, $titre, $contenu, $motclef, self::$motentier);
						if (is_array($tempData) ) {
							$result [] = $tempData;
						}
					}

					foreach($childIds as $childId) {
							// Sous page
							if ($this->getData(['page', $childId, 'disable']) === false &&
                                $this->getUser('group') >= $this->getData(['page', $parentId, 'group']) &&
                                $this->getData(['page', $parentId, 'block']) !== 'bar') 	{
                                    $url = $childId;
                                    $titre = $this->getData(['page', $childId, 'title']);
                                    $contenu = ' ' . $titre . ' ' . $this->getData(['page', $childId, 'content']);
                                    //Pages filles
									$tempData  = $this->occurrence($url, $titre, $contenu, $motclef, self::$motentier);
									if (is_array($tempData) ) {
										$result [] = $tempData;
									}
							}

							// Articles d'une sous-page blog
							if ($this->getData(['page', $childId, 'moduleId']) === 'blog' &&
								$this->getData(['module',$parentId,'posts']) )
							{
								foreach($this->getData(['module',$childId,'posts']) as $articleId => $article) {
									if($this->getData(['module',$childId,'posts',$articleId,'state']) === true)  {
										$url = $childId . '/' . $articleId;
										$titre = $article['title'];
										$contenu = ' ' . $titre . ' ' . $article['content'];
										// Articles de sous-page de type blog
										$tempData  = $this->occurrence($url, $titre, $contenu, $motclef, self::$motentier);
										if (is_array($tempData) ) {
											$result [] = $tempData;
										}
									}
                                }
							}
                    }

					// Articles d'un blog
					if ($this->getData(['page', $parentId, 'moduleId']) === 'blog' &&
						$this->getData(['module',$parentId,'posts']) ) {
						foreach($this->getData(['module',$parentId,'posts']) as $articleId => $article) {
							if($this->getData(['module',$parentId,'posts',$articleId,'state']) === true)
							{
								$url = $parentId. '/' . $articleId;
								$titre = $article['title'];
								$contenu = ' ' . $titre . ' ' . $article['content'];
								// Articles de Blog
								$tempData  = $this->occurrence($url, $titre, $contenu, $motclef, self::$motentier);
								if (is_array($tempData) ) {
									$result [] = $tempData;
								}
							}
                        }
					}
				}

				// Message de synthèse de la recherche
				if (count($result) === 0) 	{
					self::$resultTitle = 'Aucun résultat';
					self::$resultError = 'Avez-vous pens&eacute; aux accents ?';
				} else  {
					self::$resultError = '';
					self::$resultTitle = ' Résultat de votre recherche';
					rsort($result);
					foreach ($result as $key => $value) {
						$r [] = $value['preview'];
					}
					// Générer une chaine de caractères
					self::$resultList= implode("", $r);
				}
			}

			// Valeurs en sortie, affichage du résultat
			$this->addOutput([
				'view' => 'index',
				'showBarEditButton' => true,
				'showPageContent' => !$this->getData(['module', $this->getUrl(0), 'config', 'resultHideContent']),
				'style' => $this->getData(['module', $this->getUrl(0), 'theme', 'style'])
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
		$contenu = preg_replace ('/<[^>]*>/', ' ', $contenu);
		// Accentuation
		$contenu = html_entity_decode($contenu);

		// Découper le chaîne en tenant compte des quillemets
		$a = str_getcsv(html_entity_decode($motclef), ' ');

		// Construire la clé de recherche selon options de recherche
		$keywords = '/(';

		foreach ($a as $key => $value) {

			$keywords .= $motentier === true ? $value . '|' : '\b' . $value . '\b|' ;
		}
		$keywords = substr($keywords,0,strlen($keywords) - 1);
		$keywords .= ')/i';
		$keywords = str_replace ('+', ' ',$keywords);

		// Rechercher
		$valid = preg_match_all($keywords,$contenu,$matches,PREG_OFFSET_CAPTURE);
		if ($valid > 0 ) {
			if (($matches[0][0][1]) > 0) {
				$resultat = '<h2><a  href="./?'.$url.'" target="_blank" rel="noopener">' . $titre .  '</a></h2>';
				// Création de l'aperçu
				// Eviter de découper avec une valeur négative
				$d = $matches[0][0][1] - 50 < 0 ? 1 : $matches[0][0][1] - 50;
				// Rechercher l'espace le plus proche
				$d = $d >= 1 ? strpos($contenu,' ',$d) : $d;
				// Découper l'aperçu
				$t = substr($contenu, $d ,$this->getData(['module',$this->getUrl(0), 'config', 'previewLength']));
				// Applique une mise en évidence
				$t = preg_replace($keywords, '<span class= "keywordColor">\1</span>',$t);
				// Sauver résultat
				$resultat .= '<p class="searchResult">'.$t.'...</p>';
				$resultat .= '<p class="searchTitle">' . count($matches[0]) . (count($matches[0]) === 1 ? ' correspondance<p>' : ' correspondances<p>');
				//}
				return ([
					'matches' => count($matches[0]),
					'preview' => $resultat
				]);
			}
		}
	}

	// Requête de traduction avec le script Google
	private function translate($from_lan, $to_lan, $text) {
		$arrayjson = json_decode(file_get_contents('https://translate.googleapis.com/translate_a/single?client=gtx&sl='.$from_lan.'&tl=fr&dt=t&q='.$text),true);
		return $arrayjson[0][0][0];
	}
}
