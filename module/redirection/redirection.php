<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class redirection extends common
{

	const VERSION = '2.2';
	const REALNAME = 'Redirection';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	public static $actions = [
		'config' => self::ROLE_EDITOR,
		'index' => self::ROLE_VISITOR
	];


	/**
	 * Configuration
	 */
	public function config()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			$this->setData(['module', $this->getUrl(0), 'url', $this->getInput('redirectionConfigUrl', helper::FILTER_URL, true)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Configuration du module'),
			'view' => 'config'
		]);
	}

	/**
	 * Accueil
	 */
	public function index()
	{
		// Message si l'utilisateur peut éditer la page
		if (
			$this->isConnected() === true
			&& $this->getUser('role') >= self::ROLE_EDITOR
			&& $this->getUrl(1) !== 'force'
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