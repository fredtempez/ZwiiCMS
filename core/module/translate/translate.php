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
				'activated'      => $this->getInput('translateActivated', helper::FILTER_BOOLEAN),
				'showCredits' 	 => $this->getInput('translateCredits', helper::FILTER_BOOLEAN) ? $this->getInput('translateCredits', helper::FILTER_BOOLEAN) : false,
				'autoDetect' 	 => $this->getInput('translateAutoDetect', helper::FILTER_BOOLEAN)
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
			'redirect' 		=> 	helper::baseUrl(false)
		]);
	}
}