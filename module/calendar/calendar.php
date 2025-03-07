<?php

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class calendar extends common
{

	const VERSION = '1.8';
	const REALNAME = 'Calendrier';
	const DATA_DIRECTORY = self::DATA_DIR . 'calendar/';

	const SORT_ASC = 'SORT_ASC';
	const SORT_DSC = 'SORT_DSC';
	const SORT_HAND = 'SORT_HAND';


	public static $calendars = [];

	public static $classes = ['' => 'A définir'];

	public static $actions = [
		'config' => self::GROUP_EDITOR,
		'delete' => self::GROUP_EDITOR,
		'dirs' => self::GROUP_EDITOR,
		'add' => self::GROUP_EDITOR,
		'edit' => self::GROUP_EDITOR,
		'index' => self::ROLE_VISITOR
	];


	/**
	 * Mise à jour du module
	 * Appelée par les fonctions index et config
	 */
	private function update()
	{

		//$versionData = $this->getData(['module', $this->getUrl(0), 'config', 'versionData']);

	}


	/**
	 * Configuration
	 */
	public function config()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true
		) {
			$calendars = $this->getData(['module', $this->getUrl(0), 'content']);
			if (is_null($calendars)) {
				$this->setData(['module', $this->getUrl(0), 'content', []]);
			} elseif (!empty($calendars)) {
				foreach ($calendars as $calendarId => $calendarData) {
					self::$calendars[] = [
						$calendarData['eventName'],
						helper::dateUTF8('%d %m %Y', $calendarData['date'], self::$i18nUI),
						empty($calendarData['time']) ? '' : helper::dateUTF8('%H:%M', $calendarData['time'], self::$i18nUI),
						template::button('calendarConfigEdit' . $calendarId, [
							'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $calendarId,
							'value' => template::ico('pencil'),
							'help' => 'Configuration'
						]),
						template::button('galleryConfigDelete' . $calendarId, [
							'class' => 'galleryConfigDelete buttonRed',
							'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $calendarId,
							'value' => template::ico('trash'),
							'help' => 'Supprimer'
						])

					];
				}
			}
		}

		// Initialise la feuille de style
		if (empty($this->getData(['page', $this->getUrl(0), 'css']))) {
			$this->initCss();
		}

		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'title' => helper::translate('Configuration'),
			'view' => 'config'
		]);
	}

	/**
	 * Ajout d'une événement 
	 */
	public function add()
	{
		// Soumission du formulaire d'ajout d'une galerie
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {

			$this->setData([
				'module',
				$this->getUrl(0),
				'content',
				uniqid(),
				[
					'eventName' => $this->getInput('calendarAddEventName', null, true),
					'date' => $this->getInput('calendarAddDate', helper::FILTER_DATETIME, true),
					'time' => $this->getInput('calendarAddAllDay', helper::FILTER_BOOLEAN) === false ? $this->getInput('calendarAddTime', helper::FILTER_DATETIME) : '',
					'className' => $this->getInput('calendarAddDateClassName', null, true),
					'dateColor' => $this->getInput('calendarAddDateColor', null),
				]
			]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}

		// Liste des classes disponibles
		$classes = $this->getData(['page', $this->getUrl(0), 'css']);
		$classes = trim($classes); // Supprimer les espaces au début et à la fin
		$classes = preg_replace('/\s+/', ' ', $classes); // Remplacer les espaces multiples par un seul espace
		preg_match_all('/\.(\w[\w-]*)\b/', $classes, $matches);

		// Construction du sélecteur
		if (isset($matches[1]) && !empty($matches[1])) {
			// Supprimer les valeurs vides ou invalides
			$filteredMatches = array_filter($matches[1], function ($class) {
				return !empty($class); // Exclure les valeurs vides ou nulles
			});

			// Vérifier que $filteredMatches n'est pas vide
			if (!empty($filteredMatches)) {
				// Créer un tableau associatif
				$associativeClasses = array_combine($filteredMatches, $filteredMatches);

				// Fusionner ce tableau avec le tableau self::$classes
				self::$classes = array_merge(self::$classes, $associativeClasses);
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Nouvel événement'),
			'view' => 'add',
			'vendor' => [
				'tinycolorpicker'
			],
		]);
	}

	/**
	 * Ajout d'une événement 
	 */
	public function edit()
	{
		// Soumission du formulaire d'ajout d'une galerie
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {

			$this->setData([
				'module',
				$this->getUrl(0),
				'content',
				$this->getUrl(2),
				[
					'eventName' => $this->getInput('calendarEditEventName', null, true),
					'date' => $this->getInput('calendarEditDate', helper::FILTER_DATETIME, true),
					'time' => $this->getInput('calendarEditAllDay', helper::FILTER_BOOLEAN) === false ? $this->getInput('calendarEditTime', helper::FILTER_DATETIME) : '',
					'className' => $this->getInput('calendarEditDateClassName', null, true),
					'dateColor' => $this->getInput('calendarEditDateColor', null),
				]
			]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}

		// Liste des classes disponibles
		$classes = $this->getData(['page', $this->getUrl(0), 'css']);
		$classes = trim($classes); // Supprimer les espaces au début et à la fin
		$classes = preg_replace('/\s+/', ' ', $classes); // Remplacer les espaces multiples par un seul espace
		preg_match_all('/\.(\w[\w-]*)\b/', $classes, $matches);

		// Construction du sélecteur
		if (isset($matches[1]) && !empty($matches[1])) {
			// Supprimer les valeurs vides ou invalides
			$filteredMatches = array_filter($matches[1], function ($class) {
				return !empty($class); // Exclure les valeurs vides ou nulles
			});

			// Vérifier que $filteredMatches n'est pas vide
			if (!empty($filteredMatches)) {
				// Créer un tableau associatif
				$associativeClasses = array_combine($filteredMatches, $filteredMatches);

				// Fusionner ce tableau avec le tableau self::$classes
				self::$classes = array_merge(self::$classes, $associativeClasses);
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Edition'),
			'view' => 'edit',
			'vendor' => [
				'tinycolorpicker'
			],
		]);
	}

	/**
	 * Suppression
	 */
	public function delete()
	{
		// La galerie n'existe pas
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true ||
			$this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2)]) === null
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		}
		// Suppression
		else {
			$this->deleteData(['module', $this->getUrl(0), 'content', $this->getUrl(2)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Evenement effacé'),
				'state' => true
			]);
		}
	}


	/**
	 * Accueil (deux affichages en un pour éviter une url à rallonge)
	 */
	public function index()
	{

		// Mise à jour des données de module
		$this->update();

		$calendars = $this->getData(['module', $this->getUrl(0), 'content']);

		// Initialise la feuille de style
		if (empty($this->getData(['page', $this->getUrl(0), 'css']))) {
			$this->initCss();
		}

		// Affichage du template si les données sont disponibles
		if (is_null($calendars)) {
			$this->setData(['module', $this->getUrl(0), 'content', []]);
		} elseif (!empty($calendars)) {
			// Lecture des données
			foreach ($calendars as $calendarsId => $data) {
				// Convertion du timestamp en ISO
				$data['date'] = helper::dateUTF8('%Y-%m-%d', $data['date']);
				// Ajouter l'horaire
				if (!empty($data['time'])) {
					$data['time'] = helper::dateUTF8('%H:%M', $data['time']);
					$data['date'] = $data['date'] . 'T' . $data['time'];
				}

				self::$calendars[] = $data;
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index',
			'vendor' => [
				'animated-calendar'
			],
		]);
	}

	// Page de module vide
	private function initCss()
	{
		// Feuille de styles
		$cssContent =
			'.textRed {
				padding: 2px;
				border-radius: 5px; 
				color: red;
				background-color: lightgrey;
				font-size: 18px;
				width: 90% ;
				}
				.textGreen {
				border-radius: 5px;
				padding: 2px;
				color: lightgreen;
				background-color: darkgrey;
			 	font-size: 18px;
				width: 90% ;
				}
				.textOrange {
				padding: 2px;
				border-radius: 5px; 
				color: orange;
				background-color: green;
				font-size: 18px;
				width: 90% ;
				}';
		$this->setData(['page', $this->getUrl(0), 'css', $cssContent]);
	}
}
