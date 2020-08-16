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

	public static $defaultPlaceHolder = 'Un mot clé ou une phrase entière sans guillemets';

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
					$notification = 'Mot clef non trouv&eacute;. Avez-vous pens&eacute; aux accents ?';
					$result .='Mot clef non trouv&eacute;. Avez-vous pens&eacute; aux accents ?';
					$success = false;
				} else  {
					//$result .= self::$nbResults .' occurrences ont été trouvées.';
					$notification = 'Nombre d\'occurrences : '.self::$nbResults;
					self::$resultTitle = 'Résultat(s) : "' . self::$motclef . '" a été trouvé  '. self::$nbResults . ' fois';
					$success = true;
				}
			} else {
				$notification = 'Trop court ! Minimum 3 caract&egrave;res';
				$result = 'Trop court ! Minimum 3 caract&egrave;res';
				$success = false;
			}

			self::$resultList = $result;
			// Valeurs en sortie, affichage du résultat
			$this->addOutput([
				'view' => 'index',
				'notification' => $notification,
				'state' => $success,
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
		$contenu = $this->nettoyer_html($contenu);
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
				$contenu = preg_replace($motclef, '<span class="evidence">\1</span>', $contenu);
				foreach ($matches[0] as $key => $value) {
					//$resultat .= '<p>'.$nboccu.' - "...<em>'.substr($contenu,$value[1] ,200).'</em>..."<br/></p>';	# code...
					$resultat .= '"<em>'.substr($contenu,$value[1] ,200).'</em>..."<br/></p>';	# code...
				}
			}
			// Pour recherche d'une autre occurrence dans le même contenu
			$contenu = substr($occu,10);
		}
		while($occu != '');
		self::$nbResults = self::$nbResults + $nboccu;

		return $resultat;
	}

	// Déclaration de la fonction nettoyer(string $contenu) : string
	// Supprime de $contenu les caractères placés entre < et >, donc les balises html comme <p> <br/> etc...
	// Retourne $contenu nettoyée, le résultat est sensiblement différent de celui obtenu avec la fonction strip_tags()
	private function nettoyer_html($contenu)
	{
		do {
			$pos1=strpos($contenu,chr(60));
			if($pos1!==false) {
				$pos2=strpos($contenu,chr(62));
				if($pos2!==false) $contenu=substr_replace($contenu," ",$pos1,($pos2 - $pos1 + 1));
			}
		}
		while($pos1!==false);
		return $contenu;
	}
}
