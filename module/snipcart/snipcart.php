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
		'option' => self::GROUP_MODERATOR,
		'index' => self::GROUP_VISITOR
	];


	const VERSION = '2.0';
	const REALNAME = 'Snipcart';
	const DELETE = true;
	const UPDATE = '0.0';
	const DATADIRECTORY = self::DATA_DIR . 'snipcart/';

	// Dossier des données du module externes à module.json
	// Modifications à faire en plus dans config.php et dans core/vendor/.../snipcart/plugin.js et plugin.min.js
	const DATAMODULE = self::DATA_DIR . 'snipcart/module';

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

		// Initialisation ou mise à jour vers la version 1.4
		if (version_compare($this->getData(['module', $this->getUrl(0), 'config', 'versionData']), '1.4', '<')) {
			// Si c'est une initialisation
			if (null === $this->getData(['module', $this->getUrl(0), 'config'])) {
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
						'versionData' => '1.4'
					]
				]);
			} else {
				// Mise à jour de version snipcart <=1.3 vers 1.4
				// Déplacement de datadefault.json de site/data/snipcart vers site/data/snipcart/module 
				if (is_file('site/data/snipcart/datadefault.json')) {
					rename('site/data/snipcart/datadefault.json', self::DATAMODULE . '/datadefault.json');
					unlink('site/data/snipcart/interdits.html');
					unlink('site/data/snipcart/snipcart_mode_emploi.pdf');

				}
				$this->setData(['module', $this->getUrl(0), 'config', 'versionData', '1.4']);
			}
		}
	}

	/**
	 * Configuration
	 */
	public function option()
	{

		// Mise à jour des données de module
		$this->update();

		// Soumission du formulaire
		if ($this->isPost()) {
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

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		} else {
			// Pour compatibilité avec version 1.1
			if (null === $this->getData(['module', $this->getUrl(0), 'config', 'template'])) {
				$template = 'bouton_seul';
			} else {
				$template = $this->getData(['module', $this->getUrl(0), 'config', 'template']);
			}

			// Modification du fichier datadefault.json
			$data = [];
			$data["poids"] = $this->getData(['module', $this->getUrl(0), 'config', 'poids']);
			$data["taxes"] = $this->getData(['module', $this->getUrl(0), 'config', 'taxes']);
			$data["transport"] = $this->getData(['module', $this->getUrl(0), 'config', 'transport']);
			$data["buttonText"] = $this->getData(['module', $this->getUrl(0), 'config', 'buttonText']);
			$data["buttonWidth"] = $this->getData(['module', $this->getUrl(0), 'config', 'buttonWidth']);
			$data["buttonColor"] = $this->getData(['module', $this->getUrl(0), 'config', 'buttonColor']);
			$data["buttonBgColor"] = $this->getData(['module', $this->getUrl(0), 'config', 'buttonBgColor']);
			$data["template"] = $template;
			$json = json_encode($data);
			file_put_contents(self::DATAMODULE . '/datadefault.json', $json);

			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Options',
				'vendor' => [
					'tinycolorpicker'
				],
				'view' => 'option'
			]);
		}
	}

	/**
	 * Configuration du module et de la boutique
	 */
	public function config()
	{
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Configuration',
			'vendor' => [
				'tinymce4'
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

		// Pour compatibilité avec version 1.1
		if (null === $this->getData(['module', $this->getUrl(0), 'config', 'template'])) {
			$template = 'bouton_seul';
		} else {
			$template = $this->getData(['module', $this->getUrl(0), 'config', 'template']);
		}

		// Mise à jour du fichier datadefault.json à l'ouverture de chaque page boutique
		// Ces paramètres sont propres à la page boutique et mémorisés dans module.json
		$data = [];
		$data["poids"] = $this->getData(['module', $this->getUrl(0), 'config', 'poids']);
		$data["taxes"] = $this->getData(['module', $this->getUrl(0), 'config', 'taxes']);
		$data["transport"] = $this->getData(['module', $this->getUrl(0), 'config', 'transport']);
		$data["buttonText"] = $this->getData(['module', $this->getUrl(0), 'config', 'buttonText']);
		$data["buttonWidth"] = $this->getData(['module', $this->getUrl(0), 'config', 'buttonWidth']);
		$data["buttonColor"] = $this->getData(['module', $this->getUrl(0), 'config', 'buttonColor']);
		$data["buttonBgColor"] = $this->getData(['module', $this->getUrl(0), 'config', 'buttonBgColor']);
		$data["template"] = $template;
		$json = json_encode($data);
		file_put_contents(self::DATAMODULE . '/datadefault.json', $json);


		if ($this->getData(['module', $this->getUrl(0), 'config', 'valid']) !== true) {
			self::$checkMessage = 'Snipcart n\'est pas activé !';
		}
		if ($this->getData(['module', $this->getUrl(0), 'config', 'key']) === '') {
			self::$checkMessage = 'La clef snipcart n\'est pas renseignée !';
		}
		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index',
			'vendor' => [
				'snipcart'
			]
		]);

	}
}
?>