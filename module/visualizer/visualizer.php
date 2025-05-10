<?php

/**
 * This file is part of ZwiiCMS.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 * @author Jean-Luc Crespel
 * @copyright Copyright (C) from 2025, Jean-Luc Crespel
 */
class visualizer extends common
{
	/*const EDIT_OWNER = 'owner';
	const EDIT_ROLE = 'role';
	const EDIT_ALL = 'all';*/

	public static $actions = [
		'index' => self::GROUP_VISITOR,
		'config' => self::ROLE_EDITOR,
		'upload' => self::ROLE_EDITOR,
		'dirs' => self::ROLE_EDITOR,
	];

	public static $directories = [];

	// N° de version
	const VERSION = 'P-2.05';
	// Nom réél ou alias apparaissant lors du choix de module
	const REALNAME = 'Visualiseur GPX';
	// Suppression autorisée depuis la gestion des modules
	const DELETE = true;
	// Mise à jour autorisée pour toutes les versions >= 0.0
	const UPDATE = '0.0';
	// ici DATADIRECTORY = '' car ni de données externes à module.json, ni de fichier theme.css pour les pages utilisant ce module
	// Sinon il faut noter :  const DATADIRECTORY = self::DATA_DIR.'nom_du_module/';
	// placer les données qui sont communes à toutes les pages utilisant ce module dans  self::DATADIRECTORY.'module/'
	// placer les données de thèmes (comme theme.css) dans self::DATADIRECTORY.'nom_page/'
	// les autres données de page spécifiques à la page, traduite ou pas, sont à placer dans 'data_module/nom_page.json'
	// en utilisant la fonction $this->setData(['data_module', $this->getUrl(0), 'data', $vos_datas]). Lecture par $this->getData(['data_module', $this->getUrl(0), 'data'])
	const DATADIRECTORY = '';

	/**
	 * La fonction config() est réservée aux modérateurs ou administrateurs, elle permet de paramétrer le module,
	 * elle est utilisée pour ALLER en configuration et pour traiter les informations au RETOUR
	 * le script de configuration est dans .../view/config/config.php, il ne doit contenir que la partie affichage, la parie VISIBLE
	 * toute la partie traitement doit être ici ! c'est le principe de l'architecture MVC : Modèle Vue Contrôleur
	 */
	public function config()
	{
		// RETOUR de configuration par Enregistrer avec contrôle de l'autorisation

		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Enregistrement dans module.json des paramètres de configuration
			$this->setData([
				'module',
				$this->getUrl(0),
				'config',
				[
					'nameCata' => $this->getInput('visualizerName', helper::FILTER_STRING_SHORT, true),
					'KeyIGN' => $this->getInput('visualizerKeyIGN', helper::FILTER_STRING_SHORT, false),
					'DisplayTrk' => $this->getInput('visualizerDisplayTrk', helper::FILTER_STRING_SHORT, true),
					'MapW' => $this->getInput('visualizerMapW', helper::FILTER_STRING_SHORT, false),
					'MapH' => $this->getInput('visualizerMapH', helper::FILTER_STRING_SHORT, true),
					'dirTrkSelect' => $this->getInput('visualizerDirTrk'),
					'dirWPSelect' => $this->getInput('visualizerDirWP'),
					'dirDocTrkSelect' => $this->getInput('visualizerDirDocTrk'),
					'dirDocWPSelect' => $this->getInput('visualizerDirDocWP')
				]
			]);

			// Message et redirection (ici sur la page de configuration) après enregistrement de la configuration
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}

		// ALLER à la configuration du module
		$this->addOutput([
			'title' => helper::translate('configuration'),
			'view' => 'config'
		]);
	}

	/**
	 * La fonction index() est la fonction principale dont la partie VIEW sera visible par tous les visiteurs
	 */
	public function index()
	{
		// Ici la partie traitement, la partie VISIBLE sera dans le script .../view/index/index.php

		// Valeurs en sortie chargement des ressources externes sous vendor
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index',
			'vendor' => [
				'system'
			]
		]);

		// Lister les fichiers TRK : début
		$dirTrkSelect = $this->getData(['module', $this->getUrl(0), 'config', 'dirTrkSelect']);
		// $dirTrkSelect = $this->getData(['module', $this->getUrl(0), 'config', 'indexDirTrk']);
		$listFilesTrk = array();

		if (is_null($dirTrkSelect) === false && file_exists($dirTrkSelect)) {
			// $listFilesTrk_man = array_diff(scandir($dirTrkSelect), array('.', '..','doc'));
			$listFilesTrk_man = array_diff(scandir($dirTrkSelect), array('.', '..'));
			foreach ($listFilesTrk_man as $x) {
				$extension = substr(strrchr($x, '.'), 1);  // récupération de l'extension
				if ($extension == 'gpx' or $extension == 'GPX') {
					array_push($listFilesTrk, $x);
				}
			}
		}

		$this->setData([
			'module',
			$this->getUrl(0),
			'listFilesTrk',
			implode(';', $listFilesTrk)
		]);
		// Lister les fichiers TRK : fin

		// Lister les fichiers WP : début
		$dirWPSelect = $this->getData(['module', $this->getUrl(0), 'config', 'dirWPSelect']);
		$listFilesWP = array();

		if (is_null($dirWPSelect) === false && file_exists($dirWPSelect)) {
			$listFilesWP_man = array_diff(scandir($dirWPSelect), array('.', '..'));
			foreach ($listFilesWP_man as $x) {
				$extension = substr(strrchr($x, '.'), 1);  // récupération de l'extension
				if ($extension == 'gpx' or $extension == 'GPX') {
					array_push($listFilesWP, $x);
				}
			}
		}

		$this->setData([
			'module',
			$this->getUrl(0),
			'listFilesWP',
			implode(';', $listFilesWP)
		]);
		// Lister les fichiers WP : fin

		// Lister les fichiers doc traces : début
		$dirDocTrkSelect = $this->getData(['module', $this->getUrl(0), 'config', 'dirDocTrkSelect']);
		$listFilesDocTrk = array();

		if (is_null($dirDocTrkSelect) === false && file_exists($dirDocTrkSelect)) {
			$listFilesDocTrk_man = array_diff(scandir($dirDocTrkSelect), array('.', '..'));
			foreach ($listFilesDocTrk_man as $x) {
				$extension = substr(strrchr($x, '.'), 1);  // récupération de l'extension
				if ($extension == 'pdf' or $extension == 'PDF') {
					array_push($listFilesDocTrk, $x);
				}
			}
		}

		$this->setData([
			'module',
			$this->getUrl(0),
			'listFilesDocTrk',
			implode(';', $listFilesDocTrk)
		]);
		// Lister les fichiers doc traces : fin

		// Lister les fichiers doc services (WP) : début
		$dirDocWPSelect = $this->getData(['module', $this->getUrl(0), 'config', 'dirDocWPSelect']);
		$listFilesDocWP = array();

		if (is_null($dirDocWPSelect) === false && file_exists($dirDocWPSelect)) {
			$listFilesDocWP_man = array_diff(scandir($dirDocWPSelect), array('.', '..'));
			foreach ($listFilesDocWP_man as $x) {
				$extension = substr(strrchr($x, '.'), 1);  // récupération de l'extension
				if ($extension == 'pdf' or $extension == 'PDF') {
					array_push($listFilesDocWP, $x);
				}
			}
		}

		$this->setData([
			'module',
			$this->getUrl(0),
			'listFilesDocWP',
			implode(';', $listFilesDocWP)
		]);
	}

	public function upload()
	{
		// Retour de transfert de fichier avec contrôle de l'autorisation
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true && $this->isPost()
			// $this->getUser('permission', __CLASS__, __FUNCTION__) === true && $_SERVER['REQUEST_METHOD'] === 'POST'
		) {
			$targetPath = $_REQUEST['visualizerUploadDir'];
			$uploadFileSelect = $_FILES['visualizerUploadFile'];
			$maxSize = 2000000;

			/*echo '<pre>';
			echo 'Here is some more debugging info:';
			print_r($_FILES);
			print "</pre>";*/

			$state = true;
			$notif = 'Fichier(s) transféré(s)';

			if (is_dir($targetPath)) {
				$nbr = count($uploadFileSelect['name']);
				for ($i = 0; $i < $nbr; $i++) {
					$filename = basename($uploadFileSelect['name'][$i]);
					$size = filesize($uploadFileSelect['tmp_name'][$i]);
					if ($size > $maxSize) {
						$state = false;
						$notif = 'La taille de ' . $filename . ' est > à 1Mo !';
						break;
					} else if (!move_uploaded_file($uploadFileSelect['tmp_name'][$i], $targetPath . $filename)) {
						$state = false;
						$notif = 'Erreur pendant le transert !';
						break;
					}
				}
			} else {
				$state = false;
				$notif = "Le répertoire n'existe pas !";
			}

			if ($state == true) {
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
					'notification' => helper::translate($notif),
					'state' => $state
				]);
			} else {
				$this->addOutput([
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
					'notification' => helper::translate($notif),
					'state' => $state
				]);
			}
		}
		// ALLER à l'upload du module
		$this->addOutput([
			'title' => helper::translate('upload'),
			'view' => 'upload'
		]);
	}

	public function dirs()
	{
		$rootPath = $this->getUserPath();
		$sharePath = [];

		// Cas où l'utilisateur n'a pas de droits d'accès
		if (empty($rootPath) || $rootPath === 'none') {
			$sharePath[] = [
				'name' => helper::translate('Aucun dossier partagé'),
				'path' => 'none'
			];
		} else {
			try {
				// Liste des dossiers
				$directories = helper::getSubdirectories($rootPath);

				// Tri et transformation des dossiers
				if (!empty($directories)) {
					ksort($directories);
					foreach ($directories as $key => $path) {
						$sharePath[] = [
							'name' => $key,
							'path' => $path
						];
					}
				}
			} catch (Exception $e) {
				$sharePath[] = [
					'name' => helper::translate('Erreur lors de la lecture des dossiers'),
					'path' => 'none'
				];
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'display' => self::DISPLAY_JSON,
			'content' => $sharePath
		]);
	}

}
