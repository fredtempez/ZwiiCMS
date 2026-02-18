<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International 
 * @Copyright (C) 2026, Frédéric Tempez
 * @Licensed under the GNU General Public License v3.0 or later.
 * @link http://zwiicms.fr/
 */
class common
{
	const DISPLAY_RAW = 0;
	const DISPLAY_JSON = 1;
	const DISPLAY_RSS = 2;
	const DISPLAY_LAYOUT_BLANK = 3;
	const DISPLAY_LAYOUT_MAIN = 4;
	const DISPLAY_LAYOUT_LIGHT = 5;
	const ROLE_BANNED = -1;
	const ROLE_VISITOR = 0;
	const ROLE_MEMBER = 1;
	const ROLE_EDITOR = 2;
	// Role MODERATOR, compatibilité avec les anciens modules :
	const ROLE_MODERATOR = 2;
	const ROLE_ADMIN = 3;
	// Compatibilité avec les anciens version de modules
	const GROUP_BANNED = -1;
	const GROUP_VISITOR = 0;
	const GROUP_MEMBER = 1;
	const GROUP_EDITOR = 2;
	// Role MODERATOR, compatibilité avec les anciens modules :
	const GROUP_MODERATOR = 2;
	const GROUP_ADMIN = 3;
	// -------------------------------------------------
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
	const ZWII_VERSION = '14.1.05';
	// URL autoupdate
	const ZWII_UPDATE_URL = 'https://codeberg.org/fredtempez/cms-update/raw/branch/master/';

	/**
	 * Branche de base pour la mise à jour
	 * Pour les versions supérieures à 13.4 et inférieure à 14, la branche reste sur v134
	 * La branche v13 est maintenue afin de télécharger un correctif permettant d'installer
	 * les version supérieures.
	 */
	const ZWII_UPDATE_CHANNEL = 'v14';

	// Valeurs possibles multiple de 10, 10 autorise 9 profils, 100 autorise 99 profils
	const MAX_PROFILS = 10;
	// Taille et rotation des journaux 1 Go
	const LOG_MAXSIZE = 1024 * 1024;
	const LOG_MAXARCHIVE = 5;

	// Profondeur des menus
	const MENU_DEPTH = 5;

	public static $actions = [];

	public static $coreModuleIds = [
		'config',
		'dashboard',
		'install',
		'language',
		'maintenance',
		'page',
		'plugin',
		'sitemap',
		'theme',
		'user'
	];

	public static $concurrentAccess = [
		'config',
		'edit',
		'language',
		'plugin',
		'theme',
		'user'
	];

	/*
	 * Cette variable est supprimée du test dans le routeur.
	 * public static $accessExclude = [
	 * 	'login',
	 * 	'logout',
	 * 	"maintenance",
	 * ];
	 */
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
			// 'imagemap',
			'simplelightbox'
		],
		'view' => ''
	];

	public static $roles = [
		self::ROLE_BANNED => 'Banni',
		self::ROLE_VISITOR => 'Visiteur',
		self::ROLE_MEMBER => 'Membre',
		self::ROLE_EDITOR => 'Éditeur',
		self::ROLE_ADMIN => 'Administrateur'
	];

	public static $roleEdits = [
		self::ROLE_BANNED => 'Banni',
		self::ROLE_MEMBER => 'Membre',
		self::ROLE_EDITOR => 'Éditeur',
		self::ROLE_ADMIN => 'Administrateur'
	];

	public static $roleNews = [
		self::ROLE_MEMBER => 'Membre',
		self::ROLE_EDITOR => 'Éditeur',
		self::ROLE_ADMIN => 'Administrateur'
	];

	public static $rolePublics = [
		self::ROLE_VISITOR => 'Visiteur',
		self::ROLE_MEMBER => 'Membre',
		self::ROLE_EDITOR => 'Éditeur',
		self::ROLE_ADMIN => 'Administrateur'
	];

	// Langues de l'UI
	// Langue de l'interface, tableau des dialogues
	public static $dialog;
	// Langue de l'interface sélectionnée
	public static $i18nUI = 'fr_FR';
	// Langues de contenu
	public static $siteContent = 'fr_FR';

	public static $languages = [
		'az_AZ' => 'Azərbaycan dili',
		'bg_BG' => 'български език',
		// 'ca' => 'Català, valencià',
		// 'cs' => 'čeština, český jazyk',
		// 'da' => 'Dansk',
		'de' => 'Deutsch',
		'en_EN' => 'English',
		'es' => 'Español',
		// 'fa' => 'فارسی',
		'fr_FR' => 'Français',
		'he_IL' => 'Hebrew (Israel)',
		'gr_GR' => 'Ελληνικά',
		'hr' => 'Hrvatski jezik',
		'hu_HU' => 'Magyar',
		'id' => 'Bahasa Indonesia',
		'it' => 'Italiano',
		'ja' => '日本',
		'lt' => 'Lietuvių kalba',
		// 'mn_MN' => 'монгол',
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
	protected $dataFiles = [
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

	// Bases essentielles chargées au démarrage (optimisé avec JsonDb cache)
	private $essentialDataFiles = [
		'config',
		'user',
		'locale',
		'core',
		'page',
		'module'
	];


	// Configuration JsonDb améliorée
	private $jsonDbConfig = null;

	// Configuration par défaut pour toutes les bases JsonDb
	private const JSONDB_DEFAULT_CONFIG = [
		'minification' => false,
		'compression' => false,
		'encryption' => false,
		'encryptionKey' => '',
		'cachePrefix' => null  // Préfixe de cache pour éviter les collisions entre sites
	];

	public static $fontsWebSafe = [
		'arial' => [
			'name' => 'Arial',
			'font-family' => 'Arial, Helvetica, sans-serif',
			'resource' => 'websafe'
		],
		'arial-black' => [
			'name' => 'Arial Black',
			'font-family' => "'Arial Black', Gadget, sans-serif",
			'resource' => 'websafe'
		],
		'courrier' => [
			'name' => 'Courier',
			'font-family' => "Courier, 'Liberation Mono', monospace",
			'resource' => 'websafe'
		],
		'courrier-new' => [
			'name' => 'Courier New',
			'font-family' => "'Courier New', Courier, monospace",
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
			'font-family' => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
			'resource' => 'websafe'
		],
		'tahoma' => [
			'name' => 'Tahoma',
			'font-family' => 'Tahoma, Geneva, sans-serif',
			'resource' => 'websafe'
		],
		'times-new-roman' => [
			'name' => 'Times New Roman',
			'font-family' => "'Times New Roman', 'Liberation Serif', serif",
			'resource' => 'websafe'
		],
		'trebuchet' => [
			'name' => 'Trebuchet',
			'font-family' => "'Trebuchet MS', Arial, Helvetica, sans-serif",
			'resource' => 'websafe'
		],
		'verdana' => [
			'name' => 'Verdana',
			'font-family' => 'Verdana, Geneva, sans-serif;',
			'resource' => 'websafe'
		]
	];

	// Boutons de navigation dans la page
	public static $navIconTemplate = [
		'open' => [
			'left' => 'left-open',
			'right' => 'right-open',
		],
		'dir' => [
			'left' => 'left',
			'right' => 'right-dir',
		],
		'big' => [
			'left' => 'left-big',
			'right' => 'right-big',
		],
	];

	/**
	 * Constructeur commun
	 */
	/**
	 * Constructeur optimisé avec lazy loading des bases JSON
	 * Seules les bases essentielles sont chargées au démarrage :
	 * - config, user, locale, page, module, core
	 * Les autres bases (theme, font, admin, blacklist, language, profil)
	 * sont chargées automatiquement à la demande via getData/setData
	 */
	public function __construct()
	{
		// Récupération du cache des propriétés
		if (isset($GLOBALS['common_cache'])) {
			$this->input['_POST'] = $GLOBALS['common_cache']['input']['_POST'];
			$this->input['_COOKIE'] = $GLOBALS['common_cache']['input']['_COOKIE'];
			self::$siteContent = $GLOBALS['common_cache']['siteContent'];
			$this->dataFiles = $GLOBALS['common_cache']['dataFiles'];
			$this->user = $GLOBALS['common_cache']['user'];
			self::$i18nUI = $GLOBALS['common_cache']['i18nUI'];
			$this->hierarchy = $GLOBALS['common_cache']['hierarchy'];
			$this->url = $GLOBALS['common_cache']['url'];
			self::$dialog = $GLOBALS['common_cache']['dialog'];
			return;
		}

		// Extraction des données http
		if (isset($_POST)) {
			$this->input['_POST'] = $_POST;
		}
		if (isset($_COOKIE)) {
			$this->input['_COOKIE'] = $_COOKIE;
		}

		// Déterminer la langue du contenu du site
		if (isset($_SESSION['ZWII_SITE_CONTENT'])) {
			// Déterminé par la session présente
			self::$siteContent = $_SESSION['ZWII_SITE_CONTENT'];
		} else {
			// Détermine la langue par défaut
			foreach (self::$languages as $key => $value) {
				if (file_exists(self::DATA_DIR . $key . '/.default')) {
					self::$siteContent = $key;
					$_SESSION['ZWII_SITE_CONTENT'] = $key;
					break;
				}
			}
		}

		// Localisation
		\setlocale(LC_ALL, self::$siteContent . '.UTF8');

		// Configuration automatique du cachePrefix pour éviter les collisions entre sites
		$hostname = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'default';
		$this->jsonDbConfig = [
			'cachePrefix' => $hostname
		];

		// Instanciation de la classe des entrées / sorties (bases essentielles seulement)
		$this->jsonDB(self::$siteContent, $this->essentialDataFiles);

		// Installation fraîche, initialisation des modules
		if ($this->user === []) {
			foreach ($this->dataFiles as $stageId => $item) {
				$folder = $this->dataPath($stageId, self::$siteContent);
				if (
					file_exists($folder . $stageId . '.json') === false
				) {
					$this->initData($stageId, self::$siteContent);
					common::$coreNotices[] = $stageId;
				}
			}
		}

		// Récupère un utilisateur connecté
		if ($this->user === []) {
			$userId = isset($_SESSION['ZWII_USER_ID']) ? $_SESSION['ZWII_USER_ID'] : null;
			if ($userId) {
				$this->user = $this->getData(['user', $userId]);
			}
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
			// Par défaut la langue définie par défaut à l'installation
			if ($this->getData(['config', 'defaultLanguageUI'])) {
				self::$i18nUI = $this->getData(['config', 'defaultLanguageUI']);
			} else {
				self::$i18nUI = 'fr_FR';
				$this->setData(['config', 'defaultLanguageUI', 'fr_FR']);
			}
		}
		// Stocker le cookie de langue pour l'éditeur de texte
		setcookie('ZWII_UI', self::$i18nUI, [
			'expires' => time() + 3600,
			'path' => helper::baseUrl(false, false),
			'domain' => '',
			'secure' => false,
			'httponly' => false,
			'samesite' => 'Lax'  // Vous pouvez aussi utiliser 'Strict' ou 'None'
		]);
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
				is_dir(self::MODULE_DIR . $moduleId . '/i18n') &&
				file_exists(self::MODULE_DIR . $moduleId . '/i18n/' . self::$i18nUI . '.json')
			) {
				$d = json_decode(file_get_contents(self::MODULE_DIR . $moduleId . '/i18n/' . self::$i18nUI . '.json'), true);
				self::$dialog = array_merge(self::$dialog, $d);
			}
		}

		// Cache
		$GLOBALS['common_construct']['dialog'] = self::$dialog;

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
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false
				)
			);
			stream_context_set_default($context);
		}

		// Mise en cache des propriétés (optimisé pour lazy loading)
		$GLOBALS['common_cache'] = [
			'input' => [
				'_POST' => $this->input['_POST'],
				'_COOKIE' => $this->input['_COOKIE'],
			],
			'siteContent' => self::$siteContent,
			'dataFiles' => $this->dataFiles, // Contient seulement les bases essentielles chargées
			'user' => $this->user,
			'i18nUI' => self::$i18nUI,
			'hierarchy' => $this->hierarchy,
			'url' => $this->url,
			'dialog' => self::$dialog,
		];

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
	 * Check du token CSRF
	 */
	public function checkCSRF()
	{
		return ((empty($_POST['csrf']) or hash_equals($_POST['csrf'], $_SESSION['csrf']) === false) === false);
	}

	/**
	 * Supprime des données
	 * @param array $keys Clé(s) des données
	 */
	public function deleteData($keys)
	{
		// Vérifier si la base est chargée, sinon la charger automatiquement
		if (empty($this->dataFiles[$keys[0]])) {
			// Charge la base ciblée avant suppression pour éviter un descripteur vide
			$this->loadDataFile($keys[0]);
		}

		// descripteur de la base
		$db = (object) $this->dataFiles[$keys[0]];
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
	 * @param array $keys Clé(s) des données (les premiers éléments sont le chemin, le dernier est la valeur)
	 * @param bool|null $save Stratégie de sauvegarde :
	 * - null (défaut) : Utilise la configuration actuelle de JsonDb (sauvegarde immédiate par défaut, sauf si setAutoSave(false) a été appelé).
	 * - true : Force la sauvegarde immédiate.
	 * - false : Empêche la sauvegarde immédiate (pour les traitements par lots).
	 * @return bool Succès de l'opération
	 */
	public function setData($keys = [], $save = null)
	{
		// Pas d'enregistrement lorsqu'une notice est présente ou tableau transmis vide
		if (
			!empty(self::$inputNotices) or
			empty($keys)
		) {
			return false;
		}

		// Validation des clés
		if (!is_array($keys) || count($keys) < 2) {
			return false;
		}

		// Empêcher la sauvegarde d'une donnée nulle.
		if (gettype($keys[count($keys) - 1]) === NULL) {
			return false;
		}

		// Vérifier si la base est chargée, sinon la charger automatiquement
		if (empty($this->dataFiles[$keys[0]])) {
			// Charge la base concernée pour disposer du descripteur avant écriture
			$this->loadDataFile($keys[0]);
		}

		// Vérifier que la base de données existe après tentative de chargement
		if (!isset($this->dataFiles[$keys[0]]) || empty($this->dataFiles[$keys[0]])) {
			return false;
		}

		// Initialisation du retour en cas d'erreur de descripteur
		$success = false;

		try {
			// Construire la requête dans la base si au moins 1 clé
			if (count($keys) >= 1) {
				// Descripteur de la base
				$db = (object) $this->dataFiles[$keys[0]];

				// La première clé est le nom du module/fichier
				$query = $keys[0];

				// Construire la requête (notation par points)
				// On boucle jusqu'à l'avant-dernier élément (le dernier étant la valeur)
				for ($i = 1; $i < count($keys) - 1; $i++) {
					$query .= '.' . $keys[$i];
				}

				// Appliquer la modification
				// $save étant null par défaut, c'est JsonDb qui décidera s'il faut écrire sur le disque
				// en fonction de son état interne (setAutoSave)
				$success = $db->set($query, $keys[count($keys) - 1], $save);
			}
		} catch (Exception $e) {
			// Log de l'erreur si possible
			if (method_exists($this, 'saveLog')) {
				$this->saveLog('Erreur setData: ' . $e->getMessage() . ' pour les clés: ' . implode('.', $keys));
			}
			return false;
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
			// Vérifier si la base est chargée, sinon la charger automatiquement
			if (empty($this->dataFiles[$keys[0]])) {
				// Charge la base demandée avant lecture pour accéder aux données
				$this->loadDataFile($keys[0]);
			}

			// descripteur de la base
			$db = (object) $this->dataFiles[$keys[0]];
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
			$this->getData(['page', $page, 'content']) !== '' &&
			file_exists(self::DATA_DIR . $lang . '/content/' . $this->getData(['page', $page, 'content']))
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
		return $this->secure_file_put_contents(self::DATA_DIR . $lang . '/content/' . $page . '.html', $value);
	}

	/**
	 * Écrit les données dans un fichier avec plusieurs tentatives d'écriture et verrouillage
	 *
	 * @param string $filename Le nom du fichier
	 * @param string $data Les données à écrire dans le fichier
	 * @param int $flags Les drapeaux optionnels à passer à la fonction file_put_contents
	 * @return bool True si l'écriture a réussi, sinon false
	 */
	function secure_file_put_contents($filename, $data, $flags = 0)
	{
		// Validation des paramètres
		if (empty($filename) || !is_string($filename)) {
			return false;
		}

		// Vérifier que le dossier parent existe et est accessible en écriture
		$dir = dirname($filename);
		if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
			return false;
		}

		if (!is_writable($dir)) {
			return false;
		}

		// Initialise le compteur de tentatives
		$attempts = 0;
		$maxAttempts = 5;

		// Effectue jusqu'à 5 tentatives d'écriture
		while ($attempts < $maxAttempts) {
			// Essaye d'écrire les données dans le fichier avec verrouillage exclusif
			$write_result = file_put_contents($filename, $data, LOCK_EX | $flags);

			// Vérifie si l'écriture a réussi
			if ($write_result !== false) {
				// Vérification supplémentaire : lire le fichier pour s'assurer de l'intégrité
				$read_data = file_get_contents($filename);
				if ($read_data === $data) {
					return true;
				}
			}

			// Incrémente le compteur de tentatives
			$attempts++;

			// Pause progressive entre les tentatives
			usleep(100000 * $attempts); // 100ms, 200ms, 300ms, etc.
		}

		// État de l'écriture
		return false;
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

	public function jsonDB($lang, $dataFilesToLoad = null)
	{
		// Instanciation de la classe des entrées / sorties avec JsonDb amélioré
		// Si aucune liste spécifiée, charger toutes les bases (comportement par défaut)
		if ($dataFilesToLoad === null) {
			$dataFilesToLoad = array_keys($this->dataFiles);
		}

		// Configuration par défaut globale
		$defaultConfig = self::JSONDB_DEFAULT_CONFIG;

		// Récupère les descripteurs pour les bases spécifiées
		foreach ($dataFilesToLoad as $keys) {
			if (array_key_exists($keys, $this->dataFiles)) {
				// Configuration de base
				$config = [
					'name' => $keys . '.json',
					'dir' => $this->dataPath($keys, $lang),
					'backup' => file_exists('site/data/.backup'),
					'update' => false,
				];

				// Fusion avec configuration spécifique si existe
				$dataFilesConfig = $this->getDataFilesConfig();
				if (isset($dataFilesConfig[$keys])) {
					$config = array_merge($config, $defaultConfig, $dataFilesConfig[$keys]);
				} else {
					// Sinon utiliser la configuration globale par défaut
					$config = array_merge($config, $defaultConfig);
				}

				// Utilisation de la classe JsonDb améliorée
				$this->dataFiles[$keys] = new \Prowebcraft\JsonDb($config);
			}
		}
	}

	/**
	 * Charge (ou instancie si absent) une base JsonDb dans le cache partagé, en appliquant la configuration globale + spécifique (compression/chiffrement) pour la langue cible.
	 * @param string $dataFile Nom de la base à charger
	 * @param string $lang Langue (optionnel, utilise la langue courante par défaut)
	 */
	private function loadDataFile($dataFile, $lang = null)
	{
		// Instancie la JsonDb ciblée et la garde en cache partagé pour les lectures/écritures classiques
		if ($lang === null) {
			$lang = self::$siteContent;
		}

		// Vérifier si la base existe et n'est pas déjà chargée
		if (!array_key_exists($dataFile, $this->dataFiles) || empty($this->dataFiles[$dataFile])) {
			// Configuration de base
			$config = [
				'name' => $dataFile . '.json',
				'dir' => $this->dataPath($dataFile, $lang),
				'backup' => file_exists('site/data/.backup'),
				'update' => false,
			];

			// Fusionner avec la configuration JsonDb globale si définie
			if (isset($this->jsonDbConfig)) {
				$config = array_merge($config, $this->jsonDbConfig);
			} else {
				// Configuration par défaut si aucune configuration globale
				$config = array_merge($config, self::JSONDB_DEFAULT_CONFIG);
			}

			// Fusionner avec la configuration spécifique à la base de données
			$dataFilesConfig = $this->getDataFilesConfig();
			if (isset($dataFilesConfig[$dataFile])) {
				$config = array_merge($config, $dataFilesConfig[$dataFile]);
			}

			// Désactiver l'encryption si la clé est vide
			if (isset($config['encryption']) && $config['encryption'] && empty($config['encryptionKey'])) {
				$config['encryption'] = false;
			}

			// Utilisation de la classe JsonDb améliorée
			$this->dataFiles[$dataFile] = new \Prowebcraft\JsonDb($config);
		}
	}

	/**
	 * Assure qu'une base est présente dans le cache partagé en passant par loadDataFile, pour les modules qui n'ont pas accès à la méthode privée.
	 * @param string $dataFile Nom de la base
	 * @param string $lang Langue (optionnel)
	 */
	protected function ensureDataFileLoaded($dataFile, $lang = null)
	{
		// Expose le chargement cache aux modules sans ouvrir l'accès direct à loadDataFile
		if ($lang === null) {
			$lang = self::$siteContent;
		}
		// Charge la base demandée dans le cache partagé pour les appels module
		$this->loadDataFile($dataFile, $lang);
	}

	/**
	 * Lit une base JsonDb dans une instance éphémère (hors cache partagé) avec la configuration complète, et retourne la clé demandée (racine par défaut).
	 * @param string $dataFile Nom de la base
	 * @param string $lang Langue/chemin (optionnel)
	 * @param string|array|null $query Clé à lire (string notation point ou tableau). Par défaut, la racine porte le nom de la base.
	 * @return mixed Données lues ou null si absentes
	 */
	public function fetchDataFile($dataFile, $lang = null, $query = null)
	{
		// Lit une base isolée (nouvelle instance) pour éviter de polluer le cache partagé
		if ($lang === null) {
			$lang = self::$siteContent;
		}
		$config = [
			'name' => $dataFile . '.json',
			'dir' => $this->dataPath($dataFile, $lang),
			'backup' => file_exists('site/data/.backup'),
			'update' => false,
		];

		$config = isset($this->jsonDbConfig)
			? array_merge($config, $this->jsonDbConfig)
			: array_merge($config, self::JSONDB_DEFAULT_CONFIG);

		$dataFilesConfig = $this->getDataFilesConfig();
		if (isset($dataFilesConfig[$dataFile])) {
			$config = array_merge($config, $dataFilesConfig[$dataFile]);
		}

		if (isset($config['encryption']) && $config['encryption'] && empty($config['encryptionKey'])) {
			$config['encryption'] = false;
		}

		$db = new \Prowebcraft\JsonDb($config);

		if ($query === null) {
			$target = $dataFile;
		} elseif (is_array($query)) {
			$target = $dataFile;
			foreach ($query as $segment) {
				$target .= '.' . $segment;
			}
		} else {
			$target = $query;
		}

		return $db->get($target);
	}

	/**
	 * Écrit des données à la racine d'une base et persiste via JsonDb en appliquant la configuration et la langue ciblée (compression/chiffrement inclus).
	 * @param string $dataFile Nom de la base à sauvegarder
	 * @param mixed $data Données à écrire (placées à la racine de la base)
	 * @param string|null $lang Langue (optionnel, langue courante par défaut)
	 * @return bool Succès de l'écriture
	 */
	public function saveDataFile($dataFile, $data, $lang = null)
	{
		// Force un chargement avec la bonne config puis écrit et persiste la base ciblée
		if ($lang === null) {
			$lang = self::$siteContent;
		}

		// Charger la base avec la config adaptée si besoin
		// Charge/initialise la base pour appliquer la config (compression/chiffrement) avant sauvegarde
		$this->loadDataFile($dataFile, $lang);

		$db = (object) $this->dataFiles[$dataFile];
		$db->set($dataFile, $data);
		return $db->save();
	}

	/**
	 * Indique si le descripteur JsonDb d'une base est déjà instancié dans le cache partagé.
	 * @param string $dataFile Nom de la base à vérifier
	 * @return bool True si la base est chargée, false sinon
	 */
	public function isDataFileLoaded($dataFile)
	{
		// Indique si le descripteur JsonDb est déjà en cache partagé
		return array_key_exists($dataFile, $this->dataFiles) && !empty($this->dataFiles[$dataFile]);
	}


	/**
	 * Précharge les bases nécessaires à un module (communes + spécifiques) pour la langue donnée, sans recharger celles déjà présentes en cache.
	 * @param string $moduleId ID du module
	 * @param string $lang Langue (optionnel)
	 */
	public function loadModuleDataFiles($moduleId, $lang = null)
	{
		// Précharge les bases nécessaires à un module (avec fallback sur la langue courante)
		if ($lang === null) {
			$lang = self::$siteContent;
		}

		// Bases communes requises par la plupart des modules
		$moduleRequiredBases = ['theme', 'font'];

		// Bases spécifiques selon le module
		switch ($moduleId) {
			case 'user':
			case 'config':
				$moduleRequiredBases[] = 'admin';
				$moduleRequiredBases[] = 'profil';
				break;
			case 'blog':
			case 'news':
				$moduleRequiredBases[] = 'blacklist';
				break;
			case 'language':
				$moduleRequiredBases[] = 'language';
				break;
		}

		// Charger les bases requises qui ne sont pas encore chargées
		foreach ($moduleRequiredBases as $dataFile) {
			if (!$this->isDataFileLoaded($dataFile)) {
				// Précharge la base requise pour le module afin d'éviter des accès paresseux plus loin
				$this->loadDataFile($dataFile, $lang);
			}
		}
	}


	/**
	 * Construit la configuration par base en priorisant config.json, sinon les valeurs par défaut de l'install (defaultdata.php).
	 * @return array Configuration des bases de données
	 */
	private function getDataFilesConfig()
	{
		// Construit la config par base (priorité config.json, sinon défauts d'install)
		// 1. Essayer de lire depuis config.json (priorité 1)
		$configPath = self::DATA_DIR . 'config.json';
		if (file_exists($configPath)) {
			$configData = json_decode(file_get_contents($configPath), true);
			if (isset($configData['config']['database'])) {
				return $configData['config']['database'];
			}
		}

		// 2. Sinon, utiliser la configuration par défaut depuis defaultdata.php (priorité 2)
		require_once('core/module/install/ressource/defaultdata.php');
		return init::$dataFilesConfig ?? [];
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

		if (!file_exists(self::DATA_DIR . $lang)) {
			mkdir(self::DATA_DIR . $lang, 0755);
		}

		switch ($module) {
			case 'page':
			case 'module':
			case 'locale':
				// Création des sous-dossiers localisés
				if (!file_exists(self::DATA_DIR . $lang)) {
					mkdir(self::DATA_DIR . $lang, 0755);
				}
				if (!file_exists(self::DATA_DIR . $lang . '/content')) {
					mkdir(self::DATA_DIR . $lang . '/content', 0755);
				}
				// Site en français avec site exemple
				if ($lang == 'fr_FR' && $sampleSite === true && $module !== 'locale') {
					$this->setData([$module, init::$siteTemplate[$module]]);
					// Création des pages
					foreach (init::$siteContent as $key => $value) {
						$this->setPage($key, $value['content'], 'fr_FR');
					}
					// Version en langue étrangère ou fr_FR sans site de test
				} else {
					// En_EN par défaut si le contenu localisé n'est pas traduit
					$langDefault = array_key_exists($lang, init::$defaultDataI18n) === true ? $lang : 'default';
					// Charger les données de cette langue
					$this->setData([$module, init::$defaultDataI18n[$langDefault][$module]]);
					// Créer la page d'accueil, une seule page dans cette configuration
					$pageId = init::$defaultDataI18n[$langDefault]['locale']['homePageId'];
					$content = init::$defaultDataI18n[$langDefault]['html'];
					$this->setPage($pageId, $content, $lang);
				}
				break;
			default:
				// Installation des données des autres modules cad theme profil font config, admin et core
				$this->setData([$module, init::$defaultData[$module]]);
				break;
		}
	}

	/**
	 * Forçage de l'enregistrement
	 * @param mixed $module
	 * @return void
	 * @throws Exception Si la base n'est pas chargée (erreur logique)
	 */
	public function saveDB($module): void
	{
		// Vérifier que la base est bien chargée (ne pas masquer les erreurs logiques)
		if (empty($this->dataFiles[$module])) {
			throw new Exception("Tentative de sauvegarde d'une base non chargée : $module. La base doit être chargée avant d'être sauvegardée.");
		}

		$db = (object) $this->dataFiles[$module];
		$db->save();
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
				$this->getData(['page', $pageId, 'parentPageId']) === '' and
					// Ignore les pages dont l'utilisateur n'a pas accès
				($this->getData(['page', $pageId, 'role']) === self::ROLE_VISITOR or
					($this->isConnected() === true and
							// and $this->getUser('role') >= $this->getData(['page', $pageId, 'role'])
							// Modification qui tient compte du profil de la page
						($this->getUser('role') * self::MAX_PROFILS + $this->getUser('profil')) >= ($this->getData(['page', $pageId, 'role']) * self::MAX_PROFILS + $this->getData(['page', $pageId, 'profil']))))
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
				$parentId = $this->getData(['page', $pageId, 'parentPageId']) and
					// Ignore les pages dont l'utilisateur n'a pas accès
				(
					(
						$this->getData(['page', $pageId, 'role']) === self::ROLE_VISITOR and
						$this->getData(['page', $parentId, 'role']) === self::ROLE_VISITOR
					) or (
						$this->isConnected() === true and
						$this->getUser('role') * self::MAX_PROFILS + $this->getUser('profil')
					) >= ($this->getData(['page', $pageId, 'role']) * self::MAX_PROFILS + $this->getData(['page', $pageId, 'profil']))
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
	 */
	private function tinyMcePages()
	{
		// Sauve la liste des pages pour TinyMCE
		$parents = [];
		$rewrite = (helper::checkRewrite()) ? '' : '?';

		// Boucle de recherche des pages actives avec hiérarchie
		foreach ($this->getHierarchy() as $parentId => $childIds) {
			// Ne traiter que les pages racines (parentPageId vide)
			if ($this->getData(['page', $parentId, 'parentPageId']) !== '') {
				continue;
			}

			if ($this->getData(['page', $parentId]) && $this->getData(['page', $parentId, 'block']) !== 'bar') {
				// Ajouter la page parent
				$pageEntry = [
					'title' => html_entity_decode($this->getData(['page', $parentId, 'title']), ENT_QUOTES),
					'value' => $rewrite . $parentId,
					'menu' => []
				];

				// Ajouter les enfants récursivement
				$this->addTinyMceChildren($pageEntry['menu'], $childIds, $rewrite, 1);

				$parents[] = $pageEntry;
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
					array_key_exists($firstKey, $values) and
					array_key_exists($secondKey, $values[$firstKey])
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
	 * Récupère les informations de l'utilisateur connecté ou vérifie ses permissions
	 * 
	 * @param string $key Clé de la propriété utilisateur à récupérer. Valeurs spéciales :
	 *                   - 'id' : Retourne l'ID de l'utilisateur depuis la session
	 *                   - 'permission' : Vérifie une permission (utilise $perm1 et $perm2)
	 *                   - Autre clé : Propriété de l'utilisateur (ex: 'role', 'pseudo', 'mail'...)
	 * @param mixed $perm1 Premier paramètre de permission (obligatoire si $key = 'permission')
	 * @param mixed $perm2 Second paramètre de permission optionnel
	 * @return mixed Retourne :
	 *               - string|int|array pour les propriétés utilisateur
	 *               - bool pour les vérifications de permission
	 *               - null si l'utilisateur n'est pas connecté ou la propriété n'existe pas
	 * 
	 * @example 
	 * // Récupérer l'ID de l'utilisateur
	 * $userId = $this->getUser('id');
	 * 
	 * // Vérifier une permission
	 * $canEdit = $this->getUser('permission', 'page', 'edit');
	 * 
	 * // Récupérer une propriété utilisateur
	 * $userRole = $this->getUser('role');
	 */
	public function getUser($key, $perm1 = null, $perm2 = null)
	{
		if ($this->isConnected() === false) {
			return false;
		} elseif (is_array($this->user) === false) {
			return false;
		} elseif ($key === 'id') {
			return $_SESSION['ZWII_USER_ID'] ?? false;
		} elseif ($key === 'permission') {
			return $this->getPermission($perm1, $perm2);
		} elseif (array_key_exists($key, $this->user)) {
			return $this->user[$key];
		} else {
			return false;
		}
	}

	/**
	 * Vérifie les permissions d'un utilisateur selon son rôle et son profil
	 * 
	 * @param string|object $key1 Clé de permission (peut être une classe de module ou une catégorie de permission)
	 * @param string|null $key2 Sous-clé de permission optionnelle (action spécifique ou sous-catégorie)
	 * @return mixed Retourne :
	 *               - true si l'utilisateur est administrateur
	 *               - false si le rôle n'a pas la permission ou si l'utilisateur est un visiteur
	 *               - La valeur de la permission spécifiée si elle existe dans le profil
	 *               - false dans tous les autres cas
	 * 
	 * @example 
	 * // Vérifie si l'utilisateur a la permission d'ajouter un article dans le module blog
	 * $hasPermission = $this->getPermission('blog', 'add');
	 * 
	 * // Vérifie si l'utilisateur a une permission spécifique
	 * $canEdit = $this->getPermission('page', 'edit');
	 * 
	 * // Vérifie une permission sur un module instancié
	 * $module = new blog();
	 * $canConfigure = $this->getPermission($module, 'config');
	 */
	private function getPermission($key1, $key2 = null)
	{
		// Administrateur, toutes les permissions
		if ($this->getUser('role') === self::ROLE_ADMIN) {
			return true;
		} elseif ($this->getUser('role') <= self::ROLE_VISITOR) {  // Role sans autorisation
			return false;
		} elseif (
			// Role avec profil, consultation des autorisations sur deux clés
			$key1 &&
			$key2 &&
			$this->user &&
			$this->getData(['profil', $this->user['role'], $this->user['profil'], $key1]) &&
			array_key_exists($key2, $this->getData(['profil', $this->user['role'], $this->user['profil'], $key1]))
		) {
			return $this->getData(['profil', $this->user['role'], $this->user['profil'], $key1, $key2]);
			// Role avec profil, consultation des autorisations sur une seule clé
		} elseif (
			$key1 &&
			$this->user &&
			$this->getData(['profil', $this->user['role'], $this->user['profil']]) &&
			array_key_exists($key1, $this->getData(['profil', $this->user['role'], $this->user['profil']]))
		) {
			return $this->getData(['profil', $this->user['role'], $this->user['profil'], $key1]);
		} else {
			// Une permission non spécifiée dans le profil est autorisée selon la valeur de $actions
			if (class_exists($key1)) {
				$module = new $key1;
				if (array_key_exists($key2, $module::$actions)) {
					return $this->getUser('role') >= $module::$actions[$key2];
				}
			}
			return false;
		}
	}

	/**
	 * @return bool l'utilisateur est connecté true sinon false
	 */
	public function isConnected()
	{
		// Vérifie d'abord si l'utilisateur est chargé et a une clé d'authentification
		if (is_array($this->user) === false || empty($this->user['authKey'])) {
			return false;
		}
		// Vérifie que la clé d'authentification correspond
		return $this->user['authKey'] === $this->getInput('ZWII_AUTH_KEY');
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
		// Le drapeau prend true quand au moins une page est trouvée
		$flag = false;

		// Rafraîchit la liste des pages après une modification de pageId notamment
		$this->buildHierarchy();

		// Actualise la liste des pages pour TinyMCE
		$this->tinyMcePages();

		// require_once 'core/vendor/sitemap/SitemapGenerator.php';

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
		$datetime->format(DateTime::ATOM);  // Updated ISO8601

		foreach ($this->getHierarchy() as $parentPageId => $childrenPageIds) {
			// Exclure les barres et les pages non publiques et les pages masquées
			if (
				$this->getData(['page', $parentPageId, 'role']) !== 0 ||
				$this->getData(['page', $parentPageId, 'block']) === 'bar'
			) {
				continue;
			}
			// Page désactivée, traiter les sous-pages sans prendre en compte la page parente.
			if ($this->getData(['page', $parentPageId, 'disable']) !== true) {
				// Cas de la page d'accueil ne pas dupliquer l'URL
				$pageId = ($parentPageId !== $this->getData(['locale', 'homePageId'])) ? $parentPageId : '';
				$sitemap->addUrl('/' . $pageId, $datetime);
				$flag = true;
			}
			// Articles du blog
			if (
				$this->getData(['page', $parentPageId, 'moduleId']) === 'blog' &&
				!empty($this->getData(['module', $parentPageId])) &&
				$this->getData(['module', $parentPageId, 'posts'])
			) {
				foreach ($this->getData(['module', $parentPageId, 'posts']) as $articleId => $article) {
					if ($this->getData(['module', $parentPageId, 'posts', $articleId, 'state']) === true) {
						$date = $this->getData(['module', $parentPageId, 'posts', $articleId, 'publishedOn']);
						$sitemap->addUrl('/' . $parentPageId . '/' . $articleId, DateTime::createFromFormat('U', $date));
					}
				}
			}
			// Sous-pages
			foreach ($childrenPageIds as $childKey) {
				if ($this->getData(['page', $childKey, 'role']) !== 0 || $this->getData(['page', $childKey, 'disable']) === true) {
					continue;
				}
				// Cas de la page d'accueil ne pas dupliquer l'URL
				$pageId = ($childKey !== $this->getData(['locale', 'homePageId'])) ? $childKey : '';
				$sitemap->addUrl('/' . $childKey, $datetime);
				$flag = true;

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

		if ($flag === false) {
			return false;
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
			$this->secure_file_put_contents('robots.txt', 'User-agent: *' . PHP_EOL . 'Disallow: /');
		}

		// Submit your sitemaps to Google, Yahoo, Bing and Ask.com
		if (empty($this->getData(['config', 'proxyType']) . $this->getData(['config', 'proxyUrl']) . ':' . $this->getData(['config', 'proxyPort']))) {
			$sitemap->submitSitemap();
		}

		return (file_exists('sitemap.xml') && file_exists('robots.txt'));
	}

	/**
	 * Crée une miniature d'une image avec gestion de plusieurs formats (JPEG, PNG, GIF, WebP, AVIF, SVG)
	 * La fonction gère automatiquement la transparence pour les formats PNG et GIF
	 * 
	 * Exemple d'utilisation :
	 * // Création d'une miniature de 300px de large
	 * $thumbPath = $core->makeThumb('chemin/vers/image.jpg', null, 300);
	 * 
	 * @param string $src Chemin absolu vers le fichier image source
	 * @param string|null $dest (Optionnel) Chemin complet où enregistrer la miniature.
	 *                        Si null, généré automatiquement dans un dossier 'thumb' parallèle au dossier source.
	 * @param int $desired_width Largeur souhaitée pour la miniature en pixels.
	 *                         La hauteur est calculée proportionnellement.
	 *                         Ignorée pour les fichiers SVG qui sont simplement copiés.
	 * 
	 * @return string|bool Chemin complet de la miniature créée en cas de succès,
	 *                    false en cas d'échec (fichier source inexistant, erreur de traitement, etc.)
	 * 
	 * @throws Exception En cas d'erreur lors du traitement de l'image
	 * @since 1.0.0
	 */
	public static function makeThumb($src, $dest = null, $desired_width = 200)
	{
		// Vérifier si le fichier source existe
		if (!file_exists($src)) {
			error_log("Fichier source introuvable: $src");
			return false;
		}

		// Générer automatiquement le chemin de destination si non fourni
		if ($dest === null) {
			$srcInfo = pathinfo($src);
			$destDir = str_replace('source', 'thumb', $srcInfo['dirname']);
			if (!is_dir($destDir) && !mkdir($destDir, 0755, true)) {
				error_log("Impossible de créer le répertoire de destination: $destDir");
				return false;
			}
			$dest = $destDir . '/' . self::THUMBS_SEPARATOR . strtolower($srcInfo['basename']);
		}

		$fileInfo = pathinfo($dest);
		$extension = strtolower($fileInfo['extension'] ?? '');
		$mime_type = mime_content_type($src);

		// Gestion des fichiers SVG (copie simple sans redimensionnement)
		if ($extension === 'svg' || $mime_type === 'image/svg+xml') {
			return copy($src, $dest) ? $dest : false;
		}

		// Chargement de l'image source selon le type
		$source_image = null;
		try {
			switch (strtolower(pathinfo($src, PATHINFO_EXTENSION))) {
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
				case 'avif':
					if (function_exists('imagecreatefromavif')) {
						$source_image = imagecreatefromavif($src);
					}
					break;
				default:
					error_log("Format d'image non supporté: $src");
					return false;
			}

			if (!$source_image) {
				error_log("Impossible de charger l'image source: $src");
				return false;
			}

			$width = imagesx($source_image);
			$height = imagesy($source_image);
			$desired_height = floor($height * ($desired_width / $width));

			// Création de la miniature
			$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

			// Gestion de la transparence pour les PNG et GIF
			if (in_array($extension, ['png', 'gif'])) {
				imagealphablending($virtual_image, false);
				imagesavealpha($virtual_image, true);
				$transparent = imagecolorallocatealpha($virtual_image, 255, 255, 255, 127);
				imagefilledrectangle($virtual_image, 0, 0, $desired_width, $desired_height, $transparent);
			}

			imagecopyresampled(
				$virtual_image,
				$source_image,
				0,
				0,
				0,
				0,
				$desired_width,
				$desired_height,
				$width,
				$height
			);

			// Sauvegarde de la miniature
			$result = false;
			switch ($mime_type) {
				case 'image/jpeg':
				case 'image/jpg':
					$result = imagejpeg($virtual_image, $dest, 85);
					break;
				case 'image/png':
					$result = imagepng($virtual_image, $dest, 8);
					break;
				case 'image/gif':
					$result = imagegif($virtual_image, $dest);
					break;
				case 'image/webp':
					$result = imagewebp($virtual_image, $dest, 85);
					break;
				case 'image/avif':
					if (function_exists('imageavif')) {
						$result = imageavif($virtual_image, $dest, 60);
					}
					break;
			}

			// Nettoyage
			imagedestroy($virtual_image);
			imagedestroy($source_image);

			if ($result && file_exists($dest)) {
				return $dest;
			}

			error_log("Échec de la sauvegarde de la miniature: $dest");
			return false;

		} catch (Exception $e) {
			if (isset($source_image) && (is_resource($source_image) || $source_image instanceof GdImage)) {
				imagedestroy($source_image);
			}
			if (isset($virtual_image) && (is_resource($virtual_image) || $virtual_image instanceof GdImage)) {
				imagedestroy($virtual_image);
			}
			error_log("Erreur lors de la création de la miniature: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * Génère le chemin d'une miniature à partir d'un chemin source
	 * @param string $sourcePath Chemin vers le fichier source
	 * @param string $prefix Préfixe à ajouter au nom du fichier (par défaut: self::THUMBS_SEPARATOR)
	 * @return string Chemin de la miniature
	 */
	public static function getThumb($sourcePath, $prefix = self::THUMBS_SEPARATOR)
	{
		// Vérifier si le chemin source est vide
		if (empty($sourcePath)) {
			return '';
		}

		// Récupérer les informations du chemin
		$pathInfo = pathinfo($sourcePath);

		// Remplacer 'source' par 'thumb' dans le répertoire
		$dirName = str_replace('source', 'thumb', $pathInfo['dirname']);

		// Construire le nouveau nom de fichier avec le préfixe
		$filename = $pathInfo['filename'];
		$extension = $pathInfo['extension'] ?? '';
		$newFilename = $prefix . $filename . ($extension ? '.' . $extension : '');

		// Retourner le chemin complet de la miniature
		return $dirName . '/' . $newFilename;
	}

	/**
	 * Envoi un mail - VERSION AVEC LOGS D'ERREURS
	 * @param string|array $to Destinataire
	 * @param string $subject Sujet
	 * @param string $content Contenu
	 * @param string|null $replyTo Adresse de réponse
	 * @param string $from Adresse d'expédition
	 * @return bool|string True si succès, message d'erreur sinon
	 */
	public function sendMail($to, $subject, $content, $replyTo = null, $from = 'no-reply@localhost')
	{
		// 1. Validation de sécurité des paramètres
		if (empty($to) || empty($subject) || empty($content)) {
			$this->saveLog("Échec sendMail : Paramètres manquants.");
			return 'Paramètres manquants';
		}

		// 2. Normalisation et validation des destinataires
		$destinatairesArray = is_array($to) ? $to : explode(',', $to);
		$destinatairesValides = [];
		foreach ($destinatairesArray as $email) {
			$email = trim($email);
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$destinatairesValides[] = $email;
			} else {
				$this->saveLog("Échec sendMail : Format email invalide ($email).");
				return 'Format email invalide: ' . $email;
			}
		}
		$destinatairesStr = implode(', ', $destinatairesValides);

		// 3. Préparation du Layout HTML
		ob_start();
		include 'core/layout/mail.php';
		$layout = ob_get_clean();

		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		$mail->CharSet = 'UTF-8';
		// Langue par défaut : en, ou fr si configuré
		if ($this->getData(['config', 'i18n', 'langAdmin']) === 'fr') {
			$mail->setLanguage('fr', 'core/class/phpmailer/phpmailer.lang-fr.php');
		} else {
			$mail->setLanguage('en');
		}

		try {
			// 4. Configuration du mode d'envoi (SMTP ou fallback mail)
			if ($this->getdata(['config', 'smtp', 'enable'])) {
				$mail->isSMTP();
				$mail->SMTPAutoTLS = false;
				$mail->Host = $this->getdata(['config', 'smtp', 'host']);
				$mail->Port = (int) $this->getdata(['config', 'smtp', 'port']);
				if ($this->getData(['config', 'smtp', 'auth'])) {
					$mail->Username = $this->getData(['config', 'smtp', 'username']);
					$mail->Password = helper::decrypt($this->getData(['config', 'smtp', 'username']), $this->getData(['config', 'smtp', 'password']));
					$mail->SMTPAuth = $this->getData(['config', 'smtp', 'auth']);
					$mail->SMTPSecure = $this->getData(['config', 'smtp', 'secure']);
					$mail->setFrom($this->getData(['config', 'smtp', 'username']));
					if (is_null($replyTo)) {
						$mail->addReplyTo($this->getData(['config', 'smtp', 'username']));
					} else {
						$mail->addReplyTo($replyTo);
					}
				}
			} else {
				   if (null !== $this->getData(['config', 'mailDomainName']) && $this->getData(['config', 'mailDomainName']) !== '') {
					   $host = $this->getData(['config', 'mailDomainName']);
				   } else {
					   // Extraire le domaine principal sans sous-domaine
					   $hostParts = explode('.', str_replace('www.', '', $_SERVER['HTTP_HOST']));
					   $hostPartsCount = count($hostParts);
					   if ($hostPartsCount >= 2) {
						   $host = $hostParts[$hostPartsCount - 2] . '.' . $hostParts[$hostPartsCount - 1];
					   } else {
						   $host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
					   }
				   }
				   $mail->setFrom('no-reply@' . $host, $this->getData(['locale', 'title']));
				   if (is_null($replyTo)) {
					   $mail->addReplyTo('no-reply@' . $host, $this->getData(['locale', 'title']));
				   } else {
					   $mail->addReplyTo($replyTo);
				   }
			}
			// Ajout des destinataires
			if (is_array($to)) {
				foreach ($to as $userMail) {
					$mail->addAddress($userMail);
				}
			} else {
				$mail->addAddress($to);
			}
			$mail->isHTML(true);
			$mail->Subject = $subject;
			   $mail->addCustomHeader('List-Unsubscribe', '<mailto:no-reply@' . $host . '>');
			$mail->Body = $layout;
			$mail->AltBody = strip_tags($content);
			// Gestion pièce jointe (optionnelle)
			if (isset($file_name) && $file_name !== '') {
				$mail->addAttachment(self::FILE_DIR . 'uploads/' . $file_name);
			}
			if ($mail->send()) {
				return true;
			} else {
				return $mail->ErrorInfo;
			}
		} catch (Exception $e) {
			$this->saveLog('Erreur mail : ' . $e->getMessage());
			return $e->getMessage();
		} catch (\Exception $e) {
			$this->saveLog('Erreur mail : ' . $e->getMessage());
			return $e->getMessage();
		}
	}

	/**
	 * Effacer un dossier non vide.
	 * @param string URL du dossier à supprimer
	 */
	public function deleteDir($path)
	{
		foreach (new DirectoryIterator($path) as $item) {
			if ($item->isFile())
				@unlink($item->getRealPath());
			if (!$item->isDot() && $item->isDir())
				$this->deleteDir($item->getRealPath());
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
			$success and
			$file = readdir($dir)
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
		// $directory = 'site/';
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
				$zip->addFile($filePath, str_replace('\\', '/', $relativePath));
			}
		}
		$zip->close();
	}

	/**
	 * Journalisation avec gestion de la taille maximale et compression
	 */
	public function saveLog($message = '')
	{
		// Chemin du fichier journal
		$logFile = self::DATA_DIR . 'journal.log';

		// Vérifier la taille du fichier
		if (file_exists($logFile) && filesize($logFile) > self::LOG_MAXSIZE) {
			$this->rotateLogFile();
		}

		// Création de l'entrée de journal
		$dataLog = helper::dateUTF8('%Y%m%d', time(), self::$i18nUI) . ';'
			. helper::dateUTF8('%H:%M', time(), self::$i18nUI) . ';';
		$dataLog .= helper::getIp($this->getData(['config', 'connect', 'anonymousIp'])) . ';';
		$dataLog .= empty($this->getUser('id')) ? 'visitor;' : $this->getUser('id') . ';';
		$dataLog .= $message ? $this->getUrl() . ';' . $message : $this->getUrl();
		$dataLog .= PHP_EOL;

		// Écriture dans le fichier si la journalisation est activée
		if ($this->getData(['config', 'connect', 'log'])) {
			file_put_contents($logFile, $dataLog, FILE_APPEND);
		}
	}

	/**
	 * Gère la rotation et la compression des fichiers journaux
	 * @return bool True si la rotation a réussi, false sinon
	 */
	private function rotateLogFile(): bool
	{
		$logFile = self::DATA_DIR . 'journal.log';

		// 1. Vérifications préliminaires
		if (!is_readable($logFile) || !is_writable(dirname($logFile))) {
			trigger_error('Fichier journal inaccessible ou dossier non inscriptible', E_USER_WARNING);
			return false;
		}

		// 2. Vérifier si rotation nécessaire (taille minimale)
		if (filesize($logFile) < 1024) { // < 1KB
			return true; // Pas besoin de rotation
		}

		// 3. Verrouillage du fichier pour éviter les race conditions
		$lockFile = fopen($logFile, 'r');
		if (!flock($lockFile, LOCK_EX | LOCK_NB)) {
			fclose($lockFile);
			trigger_error('Fichier journal déjà utilisé par un autre processus', E_USER_WARNING);
			return false;
		}

		$tempFile = null;
		try {
			// 4. Décaler d'abord les anciennes archives
			for ($i = self::LOG_MAXARCHIVE - 1; $i > 0; $i--) {
				$oldFile = self::DATA_DIR . 'journal-' . $i . '.log.gz';
				$newFile = self::DATA_DIR . 'journal-' . ($i + 1) . '.log.gz';

				// Si le fichier existe, le déplacer ou le supprimer
				if (file_exists($oldFile)) {
					if ($i >= self::LOG_MAXARCHIVE - 1) {
						if (!unlink($oldFile)) {
							trigger_error('Impossible de supprimer l\'ancienne archive: ' . $oldFile, E_USER_WARNING);
						}
					} else if (!rename($oldFile, $newFile)) {
						trigger_error(sprintf('Échec du déplacement de %s vers %s', $oldFile, $newFile), E_USER_WARNING);
					}
				}
			}

			// 5. Créer une copie temporaire pour la compression
			$tempFile = tempnam(sys_get_temp_dir(), 'zwii_log_');
			if (!copy($logFile, $tempFile)) {
				throw new \RuntimeException('Échec de la copie temporaire du journal');
			}

			// 6. Compresser L'ANCIEN contenu (sécurisé - AVANT de vider le fichier)
			$gzFile = self::DATA_DIR . 'journal-1.log.gz';
			$gz = @gzopen($gzFile, 'w9');
			if ($gz === false) {
				throw new \RuntimeException('Impossible d\'ouvrir le fichier de destination pour la compression');
			}

			$source = fopen($tempFile, 'r');
			if ($source === false) {
				gzclose($gz);
				throw new \RuntimeException('Impossible de lire le fichier source pour la compression');
			}

			// Utiliser stream_copy_to_stream pour de meilleures performances
			stream_copy_to_stream($source, $gz);

			fclose($source);
			gzclose($gz);

			// 7. Vérifier le succès de la compression AVANT de vider le fichier
			if (!file_exists($gzFile) || filesize($gzFile) === 0) {
				throw new \RuntimeException('La compression du journal a échoué');
			}

			// 8. SEULEMENT MAINTENANT vider le fichier journal principal (sécurisé)
			if (file_put_contents($logFile, '') === false) {
				throw new \RuntimeException('Impossible de vider le fichier journal');
			}

			// 9. Nettoyer le fichier temporaire
			@unlink($tempFile);
			$tempFile = null;

			// 10. Libérer le verrou
			flock($lockFile, LOCK_UN);
			fclose($lockFile);

			return true;

		} catch (\Exception $e) {
			// Nettoyer en cas d'erreur
			if ($tempFile) {
				@unlink($tempFile);
			}
			if (isset($lockFile)) {
				flock($lockFile, LOCK_UN);
				fclose($lockFile);
			}

			trigger_error('Erreur lors de la rotation du journal: ' . $e->getMessage(), E_USER_WARNING);
			return false;
		}
	}

	/**
	 * Retourne la signature d'un utilisateur
	 */
	public function signature($userId)
	{
		switch ($this->getData(['user', $userId, 'signature'])) {
			case 1:
				return $userId;
			case 2:
				return $this->getData(['user', $userId, 'pseudo']);
			case 3:
				return $this->getData(['user', $userId, 'firstname']) . ' ' . $this->getData(['user', $userId, 'lastname']);
			case 4:
				return $this->getData(['user', $userId, 'lastname']) . ' ' . $this->getData(['user', $userId, 'firstname']);
			default:
				return $this->getData(['user', $userId, 'firstname']);
		}
	}

	/**
	 * Retourne le chemin du dossier autorisé pour l'utilisateur selon son rôle et son profil
	 *
	 * @param string $userId L'identifiant de l'utilisateur
	 * @return string Dossier autorisé
	 *
	 * Pour les administrateurs :
	 * - Retourne un accès complet au répertoire source
	 *
	 * Pour les éditeurs :
	 * - Retourne un accès basé sur les paramètres de leur profil
	 * - En mode Campus : utilise homePath ou coursePath
	 * - En mode CMS : utilise le chemin standard
	 * - Retourne 'none' si aucun accès n'est accordé
	 * - Sinon retourne un accès limité à leur espace de contenu
	 */
	public function getUserPath($userId = null)
	{
		// Si aucun userId n'est fourni, utiliser l'id de l'utilisateur courant
		$userId = $userId ?? $this->getUser('id');

		// Vérifier si l'utilisateur est un administrateur
		switch ($this->getData(['user', $userId, 'role'])) {
			case self::ROLE_ADMIN:
				return self::FILE_DIR . 'source/';
			case self::ROLE_EDITOR:
			case self::ROLE_MODERATOR:
				return $this->getPermission('folder', 'path');
			default:
				// retourne un échec
				return 'none';
		}
	}

	/**
	 * Retourne le chemin normalisé d'un fichier selon le rôle utilisateur
	 * 
	 * @param string $filePath Chemin relatif du fichier (depuis site/file/source/)
	 * @param string|null $userId ID utilisateur (optionnel)
	 * @return string URL complète ou chaîne vide si accès refusé
	 */
	public function getNormalizedFilePath($filePath, $userId = null)
	{

		// Récupérer l'ID utilisateur si non fourni
		$userId = $userId ?? $this->getUser('id');

		// Contrôle de la saisie et des accès de l'utilisateur
		// Pas de chemin  ou pas d'accès au filemanager
		if (
			empty(
			$filePath ||
			$this->getUserPath($userId) === 'none'
		)
		)
			return null;

		// Récupérer l'utilisateur et son rôle
		$userPath = $this->getUserPath($userId);

		// Si le chemin commence déjà par 'site/file/source/', on le retourne tel quel
		if (strpos($filePath, 'site/file/source/') === 0) {
			return $filePath;
		}

		// Sinon, on ajoute le chemin de l'utilisateur au début du chemin du fichier
		return $userPath . ltrim($filePath, '/');


	}

	/**
	 * Retourne un chemin relatif depuis la racine du site sans la baseUrl
	 */
	public function getRelativePath($path)
	{
		return str_replace(helper::baseUrl(false), '', $path);
	}

	/**
	 * Ajoute récursivement les enfants pour TinyMCE
	 * @param array &$menu Référence au menu à construire
	 * @param array $childrenPageIds IDs des enfants
	 * @param string $rewrite URL rewrite
	 * @param int $depth Profondeur actuelle
	 */
	private function addTinyMceChildren(&$menu, $childrenPageIds, $rewrite, $depth = 1)
	{
		$maxDepth = 3; // Profondeur maximale pour TinyMCE

		if ($depth >= $maxDepth) {
			return;
		}

		foreach ($childrenPageIds as $childId) {
			// Vérifier que la page enfant existe
			if ($this->getData(['page', $childId])) {
				// Vérifier si cette page n'est pas déjà dans le menu pour éviter les doublons
				$alreadyExists = false;
				foreach ($menu as $existingItem) {
					if ($existingItem['value'] === $rewrite . $childId) {
						$alreadyExists = true;
						break;
					}
				}
				if ($alreadyExists) {
					continue;
				}

				$indent = str_repeat('── ', $depth);
				$childEntry = [
					'title' => $indent . html_entity_decode($this->getData(['page', $childId, 'title']), ENT_QUOTES),
					'value' => $rewrite . $childId,
					'menu' => []
				];

				// Récupérer les enfants de cette page en cherchant les pages avec ce parent
				$grandChildren = [];
				foreach ($this->getData(['page']) as $pageId => $pageData) {
					if ($pageData['parentPageId'] === $childId) {
						$grandChildren[] = $pageId;
					}
				}

				// Ajouter les enfants récursivement
				if (!empty($grandChildren)) {
					$this->addTinyMceChildren($childEntry['menu'], $grandChildren, $rewrite, $depth + 1);
				}

				$menu[] = $childEntry;
			}
		}
	}

}
