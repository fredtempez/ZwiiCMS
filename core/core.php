<?php

namespace Zwii\Cms;

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
class Core
{
	// Utilisation des traits
	use \Data;
	use \InputOutput;
	use \PageContent;
	use \Tools;
	use \UserPermission;
	use \Security;
	
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
	const ZWII_VERSION = '14.2.00.f';
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
					core::$coreNotices[] = $stageId;
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
			'path' => Helper::baseUrl(false, false),
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
		require_once('core/include/update.inc.php');

	}

}
