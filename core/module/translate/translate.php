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
	 * Met à jour les traduction du site depuis le store
	 */
	public function update()
	{
		// Jeton incorrect ou URl avec le code langue incorrecte
		if (
			$this->getUrl(3) !== $_SESSION['csrf']
		) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'translate',
				'state' => false,
				'notification' => helper::translate('Action interdite')
			]);
		}

		// Upload et sauver le fichier de langue
		$response = helper::getUrlContents(common::ZWII_UI_URL . $this->getUrl(2) . '.json');
		if ($response !== false) {
			$response = file_put_contents(self::I18N_DIR . $this->getUrl(2) . '.json', $response);
			// Régénère le descripteur
			unlink(self::I18N_DIR . 'enum.json');
			$this->getUiLanguages();
		}


		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl() . 'translate',
			'notification' => is_int($response) ?  helper::translate('Copie terminée avec succès') : 'Copie terminée avec des erreurs',
			'state' => is_int($response)
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

		// --------------------------------------------------------------------------------------------------
		// Onglet des langues de l'interface

		// Langues attachées à des utilisateurs non effaçables
		$usersUI = [];
		$users = $this->getData(['user']);
		foreach ($users as $key => $value) {
			array_push($usersUI, $this->getData(['user', $key, 'language']));
		}

		// Langues installées
		$installedUI = $this->getUiLanguages();

		// Récupérer la liste des langues disponibles en ligne
		$storeUI = json_decode(helper::getUrlContents(common::ZWII_UI_URL . '/enum.json'), true);

		// Construction du tableau à partir des langues disponibles dans le store
		foreach ($storeUI as $file => $value) {
			// La langue est-elle référencée ?
			if (array_key_exists(basename($file, '.json'), $installedUI)) {
				// La langue est déjà installée
				self::$languagesUiInstalled[$file] =  [
					template::flag($file, '20 %'),
					self::$languages[$file],
					self::$i18nUI === $file ? helper::translate('Interface') : '',
					template::button('translateContentLanguageUIEdit' . $file, [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/ui/' . $file,
						'value' => template::ico('pencil'),
						'help' => 'Éditer',
						'disabled' => 'fr_FR' === $file
					]),
					template::button('translateContentLanguageUIDownload' . $file, [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/download/' . $file . '/' . $_SESSION['csrf'],
						'value' => template::ico('download'),
						'help' => 'Télécharger',
					]),
					template::button('translateContentLanguageUIDownload' . $file, [
						'class' => version_compare($installedUI[$file]['version'], $storeUI[$file]['version']) < 0 ? 'buttonGreen' : '',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/update/' . $file . '/' . $_SESSION['csrf'],
						'value' => template::ico('update'),
						'help' => 'Mettre à jour',
					]),
					template::button('translateContentLanguageUIDelete' . $file, [
						'class' => 'buttonRed' . (in_array($file, $usersUI) ? ' disabled' : ''),
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/ui/' . $file . '/' . $_SESSION['csrf'],
						'value' => template::ico('trash'),
						'help' => 'Supprimer',
					]),
				];
			} else {
				// La langue n'est pas installée
				self::$languagesUiInstalled[$file] =  [
					template::flag($file, '20 %'),
					self::$languages[$file],
					self::$i18nUI === $file ? helper::translate('Interface') : '',
					'',
					'',
					template::button('translateContentLanguageUIDownload' . $file, [
						'class' => 'buttonGreen',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/update/' . $file . '/' . $_SESSION['csrf'],
						'value' => template::ico('shopping-basket'),
						'help' => 'Installer',
					]),
					''
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

			case 'ui':
				// Effacement d'une langue de l'interface
				if (file_exists(self::I18N_DIR . $lang . '.json') === true) {
					$success = unlink(self::I18N_DIR . $lang . '.json');
				}
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'translate',
					'notification' => $success ? helper::translate('Traduction supprimée') :  helper::translate('Erreur inconnue'),
					'state' => $success
				]);
				unlink(self::I18N_DIR . 'enum.json');
				$this->getUiLanguages();
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

	/**
	 * Génère un fichier d'énumération des langues de l'UI
	 */
	private function getUiLanguages()
	{
		$enums = json_decode(helper::getUrlContents(self::I18N_DIR . '/enum.json'), true);

		// Générer une énumération absente
		if (is_array($enums) === false) {
			if (is_dir(self::I18N_DIR) === false) {
				mkdir(self::I18N_DIR);
			}
			$dir = getcwd();
			chdir(self::I18N_DIR);
			$files = glob('*.json');
			chdir($dir);
			$enums = [];
			foreach ($files as $file => $value) {
				if (basename($value, '.json') === 'enum.json') {
					continue;
				}
				$enums[basename($value, '.json')] = [
					'version' => 1.0,
					'date' => 1672052400
				];
			}
			file_put_contents(self::I18N_DIR . 'enum.json', json_encode($enums));
		}

		return ($enums);
	}
}
