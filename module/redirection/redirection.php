<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class redirection extends common {

	const VERSION = '1.5';
	const REALNAME = 'Redirection';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	public static $actions = [
		'config' => self::GROUP_MODERATOR,
		'index' => self::GROUP_VISITOR
	];


	/**
	 * Configuration
	 */
	public function config() {
		// Soumission du formulaire
		if($this->isPost()) {
			$this->setData(['module', $this->getUrl(0), 'url', $this->getInput('redirectionConfigUrl', helper::FILTER_URL, true)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Configuration du module',
			'view' => 'config'
		]);
	}

	/**
	 * Accueil
	 */
	public function index() {
		// Message si l'utilisateur peut éditer la page
		if(
			$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
			AND $this->getUser('group') >= self::GROUP_MODERATOR
			AND $this->getUrl(1) !== 'force'
		) {
			// Valeurs en sortie
			$this->addOutput([
				'display' => self::DISPLAY_LAYOUT_BLANK,
				'title' => '',
				'view' => 'index'
			]);
		}
		// Sinon redirection
		else {
			// Incrémente le compteur de clics
			$this->setData(['module', $this->getUrl(0), 'count', helper::filter($this->getData(['module', $this->getUrl(0), 'count']) + 1, helper::FILTER_INT)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => $this->getData(['module', $this->getUrl(0), 'url']),
				'state' => true
			]);
		}
	}
}
