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
 * @copyright Copyright (C) 2018-2021, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 *
 * Module Snipcart pour e-commerce
 * @author Sylvain Lelièvre <lelievresylvain@free.fr>
 * @copyright Copyright (C) 2020-2021, Sylvain Lelièvre
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2023, Frédéric Tempez
 * 
 */

/*
 * A faire :
 * Dissocier la configuration générale du thème de la page sélectionnée, les paramètres de la boutique sont communs au site mais pas ceux du thème
 * Stocker les feuilles de styles, une par page invoquée dans la page
 */


class snipcart extends common
{

	public static $actions = [
		'config' => self::GROUP_MODERATOR,
		'index' => self::GROUP_VISITOR
	];


	const VERSION = '2.0';
	const REALNAME = 'Snipcart';
	const DELETE = true;
	const UPDATE = '0.0';
	const DATADIRECTORY = self::DATA_DIR . 'snipcart/';

	public static $choixTemplate = [
		'bouton_seul' => 'Le module ne crée que le bouton d\'ajout au panier',
		'bouton_produit' => 'Le module crée simultanément le produit et le bouton'
	];

	public static $checkMessage = '';

	/**
	 * Mise à jour du module
	 * Appelée par les fonctions index et config
	 */
	private function update()
	{

		// Init
		if (is_null($this->getData(['module', $this->getUrl(0), 'config']))) {
			$this->setData([
				'module', $this->getUrl(0),
				'config',
				[
					'valid' => false,
					'key' => '',
					'poids' => '1',
					'taxes' => 'TVA 20%',
					'transport' => true,
					'buttonText' => 'Ajout au panier',
					'buttonWidth' => '150',
					'buttonColor' => 'rgba(33, 34, 35, 1)',
					'buttonBgColor' => 'rgba(162, 223, 57, 1)',
					'template' => 'bouton_seul',
					'versionData' => '1.0'
				]
			]);
		}
	}

	/**
	 * Configuration du module et de la boutique
	 */
	public function config()
	{
		if ($this->isPost()) {
			// Enregistre la page
			$this->setData([
				'module', 
					$this->getUrl(0), 
						'content',  
							$this->getInput('snipcartContent', helper::FILTER_STRING_LONG)
							
			]);
			// Enregistre les paramètres
			$this->setData([
				'module', $this->getUrl(0),
				'config',
				[
					'valid' => $this->getInput('snipcartOptionValid', helper::FILTER_BOOLEAN),
					'key' => $this->getInput('snipcartOptionKey', helper::FILTER_STRING_SHORT),
					'poids' => $this->getInput('snipcartOptionPoids', helper::FILTER_STRING_SHORT),
					'taxes' => $this->getInput('snipcartOptionTaxes', helper::FILTER_STRING_SHORT),
					'transport' => $this->getInput('snipcartOptionTransport', helper::FILTER_BOOLEAN),
					'buttonText' => $this->getInput('snipcartOptionText', helper::FILTER_STRING_SHORT),
					'buttonWidth' => $this->getInput('snipcartOptionWidth', helper::FILTER_STRING_SHORT),
					'buttonColor' => $this->getInput('snipcartOptionColor', helper::FILTER_STRING_SHORT),
					'buttonBgColor' => $this->getInput('snipcartOptionBgColor', helper::FILTER_STRING_SHORT),
					'template' => $this->getInput('snipcartOptionTemplate', helper::FILTER_STRING_SHORT),
					'versionData' => $this->getData(['module', $this->getUrl(0), 'config', 'versionData'])
				],
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Contenu de la boutique',
			'vendor' => [
				'tinymce4',
				'tinycolorpicker'
			],
			'view' => 'config'
		]);

	}


	/**
	 * Fonction index()
	 */

	public function index()
	{


		// Mise à jour des données de module
		$this->update();

		// Check configuration
		if ($this->getData(['module', $this->getUrl(0), 'config', 'valid']) !== true) {
			self::$checkMessage = 'Snipcart n\'est pas activé !';
		}
		if ($this->getData(['module', $this->getUrl(0), 'config', 'key']) === '') {
			self::$checkMessage = 'La clef snipcart n\'est pas renseignée !';
		}
		// Lecture des données de la boutique
		if (!is_dir(self::DATA_DIR . 'snipcart')) {
			mkdir(self::DATA_DIR . 'snipcart');
		}
		$data = $this->getData(['module', $this->getUrl(0),'config']);
		file_put_contents(self::DATA_DIR . 'snipcart/default.dat', json_encode($data));
			
		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index',
			'style' => $this->getData(['module', $this->getUrl(0), 'theme', 'style']) && file_exists($this->getData(['module', $this->getUrl(0), 'theme', 'style']))
			? $this->getData(['module', $this->getUrl(0), 'theme', 'style'])
			: '',
			'vendor' => [
				'snipcart'
			]
		]);

	}
}
?>