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

	/**
	 * Configuration
	 */
	public function index() {

		// Soumission du formulaire
		if($this->isPost()) {
			$this->setData(['config','translate', [
				'scriptGoogle'      => $this->getInput('translateScriptGoogle', helper::FILTER_BOOLEAN),
				'showCredits' 	 => $this->getInput('translateCredits', helper::FILTER_BOOLEAN) ? $this->getInput('translateCredits', helper::FILTER_BOOLEAN) : false,
				'autoDetect' 	 => $this->getInput('translateAutoDetect', helper::FILTER_BOOLEAN),
				'admin'			 => $this->getInput('translateAdmin', helper::FILTER_BOOLEAN),
				'scriptFR' 		 => $this->getInput('translateScriptFlagFR', helper::FILTER_BOOLEAN),
				'scriptDE' 		 => $this->getInput('translateScriptFlagDE', helper::FILTER_BOOLEAN),
				'scriptEN' 		 => $this->getInput('translateScriptFlagEN', helper::FILTER_BOOLEAN),
				'scriptES' 		 => $this->getInput('translateScriptFlagES', helper::FILTER_BOOLEAN),
				'scriptIT' 		 => $this->getInput('translateScriptFlagIT', helper::FILTER_BOOLEAN),
				'scriptNL' 		 => $this->getInput('translateScriptFlagNL', helper::FILTER_BOOLEAN),
				'scriptPT' 		 => $this->getInput('translateScriptFlagPT', helper::FILTER_BOOLEAN),
				'site'           => $this->getInput('translateSite', helper::FILTER_BOOLEAN),
				'siteFR' 		 => $this->getInput('translateSiteFlagFR', helper::FILTER_BOOLEAN),
				'siteDE' 		 => $this->getInput('translateSiteFlagDE', helper::FILTER_BOOLEAN),
				'siteEN' 		 => $this->getInput('translateSiteFlagEN', helper::FILTER_BOOLEAN),
				'siteES' 		 => $this->getInput('translateSiteFlagES', helper::FILTER_BOOLEAN),
				'siteIT' 		 => $this->getInput('translateSiteFlagIT', helper::FILTER_BOOLEAN),
				'siteNL' 		 => $this->getInput('translateSiteFlagNL', helper::FILTER_BOOLEAN),
				'sitePT' 		 => $this->getInput('translateSiteFlagPT', helper::FILTER_BOOLEAN)
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
		setcookie('ZWII_USER_I18N', $this->getUrl(2), time() + 3600, helper::baseUrl(false, false)  , '', helper::isHttps(), true);
		// Valeurs en sortie sans post
		$this->addOutput([
			'redirect' 		=> 	helper::baseUrl()  .  $this->getUrl(3)
		]);
	}
}