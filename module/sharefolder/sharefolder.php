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
 * @copyright Copyright (C) 2018-2024, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class sharefolder extends common
{

	const VERSION = '4.2';
	const REALNAME = 'Dossier partagé';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	public static $actions = [
		'config' => self::GROUP_EDITOR,
		'index' => self::GROUP_VISITOR,
	];

	static $folders = '';

    public function index() {

		self::$folders = $this->listerSousDossier( self::FILE_DIR . 'source/partage');

        // Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index'
		]);
    }

	private function listerSousDossier($chemin) {
		// Vérifier si le chemin existe et est un dossier
		if (is_dir($chemin)) {	
			// Ouvrir le dossier
			if ($dh = opendir($chemin)) {
				$items = isset($items) ? $items . '<ul>' : '<ul>';
				//$items = '<ul>';
				// Parcourir les éléments du dossier
				while (($element = readdir($dh)) !== false) {
					// Exclure les éléments spéciaux
					if ($element != '.' && $element != '..') {
						// Construire le chemin complet de l'élément
						$cheminComplet = $chemin . '/' . $element;
	
						// Vérifier si c'est un dossier
						if (is_dir($cheminComplet)) {
							// Afficher le nom du dossier
							$items .= "<li>$element";
							// Appeler récursivement la fonction pour ce sous-dossier
							$items .= $this->listerSousDossier($cheminComplet);
							$items .= '</li>';
						} else {
							// Afficher le nom du fichier comme un lien
							$items .= '<li><a href="' . $cheminComplet . '">' . $element . '</a></li>';
						}
					}
				}
				$items .=  "</ul>";
	
				// Fermer le dossier
				closedir($dh);
			}
			return $items;
		} else {
			exit ('Erreur de chemin');
		}
		
	}

}