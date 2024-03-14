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

class folder extends common
{

	const VERSION = '0.1';
	const REALNAME = 'Partage de dossier';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	public static $actions = [
		'config' => self::GROUP_EDITOR,
		'index' => self::GROUP_VISITOR,
	];

	// Contenu du chemin sélectionné
	public static $folders = '';

	public static $sharePath = [
		'site/file/source/'
	];

	public function index()
	{

		self::$folders = $this->getFolderContent($this->getData(['module', $this->getUrl(0), 'path']));

		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index'
		]);
	}


	public function config()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			$this->setData(['module', $this->getUrl(0), 'path', preg_replace('/^\\./', '', $this->getInput('folderEditPath')) ]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0),
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);

		}

		self::$sharePath = $this->getSubdirectories('site/file/source');
		self::$sharePath = array_flip(self::$sharePath);

		// Valeurs en sortie
		$this->addOutput([
			'view' => 'config'
		]);
	}


private function getFolderContent($chemin)
{
    // Vérifier si le chemin existe et est un dossier
    if (is_dir($chemin)) {
        // Ouvrir le dossier
        if ($dh = opendir($chemin)) {
            $items = isset($items) ? $items . '<ul class="folder">' : '<ul class ="folder">';
            // Parcourir les éléments du dossier
            while (($element = readdir($dh)) !== false) {
                // Exclure les éléments spéciaux
                if ($element != '.' && $element != '..') {
                    // Construire le chemin complet de l'élément
                    $cheminComplet = $chemin . $element;

                    // Vérifier si c'est un dossier
                    if (is_dir($cheminComplet)) {
                        // Afficher le nom du dossier avec un élément details
                        $items .= "<li class='directory'>$element<ul>";
                        // Appeler récursivement la fonction pour ce sous-dossier
                        $items .= $this->getFolderContent($cheminComplet);
                        $items .= '</ul></li>';
                    } else {
                        // Afficher le nom du fichier comme un lien
                        $items .= "<li class='file'><a href='$cheminComplet' target='_blank'>$element</a></li>";
                    }
                }
            }
            $items .= "</ul>";

            // Fermer le dossier
            closedir($dh);
        }
        return $items;
    }
}

	
	

	/**
	 * Liste les dossier contenus dans RFM
	 */
	private function getSubdirectories($dir, $basePath = '')
	{
		$subdirs = array();
		// Ouvrez le répertoire spécifié
		$dh = opendir($dir);
		// Parcourez tous les fichiers et répertoires dans le répertoire
		while (($file = readdir($dh)) !== false) {
			// Ignorer les entrées de répertoire parent et actuel
			if ($file == '.' || $file == '..') {
				continue;
			}
			// Construisez le chemin complet du fichier ou du répertoire
			$path = $dir . '/' . $file;
			// Vérifiez si c'est un répertoire
			if (is_dir($path)) {
				// Construisez la clé et la valeur pour le tableau associatif
				$key = $basePath === '' ? ucfirst($file) : $basePath . '/' . $file;
				$value = $path . '/';
				// Ajouter la clé et la valeur au tableau associatif
				$subdirs[$key] = $value;
				// Appeler la fonction récursivement pour ajouter les sous-répertoires
				$subdirs = array_merge($subdirs, $this->getSubdirectories($path, $key));
			}
		}
		// Fermez le gestionnaire de dossier
		closedir($dh);
		return $subdirs;
	}

}