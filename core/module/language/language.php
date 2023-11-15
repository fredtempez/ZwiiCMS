<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class language extends common
{

	// URL langues de l'UI en ligne
	const ZWII_UI_URL = 'https://forge.chapril.org/ZwiiCMS-Team/zwiicms-translations/raw/branch/master/v13/';

	public static $actions = [
		'index' => self::GROUP_ADMIN,
		'copy' => self::GROUP_ADMIN,
		'add' => self::GROUP_ADMIN,
		// Ajouter une langue de contenu
		'edit' => self::GROUP_ADMIN,
		// Éditer une langue de l'UI
		'locale' => self::GROUP_ADMIN,
		// Éditer une langue de contenu
		'delete' => self::GROUP_ADMIN,
		// Effacer une langue de contenu ou de l'interface
		'content' => self::GROUP_VISITOR,
		'update' => self::GROUP_ADMIN,
		'default' => self::GROUP_ADMIN
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
	public static $languagesStore = [];
	public static $dialogues = [];

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
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			$lang = $this->getUrl(2);
			// Action interdite ou URl avec le code langue incorrecte
			if (
				array_key_exists($lang, self::$languages) === false
			) {
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'language',
					'state' => false,
					'notification' => helper::translate('Action interdite')
				]);
			}

			// Télécharger le descripteur en ligne
			$languageData = json_decode(helper::getUrlContents(self::ZWII_UI_URL . $lang . '.json'), true);
			$descripteur = json_decode(helper::getUrlContents(self::ZWII_UI_URL . 'language.json'), true);
			$success = false;
			if (
				is_array($languageData) &&
				is_array($descripteur['language'][$lang])
			) {
				if ($this->setData(['language', $lang, $descripteur['language'][$lang]])) {
					$success = file_put_contents(self::I18N_DIR . $lang . '.json', json_encode($languageData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
					$success = is_int($success) ? true : false;
				}
			}

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'language',
				'notification' => $success ? helper::translate('Copie terminée avec succès') : 'Copie terminée avec des erreurs',
				'state' => $success
			]);
		}
	}

	/**
	 * Configuration avancée des langues
	 */
	public function copy()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Initialisation
			$success = false;
			$copyFrom = $this->getInput('translateFormCopySource');
			$toCreate = $this->getInput('translateFormCopyTarget');
			if ($copyFrom !== $toCreate) {
				// Création du dossier
				if (is_dir(self::DATA_DIR . $toCreate) === false) { // Si le dossier est déjà créé
					$success = mkdir(self::DATA_DIR . $toCreate, 0755);
					$success = mkdir(self::DATA_DIR . $toCreate . '/content', 0755);
				} else {
					$success = true;
				}
				// Copier les données par défaut
				$success = (copy(self::DATA_DIR . $copyFrom . '/locale.json', self::DATA_DIR . $toCreate . '/locale.json') === true && $success === true) ? true : false;
				$success = (copy(self::DATA_DIR . $copyFrom . '/module.json', self::DATA_DIR . $toCreate . '/module.json') === true && $success === true) ? true : false;
				$success = (copy(self::DATA_DIR . $copyFrom . '/page.json', self::DATA_DIR . $toCreate . '/page.json') === true && $success === true) ? true : false;
				$success = ($this->copyDir(self::DATA_DIR . $copyFrom . '/content', self::DATA_DIR . $toCreate . '/content') === true && $success === true) ? true : false;
				// Enregistrer la langue
				if ($success) {
					$notification = sprintf(helper::translate('Données %s copiées vers %s'), self::$languages[$copyFrom], self::$languages[$toCreate]);
				} else {
					$notification = helper::translate('Erreur de copie, vérifiez les permissions');
				}
			} else {
				$success = false;
				$notification = helper::translate('Les langues sélectionnées sont identiques');
			}
			// Valeurs en sortie
			$this->addOutput([
				'notification' => $notification,
				'title' => 'Utilitaire de copie',
				'view' => 'index',
				'state' => $success
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

		// --------------------------------------------------------------------------------------------------
		// Langues du site
		// --------------------------------------------------------------------------------------------------

		foreach (self::$languages as $key => $value) {
			// tableau des langues installées
			if (is_dir(self::DATA_DIR . $key)) {
				if (
					file_exists(self::DATA_DIR . $key . '/page.json') &&
					file_exists(self::DATA_DIR . $key . '/module.json') &&
					file_exists(self::DATA_DIR . $key . '/locale.json')
				) {
					if (file_exists(self::DATA_DIR . $key . '/.default')) {
						$messageLocale = helper::translate('Langue par défaut');
					} elseif (isset($_SESSION['ZWII_CONTENT']) && $_SESSION['ZWII_CONTENT'] === $key) {
						$messageLocale = helper::translate('Langue du site sélectionnée');
					} else {
						$messageLocale = '';
					}
					self::$languagesInstalled[] = [
						template::flag($key, '20 %') . '&nbsp;' . $value . ' (' . $key . ')',
						$messageLocale,
						template::button('translateContentLanguageLocaleEdit' . $key, [
							'class' => file_exists(self::DATA_DIR . $key . '/locale.json') ? '' : ' disabled',
							'href' => helper::baseUrl() . $this->getUrl(0) . '/locale/' . $key,
							'value' => template::ico('pencil'),
							'help' => 'Éditer'
						]),
						template::button('translateContentLanguageLocaleDelete' . $key, [
							'class' => 'translateDelete buttonRed' . ($messageLocale ? ' disabled' : ''),
							'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/locale/' . $key,
							'value' => template::ico('trash'),
							'help' => 'Supprimer',
						])
					];
				}
			}

		}
		// Activation du bouton de copie
		self::$siteCopy = count(self::$languagesInstalled) > 1 ? false : true;

		// --------------------------------------------------------------------------------------------------
		// Langues de l'UI
		// --------------------------------------------------------------------------------------------------

		// Langues attachées à des utilisateurs non effaçables
		$usersUI = [];
		$users = $this->getData(['user']);
		foreach ($users as $key => $value) {
			array_push($usersUI, $this->getData(['user', $key, 'language']));
		}

		// Langues installées
		$installedUI = $this->getData(['language']);

		if (array_key_exists('language', $installedUI)) {
			$installedUI = $installedUI['language'];
		}

		// Langues disponibles en ligne
		$storeUI = json_decode(helper::getUrlContents(self::ZWII_UI_URL . 'language.json'), true);
		$storeUI = $storeUI ? $storeUI['language'] : null;

		// Construction du tableau à partir des langues disponibles dans le store
		foreach ($installedUI as $file => $value) {
			// La langue est-elle référencée ?
			if (array_key_exists(basename($file, '.json'), $installedUI)) {
				// La langue est déjà installée
				self::$languagesUiInstalled[$file] = [
					template::flag($file, '20 %') . '&nbsp;' . self::$languages[$file],
					$value['version'],
					helper::dateUTF8('%d/%m/%Y', $value['date'], self::$siteContent),
					//self::$i18nUI === $file ? helper::translate('Interface') : '',
					'',
					/*
					template::button('translateContentLanguageUIEdit' . $file, [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $file,
						'value' => template::ico('pencil'),
						'help' => 'Éditer',
						'disabled' => 'fr_FR' === $file
					]),
					*/

					template::button('translateContentLanguageUIDownload' . $file, [
						'class' =>  isset($storeUI[$file]['version']) && version_compare($installedUI[$file]['version'], $storeUI[$file]['version']) < 0 ? 'buttonGreen' : '',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/update/' . $file,
						'value' => template::ico('update'),
						'help' => 'Mettre à jour',
					]),
					template::button('translateContentLanguageUIDelete' . $file, [
						'class' => 'translateDelete buttonRed' . (in_array($file, $usersUI) ? ' disabled' : ''),
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/ui/' . $file,
						'value' => template::ico('trash'),
						'help' => 'Supprimer',
					]),
				];
			}
		}
		// Construction du tableau à partir des langues disponibles dans le store
		if ($storeUI) {
			foreach ($storeUI as $file => $value) {

				// La langue est-elle installée ?
				if (array_key_exists($file, $installedUI) === false) {
					self::$languagesStore[$file] = [
						template::flag($file, '20 %') . '&nbsp;' . self::$languages[$file],
						$value['version'],
						helper::dateUTF8('%d/%m/%Y', $value['date'], self::$siteContent),
						'',
						template::button('translateContentLanguageUIDownload' . $file, [
							'class' => 'buttonGreen',
							'href' => helper::baseUrl() . $this->getUrl(0) . '/update/' . $file,
							'value' => template::ico('shopping-basket'),
							'help' => 'Installer',
						])
					];
				}
			}
		}


		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Langues'),
			'view' => 'index'
		]);
	}


	/***
	 * Ajouter une langue de contenu
	 */

	public function add()
	{

		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {

			$lang = $this->getInput('translateAddContent');

			// Constructeur pour cette langue
			$this->jsonDB($lang);

			// Création du contenu
			$this->initData('page', $lang);
			$this->initData('module', $lang);
			$this->initData('locale', $lang);


			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'language',
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
		// Action interdite ou URl avec le code langue incorrecte
		$lang = $this->getUrl(2);
		if (
			array_key_exists($lang, self::$languages) === false
		) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'language',
				'state' => false,
				'notification' => helper::translate('Action interdite')
			]);
		}

		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {

			// Sauvegarder les locales
			$data = [
				'locale' => [
					'homePageId' => $this->getInput('localeHomePageId', helper::FILTER_ID, true),
					'page404' => $this->getInput('localePage404'),
					'page403' => $this->getInput('localePage403'),
					'page302' => $this->getInput('localePage302'),
					'legalPageId' => $this->getInput('localeLegalPageId'),
					'searchPageId' => $this->getInput('localeSearchPageId'),
					'poweredPageLabel' => empty($this->getInput('localePoweredPageLabel', helper::FILTER_STRING_SHORT)) ? 'Motorisé par' : $this->getInput('localePoweredPageLabel', helper::FILTER_STRING_SHORT),
					'searchPageLabel' => empty($this->getInput('localeSearchPageLabel', helper::FILTER_STRING_SHORT)) ? 'Rechercher' : $this->getInput('localeSearchPageLabel', helper::FILTER_STRING_SHORT),
					'legalPageLabel' => empty($this->getInput('localeLegalPageLabel', helper::FILTER_STRING_SHORT)) ? 'Mentions légales' : $this->getInput('localeLegalPageLabel', helper::FILTER_STRING_SHORT),
					'sitemapPageLabel' => empty($this->getInput('localeSitemapPageLabel', helper::FILTER_STRING_SHORT)) ? 'Plan du site' : $this->getInput('localeSitemapPageLabel', helper::FILTER_STRING_SHORT),
					'metaDescription' => $this->getInput('localeMetaDescription', helper::FILTER_STRING_LONG, true),
					'title' => $this->getInput('localeTitle', helper::FILTER_STRING_SHORT, true),
					'cookies' => [
						// Les champs sont obligatoires si l'option consentement des cookies est active
						'mainLabel' => $this->getInput('localeCookiesZwiiText', helper::FILTER_STRING_LONG, $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN)),
						'titleLabel' => $this->getInput('localeCookiesTitleText', helper::FILTER_STRING_SHORT, $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN)),
						'linkLegalLabel' => $this->getInput('localeCookiesLinkMlText', helper::FILTER_STRING_SHORT, $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN)),
						'cookiesFooterText' => $this->getInput('localeCookiesFooterText', helper::FILTER_STRING_SHORT, $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN)),
						'buttonValidLabel' => $this->getInput('localeCookiesButtonText', helper::FILTER_STRING_SHORT, $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN))
					]
				]
			];

			// Sauvegarde hors méthodes si la langue n'est pas celle de l'UI
			if ($lang === self::$siteContent) {
				// Enregistrer les données par lecture directe du formulaire
				$this->setData(['locale', $data['locale']]);
			} else {
				// Sauver sur le disque
				file_put_contents(self::DATA_DIR . $lang . '/locale.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), LOCK_EX);
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

		// La locale est-elle celle de la langue de l'UI ?
		if ($lang === self::$siteContent) {
			self::$locales[$lang]['locale'] = $this->getData(['locale']);
		} else {
			// Lire les locales sans passer par les méthodes
			self::$locales[$lang] = json_decode(file_get_contents(self::DATA_DIR . $lang . '/locale.json'), true);
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

		self::$orphansList = $this->getData(['page']);
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
			'title' => helper::translate('Paramètres de la localisation') . '&nbsp;' . template::flag($lang, '20 %'),
			'view' => 'locale'
		]);
	}

	/**
	 * Edition de la langue de l'interface
	 */
	public function edit()
	{
		$lang = $this->getUrl(2);
		// Action interdite ou URl avec le code langue incorrecte
		if (
			array_key_exists($lang, self::$languages) === false
		) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'language',
				'state' => false,
				'notification' => helper::translate('Action interdite')
			]);
		}
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {

			// Sauvegarder les champs de la langue
			$data = json_decode(file_get_contents(self::I18N_DIR . $lang . '.json'), true);
			foreach ($data as $key => $value) {
				$target = $this->getInput('translateString' . array_search($key, array_keys($data)));
				if (empty($target) === false) {
					$data[$key] = $target;
				}
			}
			file_put_contents(self::I18N_DIR . $lang . '.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), LOCK_EX);

			// Mettre à jour le descripteur
			$this->setData([
				'language',
				$lang,
				[
					'version' => $this->getInput('translateEditVersion'),
					'date' => $this->getInput('translateEditDate', helper::FILTER_DATETIME),
				]
			]);

			// Valeurs en sortie
			$this->addOutput([
				'notification' => helper::translate('Modifications enregistrées'),
				'redirect' => helper::baseUrl() . 'language',
				'state' => true,
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
		file_put_contents(self::I18N_DIR . $lang . '.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), LOCK_EX);

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
			self::$dialogues[$i] = $dialogues[$i];
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Éditer les dialogues') . '&nbsp;' . template::flag($lang, '20 %'),
			'view' => 'edit',
			'vendor' => [
				'flatpickr',
			],
		]);
	}

	/***
	 * Effacer une langue de contenu
	 */
	public function delete()
	{
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Action interdite ou URl avec le code langue incorrecte
			$target = $this->getUrl(2);
			$lang = $this->getUrl(3);
			if (
				array_key_exists($lang, self::$languages) === false
			) {
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'language',
					'state' => false,
					'notification' => helper::translate('Action interdite')
				]);
			}
			switch ($target) {
				case 'locale':
					$success = false;
					// Effacement d'une site dans une langue
					if (is_dir(self::DATA_DIR . $lang) === true) {
						$success = $this->deleteDir(self::DATA_DIR . $lang);
					}
					// Valeurs en sortie
					$this->addOutput([
						'redirect' => helper::baseUrl() . 'language',
						'notification' => $success ? helper::translate('Traduction supprimée') : helper::translate('Erreur inconnue'),
						'state' => $success
					]);
					break;

				case 'ui':
					$success = false;
					// Effacement d'une langue de l'interface
					if (file_exists(self::I18N_DIR . $lang . '.json') === true) {
						$this->deleteData(['language', $lang]);
						$success = unlink(self::I18N_DIR . $lang . '.json');
					}
					// Valeurs en sortie
					$this->addOutput([
						'redirect' => helper::baseUrl() . 'language',
						'notification' => $success ? helper::translate('Traduction supprimée') : helper::translate('Erreur inconnue'),
						'state' => $success
					]);
					break;
				default:
					# Do nothing
					break;
			}
		}
	}

	/*
	 * Modifie la langue du site par défaut
	 *
	 */
	public function default()
	{
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Action interdite ou URl avec le code langue incorrecte
			$lang = $this->getUrl(2);
			if (
				array_key_exists($lang, self::$languages) === false
			) {
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . 'language',
					'state' => false,
					'notification' => helper::translate('Action interdite')
				]);
			}

			foreach (self::$languages as $key => $value) {
				if (file_exists(self::DATA_DIR . $key . '/.default')) {
					unlink(self::DATA_DIR . $key . '/.default');
					touch(self::DATA_DIR . $lang . '/.default');
					break;
				}
			}
			// Valeurs en sortie
			$this->addOutput([
				'notification' => helper::translate('Modifications enregistrées'),
				'redirect' => helper::baseUrl() . 'language',
				'state' => true,
			]);
		}
	}

	/*
	 * Traitement du changement de langue
	 * Fonction utilisée par le noyau
	 */
	public function content()
	{
		// Langue sélectionnée
		$lang = $this->getUrl(2);
		/**
		 * Changement de la langue si
		 * différe de la langue active
		 * déjà initialisée
		 * fait partie des langues installées
		 */

		if (
			is_dir(self::DATA_DIR . $lang) &&
			array_key_exists($lang, self::$languages) === true
		) {

			// Stocker la sélection
			$_SESSION['ZWII_CONTENT'] = $lang;
		}

		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl()
		]);
	}

}