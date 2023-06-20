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

/**
 * Chargement des classes filles
 * router : aiguillage des pages
 */


class common
{

	const DISPLAY_RAW = 0;
	const DISPLAY_JSON = 1;
	const DISPLAY_RSS = 2;
	const DISPLAY_LAYOUT_BLANK = 3;
	const DISPLAY_LAYOUT_MAIN = 4;
	const DISPLAY_LAYOUT_LIGHT = 5;
	const GROUP_BANNED = -1;
	const GROUP_VISITOR = 0;
	const GROUP_MEMBER = 1;
	const GROUP_MODERATOR = 2;
	const GROUP_ADMIN = 3;
	const SIGNATURE_ID = 1;
	const SIGNATURE_PSEUDO = 2;
	const SIGNATURE_FIRSTLASTNAME = 3;
	const SIGNATURE_LASTFIRSTNAME = 4;
	// Dossier de travail
	const BACKUP_DIR = 'site/backup/';
	const DATA_DIR = 'site/data/';
	const FILE_DIR = 'site/file/';
	const TEMP_DIR = 'site/tmp/';
	const I18N_DIR = 'site/i18n/';
	const MODULE_DIR = 'module/';

	// Miniatures de la galerie
	const THUMBS_SEPARATOR = 'mini_';
	const THUMBS_WIDTH = 640;

	// Contrôle d'édition temps maxi en secondes avant déconnexion 30 minutes
	const ACCESS_TIMER = 1800;

	// Numéro de version
	const ZWII_VERSION = '12.4.00';

	// URL autoupdate
	const ZWII_UPDATE_URL = 'https://forge.chapril.org/ZwiiCMS-Team/update/raw/branch/master/';
	const ZWII_UPDATE_CHANNEL = "v12";

	// URL langues de l'UI en ligne
	const ZWII_UI_URL = 'https://forge.chapril.org/ZwiiCMS-Team/zwiicms-translations/raw/branch/master/';


	public static $actions = [];
	public static $coreModuleIds = [
		'config',
		'install',
		'maintenance',
		'page',
		'sitemap',
		'theme',
		'user',
		'translate',
		'plugin'
	];
	public static $accessList = [
		'user',
		'theme',
		'config',
		'edit',
		'config',
		'translate'
	];
	public static $accessExclude = [
		'login',
		'logout'
	];
	private $data = [];
	private $hierarchy = [
		'all' => [],
		'visible' => [],
		'bar' => []
	];
	private $input = [
		'_COOKIE' => [],
		'_POST' => []
	];
	public static $inputBefore = [];
	public static $inputNotices = [];
	public static $importNotices = [];
	public static $coreNotices = [];
	public $output = [
		'access' => true,
		'content' => '',
		'contentLeft' => '',
		'contentRight' => '',
		'display' => self::DISPLAY_LAYOUT_MAIN,
		'metaDescription' => '',
		'metaTitle' => '',
		'notification' => '',
		'redirect' => '',
		'script' => '',
		'showBarEditButton' => false,
		'showPageContent' => false,
		'state' => false,
		'style' => '',
		'inlineStyle' => [],
		'inlineScript' => [],
		'title' => null,
		// Null car un titre peut être vide
		// Trié par ordre d'exécution
		'vendor' => [
			'jquery',
			'normalize',
			'lity',
			'filemanager',
			// 'tinycolorpicker', Désactivé par défaut
			// 'tinymce', Désactivé par défaut
			// 'codemirror', // Désactivé par défaut
			'tippy',
			'zwiico',
			'imagemap',
			'simplelightbox'
		],
		'view' => ''
	];
	public static $groups = [
		self::GROUP_BANNED => 'Banni',
		self::GROUP_VISITOR => 'Visiteur',
		self::GROUP_MEMBER => 'Membre',
		self::GROUP_MODERATOR => 'Éditeur',
		self::GROUP_ADMIN => 'Administrateur'
	];
	public static $groupEdits = [
		self::GROUP_BANNED => 'Banni',
		self::GROUP_MEMBER => 'Membre',
		self::GROUP_MODERATOR => 'Éditeur',
		self::GROUP_ADMIN => 'Administrateur'
	];
	public static $groupNews = [
		self::GROUP_MEMBER => 'Membre',
		self::GROUP_MODERATOR => 'Éditeur',
		self::GROUP_ADMIN => 'Administrateur'
	];
	public static $groupPublics = [
		self::GROUP_VISITOR => 'Visiteur',
		self::GROUP_MEMBER => 'Membre',
		self::GROUP_MODERATOR => 'Éditeur',
		self::GROUP_ADMIN => 'Administrateur'
	];

	//Langues de l'UI
	// Langue de l'interface, tableau des dialogues
	public static $dialog;
	// Langue de l'interface sélectionnée
	public static $i18nUI = 'fr_FR';
	// Langues de contenu
	public static $i18nContent = 'fr_FR';
	public static $languages = [
		'az_AZ' => 'Azərbaycan dili',
		'bg_BG' => 'български език',
		//'ca' => 'Català, valencià',
		//'cs' => 'čeština, český jazyk',
		//'da' => 'Dansk',
		'de' => 'Deutsch',
		'en_EN' => 'English',
		'es' => 'Español',
		//'fa' => 'فارسی',
		'fr_FR' => 'Français',
		'he_IL' => 'Hebrew (Israel)',
		'gr_GR' => 'Ελληνικά',
		'hr' => 'Hrvatski jezik',
		'hu_HU' => 'Magyar',
		'id' => 'Bahasa Indonesia',
		'it' => 'Italiano',
		'ja' => '日本',
		'lt' => 'Lietuvių kalba',
		//'mn_MN' => 'монгол',
		'nb_NO' => 'Norsk bokmål',
		'nn_NO' => 'Norsk nynorsk',
		'nl' => 'Nederlands, Vlaams',
		'pl' => 'Język polski, polszczyzna',
		'pt_BR' => 'Português(Brazil)',
		'pt_PT' => 'Português',
		'ro' => 'Română',
		'ru' => 'Pусский язык',
		'sk' => 'Slovenčina',
		'sl' => 'Slovenski jezik',
		'sv_SE' => 'Svenska',
		'th_TH' => 'ไทย',
		'tr_TR' => 'Türkçe',
		'uk_UA' => 'Yкраїнська мова',
		'vi' => 'Tiếng Việt',
		'zh_CN' => '中文 (Zhōngwén), 汉语, 漢語',

		// source: http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
	];


	// Zone de temps
	public static $timezone;
	private $url = '';
	// Données de site
	private $user = [];

	// Descripteur de données Entrées / Sorties
	// Liste ici tous les fichiers de données
	private $dataFiles = [
		'admin' => '',
		'blacklist' => '',
		'config' => '',
		'core' => '',
		'font' => '',
		'module' => '',
		'locale' => '',
		'page' => '',
		'theme' => '',
		'user' => '',
		'language' => '',
		'profil' => '',
	];

	public static $fontsWebSafe = [
		'arial' => [
			'name' => 'Arial',
			'font-family' => 'Arial, Helvetica, sans-serif',
			'resource' => 'websafe'
		],
		'arial-black' => [
			'name' => 'Arial Black',
			'font-family' => '\'Arial Black\', Gadget, sans-serif',
			'resource' => 'websafe'
		],
		'courrier' => [
			'name' => 'Courier',
			'font-family' => 'Courier, \'Liberation Mono\', monospace',
			'resource' => 'websafe'
		],
		'courrier-new' => [
			'name' => 'Courier New',
			'font-family' => '\'Courier New\', Courier, monospace',
			'resource' => 'websafe'
		],
		'garamond' => [
			'name' => 'Garamond',
			'font-family' => 'Garamond, serif',
			'resource' => 'websafe'
		],
		'georgia' => [
			'name' => 'Georgia',
			'font-family' => 'Georgia, serif',
			'resource' => 'websafe'
		],
		'impact' => [
			'name' => 'Impact',
			'font-family' => 'Impact, Charcoal, sans-serif',
			'resource' => 'websafe'
		],
		'lucida' => [
			'name' => 'Lucida',
			'font-family' => '\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif',
			'resource' => 'websafe'
		],
		'tahoma' => [
			'name' => 'Tahoma',
			'font-family' => 'Tahoma, Geneva, sans-serif',
			'resource' => 'websafe'
		],
		'times-new-roman' => [
			'name' => 'Times New Roman',
			'font-family' => '\'Times New Roman\', \'Liberation Serif\', serif',
			'resource' => 'websafe'
		],
		'trebuchet' => [
			'name' => 'Trebuchet',
			'font-family' => '\'Trebuchet MS\', Arial, Helvetica, sans-serif',
			'resource' => 'websafe'
		],
		'verdana' => [
			'name' => 'Verdana',
			'font-family' => 'Verdana, Geneva, sans-serif;',
			'resource' => 'websafe'
		]
	];


	/**
	 * Constructeur commun
	 */
	public function __construct()
	{

		// Extraction des données http
		if (isset($_POST)) {
			$this->input['_POST'] = $_POST;
		}
		if (isset($_COOKIE)) {
			$this->input['_COOKIE'] = $_COOKIE;
		}

		// Extraction de la sesion
		// $this->input['_SESSION'] = $_SESSION;

		// Déterminer la langue du contenu du site
		if (isset($_SESSION['ZWII_CONTENT'])) {
			// Déterminé par la session présente
			self::$i18nContent = $_SESSION['ZWII_CONTENT'];
		} else {
			// Détermine la langue par défaut
			foreach (self::$languages as $key => $value) {
				if (file_exists(self::DATA_DIR . $key . '/.default')) {
					self::$i18nContent = $key;
					$_SESSION['ZWII_CONTENT'] = $key;
					break;
				}
			}
		}
		\setlocale(LC_ALL, self::$i18nContent . '.UTF8');

		// Instanciation de la classe des entrées / sorties
		// Récupère les descripteurs
		foreach ($this->dataFiles as $keys => $value) {
			// Constructeur  JsonDB;
			$this->dataFiles[$keys] = new \Prowebcraft\JsonDb([
				'name' => $keys . '.json',
				'dir' => $this->dataPath($keys, self::$i18nContent),
				'backup' => file_exists('site/data/.backup')
			]);
		}

		// Installation fraîche, initialisation des modules
		if ($this->user === []) {
			foreach ($this->dataFiles as $stageId => $item) {
				$folder = $this->dataPath($stageId, self::$i18nContent);
				if (
					file_exists($folder . $stageId . '.json') === false
				) {
					$this->initData($stageId, self::$i18nContent);
					common::$coreNotices[] = $stageId;
				}
			}
		}

		// Récupère un utilisateur connecté
		if ($this->user === []) {
			$this->user = $this->getData(['user', $this->getInput('ZWII_USER_ID')]);
		}

		// Langue de l'administration si le user est connecté
		if ($this->getData(['user', $this->getUser('id'), 'language'])) {
			// Langue sélectionnée dans le compte, la langue du cookie sinon celle du compte ouvert
			self::$i18nUI = $this->getData(['user', $this->getUser('id'), 'language']);
			// Validation de la langue
			self::$i18nUI = isset(self::$i18nUI) && file_exists(self::I18N_DIR . self::$i18nUI . '.json')
				? self::$i18nUI
				: 'fr_FR';
		} else {
			if (isset($_SESSION['ZWII_UI'])) {
				self::$i18nUI = $_SESSION['ZWII_UI'];
			} elseif (isset($_COOKIE['ZWII_UI'])) {
				self::$i18nUI = $_COOKIE['ZWII_UI'];
			} else {
				self::$i18nUI = 'fr_FR';
			}
			$_SESSION['ZWII_UI'] = self::$i18nUI;
		}
		// Stocker le cookie de langue pour l'éditeur de texte
		setcookie('ZWII_UI', self::$i18nUI, time() + 3600, helper::baseUrl(false, false), '', false, false);

		// Construit la liste des pages parents/enfants
		if ($this->hierarchy['all'] === []) {
			$this->buildHierarchy();
		}

		// Construit l'url
		if ($this->url === '') {
			if ($url = $_SERVER['QUERY_STRING']) {
				$this->url = $url;
			} else {
				$this->url = $this->getData(['locale', 'homePageId']);
			}
		}

		// Chargement des dialogues
		if (!file_exists(self::I18N_DIR . self::$i18nUI . '.json')) {
			// Copie des fichiers de langue par défaut fr_FR si pas initialisé
			$this->copyDir('core/module/install/ressource/i18n', self::I18N_DIR);
		}
		self::$dialog = json_decode(file_get_contents(self::I18N_DIR . self::$i18nUI . '.json'), true);

		// Dialogue du module
		if ($this->getData(['page', $this->getUrl(0), 'moduleId'])) {
			$moduleId = $this->getData(['page', $this->getUrl(0), 'moduleId']);
			if (
				is_dir(self::MODULE_DIR . $moduleId . '/i18n')
				&& file_exists(self::MODULE_DIR . $moduleId . '/i18n/' . self::$i18nUI . '.json')
			) {
				$d = json_decode(file_get_contents(self::MODULE_DIR . $moduleId . '/i18n/' . self::$i18nUI . '.json'), true);
				self::$dialog = array_merge(self::$dialog, $d);
			}
		}

		// Données de proxy
		$proxy = $this->getData(['config', 'proxyType']) . $this->getData(['config', 'proxyUrl']) . ':' . $this->getData(['config', 'proxyPort']);
		if (
			!empty($this->getData(['config', 'proxyUrl'])) &&
			!empty($this->getData(['config', 'proxyPort']))
		) {
			$context = array(
				'http' => array(
					'proxy' => $proxy,
					'request_fulluri' => true,
					'verify_peer' => false,
					'verify_peer_name' => false,
				),
				"ssl" => array(
					"verify_peer" => false,
					"verify_peer_name" => false
				)
			);
			stream_context_set_default($context);
		}
		// Mise à jour des données core
		include('core/include/update.inc.php');

	}



	/**
	 * Ajoute les valeurs en sortie
	 * @param array $output Valeurs en sortie
	 */
	public function addOutput($output)
	{
		$this->output = array_merge($this->output, $output);
	}

	/**
	 * Ajoute une notice de champ obligatoire
	 * @param string $key Clef du champ
	 */
	public function addRequiredInputNotices($key)
	{
		// La clef est un tableau
		if (preg_match('#\[(.*)\]#', $key, $secondKey)) {
			$firstKey = explode('[', $key)[0];
			$secondKey = $secondKey[1];
			if (empty($this->input['_POST'][$firstKey][$secondKey])) {
				common::$inputNotices[$firstKey . '_' . $secondKey] = helper::translate('Obligatoire');
			}
		}
		// La clef est une chaine
		elseif (empty($this->input['_POST'][$key])) {
			common::$inputNotices[$key] = helper::translate('Obligatoire');
		}
	}

	/**
	 * Check du token CSRF (true = bo
	 */
	public function checkCSRF()
	{
		return ((empty($_POST['csrf']) or hash_equals( $_POST['csrf'], $_SESSION['csrf']) === false) === false);
	}

	/**
	 * Supprime des données
	 * @param array $keys Clé(s) des données
	 */
	public function deleteData($keys)
	{
		// Descripteur de la base
		$db = $this->dataFiles[$keys[0]];
		// Initialisation de la requête par le nom de la base
		$query = $keys[0];
		// Construire la requête
		for ($i = 1; $i <= count($keys) - 1; $i++) {
			$query .= '.' . $keys[$i];
		}
		// Effacer la donnée
		$success = $db->delete($query, true);
		return is_object($success);
	}

	/**
	 * Sauvegarde des données
	 * @param array $keys Clé(s) des données
	 */
	public function setData($keys = [])
	{
		// Pas d'enregistrement lorsqu'une notice est présente ou tableau transmis vide
		if (
			!empty(self::$inputNotices)
			or empty($keys)
		) {
			return false;
		}

		// Empêcher la sauvegarde d'une donnée nulle.
		if (gettype($keys[count($keys) - 1]) === NULL) {
			return false;
		}

		// Initialisation du retour en cas d'erreur de descripteur
		$success = false;
		// Construire la requête dans la base inf à 1 retourner toute la base
		if (count($keys) >= 1) {
			// Descripteur de la base
			$db = $this->dataFiles[$keys[0]];
			$query = $keys[0];
			// Construire la requête
			// Ne pas tenir compte du dernier élément qui une une value donc <
			for ($i = 1; $i < count($keys) - 1; $i++) {
				$query .= '.' . $keys[$i];
			}
			// Appliquer la modification, le dernier élément étant la donnée à sauvegarder
			$success = is_object($db->set($query, $keys[count($keys) - 1], true));
		}
		return $success;
	}

	/**
	 * Accède aux données
	 * @param array $keys Clé(s) des données
	 * @return mixed
	 */
	public function getData($keys = [])
	{
		// Eviter une requete vide
		if (count($keys) >= 1) {
			// descripteur de la base
			$db = $this->dataFiles[$keys[0]];
			$query = $keys[0];
			// Construire la requête
			for ($i = 1; $i < count($keys); $i++) {
				$query .= '.' . $keys[$i];
			}
			return $db->get($query);
		}
	}

	/**
	 * Lire les données de la page
	 * @param string pageId
	 * @param string langue
	 * @return string contenu de la page
	 */
	public function getPage($page, $lang)
	{

		// Le nom de la ressource et le fichier de contenu sont définis :
		if (
			$this->getData(['page', $page, 'content']) !== ''
			&& file_exists(self::DATA_DIR . $lang . '/content/' . $this->getData(['page', $page, 'content']))
		) {
			return file_get_contents(self::DATA_DIR . $lang . '/content/' . $this->getData(['page', $page, 'content']));
		} else {
			return 'Aucun contenu trouvé.';
		}
	}

	/**
	 * Ecrire les données de la page
	 * @param string pageId
	 * @param string contenu de la page
	 * @return int nombre d'octets écrits ou erreur
	 */
	public function setPage($page, $value, $lang)
	{

		return file_put_contents(self::DATA_DIR . $lang . '/content/' . $page . '.html', $value);
	}



	/**
	 * Effacer les données de la page
	 * @param string pageId
	 * @return bool statut de l'effacement
	 */
	public function deletePage($page, $lang)
	{

		return unlink(self::DATA_DIR . $lang . '/content/' . $this->getData(['page', $page, 'content']));
	}

	/**
	 * Initialisation des données
	 * @param string $module : nom du module à générer
	 * @param string $lang la langue à créer
	 * @param bool $sampleSite créer un site exemple en FR
	 * choix valides :  core config user theme page module
	 */
	public function initData($module, $lang, $sampleSite = false)
	{
		// Tableau avec les données vierges
		require_once('core/module/install/ressource/defaultdata.php');

		if (
			$module === 'page' ||
			$module === 'module' ||
			$module === 'locale'
		) {
			// Création des sous-dossiers localisés
			if (!file_exists(self::DATA_DIR . $lang)) {
				mkdir(self::DATA_DIR . $lang, 0755);
			}
			if (!file_exists(self::DATA_DIR . $lang . '/content')) {
				mkdir(self::DATA_DIR . $lang . '/content', 0755);
			}
			// Site en français avec site exemple
			if ($lang == 'fr_FR' && $sampleSite === true) {
				file_put_contents(self::DATA_DIR . $lang . '/' . $module . '.json', json_encode([$module => init::$siteTemplate[$module]], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT));
				// Création des pages
				foreach (init::$siteContent as $key => $value) {
					$this->setPage($key, $value, 'fr_FR');
				}
				// Version en langue étrangère ou fr_FR sans site de test
			} else {
				// En_EN par défaut si le contenu localisé n'est pas traduit
				$langDefault = $lang;
				if (!isset(init::$defaultDataI18n[$lang])) {
					$langDefault = 'default';
				}
				file_put_contents(self::DATA_DIR . $lang . '/' . $module . '.json', json_encode([$module => init::$defaultDataI18n[$langDefault][$module]], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT));
				// Créer la page d'accueil
				$pageId = init::$defaultDataI18n[$langDefault]['locale']['homePageId'];
				$content = init::$defaultDataI18n[$langDefault]['html'];
				file_put_contents(self::DATA_DIR . $lang . '/content/' . init::$defaultDataI18n[$langDefault]['page'][$pageId]['content'], $content);
			}
		} else {
			// Installation des données du module
			file_put_contents(self::DATA_DIR . $module . '.json', json_encode([$module => init::$defaultData[$module]], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT));
		}

	}


	/**
	 * Accède à la liste des pages parents et de leurs enfants
	 * @param int $parentId Id de la page parent
	 * @param bool $onlyVisible Affiche seulement les pages visibles
	 * @param bool $onlyBlock Affiche seulement les pages de type barre
	 * @return array
	 */
	public function getHierarchy($parentId = null, $onlyVisible = true, $onlyBlock = false)
	{
		$hierarchy = $onlyVisible ? $this->hierarchy['visible'] : $this->hierarchy['all'];
		$hierarchy = $onlyBlock ? $this->hierarchy['bar'] : $hierarchy;
		// Enfants d'un parent
		if ($parentId) {
			if (array_key_exists($parentId, $hierarchy)) {
				return $hierarchy[$parentId];
			} else {
				return [];
			}
		}
		// Parents et leurs enfants
		else {
			return $hierarchy;
		}
	}

	/**
	 * Fonction pour construire le tableau des pages
	 * Appelée par le core uniquement
	 */

	private function buildHierarchy()
	{

		$pages = helper::arrayColumn($this->getData(['page']), 'position', 'SORT_ASC');
		// Parents
		foreach ($pages as $pageId => $pagePosition) {
			if (
				// Page parent
				$this->getData(['page', $pageId, 'parentPageId']) === ""
				// Ignore les pages dont l'utilisateur n'a pas accès
				and ($this->getData(['page', $pageId, 'group']) === self::GROUP_VISITOR
					or ($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
						and $this->getUser('group') >= $this->getData(['page', $pageId, 'group'])
					)
				)
			) {
				if ($pagePosition !== 0) {
					$this->hierarchy['visible'][$pageId] = [];
				}
				if ($this->getData(['page', $pageId, 'block']) === 'bar') {
					$this->hierarchy['bar'][$pageId] = [];
				}
				$this->hierarchy['all'][$pageId] = [];
			}
		}
		// Enfants
		foreach ($pages as $pageId => $pagePosition) {
			if (
				// Page parent
				$parentId = $this->getData(['page', $pageId, 'parentPageId'])
				// Ignore les pages dont l'utilisateur n'a pas accès
				and (
					($this->getData(['page', $pageId, 'group']) === self::GROUP_VISITOR
						and $this->getData(['page', $parentId, 'group']) === self::GROUP_VISITOR
					)
					or ($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
						and $this->getUser('group') >= $this->getData(['page', $parentId, 'group'])
						and $this->getUser('group') >= $this->getData(['page', $pageId, 'group'])
					)
				)
			) {
				if ($pagePosition !== 0) {
					$this->hierarchy['visible'][$parentId][] = $pageId;
				}
				if ($this->getData(['page', $pageId, 'block']) === 'bar') {
					$this->hierarchy['bar'][$pageId] = [];
				}
				$this->hierarchy['all'][$parentId][] = $pageId;
			}
		}
	}

	/**
	 * Génère un fichier json avec la liste des pages
	 *
	 */
	private function tinyMcePages()
	{
		// Sauve la liste des pages pour TinyMCE
		$parents = [];
		$rewrite = (helper::checkRewrite()) ? '' : '?';
		// Boucle de recherche des pages actives
		foreach ($this->getHierarchy(null, false, false) as $parentId => $childIds) {
			$children = [];
			// Exclure les barres
			if ($this->getData(['page', $parentId, 'block']) !== 'bar') {
				// Boucler sur les enfants et récupérer le tableau children avec la liste des enfants
				foreach ($childIds as $childId) {
					$children[] = [
						'title' => '&nbsp;»&nbsp;' . html_entity_decode($this->getData(['page', $childId, 'shortTitle']), ENT_QUOTES),
						'value' => $rewrite . $childId
					];
				}
				// Traitement
				if (empty($childIds)) {
					// Pas d'enfant, uniquement l'entrée du parent
					$parents[] = [
						'title' => html_entity_decode($this->getData(['page', $parentId, 'shortTitle']), ENT_QUOTES),
						'value' => $rewrite . $parentId
					];
				} else {
					// Des enfants, on ajoute la page parent en premier
					array_unshift($children, [
						'title' => html_entity_decode($this->getData(['page', $parentId, 'shortTitle']), ENT_QUOTES),
						'value' => $rewrite . $parentId
					]);
					// puis on ajoute les enfants au parent
					$parents[] = [
						'title' => html_entity_decode($this->getData(['page', $parentId, 'shortTitle']), ENT_QUOTES),
						'value' => $rewrite . $parentId,
						'menu' => $children
					];
				}
			}
		}
		// Sitemap et Search
		$children = [];
		$children[] = [
			'title' => 'Rechercher dans le site',
			'value' => $rewrite . 'search'
		];
		$children[] = [
			'title' => 'Plan du site',
			'value' => $rewrite . 'sitemap'
		];
		$parents[] = [
			'title' => 'Pages spéciales',
			'value' => '#',
			'menu' => $children
		];

		// Enregistrement : 3 tentatives
		for ($i = 0; $i < 3; $i++) {
			if (file_put_contents('core/vendor/tinymce/link_list.json', json_encode($parents, JSON_UNESCAPED_UNICODE), LOCK_EX) !== false) {
				break;
			}
			// Pause de 10 millisecondes
			usleep(10000);
		}
	}


	/**
	 * Accède à une valeur des variables http (ordre de recherche en l'absence de type : _COOKIE, _POST)
	 * @param string $key Clé de la valeur
	 * @param int $filter Filtre à appliquer à la valeur
	 * @param bool $required Champ requis
	 * @return mixed
	 */
	public function getInput($key, $filter = helper::FILTER_STRING_SHORT, $required = false)
	{
		// La clef est un tableau
		if (preg_match('#\[(.*)\]#', $key, $secondKey)) {
			$firstKey = explode('[', $key)[0];
			$secondKey = $secondKey[1];
			foreach ($this->input as $type => $values) {
				// Champ obligatoire
				if ($required) {
					$this->addRequiredInputNotices($key);
				}
				// Check de l'existence
				// Également utile pour les checkbox qui ne retournent rien lorsqu'elles ne sont pas cochées
				if (
					array_key_exists($firstKey, $values)
					and array_key_exists($secondKey, $values[$firstKey])
				) {
					// Retourne la valeur filtrée
					if ($filter) {
						return helper::filter($this->input[$type][$firstKey][$secondKey], $filter);
					}
					// Retourne la valeur
					else {
						return $this->input[$type][$firstKey][$secondKey];
					}
				}
			}
		}
		// La clef est une chaîne
		else {
			foreach ($this->input as $type => $values) {
				// Champ obligatoire
				if ($required) {
					$this->addRequiredInputNotices($key);
				}
				// Check de l'existence
				// Également utile pour les checkbox qui ne retournent rien lorsqu'elles ne sont pas cochées
				if (array_key_exists($key, $values)) {
					// Retourne la valeur filtrée
					if ($filter) {
						return helper::filter($this->input[$type][$key], $filter);
					}
					// Retourne la valeur
					else {
						return $this->input[$type][$key];
					}
				}
			}
		}
		// Sinon retourne null
		return helper::filter(null, $filter);
	}

	/**
	 * Accède à une partie l'url ou à l'url complète
	 * @param int $key Clé de l'url
	 * @return string|null
	 */
	public function getUrl($key = null)
	{
		// Url complète
		if ($key === null) {
			return $this->url;
		}
		// Une partie de l'url
		else {
			$url = explode('/', $this->url);
			return array_key_exists($key, $url) ? $url[$key] : null;
		}
	}

	/**
	 * Accède à l'utilisateur connecté
	 * @param int $key Clé de la valeur
	 * @return string|null
	 */
	public function getUser($key, $perm1 = null, $perm2 = null)
	{
		if (is_array($this->user) === false) {
			return false;
		} elseif ($key === 'id') {
			return $this->getInput('ZWII_USER_ID');
		} elseif ($key === 'permission') {
			return $this->getPermission($perm1, $perm2);
		} elseif (array_key_exists($key, $this->user)) {
			return $this->user[$key];
		} else {
			return false;
		}
	}

	/**
	 * Retourne les permission de l'utilisateur connecté
	 * @param int $key Clé de la valeur du groupe
	 * @return string|null
	 */
	public function getPermission($key1, $key2 = null)
	{
		// User n'existe pas
		// if (is_array($this->user) === false) {
		//	return false;
		// Administrateur, toutes les permissions
		if ($this->getUser('group') === self::GROUP_ADMIN) {
			return true;
		} elseif ($this->getUser('group') < 1) { // Groupe sans autorisation
			return false;
		} elseif (
			// Groupe avec profil, consultation des autorisations sur deux clés
			$key1
			&& $key2
			&& $this->user
			&& $this->getData(['profil', $this->user['group'], $this->user['profil'], $key1])
			&& array_key_exists($key2, $this->getData(['profil', $this->user['group'], $this->user['profil'], $key1]))
		) {
			return $this->getData(['profil', $this->user['group'], $this->user['profil'], $key1, $key2]);
			// Groupe avec profil, consultation des autorisations sur une seule clé
		} elseif (
			$key1
			&& $this->user
			&& $this->getData(['profil', $this->user['group'], $this->user['profil']])
			&& array_key_exists($key1, $this->getData(['profil', $this->user['group'], $this->user['profil']]))
		) {
			return $this->getData(['profil', $this->user['group'], $this->user['profil'], $key1]);
		} else {
			// Une permission non spécifiée dans le profil est autorisée par défaut pour le fonctionnement de $action
			return true;
		}

	}

	/**
	 * Check qu'une valeur est transmise par la méthode _POST
	 * @return bool
	 */
	public function isPost()
	{
		return ($this->checkCSRF() and $this->input['_POST'] !== []);
	}


	/**
	 * Retourne une chemin localisé pour l'enregistrement des données
	 * @param $stageId nom du module
	 * @param $lang langue des pages
	 * @return string du dossier à créer
	 */
	public function dataPath($id, $lang)
	{
		// Sauf pour les pages et les modules
		if (
			$id === 'page' ||
			$id === 'module' ||
			$id === 'locale'
		) {
			$folder = self::DATA_DIR . $lang . '/';
		} else {
			$folder = self::DATA_DIR;
		}
		return ($folder);
	}


	/**
	 * Génère un fichier un fichier sitemap.xml
	 * https://github.com/icamys/php-sitemap-generator
	 * all : génère un site map complet
	 * Sinon contient id de la page à créer
	 * @param string Valeurs possibles
	 */

	public function updateSitemap()
	{

		// Rafraîchit la liste des pages après une modification de pageId notamment 
		$this->buildHierarchy();

		// Actualise la liste des pages pour TinyMCE
		$this->tinyMcePages();

		//require_once "core/vendor/sitemap/SitemapGenerator.php";	

		$timezone = $this->getData(['config', 'timezone']);
		$outputDir = getcwd();
		$sitemap = new \Icamys\SitemapGenerator\SitemapGenerator(helper::baseurl(false), $outputDir);

		// will create also compressed (gzipped) sitemap : option buguée
		// $sitemap->enableCompression();

		// determine how many urls should be put into one file
		// according to standard protocol 50000 is maximum value (see http://www.sitemaps.org/protocol.html)
		$sitemap->setMaxUrlsPerSitemap(50000);

		// sitemap file name
		$sitemap->setSitemapFileName('sitemap.xml');


		// Set the sitemap index file name
		$sitemap->setSitemapIndexFileName('sitemap-index.xml');

		$datetime = new DateTime(date('c'));
		$datetime->format(DateTime::ATOM); // Updated ISO8601

		foreach ($this->getHierarchy(null, null, null) as $parentPageId => $childrenPageIds) {
			// Exclure les barres et les pages non publiques et les pages masquées
			if (
				$this->getData(['page', $parentPageId, 'group']) !== 0 ||
				$this->getData(['page', $parentPageId, 'block']) === 'bar'
			) {
				continue;
			}
			// Page désactivée, traiter les sous-pages sans prendre en compte la page parente.
			if ($this->getData(['page', $parentPageId, 'disable']) !== true) {
				// Cas de la page d'accueil ne pas dupliquer l'URL
				$pageId = ($parentPageId !== $this->getData(['locale', 'homePageId'])) ? $parentPageId : '';
				$sitemap->addUrl('/' . $pageId, $datetime);
			}
			// Articles du blog
			if (
				$this->getData(['page', $parentPageId, 'moduleId']) === 'blog' &&
				!empty($this->getData(['module', $parentPageId]))
			) {
				foreach ($this->getData(['module', $parentPageId, 'posts']) as $articleId => $article) {
					if ($this->getData(['module', $parentPageId, 'posts', $articleId, 'state']) === true) {
						$date = $this->getData(['module', $parentPageId, 'posts', $articleId, 'publishedOn']);
						$sitemap->addUrl('/' . $parentPageId . '/' . $articleId, new DateTime("@{$date}", new DateTimeZone($timezone)));
					}
				}
			}
			// Sous-pages
			foreach ($childrenPageIds as $childKey) {
				if ($this->getData(['page', $childKey, 'group']) !== 0 || $this->getData(['page', $childKey, 'disable']) === true) {
					continue;
				}
				// Cas de la page d'accueil ne pas dupliquer l'URL
				$pageId = ($childKey !== $this->getData(['locale', 'homePageId'])) ? $childKey : '';
				$sitemap->addUrl('/' . $childKey, $datetime);

				// La sous-page est un blog
				if (
					$this->getData(['page', $childKey, 'moduleId']) === 'blog' &&
					!empty($this->getData(['module', $childKey]))
				) {
					foreach ($this->getData(['module', $childKey, 'posts']) as $articleId => $article) {
						if ($this->getData(['module', $childKey, 'posts', $articleId, 'state']) === true) {
							$date = $this->getData(['module', $childKey, 'posts', $articleId, 'publishedOn']);
							$sitemap->addUrl('/' . $childKey . '/' . $articleId, new DateTime("@{$date}", new DateTimeZone($timezone)));
						}
					}
				}
			}
		}

		// Flush all stored urls from memory to the disk and close all necessary tags.
		$sitemap->flush();

		// Move flushed files to their final location. Compress if the option is enabled.
		$sitemap->finalize();

		// Update robots.txt file in output directory

		if ($this->getData(['config', 'seo', 'robots']) === true) {
			if (file_exists('robots.txt')) {
				unlink('robots.txt');
			}
			$sitemap->updateRobots();
		} else {
			file_put_contents('robots.txt', 'User-agent: *' . PHP_EOL . 'Disallow: /');
		}

		// Submit your sitemaps to Google, Yahoo, Bing and Ask.com
		if (empty($this->getData(['config', 'proxyType']) . $this->getData(['config', 'proxyUrl']) . ':' . $this->getData(['config', 'proxyPort']))) {
			$sitemap->submitSitemap();
		}

		return (file_exists('sitemap.xml') && file_exists('robots.txt'));


	}

	/*
	 * Création d'une miniature
	 * Fonction utilisée lors de la mise à jour d'une version 9 à une version 10
	 * @param string $src image source
	 * @param string $dets image destination
	 * @param integer $desired_width largeur demandée
	 */
	function makeThumb($src, $dest, $desired_width)
	{
		// Vérifier l'existence du dossier de destination.
		$fileInfo = pathinfo($dest);
		if (!is_dir($fileInfo['dirname'])) {
			mkdir($fileInfo['dirname'], 0755, true);
		}
		$source_image = '';
		// Type d'image
		switch ($fileInfo['extension']) {
			case 'jpeg':
			case 'jpg':
				$source_image = imagecreatefromjpeg($src);
				break;
			case 'png':
				$source_image = imagecreatefrompng($src);
				break;
			case 'gif':
				$source_image = imagecreatefromgif($src);
				break;
			case 'webp':
				$source_image = imagecreatefromwebp($src);
				break;
		}
		// Image valide
		if ($source_image) {
			$width = imagesx($source_image);
			$height = imagesy($source_image);
			/* find the "desired height" of this thumbnail, relative to the desired width  */
			$desired_height = floor($height * ($desired_width / $width));
			/* create a new, "virtual" image */
			$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
			/* copy source image at a resized size */
			imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
			switch (mime_content_type($src)) {
				case 'image/jpeg':
				case 'image/jpg':
					return (imagejpeg($virtual_image, $dest));
				case 'image/png':
					return (imagepng($virtual_image, $dest));
				case 'image/gif':
					return (imagegif($virtual_image, $dest));
				case 'webp':
					$source_image = imagecreatefromwebp($src);
			}
		} else {
			return (false);
		}
	}


	/**
	 * Envoi un mail
	 * @param string|array $to Destinataire
	 * @param string $subject Sujet
	 * @param string $content Contenu
	 * @return bool
	 */
	public function sendMail($to, $subject, $content, $replyTo = null, $from = '')
	{
		// Layout
		ob_start();
		include 'core/layout/mail.php';
		$layout = ob_get_clean();
		$mail = new PHPMailer\PHPMailer\PHPMailer;
		$mail->setLanguage(substr(self::$i18nUI, 0, 2), 'core/class/phpmailer/i18n/');
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = 'base64';
		// Mail
		try {
			// Paramètres SMTP perso
			if ($this->getdata(['config', 'smtp', 'enable'])) {
				//$mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_CLIENT;
				$mail->isSMTP();
				$mail->SMTPAutoTLS = false;
				$mail->SMTPSecure = false;
				$mail->SMTPAuth = false;
				$mail->Host = $this->getdata(['config', 'smtp', 'host']);
				$mail->Port = (int) $this->getdata(['config', 'smtp', 'port']);
				if ($this->getData(['config', 'smtp', 'auth'])) {
					$mail->SMTPSecure = true;
					$mail->SMTPAuth = true;
					$mail->Username = $this->getData(['config', 'smtp', 'username']);
					$mail->Password = helper::decrypt($this->getData(['config', 'smtp', 'password']), $this->getData(['config', 'smtp', 'host']));
					switch ($this->getData(['config', 'smtp', 'secure'])) {
						case 'ssl':
							$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
							break;
						case 'tls':
							$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
							break;
						default:
							break;
					}
				}
			}

			// Expéditeur
			$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
			$from = $from ? $from : 'no-reply@' . $host;
			$mail->setFrom($from, html_entity_decode($this->getData(['locale', 'title'])));

			// répondre à
			if (is_null($replyTo)) {
				$mail->addReplyTo($from, html_entity_decode($this->getData(['locale', 'title'])));
			} else {
				$mail->addReplyTo($replyTo);
			}

			// Destinataires
			if (is_array($to)) {
				foreach ($to as $userMail) {
					$mail->addAddress($userMail);
				}
			} else {
				$mail->addAddress($to);
			}
			$mail->isHTML(true);
			$mail->Subject = html_entity_decode($subject);
			$mail->Body = $layout;
			$mail->AltBody = strip_tags($content);
			if ($mail->send()) {
				return true;
			} else {
				return $mail->ErrorInfo;
			}
		} catch (Exception $e) {
			return $mail->ErrorInfo;
		}
	}



	/**
	 * Effacer un dossier non vide.
	 * @param string URL du dossier à supprimer
	 */
	public function removeDir($path)
	{
		foreach (new DirectoryIterator($path) as $item) {
			if ($item->isFile())
				@unlink($item->getRealPath());
			if (!$item->isDot() && $item->isDir())
				$this->removeDir($item->getRealPath());
		}
		return (rmdir($path));
	}


	/*
	 * Copie récursive de dossiers
	 * @param string $src dossier source
	 * @param string $dst dossier destination
	 * @return bool
	 */
	public function copyDir($src, $dst)
	{
		// Ouvrir le dossier source
		$dir = opendir($src);
		// Créer le dossier de destination
		if (!is_dir($dst))
			$success = mkdir($dst, 0755, true);
		else
			$success = true;

		// Boucler dans le dossier source en l'absence d'échec de lecture écriture
		while (
			$success
			and $file = readdir($dir)
		) {

			if (($file != '.') && ($file != '..')) {
				if (is_dir($src . '/' . $file)) {
					// Appel récursif des sous-dossiers
					$s = $this->copyDir($src . '/' . $file, $dst . '/' . $file);
					$success = $s || $success;
				} else {
					$s = copy($src . '/' . $file, $dst . '/' . $file);
					$success = $s || $success;
				}
			}
		}
		return $success;
	}


	/**
	 * Fonction de parcours des données de module
	 * @param string $find donnée à rechercher
	 * @param string $replace donnée à remplacer
	 * @param array tableau à analyser
	 * @param int count nombres d'occurrences
	 * @return array avec les valeurs remplacées.
	 */
	public function recursive_array_replace($find, $replace, $array, &$count)
	{
		if (!is_array($array)) {
			return str_replace($find, $replace, $array, $count);
		}

		$newArray = [];
		foreach ($array as $key => $value) {
			$newArray[$key] = $this->recursive_array_replace($find, $replace, $value, $c);
			$count += $c;
		}
		return $newArray;
	}

	/**
	 * Génère une archive d'un dossier et des sous-dossiers
	 * @param string fileName path et nom de l'archive
	 * @param string folder path à zipper
	 * @param array filter dossiers à exclure
	 */
	public function makeZip($fileName, $folder, $filter = [])
	{
		$zip = new ZipArchive();
		$zip->open($fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		//$directory = 'site/';
		$files = new RecursiveIteratorIterator(
			new RecursiveCallbackFilterIterator(
				new RecursiveDirectoryIterator(
					$folder,
					RecursiveDirectoryIterator::SKIP_DOTS
				),
				function ($fileInfo, $key, $iterator) use ($filter) {
					return $fileInfo->isFile() || !in_array($fileInfo->getBaseName(), $filter);
				}
			)
		);
		foreach ($files as $name => $file) {
			if (!$file->isDir()) {
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen(realpath($folder)) + 1);
				$zip->addFile($filePath, str_replace("\\", "/", $relativePath));
			}
		}
		$zip->close();
	}


	/**
	 * Journalisation
	 */
	public function saveLog($message = '')
	{
		// Journalisation
		$dataLog = helper::dateUTF8('%Y %m %d', time()) . ' - ' . helper::dateUTF8('%H:%M', time());
		$dataLog .= helper::getIp($this->getData(['config', 'connect', 'anonymousIp'])) . ';';
		$dataLog .= empty($this->getUser('id')) ? 'visitor;' : $this->getUser('id') . ';';
		$dataLog .= $message ? $this->getUrl() . ';' . $message : $this->getUrl();
		$dataLog .= PHP_EOL;
		if ($this->getData(['config', 'connect', 'log'])) {
			file_put_contents(self::DATA_DIR . 'journal.log', $dataLog, FILE_APPEND);
		}
	}
}
class core extends common
{

	/**
	 * Constructeur du coeur
	 */
	public function __construct()
	{
		parent::__construct();
		// Token CSRF
		if (empty($_SESSION['csrf'])) {
			$_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(128));
		}

		// Fuseau horaire
		self::$timezone = $this->getData(['config', 'timezone']); // Utile pour transmettre le timezone à la classe helper
		date_default_timezone_set(self::$timezone);
		// Supprime les fichiers temporaires
		$lastClearTmp = mktime(0, 0, 0);
		if ($lastClearTmp > $this->getData(['core', 'lastClearTmp']) + 86400) {
			$iterator = new DirectoryIterator(self::TEMP_DIR);
			foreach ($iterator as $fileInfos) {
				if (
					$fileInfos->isFile() &&
					$fileInfos->getBasename() !== '.htaccess' &&
					$fileInfos->getBasename() !== '.gitkeep'
				) {
					@unlink($fileInfos->getPathname());
				}
			}
			// Date de la dernière suppression
			$this->setData(['core', 'lastClearTmp', $lastClearTmp]);
			// Enregistre les données
			//$this->SaveData();
		}
		// Backup automatique des données
		$lastBackup = mktime(0, 0, 0);
		if (
			$this->getData(['config', 'autoBackup'])
			and $lastBackup > $this->getData(['core', 'lastBackup']) + 86400
			and $this->getData(['user']) // Pas de backup pendant l'installation
		) {
			// Copie des fichier de données
			helper::autoBackup(self::BACKUP_DIR, ['backup', 'tmp', 'file']);
			// Date du dernier backup
			$this->setData(['core', 'lastBackup', $lastBackup]);
			// Supprime les backups de plus de 30 jours
			$iterator = new DirectoryIterator(self::BACKUP_DIR);
			foreach ($iterator as $fileInfos) {
				if (
					$fileInfos->isFile()
					and $fileInfos->getBasename() !== '.htaccess'
					and $fileInfos->getMTime() + (86400 * 30) < time()
				) {
					@unlink($fileInfos->getPathname());
				}
			}
		}

		// Crée le fichier de personnalisation avancée
		if (file_exists(self::DATA_DIR . 'custom.css') === false) {
			file_put_contents(self::DATA_DIR . 'custom.css', file_get_contents('core/module/theme/resource/custom.css'));
			chmod(self::DATA_DIR . 'custom.css', 0755);
		}
		// Crée le fichier de personnalisation
		if (file_exists(self::DATA_DIR . 'theme.css') === false) {
			file_put_contents(self::DATA_DIR . 'theme.css', '');
			chmod(self::DATA_DIR . 'theme.css', 0755);
		}
		// Crée le fichier de personnalisation de l'administration
		if (file_exists(self::DATA_DIR . 'admin.css') === false) {
			file_put_contents(self::DATA_DIR . 'admin.css', '');
			chmod(self::DATA_DIR . 'admin.css', 0755);
		}

		// Check la version rafraichissement du theme
		$cssVersion = preg_split('/\*+/', file_get_contents(self::DATA_DIR . 'theme.css'));
		if (empty($cssVersion[1]) or $cssVersion[1] !== md5(json_encode($this->getData(['theme'])))) {
			// Version
			$css = '/*' . md5(json_encode($this->getData(['theme']))) . '*/';


			/**
			 * Import des polices de caractères
			 * A partir du CDN
			 * ou dans le dossier site/file/source/fonts
			 * ou pas du tout si fonte webSafe
			 */

			// Fonts disponibles
			$fontsAvailable['files'] = $this->getData(['font', 'files']);
			$fontsAvailable['imported'] = $this->getData(['font', 'imported']);
			$fontsAvailable['websafe'] = self::$fontsWebSafe;

			// Fontes installées
			$fonts = [
				$this->getData(['theme', 'text', 'font']),
				$this->getData(['theme', 'title', 'font']),
				$this->getData(['theme', 'header', 'font']),
				$this->getData(['theme', 'menu', 'font']),
				$this->getData(['theme', 'footer', 'font'])
			];
			// Suppression des polices identiques
			$fonts = array_unique($fonts);

			/**
			 * Charge les fontes websafe
			 */
			$fontFile = '';
			foreach ($fonts as $fontId) {
				if (isset($fontsAvailable['websafe'][$fontId])) {
					$fonts[$fontId] = $fontsAvailable['websafe'][$fontId]['font-family'];
				}
			}

			/**
			 * Chargement des polices en ligne dans un fichier font.html inclus dans main.php
			 */
			$fontFile = '';
			$gf = false;
			foreach ($fonts as $fontId) {
				if (isset($fontsAvailable['imported'][$fontId])) {
					$fontFile .= '<link href="' . $fontsAvailable['imported'][$fontId]['resource'] . '" rel="stylesheet">';
					// Tableau pour la construction de la feuille de style
					$fonts[$fontId] = $fontsAvailable['imported'][$fontId]['font-family'];
					$gf = strpos($fontsAvailable['imported'][$fontId]['resource'], 'fonts.googleapis.com') === false ? $gf || false : $gf || true;
				}
			}
			// Ajoute le préconnect des fontes Googles.
			$fontFile = $gf ? '<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . $fontFile
				: $fontFile;
			// Enregistre la personnalisation
			if (!is_dir(self::DATA_DIR . 'font')) {
				mkdir(self::DATA_DIR . 'font');
			}
			file_put_contents(self::DATA_DIR . 'font/font.html', $fontFile);

			/**
			 * Fontes installées localement
			 */
			foreach ($fonts as $fontId) {
				// Validité du tableau :
				if (isset($fontsAvailable['files'][$fontId])) {
					if (file_exists(self::DATA_DIR . 'font/' . $fontId)) {
						// Chargement de la police
						$css .= '@font-face {font-family:"' . $fontsAvailable['files'][$fontId]['font-family'] . '";';
						$css .= 'src: url("' . helper::baseUrl(false) . self::DATA_DIR . 'font/' . $fontsAvailable['files'][$fontId]['resource'] . '");}';
						// Tableau pour la construction de la feuille de style
						$fonts[$fontId] = $fontsAvailable['files'][$fontId]['font-family'];
					} else {
						// Le fichier de font n'est pas disponible, fonte par défaut
						$fonts[$fontId] = 'verdana';
					}
				}
			}

			// Fond du body
			$colors = helper::colorVariants($this->getData(['theme', 'body', 'backgroundColor']));
			// Body
			$css .= 'body{font-family:' . $fonts[$this->getData(['theme', 'text', 'font'])] . ';}';
			if ($themeBodyImage = $this->getData(['theme', 'body', 'image'])) {
				// Image dans html pour éviter les déformations.
				$css .= 'html {background-image:url("../file/source/' . $themeBodyImage . '");background-position:' . $this->getData(['theme', 'body', 'imagePosition']) . ';background-attachment:' . $this->getData(['theme', 'body', 'imageAttachment']) . ';background-size:' . $this->getData(['theme', 'body', 'imageSize']) . ';background-repeat:' . $this->getData(['theme', 'body', 'imageRepeat']) . '}';
				// Couleur du body transparente
				$css .= 'body {background-color: rgba(0,0,0,0)}';
			} else {
				// Pas d'image couleur du body
				$css .= 'html {background-color:' . $colors['normal'] . ';}';
			}
			// Icône BacktoTop
			$css .= '#backToTop {background-color:' . $this->getData(['theme', 'body', 'toTopbackgroundColor']) . ';color:' . $this->getData(['theme', 'body', 'toTopColor']) . ';}';
			// Site
			$colors = helper::colorVariants($this->getData(['theme', 'text', 'linkColor']));
			$css .= 'a{color:' . $colors['normal'] . '}';
			// Couleurs de site dans TinyMCe
			$css .= 'div.mce-edit-area {font-family:' . $fonts[$this->getData(['theme', 'text', 'font'])] . ';}';
			// Site dans TinyMCE
			$css .= '.editorWysiwyg, .editorWysiwygComment {background-color:' . $this->getData(['theme', 'site', 'backgroundColor']) . ';}';
			$css .= 'span.mce-text{background-color: unset !important;}';
			$css .= 'body,.row > div{font-size:' . $this->getData(['theme', 'text', 'fontSize']) . '}';
			$css .= 'body{color:' . $this->getData(['theme', 'text', 'textColor']) . '}';
			$css .= 'select,input[type=password],input[type=email],input[type=text],input[type=date],input[type=time],input[type=week],input[type=month],input[type=datetime-local],.inputFile,select,textarea{color:' . $this->getData(['theme', 'text', 'textColor']) . ';background-color:' . $this->getData(['theme', 'site', 'backgroundColor']) . ';}';
			// spécifiques au module de blog
			$css .= '.blogDate {color:' . $this->getData(['theme', 'text', 'textColor']) . ';}.blogPicture img{border:1px solid ' . $this->getData(['theme', 'text', 'textColor']) . '; box-shadow: 1px 1px 5px ' . $this->getData(['theme', 'text', 'textColor']) . ';}';
			// Couleur fixée dans admin.css
			$css .= '.container {max-width:' . $this->getData(['theme', 'site', 'width']) . '}';
			$margin = $this->getData(['theme', 'site', 'margin']) ? '0' : '20px';
			// Marge supplémentaire lorsque le pied de page est fixe
			if (
				$this->getData(['theme', 'footer', 'fixed']) === true &&
				$this->getData(['theme', 'footer', 'position']) === 'body'
			) {

				$marginBottomLarge = ((str_replace('px', '', $this->getData(['theme', 'footer', 'height'])) * 2) + 31) . 'px';
				$marginBottomSmall = ((str_replace('px', '', $this->getData(['theme', 'footer', 'height'])) * 2) + 93) . 'px';
			} else {
				$marginBottomSmall = $margin;
				$marginBottomLarge = $margin;
			}
			$css .= $this->getData(['theme', 'site', 'width']) === '100%'
				? '@media (min-width: 769px) {#site{margin:0 auto ' . $marginBottomLarge . ' 0 !important;}}@media (max-width: 768px) {#site{margin:0 auto ' . $marginBottomSmall . ' 0 !important;}}#site.light{margin:5% auto !important;} body{margin:0 auto !important;}  #bar{margin:0 auto !important;} body > header{margin:0 auto !important;} body > nav {margin: 0 auto !important;} body > footer {margin:0 auto !important;}'
				: '@media (min-width: 769px) {#site{margin: ' . $margin . ' auto ' . $marginBottomLarge . ' auto !important;}}@media (max-width: 768px) {#site{margin: ' . $margin . ' auto ' . $marginBottomSmall . ' auto !important;}}#site.light{margin: 5% auto !important;} body{margin:0px 10px;}  #bar{margin: 0 -10px;} body > header{margin: 0 -10px;} body > nav {margin: 0 -10px;} body > footer {margin: 0 -10px;} ';
			$css .= $this->getData(['theme', 'site', 'width']) === '750px'
				? '.button, button{font-size:0.8em;}'
				: '';
			$css .= '#site{background-color:' . $this->getData(['theme', 'site', 'backgroundColor']) . ';border-radius:' . $this->getData(['theme', 'site', 'radius']) . ';box-shadow:' . $this->getData(['theme', 'site', 'shadow']) . ' #212223;}';
			$colors = helper::colorVariants($this->getData(['theme', 'button', 'backgroundColor']));
			$css .= '.speechBubble,.button,.button:hover,button[type=submit],.pagination a,.pagination a:hover,input[type=checkbox]:checked + label:before,input[type=radio]:checked + label:before,.helpContent{background-color:' . $colors['normal'] . ';color:' . $colors['text'] . '}';
			$css .= '.helpButton span{color:' . $colors['normal'] . '}';
			$css .= 'input[type=text]:hover,input[type=date]:hover,input[type=time]:hover,input[type=week]:hover,input[type=month]:hover,input[type=datetime-local]:hover,input[type=password]:hover,.inputFile:hover,select:hover,textarea:hover{border-color:' . $colors['normal'] . '}';
			$css .= '.speechBubble:before{border-color:' . $colors['normal'] . ' transparent transparent transparent}';
			$css .= '.button:hover,button[type=submit]:hover,.pagination a:hover,input[type=checkbox]:not(:active):checked:hover + label:before,input[type=checkbox]:active + label:before,input[type=radio]:checked:hover + label:before,input[type=radio]:not(:checked):active + label:before{background-color:' . $colors['darken'] . '}';
			$css .= '.helpButton span:hover{color:' . $colors['darken'] . '}';
			$css .= '.button:active,button[type=submit]:active,.pagination a:active{background-color:' . $colors['veryDarken'] . '}';
			$colors = helper::colorVariants($this->getData(['theme', 'title', 'textColor']));
			$css .= 'h1,h2,h3,h4,h5,h6,h1 a,h2 a,h3 a,h4 a,h5 a,h6 a{color:' . $colors['normal'] . ';font-family:' . $fonts[$this->getData(['theme', 'title', 'font'])] . ';font-weight:' . $this->getData(['theme', 'title', 'fontWeight']) . ';text-transform:' . $this->getData(['theme', 'title', 'textTransform']) . '}';
			$css .= 'h1 a:hover,h2 a:hover,h3 a:hover,h4 a:hover,h5 a:hover,h6 a:hover{color:' . $colors['darken'] . '}';
			// Les blocs
			$colors = helper::colorVariants($this->getData(['theme', 'block', 'backgroundColor']));
			$css .= '.block {border: 1px solid ' . $this->getdata(['theme', 'block', 'borderColor']) . ';}.block h4 {background-color:' . $colors['normal'] . ';color:' . $colors['text'] . ';}';

			// Bannière

			// Eléments communs
			if ($this->getData(['theme', 'header', 'margin'])) {
				if ($this->getData(['theme', 'menu', 'position']) === 'site-first') {
					$css .= 'header{margin:0 20px}';
				} else {
					$css .= 'header{margin:20px 20px 0 20px}';
				}
			}
			$colors = helper::colorVariants($this->getData(['theme', 'header', 'backgroundColor']));
			$css .= 'header{background-color:' . $colors['normal'] . ';}';

			// Bannière de type papier peint
			if ($this->getData(['theme', 'header', 'feature']) === 'wallpaper') {
				$css .= 'header{background-size:' . $this->getData(['theme', 'header', 'imageContainer']) . '}';
				$css .= 'header{background-color:' . $colors['normal'];

				// Valeur de hauteur traditionnelle
				$css .= ';height:' . $this->getData(['theme', 'header', 'height']) . ';line-height:' . $this->getData(['theme', 'header', 'height']);

				$css .= ';text-align:' . $this->getData(['theme', 'header', 'textAlign']) . '}';
				if ($themeHeaderImage = $this->getData(['theme', 'header', 'image'])) {
					$css .= 'header{background-image:url("../file/source/' . $themeHeaderImage . '");background-position:' . $this->getData(['theme', 'header', 'imagePosition']) . ';background-repeat:' . $this->getData(['theme', 'header', 'imageRepeat']) . '}';
				}
				$colors = helper::colorVariants($this->getData(['theme', 'header', 'textColor']));
				$css .= 'header span{color:' . $colors['normal'] . ';font-family:' . $fonts[$this->getData(['theme', 'header', 'font'])] . ';font-weight:' . $this->getData(['theme', 'header', 'fontWeight']) . ';font-size:' . $this->getData(['theme', 'header', 'fontSize']) . ';text-transform:' . $this->getData(['theme', 'header', 'textTransform']) . '}';
			}

			// Bannière au Contenu HTML
			if ($this->getData(['theme', 'header', 'feature']) === 'feature') {
				// Hauteur de la taille du contenu perso
				$css .= 'header {height:' . $this->getData(['theme', 'header', 'height']) . '; min-height:' . $this->getData(['theme', 'header', 'height']) . ';overflow: hidden;}';
			}

			// Menu
			$colors = helper::colorVariants($this->getData(['theme', 'menu', 'backgroundColor']));
			$css .= 'nav,nav.navMain a{background-color:' . $colors['normal'] . '}';
			$css .= 'nav a,#toggle span,nav a:hover{color:' . $this->getData(['theme', 'menu', 'textColor']) . '}';
			$css .= 'nav a:hover{background-color:' . $colors['darken'] . '}';
			$css .= 'nav a.active{color:' . $this->getData(['theme', 'menu', 'activeTextColor']) . ';}';
			if ($this->getData(['theme', 'menu', 'activeColorAuto']) === true) {
				$css .= 'nav a.active{background-color:' . $colors['veryDarken'] . '}';
			} else {
				$css .= 'nav a.active{background-color:' . $this->getData(['theme', 'menu', 'activeColor']) . '}';
			}
			$css .= 'nav #burgerText{color:' . $colors['text'] . '}';
			// Sous menu
			$colors = helper::colorVariants($this->getData(['theme', 'menu', 'backgroundColorSub']));
			$css .= 'nav .navSub a{background-color:' . $colors['normal'] . '}';
			$css .= 'nav .navMain a.active {border-radius:' . $this->getData(['theme', 'menu', 'radius']) . '}';
			$css .= '#menu{text-align:' . $this->getData(['theme', 'menu', 'textAlign']) . '}';
			if ($this->getData(['theme', 'menu', 'margin'])) {
				if (
					$this->getData(['theme', 'menu', 'position']) === 'site-first'
					or $this->getData(['theme', 'menu', 'position']) === 'site-second'
				) {
					$css .= 'nav{padding:10px 10px 0 10px;}';
				} else {
					$css .= 'nav{padding:0 10px}';
				}
			} else {
				$css .= 'nav{margin:0}';
			}
			if (
				$this->getData(['theme', 'menu', 'position']) === 'top'
			) {
				$css .= 'nav{padding:0 10px;}';
			}

			$css .= '#toggle span,#menu a{padding:' . $this->getData(['theme', 'menu', 'height']) . ';font-family:' . $fonts[$this->getData(['theme', 'menu', 'font'])] . ';font-weight:' . $this->getData(['theme', 'menu', 'fontWeight']) . ';font-size:' . $this->getData(['theme', 'menu', 'fontSize']) . ';text-transform:' . $this->getData(['theme', 'menu', 'textTransform']) . '}';
			// Pied de page
			$colors = helper::colorVariants($this->getData(['theme', 'footer', 'backgroundColor']));
			if ($this->getData(['theme', 'footer', 'margin'])) {
				$css .= 'footer{padding:0 20px;}';
			} else {
				$css .= 'footer{padding:0}';
			}

			$css .= 'footer span, #footerText > p {color:' . $this->getData(['theme', 'footer', 'textColor']) . ';font-family:' . $fonts[$this->getData(['theme', 'footer', 'font'])] . ';font-weight:' . $this->getData(['theme', 'footer', 'fontWeight']) . ';font-size:' . $this->getData(['theme', 'footer', 'fontSize']) . ';text-transform:' . $this->getData(['theme', 'footer', 'textTransform']) . '}';
			$css .= 'footer {background-color:' . $colors['normal'] . ';color:' . $this->getData(['theme', 'footer', 'textColor']) . '}';
			$css .= 'footer a{color:' . $this->getData(['theme', 'footer', 'textColor']) . '}';
			$css .= 'footer #footersite > div {margin:' . $this->getData(['theme', 'footer', 'height']) . ' 0}';

			$css .= 'footer #footerbody > div  {margin:' . $this->getData(['theme', 'footer', 'height']) . ' 0}';
			$css .= '@media (max-width: 768px) {footer #footerbody > div { padding: 2px }}';
			$css .= '#footerSocials{text-align:' . $this->getData(['theme', 'footer', 'socialsAlign']) . '}';
			$css .= '#footerText > p {text-align:' . $this->getData(['theme', 'footer', 'textAlign']) . '}';
			$css .= '#footerCopyright{text-align:' . $this->getData(['theme', 'footer', 'copyrightAlign']) . '}';

			// Enregistre les fontes
			if (!is_dir(self::DATA_DIR . 'font')) {
				mkdir(self::DATA_DIR . 'font');
			}
			file_put_contents(self::DATA_DIR . 'font/font.html', $fontFile);

			// Enregistre la personnalisation
			file_put_contents(self::DATA_DIR . 'theme.css', $css);

			// Effacer le cache pour tenir compte de la couleur de fond TinyMCE
			header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
		}

		// Check la version rafraichissement du theme admin
		$cssVersion = preg_split('/\*+/', file_get_contents(self::DATA_DIR . 'admin.css'));
		if (empty($cssVersion[1]) or $cssVersion[1] !== md5(json_encode($this->getData(['admin'])))) {

			// Version
			$css = '/*' . md5(json_encode($this->getData(['admin']))) . '*/';

			// Fonts disponibles
			$fontsAvailable['files'] = $this->getData(['font', 'files']);
			$fontsAvailable['imported'] = $this->getData(['font', 'imported']);
			$fontsAvailable['websafe'] = self::$fontsWebSafe;

			/**
			 * Import des polices de caractères
			 * A partir du CDN ou dans le dossier site/file/source/fonts
			 */
			$fonts = [
				$this->getData(['admin', 'fontText']),
				$this->getData(['admin', 'fontTitle']),
			];
			// Suppression des polices identiques
			$fonts = array_unique($fonts);

			/**
			 * Charge les fontes websafe
			 */
			$fontFile = '';
			foreach ($fonts as $fontId) {
				if (isset($fontsAvailable['websafe'][$fontId])) {
					$fonts[$fontId] = $fontsAvailable['websafe'][$fontId]['font-family'];
				}
			}

			/**
			 * Chargement des polices en ligne dans un fichier font.html inclus dans main.php
			 */
			$fontFile = '';
			foreach ($fonts as $fontId) {
				if (isset($fontsAvailable['imported'][$fontId])) {
					$fontFile .= '<link href="' . $fontsAvailable['imported'][$fontId]['resource'] . '" rel="stylesheet">';
					// Tableau pour la construction de la feuille de style
					$fonts[$fontId] = $fontsAvailable['imported'][$fontId]['font-family'];
				}
			}
			// Enregistre la personnalisation
			file_put_contents(self::DATA_DIR . 'font/font.html', $fontFile);

			/**
			 * Fontes installées localement
			 */
			foreach ($fonts as $fontId) {
				// Validité du tableau :
				if (isset($fontsAvailable['files'][$fontId])) {
					if (file_exists(self::DATA_DIR . 'font/' . $fontId)) {
						// Chargement de la police
						$css .= '@font-face {font-family:"' . $fontsAvailable['files'][$fontId]['font-family'] . '";';
						$css .= 'src: url("' . helper::baseUrl(false) . self::DATA_DIR . 'font/' . $fontsAvailable['files'][$fontId]['resource'] . '");}';
						// Tableau pour la construction de la feuille de style
						$fonts[$fontId] = $fontsAvailable['files'][$fontId]['font-family'];
					} else {
						// Le fichier de font n'est pas disponible, fonte par défaut
						$fonts[$fontId] = 'verdana';
					}
				}
			}

			// Thème Administration
			$colors = helper::colorVariants($this->getData(['admin', 'backgroundColor']));
			$css .= '#site{background-color:' . $colors['normal'] . ';}';
			$css .= 'p, div, label, select, input, table, span {font-family:' . $fonts[$this->getData(['admin', 'fontText'])] . '}';
			$css .= 'body,.row > div {font-size:' . $this->getData(['admin', 'fontSize']) . '}';
			$css .= 'body h1, h2, h3, h4 a, h5, h6 {font-family:' . $fonts[$this->getData(['admin', 'fontTitle'])] . ';color:' . $this->getData(['admin', 'colorTitle']) . ';}';

			// TinyMCE
			$colors = helper::colorVariants($this->getData(['admin', 'colorText']));
			$css .= 'body:not(.editorWysiwyg), body:not(editorWysiwygComment),span .zwiico-help {color:' . $colors['normal'] . ';}';
			$css .= 'table thead tr, table thead tr .zwiico-help{ background-color:' . $colors['normal'] . '; color:' . $colors['text'] . ';}';
			$css .= 'table thead th { color:' . $colors['text'] . ';}';
			$colors = helper::colorVariants($this->getData(['admin', 'backgroundColorButton']));
			$css .= 'input[type=checkbox]:checked + label::before,.speechBubble{background-color:' . $colors['normal'] . ';color:' . $colors['text'] . ';}';
			$css .= '.speechBubble::before {border-color:' . $colors['normal'] . ' transparent transparent transparent;}';
			$css .= '.button {background-color:' . $colors['normal'] . ';color:' . $colors['text'] . ';}.button:hover {background-color:' . $colors['darken'] . ';color:' . $colors['text'] . ';}.button:active {background-color:' . $colors['veryDarken'] . ';color:' . $colors['text'] . ';}';
			$colors = helper::colorVariants($this->getData(['admin', 'backgroundColorButtonGrey']));
			$css .= '.button.buttonGrey {background-color: ' . $colors['normal'] . ';color: ' . $colors['text'] . ';}.button.buttonGrey:hover {background-color:' . $colors['darken'] . ';color:' . $colors['text'] . ';}.button.buttonGrey:active {background-color:' . $colors['veryDarken'] . ';color:' . $colors['text'] . ';}';
			$colors = helper::colorVariants($this->getData(['admin', 'backgroundColorButtonRed']));
			$css .= '.button.buttonRed {background-color: ' . $colors['normal'] . ';color: ' . $colors['text'] . ';}.button.buttonRed:hover {background-color:' . $colors['darken'] . ';color:' . $colors['text'] . ';}.button.buttonRed:active {background-color:' . $colors['veryDarken'] . ';color:' . $colors['text'] . ';}';
			$colors = helper::colorVariants($this->getData(['admin', 'backgroundColorButtonHelp']));
			$css .= '.button.buttonHelp {background-color: ' . $colors['normal'] . ';color: ' . $colors['text'] . ';}.button.buttonHelp:hover {background-color:' . $colors['darken'] . ';color:' . $colors['text'] . ';}.button.buttonHelp:active {background-color:' . $colors['veryDarken'] . ';color:' . $colors['text'] . ';}';
			$colors = helper::colorVariants($this->getData(['admin', 'backgroundColorButtonGreen']));
			$css .= '.button.buttonGreen, button[type=submit] {background-color: ' . $colors['normal'] . ';color: ' . $colors['text'] . ';}.button.buttonGreen:hover, button[type=submit]:hover {background-color: ' . $colors['darken'] . ';color: ' . $colors['text'] . ';}.button.buttonGreen:active, button[type=submit]:active {background-color: ' . $colors['darken'] . ';color: ' . $colors['text'] . ';}';
			$colors = helper::colorVariants($this->getData(['admin', 'backgroundBlockColor']));
			$css .= '.buttonTab, .block {border: 1px solid ' . $this->getData(['admin', 'borderBlockColor']) . ';}.buttonTab, .block h4 {background-color: ' . $colors['normal'] . ';color:' . $colors['text'] . ';}';
			$css .= 'table tr,input[type=email],input[type=date],input[type=time],input[type=month],input[type=week],input[type=datetime-local],input[type=text],input[type=password],select:not(#barSelectLanguage),select:not(#barSelectPage),textarea:not(.editorWysiwyg), textarea:not(.editorWysiwygComment),.inputFile{background-color: ' . $colors['normal'] . ';color:' . $colors['text'] . ';border: 1px solid ' . $this->getData(['admin', 'borderBlockColor']) . ';}';
			// Bordure du contour TinyMCE
			$css .= '.mce-tinymce{border: 1px solid ' . $this->getData(['admin', 'borderBlockColor']) . '!important;}';
			// Enregistre la personnalisation
			file_put_contents(self::DATA_DIR . 'admin.css', $css);
		}
	}
	/**
	 * Auto-chargement des classes
	 * @param string $className Nom de la classe à charger
	 */
	public static function autoload($className)
	{

		$classPath = strtolower($className) . '/' . strtolower($className) . '.php';
		// Module du coeur
		if (is_readable('core/module/' . $classPath)) {
			require 'core/module/' . $classPath;
		}
		// Module
		elseif (is_readable(self::MODULE_DIR . $classPath)) {
			require self::MODULE_DIR . $classPath;
		}
		// Librairie
		elseif (is_readable('core/vendor/' . $classPath)) {
			require 'core/vendor/' . $classPath;
		}
	}

	/**
	 * Routage des modules
	 */
	public function router()
	{

		$layout = new layout($this);

		// Installation
		if (
			$this->getData(['user']) === []
			and $this->getUrl(0) !== 'install'
		) {
			http_response_code(302);
			header('Location:' . helper::baseUrl() . 'install');
			exit();
		}

		// Journalisation
		$this->saveLog();

		// Force la déconnexion des membres bannis ou d'une seconde session
		if (
			$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
			and ($this->getUser('group') === self::GROUP_BANNED
				or ($_SESSION['csrf'] !== $this->getData(['user', $this->getUser('id'), 'accessCsrf'])
					and $this->getData(['config', 'connect', 'autoDisconnect']) === true)
			)
		) {
			$user = new user;
			$user->logout();
		}
		// Mode maintenance
		if (
			$this->getData(['config', 'maintenance'])
			and in_array($this->getUrl(0), ['maintenance', 'user']) === false
			and $this->getUrl(1) !== 'login'
			and ($this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
				or ($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
					and $this->getUser('group') < self::GROUP_ADMIN
				)
			)
		) {
			// Déconnexion
			$user = new user;
			$user->logout();
			// Redirection
			http_response_code(302);
			header('Location:' . helper::baseUrl() . 'maintenance');
			exit();
		}

		// Pour éviter une 404 sur une langue étrangère, bascule dans la langue correcte.
		if (is_null($this->getData(['page', $this->getUrl(0)]))) {
			foreach (self::$languages as $key => $value) {
				if (
					is_dir(self::DATA_DIR . $key) &&
					file_exists(self::DATA_DIR . $key . '/page.json')
				) {
					$pagesId = json_decode(file_get_contents(self::DATA_DIR . $key . '/page.json'), true);
					if (array_key_exists($this->getUrl(0), $pagesId['page'])) {
						$_SESSION['ZWII_CONTENT'] = $key;
						header('Refresh:0; url=' . helper::baseUrl() . $this->getUrl(0));
						exit();
					}
				}
			}
		}

		// Check l'accès à la page
		$access = null;
		if ($this->getData(['page', $this->getUrl(0)]) !== null) {
			if (
				$this->getData(['page', $this->getUrl(0), 'group']) === self::GROUP_VISITOR
				or ($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
					and $this->getUser('group') >= $this->getData(['page', $this->getUrl(0), 'group'])
				)
			) {
				$access = true;
			} else {
				if ($this->getUrl(0) === $this->getData(['locale', 'homePageId'])) {
					$access = 'login';
				} else {
					$access = false;
				}
			}
			// Empêcher l'accès aux pages désactivées par URL directe
			if (
				($this->getData(['page', $this->getUrl(0), 'disable']) === true
					and $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
				) or ($this->getData(['page', $this->getUrl(0), 'disable']) === true
					and $this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
					and $this->getUser('group') < self::GROUP_MODERATOR
				)
			) {
				$access = false;
			}
		}

		/**
		 * Contrôle si la page demandée est en édition ou accès à la gestion du site
		 * conditions de blocage :
		 * - Les deux utilisateurs qui accèdent à la même page sont différents
		 * - les URLS sont identiques
		 * - Une partie de l'URL fait partie  de la liste de filtrage (édition d'un module etc..)
		 * - L'édition est ouverte depuis un temps dépassé, on considère que la page est restée ouverte et qu'elle ne sera pas validée
		 */
		$accessInfo['userName'] = '';
		$accessInfo['pageId'] = '';
		if ($this->getData(['user'])) {
			foreach ($this->getData(['user']) as $userId => $userIds) {
				if (!is_null($this->getData(['user', $userId, 'accessUrl']))) {
					$t = explode('/', $this->getData(['user', $userId, 'accessUrl']));
				}
				if (
					$this->getUser('id') &&
					$userId !== $this->getUser('id') &&
					$this->getData(['user', $userId, 'accessUrl']) === $this->getUrl() &&
					array_intersect($t, self::$accessList) &&
					array_intersect($t, self::$accessExclude) !== false &&
					time() < $this->getData(['user', $userId, 'accessTimer']) + self::ACCESS_TIMER
				) {
					$access = false;
					$accessInfo['userName'] = $this->getData(['user', $userId, 'lastname']) . ' ' . $this->getData(['user', $userId, 'firstname']);
					$accessInfo['pageId'] = end($t);
				}
			}
		}
		// Accès concurrent stocke la page visitée
		if (
			$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
			&& $this->getUser('id')
		) {
			$this->setData(['user', $this->getUser('id'), 'accessUrl', $this->getUrl()]);
			$this->setData(['user', $this->getUser('id'), 'accessTimer', time()]);
		}
		// Breadcrumb
		$title = $this->getData(['page', $this->getUrl(0), 'title']);
		if (
			!empty($this->getData(['page', $this->getUrl(0), 'parentPageId'])) &&
			$this->getData(['page', $this->getUrl(0), 'breadCrumb'])
		) {
			$title = '<a href="' . helper::baseUrl() .
				$this->getData(['page', $this->getUrl(0), 'parentPageId']) .
				'">' .
				ucfirst($this->getData(['page', $this->getData(['page', $this->getUrl(0), 'parentPageId']), 'title'])) .
				'</a> &#8250; ' .
				$this->getData(['page', $this->getUrl(0), 'title']);
		}


		// Importe le style de la page principale
		$inlineStyle[] = $this->getData(['page', $this->getUrl(0), 'css']) === null ? '' : $this->getData(['page', $this->getUrl(0), 'css']);
		// Importe le script de la page principale
		$inlineScript[] = $this->getData(['page', $this->getUrl(0), 'js']) === null ? '' : $this->getData(['page', $this->getUrl(0), 'js']);

		// Importe le contenu, le CSS et le script des barres
		$contentRight = $this->getData(['page', $this->getUrl(0), 'barRight']) ? $this->getPage($this->getData(['page', $this->getUrl(0), 'barRight']), self::$i18nContent) : '';
		$inlineStyle[] = $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'css']) === null ? '' : $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'css']);
		$inlineScript[] = $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'js']) === null ? '' : $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'js']);
		$contentLeft = $this->getData(['page', $this->getUrl(0), 'barLeft']) ? $this->getPage($this->getData(['page', $this->getUrl(0), 'barLeft']), self::$i18nContent) : '';
		$inlineStyle[] = $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barLeft']), 'css']) === null ? '' : $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barLeft']), 'css']);
		$inlineScript[] = $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barLeft']), 'js']) === null ? '' : $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barLeft']), 'js']);


		// Importe la page simple sans module ou avec un module inexistant
		if (
			$this->getData(['page', $this->getUrl(0)]) !== null
			and ($this->getData(['page', $this->getUrl(0), 'moduleId']) === ''
				or !class_exists($this->getData(['page', $this->getUrl(0), 'moduleId']))
			)
			and $access
		) {

			// Importe le CSS de la page principale

			$this->addOutput([
				'title' => $title,
				'content' => $this->getPage($this->getUrl(0), self::$i18nContent),
				'metaDescription' => $this->getData(['page', $this->getUrl(0), 'metaDescription']),
				'metaTitle' => $this->getData(['page', $this->getUrl(0), 'metaTitle']),
				'typeMenu' => $this->getData(['page', $this->getUrl(0), 'typeMenu']),
				'iconUrl' => $this->getData(['page', $this->getUrl(0), 'iconUrl']),
				'disable' => $this->getData(['page', $this->getUrl(0), 'disable']),
				'contentRight' => $contentRight,
				'contentLeft' => $contentLeft,
				'inlineStyle' => $inlineStyle,
				'inlineScript' => $inlineScript,
			]);

		}
		// Importe le module
		else {
			// Id du module, et valeurs en sortie de la page s'il s'agit d'un module de page

			if ($access and $this->getData(['page', $this->getUrl(0), 'moduleId'])) {
				$moduleId = $this->getData(['page', $this->getUrl(0), 'moduleId']);

				// Construit un meta absent
				$metaDescription = $this->getData(['page', $this->getUrl(0), 'moduleId']) === 'blog' && !empty($this->getUrl(1)) && in_array($this->getUrl(1), $this->getData(['module']))
					? strip_tags(substr($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'content']), 0, 159))
					: $this->getData(['page', $this->getUrl(0), 'metaDescription']);

				// Importe le CSS de la page principale
				$pageContent = $this->getPage($this->getUrl(0), self::$i18nContent);

				$this->addOutput([
					'title' => $title,
					// Meta description = 160 premiers caractères de l'article
					'content' => $pageContent,
					'metaDescription' => $metaDescription,
					'metaTitle' => $this->getData(['page', $this->getUrl(0), 'metaTitle']),
					'typeMenu' => $this->getData(['page', $this->getUrl(0), 'typeMenu']),
					'iconUrl' => $this->getData(['page', $this->getUrl(0), 'iconUrl']),
					'disable' => $this->getData(['page', $this->getUrl(0), 'disable']),
					'contentRight' => $contentRight,
					'contentLeft' => $contentLeft,
					'inlineStyle' => $inlineStyle,
					'inlineScript' => $inlineScript,
				]);
			} else {
				$moduleId = $this->getUrl(0);
				$pageContent = '';
			}

			// Check l'existence du module
			if (class_exists($moduleId)) {
				/** @var common $module */
				$module = new $moduleId;

				// Check l'existence de l'action
				$action = '';
				$ignore = true;
				if (!is_null($this->getUrl(1))) {
					foreach (explode('-', $this->getUrl(1)) as $actionPart) {
						if ($ignore) {
							$action .= $actionPart;
							$ignore = false;
						} else {
							$action .= ucfirst($actionPart);
						}
					}
				}
				$action = array_key_exists($action, $module::$actions) ? $action : 'index';
				if (array_key_exists($action, $module::$actions)) {
					$module->$action();
					$output = $module->output;
					// Check le groupe de l'utilisateur
					if (
						($module::$actions[$action] === self::GROUP_VISITOR
							or ($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
								and $this->getUser('group') >= $module::$actions[$action]
								and $this->getUser('permission', $moduleId, $action)
							)
						)
						and $output['access'] === true
					) {
						// Enregistrement du contenu de la méthode POST lorsqu'une notice est présente
						if (common::$inputNotices) {
							foreach ($_POST as $postId => $postValue) {
								if (is_array($postValue)) {
									foreach ($postValue as $subPostId => $subPostValue) {
										self::$inputBefore[$postId . '_' . $subPostId] = $subPostValue;
									}
								} else {
									self::$inputBefore[$postId] = $postValue;
								}
							}
						}
						// Sinon traitement des données de sortie qui requiert qu'aucune notice ne soit présente
						else {
							// Notification
							if ($output['notification']) {
								if ($output['state'] === true) {
									$notification = 'ZWII_NOTIFICATION_SUCCESS';
								} elseif ($output['state'] === false) {
									$notification = 'ZWII_NOTIFICATION_ERROR';
								} else {
									$notification = 'ZWII_NOTIFICATION_OTHER';
								}
								$_SESSION[$notification] = $output['notification'];
							}
							// Redirection
							if ($output['redirect']) {
								http_response_code(301);
								header('Location:' . $output['redirect']);
								exit();
							}
						}
						// Données en sortie applicables même lorsqu'une notice est présente
						// Affichage
						if ($output['display']) {
							$this->addOutput([
								'display' => $output['display']
							]);
						}
						// Contenu brut
						if ($output['content']) {
							$this->addOutput([
								'content' => $output['content']
							]);
						}
						// Contenu par vue
						elseif ($output['view']) {
							// Chemin en fonction d'un module du coeur ou d'un module
							$modulePath = in_array($moduleId, self::$coreModuleIds) ? 'core/' : '';
							// CSS
							$stylePath = $modulePath . self::MODULE_DIR . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.css';
							if (file_exists($stylePath)) {
								$this->addOutput([
									'style' => file_get_contents($stylePath)
								]);
							}
							if ($output['style']) {
								$this->addOutput([
									'style' => file_get_contents($output['style'])
								]);
							}

							// JS
							$scriptPath = $modulePath . self::MODULE_DIR . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.js.php';
							if (file_exists($scriptPath)) {
								ob_start();
								include $scriptPath;
								$this->addOutput([
									'script' => ob_get_clean()
								]);
							}
							// Vue
							$viewPath = $modulePath . self::MODULE_DIR . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.php';
							if (file_exists($viewPath)) {
								ob_start();
								include $viewPath;
								$modpos = $this->getData(['page', $this->getUrl(0), 'modulePosition']);
								if ($modpos === 'top') {
									$this->addOutput([
										'content' => ob_get_clean() . ($output['showPageContent'] ? $pageContent : '')
									]);
								} else if ($modpos === 'free') {
									if (strstr($pageContent, '[MODULE]', true) === false) {
										$begin = strstr($pageContent, '[]', true);
									} else {
										$begin = strstr($pageContent, '[MODULE]', true);
									}
									if (strstr($pageContent, '[MODULE]') === false) {
										$end = strstr($pageContent, '[]');
									} else {
										$end = strstr($pageContent, '[MODULE]');
									}
									$cut = 8;
									$end = substr($end, -strlen($end) + $cut);
									$this->addOutput([
										'content' => ($output['showPageContent'] ? $begin : '') . ob_get_clean() . ($output['showPageContent'] ? $end : '')
									]);
								} else {
									$this->addOutput([
										'content' => ($output['showPageContent'] ? $pageContent : '') . ob_get_clean()
									]);
								}
							}
						}
						// Librairies
						if ($output['vendor'] !== $this->output['vendor']) {
							$this->addOutput([
								'vendor' => array_merge($this->output['vendor'], $output['vendor'])
							]);
						}

						if ($output['title'] !== null) {
							$this->addOutput([
								'title' => $output['title']
							]);
						}
						// Affiche le bouton d'édition de la page dans la barre de membre
						if ($output['showBarEditButton']) {
							$this->addOutput([
								'showBarEditButton' => $output['showBarEditButton']
							]);
						}
					}
					// Erreur 403
					else {
						$access = false;
					}
				}
			}
		}
		// Erreurs
		if ($access === 'login') {
			http_response_code(302);
			header('Location:' . helper::baseUrl() . 'user/login/');
			exit();
		}
		if ($access === false) {
			http_response_code(403);
			if ($accessInfo['userName']) {
				$this->addOutput([
					'title' => 'Accès verrouillé',
					'content' => template::speech(sprintf(helper::translate('La page %s est ouverte par l\'utilisateur %s'), $accessInfo['pageId'], $accessInfo['userName']))

				]);
			} else {
				if (
					$this->getData(['locale', 'page403']) !== 'none'
					and $this->getData(['page', $this->getData(['locale', 'page403'])])
				) {
					header('Location:' . helper::baseUrl() . $this->getData(['locale', 'page403']));
				} else {
					$this->addOutput([
						'title' => 'Accès interdit',
						'content' => template::speech(helper::translate('Vous n\'êtes pas autorisé à consulter cette page (erreur 403)'))
					]);
				}
			}
		} elseif ($this->output['content'] === '') {
			http_response_code(404);
			if (
				$this->getData(['locale', 'page404']) !== 'none'
				and $this->getData(['page', $this->getData(['locale', 'page404'])])
			) {
				header('Location:' . helper::baseUrl() . $this->getData(['locale', 'page404']));
			} else {
				$this->addOutput([
					'title' => 'Page indisponible',
					'content' => template::speech(helper::translate('La page demandée n\'existe pas ou est introuvable (erreur 404)'))
				]);
			}
		}
		// Mise en forme des métas
		if ($this->output['metaTitle'] === '') {
			if ($this->output['title']) {
				$this->addOutput([
					'metaTitle' => strip_tags($this->output['title']) . ' - ' . $this->getData(['locale', 'title'])
				]);
			} else {
				$this->addOutput([
					'metaTitle' => $this->getData(['locale', 'title'])
				]);
			}
		}
		if ($this->output['metaDescription'] === '') {
			$this->addOutput([
				'metaDescription' => $this->getData(['locale', 'metaDescription'])
			]);
		}
		switch ($this->output['display']) {
			// Layout brut
			case self::DISPLAY_RAW:
				echo $this->output['content'];
				break;
			// Layout vide
			case self::DISPLAY_LAYOUT_BLANK:
				require 'core/layout/blank.php';
				break;
			// Affichage en JSON
			case self::DISPLAY_JSON:
				header('Content-Type: application/json');
				echo json_encode($this->output['content']);
				break;
			// RSS feed
			case self::DISPLAY_RSS:
				header('Content-type: application/rss+xml; charset=UTF-8');
				echo $this->output['content'];
				break;
			// Layout allégé
			case self::DISPLAY_LAYOUT_LIGHT:
				ob_start();
				require 'core/layout/light.php';
				$content = ob_get_clean();
				// Convertit la chaîne en UTF-8 pour conserver les caractères accentués
				$content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
				// Supprime les espaces, les sauts de ligne, les tabulations et autres caractères inutiles
				$content = preg_replace('/[\t ]+/u', ' ', $content);
				echo $content;
				break;
			// Layout principal
			case self::DISPLAY_LAYOUT_MAIN:
				ob_start();
				require 'core/layout/main.php';
				$content = ob_get_clean();
				// Convertit la chaîne en UTF-8 pour conserver les caractères accentués
				$content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
				// Supprime les espaces, les sauts de ligne, les tabulations et autres caractères inutiles
				$content = preg_replace('/[\t ]+/u', ' ', $content);
				echo $content;
				break;
		}
	}
}