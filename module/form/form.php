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
 * @link http://zwiicms.fr/
 */

class form extends common
{

	const VERSION = '4.6';
	const REALNAME = 'Formulaire';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	public static $actions = [
		'config' => self::GROUP_EDITOR,
		'option' => self::GROUP_EDITOR,
		'data' => self::GROUP_EDITOR,
		'delete' => self::GROUP_EDITOR,
		'deleteall' => self::GROUP_EDITOR,
		'index' => self::GROUP_VISITOR,
		'export2csv' => self::GROUP_EDITOR,
	];

	public static $data = [];

	public static $pages = [];

	public static $pagination;

	// Nombre d'articles dans la page de config:
	public static $itemsperPage = 20;


	// Objets
	const TYPE_MAIL = 'mail';
	const TYPE_SELECT = 'select';
	const TYPE_TEXT = 'text';
	const TYPE_TEXTAREA = 'textarea';
	const TYPE_DATETIME = 'date';
	const TYPE_CHECKBOX = 'checkbox';
	const TYPE_LABEL = 'label';
	const ITEMSPAGE = 10;


	public static $types = [
		self::TYPE_LABEL => 'Étiquette',
		self::TYPE_TEXT => 'Champ texte',
		self::TYPE_TEXTAREA => 'Grand champ texte',
		self::TYPE_MAIL => 'Adresse électronique',
		self::TYPE_SELECT => 'Sélection',
		self::TYPE_CHECKBOX => 'Case à cocher',
		self::TYPE_DATETIME => 'Date'
	];

	public static $listUsers = [
	];

	public static $signature = [
		'text' => 'Nom du site',
		'logo' => 'Logo du site'
	];

	public static $logoWidth = [
		'40' => '40%',
		'60' => '60%',
		'80' => '80%',
		'100' => '100%'
	];

	public static $optionOffset = [
		0 => 'Aucune',
		1 => 'Une colonne',
		2 => 'Deux colonnes'
	];

	public static $optionWidth = [
		6 => 'Six colonnes',
		7 => 'Sept colonnes',
		8 => 'Huit colonnes',
		9 => 'Neuf colonnes',
		10 => 'Dix colonnes',
		11 => 'Onze colonnes',
		12 => 'Douze colonnes',
	];

	public static $optionAlign = [
		'' => 'A gauche',
		'textAlignCenter' => 'Au centre',
		'textAlignRight' => 'A droite'
	];

	// Format fixe temporaire
	public static $dateFormat = 'd/m/Y';

	/**
	 * Configuration
	 */
	public function config()
	{

		// Mise à jour des données de module
		$this->update();

		// Liste des utilisateurs
		$userIdsFirstnames = helper::arrayColumn($this->getData(['user']), 'firstname');
		ksort($userIdsFirstnames);
		self::$listUsers[] = '';
		foreach ($userIdsFirstnames as $userId => $userFirstname) {
			self::$listUsers[] = $userId;
		}
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Génération des données vides
			if ($this->getData(['module', $this->getUrl(0), 'data']) === null) {
				$this->setData(['module', $this->getUrl(0), 'data', []]);
			}
			// Génération des champs
			$inputs = [];
			foreach ($this->getInput('formConfigPosition', null) as $index => $position) {
				$inputs[] = [
					'name' => html_entity_decode($this->getInput('formConfigName[' . $index . ']')),
					'position' => helper::filter($position, helper::FILTER_INT),
					'required' => $this->getInput('formConfigRequired[' . $index . ']', helper::FILTER_BOOLEAN),
					'type' => $this->getInput('formConfigType[' . $index . ']'),
					'values' => $this->getInput('formConfigValues[' . $index . ']')
				];
			}
			$this->setData(['module', $this->getUrl(0), 'input', $inputs]);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => helper::translate('Modifications enregistrées'),
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Configuration du module'),
			'vendor' => [
				'html-sortable',
			],
			'view' => 'config'
		]);
	}


	public function option()
	{
		// Liste des utilisateurs
		$userIdsFirstnames = helper::arrayColumn($this->getData(['user']), 'firstname');
		ksort($userIdsFirstnames);
		self::$listUsers[] = '';
		foreach ($userIdsFirstnames as $userId => $userFirstname) {
			self::$listUsers[] = $userId;
		}
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Débordement
			$width = $this->getInput('formOptionWidth');
			if ($this->getInput('formOptionWidth', helper::FILTER_INT) + $this->getInput('formOptionOffset', helper::FILTER_INT) > 12) {
				$width = (string) $this->getInput('formOptionWidth', helper::FILTER_INT) - $this->getInput('formOptionOffset', helper::FILTER_INT);
			}

			// Configuration
			$this->setData([
				'module',
				$this->getUrl(0),
				'config',
				[
					'button' => $this->getInput('formOptionButton'),
					'captcha' => $this->getInput('formOptionCaptcha', helper::FILTER_BOOLEAN),
					'role' => $this->getInput('formOptionGroup', helper::FILTER_INT),
					'user' => self::$listUsers[$this->getInput('formOptionUser', helper::FILTER_INT)],
					'mail' => $this->getInput('formOptionMail'),
					'pageId' => $this->getInput('formOptionPageIdToggle', helper::FILTER_BOOLEAN) === true ? $this->getInput('formOptionPageId', helper::FILTER_ID) : '',
					'subject' => html_entity_decode($this->getInput('formOptionSubject')),
					'replyto' => $this->getInput('formOptionMailReplyTo', helper::FILTER_BOOLEAN),
					'signature' => $this->getInput('formOptionSignature'),
					'logoUrl' => $this->getInput('formOptionLogo'),
					'logoWidth' => $this->getInput('formOptionLogoWidth'),
					'offset' => $this->getInput('formOptionOffset'),
					'width' => $width,
					'align' => $this->getInput('formOptionAlign'),
				]
			]);
			// Génération des données vides
			if ($this->getData(['module', $this->getUrl(0), 'data']) === null) {
				$this->setData(['module', $this->getUrl(0), 'data', []]);
			}
			// Valeurs en sortie
			$this->addOutput([
				'notification' => helper::translate('Modifications enregistrées'),
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'state' => true
			]);
		} else {
			// Liste des pages
			foreach ($this->getHierarchy(null, false, false) as $parentPageId => $childrenPageIds) {
				self::$pages[$parentPageId] = $this->getData(['page', $parentPageId, 'title']);
				foreach ($childrenPageIds as $childKey) {
					self::$pages[$childKey] = '&nbsp;»&nbsp;' . $this->getData(['page', $childKey, 'title']);
				}
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => helper::translate('Options de configuration'),
				'vendor' => [
					'html-sortable',
				],
				'view' => 'option'
			]);
		}
	}

	/**
	 * Données enregistrées
	 */
	public function data()
	{
		$data = $this->getData(['module', $this->getUrl(0), 'data']);
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$data
		) {
			// Pagination
			$pagination = helper::pagination($data, $this->getUrl(), self::$itemsperPage);
			// Liste des pages
			self::$pages = $pagination['pages'];
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
						'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $dataIds[$i],
						'value' => template::ico('trash')
					])
				];
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Export des données'),
			'view' => 'data'
		]);
	}

	/**
	 * Export CSV
	 * @author Frédéric Tempez <frederic.tempez@outlook.com>
	 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
	 */
	public function export2csv()
	{
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
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
					'notification' => sprintf(helper::translate('Export CSV effectué dans %s '), $csvfilename	),
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
					'state' => true
				]);
			} else {
				$this->addOutput([
					'notification' => helper::translate('Aucune donnée à exporter'),
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
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			$data = ($this->getData(['module', $this->getUrl(0), 'data']));
			if (count($data) > 0) {
				// Suppression multiple
				for ($i = 1; $i <= count($data); $i++) {
					$this->deleteData(['module', $this->getUrl(0), 'data', $i]);
				}
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
					'notification' => helper::translate('Données effacées'),
					'state' => true
				]);
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
					'notification' => helper::translate('Aucune donnée à effacer')
				]);
			}
		}
	}


	/**
	 * Suppression
	 */
	public function delete()
	{
		// Action interdite
		if ($this->getUser('permission', __CLASS__, __FUNCTION__) !== true) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
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
					'notification' => helper::translate('Donnée effacée'),
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

		// Mise à jour des données de module
		$this->update();

		// Soumission du formulaire
		if (
			$this->isPost()
		) {
			// Check la captcha
			if (
				$this->getData(['module', $this->getUrl(0), 'config', 'captcha'])
				// AND $this->getInput('formcaptcha', helper::FILTER_INT) !== $this->getInput('formcaptchaFirstNumber', helper::FILTER_INT) + $this->getInput('formcaptchaSecondNumber', helper::FILTER_INT))
				and password_verify($this->getInput('formCaptcha', helper::FILTER_INT), $this->getInput('formCaptchaResult')) === false
			) {
				self::$inputNotices['formCaptcha'] = helper::translate('Captcha incorrect');

			}
			// Préparation le contenu du mail
			$data = [];
			$replyTo = null;
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
				// Application des filtres
				$value = $this->getInput('formInput[' . $index . ']', $filter, $input['required']) === true ? 'X' : $this->getInput('formInput[' . $index . ']', $filter, $input['required']);
				// Convertit la date au format correct
				$value = $input['type'] === self::TYPE_DATETIME ? date_format(date_create($value), 'd/m/Y') : $value;
				//  premier champ email ajouté au mail en reply si option active
				if (
					$this->getData(['module', $this->getUrl(0), 'config', 'replyto']) === true &&
					$input['type'] === 'mail'
				) {
					$replyTo = $value;
				}
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
			$role = $this->getData(['module', $this->getUrl(0), 'config', 'role']);
			// Verification si le mail peut être envoyé
			if (
				self::$inputNotices === [] && (
					$role > 0 ||
					$singleuser !== '' ||
					$singlemail !== '')
			) {
				// Utilisateurs dans le groupe
				$to = [];
				if ($role > 0) {
					foreach ($this->getData(['user']) as $userId => $user) {
						if ($user['role'] >= $role) {
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
				if (empty($to)=== false) {
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
						$content,
						$replyTo,
						$this->getData(['config', 'smtp', 'from'])
					);
				}

			}
			// Redirection
			$redirect = $this->getData(['module', $this->getUrl(0), 'config', 'pageId']);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => ($sent === true ? helper::translate('Formulaire soumis') : $sent),
				'redirect' => $redirect ? helper::baseUrl() . $redirect : '',
				'state' => ($sent === true ? true : false),
				'vendor' => [
				],
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index',
		]);
	}

	/**
	 * Mise à jour du module
	 * Appelée par les fonctions index et config
	 */
	private function update()
	{

		// le module n'est pas initialisé
		if ($this->getData(['module', $this->getUrl(0), 'config']) === NULL) {
			$this->init();
		}
	}

	/**
	 * Initialisation du thème d'un nouveau module
	 */
	private function init()
	{
		// Données du module absentes
		require_once('module/form/ressource/defaultdata.php');
		if ($this->getData(['module', $this->getUrl(0), 'config']) === null) {
			$this->setData(['module', $this->getUrl(0), 'config', init::$defaultData]);
		}
	}

}