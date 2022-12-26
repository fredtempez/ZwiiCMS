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
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

class translate extends common
{

	public static $actions = [
		'index' => self::GROUP_ADMIN,
		'copy' => self::GROUP_ADMIN,
		'add' => self::GROUP_ADMIN, 	// Ajouter une langue de contenu
		'ui' => self::GROUP_ADMIN, 	// Éditer une langue de l'UI
		'locale' => self::GROUP_ADMIN, 	// Éditer une langue de contenu
		'delete' => self::GROUP_ADMIN, 	// Effacer une langue de contenu ou de l'interface
		'content' => self::GROUP_VISITOR,
		'update' => self::GROUP_ADMIN,
	];

	const PAGINATION = '20';

	// Language contents
	public static $translateOptions = [];

	// Page pour la configuration dans la langue
	public static $pagesList = [];
	public static $orphansList = [];
	public static $pages = '';

	// Liste des langues installées
	public static $languagesUiInstalled = [];
	public static $languagesInstalled = [];

	// Liste des langues cibles
	public static $languagesTarget = [];

	// Activation du bouton de copie
	public static $siteCopy = true;

	// Localisation en cours d'édition
	public static $locales = [];

	//UI
	// Fichiers des langues de l'interface
	public static $i18nFiles = [];

	/**
	 * Met à jour les traduction du site
	 */
	public function update()
	{
		$response = $this->copyDir('core/module/install/ressource/i18n', self::I18N_DIR);
		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl() . 'translate',
			'notification' => $response ? helper::translate('Copie terminée avec succès') : 'Copie terminée avec des erreurs',
			'state' => $response
		]);
	}

	/**
	 * Configuration avancée des langues
	 */
	public function copy()
	{

		// Soumission du formulaire
		if ($this->isPost()) {
			// Initialisation
			$success = false;
			$copyFrom = $this->getInput('translateFormCopySource');
			$toCreate = $this->getInput('translateFormCopyTarget');
			if ($copyFrom !== $toCreate) {
				// Création du dossier
				if (is_dir(self::DATA_DIR . $toCreate) === false) { // Si le dossier est déjà créé
					$success  = mkdir(self::DATA_DIR . $toCreate, 0755);
					$success  = mkdir(self::DATA_DIR . $toCreate . '/content', 0755);
				} else {
					$success = true;
				}
				// Copier les données par défaut
				$success  = (copy(self::DATA_DIR . $copyFrom . '/locale.json', self::DATA_DIR . $toCreate . '/locale.json') === true && $success  === true) ? true : false;
				$success  = (copy(self::DATA_DIR . $copyFrom . '/module.json', self::DATA_DIR . $toCreate . '/module.json') === true && $success  === true) ? true : false;
				$success  = (copy(self::DATA_DIR . $copyFrom . '/page.json', self::DATA_DIR . $toCreate . '/page.json') === true && $success  === true) ? true : false;
				$success  = ($this->copyDir(self::DATA_DIR . $copyFrom . '/content', self::DATA_DIR . $toCreate . '/content') === true && $success  === true) ? true : false;
				// Enregistrer la langue
				if ($success) {
					$notification = sprintf(helper::translate('Données %s copiées vers %s'),  self::$languages[$copyFrom], self::$languages[$toCreate]);
				} else {
					$notification = helper::translate('Erreur de copie, vérifiez les permissions');
				}
			} else {
				$success = false;
				$notification = helper::translate('Les langues sélectionnées sont identiques');
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
		foreach (self::$languages as $key => $value) {
			// tableau des langues installées
			if (is_dir(self::DATA_DIR . $key)) {
				self::$languagesTarget[$key] = self::$languages[$key];
			}
		}

		// Langues cibles fr en plus
		self::$languagesInstalled = self::$languagesTarget;

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Copie de contenus localisés'),
			'view' => 'copy'
		]);
	}

	/**
	 * Configuration
	 */
	public function index()
	{


		// Préparation du formulaire
		// -------------------------

		// Onglet des langues de contenu
		foreach (self::$languages as $key => $value) {
			// tableau des langues installées
			if (is_dir(self::DATA_DIR . $key)) {
				if (self::$i18nUI === $key) {
					$messageLocale = helper::translate('Langue par défaut');
				} elseif (isset($_COOKIE['ZWII_CONTENT']) && $_COOKIE['ZWII_CONTENT'] === $key) {
					$messageLocale = helper::translate('Langue du site sélectionnée');
				} else {
					$messageLocale = '';
				}
				self::$languagesInstalled[] = [
					template::flag($key, '20 %'),
					$value . ' (' . $key . ')',
					$messageLocale,
					template::button('translateContentLanguageLocaleEdit' . $key, [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/locale/' . $key,
						'value' => template::ico('pencil'),
						'help' => 'Éditer'
					]),
					template::button('translateContentLanguageLocaleDelete' . $key, [
						'class' => 'translateDeleteLocale buttonRed' . ($messageLocale ? ' disabled' : ''),
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/locale/' . $key . '/' . $_SESSION['csrf'],
						'value' => template::ico('trash'),
						'help' => 'Supprimer',
					])
				];
			}
		}
		// Activation du bouton de copie
		self::$siteCopy = count(self::$languagesInstalled) > 1 ? false : true;

		// Onglet des langues de l'interface
		if (is_dir(self::I18N_DIR)) {
			$dir = getcwd();
			chdir(self::I18N_DIR);
			$files = glob('*.json');
			chdir($dir);
		}

		// Construit le tableau des langues de l'UI
		$usersUI = [];
		$users = $this->getData(['user']);
		foreach ($users as $key => $value) {
			array_push($usersUI, $this->getData(['user', $key, 'language']));
		}

		// Construction du tableau
		foreach ($files as $file) {

			// La langue est-elle référencée ?
			if (array_key_exists(basename($file, '.json'), self::$languages)) {

				//self::$i18nFiles[basename($file, '.json')] = self::$languages[basename($file, '.json')];
				$selected = basename($file, '.json');
				self::$languagesUiInstalled[$file] =  [
					template::flag($selected, '20 %'),
					self::$languages[$selected],
					self::$i18nUI === $selected ? helper::translate('Interface') : '',
					template::button('translateContentLanguageUIEdit' . basename($file, '.json'), [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/ui/' . $selected,
						'value' => template::ico('pencil'),
						'help' => 'Éditer',
						'disabled' => 'fr_FR' === $selected
					]),
					template::button('translateContentLanguageUIDelete' . basename($file, '.json'), [
						'class' => 'translateDeleteUI buttonRed' . (in_array(basename($file, '.json'), $usersUI) ? ' disabled' : ''),
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/ui/' . basename($file, '.json') . '/' . $_SESSION['csrf'],
						'value' => template::ico('trash'),
						'help' => 'Supprimer',
					])
				];
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Multilingue'),
			'view' => 'index'
		]);
	}


	/***
	 * Ajouter une langue de contenu
	 */

	public function add()
	{

		// Soumission du formulaire
		if ($this->isPost()) {

			// Création du contenu
			$lang = $this->getInput('translateAddContent');

			// Stockage dans un sous-dossier localisé
			if (!file_exists(self::DATA_DIR .  $lang)) {
				mkdir(self::DATA_DIR . $lang, 0755);
			}

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'translate',
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}


		// Préparation de l'affichage du formulaire
		//-----------------------------------------

		// Tableau des langues non installées
		foreach (self::$languages as $key => $value) {
			if (!is_dir(self::DATA_DIR . $key))
				self::$i18nFiles[$key] = $value;
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Nouveau contenu localisé'),
			'view' => 'add'
		]);
	}

	/**
	 * Edition des paramètres de la langue de contenu
	 */
	public function locale()
	{
		// Jeton incorrect ou URl avec le code langue incorrecte
		if (
			!array_key_exists($this->getUrl(2), self::$languages)
		) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'translate',
				'state' => false,
				'notification' => helper::translate('Action interdite')
			]);
		}

		// Soumission du formulaire
		if ($this->isPost()) {

			// Sauvegarder les locales
			$data = [
				'locale' => [
					'homePageId' => $this->getInput('localeHomePageId', helper::FILTER_ID, true),
					'page404' => $this->getInput('localePage404'),
					'page403' => $this->getInput('localePage403'),
					'page302' => $this->getInput('localePage302'),
					'legalPageId' => $this->getInput('localeLegalPageId'),
					'searchPageId' => $this->getInput('localeSearchPageId'),
					'searchPageLabel' => empty($this->getInput('localeSearchPageLabel', helper::FILTER_STRING_SHORT))  ? 'Rechercher' : $this->getInput('localeSearchPageLabel', helper::FILTER_STRING_SHORT),
					'legalPageLabel' => empty($this->getInput('localeLegalPageLabel', helper::FILTER_STRING_SHORT)) ? 'Mentions légales' : $this->getInput('localeLegalPageLabel', helper::FILTER_STRING_SHORT),
					'sitemapPageLabel' => empty($this->getInput('localeSitemapPageLabel', helper::FILTER_STRING_SHORT))  ? 'Plan du site' : $this->getInput('localeSitemapPageLabel', helper::FILTER_STRING_SHORT),
					'metaDescription' => $this->getInput('localeMetaDescription', helper::FILTER_STRING_LONG, true),
					'title' => $this->getInput('localeTitle', helper::FILTER_STRING_SHORT, true),
					'cookies' => [
						// Les champs sont obligatoires si l'option consentement des cookies est active
						'mainLabel'	=> $this->getInput('localeCookiesZwiiText', helper::FILTER_STRING_LONG, $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN)),
						'titleLabel'	=> $this->getInput('localeCookiesTitleText', helper::FILTER_STRING_SHORT, $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN)),
						'linkLegalLabel'	=> $this->getInput('localeCookiesLinkMlText', helper::FILTER_STRING_SHORT, $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN)),
						'cookiesFooterText' =>  $this->getInput('localeCookiesFooterText', helper::FILTER_STRING_SHORT, $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN)),
						'buttonValidLabel' => $this->getInput('localeCookiesButtonText', helper::FILTER_STRING_SHORT, $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN))
					]
				]
			];

			// Sauvegarde hors méthodes si la langue n'est pas celle de l'UI
			if ($this->getUrl(2) === self::$i18nUI) {
				// Enregistrer les données par lecture directe du formulaire
				$this->setData(['locale', $data['locale']]);
			} else {
				// Sauver sur le disque
				file_put_contents(self::DATA_DIR . $this->getUrl(2) . '/locale.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), LOCK_EX);
			}

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}

		// Préparation de l'affichage du formulaire
		//-----------------------------------------

		// Récupération des locales de la langue sélectionnée

		// Vérifier la conformité de l'URL
		if (!array_key_exists($this->getUrl(2), self::$languages)) {
			// Bidouillage de l'URL, on sort
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'translate',
				'notification' => helper::translate('Erreur d\'URL'),
				'state' => false
			]);
		}
		//Lecture des données pour transmission au formulaire
		// La locale est-elle celle de la langue de l'UI ?
		if ($this->getUrl(2) === self::$i18nUI) {
			self::$locales[$this->getUrl(2)]['locale']  = $this->getData(['locale']);
		} else {
			// Lire les locales sans passer par les méthodes
			self::$locales[$this->getUrl(2)] = json_decode(file_get_contents(self::DATA_DIR . $this->getUrl(2) . '/locale.json'), true);
		}

		// Générer la liste des pages disponibles
		self::$pagesList = $this->getData(['page']);
		foreach (self::$pagesList as $page => $pageId) {
			if (
				$this->getData(['page', $page, 'block']) === 'bar' ||
				$this->getData(['page', $page, 'disable']) === true
			) {
				unset(self::$pagesList[$page]);
			}
		}

		self::$orphansList =  $this->getData(['page']);
		foreach (self::$orphansList as $page => $pageId) {
			if (
				$this->getData(['page', $page, 'block']) === 'bar' ||
				$this->getData(['page', $page, 'disable']) === true ||
				$this->getdata(['page', $page, 'position']) !== 0
			) {
				unset(self::$orphansList[$page]);
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Paramètres de la localisation') . '&nbsp;' . template::flag($this->getUrl(2), '20 %'),
			'view' => 'locale'
		]);
	}

	/**
	 * Edition de la langue de l'interface
	 */
	public function ui()
	{
		// Jeton incorrect ou URl avec le code langue incorrecte
		if (
			!array_key_exists($this->getUrl(2), self::$languages)
		) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'translate',
				'state' => false,
				'notification' => helper::translate('Action interdite')
			]);
		}
		// Soumission du formulaire
		if ($this->isPost()) {

			$data = json_decode(file_get_contents(self::I18N_DIR . $this->getUrl(2) . '.json'), true);
			foreach ($data as $key => $value) {
				$data[$key] = $this->getInput('translateString' . array_search($key, array_keys($data)), false, null);
			}

			file_put_contents(self::I18N_DIR . $this->getUrl(2) . '.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), LOCK_EX);

			// Valeurs en sortie
			$this->addOutput([
				'notification' => helper::translate('Modifications enregistrées'),
				'redirect' => helper::baseUrl() . 'translate',
				'state' => true
			]);
		}

		// Construction du formulaire

		// Chargement des dialogue de la langue cible
		if (!isset($data)) {
			$data = json_decode(file_get_contents(self::I18N_DIR . $this->getUrl(2) . '.json'), true);
		}

		// Ajout des champs absents selon la langue de référence
		$dataFr = json_decode(file_get_contents(self::I18N_DIR . 'fr_FR.json'), true);
		foreach ($dataFr as $key => $value) {
			if (!array_key_exists($key, $data)) {
				$data[$key] = '';
			}
		}
		file_put_contents(self::I18N_DIR . $this->getUrl(2) . '.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), LOCK_EX);

		//  Tableau des chaines à traduire dans la langue sélectionnée
		foreach ($data as $key => $value) {
			$dialogues[] = ['source' => $key, 'target' => $value];
		}

		// Pagination
		$pagination = helper::pagination($dialogues, $this->getUrl(), self::PAGINATION);

		// Liste des pages
		self::$pages = $pagination['pages'];


		// Articles en fonction de la pagination
		for ($i = $pagination['first']; $i < $pagination['last']; $i++) {
			self::$languagesUiInstalled[$i] =  $dialogues[$i];
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Éditer les dialogues') . '&nbsp;' . template::flag($this->getUrl(2), '20 %'),
			'view' => 'ui'
		]);
	}

	/***
	 * Effacer une langue de contenu
	 */
	public function delete()
	{
		// Jeton incorrect ou URl avec le code langue incorrecte
		$target = $this->getUrl(2);
		$lang = $this->getUrl(3);
		if (
			$this->getUrl(4) !== $_SESSION['csrf']
			|| !array_key_exists($lang, self::$languages)
		) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'translate',
				'state' => false,
				'notification' => helper::translate('Action interdite')
			]);
		}
		switch ($target) {
			case 'locale':
				// Effacement d'une site dans une langue
				if (is_dir(self::DATA_DIR . $lang) === true) {
					$success = $this->removeDir(self::DATA_DIR . $lang);
				}
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'translate',
					'notification' => $success ? helper::translate('Traduction supprimée') :  helper::translate('Erreur inconnue'),
					'state' => $success
				]);
				break;

			case 'ui' :
				// Effacement d'une langue de l'interface
				if (file_exists(self::I18N_DIR . $lang . '.json') === true) {
					$success =unlink(self::I18N_DIR . $lang . '.json');
				}
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'translate',
					'notification' => $success ? helper::translate('Traduction supprimée') :  helper::translate('Erreur inconnue'),
					'state' => $success
				]);
				break;
			default:
				# Do nothing
				break;
		}
	}

	/*
	 * Traitement du changement de langue
	 * Fonction utilisée par le noyau
	 */
	public function content()
	{
		// Activation du drapeau
		$lang = $this->getUrl(2);
		// Changement ?
		if ($this->getInput('ZWII_CONTENT') !== $lang) {
			// Nettoyer le cookie
			helper::deleteCookie('ZWII_CONTENT');
			// Stocker le choix
			setcookie('ZWII_CONTENT', $lang, time() + 3600, helper::baseUrl(false, false), '', helper::isHttps(), true);
		}

		// Valeurs en sortie
		$this->addOutput([
			'redirect' 	=> 	helper::baseUrl() . $this->getData(['locale', $this->getUrl(2), 'homePageId'])
		]);
	}
}
