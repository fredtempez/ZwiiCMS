<?php

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
					if (
						is_array($pagesId['page']) &&
						array_key_exists($this->getUrl(0), $pagesId['page'])
					) {
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
					// and $this->getUser('group') >= $this->getData(['page', $this->getUrl(0), 'group'])
					// Modification qui tient compte du profil de la page
					and ($this->getUser('group') * 10 + $this->getUser('profil')) >= ($this->getData(['page', $this->getUrl(0), 'group']) * 10 + $this->getData(['page', $this->getUrl(0), 'profil']))
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
					and $this->getUser('group') < self::GROUP_EDITOR
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
					array_intersect($t, self::$concurrentAccess) &&
					//array_intersect($t, self::$accessExclude) !== false &&
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