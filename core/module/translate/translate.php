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
		'index' => self::GROUP_MODERATOR,
		'language' => self::GROUP_VISITOR
	];

	public static $typeTranslate = [
		'none'   => 'Masqué',
		'script' => 'Traduction automatique',
		'site'   => 'Traduction rédigée'
	];

	/**
	 * Configuration
	 */
	public function index() {

		// Soumission du formulaire
		if($this->isPost()) {
			// Désactivation du script Google
			if ($this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) === false) {
				setrawcookie('googtrans', '/fr/fr', time() + 3600, helper::baseUrl());
			}
			// Edition des langues
			foreach (self::$i18nList as $keyi18n => $value) {
				if ($keyi18n === 'fr') {continue;}
				// Effacement d'une langue installée
				if ( is_dir( self::DATA_DIR . $keyi18n ) === true
					AND  $this->getInput('translate' . strtoupper($keyi18n)) === 'none')
				 {
						$this->removeDir( self::DATA_DIR . $keyi18n);
				}
				// Installation d'une langue
				if ( $this->getInput('translate' . strtoupper($keyi18n)) === 'site')
				{
					// Créer les données absentes
					if (is_dir( self::DATA_DIR . $keyi18n )  === false ) {
						mkdir( self::DATA_DIR . $keyi18n);
						// Charger les modèles
						require_once('core/module/install/ressource/defaultdata.php');
						// Nouvelle instance page, module, locale
						$files = ['page','module','locale'];
						foreach ($files as $keyFile) {
							echo $keyFile;
							$e = new \Prowebcraft\JsonDb([
								'name' => $keyFile . '.json',
								'dir' => $this->dirData ($keyFile,$keyi18n)
							]);;
							$e->set($keyFile, init::$defaultData[$keyFile]);
							$e->save();
						}
					}
				}
			}

			// Enregistrement des données
			$this->setData(['config','translate', [
				'scriptGoogle'      => $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN),
				'showCredits' 	 	=> $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) ? $this->getInput('translateCredits', helper::FILTER_BOOLEAN) : false,
				'autoDetect' 	 	=> $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) ? $this->getInput('translateAutoDetect', helper::FILTER_BOOLEAN) : false,
				'admin'			 	=> $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) ? $this->getInput('translateAdmin', helper::FILTER_BOOLEAN) : false,
					'fr'		 	=> $this->getInput('translateFR'),
					'de' 		 	=> ($this->getInput('translateDE') === 'script' AND $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) === false) ? 'none' : $this->getInput('translateDE'),
					'en' 		 	=> ($this->getInput('translateEN') === 'script' AND $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) === false) ? 'none' : $this->getInput('translateEN'),
					'es' 		 	=> ($this->getInput('translateES') === 'script' AND $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) === false) ? 'none' : $this->getInput('translateES'),
					'it' 		 	=> ($this->getInput('translateIT') === 'script' AND $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) === false) ? 'none' : $this->getInput('translateIT'),
					'nl' 		 	=> ($this->getInput('translateNL') === 'script' AND $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) === false) ? 'none' : $this->getInput('translateNL'),
					'pt' 		 	=> ($this->getInput('translatePT') === 'script' AND $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN) === false) ? 'none' : $this->getInput('translatePT')

			]]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Paramètres',
			'view' => 'index'
		]);
	}

			/*
	* Traitement du changement de langues
	*/
	public function language() {
		// Transmettre le choix au noyau
		if ($this->getUrl(3) === 'script') {
			setrawcookie("googtrans", '/fr/'. $this->getUrl(2), time() + 3600, helper::baseUrl());
			helper::deleteCookie('ZWII_I18N_SITE');
		} elseif ($this->getUrl(3) === 'site') {
			setcookie('ZWII_I18N_SITE', $this->getUrl(2), time() + 3600, helper::baseUrl(false, false)  , '', helper::isHttps(), true);
			setrawcookie("googtrans", '/fr/fr', time() + 3600, helper::baseUrl());
		}
		// Valeurs en sortie
		$this->addOutput([
			'redirect' 		=> 	helper::baseUrl()
		]);
	}
}