<?php

/**
 * This file is part of Zwii. *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @link http://zwiicms.com/
 */

class common {

	const DISPLAY_RAW = 0;
	const DISPLAY_JSON = 1;
	const DISPLAY_LAYOUT_BLANK = 2;
	const DISPLAY_LAYOUT_MAIN = 3;
	const DISPLAY_LAYOUT_LIGHT = 4;
	const DISPLAY_LAYOUT_POPUP = 5;
	const GROUP_BANNED = -1;
	const GROUP_VISITOR = 0;
	const GROUP_MEMBER = 1;
	const GROUP_MODERATOR = 2;
	const GROUP_ADMIN = 3;
	// Dossier de travail
	const BACKUP_DIR = 'site/backup/';
	const DATA_DIR = 'site/data/';
	const FILE_DIR = 'site/file/';
	const TEMP_DIR = 'site/tmp/';

	// Numéro de version 
	const ZWII_VERSION = '9.2.28';
	const ZWII_UPDATE_CHANNEL = "v9";

	public static $actions = [];
	public static $coreModuleIds = [
		'config',
		'install',
		'maintenance',
		'page',
        'search',
		'sitemap',
		'theme',
		'user'
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
		'targetLity' => false,
		'showBarEditButton' => false,
		'showPageContent' => false,
		'state' => false,
		'style' => '',
		'title' => null, // Null car un titre peut être vide
		// Trié par ordre d'exécution
		'vendor' => [
			'jquery',
			'normalize',
			'lity',
			'filemanager',
			'flatpickr', 
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
	public static $timezone;
	private $url = '';
	private $user = [];

	/**
	 * Constructeur commun
	 */
	public function __construct() {
		// Extraction des données http
		if(isset($_POST)) {
			$this->input['_POST'] = $_POST;
		}
		if(isset($_COOKIE)) {
			$this->input['_COOKIE'] = $_COOKIE;
		}

		// Import des données d'une version 8		
		$this->importData();

		// Génère le fichier de données lorque les deux fichiers sont absents ou seulement le thème est - installation fraîche par défaut
		if(file_exists(self::DATA_DIR.'core.json')   === false OR 
		   file_exists(self::DATA_DIR.'theme.json')  === false) {
			include_once('core/module/install/ressource/defaultdata.php');   
			$this->setData([install::$defaultData]);
			$this->saveData();
			chmod(self::DATA_DIR.'core.json', 0755);
			chmod(self::DATA_DIR.'theme.json', 0755);
		} 

		// Import des données d'un fichier data.json déjà présent
		if($this->data === [])  {
			$this->readData();
		}
	
		// Utilisateur connecté
		if($this->user === []) {
			$this->user = $this->getData(['user', $this->getInput('ZWII_USER_ID')]);
		}
		// Construit la liste des pages parents/enfants
		if($this->hierarchy['all'] === []) {
			$pages = helper::arrayCollumn($this->getData(['page']), 'position', 'SORT_ASC');
			// Parents
			foreach($pages as $pageId => $pagePosition) {
				if(
					// Page parent
					$this->getData(['page', $pageId, 'parentPageId']) === ""
					// Ignore les pages dont l'utilisateur n'a pas accès
					AND (
						$this->getData(['page', $pageId, 'group']) === self::GROUP_VISITOR
						OR (
							$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
							AND $this->getUser('group') >= $this->getData(['page', $pageId, 'group'])
						)
					)
				) {
					if($pagePosition !== 0) {
						$this->hierarchy['visible'][$pageId] = [];
					}
					if($this->getData(['page', $pageId, 'block']) === 'bar') {
						$this->hierarchy['bar'][$pageId] = [];
					}
					$this->hierarchy['all'][$pageId] = [];
				}
			}
			// Enfants
			foreach($pages as $pageId => $pagePosition) {
				if(
					// Page parent
					$parentId = $this->getData(['page', $pageId, 'parentPageId'])
					// Ignore les pages dont l'utilisateur n'a pas accès
					AND (
						(
							$this->getData(['page', $pageId, 'group']) === self::GROUP_VISITOR
							AND $this->getData(['page', $parentId, 'group']) === self::GROUP_VISITOR
						)
						OR (
							$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
							AND $this->getUser('group') >= $this->getData(['page', $parentId, 'group'])
							AND $this->getUser('group') >= $this->getData(['page', $pageId, 'group'])
						)
					)
				) {
					if($pagePosition !== 0) {
						$this->hierarchy['visible'][$parentId][] = $pageId;
					}
					if($this->getData(['page', $pageId, 'block']) === 'bar') {
						$this->hierarchy['bar'][$pageId] = [];
					}
					$this->hierarchy['all'][$parentId][] = $pageId;
				}
			}
		}
		// Construit l'url
		if($this->url === '') {
			if($url = $_SERVER['QUERY_STRING']) {
				$this->url = $url;
			}
			else {
				$this->url = $this->getData(['config', 'homePageId']);
			}
		}

		// Mise à jour des données core
		$this->update();

		// Données de proxy
		$proxy = $this->getData(['config','proxyType']) . $this->getData(['config','proxyUrl']) . ':' . $this->getData(['config','proxyPort']);
		if (!empty($this->getData(['config','proxyUrl'])) &&
			!empty($this->getData(['config','proxyPort'])) )  {
			$context = array(
				'http' => array(
					'proxy' => $proxy,
					'request_fulluri' => true,
					'verify_peer'      => false,
					'verify_peer_name' => false,
				),
				"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false
				)
			);
			stream_context_set_default($context);
		} 
	}

	/**
	 * Lecture des fichiers de données
	 * 
	 */
	public function readData() {
		// Trois tentatives
		for($i = 0; $i < 3; $i++) {
			$this->setData([json_decode(file_get_contents(self::DATA_DIR.'core.json'), true) + json_decode(file_get_contents(self::DATA_DIR.'theme.json'), true)]);
			if($this->data) {
				break;
			}
			elseif($i === 2) {
				exit('Unable to read data file.');
			}
			// Pause de 10 millisecondes
			usleep(10000);
		}
	}

	/**
	 * Import des données de la version 8
	 * Converti un fichier de données data.json puis le renomme
	 */
	public function importData() {
		if(file_exists(self::DATA_DIR.'data.json')) {
			// Trois tentatives
			for($i = 0; $i < 3; $i++) {
				$tempData = [json_decode(file_get_contents(self::DATA_DIR.'data.json'), true)];			
				if($tempData) {
					for($i = 0; $i < 3; $i++) {
						if(file_put_contents(self::DATA_DIR.'core.json', json_encode(array_slice($tempData[0],0,5)), LOCK_EX) !== false) {
							break;
						}
						// Pause de 10 millisecondes
						usleep(10000);
					}
					for($i = 0; $i < 3; $i++) {
						if(file_put_contents(self::DATA_DIR.'theme.json', json_encode(array_slice($tempData[0],5)), LOCK_EX) !== false) {
							break;
						}
						// Pause de 10 millisecondes
						usleep(10000);
					}					
					rename (self::DATA_DIR.'data.json',self::DATA_DIR.'imported_data.json');
					break;
				}
				elseif($i === 2) {
					exit('Unable to read data file.');
				}
				// Pause de 10 millisecondes
				usleep(10000);
			}
		
		}
	}

	/**
	 * Ajoute les valeurs en sortie
	 * @param array $output Valeurs en sortie
	 */
	public function addOutput($output) {
		$this->output = array_merge($this->output, $output);
	}

	/**
	 * Ajoute une notice de champ obligatoire
	 * @param string $key Clef du champ
	 */
	public function addRequiredInputNotices($key) {
		// La clef est un tableau
		if(preg_match('#\[(.*)\]#', $key, $secondKey)) {
			$firstKey = explode('[', $key)[0];
			$secondKey = $secondKey[1];
			if(empty($this->input['_POST'][$firstKey][$secondKey])) {
				common::$inputNotices[$firstKey . '_' . $secondKey] = 'Obligatoire';
			}
		}
		// La clef est une chaine
		elseif(empty($this->input['_POST'][$key])) {
			common::$inputNotices[$key] = 'Obligatoire';
		}
	}

	/**
	 * Check du token CSRF (true = bo
	 */
	public function checkCSRF() {
		return ((empty($_POST['csrf']) OR hash_equals($_SESSION['csrf'], $_POST['csrf']) === false) === false);
	}

	/**
	 * Supprime des données
	 * @param array $keys Clé(s) des données
	 */
	public function deleteData($keys) {
		switch(count($keys)) {
			case 1 :
				unset($this->data[$keys[0]]);
				break;
			case 2:
				unset($this->data[$keys[0]][$keys[1]]);
				break;
			case 3:
				unset($this->data[$keys[0]][$keys[1]][$keys[2]]);
				break;
			case 4:
				unset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]]);
				break;
			case 5:
				unset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]]);
				break;
			case 6:
				unset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]]);
				break;
			case 7:
				unset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]][$keys[6]]);
				break;
		}
	}


	/**
	 * Récupérer une copie d'écran du site Web pour le tag image si le fichier n'existe pas
	 * En local, copie du site décran de ZwiiCMS
	 */	
	public function makeImageTag () {
		if (!file_exists(self::FILE_DIR.'source/screenshot.png'))
		{ 			
			if ( strpos(helper::baseUrl(false),'localhost') == 0 AND strpos(helper::baseUrl(false),'127.0.0.1') == 0)	{							
				$googlePagespeedData = @file_get_contents('https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url='. helper::baseUrl(false) .'&screenshot=true');	
				if ($googlePagespeedData  !== false) {
					$googlePagespeedData = json_decode($googlePagespeedData, true);
					$screenshot = $googlePagespeedData['screenshot']['data'];
					$screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);
					$data = 'data:image/jpeg;base64,'.$screenshot;
					$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));			
					file_put_contents( self::FILE_DIR.'source/screenshot.png',$data);
				}
			}
		}
	}
	


	/**
	 * Accède aux données
	 * @param array $keys Clé(s) des données
	 * @return mixed
	 */
	public function getData($keys = null) {
		// Retourne l'ensemble des données
		if($keys === null) {
			return $this->data;
		}
		// Décent dans les niveaux de la variable $data
		$data = $this->data;
		foreach($keys as $key) {
			// Si aucune donnée n'existe retourne null
			if(isset($data[$key]) === false) {
				return null;
			}
			// Sinon décent dans les niveaux
			else {
				$data = $data[$key];
			}
		}
		// Retourne les données
		return $data;
	}

	/**
	 * Accède à la liste des pages parents et de leurs enfants
	 * @param int $parentId Id de la page parent
	 * @param bool $onlyVisible Affiche seulement les pages visibles
	 * @param bool $onlyBlock Affiche seulement les pages de type barre
	 * @return array
	 */
	public function getHierarchy($parentId = null, $onlyVisible = true, $onlyBlock = false) {
		$hierarchy = $onlyVisible ? $this->hierarchy['visible'] : $this->hierarchy['all'];
		$hierarchy = $onlyBlock ? $this->hierarchy['bar'] : $hierarchy;		
		// Enfants d'un parent
		if($parentId) {
			if(array_key_exists($parentId, $hierarchy)) {
				return $hierarchy[$parentId];
			}
			else {
				return [];
			}
		}
		// Parents et leurs enfants
		else {
			return $hierarchy;
		}
	}

	/**
	 * Accède à une valeur des variables http (ordre de recherche en l'absence de type : _COOKIE, _POST)
	 * @param string $key Clé de la valeur
	 * @param int $filter Filtre à appliquer à la valeur
	 * @param bool $required Champ requis
	 * @return mixed
	 */
	public function getInput($key, $filter = helper::FILTER_STRING_SHORT, $required = false) {
		// La clef est un tableau
		if(preg_match('#\[(.*)\]#', $key, $secondKey)) {
			$firstKey = explode('[', $key)[0];
			$secondKey = $secondKey[1];
			foreach($this->input as $type => $values) {
				// Champ obligatoire
				if($required) {
					$this->addRequiredInputNotices($key);
				}
				// Check de l'existence
				// Également utile pour les checkboxs qui ne retournent rien lorsqu'elles ne sont pas cochées
				if(
					array_key_exists($firstKey, $values)
					AND array_key_exists($secondKey, $values[$firstKey])
				) {
					// Retourne la valeur filtrée
					if($filter) {
						return helper::filter($this->input[$type][$firstKey][$secondKey], $filter);
					}
					// Retourne la valeur
					else {
						return $this->input[$type][$firstKey][$secondKey];
					}
				}
			}
		}
		// La clef est une chaine
		else {
			foreach($this->input as $type => $values) {
				// Champ obligatoire
				if($required) {
					$this->addRequiredInputNotices($key);
				}
				// Check de l'existence
				// Également utile pour les checkboxs qui ne retournent rien lorsqu'elles ne sont pas cochées
				if(array_key_exists($key, $values)) {
					// Retourne la valeur filtrée
					if($filter) {
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
	public function getUrl($key = null) {
		// Url complète
		if($key === null) {
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
	public function getUser($key) {
		if(is_array($this->user) === false) {
			return false;
		}
		elseif($key === 'id') {
			return $this->getInput('ZWII_USER_ID');
		}
		elseif(array_key_exists($key, $this->user)) {
			return $this->user[$key];
		}
		else {
			return false;
		}
	}

	/**
	 * Check qu'une valeur est transmise par la méthode _POST
	 * @return bool
	 */
	public function isPost() {
		return ($this->checkCSRF() AND $this->input['_POST'] !== []);
	}

	/**
	 * Enregistre les données dans deux fichiers séparés
	 */
	public function saveData() {
		// Save config core page module et user
		// 5 premières clés principales
		// Trois tentatives
		$core = ['core' => $this->getData(['core'])];
		$config = ['config' => $this->getData(['config'])];
		$page = ['page' => $this->getData(['page'])];
		$module = ['module' => $this->getData(['module'])];
		$user = ['user' => $this->getData(['user'])];

		for($i = 0; $i < 3; $i++) {
			if(file_put_contents(self::DATA_DIR.'core.json', json_encode( $core + $config + $page + $module + $user ) , LOCK_EX) !== false) {
				break;
			}
			// Pause de 10 millisecondes
			usleep(10000);
		}
		// Save theme
		// dernière clé principale
		// Trois tentatives
		$theme = ['theme' => $this->getData(['theme'])];		
		for($i = 0; $i < 3; $i++) {
			if(file_put_contents(self::DATA_DIR.'theme.json', json_encode($theme), LOCK_EX) !== false) {
				break;
			}
			// Pause de 10 millisecondes
			usleep(10000);
		}
	}

	/**
	 * Génére un fichier json avec la liste des pages
	 * 
    */
    public function pages2Json() {
    // Sauve la liste des pages pour TinyMCE
		$parents = [];
        $rewrite = (helper::checkRewrite()) ? '' : '?';
        // Boucle de recherche des pages actives
		foreach($this->getHierarchy(null,false,false) as $parentId => $childIds) {
			$children = [];
			// Exclure les barres
			if ($this->getData(['page', $parentId, 'block']) !== 'bar' ) { 
				// Boucler sur les enfants et récupérer le tableau children avec la liste des enfants
				foreach($childIds as $childId) {					
					$children [] = [ 'title' => ' » '. html_entity_decode($this->getData(['page', $childId, 'title']), ENT_QUOTES) ,
								'value'=> $rewrite.$childId
					];				
				}
				// Traitement 
				if (empty($childIds)) {						
					// Pas d'enfant, uniuement l'entrée du parent
					$parents [] = ['title' =>   html_entity_decode($this->getData(['page', $parentId, 'title']), ENT_QUOTES) ,
									'value'=> $rewrite.$parentId 		
					];	
				} else {
					// Des enfants, on ajoute la page parent en premier
					array_unshift ($children ,  ['title' => html_entity_decode($this->getData(['page', $parentId, 'title']), ENT_QUOTES) ,
									'value'=> $rewrite.$parentId 		
					]);	
					// puis on ajoute les enfants au parent
					$parents [] = ['title' => html_entity_decode($this->getData(['page', $parentId, 'title']), ENT_QUOTES) ,
									'value'=> $rewrite.$parentId ,  
									'menu' => $children 
					];							
				} 											
			}
		}
        // Sitemap et Search
        $children = [];
        $children [] = ['title'=>'Rechercher dans le site',
           'value'=>$rewrite.'search'
          ];
        $children [] = ['title'=>'Plan du site',
           'value'=>$rewrite.'sitemap'
          ];
        $parents [] = ['title' => 'Pages spéciales',
                      'value' => '#',
                      'menu' => $children
                      ];
		
		// Enregistrement : 3 tentatives
		for($i = 0; $i < 3; $i++) {
			if (file_put_contents ('core/vendor/tinymce/link_list.json', json_encode($parents), LOCK_EX) !== false) {
				break;
			}
			// Pause de 10 millisecondes
			usleep(10000);
		}	
	}

	/**
	 * Génére un fichier robots.txt à l'installation
	 * Si le fichier exite déjà les commandes sont ajoutées
	 */

	 public function createRobots() {

		$robotValue = 
						PHP_EOL .
						'# ZWII CONFIG ---------' . PHP_EOL .
						'User-agent: *' . PHP_EOL .
						'Allow: /site/file/' .PHP_EOL .						
						'Disallow: /site/' .PHP_EOL .
						'Sitemap: ' . helper::baseUrl(false) . 'sitemap.xml' . PHP_EOL .
						'Sitemap: ' . helper::baseUrl(false) . 'sitemap.xml.gz' . PHP_EOL .
						'# ZWII CONFIG  ---------' . PHP_EOL ;

		if (file_exists('robots.txt')) {			
			return(file_put_contents(
				'robots.txt',
				$robotValue,
				FILE_APPEND
				));
		} else  {
			// Sinon on crée un fichier
			return(file_put_contents(
				'robots.txt',
				 $robotValue
				));
		}
	 }


	/**
	 * Génére un fichier un fchier sitemap.xml
	 * https://github.com/icamys/php-sitemap-generator
	 * $command valeurs possible
	 * all : génére un site map complet 
	 * Sinon contient id de la page à créer
	*/

	public function createSitemap($command = "all") {

		require_once "core/vendor/sitemap/SitemapGenerator.php";

		$timezone = $this->getData(['config','timezone']);

		$sitemap = new \Icamys\SitemapGenerator\SitemapGenerator(helper::baseurl());

		// will create also compressed (gzipped) sitemap
		$sitemap->createGZipFile = true;

		// determine how many urls should be put into one file
		// according to standard protocol 50000 is maximum value (see http://www.sitemaps.org/protocol.html)
		$sitemap->maxURLsPerSitemap = 50000;

		// sitemap file name
		$sitemap->sitemapFileName = "sitemap.xml";
		
		$datetime = new DateTime(date('c'));
		$datetime->format(DateTime::ATOM); // Updated ISO8601
		// sitemap index file name
		$sitemap->sitemapIndexFileName = "sitemap-index.xml";
		foreach($this->getHierarchy(null, null, null) as $parentPageId => $childrenPageIds) {
			// Exclure les barres et les pages non publiques et les pages masquées
			if ($this->getData(['page',$parentPageId,'group']) !== 0  || 
				$this->getData(['page', $parentPageId, 'block']) === 'bar' )  {
				continue;
			}
			// Page désactivée, traiter les sous-pages sans prendre en compte la page parente.
			if ($this->getData(['page', $parentPageId, 'disable']) !== true ) {
				$sitemap->addUrl ($parentPageId,$datetime);
			}
			// Sous-pages
			foreach($childrenPageIds as $childKey) {
				if ($this->getData(['page',$childKey,'group']) !== 0 || $this->getData(['page', $childKey, 'disable']) === true)  {
					continue;
				}
				$sitemap->addUrl($childKey,$datetime);

				// La sous-page est un blog
				if ($this->getData(['page', $childKey, 'moduleId']) === 'blog' &&
				   !empty($this->getData(['module',$childKey])) ) {
					foreach($this->getData(['module',$childKey]) as $articleId => $article) {
						if($this->getData(['module',$childKey,$articleId,'state']) === true) {
							$date = $this->getData(['module',$childKey,$articleId,'publishedOn']);
							$sitemap->addUrl( $childKey . '/' . $articleId , new DateTime("@{$date}",new DateTimeZone($timezone)));
						}			
					}
				}							
			}
			// Articles du blog
			if ($this->getData(['page', $parentPageId, 'moduleId']) === 'blog' &&
				!empty($this->getData(['module',$parentPageId])) ) {				
				foreach($this->getData(['module',$parentPageId]) as $articleId => $article) {
					if($this->getData(['module',$parentPageId,$articleId,'state']) === true) {		
						$date = $this->getData(['module',$parentPageId,$articleId,'publishedOn']);
						$sitemap->addUrl( $parentPageId . '/' . $articleId , new DateTime("@{$date}",new DateTimeZone($timezone)));
					}
				}
			}
		}		
		
		// generating internally a sitemap
		$sitemap->createSitemap();

		// writing early generated sitemap to file
		$sitemap->writeSitemap();
		
		return(file_exists('sitemap.xml'));

	}
	


		/**
	 * Envoi un mail
	 * @param string|array $to Destinataire
	 * @param string $subject Sujet
	 * @param string $content Contenu
	 * @return bool
	 */
	public function sendMail($to, $subject, $content, $replyTo = null) {
		// Utilisation de PHPMailer version 6.0.6
		require_once "core/vendor/phpmailer/phpmailer.php";
		require_once "core/vendor/phpmailer/exception.php";

		// Layout
		ob_start();
		include 'core/layout/mail.php';
		$layout = ob_get_clean();
		// Mail
		try{
			$mail = new PHPMailer\PHPMailer\PHPMailer;
			$mail->CharSet = 'UTF-8';
			$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
			$mail->setFrom('no-reply@' . $host, $this->getData(['config', 'title']));
			if (empty($replyTo)) {
				$mail->addReplyTo('no-reply@' . $host, $this->getData(['config', 'title']));
			} else {
				$mail->addReplyTo($replyTo);
			}			
			if(is_array($to)) {
					foreach($to as $userMail) {
							$mail->addAddress($userMail);
					}
			}
			else {
					$mail->addAddress($to);
			}
			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $layout;
			$mail->AltBody = strip_tags($content);
			if($mail->send()) {
					return true;
			}
			else {
					return $mail->ErrorInfo;
			}
		} catch (phpmailerException $e) {
			return $e->errorMessage();
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Insert des données
	 * @param array $keys Clé(s) des données
	 */
	public function setData($keys) {
		switch(count($keys)) {
			case 1:
				$this->data = $keys[0];
				break;
			case 2:
				$this->data[$keys[0]] = $keys[1];
				break;
			case 3:
				$this->data[$keys[0]][$keys[1]] = $keys[2];
				break;
			case 4:
				$this->data[$keys[0]][$keys[1]][$keys[2]] = $keys[3];
				break;
			case 5:
				$this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]] = $keys[4];
				break;
			case 6:
				$this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]] = $keys[5];
				break;
			case 7:
				$this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]] = $keys[6];
				break;
		}
	}

	/**
	 * Mises à jour
	 */
	private function update() {
		// Version 8.1.0
		if($this->getData(['core', 'dataVersion']) < 810) {
			$this->setData(['config', 'timezone', 'Europe/Paris']);
			$this->setData(['core', 'dataVersion', 810]);
			$this->saveData();
		}
		// Version 8.2.0
		if($this->getData(['core', 'dataVersion']) < 820) {
			$this->setData(['theme', 'body', 'backgroundColor', 'rgba(236, 239, 241, 1)']);
			$this->setData(['theme', 'site', 'backgroundColor', 'rgba(255, 255, 255, 1)']);
			$this->setData(['theme', 'text', 'fontSize', '13px']);
			$this->setData(['theme', 'text', 'textColor', 'rgba(33, 34, 35, 1)']);
			$this->setData(['theme', 'menu', 'fontSize', '1em']);
			$this->setData(['theme', 'menu', 'textColor', 'rgba(255, 255, 255, 1)']);
			$this->setData(['theme', 'header', 'fontSize', '2em']);
			$this->setData(['theme', 'footer', 'textColor', 'rgba(33, 34, 35, 1)']);
			$this->setData(['core', 'dataVersion', 820]);
			$this->saveData();
		}
		// Version 8.2.2
		if($this->getData(['core', 'dataVersion']) < 822) {
			$this->setData(['config', 'maintenance', false]);
			$this->setData(['core', 'dataVersion', 822]);
			$this->saveData();
		}
		// Version 8.2.6
		if($this->getData(['core', 'dataVersion']) < 826) {
			$this->setData(['theme','header','linkHome',true]);
			$this->setData(['core', 'dataVersion', 826]);
			$this->saveData();
		}
		// Version 8.3.1
		if($this->getData(['core', 'dataVersion']) < 831) {
			$this->setData(['theme','header','imageContainer','auto']);
			$this->setData(['core', 'dataVersion', 831]);
			$this->saveData();
		}

		// Version 8.4.0
		if($this->getData(['core', 'dataVersion']) < 840) {
			$this->setData(['config','itemsperPage',10]);
			$this->setData(['core', 'dataVersion', 840]);
			$this->saveData();
		}
		// Version 8.4.4
		if($this->getData(['core', 'dataVersion']) < 844) {			
			$this->setData(['core', 'dataVersion', 844]);
			$this->saveData();
		}			
		// Version 8.4.6
		if($this->getData(['core', 'dataVersion']) < 846) {		
			$this->setData(['config','itemsperPage',10]);
			$this->setData(['core', 'dataVersion', 846]);
			$this->saveData();
		}		
		// Version 8.5.0
		if($this->getData(['core', 'dataVersion']) < 850) {
			$this->setData(['theme','menu','font','Open+Sans']);
			$this->setData(['core', 'dataVersion', 850]);
			$this->saveData();
		}	
		// Version 8.5.1
		if($this->getData(['core', 'dataVersion']) < 851) {
			$this->setData(['config','itemsperPage',10]);
			$this->deleteData(['config','ItemsperPage']);
			$this->setData(['core', 'dataVersion', 851]);
			$this->saveData();
		}	
		// Version 9.0.0
		if($this->getData(['core', 'dataVersion']) < 9000) {
			$this->deleteData(['theme', 'site', 'block']);
			if ($this->getData(['theme','menu','position']) === 'body-top') {
				$this->setData(['theme','menu','position','top']);
			}
			$this->setData(['theme', 'menu','fixed',false]);						
			$this->setData(['core', 'dataVersion', 9000]);
			$this->saveData();
		}	
		// Version 9.0.01
		if($this->getData(['core', 'dataVersion']) < 9001) {
			$this->deleteData(['config', 'social', 'googleplusId']);
			$this->setData(['core', 'dataVersion', 9001]);
			$this->saveData();
		}
		// Version 9.0.08
		if($this->getData(['core', 'dataVersion']) < 9008) {
			$this->setData(['theme', 'footer', 'textTransform','none']);
			$this->setData(['theme', 'footer', 'fontWeight','normal']);
			$this->setData(['theme', 'footer', 'fontSize','.8em']);
			$this->setData(['theme', 'footer', 'font','Open+Sans']);	
			$this->setData(['core', 'dataVersion', 9008]);
			$this->saveData();
		}
		// Version 9.0.09
		if($this->getData(['core', 'dataVersion']) < 9009) {
			$this->setData(['core', 'dataVersion', 9009]);
			$this->saveData();
		}
		// Version 9.0.10
		if($this->getData(['core', 'dataVersion']) < 9010) {
			$this->deleteData(['config', 'social', 'googleplusId']);			
			$this->setData(['core', 'dataVersion', 9010]);
			$this->saveData();
		}
		// Version 9.0.11
		if($this->getData(['core', 'dataVersion']) < 9011) {
			if ($this->getData(['theme','menu','position']) === 'body')
				$this->setData(['theme','menu','position','site']);
			$this->setData(['core', 'dataVersion', 9011]);
			$this->saveData();
		}
		// Version 9.0.17
		if($this->getData(['core', 'dataVersion']) < 9017) {
			$this->setData(['theme','footer','displayVersion', true ]);
			$this->setData(['core', 'dataVersion', 9017]);
			$this->saveData();
		}
		// Version 9.1.0
		if($this->getData(['core', 'dataVersion']) < 9100) {
			$this->setData(['theme','footer','displayVersion', true ]);
			$this->setData(['theme','footer','displaySiteMap', true ]);
			$this->setData(['theme','footer','displayCopyright', false ]);
			$this->setData(['core', 'dataVersion', 9100]);
			$this->saveData();
		}
		// Version 9.2.00
		if($this->getData(['core', 'dataVersion']) < 9200) {
			$this->setData(['theme','footer','template', 3 ]);
			$this->setData(['theme','footer','margin', true ]);			
			$this->setData(['theme','footer','displayLegal', !empty($this->getdata(['config','legalPageId'])) ]);
			$this->setData(['theme','footer','displaySearch', false ]);
			$this->setData(['config','social','githubId', '' ]);
			$this->setData(['core', 'dataVersion', 9200]);
			$this->saveData();
		}
		// Version 9.2.05
		if($this->getData(['core', 'dataVersion']) < 9205) {
			// Nettoyage Swiper
			if (file_exists('core/vendor/tinymce/templates/swiper.html')) {
				unlink ('core/vendor/tinymce/templates/swiper.html');
			}
			if (is_dir('core/vendor/swiper')) {
				$dir = getcwd();
				chdir('core/vendor/swiper');
				$files = glob('*');
				foreach($files as $file) unlink($file);
				chdir($dir);
				rmdir ('core/vendor/swiper/');
			}
			$this->setData(['core', 'dataVersion', 9205]);
			$this->saveData();
		}
		// Version 9.2.10
		if($this->getData(['core', 'dataVersion']) < 9210) {
			
			// Utile pour l'installation d'un backup sur un autre serveur
			//$this->setData(['core', 'baseUrl', helper::baseUrl(false,false) ]);

			// Suppression d'une option de hauteur de la bannière
			if ($this->getData(['theme', 'header','height']) === 'none') {
				$this->setData(['theme', 'header','height','150px']);
			}	
			// Changer le nom de la clé linkHome -> linkHomePage
			$this->setdata(['theme','header','linkHomePage',$this->getData(['theme','header','linkHome'])]);
			$this->deleteData(['theme','header','linkHome']);

			// Préparation des clés de légendes pour la v10
			// Construire une liste plate de parents et d'enfants

			$pageList = array();
			
			foreach ($this->getHierarchy(null,null,null) as $parentKey=>$parentValue) {
				$pageList [] = $parentKey;
				foreach ($parentValue as $childKey) {
					$pageList [] = $childKey;
				}
			}			
			// Parcourir toutes les pages
			foreach ($pageList as $parentKey => $parent) {
				//La page a une galerie
				if ($this->getData(['page',$parent,'moduleId']) === 'gallery' ) {
					// Lire les données du module
					// Parcourir les dossiers de la galerie
					$tempData =  $this->getData(['module', $parent]);			
					foreach ($tempData as $galleryKey => $galleryItem) {
						foreach ($galleryItem as $legendKey => $legendValue) {
							// Recherche la clé des légendes
							if ($legendKey === 'legend') {
								foreach ($legendValue as $itemKey=>$itemValue) {		
									// Ancien nom avec un point devant l'extension ?
									if (strpos($itemKey,'.') > 0) {
										// Créer une nouvelle clé
										$this->setData(['module', $parent, $galleryKey, 'legend',str_replace('.','',$itemKey),$itemValue]);
										// Supprimer la valeur
										$this->deleteData(['module', $parent, $galleryKey, 'legend',$itemKey]);
									}									
								}
							}
						}
					}
				}
			}			
			$this->setData(['core', 'dataVersion', 9210]);
			$this->saveData();
		}
		// Version 9.2.11
		if($this->getData(['core', 'dataVersion']) < 9211) {
			$autoUpdate= mktime(0, 0, 0);
			$this->setData(['core', 'lastAutoUpdate', $autoUpdate]);
			$this->setData(['config','autoUpdate', true]);
			$this->setData(['core', 'dataVersion', 9211]);
			$this->saveData();
		}
		// Version 9.2.12
		if($this->getData(['core', 'dataVersion']) < 9212) {
			$this->setData(['theme','menu', 'activeColorAuto',true]);
			$this->setData(['theme','menu', 'activeColor','rgba(255, 255, 255, 1)']);
			$this->setData(['core', 'dataVersion', 9212]);
			$this->saveData();
		}
		// Version 9.2.15
		if($this->getData(['core', 'dataVersion']) < 9215) {
			// Données de la barre de langue dans le menu
			$this->setData(['theme','menu','burgerTitle',true]);
			$this->setData(['core', 'dataVersion', 9215]);
			$this->saveData();
		}
		// Version 9.2.16
		if($this->getData(['core', 'dataVersion']) < 9216) {
			// Utile pour l'installation d'un backup sur un autre serveur
			// mais avec la réécriture d'URM
			$this->setData(['core', 'baseUrl', helper::baseUrl(true,false) ]);
			$this->setData(['core', 'dataVersion', 9216]);
			$this->saveData();
		}
		// Version 9.2.21
		if($this->getData(['core', 'dataVersion']) < 9221) {
			// Utile pour l'installation d'un backup sur un autre serveur
			// mais avec la réécriture d'URM
			$this->setData(['theme', 'body', 'toTopbackgroundColor', 'rgba(33, 34, 35, .8)' ]);
			$this->setData(['theme', 'body', 'toTopColor', 'rgba(255, 255, 255, 1)' ]);
			$this->setData(['core', 'dataVersion', 9221]);
			$this->saveData();
		}		
		// Version 9.2.23
		if($this->getData(['core', 'dataVersion']) < 9223) {
			// Utile pour l'installation d'un backup sur un autre serveur
			// mais avec la réécriture d'URM
			$this->setData(['config', 'proxyUrl', '' ]);
			$this->setData(['config', 'proxyPort', '' ]);
			$this->setData(['config', 'proxyType', 'tcp://' ]);
			$this->setData(['core', 'dataVersion', 9223]);
			$this->saveData();
		}
		// Version 9.2.27
		if($this->getData(['core', 'dataVersion']) < 9227) {
			// Forcer la régénération du thème
			if (file_exists(self::DATA_DIR.'theme.css') === false) {
				unlink (self::DATA_DIR.'theme.css');
			}
			$this->setData(['core', 'dataVersion', 9227]);
			$this->saveData();
		}	
	}
}

class core extends common {

	/**
	 * Constructeur du coeur
	 */
	public function __construct() {
		parent::__construct();
		// Token CSRF
		if(empty($_SESSION['csrf'])) {
			$_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
		}
		// Fuseau horaire
		self::$timezone = $this->getData(['config', 'timezone']); // Utile pour transmettre le timezone à la classe helper
		date_default_timezone_set(self::$timezone);
		// Supprime les fichiers temporaires
		$lastClearTmp = mktime(0, 0, 0);
		if($lastClearTmp > $this->getData(['core', 'lastClearTmp']) + 86400) {
			$iterator = new DirectoryIterator(self::TEMP_DIR);
			foreach($iterator as $fileInfos) {
				if( $fileInfos->isFile() && 
					$fileInfos->getBasename() !== '.htaccess' &&
					$fileInfos->getBasename() !== '.gitkeep'
				) {
					@unlink($fileInfos->getPathname());
				}
			}
			// Date de la dernière suppression
			$this->setData(['core', 'lastClearTmp', $lastClearTmp]);
			// Enregistre les données
			$this->saveData();
		}
		// Backup automatique des données
		$lastBackup = mktime(0, 0, 0);
		if(
			$this->getData(['config', 'autoBackup'])
			AND $lastBackup > $this->getData(['core', 'lastBackup']) + 86400
			AND $this->getData(['user']) // Pas de backup pendant l'installation
		) {
			// Copie du fichier de données
			copy(self::DATA_DIR.'core.json', self::BACKUP_DIR . date('Y-m-d', $lastBackup) . '.json');
			// Date du dernier backup
			$this->setData(['core', 'lastBackup', $lastBackup]);
			// Enregistre les données
			$this->saveData();
			// Supprime les backups de plus de 30 jours
			$iterator = new DirectoryIterator(self::BACKUP_DIR);
			foreach($iterator as $fileInfos) {
				if(
					$fileInfos->isFile()
					AND $fileInfos->getBasename() !== '.htaccess'
					AND $fileInfos->getMTime() + (86400 * 30) < time()
				) {
					@unlink($fileInfos->getPathname());
				}
			}
		}
		// Crée le fichier de personnalisation avancée
		if(file_exists(self::DATA_DIR.'custom.css') === false) {
			file_put_contents(self::DATA_DIR.'custom.css', file_get_contents('core/module/theme/resource/custom.css'));
			chmod(self::DATA_DIR.'custom.css', 0755);
		}
		// Crée le fichier de personnalisation
		if(file_exists(self::DATA_DIR.'theme.css') === false) {
			file_put_contents(self::DATA_DIR.'theme.css', '');
			chmod(self::DATA_DIR.'theme.css', 0755);
		}
		// Check la version
		$cssVersion = preg_split('/\*+/', file_get_contents(self::DATA_DIR.'theme.css'));
		if(empty($cssVersion[1]) OR $cssVersion[1] !== md5(json_encode($this->getData(['theme'])))) {
			// Version
			$css = '/*' . md5(json_encode($this->getData(['theme']))) . '*/';
			// Import des polices de caractères
			$css .= '@import url("https://fonts.googleapis.com/css?family=' . $this->getData(['theme', 'text', 'font']) . '|' . $this->getData(['theme', 'title', 'font']) . '|' . $this->getData(['theme', 'header', 'font']) .  '|' . $this->getData(['theme', 'menu', 'font']) . '");';
			// Fond du site
			$colors = helper::colorVariants($this->getData(['theme', 'body', 'backgroundColor']));
			$css .= 'body{background-color:' . $colors['normal'] . ';font-family:"' . str_replace('+', ' ', $this->getData(['theme', 'text', 'font'])) . '",sans-serif}';
			if($themeBodyImage = $this->getData(['theme', 'body', 'image'])) {
				$css .= 'body{background-image:url("../file/source/' . $themeBodyImage . '");background-position:' . $this->getData(['theme', 'body', 'imagePosition']) . ';background-attachment:' . $this->getData(['theme', 'body', 'imageAttachment']) . ';background-size:' . $this->getData(['theme', 'body', 'imageSize']) . ';background-repeat:' . $this->getData(['theme', 'body', 'imageRepeat']) . '}';
			}
			// Icône BacktoTop
			$css .= '#backToTop {background-color:' .$this->getData(['theme', 'body', 'toTopbackgroundColor']). ';color:'.$this->getData(['theme', 'body', 'toTopColor']).';}';
			// Site
			$colors = helper::colorVariants($this->getData(['theme', 'link', 'textColor']));
			$css .= 'a{color:' . $colors['normal'] . '}';
			$css .= 'a:hover{color:' . $colors['darken'] . '}';
			$css .= 'body,.row > div{font-size:' . $this->getData(['theme', 'text', 'fontSize']) . '}';
			$css .= 'body,.block h4{color:' . $this->getData(['theme', 'text', 'textColor']) . '}';
			$css .= 'select,input[type=\'email\'],input[type=\'text\'],textarea{color:' . $this->getData(['theme', 'text', 'backgroundColor']) . '}';
			// Couleur fixée dans admin.css
			//$css .= '.button.buttonGrey,.button.buttonGrey:hover{color:' . $this->getData(['theme', 'text', 'textColor']) . '}';
			$css .= '.container{max-width:' . $this->getData(['theme', 'site', 'width']) . '}';
			$css .= $this->getData(['theme', 'site', 'width']) === '100%' ? '#site{margin:0 auto !important;} body{margin:0 auto !important;}  #bar{margin:0 auto !important;} body > header{margin:0 auto !important;} body > nav {margin: 0 auto !important;} body > footer {margin:0 auto !important;}': '';
			$css .= $this->getData(['theme', 'site', 'width']) === '750px' ? '.button, button{font-size:0.8em;}' : '';
			$css .= '#site{background-color:' . $this->getData(['theme', 'site', 'backgroundColor']) . ';border-radius:' . $this->getData(['theme', 'site', 'radius']) . ';box-shadow:' . $this->getData(['theme', 'site', 'shadow']) . ' #212223;}';
			$css .= '.editorWysiwyg {background-color:' . $this->getData(['theme', 'site', 'backgroundColor']) . ';}';
			$colors = helper::colorVariants($this->getData(['theme', 'button', 'backgroundColor']));
			$css .= '.speechBubble,.button,.button:hover,button[type=\'submit\'],.pagination a,.pagination a:hover,input[type=\'checkbox\']:checked + label:before,input[type=\'radio\']:checked + label:before,.helpContent{background-color:' . $colors['normal'] . ';color:' . $colors['text'] . '}';
			$css .= '.helpButton span{color:' . $colors['normal'] . '}';
			$css .= 'input[type=\'text\']:hover,input[type=\'password\']:hover,.inputFile:hover,select:hover,textarea:hover{border-color:' . $colors['normal'] . '}';
			$css .= '.speechBubble:before{border-color:' . $colors['normal'] . ' transparent transparent transparent}';
			$css .= '.button:hover,button[type=\'submit\']:hover,.pagination a:hover,input[type=\'checkbox\']:not(:active):checked:hover + label:before,input[type=\'checkbox\']:active + label:before,input[type=\'radio\']:checked:hover + label:before,input[type=\'radio\']:not(:checked):active + label:before{background-color:' . $colors['darken'] . '}';
			$css .= '.helpButton span:hover{color:' . $colors['darken'] . '}';
			$css .= '.button:active,button[type=\'submit\']:active,.pagination a:active{background-color:' . $colors['veryDarken'] . '}';					
			$colors = helper::colorVariants($this->getData(['theme', 'title', 'textColor']));
			$css .= 'h1,h2,h3,h4,h5,h6{color:' . $colors['normal'] . ';font-family:"' . str_replace('+', ' ', $this->getData(['theme', 'title', 'font'])) . '",sans-serif;font-weight:' . $this->getData(['theme', 'title', 'fontWeight']) . ';text-transform:' . $this->getData(['theme', 'title', 'textTransform']) . '}';
			// Bannière
			$colors = helper::colorVariants($this->getData(['theme', 'header', 'backgroundColor']));
			if($this->getData(['theme', 'header', 'margin'])) {
				if($this->getData(['theme', 'menu', 'position']) === 'site-first') {
					$css .= 'header{margin:0 20px}';
				}
				else {
					$css .= 'header{margin:20px 20px 0 20px}';
				}
			}
			$css .= 'header{background-size:' . $this->getData(['theme','header','imageContainer']).'}';
			$css .= 'header{background-color:' . $colors['normal'];

			// Valeur de hauteur traditionnelle
			$css .= ';height:' . $this->getData(['theme', 'header', 'height']) . ';line-height:' . $this->getData(['theme', 'header', 'height']) ;

			$css .=  ';text-align:' . $this->getData(['theme', 'header', 'textAlign']) . '}';
			if($themeHeaderImage = $this->getData(['theme', 'header', 'image'])) {
				$css .= 'header{background-image:url("../file/source/' . $themeHeaderImage . '");background-position:' . $this->getData(['theme', 'header', 'imagePosition']) . ';background-repeat:' . $this->getData(['theme', 'header', 'imageRepeat']) . '}';
			}
			$colors = helper::colorVariants($this->getData(['theme', 'header', 'textColor']));
			$css .= 'header span{color:' . $colors['normal'] . ';font-family:"' . str_replace('+', ' ', $this->getData(['theme', 'header', 'font'])) . '",sans-serif;font-weight:' . $this->getData(['theme', 'header', 'fontWeight']) . ';font-size:' . $this->getData(['theme', 'header', 'fontSize']) . ';text-transform:' . $this->getData(['theme', 'header', 'textTransform']) . '}';
			// Menu
			$colors = helper::colorVariants($this->getData(['theme', 'menu', 'backgroundColor']));							
			$css .= 'nav,nav a{background-color:' . $colors['normal'] . '}';
			$css .= 'nav a,#toggle span,nav a:hover{color:' . $this->getData(['theme', 'menu', 'textColor']) . '}';
			$css .= 'nav a:hover{background-color:' . $colors['darken'] . '}';
			if ($this->getData(['theme','menu','activeColorAuto']) === true) {
				$css .= 'nav a.active{background-color:' . $colors['veryDarken'] . '}';				
			} else {
				$css .= 'nav a.active{background-color:' . $this->getData(['theme','menu','activeColor']) . '}';
				$color2 = helper::colorVariants($this->getData(['theme', 'menu', 'textColor']));
				$css .= 'nav a.active{color:' .  $color2['text'] . '}';
			}		
			$css .= 'nav #burgerText{color:' .  $colors['text'] . '}';		
			$css .= 'nav .navLevel1 a.active {border-radius:' . $this->getData(['theme', 'menu', 'radius']) . '}'; 
			$css .= '#menu{text-align:' . $this->getData(['theme', 'menu', 'textAlign']) . '}';
			if($this->getData(['theme', 'menu', 'margin'])) {
				if(
					$this->getData(['theme', 'menu', 'position']) === 'site-first'
					OR $this->getData(['theme', 'menu', 'position']) === 'site-second'
				) {
					$css .= 'nav{padding:10px 10px 0 10px;}';
				}
				else {
					$css .= 'nav{padding:0 10px}';
				}				
			} else {
				$css .= 'nav{margin:0}';
			}
			if(
				$this->getData(['theme', 'menu', 'position']) === 'top'
				) {
					$css .= 'nav{padding:0 10px;}';
			}

			$css .= '#toggle span,#menu a{padding:' . $this->getData(['theme', 'menu', 'height']) .';font-family:"' . str_replace('+', ' ', $this->getData(['theme', 'menu', 'font'])) . '",sans-serif;font-weight:' . $this->getData(['theme', 'menu', 'fontWeight']) . ';font-size:' . $this->getData(['theme', 'menu', 'fontSize']) . ';text-transform:' . $this->getData(['theme', 'menu', 'textTransform']) . '}';			
			// Pied de page
			$colors = helper::colorVariants($this->getData(['theme', 'footer', 'backgroundColor']));
			if($this->getData(['theme', 'footer', 'margin'])) {
				$css .= 'footer{padding:0 20px;}';
			} else {
				$css .= 'footer{padding:0}';
			}
			$css .= 'footer span, #footerText > p {color:' . $this->getData(['theme', 'footer', 'textColor']) . ';font-family:"' . str_replace('+', ' ', $this->getData(['theme', 'footer', 'font'])) . '",sans-serif;font-weight:' . $this->getData(['theme', 'footer', 'fontWeight']) . ';font-size:' . $this->getData(['theme', 'footer', 'fontSize']) . ';text-transform:' . $this->getData(['theme', 'footer', 'textTransform']) . '}';
			$css .= 'footer{background-color:' . $colors['normal'] . ';color:' . $this->getData(['theme', 'footer', 'textColor']) . '}';
			$css .= 'footer a{color:' . $this->getData(['theme', 'footer', 'textColor']) . '}';
			$css .= 'footer #footersite > div {margin:' . $this->getData(['theme', 'footer', 'height']) . ' 0}';
			$css .= 'footer #footerbody > div {margin:' . $this->getData(['theme', 'footer', 'height']) . ' 0}';
			$css .= '#footerSocials{text-align:' . $this->getData(['theme', 'footer', 'socialsAlign']) . '}';
			$css .= '#footerText > p {text-align:' . $this->getData(['theme', 'footer', 'textAlign']) . '}';
			$css .= '#footerCopyright{text-align:' . $this->getData(['theme', 'footer', 'copyrightAlign']) . '}';
			// Marge supplémentaire lorsque le pied de page est fixe 
			if ( $this->getData(['theme', 'footer', 'fixed']) === true &&
				 $this->getData(['theme', 'footer', 'position']) === 'body') {
				$css .= "@media (min-width: 769px) { #site {margin-bottom: 100px;} }";
				$css .= "@media (max-width: 768px) { #site {margin-bottom: 150px;} }";
			}
			// Enregistre la personnalisation
			file_put_contents(self::DATA_DIR.'theme.css', $css);
		}
	}
	/**
	 * Auto-chargement des classes
	 * @param string $className Nom de la classe à charger
	 */
	public static function autoload($className) {
		$classPath = strtolower($className) . '/' . strtolower($className) . '.php';
		// Module du coeur
		if(is_readable('core/module/' . $classPath)) {
			require 'core/module/' . $classPath;
		}
		// Module
		elseif(is_readable('module/' . $classPath)) {
			require 'module/' . $classPath;
		}
		// Librairie
		elseif(is_readable('core/vendor/' . $classPath)) {
			require 'core/vendor/' . $classPath;
		}
	}

	/**
	 * Routage des modules
	 */
	public function router() {
		// Installation
		if(
			$this->getData(['user']) === []
			AND $this->getUrl(0) !== 'install'
		) {
			http_response_code(302);
			header('Location:' . helper::baseUrl() . 'install');
			exit();
		}
		// Force la déconnexion des membres bannis
		if (
			$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
			AND $this->getUser('group') === self::GROUP_BANNED
		) {
			$user = new user;
			$user->logout();
		}
		// Mode maintenance
		if(
			$this->getData(['config', 'maintenance'])
			AND in_array($this->getUrl(0), ['maintenance', 'user']) === false
			AND $this->getUrl(1) !== 'login'
			AND (
				$this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
				OR (
					$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
					AND $this->getUser('group') < self::GROUP_ADMIN
				)
			)
		) {
			// Déconnexion
			$user = new user;
			$user->logout();
			// Rédirection
			http_response_code(302);
			header('Location:' . helper::baseUrl() . 'maintenance');
			exit();
		}
		// Check l'accès à la page
		$access = null;
		if($this->getData(['page', $this->getUrl(0)]) !== null) {
			if(
				$this->getData(['page', $this->getUrl(0), 'group']) === self::GROUP_VISITOR
				OR (
					$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
					AND $this->getUser('group') >= $this->getData(['page', $this->getUrl(0), 'group'])
				)
			) {
				$access = true;
			}
			else {
				if($this->getUrl(0) === $this->getData(['config', 'homePageId'])) {
					$access = 'login';
				}
				else {
					$access = false;
				}
			}
		}

		// Breadcrumb
		$title = $this->getData(['page', $this->getUrl(0), 'title']);
		if (!empty($this->getData(['page', $this->getUrl(0), 'parentPageId'])) &&
				$this->getData(['page', $this->getUrl(0), 'breadCrumb'])) {
				$title = '<a href="' . helper::baseUrl() . 
						$this->getData(['page', $this->getUrl(0), 'parentPageId']) .
						'">' .
						ucfirst($this->getData(['page',$this->getData(['page', $this->getUrl(0), 'parentPageId']), 'title'])) .
						'</a> &#8250; '.
						$this->getData(['page', $this->getUrl(0), 'title']);			
		} 
		
		// Importe la page
		if(
			$this->getData(['page', $this->getUrl(0)]) !== null
			AND $this->getData(['page', $this->getUrl(0), 'moduleId']) === ''
			AND $access
		) {
			$this->addOutput([
				'title' => $title,
				'content' => $this->getData(['page', $this->getUrl(0), 'content']),
				'metaDescription' => $this->getData(['page', $this->getUrl(0), 'metaDescription']),
				'metaTitle' => $this->getData(['page', $this->getUrl(0), 'metaTitle']),
				'typeMenu' => $this->getData(['page', $this->getUrl(0), 'typeMenu']),
				'iconUrl' => $this->getData(['page', $this->getUrl(0), 'iconUrl']),
				'disable' => $this->getData(['page', $this->getUrl(0), 'disable']),
				'contentRight' => $this->getData(['page',$this->getData(['page',$this->getUrl(0),'barRight']),'content']),
				'contentLeft'  => $this->getData(['page',$this->getData(['page',$this->getUrl(0),'barLeft']),'content']),
				'targetLity' => is_null($this->getData(['page', $this->getUrl(0), 'targetLity'])) ? false : $this->getData(['page', $this->getUrl(0), 'targetLity'])
			]);
		}
		// Importe le module
		else {
			// Id du module, et valeurs en sortie de la page si il s'agit d'un module de page

			if($access AND $this->getData(['page', $this->getUrl(0), 'moduleId'])) {
				$moduleId = $this->getData(['page', $this->getUrl(0), 'moduleId']);
				$this->addOutput([
					'title' => $title,
					'metaDescription' => $this->getData(['page', $this->getUrl(0), 'metaDescription']),
					'metaTitle' => $this->getData(['page', $this->getUrl(0), 'metaTitle']),
					'typeMenu' => $this->getData(['page', $this->getUrl(0), 'typeMenu']),
					'iconUrl' => $this->getData(['page', $this->getUrl(0), 'iconUrl']),
					'disable' => $this->getData(['page', $this->getUrl(0), 'disable']),
					'contentRight' => $this->getData(['page',$this->getData(['page',$this->getUrl(0),'barRight']),'content']),
					'contentLeft'  => $this->getData(['page',$this->getData(['page',$this->getUrl(0),'barLeft']),'content']),
					'targetLity' => is_null($this->getData(['page', $this->getUrl(0), 'targetLity'])) ? false : $this->getData(['page', $this->getUrl(0), 'targetLity'])
				]);
				$pageContent = $this->getData(['page', $this->getUrl(0), 'content']);
			}
			else {
				$moduleId = $this->getUrl(0);
				$pageContent = '';
			}
			// Check l'existence du module
			if(class_exists($moduleId)) {
				/** @var common $module */
				$module = new $moduleId;
				// Check l'existence de l'action
				$action = '';
				$ignore = true;
				foreach(explode('-', $this->getUrl(1)) as $actionPart) {
					if($ignore) {
						$action .= $actionPart;
						$ignore = false;
					}
					else {
						$action .= ucfirst($actionPart);
					}
				}
				$action = array_key_exists($action, $module::$actions) ? $action : 'index';
				if(array_key_exists($action, $module::$actions)) {
					$module->$action();
					$output = $module->output;
					// Check le groupe de l'utilisateur
					if(
						(
							$module::$actions[$action] === self::GROUP_VISITOR
							OR (
								$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
								AND $this->getUser('group') >= $module::$actions[$action]
							)
						)
						AND $output['access'] === true
					) {
						// Enregistrement du contenu de la méthode POST lorsqu'une notice est présente
						if(common::$inputNotices) {
							foreach($_POST as $postId => $postValue) {
								if(is_array($postValue)) {
									foreach($postValue as $subPostId => $subPostValue) {
										self::$inputBefore[$postId . '_' . $subPostId] = $subPostValue;
									}
								}
								else {
									self::$inputBefore[$postId] = $postValue;
								}
							}
						}
						// Sinon traitement des données de sortie qui requiert qu'aucune notice ne soit présente
						else {
							// Enregistrement des données
							if($output['state'] !== false) {
								$this->setData([$module->getData()]);
								$this->saveData();
							}
							// Notification
							if($output['notification']) {
								if($output['state'] === true) {
									$notification = 'ZWII_NOTIFICATION_SUCCESS';
								}
								elseif($output['state'] === false) {
									$notification = 'ZWII_NOTIFICATION_ERROR';
								}
								else {
									$notification = 'ZWII_NOTIFICATION_OTHER';
								}
								$_SESSION[$notification] = $output['notification'];
							}
							// Redirection
							if($output['redirect']) {
								http_response_code(301);
								header('Location:' . $output['redirect']);
								exit();								
							}
						}						
						// Données en sortie applicables même lorsqu'une notice est présente
						// Affichage
						if($output['display']) { 
							$this->addOutput([
								'display' => $output['display']
							]);			
						}
						// Contenu brut
						if($output['content']) {
							$this->addOutput([
								'content' => $output['content']
							]);
						}
						// Contenu par vue
						elseif($output['view']) {
							// Chemin en fonction d'un module du coeur ou d'un module
							$modulePath = in_array($moduleId, self::$coreModuleIds) ? 'core/' : '';
							// CSS
							$stylePath = $modulePath . 'module/' . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.css';
							if(file_exists($stylePath)) {
								$this->addOutput([
									'style' => file_get_contents($stylePath)
								]);
							}
							// JS
							$scriptPath = $modulePath . 'module/' . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.js.php';
							if(file_exists($scriptPath)) {
								ob_start();
								include $scriptPath;
								$this->addOutput([
									'script' => ob_get_clean()
								]);
							}
							// Vue
							$viewPath = $modulePath . 'module/' . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.php';
							if(file_exists($viewPath)) {
								ob_start();
								include $viewPath;
								$modpos = $this->getData(['page', $this->getUrl(0), 'modulePosition']);
								if ($modpos === 'top') {
									$this->addOutput([
									'content' => ob_get_clean() . ($output['showPageContent'] ? $pageContent : '')]);
								}
								else if ($modpos === 'free') {
									if ( strstr($pageContent, '[MODULE]', true) === false)  {
										$begin = strstr($pageContent, '[]', true); } else {
										$begin = strstr($pageContent, '[MODULE]', true);
									}
									if (  strstr($pageContent, '[MODULE]') === false ) {
										$end = strstr($pageContent, '[]');} else {
										$end = strstr($pageContent, '[MODULE]');
										}
									$cut=8;
									$end=substr($end,-strlen($end)+$cut);
									$this->addOutput([
									'content' => ($output['showPageContent'] ? $begin : '') . ob_get_clean() . ($output['showPageContent'] ? $end : '')]);								}
								else {
									$this->addOutput([
									'content' => ($output['showPageContent'] ? $pageContent : '') . ob_get_clean()]);
								}
							}
						}
						// Librairies
						if($output['vendor'] !== $this->output['vendor']) {
							$this->addOutput([
								'vendor' => array_merge($this->output['vendor'], $output['vendor'])
							]);
						}
						if($output['title'] !== null) {										
							$this->addOutput([
								'title' => $output['title']
							]);
						}
						// Affiche le bouton d'édition de la page dans la barre de membre
						if($output['showBarEditButton']) {
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
		if($access === 'login') {
			http_response_code(302);
			header('Location:' . helper::baseUrl() . 'user/login/');
			exit();
		}
		if($access === false) {
			http_response_code(403);
			$this->addOutput([
				'title' => 'Erreur 403',
				'content' => template::speech('Vous n\'êtes pas autorisé à accéder à cette page...')
			]);
		}
		elseif($this->output['content'] === '') {
			http_response_code(404);
			$this->addOutput([
				'title' => 'Erreur 404',
				'content' => template::speech('Oups ! La page demandée est introuvable...')
			]);
		}
		// Mise en forme des métas
		if($this->output['metaTitle'] === '') {
			if($this->output['title']) {
				$this->addOutput([
					'metaTitle' => strip_tags($this->output['title']) . ' - ' . $this->getData(['config', 'title'])
				]);
			}
			else {
				$this->addOutput([
					'metaTitle' => $this->getData(['config', 'title'])
				]);
			}
		}
		if($this->output['metaDescription'] === '') {
			$this->addOutput([
				'metaDescription' => $this->getData(['config', 'metaDescription'])
			]);
		}
		// Traitement de la popup hors connexion.
		if( $this->output['targetLity'] &&
		 	$this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
		)  {
			$this->output['display'] = self::DISPLAY_LAYOUT_POPUP;
		}
				
		switch($this->output['display']) {
			// Layout vide
			case self::DISPLAY_LAYOUT_BLANK:
				require 'core/layout/blank.php';
				break;
			// Affichage en JSON
			case self::DISPLAY_JSON:
				header('Content-Type: application/json');
				echo json_encode($this->output['content']);
				break;
			// Layout alléger
			case self::DISPLAY_LAYOUT_LIGHT:
				require 'core/layout/light.php';
				break;
			// Layout principal
			case self::DISPLAY_LAYOUT_MAIN:
				require 'core/layout/main.php';
				break;
			// Layout poup lity
			case self::DISPLAY_LAYOUT_POPUP:
				require 'core/layout/popup.php';
				break;								
			// Layout brut
			case self::DISPLAY_RAW:
				echo $this->output['content'];
				break;
		}		
	}

}

class helper {

	/** Statut de la réécriture d'URL (pour éviter de lire le contenu du fichier .htaccess à chaque self::baseUrl()) */
	public static $rewriteStatus = null;

	/** Filtres personnalisés */
	const FILTER_BOOLEAN = 1;
	const FILTER_DATETIME = 2;
	const FILTER_FLOAT = 3;
	const FILTER_ID = 4;
	const FILTER_INT = 5;
	const FILTER_MAIL = 6;
	const FILTER_PASSWORD = 7;
	const FILTER_STRING_LONG = 8;
	const FILTER_STRING_SHORT = 9;
	const FILTER_TIMESTAMP = 10;
	const FILTER_URL = 11;

	/**
	 * Retourne les valeurs d'une colonne du tableau de données
	 * @param array $array Tableau cible
	 * @param string $column Colonne à extraire
	 * @param string $sort Type de tri à appliquer au tableau (SORT_ASC, SORT_DESC, ou null)
	 * @return array
	 */
	public static function arrayCollumn($array, $column, $sort = null) {
		$newArray = [];
		if(empty($array) === false) {
			$newArray = array_map(function($element) use($column) {
				return $element[$column];
			}, $array);
			switch($sort) {
				case 'SORT_ASC':
					asort($newArray);
					break;
				case 'SORT_DESC':
					arsort($newArray);
					break;
			}
		}
		return $newArray;
	}

	/**
	 * Retourne l'URL de base du site
	 * @param bool $queryString Affiche ou non le point d'interrogation
	 * @param bool $host Affiche ou non l'host
	 * @return string
	 */
	public static function baseUrl($queryString = true, $host = true) {
		// Protocol
		if(
			(empty($_SERVER['HTTPS']) === false AND $_SERVER['HTTPS'] !== 'off')
			OR $_SERVER['SERVER_PORT'] === 443
		) {
			$protocol = 'https://';
		}
		else {
			$protocol = 'http://';
		}
		// Host
		if($host) {
			$host = $protocol . $_SERVER['HTTP_HOST'];
		}
		// Pathinfo
		$pathInfo = pathinfo($_SERVER['PHP_SELF']);
		// Querystring
		if($queryString AND helper::checkRewrite() === false) {
			$queryString = '?';
		}
		else {
			$queryString = '';
		}
		return $host . rtrim($pathInfo['dirname'], ' /') . '/' . $queryString;
	}

	/**
	 * Check le statut de l'URL rewriting
	 * @return bool
	 */
	public static function checkRewrite() {
		if(self::$rewriteStatus === null) {
			// Ouvre et scinde le fichier .htaccess
			$htaccess = explode('# URL rewriting', file_get_contents('.htaccess'));
			// Retourne un boolean en fonction du contenu de la partie réservée à l'URL rewriting
			self::$rewriteStatus = (empty($htaccess[1]) === false);
		}
		return self::$rewriteStatus;
	}

	/**
	 * Renvoie le numéro de version de Zwii est en ligne
	 * @return string
	 */
	public static function getOnlineVersion($channel = common::ZWII_UPDATE_CHANNEL) {
		return (@file_get_contents('http://zwiicms.com/update/'. $channel . '/version'));
	}


	/**
	 * Check si une nouvelle version de Zwii est disponible
	 * @return bool
	 */
	public static function checkNewVersion($channel = common::ZWII_UPDATE_CHANNEL) {
		if($version = helper::getOnlineVersion($channel)) {
			//return (trim($version) !== common::ZWII_VERSION);
			return ((version_compare(common::ZWII_VERSION,$version)) === -1);
		}
		else {
			return false;
		}
	}


	/**
	 * Génère des variations d'une couleur
	 * @param string $rgba Code rgba de la couleur
	 * @return array
	 */
	public static function colorVariants($rgba) {
		preg_match('#\(+(.*)\)+#', $rgba, $matches);
		$rgba = explode(', ', $matches[1]);
		return [
			'normal' => 'rgba(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . $rgba[3] . ')',
			'darken' => 'rgba(' . max(0, $rgba[0] - 15) . ',' . max(0, $rgba[1] - 15) . ',' . max(0, $rgba[2] - 15) . ',' . $rgba[3] . ')',
			'veryDarken' => 'rgba(' . max(0, $rgba[0] - 20) . ',' . max(0, $rgba[1] - 20) . ',' . max(0, $rgba[2] - 20) . ',' . $rgba[3] . ')',
			'text' => self::relativeLuminanceW3C($rgba) > .22 ? "inherit" : "white"
		];
	}

	/**
	 * Supprime un cookie
	 * @param string $cookieKey Clé du cookie à supprimer
	 */
	public static function deleteCookie($cookieKey) {
		unset($_COOKIE[$cookieKey]);
		setcookie($cookieKey, '', time() - 3600, helper::baseUrl(false, false));
	}

	/**
	 * Filtre une chaîne en fonction d'un tableau de données
	 * @param string $text Chaîne à filtrer
	 * @param int $filter Type de filtre à appliquer
	 * @return string
	 */
	public static function filter($text, $filter) {
		$text = trim($text);
		switch($filter) {
			case self::FILTER_BOOLEAN:
				$text = (bool) $text;
				break;
			case self::FILTER_DATETIME:
				$timezone = new DateTimeZone(core::$timezone);
				$date = new DateTime($text);
				$date->setTimezone($timezone);
				$text = (int) $date->format('U');
				break;
			case self::FILTER_FLOAT:
				$text = filter_var($text, FILTER_SANITIZE_NUMBER_FLOAT);
				$text = (float) $text;
				break;
			case self::FILTER_ID:
				$text = mb_strtolower($text, 'UTF-8');
				$text = strip_tags(str_replace(
					explode(',', 'á,à,â,ä,ã,å,ç,é,è,ê,ë,í,ì,î,ï,ñ,ó,ò,ô,ö,õ,ú,ù,û,ü,ý,ÿ,\',", '),
					explode(',', 'a,a,a,a,a,a,c,e,e,e,e,i,i,i,i,n,o,o,o,o,o,u,u,u,u,y,y,-,-,-'),
					$text
				));
				$text = preg_replace('/([^a-z0-9-])/', '', $text);
				// Cas où un identifiant est vide
				if (empty($text)) {
					$text = uniqid('page-');
				}
				// Un ID ne peut pas être un entier, pour éviter les conflits avec le système de pagination
				if(intval($text) !== 0) {
					$text = 'i' . $text;
				}			
				break;
			case self::FILTER_INT:
				$text = (int) filter_var($text, FILTER_SANITIZE_NUMBER_INT);
				break;
			case self::FILTER_MAIL:
				$text = filter_var($text, FILTER_SANITIZE_EMAIL);
				break;
			case self::FILTER_PASSWORD:
				$text = password_hash($text, PASSWORD_BCRYPT);
				break;
			case self::FILTER_STRING_LONG:
				$text = mb_substr(filter_var($text, FILTER_SANITIZE_STRING), 0, 500000);
				break;
			case self::FILTER_STRING_SHORT:
				$text = mb_substr(filter_var($text, FILTER_SANITIZE_STRING), 0, 500);
				break;
			case self::FILTER_TIMESTAMP:
				$text = date('Y-m-d H:i:s', $text);
				break;
			case self::FILTER_URL:
				$text = filter_var($text, FILTER_SANITIZE_URL);
				break;
		}
		return  $text;
	}

	/**
	 * Incrémente une clé en fonction des clés ou des valeurs d'un tableau
	 * @param mixed $key Clé à incrémenter
	 * @param array $array Tableau à vérifier
	 * @return string
	 */
	public static function increment($key, $array = []) {
		// Pas besoin d'incrémenter si la clef n'existe pas
		if($array === []) {
			return $key;
		}
		// Incrémente la clef
		else {
			// Si la clef est numérique elle est incrémentée
			if(is_numeric($key)) {
				$newKey = $key;
				while(array_key_exists($newKey, $array) OR in_array($newKey, $array)) {
					$newKey++;
				}
			}
			// Sinon l'incrémentation est ajoutée après la clef
			else {
				$i = 2;
				$newKey = $key;
				while(array_key_exists($newKey, $array) OR in_array($newKey, $array)) {
					$newKey = $key . '-' . $i;
					$i++;
				}
			}
			return $newKey;
		}
	}

	/**
	 * Minimise du css
	 * @param string $css Css à minimiser
	 * @return string
	 */
	public static function minifyCss($css) {
		// Supprime les commentaires
		$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
		// Supprime les tabulations, espaces, nouvelles lignes, etc...
		$css = str_replace(["\r\n", "\r", "\n" ,"\t", '  ', '    ', '     '], '', $css);
		$css = preg_replace(['(( )+{)', '({( )+)'], '{', $css);
		$css = preg_replace(['(( )+})', '(}( )+)', '(;( )*})'], '}', $css);
		$css = preg_replace(['(;( )+)', '(( )+;)'], ';', $css);
		// Retourne le css minifié
		return $css;
	}

	/**
	 * Minimise du js
	 * @param string $js Js à minimiser
	 * @return string
	 */
	public static function minifyJs($js) {
		// Supprime les commentaires
		$js = preg_replace('/\\/\\*[^*]*\\*+([^\\/][^*]*\\*+)*\\/|\s*(?<![\:\=])\/\/.*/', '', $js);
		// Supprime les tabulations, espaces, nouvelles lignes, etc...
		$js = str_replace(["\r\n", "\r", "\t", "\n", '  ', '    ', '     '], '', $js);
		$js = preg_replace(['(( )+\))', '(\)( )+)'], ')', $js);
		// Retourne le js minifié
		return $js;
	}

	/**
	 * Crée un système de pagination (retourne un tableau contenant les informations sur la pagination (first, last, pages))
	 * @param array $array Tableau de donnée à utiliser
	 * @param string $url URL à utiliser, la dernière partie doit correspondre au numéro de page, par défaut utiliser $this->getUrl()
	 * @param string  $item pagination nombre d'éléments par page
	 * @param null|int $sufix Suffixe de l'url
	 * @return array
	 */
	public static function pagination($array, $url, $item, $sufix = null) {
		// Scinde l'url
		$url = explode('/', $url);
		// Url de pagination
		$urlPagination = is_numeric($url[count($url) - 1]) ? array_pop($url) : 1;
		// Url de la page courante
		$urlCurrent = implode('/', $url);
		// Nombre d'éléments à afficher
		$nbElements = count($array);
		// Nombre de page
		$nbPage = ceil($nbElements / $item);
		// Page courante
		$currentPage = is_numeric($urlPagination) ? self::filter($urlPagination, self::FILTER_INT) : 1;
		// Premier élément de la page
		$firstElement = ($currentPage - 1) * $item;
		// Dernier élément de la page
		$lastElement = $firstElement + $item;
		$lastElement = ($lastElement > $nbElements) ? $nbElements : $lastElement;
		// Mise en forme de la liste des pages
		$pages = '';
		if($nbPage > 1) {
			for($i = 1; $i <= $nbPage; $i++) {
				$disabled = ($i === $currentPage) ? ' class="disabled"' : false;
				$pages .= '<a href="' . helper::baseUrl() . $urlCurrent . '/' . $i . $sufix . '"' . $disabled . '>' . $i . '</a>';
			}
			$pages = '<div class="pagination">' . $pages . '</div>';
		}
		// Retourne un tableau contenant les informations sur la pagination
		return [
			'first' => $firstElement,
			'last' => $lastElement,
			'pages' => $pages
		];
	}

	/**
	 * Calcul de la luminance relative d'une couleur
	 */
	public static function relativeLuminanceW3C($rgba) {
		// Conversion en sRGB
		$RsRGB = $rgba[0] / 255;
		$GsRGB = $rgba[1] / 255;
		$BsRGB = $rgba[2] / 255;
		// Ajout de la transparence
		$RsRGBA = $rgba[3] * $RsRGB + (1 - $rgba[3]);
		$GsRGBA = $rgba[3] * $GsRGB + (1 - $rgba[3]);
		$BsRGBA = $rgba[3] * $BsRGB + (1 - $rgba[3]);
		// Calcul de la luminance
		$R = ($RsRGBA <= .03928) ? $RsRGBA / 12.92 : pow(($RsRGBA + .055) / 1.055, 2.4);
		$G = ($GsRGBA <= .03928) ? $GsRGBA / 12.92 : pow(($GsRGBA + .055) / 1.055, 2.4);
		$B = ($BsRGBA <= .03928) ? $BsRGBA / 12.92 : pow(($BsRGBA + .055) / 1.055, 2.4);
		return .2126 * $R + .7152 * $G + .0722 * $B;
	}

	/**
	 * Retourne les attributs d'une balise au bon format
	 * @param array $array Liste des attributs ($key => $value)
	 * @param array $exclude Clés à ignorer ($key)
	 * @return string
	 */
	public static function sprintAttributes(array $array = [], array $exclude = []) {
		$exclude = array_merge(
			[
				'before',
				'classWrapper',
				'help',
				'label'
			],
			$exclude
		);
		$attributes = [];
		foreach($array as $key => $value) {
			if(($value OR $value === 0) AND in_array($key, $exclude) === false) {
				// Désactive le message de modifications non enregistrées pour le champ
				if($key === 'noDirty') {
					$attributes[] = 'data-no-dirty';
				}
				// Disabled
				// Readonly
				elseif(in_array($key, ['disabled', 'readonly'])) {
					$attributes[] = sprintf('%s', $key);
				}
				// Autres
				else {
					$attributes[] = sprintf('%s="%s"', $key, $value);
				}
			}
		}
		return implode(' ', $attributes);
	}

	/**
	 * Retourne un segment de chaîne sans couper de mot
	 * @param string $text Texte à scinder
	 * @param int $start (voir substr de PHP pour fonctionnement)
	 * @param int $length (voir substr de PHP pour fonctionnement)
	 * @return string
	 */
	public static function subword($text, $start, $length) {
		$text = trim($text);
		if(strlen($text) > $length) {
			$text = mb_substr($text, $start, $length);
			$text = mb_substr($text, 0, min(mb_strlen($text), mb_strrpos($text, ' ')));
		}
		return $text;
	}

}

class layout extends common {

	private $core;

	/**
	 * Constructeur du layout
	 */
	public function __construct(core $core) {
		parent::__construct();
		$this->core = $core;
	}

	/**
	 * Affiche le script Google Analytics
	 */
	public function showAnalytics() {
		if($code = $this->getData(['config', 'analyticsId'])) {
			echo '<!-- Global site tag (gtag.js) - Google Analytics -->
				<script async src="https://www.googletagmanager.com/gtag/js?id='. $code .'"></script>
				<script>
				  window.dataLayer = window.dataLayer || [];
				  function gtag(){dataLayer.push(arguments);}
				  gtag("js", new Date());
				  gtag("config","'. $code .'");
				</script>';
		}
	}

	/**
	 * Affiche le contenu
	 * @param Page par défaut 
	 */
	public function showContent() {
		if(
			$this->core->output['title']
			AND (
				$this->getData(['page', $this->getUrl(0)]) === null
				OR $this->getData(['page', $this->getUrl(0), 'hideTitle']) === false
			)
		) {
			echo '<h2 id="sectionTitle">' . $this->core->output['title'] . '</h2>';				
		}
		echo $this->core->output['content'];

	}


	/**
	 * Affiche le contenu de la barre gauche
	 * 
	 */
	public function showBarContentLeft() {
		// Détermine si le menu est présent
		if ($this->getData(['page',$this->getData(['page',$this->getUrl(0),'barLeft']),'displayMenu']) === 'none') {
			// Pas de menu
			echo $this->core->output['contentLeft'];
		} else {
			// $mark contient 0 le menu est positionné à la fin du contenu
			$contentLeft = str_replace ('[]','[MENU]',$this->core->output['contentLeft']);
			$contentLeft = str_replace ('[menu]','[MENU]',$contentLeft);
			$mark = strrpos($contentLeft,'[MENU]')  !== false ? strrpos($contentLeft,'[MENU]') : strlen($contentLeft);		
			echo substr($contentLeft,0,$mark);			
			echo '<div id="menuSideLeft">';
			echo $this->showMenuSide($this->getData(['page',$this->getData(['page',$this->getUrl(0),'barLeft']),'displayMenu']) === 'parents' ? false : true);
			echo '</div>';
			echo substr($contentLeft,$mark+6,strlen($contentLeft));			
		}						
	}

	/**
	 * Affiche le contenu de la barre droite
	 */
	public function showBarContentRight() {
		// Détermine si le menu est présent
		if ($this->getData(['page',$this->getData(['page',$this->getUrl(0),'barRight']),'displayMenu']) === 'none') {
			// Pas de menu
			echo $this->core->output['contentRight'];
		} else {
			// $mark contient 0 le menu est positionné à la fin du contenu
			$contentRight = str_replace ('[]','[MENU]',$this->core->output['contentRight']);
			$contentRight = str_replace ('[menu]','[MENU]',$contentRight);
			$mark = strrpos($contentRight,'[MENU]')  !== false ? strrpos($contentRight,'[MENU]') : strlen($contentRight);		
			echo substr($contentRight,0,$mark);			
			echo '<div id="menuSideRight">';
			echo $this->showMenuSide($this->getData(['page',$this->getData(['page',$this->getUrl(0),'barRight']),'displayMenu']) === 'parents' ? false : true);
			echo '</div>';
			echo substr($contentRight,$mark+6,strlen($contentRight));			
		}	
	}

	/**
	 * Affiche le texte du footer
	 */
	public function showFooterText() {
		if($footerText = $this->getData(['theme', 'footer', 'text']) OR $this->getUrl(0) === 'theme') {
			echo '<div id="footerText">' . $footerText . '</div>';
		}
	}

     /**
     * Affiche le copyright
     */
    public function showCopyright() {
		// Ouverture Bloc copyright
		$items = '<div id="footerCopyright">';
		$items .= '<span id="footerFontCopyright">';
		// Affichage de motorisé par 
		$items .= '<span id="footerDisplayCopyright" ';
		$items .= $this->getData(['theme','footer','displayCopyright']) === false ? 'class="displayNone"' : '';
		$items .= '>Motorisé&nbsp;par&nbsp;</span>';
		// Toujours afficher le nom du CMS
		$items .= '<span id="footerZwiiCMS">';
		$items .= '<a href="http://zwiicms.com/" onclick="window.open(this.href);return false" data-tippy-content="Zwii CMS sans base de données, très léger et performant">ZwiiCMS</a>';		
		$items .= '</span>';
		// Affichage du numéro de version
		$items .= '<span id="footerDisplayVersion"';
		$items .= $this->getData(['theme','footer','displayVersion']) === false ? ' class="displayNone"' : '';
		$items .= '><wbr>&nbsp;'. common::ZWII_VERSION ;			
		$items .= '</span>';
		// Affichage du sitemap
		$items .= '<span id="footerDisplaySiteMap"';
		$items .= $this->getData(['theme','footer','displaySiteMap']) ===  false ? ' class="displayNone"' : '';
		$items .=  '><wbr>&nbsp;|&nbsp;<a href="' . helper::baseUrl() .  'sitemap" data-tippy-content="Plan du site" >Plan&nbsp;du&nbsp;site</a>';
		$items .= '</span>';
        // Affichage du module de recherche
 		$items .= '<span id="footerDisplaySearch"';
		$items .= $this->getData(['theme','footer','displaySearch']) ===  false ? ' class="displayNone"' : '';
		$items .=  '><wbr>&nbsp;|&nbsp;<a href="' . helper::baseUrl() .  'search" data-tippy-content="Rechercher dans le site" >Rechercher</a>';
		$items .= '</span>';
		// Affichage des mentions légales
		$items .= '<span id="footerDisplayLegal"';
		$items .= $this->getData(['theme','footer','displayLegal']) ===  false ? ' class="displayNone" >' : '>';
		if ($this->getData(['config','legalPageId']) !== '') {
			$items .=  '<wbr>&nbsp;|&nbsp;<a href="' . helper::baseUrl() . $this->getData(['config','legalPageId']) . '" data-tippy-content="Mentions Légales">Mentions légales</a>';
		}
		$items .= '</span>';		
		// Affichage du lien de connexion 
		if(
            (
                $this->getData(['theme', 'footer', 'loginLink'])
                AND $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
            )
            OR $this->getUrl(0) === 'theme'
        ) {
			$items .= '<span id="footerLoginLink" ' . 
			($this->getUrl(0) === 'theme' ? 'class="displayNone"' : '') . 
			'><wbr>&nbsp;|&nbsp;<a href="' . helper::baseUrl() . 'user/login/' . 
			strip_tags(str_replace('/', '_', $this->getUrl())) . 
			'" data-tippy-content="Connexion à l\'administration" rel="nofollow">Connexion</a></span>';
		}
		// Fermeture du bloc copyright
        $items .= '</span></div>';
        echo $items;
	}
	
	
	/**
	 * Affiche les réseaux sociaux
	 */
	public function showSocials() {
		$socials = '';
		foreach($this->getData(['config', 'social']) as $socialName => $socialId) {
			switch($socialName) {
				case 'facebookId':
					$socialUrl = 'https://www.facebook.com/';
					$title = 'Facebook';
					break;
				case 'linkedinId':
					$socialUrl = 'https://fr.linkedin.com/in/';
					$title = 'Linkedin';
					break;
				case 'instagramId':
					$socialUrl = 'https://www.instagram.com/';
					$title = 'Instagram';
					break;
				case 'pinterestId':
					$socialUrl = 'https://pinterest.com/';
					$title = 'Pinterest';
					break;
				case 'twitterId':
					$socialUrl = 'https://twitter.com/';
					$title = 'Twitter';
					break;
				case 'youtubeId':
					$socialUrl = 'https://www.youtube.com/channel/';
					$title = 'Chaîne YouTube';
					break;
				case 'youtubeUserId':
					$socialUrl = 'https://www.youtube.com/user/';
					$title = 'YouTube';
					break;					
				case 'githubId':
					$socialUrl = 'https://www.github.com/';
					$title = 'Github';
					break;
				default:
					$socialUrl = '';
			}
			if($socialId !== '') {
				$socials .= '<a href="' . $socialUrl . $socialId . '" onclick="window.open(this.href);return false" data-tippy-content="' . $title . '">' . template::ico(substr(str_replace('User','',$socialName), 0, -2)) . '</a>';
			}
		}
		if($socials !== '') {
			echo '<div id="footerSocials">' . $socials . '</div>';
		}
	}



	/**
	 * Affiche le favicon
	 */
	public function showFavicon() {
		$favicon = $this->getData(['config', 'favicon']);
		if($favicon &&
			file_exists(self::FILE_DIR.'source/' . $favicon)
			) {
			echo '<link rel="shortcut icon" href="' . helper::baseUrl(false) . self::FILE_DIR.'source/' . $favicon . '">';
		} else {
			echo '<link rel="shortcut icon" href="' . helper::baseUrl(false) . 'core/vendor/zwiico/ico/favicon.ico">';
		}
	}


	/**
	 * Affiche le menu
	 */
	public function showMenu() {
		// Met en forme les items du menu
		$items = '';
		$currentPageId = $this->getData(['page', $this->getUrl(0)]) ? $this->getUrl(0) : $this->getUrl(2);
		foreach($this->getHierarchy() as $parentPageId => $childrenPageIds) {
			// Passer les entrées masquées	
			// Propriétés de l'item
			$active = ($parentPageId === $currentPageId OR in_array($currentPageId, $childrenPageIds)) ? ' class="active"' : '';
			$targetBlank = $this->getData(['page', $parentPageId, 'targetBlank']) ? ' target="_blank"' : '';
			$targetLity = ($this->getData(['page', $parentPageId, 'targetLity'])  && $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')) ? ' rel="data-lity"' : '';	
			// Mise en page de l'item
			$items .= '<li>';
			
			if ( $this->getData(['page',$parentPageId,'disable']) === true
				 AND $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')	)

					{$items .= '<a href="'.$this->getUrl(1).'">';
			} else {
					$items .= '<a href="' . helper::baseUrl() . $parentPageId . '"' . $active . $targetBlank . $targetLity . '>';	
			}

			switch ($this->getData(['page', $parentPageId, 'typeMenu'])) {
				case '' :
				    $items .= $this->getData(['page', $parentPageId, 'title']);
				    break;
				case 'text' :
				    $items .= $this->getData(['page', $parentPageId, 'title']);
				    break;
				case 'icon' :
				    if ($this->getData(['page', $parentPageId, 'iconUrl']) != "") {
				    $items .= '<img alt="'.$this->getData(['page', $parentPageId, 'title']).'" src="'. helper::baseUrl(false) .self::FILE_DIR.'source/'.$this->getData(['page', $parentPageId, 'iconUrl']).'" />';
				    } else {
				    $items .= $this->getData(['page', $parentPageId, 'title']);
				    }
				    break;
				case 'icontitle' :
				    if ($this->getData(['page', $parentPageId, 'iconUrl']) != "") {
				    	$items .= '<img alt="'.$this->getData(['page', $parentPageId, 'title']).'" src="'. helper::baseUrl(false) .self::FILE_DIR.'source/'.$this->getData(['page', $parentPageId, 'iconUrl']).'" data-tippy-content="';
				   	 	$items .= $this->getData(['page', $parentPageId, 'title']).'"/>';
				    } else {
				  	 	$items .= $this->getData(['page', $parentPageId, 'title']);
				    }
					break;
		       }
			// Cas où les pages enfants enfant sont toutes masquées dans le menu
			// ne pas afficher de symbole lorsqu'il n'y a rien à afficher
			$totalChild = 0;
			$disableChild = 0;
			foreach($childrenPageIds as $childKey) {
				$totalChild += 1;
			}	
			if($childrenPageIds && $disableChild !== $totalChild  &&
				$this->getdata(['page',$parentPageId,'hideMenuChildren']) === false) {
				$items .= template::ico('down', 'left');
			}
			// ------------------------------------------------	
			$items .= '</a>';
			if ($this->getdata(['page',$parentPageId,'hideMenuChildren']) === true ||
				empty($childrenPageIds)) {
				continue;
			}
			$items .= '<ul class="navLevel2">';
			foreach($childrenPageIds as $childKey) {			
				// Propriétés de l'item
				$active = ($childKey === $currentPageId) ? ' class="active"' : '';
				$targetBlank = $this->getData(['page', $childKey, 'targetBlank']) ? ' target="_blank"' : '';
				$targetLity = ($this->getData(['page', $childKey, 'targetLity'])  && $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')) ? ' rel="data-lity"' : '';		
				// Mise en page du sous-item
				$items .= '<li>';
				if ( $this->getData(['page',$childKey,'disable']) === true
					AND $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')	) {
						$items .= '<a href="'.$this->getUrl(1).'">';
				} else {
					$items .= '<a href="' . helper::baseUrl() . $childKey . '"' . $active . $targetBlank  . $targetLity . '>';			
				}

				switch ($this->getData(['page', $childKey, 'typeMenu'])) {
					case '' :
						$items .= $this->getData(['page', $childKey, 'title']);
						break;
					case 'text' :
						$items .= $this->getData(['page', $childKey, 'title']);
						break;
					case 'icon' :
						if ($this->getData(['page', $childKey, 'iconUrl']) != "") {
						$items .= '<img alt="'.$this->getData(['page', $parentPageId, 'title']).'" src="'. helper::baseUrl(false) .self::FILE_DIR.'source/'.$this->getData(['page', $childKey, 'iconUrl']).'" />';
						} else {
						$items .= $this->getData(['page', $parentPageId, 'title']);
						}
						break;
					case 'icontitle' :
						if ($this->getData(['page', $childKey, 'iconUrl']) != "") {
						$items .= '<img alt="'.$this->getData(['page', $parentPageId, 'title']).'" src="'. helper::baseUrl(false) .self::FILE_DIR.'source/'.$this->getData(['page', $childKey, 'iconUrl']).'" data-tippy-content="';
						$items .= $this->getData(['page', $childKey, 'title']).'"/>';
						} else {
						$items .= $this->getData(['page', $childKey, 'title']);
						}
						break;
					case 'icontext' :
						if ($this->getData(['page', $childKey, 'iconUrl']) != "") {
						$items .= '<img alt="'.$this->getData(['page', $parentPageId, 'title']).'" src="'. helper::baseUrl(false) .self::FILE_DIR.'source/'.$this->getData(['page', $childKey, 'iconUrl']).'" />';
						$items .= $this->getData(['page', $childKey, 'title']);
						} else {
						$items .= $this->getData(['page', $childKey, 'title']);
						}
						break;
				}
				$items .= '</a></li>';
			}
			$items .= '</ul>';
		}
		// Lien de connexion
		if(
			(
				$this->getData(['theme', 'menu', 'loginLink'])
				AND $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
			)
			OR $this->getUrl(0) === 'theme'
		) {
			$items .= '<li id="menuLoginLink" ' . 
			($this->getUrl(0) === 'theme' ? 'class="displayNone"' : '') . 
			'><a href="' . helper::baseUrl() . 'user/login/' . 
			strip_tags(str_replace('/', '_', $this->getUrl())) . 
			'">Connexion</a></li>';
		}
		// Retourne les items du menu
		echo '<ul class="navLevel1">' . $items . '</ul>';
	}

	/**
	 * Générer un menu pour la barre latérale
	 * Uniquement texte 
	 * @param onlyChildren n'affiche les sous-pages de la page actuelle
	 */
	public function showMenuSide($onlyChildren = null) {
		// Met en forme les items du menu
		$items = '';
		// Nom de la page courante
		$currentPageId = $this->getData(['page', $this->getUrl(0)]) ? $this->getUrl(0) : $this->getUrl(2);
		// Nom de la page parente
		$currentParentPageId = $this->getData(['page',$currentPageId,'parentPageId']);
		// Détermine si on affiche uniquement le parent et les enfants
		// Filtre contient le nom de la page parente

		if ($onlyChildren === true) {
			if (empty($currentParentPageId)) { 
				$filterCurrentPageId = $currentPageId;
			} else {
				$filterCurrentPageId = $currentParentPageId;				
			}
		} else {
			$items .= '<ul class="menuSide">';
		}

		foreach($this->getHierarchy() as $parentPageId => $childrenPageIds) {
			// Ne pas afficher les entrées masquées
			if ($this->getData(['page',$parentPageId,'hideMenuSide']) === true ) {
				continue;
			}
			// Filtre actif et nom de la page parente courante différente, on sort de la boucle
			if ($onlyChildren === true && $parentPageId !== $filterCurrentPageId) {
				continue;
			}
			// Propriétés de l'item
			$active = ($parentPageId === $currentPageId OR in_array($currentPageId, $childrenPageIds)) ? ' class="active"' : '';
			$targetBlank = $this->getData(['page', $parentPageId, 'targetBlank']) ? ' target="_blank"' : '';
			$targetLity = ($this->getData(['page', $parentPageId, 'targetLity'])  && $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')) ? ' rel="data-lity"' : '';	
			// Mise en page de l'item;
			// Ne pas afficher le parent d'une sous-page quand l'option est sélectionnée.
			if ($onlyChildren === false) {
				$items .= '<li class="menuSideChild">';
				if ( $this->getData(['page',$parentPageId,'disable']) === true
					AND $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')	) {
						$items .= '<a href="'.$this->getUrl(1).'">';
				} else {
						$items .= '<a href="' . helper::baseUrl() . $parentPageId . '"' . $active . $targetBlank . $targetLity . '>';	
				}
				$items .= $this->getData(['page', $parentPageId, 'title']);
				$items .= '</a>';
			}
			$itemsChildren = '';
			foreach($childrenPageIds as $childKey) {
				// Passer les entrées masquées
				if ($this->getData(['page',$childKey,'hideMenuSide']) === true ) {
					continue;
				}
				
				// Propriétés de l'item
				$active = ($childKey === $currentPageId) ? ' class="active"' : '';
				$targetBlank = $this->getData(['page', $childKey, 'targetBlank']) ? ' target="_blank"' : '';
				$targetLity = ($this->getData(['page', $childKey, 'targetLity'])  && $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')) ? ' rel="data-lity"' : '';	
				// Mise en page du sous-item
				$itemsChildren .= '<li class="menuSideChild">';

				if ( $this->getData(['page',$childKey,'disable']) === true
					AND $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')	) {
						$itemsChildren .= '<a href="'.$this->getUrl(1).'">';
				} else {
					$itemsChildren .= '<a href="' . helper::baseUrl() . $childKey . '"' . $active . $targetBlank . $targetLity . '>';
				}

				$itemsChildren .= $this->getData(['page', $childKey, 'title']);					
				$itemsChildren .= '</a></li>';
			}
			// Concaténe les items enfants
			if (!empty($itemsChildren)) {
				$items .= '<ul class="menuSideChild">';
				$items .= $itemsChildren;
				$items .= '</ul>';
			} else {
				$items .= '</li>';
			}

		}
		if ($onlyChildren === false) {
			$items .= '</ul>';
		}
		// Retourne les items du menu
		echo  $items;
	}



	/**
	 * Affiche le meta titre
	 */
	public function showMetaTitle() {
		echo '<title>' . $this->core->output['metaTitle'] . '</title>';
		echo '<meta property="og:title" content="' . $this->core->output['metaTitle'] . '" />';
		echo '<link rel="canonical" href="'. helper::baseUrl(true).$this->getUrl() .'" />';		
	}

	/**
	 * Affiche la meta description
	 */
	public function showMetaDescription() {
		echo '<meta name="description" content="' . $this->core->output['metaDescription'] . '" />';
		echo '<meta property="og:description" content="' . $this->core->output['metaDescription'] . '" />';
	}

	/**
	 * Affiche le meta type
	 */
	public function showMetaType() {
		echo '<meta property="og:type" content="website" />';
	}

	/**
	 * Affiche la meta image (site screenshot)
	 */
	public function showMetaImage() {
		echo '<meta property="og:image" content="' . helper::baseUrl() .self::FILE_DIR.'source/screenshot.png" />';
	}



	/**
	 * Affiche la notification
	 */
	public function showNotification() {
		if(common::$inputNotices) {
			$notification = 'Impossible de soumettre le formulaire, car il contient des erreurs';
			$notificationClass = 'notificationError';
		}
		elseif(empty($_SESSION['ZWII_NOTIFICATION_SUCCESS']) === false) {
			$notification = $_SESSION['ZWII_NOTIFICATION_SUCCESS'];
			$notificationClass = 'notificationSuccess';
			unset($_SESSION['ZWII_NOTIFICATION_SUCCESS']);
		}
		elseif(empty($_SESSION['ZWII_NOTIFICATION_ERROR']) === false) {
			$notification = $_SESSION['ZWII_NOTIFICATION_ERROR'];
			$notificationClass = 'notificationError';
			unset($_SESSION['ZWII_NOTIFICATION_ERROR']);
		}
		elseif(empty($_SESSION['ZWII_NOTIFICATION_OTHER']) === false) {
			$notification = $_SESSION['ZWII_NOTIFICATION_OTHER'];
			$notificationClass = 'notificationOther';
			unset($_SESSION['ZWII_NOTIFICATION_OTHER']);
		}
		if(isset($notification) AND isset($notificationClass)) {
			echo '<div id="notification" class="' . $notificationClass . '">' . $notification . '<span id="notificationClose">' . template::ico('cancel') . '<!----></span><div id="notificationProgress"></div></div>';
		}
	}

	/**
	 * Affiche la barre de membre
	 */
	public function showBar() {
		if($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')) {
			// Items de gauche
			$leftItems = '';
			if($this->getUser('group') >= self::GROUP_MODERATOR) {
				$leftItems .= '<li><select id="barSelectPage">';
				$leftItems .= '<option value="">Choisissez une page</option>';
				$leftItems .= '<optgroup label="Pages orphelines">';
				$orpheline = true ;
				$currentPageId = $this->getData(['page', $this->getUrl(0)]) ? $this->getUrl(0) : $this->getUrl(2);			
				foreach($this->getHierarchy(null,false) as $parentPageId => $childrenPageIds) {
					if ($this->getData(['page', $parentPageId, 'position']) !== 0  &&
						$orpheline ) {
							$orpheline = false;
							$leftItems .= '<optgroup label="Pages du menu">';
					} 
					// Exclure les barres
					if ($this->getData(['page', $parentPageId, 'block']) !== 'bar') {
						$leftItems .= '<option value="' . 
									helper::baseUrl() . 
									$parentPageId . '"' . 
									($parentPageId === $currentPageId ? ' selected' : false) . 
									($this->getData(['page', $parentPageId, 'disable']) === true ? ' class="inactive"' : '') .
									'>' . 
									$this->getData(['page', $parentPageId, 'title']) . 
									'</option>';
						foreach($childrenPageIds as $childKey) {
							$leftItems .= '<option value="' . 
											helper::baseUrl() . 
											$childKey . '"' . 
											($childKey === $currentPageId ? ' selected' : false) . 
											($this->getData(['page', $childKey, 'disable']) === true ? ' class="inactive"' : '') .
											'>&nbsp;&nbsp;&nbsp;&nbsp;' . 
											$this->getData(['page', $childKey, 'title']) . 
											'</option>';
						}
					}
				}
				$leftItems .= '</optgroup'>
				// Afficher les barres
				$leftItems .= '<optgroup label="Barres latérales">';
				foreach($this->getHierarchy(null, false,true) as $parentPageId => $childrenPageIds) {
					$leftItems .= '<option value="' . helper::baseUrl() . $parentPageId . '"' . ($parentPageId === $currentPageId ? ' selected' : false) . '>' . $this->getData(['page', $parentPageId, 'title']) . '</option>';
					foreach($childrenPageIds as $childKey) {
						$leftItems .= '<option value="' . helper::baseUrl() . $childKey . '"' . ($childKey === $currentPageId ? ' selected' : false) . '>&nbsp;&nbsp;&nbsp;&nbsp;' . $this->getData(['page', $childKey, 'title']) . '</option>';
					}
				}	
				$leftItems .= '</optgroup>';			
				$leftItems .= '</select></li>';
				$leftItems .= '<li><a href="' . helper::baseUrl() . 'page/add" data-tippy-content="Créer une page ou<br>une barre latérale">' . template::ico('plus') . '</a></li>';
				if(
					// Sur un module de page qui autorise le bouton de modification de la page
					$this->core->output['showBarEditButton']
					// Sur une page sans module
					OR $this->getData(['page', $this->getUrl(0), 'moduleId']) === ''
					// Sur une page d'accueil
					OR $this->getUrl(0) === ''
				) {
					$leftItems .= '<li><a href="' . helper::baseUrl() . 'page/edit/' . $this->getUrl(0) . '" data-tippy-content="Modifier la page">' . template::ico('pencil') . '</a></li>';
					$leftItems .= '<li><a id="pageDelete" href="' . helper::baseUrl() . 'page/delete/' . $this->getUrl(0) . '&csrf=' . $_SESSION['csrf'] . '" data-tippy-content="Effacer la page">' . template::ico('trash') . '</a></li>';					
				}
			}
			// Items de droite
			$rightItems = '';
			if($this->getUser('group') >= self::GROUP_MODERATOR) {
				$rightItems .= '<li><a href="' . helper::baseUrl(false) . 'core/vendor/filemanager/dialog.php?type=0&akey=' . md5_file(self::DATA_DIR.'core.json') .'" data-tippy-content="Gérer les fichiers" data-lity>' . template::ico('folder') . '</a></li>';
			}
			if($this->getUser('group') >= self::GROUP_ADMIN) {
				$rightItems .= '<li><a href="' . helper::baseUrl() . 'user" data-tippy-content="Configurer les utilisateurs">' . template::ico('users') . '</a></li>';
				$rightItems .= '<li><a href="' . helper::baseUrl() . 'theme" data-tippy-content="Personnaliser le thème">' . template::ico('brush') . '</a></li>';
				$rightItems .= '<li><a href="' . helper::baseUrl() . 'config" data-tippy-content="Configurer le site">' . template::ico('cog-alt') . '</a></li>';
				// Mise à jour automatique
				$lastAutoUpdate = mktime(0, 0, 0);			
				if( $this->getData(['config','autoUpdate']) === true &&
					$lastAutoUpdate > $this->getData(['core','lastAutoUpdate']) + 86400 ) {	
						$this->setData(['core','lastAutoUpdate',$lastAutoUpdate]);						
				    if ( helper::checkNewVersion(common::ZWII_UPDATE_CHANNEL)  ) {
						$rightItems .= '<li><a id="barUpdate" href="' . helper::baseUrl() . 'install/update" data-tippy-content="Mettre à jour Zwii '. common::ZWII_VERSION .' vers '. helper::getOnlineVersion(common::ZWII_UPDATE_CHANNEL) .'">' . template::ico('update colorRed') . '</a></li>';
					}
					$this->saveData();
				}	
			}
			$rightItems .= '<li><a href="' . helper::baseUrl() . 'user/edit/' . $this->getUser('id'). '/' . $_SESSION['csrf'] . '" data-tippy-content="Configurer mon compte">' . template::ico('user', 'right') . '<span id="displayUsername">' .  $this->getUser('firstname') . ' ' . $this->getUser('lastname') . '</span></a></li>';
			$rightItems .= '<li><a id="barLogout" href="' . helper::baseUrl() . 'user/logout" data-tippy-content="Se déconnecter">' . template::ico('logout') . '</a></li>';
			// Barre de membre 
			echo '<div id="bar"><div class="container"><ul id="barLeft">' . $leftItems . '</ul><ul id="barRight">' . $rightItems . '</ul></div></div>';
		}
	}

	/**
	 * Affiche le script
	 */
	public function showScript() {
		ob_start();
		require 'core/core.js.php';
		$coreScript = ob_get_clean();
		echo '<script>' . helper::minifyJs($coreScript . $this->core->output['script']) . '</script>';
	}

	/**
	 * Affiche le style
	 */
	public function showStyle() {
		if($this->core->output['style']) {
			echo '<base href="' . helper::baseUrl(true) .'">';
			echo '<style type="text/css">' . helper::minifyCss($this->core->output['style']) . '</style>';
		}
	}

	/**
	 * Affiche l'import des librairies
	 */
	public function showVendor() {
		// Variables partagées
		$vars = 'var baseUrl = ' . json_encode(helper::baseUrl(false)) . ';';
		$vars .= 'var baseUrlQs = ' . json_encode(helper::baseUrl()) . ';';
		if(
			$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
			AND $this->getUser('group') >= self::GROUP_MODERATOR
		) {
			$vars .= 'var privateKey = ' . json_encode(md5_file(self::DATA_DIR.'core.json')) . ';';
		}
		echo '<script>' . helper::minifyJs($vars) . '</script>';
		// Librairies
		$moduleId = $this->getData(['page', $this->getUrl(0), 'moduleId']);
		foreach($this->core->output['vendor'] as $vendorName) {
			// Coeur
			if(file_exists('core/vendor/' . $vendorName . '/inc.json')) {
				$vendorPath = 'core/vendor/' . $vendorName . '/';
			}
			// Module
			elseif(
				$moduleId
				AND in_array($moduleId, self::$coreModuleIds) === false
				AND file_exists('module/' . $moduleId . '/vendor/' . $vendorName . '/inc.json')
			) {
				$vendorPath = 'module/' . $moduleId . '/vendor/' . $vendorName . '/';
			}
			// Sinon continue
			else {
				continue;
			}
			// Détermine le type d'import en fonction de l'extension de la librairie
			$vendorFiles = json_decode(file_get_contents($vendorPath . 'inc.json'));
			foreach($vendorFiles as $vendorFile) {
				switch(pathinfo($vendorFile, PATHINFO_EXTENSION)) {
					case 'css':
						echo '<link rel="stylesheet" href="' . helper::baseUrl(false) . $vendorPath . $vendorFile . '">';
						break;
					case 'js':
						echo '<script src="' . helper::baseUrl(false) . $vendorPath . $vendorFile . '"></script>';
						break;
				}
			}
		}
	}

}

class template {

	/**
	 * Crée un bouton
	 * @param string $nameId Nom et id du champ
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function button($nameId, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'class' => '',
			'disabled' => false,
			'href' => 'javascript:void(0);',
			'ico' => '',
			'id' => $nameId,
			'name' => $nameId,
			'target' => '',
			'uniqueSubmission' => false,
			'value' => 'Bouton'
		], $attributes);
		// Retourne le html
		return  sprintf(
			'<a %s class="button %s %s %s">%s</a>',
			helper::sprintAttributes($attributes, ['class', 'disabled', 'ico', 'value']),
			$attributes['disabled'] ? 'disabled' : '',
			$attributes['class'],
			$attributes['uniqueSubmission'] ? 'uniqueSubmission' : '',
			($attributes['ico'] ? template::ico($attributes['ico'], 'right') : '') . $attributes['value']
		);
	}

	/**
	 * Crée un champ capcha
	 * @param string $nameId Nom et id du champ
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function capcha($nameId, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'class' => '',
			'classWrapper' => '',
			'help' => '',
			'id' => $nameId,
			'name' => $nameId,
			'value' => ''
		], $attributes);
		// Génère deux nombres pour le capcha
		$firstNumber = mt_rand(1, 15);
		$secondNumber = mt_rand(1, 15);
		// Début du wrapper
		$html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
		// Label
		$html .= self::label($attributes['id'], 'Combien font ' . $firstNumber . ' + ' . $secondNumber . ' ?', [
			'help' => $attributes['help']
		]);
		// Notice
		$notice = '';
		if(array_key_exists($attributes['id'], common::$inputNotices)) {
			$notice = common::$inputNotices[$attributes['id']];
			$attributes['class'] .= ' notice';
		}
		$html .= self::notice($attributes['id'], $notice);
		// Capcha
		$html .= sprintf(
			'<input type="text" %s>',
			helper::sprintAttributes($attributes)
		);
		// Champs cachés contenant les nombres
		$html .= self::hidden($attributes['id'] . 'FirstNumber', [
			'value' => $firstNumber,
			'before' => false
		]);
		$html .= self::hidden($attributes['id'] . 'SecondNumber', [
			'value' => $secondNumber,
			'before' => false
		]);
		// Fin du wrapper
		$html .= '</div>';
		// Retourne le html
		return $html;
	}

	/**
	 * Crée une case à cocher à sélection multiple
	 * @param string $nameId Nom et id du champ
	 * @param string $value Valeur de la case à cocher
	 * @param string $label Label de la case à cocher
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function checkbox($nameId, $value, $label, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'before' => true,
			'checked' => '',
			'class' => '',
			'classWrapper' => '',
			'disabled' => false,
			'help' => '',
			'id' => $nameId,
			'name' => $nameId
		], $attributes);
		// Sauvegarde des données en cas d'erreur
		if($attributes['before'] AND array_key_exists($attributes['id'], common::$inputBefore)) {
			$attributes['checked'] = (bool) common::$inputBefore[$attributes['id']];
		}
		// Début du wrapper
		$html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
		// Notice
		$notice = '';
		if(array_key_exists($attributes['id'], common::$inputNotices)) {
			$notice = common::$inputNotices[$attributes['id']];
			$attributes['class'] .= ' notice';
		}
		$html .= self::notice($attributes['id'], $notice);
		// Case à cocher
		$html .= sprintf(
			'<input type="checkbox" value="%s" %s>',
			$value,
			helper::sprintAttributes($attributes)
		);
		// Label
		$html .= self::label($attributes['id'], '<span>' . $label . '</span>', [
			'help' => $attributes['help']
		]);
		// Fin du wrapper
		$html .= '</div>';
		// Retourne le html
		return $html;
	}

	/**
	 * Crée un champ date
	 * @param string $nameId Nom et id du champ
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function date($nameId, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'autocomplete' => 'on',
			'before' => true,
			'class' => '',
			'classWrapper' => '',
			'noDirty' => false,
			'disabled' => false,
			'help' => '',
			'id' => $nameId,
			'label' => '',
			'name' => $nameId,
			'placeholder' => '',
			'readonly' => true,
			'value' => ''
		], $attributes);
		// Sauvegarde des données en cas d'erreur
		if($attributes['before'] AND array_key_exists($attributes['id'], common::$inputBefore)) {
			$attributes['value'] = common::$inputBefore[$attributes['id']];
		}
		else {
			$attributes['value'] = ($attributes['value'] ? helper::filter($attributes['value'], helper::FILTER_TIMESTAMP) : '');
		}
		// Début du wrapper
		$html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
		// Label
		if($attributes['label']) {
			$html .= self::label($attributes['id'], $attributes['label'], [
				'help' => $attributes['help']
			]);
		}
		// Notice
		$notice = '';
		if(array_key_exists($attributes['id'], common::$inputNotices)) {
			$notice = common::$inputNotices[$attributes['id']];
			$attributes['class'] .= ' notice';
		}
		$html .= self::notice($attributes['id'], $notice);
		// Date visible
		$html .= sprintf(
			'<input type="text" class="datepicker %s" value="%s" %s>',
			$attributes['class'],
			$attributes['value'],
			helper::sprintAttributes($attributes, ['class', 'value'])
		);
		// Fin du wrapper
		$html .= '</div>';
		// Retourne le html
		return $html;
	}

	/**
	 * Crée un champ d'upload de fichier
	 * @param string $nameId Nom et id du champ
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function file($nameId, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'before' => true,
			'class' => '',
			'classWrapper' => '',
			'noDirty' => false,
			'disabled' => false,
			'extensions' => '',
			'help' => '',
			'id' => $nameId,
			'label' => '',
			'maxlength' => '500',
			'name' => $nameId,
			'type' => 2,
			'value' => ''
		], $attributes);
		// Sauvegarde des données en cas d'erreur
		if($attributes['before'] AND array_key_exists($attributes['id'], common::$inputBefore)) {
			$attributes['value'] = common::$inputBefore[$attributes['id']];
		}
		// Début du wrapper
		$html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
		// Label
		if($attributes['label']) {
			$html .= self::label($attributes['id'], $attributes['label'], [
				'help' => $attributes['help']
			]);
		}
		// Notice
		$notice = '';
		if(array_key_exists($attributes['id'], common::$inputNotices)) {
			$notice = common::$inputNotices[$attributes['id']];
			$attributes['class'] .= ' notice';
		}
		$html .= self::notice($attributes['id'], $notice);
		// Champ caché contenant l'url de la page
		$html .= self::hidden($attributes['id'], [
			'class' => 'inputFileHidden',
			'disabled' => $attributes['disabled'],
			'maxlength' => $attributes['maxlength'],
			'value' => $attributes['value']
		]);
		// Champ d'upload
		$html .= '<div>';
		$html .= sprintf(
			'<a
				href="' .
					helper::baseUrl(false) . 'core/vendor/filemanager/dialog.php' .
					'?relative_url=1' .
					'&field_id=' . $attributes['id'] .
					'&type=' . $attributes['type'] .
					//'&akey=' . md5_file('site/data/'.'core.json') .
					'&akey=' . md5_file(core::DATA_DIR.'core.json') .
					($attributes['extensions'] ? '&extensions=' . $attributes['extensions'] : '')
				. '"
				class="inputFile %s %s"
				%s
				data-lity
			>
				' . self::ico('upload', 'right') . '
				<span class="inputFileLabel"></span>
			</a>',
			$attributes['class'],
			$attributes['disabled'] ? 'disabled' : '',
			helper::sprintAttributes($attributes, ['class', 'extensions', 'type', 'maxlength'])

		);
		$html .= self::button($attributes['id'] . 'Delete', [
			'class' => 'inputFileDelete',
			'value' => self::ico('cancel')
		]);
		$html .= '</div>';
		// Fin du wrapper
		$html .= '</div>';
		// Retourne le html
		return $html;
	}

	/**
	 * Ferme un formulaire
	 * @return string
	 */
	public static function formClose() {
		return '</form>';
	}

	/**
	 * Ouvre un formulaire protégé par CSRF
	 * @param string $id Id du formulaire
	 * @return string
	 */
	public static function formOpen($id) {
		// Ouverture formulaire
		$html = '<form id="' . $id . '" method="post">';
		// Stock le token CSRF
		$html .= self::hidden('csrf', [
			'value' => $_SESSION['csrf']
		]);
		// Retourne le html
		return $html;
	}



	/**
	 * Crée une aide qui s'affiche au survole
	 * @param string $text Texte de l'aide
	 * @return string
	 */
	public static function help($text) {
		return '<span class="helpButton" data-tippy-content="' . $text . '">' . self::ico('help') . '<!----></span>';
	}

	/**
	 * Crée un champ caché
	 * @param string $nameId Nom et id du champ
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function hidden($nameId, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'before' => true,
			'class' => '',
			'noDirty' => false,
			'id' => $nameId,
			//'maxlength' => '500',
			'name' => $nameId,
			'value' => ''
		], $attributes);
		// Sauvegarde des données en cas d'erreur
		if($attributes['before'] AND array_key_exists($attributes['id'], common::$inputBefore)) {
			$attributes['value'] = common::$inputBefore[$attributes['id']];
		}
		// Texte
		$html = sprintf('<input type="hidden" %s>', helper::sprintAttributes($attributes, ['before']));
		// Retourne le html
		return $html;
	}

	/**
	 * Crée un icône
	 * @param string $ico Classe de l'icône
	 * @param string $margin Ajoute un margin autour de l'icône (choix : left, right, all)
	 * @param bool $animate Ajoute une animation à l'icône
	 * @param string $fontSize Taille de la police
	 * @return string
	 */
	public static function ico($ico, $margin = '', $animate = false, $fontSize = '1em') {
		return '<span class="zwiico-' . $ico . ($margin ? ' zwiico-margin-' . $margin : '') . ($animate ? ' animate-spin' : '') . '" style="font-size:' . $fontSize . '"><!----></span>';
	}

	/**
	 * Crée un label
	 * @param string $for For du label
	 * @param array $attributes Attributs ($key => $value)
	 * @param string $text Texte du label
	 * @return string
	 */
	public static function label($for, $text, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'class' => '',
			'for' => $for,
			'help' => ''
		], $attributes);
		// Ajout d'une aide
		if($attributes['help'] !== '') {
			$text = $text . self::help($attributes['help']);
		}
		// Retourne le html
		return sprintf(
			'<label %s>%s</label>',
			helper::sprintAttributes($attributes),
			$text
		);
	}

	/**
	 * Crée un champ mail
	 * @param string $nameId Nom et id du champ
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function mail($nameId, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'autocomplete' => 'on',
			'before' => true,
			'class' => '',
			'classWrapper' => '',
			'noDirty' => false,
			'disabled' => false,
			'help' => '',
			'id' => $nameId,
			'label' => '',
			//'maxlength' => '500',
			'name' => $nameId,
			'placeholder' => '',
			'readonly' => false,
			'value' => ''
		], $attributes);
		// Sauvegarde des données en cas d'erreur
		if($attributes['before'] AND array_key_exists($attributes['id'], common::$inputBefore)) {
			$attributes['value'] = common::$inputBefore[$attributes['id']];
		}
		// Début du wrapper
		$html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
		// Label
		if($attributes['label']) {
			$html .= self::label($attributes['id'], $attributes['label'], [
				'help' => $attributes['help']
			]);
		}
		// Notice
		$notice = '';
		if(array_key_exists($attributes['id'], common::$inputNotices)) {
			$notice = common::$inputNotices[$attributes['id']];
			$attributes['class'] .= ' notice';
		}
		$html .= self::notice($attributes['id'], $notice);
		// Texte
		$html .= sprintf(
			'<input type="email" %s>',
			helper::sprintAttributes($attributes)
		);
		// Fin du wrapper
		$html .= '</div>';
		// Retourne le html
		return $html;
	}

	/**
	 * Crée une notice
	 * @param string $id Id du champ
	 * @param string $notice Notice
	 * @return string
	 */
	public static function notice($id, $notice) {
		return ' <span id="' . $id . 'Notice" class="notice ' . ($notice ? '' : 'displayNone') . '">' . $notice . '</span>';
	}

	/**
	 * Crée un champ mot de passe
	 * @param string $nameId Nom et id du champ
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function password($nameId, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'autocomplete' => 'on',
			'class' => '',
			'classWrapper' => '',
			'noDirty' => false,
			'disabled' => false,
			'help' => '',
			'id' => $nameId,
			'label' => '',
			//'maxlength' => '500',
			'name' => $nameId,
			'placeholder' => '',
			'readonly' => false
		], $attributes);
		// Début du wrapper
		$html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
		// Label
		if($attributes['label']) {
			$html .= self::label($attributes['id'], $attributes['label'], [
				'help' => $attributes['help']
			]);
		}
		// Notice
		$notice = '';
		if(array_key_exists($attributes['id'], common::$inputNotices)) {
			$notice = common::$inputNotices[$attributes['id']];
			$attributes['class'] .= ' notice';
		}
		$html .= self::notice($attributes['id'], $notice);
		// Mot de passe
		$html .= sprintf(
			'<input type="password" %s>',
			helper::sprintAttributes($attributes)
		);
		// Fin du wrapper
		$html .= '</div>';
		// Retourne le html
		return $html;
	}

	/**
	 * Crée un champ sélection
	 * @param string $nameId Nom et id du champ
	 * @param array $options Liste des options du champ de sélection ($value => $text)
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
    public static function select($nameId, array $options, array $attributes = []) {
        // Attributs par défaut
        $attributes = array_merge([
            'before' => true,
            'class' => '',
            'classWrapper' => '',
            'noDirty' => false,
            'disabled' => false,
            'help' => '',
            'id' => $nameId,
            'label' => '',
            'name' => $nameId,
            'selected' => '',
            'fonts' => false
        ], $attributes);
        // Sauvegarde des données en cas d'erreur
        if($attributes['before'] AND array_key_exists($attributes['id'], common::$inputBefore)) {
            $attributes['selected'] = common::$inputBefore[$attributes['id']];
        }
        // Liste des polices à intégrer
        if ($attributes['fonts'] === true) {
            foreach ($options as $fontId) {
                echo "<link href='https://fonts.googleapis.com/css?family=".str_replace(" ", "+", $fontId)."' rel='stylesheet' type='text/css'>\n";
            }
        }
        // Début du wrapper
        $html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
        // Label
        if($attributes['label']) {
            $html .= self::label($attributes['id'], $attributes['label'], [
                'help' => $attributes['help']
            ]);
        }
        // Notice
        $notice = '';
        if(array_key_exists($attributes['id'], common::$inputNotices)) {
            $notice = common::$inputNotices[$attributes['id']];
            $attributes['class'] .= ' notice';
        }
        $html .= self::notice($attributes['id'], $notice);
        // Début sélection
        $html .= sprintf('<select %s>',
            helper::sprintAttributes($attributes)
        );
        foreach($options as $value => $text) {
            $html .=   $attributes['fonts'] === true ? sprintf(
                    '<option value="%s"%s style="font-family:%s;">%s</option>',
                    $value,
                    $attributes['selected'] == $value ? ' selected' : '', // Double == pour ignorer le type de variable car $_POST change les types en string
                    str_replace('+',' ',$value),
                    $text
                ) : sprintf(
                    '<option value="%s"%s>%s</option>',
                        $value,
                        $attributes['selected'] == $value ? ' selected' : '', // Double == pour ignorer le type de variable car $_POST change les types en string
                        $text
                );
        }
        // Fin sélection
        $html .= '</select>';
        // Fin du wrapper
        $html .= '</div>';
        // Retourne le html
        return $html;
    }

	/**
	 * Crée une bulle de dialogue
	 * @param string $text Texte de la bulle
	 * @return string
	 */
	public static function speech($text) {
		return '<div class="speech"><div class="speechBubble">' . $text . '</div>' . template::ico('mimi speechMimi', '', false, '7em') . '</div>';
	}

	/**
	 * Crée un bouton validation
	 * @param string $nameId Nom & id du bouton validation
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function submit($nameId, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'class' => '',
			'disabled' => false,
			'ico' => 'check',
			'id' => $nameId,
			'name' => $nameId,
			'uniqueSubmission' => false, //true avant 9.1.08
			'value' => 'Enregistrer'
		], $attributes);
		// Retourne le html
		return  sprintf(
			'<button type="submit" class="%s%s" %s>%s</button>',
			$attributes['class'],
			$attributes['uniqueSubmission'] ? 'uniqueSubmission' : '',
			helper::sprintAttributes($attributes, ['class', 'ico', 'value']),
			($attributes['ico'] ? template::ico($attributes['ico'], 'right') : '') . $attributes['value']
		);
	}

	/**
	 * Crée un tableau
	 * @param array $cols Cols des colonnes (format: [col colonne1, col colonne2, etc])
	 * @param array $body Contenu (format: [[contenu1, contenu2, etc], [contenu1, contenu2, etc]])
	 * @param array $head Entêtes (format : [[titre colonne1, titre colonne2, etc])
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function table(array $cols = [], array $body = [], array $head = [], array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'class' => '',
			'classWrapper' => '',
			'id' => ''
		], $attributes);
		// Début du wrapper
		$html = '<div id="' . $attributes['id'] . 'Wrapper" class="tableWrapper ' . $attributes['classWrapper']. '">';
		// Début tableau
		$html .= '<table id="' . $attributes['id'] . '" class="table ' . $attributes['class']. '">';
		// Entêtes
		if($head) {
			// Début des entêtes
			$html .= '<thead>';
			$html .= '<tr>';
			$i = 0;
			foreach($head as $th) {
				$html .= '<th class="col' . $cols[$i++] . '">' . $th . '</th>';
			}
			// Fin des entêtes
			$html .= '</tr>';
			$html .= '</thead>';
		}
		// Début contenu
		$html .= '<tbody>';
		foreach($body as $tr) {
			$html .= '<tr>';
			$i = 0;
			foreach($tr as $td) {
				$html .= '<td " class="pos' . ($i+1) . ' col' . $cols[$i++] . '">' . $td . '</td>';
			}
			$html .= '</tr>';
		}
		// Fin contenu
		$html .= '</tbody>';
		// Fin tableau
		$html .= '</table>';
		// Fin container
		$html .= '</div>';
		// Retourne le html
		return $html;
	}

	/**
	 * Crée un champ texte court
	 * @param string $nameId Nom et id du champ
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function text($nameId, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'autocomplete' => 'on',
			'before' => true,
			'class' => '',
			'classWrapper' => '',
			'noDirty' => false,
			'disabled' => false,
			'help' => '',
			'id' => $nameId,
			'label' => '',
			//'maxlength' => '500',
			'name' => $nameId,
			'placeholder' => '',
			'readonly' => false,
			'value' => ''
		], $attributes);
		// Sauvegarde des données en cas d'erreur
		if($attributes['before'] AND array_key_exists($attributes['id'], common::$inputBefore)) {
			$attributes['value'] = common::$inputBefore[$attributes['id']];
		}
		// Début du wrapper
		$html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
		// Label
		if($attributes['label']) {
			$html .= self::label($attributes['id'], $attributes['label'], [
				'help' => $attributes['help']
			]);
		}
		// Notice
		$notice = '';
		if(array_key_exists($attributes['id'], common::$inputNotices)) {
			$notice = common::$inputNotices[$attributes['id']];
			$attributes['class'] .= ' notice';
		}
		$html .= self::notice($attributes['id'], $notice);
		// Texte
		$html .= sprintf(
			'<input type="text" %s>',
			helper::sprintAttributes($attributes)
		);
		// Fin du wrapper
		$html .= '</div>';
		// Retourne le html
		return $html;
	}

	/**
	 * Crée un champ texte long
	 * @param string $nameId Nom et id du champ
	 * @param array $attributes Attributs ($key => $value)
	 * @return string
	 */
	public static function textarea($nameId, array $attributes = []) {
		// Attributs par défaut
		$attributes = array_merge([
			'before' => true,
			'class' => '', // editorWysiwyg et editorCss possible pour utiliser le éditeurs (il faut également instancier les librairies)
			'classWrapper' => '',
			'disabled' => false,
			'noDirty' => false,
			'help' => '',
			'id' => $nameId,
			'label' => '',
			//'maxlength' => '500',
			'name' => $nameId,
			'readonly' => false,
			'value' => ''
		], $attributes);
		// Sauvegarde des données en cas d'erreur
		if($attributes['before'] AND array_key_exists($attributes['id'], common::$inputBefore)) {
			$attributes['value'] = common::$inputBefore[$attributes['id']];
		}
		// Début du wrapper
		$html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
		// Label
		if($attributes['label']) {
			$html .= self::label($attributes['id'], $attributes['label'], [
				'help' => $attributes['help']
			]);
		}
		// Notice
		$notice = '';
		if(array_key_exists($attributes['id'], common::$inputNotices)) {
			$notice = common::$inputNotices[$attributes['id']];
			$attributes['class'] .= ' notice';
		}
		$html .= self::notice($attributes['id'], $notice);
		// Texte long
		$html .= sprintf(
			'<textarea %s>%s</textarea>',
			helper::sprintAttributes($attributes, ['value']),
			$attributes['value']
		);
		// Fin du wrapper
		$html .= '</div>';
		// Retourne le html
		return $html;
	}
}
