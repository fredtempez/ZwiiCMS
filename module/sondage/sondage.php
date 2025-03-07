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
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.com/
 */


class sondage extends common
{

	const VERSION = '1.4';
	const REALNAME = 'Sondage';
	const DELETE = true;
	const UPDATE = '0.0';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	public static $actions = [
		'config' => self::ROLE_MODERATOR,
		'data' => self::ROLE_MODERATOR,
		'result' => self::ROLE_VISITOR,
		'delete' => self::ROLE_MODERATOR,
		'deleteall' => self::ROLE_MODERATOR,
		'index' => self::ROLE_VISITOR,
		'export2csv' => self::ROLE_MODERATOR,
		'output2csv' => self::ROLE_MODERATOR
	];

	public static $data = [];

	public static $pages = [];

	public static $pagination;

	const TYPE_MAIL = 'mail';
	const TYPE_SELECT = 'select';
	const TYPE_TEXT = 'text';
	const TYPE_TEXTAREA = 'textarea';
	const TYPE_DATETIME = "date";
	const TYPE_CHECKBOX = "checkbox";


	public static $types = [
		self::TYPE_TEXT => 'Champ texte',
		self::TYPE_TEXTAREA => 'Grand champ texte',
		self::TYPE_MAIL => 'Champ mail',
		self::TYPE_SELECT => 'Sélection',
		self::TYPE_DATETIME => 'Date',
		self::TYPE_CHECKBOX => 'Case à cocher'
	];
	// Nombre d'objets par page
	public static $itemsList = [
		6 => '6 réponses',
		9 => '9 réponses',
		18 => '18 réponses'
	];

	public static $listUsers = [
	];

	/**
	 * Configuration
	 */
	public function config()
	{
		// Liste des utilisateurs
		$userIdsFirstnames = helper::arraycollumn($this->getData(['user']), 'firstname');
		ksort($userIdsFirstnames);
		self::$listUsers[] = '';
		foreach ($userIdsFirstnames as $userId => $userFirstname) {
			self::$listUsers[] = $userId;
		}
		// Soumission du formulaire
		if ($this->isPost()) {
			// Configuration
			// Option sélectionnée sans page choisie
			$this->setData([
				'module',
				$this->getUrl(0),
				'config',
				[
					'button' => $this->getInput('formConfigButton'),
					'captcha' => $this->getInput('formConfigCapcha', helper::FILTER_BOOLEAN),
					'role' => $this->getInput('formConfigGroup', helper::FILTER_INT),
					'user' => self::$listUsers[$this->getInput('formConfigUser', helper::FILTER_INT)],
					'mail' => $this->getInput('formConfigMail'),
					'pageId' => $this->getInput('formConfigPageIdToggle', helper::FILTER_BOOLEAN) === true ? $this->getInput('formConfigPageId', helper::FILTER_ID) : '',
					'subject' => $this->getInput('formConfigSubject'),
					'itemsperPage' => $this->getInput('formConfigItemsperPage', helper::FILTER_INT)
				]
			]);
			// Génération des données vides
			$this->setData(['module', $this->getUrl(0), 'data', []]);
			// Génération des champs
			$inputs = [];
			if (is_array($this->getInput('formConfigPosition', null))) {
				foreach ($this->getInput('formConfigPosition', null) as $index => $position) {
					$inputs[] = [
						'name' => $this->getInput('formConfigName[' . $index . ']'),
						'position' => helper::filter($position, helper::FILTER_INT),
						'required' => $this->getInput('formConfigRequired[' . $index . ']', helper::FILTER_BOOLEAN),
						'type' => $this->getInput('formConfigType[' . $index . ']'),
						'values' => $this->getInput('formConfigValues[' . $index . ']')
					];
				}
			}

			$this->setData(['module', $this->getUrl(0), 'input', $inputs]);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'Modifications enregistrées',
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'state' => true
			]);
		}
		// Liste des pages
		foreach ($this->getHierarchy(null, false) as $parentPageId => $childrenPageIds) {
			self::$pages[$parentPageId] = $this->getData(['page', $parentPageId, 'title']);
			foreach ($childrenPageIds as $childKey) {
				self::$pages[$childKey] = '&nbsp;&nbsp;&nbsp;&nbsp;' . $this->getData(['page', $childKey, 'title']);
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Configuration du module',
			'vendor' => [
				'html-sortable',
				'flatpickr'
			],
			'view' => 'config'
		]);
	}

	/**
	 * Données enregistrées
	 */
	public function data()
	{
		$data = $this->getData(['module', $this->getUrl(0), 'data']);
		if (is_array($data)) {
			// Pagination
			$pagination = helper::pagination($data, $this->getUrl(), $this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage']));
			// Liste des pages
			self::$pagination = $pagination['pages'];
			// Inverse l'ordre du tableau
			$dataIds = array_reverse(array_keys($data));
			$data = array_reverse($data);
			// Données en fonction de la pagination
			for ($i = $pagination['first']; $i < $pagination['last']; $i++) {
				$content = '';
				foreach ($data[$i] as $input => $value) {
					$content .= $input . ' : ' . $value . '<br>';
				}
				self::$data[] = [
					$content,
					template::button('formDataDelete' . $dataIds[$i], [
						'class' => 'formDataDelete buttonRed',
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $dataIds[$i] . '/' . $_SESSION['csrf'],
						'value' => template::ico('trash')
					])
				];
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Données enregistrées',
			'view' => 'data'
		]);
	}

	/**
	 * Réponses enregistrées
	 */
	public function result()
	{
		$data = $this->getData(['module', $this->getUrl(0), 'data']);
		if ($data) {
			// Pagination
			$pagination = helper::pagination($data, $this->getUrl(), $this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage']));
			// Liste des pages
			self::$pagination = $pagination['pages'];
			// Inverse l'ordre du tableau
			$dataIds = array_reverse(array_keys($data));
			$data = array_reverse($data);
			// Données en fonction de la pagination
			for ($i = $pagination['first']; $i < $pagination['last']; $i++) {
				$content = '';
				foreach ($data[$i] as $input => $value) {
					$content .= $input . ' : ' . $value . '<br>';
				}
				self::$data[] = [
					$content
				];
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Réponses',
			'view' => 'result'
		]);
	}

	/**
	 * Export CSV
	 * @author Frédéric Tempez <frederic.tempez@outlook.com>
	 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
	 */
	public function export2csv()
	{
		// Jeton incorrect
		if ($this->getUrl(2) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
				'notification' => 'Action interdite'
			]);
		} else {
			$data = $this->getData(['module', $this->getUrl(0), 'data']);
			if ($data !== []) {
				$csvfilename = 'data-' . date('dmY') . '-' . date('hm') . '-' . rand(10, 99) . '.csv';
				if (!file_exists(self::FILE_DIR . 'source/data')) {
					mkdir(self::FILE_DIR . 'source/data', 0755);
				}
				$fp = fopen(self::FILE_DIR . 'source/data/' . $csvfilename, 'w');
				fputcsv($fp, array_keys($data[1]), ';', '"');
				foreach ($data as $fields) {
					fputcsv($fp, $fields, ';', '"');
				}
				fclose($fp);
				// Valeurs en sortie
				$this->addOutput([
					'notification' => 'Export CSV effectué dans le gestionnaire de fichiers<br />sous le nom ' . $csvfilename,
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
					'state' => true
				]);
			} else {
				$this->addOutput([
					'notification' => 'Aucune donnée à exporter',
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data'
				]);
			}
		}
	}


	/**
	 * Suppression
	 */
	public function deleteall()
	{
		// Jeton incorrect
		if ($this->getUrl(2) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
				'notification' => 'Action interdite'
			]);
		} else {
			$data = ($this->getData(['module', $this->getUrl(0), 'data']));
			if (count($data) > 0) {
				// Suppression multiple
				for ($i = 1; $i <= count($data); $i++) {
					echo $this->deleteData(['module', $this->getUrl(0), 'data', $i]);
				}
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
					'notification' => 'Données supprimées',
					'state' => true
				]);
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
					'notification' => 'Aucune donnée à supprimer'
				]);
			}
		}
	}


	/**
	 * Suppression
	 */
	public function delete()
	{
		// Jeton incorrect
		if ($this->getUrl(3) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
				'notification' => 'Action interdite'
			]);
		} else {
			// La donnée n'existe pas
			if ($this->getData(['module', $this->getUrl(0), 'data', $this->getUrl(2)]) === null) {
				// Valeurs en sortie
				$this->addOutput([
					'access' => false
				]);
			}
			// Suppression
			else {
				$this->deleteData(['module', $this->getUrl(0), 'data', $this->getUrl(2)]);
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
					'notification' => 'Donnée supprimée',
					'state' => true
				]);
			}
		}
	}




	/**
	 * Accueil
	 */
	public function index()
	{
		// Soumission du formulaire
		if ($this->isPost()) {
			// Check la captcha
			if (
				$this->getData(['module', $this->getUrl(0), 'config', 'captcha'])
				and $this->getInput('formCapcha', helper::FILTER_INT) !== $this->getInput('formCapchaFirstNumber', helper::FILTER_INT) + $this->getInput('formCapchaSecondNumber', helper::FILTER_INT)
			) {
				self::$inputNotices['formCapcha'] = 'Incorrect';

			}
			// Préparation le contenu du mail
			$data = [];
			$content = '';
			foreach ($this->getData(['module', $this->getUrl(0), 'input']) as $index => $input) {
				// Filtre la valeur
				switch ($input['type']) {
					case self::TYPE_MAIL:
						$filter = helper::FILTER_MAIL;
						break;
					case self::TYPE_TEXTAREA:
						$filter = helper::FILTER_STRING_LONG;
						break;
					case self::TYPE_DATETIME:
						$filter = helper::FILTER_STRING_SHORT; // Mettre TYPE_DATETIME pour récupérer un TIMESTAMP
						break;
					case self::TYPE_CHECKBOX:
						$filter = helper::FILTER_BOOLEAN;
						break;
					default:
						$filter = helper::FILTER_STRING_SHORT;
				}
				$value = $this->getInput('formInput[' . $index . ']', $filter, $input['required']);
				// Préparation des données pour la création dans la base
				$data[$this->getData(['module', $this->getUrl(0), 'input', $index, 'name'])] = $value;
				// Préparation des données pour le mail
				$content .= '<strong>' . $this->getData(['module', $this->getUrl(0), 'input', $index, 'name']) . ' :</strong> ' . $value . '<br>';
			}
			// Crée les données
			$this->setData(['module', $this->getUrl(0), 'data', helper::increment(1, $this->getData(['module', $this->getUrl(0), 'data'])), $data]);
			// Envoi du mail
			// Rechercher l'adresse en fonction du mail
			$sent = true;
			$singleuser = $this->getData([
				'user',
				$this->getData(['module', $this->getUrl(0), 'config', 'user']),
				'mail'
			]);
			$singlemail = $this->getData(['module', $this->getUrl(0), 'config', 'mail']);
			$group = $this->getData(['module', $this->getUrl(0), 'config', 'role']);
			// Verification si le mail peut être envoyé
			if (
				self::$inputNotices === [] && (
					$group > 0 ||
					$singleuser !== '' ||
					$singlemail !== '')
			) {
				// Utilisateurs dans le rôle
				$to = [];
				if ($group > 0) {
					foreach ($this->getData(['user']) as $userId => $user) {
						if ($user['role'] >= $group) {
							$to[] = $user['mail'];
						}
					}
				}
				// Utilisateur désigné
				if (!empty($singleuser)) {
					$to[] = $singleuser;
				}
				// Mail désigné
				if (!empty($singlemail)) {
					$to[] = $singlemail;
				}
				if ($to) {
					// Sujet du mail
					$subject = $this->getData(['module', $this->getUrl(0), 'config', 'subject']);
					if ($subject === '') {
						$subject = 'Nouveau message en provenance de votre site';
					}
					// Envoi le mail
					$sent = $this->sendMail(
						$to,
						$subject,
						'Nouveau message en provenance de la page "' . $this->getData(['page', $this->getUrl(0), 'title']) . '" :<br><br>' .
						$content
					);
				}
			}
			// Redirection
			$redirect = $this->getData(['module', $this->getUrl(0), 'config', 'pageId']);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => ($sent === true ? 'Formulaire soumis' : $sent),
				'redirect' => $redirect ? helper::baseUrl() . $redirect : '',
				'state' => ($sent === true ? true : null)
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index',
			'vendor' => [
				'flatpickr'
			],
		]);
	}
}