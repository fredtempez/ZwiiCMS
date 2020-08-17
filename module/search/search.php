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
 * @copyright Sylvain Lelièvre
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 *
 */

class search extends common {

	public static $actions = [
		'index' => self::GROUP_VISITOR,
		'config' => self::GROUP_MODERATOR
	];

	public static $resultList = '';

	public static $nbResults = 0;

	public static $resultTitle = '';

	public static $motclef = '';

	public static $motentier = '';

	public static $defaultButtonText = 'Rechercher';

	public static $defaultPlaceHolder = 'Que recherchez-vous ?';

	const SEARCH_VERSION = '1.1';

	// Configuration vide
	public function config() {
		if($this->isPost())  {
			// Soumission du formulaire
			$this->setData(['module', $this->getUrl(0), [
				'submitText' => $this->getInput('searchSubmitText'),
				'placeHolder' => $this->getInput('searchPlaceHolder'),
				'resultHideContent' => $this->getInput('searchResultHideContent',helper::FILTER_BOOLEAN)
			]]);
			// Valeurs en sortie, affichage du formulaire
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		}
		// Valeurs en sortie, affichage du formulaire
		$this->addOutput([
			'title' => 'Configuration du module',
			'view' => 'config'
		]);
	}

	public function index() {
		if($this->isPost())  {
			//Initialisations variables
			$success = true;
			$result = '';
			$notification = '';
			$total='';
			self::$nbResults = 0;

			// Récupération du mot clef passé par le formulaire de ...view/index.php, avec caractères accentués
			self::$motclef=$this->getInput('searchMotphraseclef');

			// Récupération de l'état de l'option mot entier passé par le même formulaire
			self::$motentier=$this->getInput('searchMotentier', helper::FILTER_BOOLEAN);

			//Pour affichage de l'entête du résultat
			self::$resultTitle = 'Aucun résultat';
			$result = '';
			// protection des guillemets
			// ^((("){1}([^"])*("){1})([ ]+))+$
			//preg_match('(?:^|(?<=\s))"([^"]+)"(?:$|(?=\s))',self::$motclef,$matches);
			//print_r($matches);
			// Découpage de la chaîne de mots clés
			$keywords = '/(';
			$a = explode(' ',self::$motclef);
			foreach ($a as $key => $value) {

				$keywords .= self::$motentier === false ? $value . '|' : '\\b' . $value . '\\b|' ;
			}
			$keywords = substr($keywords,0,strlen($keywords) - 1);
			$keywords .= ')/i';
			//echo $keywords;
			if ($keywords !== "" && strlen($keywords) > 2) {
				foreach($this->getHierarchy(null,false,null) as $parentId => $childIds) {
					if ($this->getData(['page', $parentId, 'disable']) === false  &&
                        $this->getUser('group') >= $this->getData(['page', $parentId, 'group']) &&
                        $this->getData(['page', $parentId, 'block']) !== 'bar') 	{
						$url = $parentId;
						$titre = $this->getData(['page', $parentId, 'title']);
						$contenu =  $this->getData(['page', $parentId, 'content']);
						// Pages sauf pages filles et articles de blog
						$result .= $this->occurrence($url, $titre, $contenu, $keywords, self::$motentier);
					}

					foreach($childIds as $childId) {
							// Sous page
							if ($this->getData(['page', $childId, 'disable']) === false &&
                                $this->getUser('group') >= $this->getData(['page', $parentId, 'group']) &&
                                $this->getData(['page', $parentId, 'block']) !== 'bar') 	{
                                    $url = $childId;
                                    $titre = $this->getData(['page', $childId, 'title']);
                                    $contenu = $this->getData(['page', $childId, 'content']);
                                    //Pages filles
                                    $result .= $this->occurrence($url, $titre, $contenu, $keywords, self::$motentier);

							}

							// Articles d'une sous-page blog
							if ($this->getData(['page', $childId, 'moduleId']) === 'blog')
							{
								foreach($this->getData(['module',$childId]) as $articleId => $article) {
									if($this->getData(['module',$childId,$articleId,'state']) === true)  {
										$url = $childId . '/' . $articleId;
										$titre = $article['title'];
										$contenu = $article['content'];
										// Articles de sous-page de type blog
										$result .= $this->occurrence($url, $titre, $contenu, $keywords, self::$motentier);

									}
                                }
							}
                    }

					// Articles d'un blog

					if ($this->getData(['page', $parentId, 'moduleId']) === 'blog' ) {
						foreach($this->getData(['module',$parentId]) as $articleId => $article) {
							if($this->getData(['module',$parentId,$articleId,'state']) === true)
							{
								$url = $parentId. '/' . $articleId;
								$titre = $article['title'];
								$contenu = $article['content'];
								// Articles de Blog
								$result .= $this->occurrence($url, $titre, $contenu, $keywords, self::$motentier);

							}
                        }
					}
                }
				// Message de synthèse de la recherche
				if (self::$nbResults === 0) 	{

					$result .='Aucun résultat. Avez-vous pens&eacute; aux accents ?';
					$success = false;
				} else  {
					$r = self::$nbResults == 1 ? '' : '( ' .self::$nbResults . ' éléments découverts )';
					self::$resultTitle =  ' Résultat de votre recherche  ' . $r ;
					$success = true;
				}
			} else {
				$result = 'Trop court ! Minimum 3 caract&egrave;res';
				$success = false;
			}

			self::$resultList = $result;
			// Valeurs en sortie, affichage du résultat
			$this->addOutput([
				'view' => 'index',
				'showBarEditButton' => true,
				'showPageContent' => !$this->getData(['module', $this->getUrl(0),'resultHideContent'])
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
		// Initialisations
		$nboccu = 0;
		$dejavu = '';
		$total = '';
		$resultat= '';
		// Recherche des occurrences
		do {
			$occu = preg_match_all($motclef,$contenu,$matches,PREG_OFFSET_CAPTURE);
			if ($occu !== false && !empty($matches[0]) ) {
				/*echo "<pre>";
				print_r($matches);
				echo "</pre>";*/
				if ($titre !== $dejavu) {
					$resultat = '<p><a href="./?'.$url.'" target="_blank" rel="noopener">'.$titre.'</a></p>';
				}
				$dejavu = $titre;
				$nboccu .= count($matches[0]);
				foreach ($matches[0] as $key => $value) {
					// Mise en évidence
					$d = $value[1] -50 < 0 ? 1 : $value[1] - 50;
					$t = substr($contenu,(int) $d ,150);
					// Nettoyage caractères invalides
					$t = preg_replace('/[^[:alnum:][:space:]]/u', '', $t);
					// Applique une mise en évidence
					$t = preg_replace($motclef, '<span class="evidence">\1</span>',$t,1);
					// Sauver résultat
					$resultat .='"<em>'.$t.'</em>..."</p>';

				}
			}
			// Pour recherche d'une autre occurrence dans le même contenu
			$contenu = substr($occu,10);
		}
		while($occu != '');
		self::$nbResults = self::$nbResults + $nboccu; // Nombre total d'occurences

		return $resultat;
	}
}
