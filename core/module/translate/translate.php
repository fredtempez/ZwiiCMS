<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2021, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

class translate extends common {

	public static $actions = [
		/*'config' => self::GROUP_MODERATOR,*/
		'index' => self::GROUP_ADMIN,
		'copy' => self::GROUP_ADMIN,
		'i18n' => self::GROUP_VISITOR
	];

	public static $translateOptions = [];

	// Liste des langues installées
	public static $languagesInstalled = [];
	// Liste des langues cibles
	public static $languagesTarget = [];
	// Activation du bouton de copie
	public static $siteTranslate = true;

	/**
	 * Configuration avancée des langues
	 */
	public function copy() {

		// Soumission du formulaire
		if ($this->isPost()) {
			// Initialisation
			$success = false;
			$copyFrom = $this->getInput('translateFormCopySource');
			$toCreate = $this->getInput('translateFormCopyTarget');
			if ($copyFrom !== $toCreate) {
				// Création du dossier
				if (is_dir(self::DATA_DIR . $toCreate) === false ) { // Si le dossier est déjà créé
					$success  = mkdir (self::DATA_DIR . $toCreate, 0755);
					$success  = mkdir (self::DATA_DIR . $toCreate.'/content', 0755);
				} else {
					$success = true;
				}
				// Copier les données par défaut avec gestion des erreurs
				$success  = (copy (self::DATA_DIR . $copyFrom . '/locale.json', self::DATA_DIR . $toCreate . '/locale.json') === true && $success  === true) ? true : false;
				$success  = (copy (self::DATA_DIR . $copyFrom . '/module.json', self::DATA_DIR . $toCreate . '/module.json') === true && $success  === true) ? true : false;
				$success  = (copy (self::DATA_DIR . $copyFrom . '/page.json', self::DATA_DIR . $toCreate . '/page.json') === true && $success  === true) ? true : false;
				$success  = ($this->copyDir (self::DATA_DIR . $copyFrom . '/content', self::DATA_DIR . $toCreate . '/content') === true && $success  === true) ? true : false;
				// Enregistrer la langue
				if ($success) {
					$this->setData(['config', 'i18n', $toCreate, 'site' ]);
					$notification = 'Données ' . self::$i18nList[$copyFrom] . ' copiées vers ' .  self::$i18nList[$toCreate];
				} else {
					$notification = "Quelque chose n\'a pas fonctionné, vérifiez les permissions.";
				}
			} else {
				$success = false;
				$notification = 'Les langues doivent être différentes.';
			}
			// Valeurs en sortie
			$this->addOutput([
				'notification'  =>  $notification,
				'title' 		=> 'Utilitaire de copie',
				'view' 			=> 'index',
				'state' 		=>  $success
			]);
		}
		// Tableau des langues installées
		foreach (self::$i18nList as $key => $value) {
			if ($this->getData(['config','i18n', $key]) === 'site') {
				self::$languagesTarget[$key] = $value;
			}
		}
		// Langues cibles fr en plus
		self::$languagesInstalled = array_merge(['fr'	=> 'Français (fr)'],self::$languagesTarget);

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Utilitaire de copie',
			'view' => 'copy'
		]);
	}

	/**
	 * Configuration
	 */
	public function index() {

		// Soumission du formulaire
		if($this->isPost()) {
			// Désactivation du script Google
			$script = $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN);
			if ($script === false) {
				setrawcookie('googtrans', '/fr/fr', time() + 3600, helper::baseUrl(false,false));
				$_SESSION['googtrans'] = '/fr/fr';
			}
			// Edition des langues
			foreach (self::$i18nList as $keyi18n => $value) {
				if ($keyi18n === 'fr') continue;

				// Effacement d'une langue installée
				if ( is_dir( self::DATA_DIR . $keyi18n ) === true
					AND  $this->getInput('translate' . strtoupper($keyi18n)) === 'delete')
				{
						$this->removeDir( self::DATA_DIR . $keyi18n);
						// Au cas ou la langue est sélectionnée
						helper::deleteCookie('ZWII_I18N_SITE');
						helper::deleteCookie('ZWII_I18N_SCRIPT');
				}

				// Active le script si une langue est en trad auto
				if ($script === false
					AND $this->getInput('translate'. strtoupper($keyi18n)) === 'script') {
						$script = true;
					}
			}

			// Enregistrement des données
			$this->setData(['config','i18n', [
				'enable'			=> $this->getData(['config', 'i18n', 'enable']),
				'scriptGoogle'      => $script,
				'showCredits' 	 	=> $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) ? $this->getInput('translateCredits', helper::FILTER_BOOLEAN) : false,
				'autoDetect' 	 	=> $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) ? $this->getInput('translateAutoDetect', helper::FILTER_BOOLEAN) : false,
				//'admin'			 	=> $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) ? $this->getInput('translateAdmin', helper::FILTER_BOOLEAN) : false,
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
		// Modification des options de suppression de la langue installée.
		foreach (self::$i18nList as $key => $value) {
			if ($this->getData(['config','i18n',$key]) === 'site') {
				self::$translateOptions [$key] = [
					'none'   => 'Drapeau masqué',
					'script' => 'Traduction automatique',
					'site'   => 'Traduction rédigée',
					'delete' => 'Supprimer la traduction'
				];
				self::$siteTranslate = $key !== 'fr' ? false : true;
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
	public function i18n() {

		// Activation du drapeau
		if ( $this->getInput('ZWII_I18N_' . strtoupper($this->getUrl(3))) !== $this->getUrl(2) ) {
			// Nettoyer et stocker le choix de l'utilisateur
			helper::deleteCookie('ZWII_I18N_SITE');
			helper::deleteCookie('ZWII_I18N_SCRIPT');
			// Sélectionner
			setcookie('ZWII_I18N_' . strtoupper($this->getUrl(3)) , $this->getUrl(2), time() + 3600, helper::baseUrl(false, false)  , '', helper::isHttps(), true);
			setrawcookie('googtrans', '/fr/' . $this->getUrl(2), time() + 3600, helper::baseUrl(false,false));
			$_SESSION['googtrans'] = '/fr/' . $this->getUrl(2);
		// Désactivation du drapeau, langue FR par défaut
		} else {
			setcookie('ZWII_I18N_SITE' , 'fr', time() + 3600, helper::baseUrl(false, false)  , '', helper::isHttps(), true);
			helper::deleteCookie('ZWII_I18N_SCRIPT');
			// Désactivation du script Google
			setrawcookie('googtrans', '/fr/fr', time() + 3600, helper::baseUrl(false,false));
			$_SESSION['googtrans'] = '/fr/fr';
		}

		// Valeurs en sortie
		$this->addOutput([
			'redirect' 		=> 	helper::baseUrl() . $this->getUrl(4)
		]);
	}
}