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
 * @link http://zwiicms.fr/
 */

class translate extends common {

	public static $actions = [
		/*'config' => self::GROUP_MODERATOR,*/
		'index' => self::GROUP_ADMIN,
		'advanced' => self::GROUP_ADMIN,
		'language' => self::GROUP_VISITOR
	];

	public static $translateOptions = [];

	// Liste des langues installées
	public static $languagesInstalled = [];
	// Liste des langues cibles
	public static $languagesTarget = [];

	/**
	 * Configuration avancée des langues
	 */
	public function advanced() {

		// Soumission du formulaire
		if ($this->isPost()) {
			// Initialisation
			$success = false;
			$copyFrom = $this->getInput('translateFormAdvancedSource');
			$toCreate = $this->getInput('translateFormAdvancedTarget');
			// Création du dossier
			if (is_dir(self::DATA_DIR . $toCreate) === false ) { // Si le dossier est déjà créé
				$success  = mkdir (self::DATA_DIR . $toCreate);
			} else {
				$success = true;
			}
			// Copier les données par défaut avec gestion des erreurs
			$success  = (copy (self::DATA_DIR . $copyFrom . '/locale.json', self::DATA_DIR . $toCreate . '/locale.json') === true && $success  === true) ? true : false;
			$success  = (copy (self::DATA_DIR . $copyFrom . '/module.json', self::DATA_DIR . $toCreate . '/module.json') === true && $success  === true) ? true : false;
			$success  = (copy (self::DATA_DIR . $copyFrom . '/page.json', self::DATA_DIR . $toCreate . '/page.json') === true && $success  === true) ? true : false;			
			// Enregistrer la langue
			if ($success) {
				$this->setData(['config', 'translate', $toCreate, 'site' ]);
				$notification = 'Données ' . self::$i18nList[$copyFrom] . ' copiées vers ' .  self::$i18nList[$toCreate];
			} else {
				$notification = "Quelque chose n\'a pas fonctionné, vérifiez les permissions.";
			}
			// Valeurs en sortie
			$this->addOutput([
				'notification'  =>  $notification,
				'title' 		=> 'Gestion avancée',
				'view' 			=> 'index',
				'state' 		=>  $success
			]);
		}
		// Tableau des langues installées
		foreach (self::$i18nList as $key => $value) {
			if ($this->getData(['config','translate',$key]) === 'site') {
				self::$languagesTarget[$key] = $value;
			}
		}
		// Langues cibles fr en plus 
		self::$languagesInstalled = array_merge(['fr'	=> 'Français (fr)'],self::$languagesTarget);

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Gestion avancée',
			'view' => 'advanced'
		]);
	}

	/**
	 * Configuration
	 */
	public function index() {

		// Soumission du formulaire
		if($this->isPost()) {
			// Désactivation du script Google
			if ($this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) === false) {
				setrawcookie('googtrans', '/fr/fr', time() + 3600, helper::baseUrl());
				$_SESSION['googtrans'] = '/fr/fr';
			}
			$script = $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN);
			// Edition des langues
			foreach (self::$i18nList as $keyi18n => $value) {
				if ($keyi18n === 'fr') {continue;}
				// Effacement d'une langue installée
				if ( is_dir( self::DATA_DIR . $keyi18n ) === true
					AND  $this->getInput('translate' . strtoupper($keyi18n)) === 'delete')
				 {
						$this->removeDir( self::DATA_DIR . $keyi18n);
				}
				// Installation d'une langue
				if ( $this->getInput('translate' . strtoupper($keyi18n)) === 'site')
				{
					// Créer les données absentes
					if (is_dir( self::DATA_DIR . $keyi18n )  === false ) {
						mkdir( self::DATA_DIR . $keyi18n);
					}
					// Charger les modèles
					require_once('core/module/install/ressource/defaultdata.php');
					// Nouvelle instance des pages, module, locale
					$files = ['page','module','locale'];
					foreach ($files as $keyFile) {
						echo $keyFile;
						$e = new \Prowebcraft\JsonDb([
							'name' => $keyFile . '.json',
							'dir' => $this->dataPath ($keyFile,$keyi18n)
						]);;
						$e->set($keyFile, init::$defaultData[$keyFile]);
						$e->save();
					}
				}
				// Active le script si une langue est en trad auto
				if ($script === false
					AND $this->getInput('translate'. strtoupper($keyi18n)) === 'script') {
						$script = true;
					}
			}
			// Enregistrement des données
			$this->setData(['config','translate', [
				'scriptGoogle'      => $script,
				'showCredits' 	 	=> $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) ? $this->getInput('translateCredits', helper::FILTER_BOOLEAN) : false,
				'autoDetect' 	 	=> $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) ? $this->getInput('translateAutoDetect', helper::FILTER_BOOLEAN) : false,
				'admin'			 	=> $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) ? $this->getInput('translateAdmin', helper::FILTER_BOOLEAN) : false,
				'fr'		 		=> $this->getInput('translateFR'),
				'de' 		 		=> $this->getInput('translateDE'),
				'en' 			 	=> $this->getInput('translateEN'),
				'es' 			 	=> $this->getInput('translateES'),
				'it' 			 	=> $this->getInput('translateIT'),
				'nl' 			 	=> $this->getInput('translateNL'),
				'pt' 			 	=> $this->getInput('translatePT')

			]]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		}
		// Modification de option de suppression de la langue installée.
		foreach (self::$i18nList as $key => $value) {
			if ($this->getData(['config','translate',$key]) === 'site') {
				self::$translateOptions [$key] = [
					'none'   => 'Drapeau masqué',
					'script' => 'Traduction automatique',
					'site'   => 'Traduction rédigée',
					'delete' => 'Supprimer la traduction'
				];
			} else {
				self::$translateOptions [$key] = [
					'none'   => 'Drapeau masqué',
					'script' => 'Traduction automatique',
					'site'   => 'Traduction rédigée'
				];
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Gestion des langues',
			'view' => 'index'
		]);
	}


	/*
	 * Traitement du changement de langue
	 * Fonction utilisée par le noyau
	 */
	public function language() {
		// Sélection et désélection de la lnague active
		if ( $this->getUrl(2) !== substr($_COOKIE['googtrans'],4,2))
		{ 
			// Transmettre le choix au noyau
			if ($this->getUrl(3) === 'script') {
				setrawcookie("googtrans", '/fr/'. $this->getUrl(2), time() + 3600, helper::baseUrl());
				helper::deleteCookie('ZWII_I18N_SITE');
			} elseif ($this->getUrl(3) === 'site') {
				setcookie('ZWII_I18N_SITE', $this->getUrl(2), time() + 3600, helper::baseUrl(false, false)  , '', helper::isHttps(), true);
				setrawcookie("googtrans", '/fr/fr', time() + 3600, helper::baseUrl());
			}
		} else { 
			// Langue du navigateur  par défaut si dispo
			helper::deleteCookie('ZWII_I18N_SITE');
			if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) {
				setrawcookie("googtrans", '/fr/'. substr( $_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2 ), time() + 3600, helper::baseUrl());
			} else {
				setrawcookie("googtrans", '/fr/fr', time() + 3600, helper::baseUrl());
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'redirect' 		=> 	helper::baseUrl()
		]);
	}
}