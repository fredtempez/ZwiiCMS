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
		'edit' => self::GROUP_ADMIN, 	// Editer une langue de contenu
		'delete' => self::GROUP_ADMIN, 	// Effacer une langue de contenu
		'content' => self::GROUP_VISITOR,
	];

	// Language contents
	public static $translateOptions = [];
	// Page pour la configuration dans la langue
	public static $pagesList = [];
	public static $orphansList = [];
	// Liste des langues installées
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
					$this->setData(['config', 'i18n', $toCreate, 'site']);
					$notification = 'Données ' . self::$languages[$copyFrom] . ' copiées vers ' .  self::$languages[$toCreate];
				} else {
					$notification = "Quelque chose n\'a pas fonctionné, vérifiez les permissions.";
				}
			} else {
				$success = false;
				$notification = 'Les langues doivent être différentes.';
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
			'title' => 'Copie de contenus localisés',
			'view' => 'copy'
		]);
	}

	/**
	 * Configuration
	 */
	public function index()
	{

		// Soumission du formulaire
		if ($this->isPost()) {

			// Sauvegarder les langues de contenu
			$this->setData(['config', 'i18n', 'interface', $this->getInput('translateUI')]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		}

		// Préparation du formulaire
		// -------------------------

		// Onglet des langues de contenu
		foreach (self::$languages as $key => $value) {
			// tableau des langues installées
			if (is_dir(self::DATA_DIR . $key)) {
				self::$languagesInstalled[] = [
					template::flag($key, '20 %'),
					$value . ' (' . $key . ')',
					self::$i18nUI === $key ? 'Interface' : '',
					'',
					template::button('translateContentLanguageEdit' . $key, [
						'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $key . '/' . $_SESSION['csrf'],
						'value' => template::ico('flag'),
						'help' => 'Editer'
					]),
					template::button('translateContentLanguageDelete' . $key, [
						'class' => 'translateDelete buttonRed' . (self::$i18nUI === $key ? ' disabled' : ''),
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $key . '/' . $_SESSION['csrf'],
						'value' => template::ico('trash'),
						'help' => 'Supprimer'
					])
				];
			}
		}
		// Activation du bouton de copie
		self::$siteCopy = count(self::$languagesInstalled) > 1 ? false : true;

		// Langues de l'UI disponibles
		if (is_dir(self::I18N_DIR)) {
			$dir = getcwd();
			chdir(self::I18N_DIR);
			$files = glob('*.json');
			// Ajouter une clé au tableau avec le code de langue
			foreach ($files as $file) {
				// La langue est-elle référencée ?
				if (array_key_exists(basename($file, '.json'), self::$languages)) {
					self::$i18nFiles[basename($file, '.json')] = self::$languages[basename($file, '.json')];
				}
			}
			chdir($dir);
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Langues',
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

			// Tableau avec les données vierges
			require_once('core/module/install/ressource/defaultdata.php');

			// Créer la structure
			foreach (['page', 'module', 'locale'] as $key) {

				// Sus-dossier localisé
				if (!file_exists(self::DATA_DIR .  $lang)) {
					mkdir(self::DATA_DIR . $lang, 0755);
				}

				// Initialiser la classe
				$db = new \Prowebcraft\JsonDb([
					'name' => $key . '.json',
					'dir' => self::DATA_DIR . $lang,
					'backup' => file_exists('site/data/.backup')
				]);;

				// Capturer et sauver
				$db->set($key, init::$defaultData[$key]);
				$db->save;
			}


			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'translate',
				'notification' => 'Modifications enregistrées',
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
			'title' => 'Ajouter un contenu localisé',
			'view' => 'add'
		]);
	}

	public function edit()
	{

		// Jeton incorrect ou URl avec le code langue incorrecte
		if (
			$this->getUrl(3) !== $_SESSION['csrf']
			|| !array_key_exists($this->getUrl(2), self::$languages)
		) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'translate',
				'state' => false,
				'notification' => 'Action non autorisée'
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
				file_put_contents(self::DATA_DIR . $this->getUrl(2) . '/locale.json', json_encode($data, JSON_UNESCAPED_UNICODE), LOCK_EX);
			}

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => 'Modifications enregistrées',
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
				'notification' => 'URL incorrecte',
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
			'title' => 'Paramètres de la localisation&nbsp;' . template::flag($this->getUrl(2), '20 %'),
			'view' => 'edit'
		]);
	}

	/***
	 * Effacer une langue de contenu
	 */
	public function delete()
	{
		// Jeton incorrect ou URl avec le code langue incorrecte
		if (
			$this->getUrl(3) !== $_SESSION['csrf']
			|| !array_key_exists($this->getUrl(2), self::$languages)
		) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'translate',
				'state' => false,
				'notification' => 'Action non autorisée'
			]);
		}

		// Effacement d'une langue installée
		if (is_dir(self::DATA_DIR . $this->getUrl(2)) === true) {
			$success = $this->removeDir(self::DATA_DIR . $this->getUrl(2));
		}
		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl() . 'translate',
			'notification' => $success ? 'La traduction a été supprimée' : 'Une erreur s\'est produite',
			'state' => $success
		]);
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
