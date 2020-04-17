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
 *
 * Module search
 * Produit par la communauté à partit d'un développement de Sylvain Lelièvre
 */

// Module de recherche d'un mot ou d'une phrase clef

class search extends common {

	public static $actions = [
		'index' => self::GROUP_VISITOR
	];


	public function index() {
		if($this->isPost())  {
			//Initialisations variables
			$success = true;
			$result = '';
			$notification = '';
			$total='';
			$this->setData(['search',$total,0]);

			// Récupération du mot clef passé par le formulaire de ...view/index.php, avec caractères accentués
			$motclef=$this->getInput('searchMotphraseclef');

			// Récupération de l'état de l'option mot entier passé par le même formulaire
			$motentier=$this->getInput('searchMotentier', helper::FILTER_BOOLEAN);

			//Pour affichage de l'entête du résultat
			$result = '<h1>Recherche avec le mot clef : '.$motclef.'<br/></h1>';
			if ($motclef !== "" && strlen($motclef) > 2) {
				foreach($this->getHierarchy(null,false,null) as $parentId => $childIds) {
					if ($this->getData(['page', $parentId, 'disable']) === false  &&
                        $this->getUser('group') >= $this->getData(['page', $parentId, 'group']) &&
                        $this->getData(['page', $parentId, 'block']) !== 'bar') 	{
						$url = $parentId;
						$titre = $this->getData(['page', $parentId, 'title']);
						$contenu =  $this->getData(['page', $parentId, 'content']);
						// Pages sauf pages filles et articles de blog
						$result .= $this->occurrence($url, $titre, $contenu, $motclef, $motentier);
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
                                    $result .= $this->occurrence($url, $titre, $contenu, $motclef, $motentier);

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
										$result .= $this->occurrence($url, $titre, $contenu, $motclef, $motentier);

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
								$result .= $this->occurrence($url, $titre, $contenu, $motclef, $motentier);

							}
                        }
					}
                }
				// Message de synthèse de la recherche
				if ($this->getData(['search',$total])===0) 	{
					$notification = 'Mot clef non trouv&eacute;. Avez-vous pens&eacute; aux accents ?';
					$result .='Mot clef non trouv&eacute;. Avez-vous pens&eacute; aux accents ?';
					$success = false;
				} else  {
					$result .= 'Nombre d\'occurrences : '.$this->getData(['search',$total]);
					$notification = 'Nombre d\'occurrences : '.$this->getData(['search',$total]);
					$success = true;
				}
			} else {
				$notification = 'Trop court ! Minimum 3 caract&egrave;res';
				$result = 'Trop court ! Minimum 3 caract&egrave;res';
				$success = false;
			}

			$_POST['result'] = $result;
			$_POST['occurence'] = $total;
			// Valeurs en sortie, affichage du résultat
			$this->addOutput([
				'title' => '',
				'view' => 'result',
				'notification' => $notification,
				'state' => $success
			]);
		} else {
			// Valeurs en sortie, affichage du formulaire
			$this->addOutput([
				'title' => '',
				'view' => 'index'
			]);
		}
	}


	// Fonction de recherche des occurences dans $contenu
	// Renvoie le résulat sous forme de chaîne
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
			$occu = stristr($contenu,$motclef);
			if ($occu !== false) {
				if ($motentier === true) {
					$controle_entier=$this->test_motentier($contenu,$motclef);
				} else {
					$controle_entier=true;
				}
				if ($controle_entier) {
					if ($titre !== $dejavu) {
						$resultat = '<p><br/>Mot clef trouv&eacute; dans la page : <a href="./?'.$url.'" target="_blank" rel="noopener">'.$titre.'</a><br/></p>';
					}
					$dejavu = $titre;
					$nboccu++;
					$resultat .= '<p>'.$nboccu.' - "...<em>'.substr($occu,0,200).'</em>..."<br/></p>';
				}
				// Pour recherche d'une autre occurrence dans le même contenu
				$contenu = substr($occu,10);
			}
		}
		while($occu != '');
		$this->setData(['search',$total,$this->getData(['search',$total]) + $nboccu]);


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

	// Déclaration de la fonction test_motentier(string $chaine, string $clef) : bool
	// Vérifie si dans la string $chaine, $clef est un mot entier
	// $clef ne doit pas être précédé ni suivi d'une lettre maj ou min
	private function test_motentier($chaine, $clef)
	{
		$resultat=true;
		$pos1=stripos($chaine,$clef);
		$avant=ord(substr($chaine,$pos1-1, 1));
		$apres=ord(substr($chaine,$pos1+strlen($clef),1));
		// Traitement pour le caractère qui précède et celui qui suit
		if (($avant>=65 && $avant<=90) ||
            ($avant>=97 && $avant<=122) ||
            ($apres>=65 && $apres<=90) ||
            ($apres>=97 && $apres<=122) ) {
        		$resultat=false;
		}
		  return $resultat;
	}
}
