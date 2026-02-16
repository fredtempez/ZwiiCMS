<?php

class core extends common
{
	/**
	 * Constructeur du coeur
	 */
	public function __construct()
	{
		parent::__construct();

		// 1. Initialisation Système (CSRF, Timezone, Nettoyage, Backup)
		$this->initSystem();

		// 2. Gestion des fichiers CSS (Création et compilation dynamique avec variables CSS)
		$this->manageCss();
	}

	/**
	 * Gère l'initialisation système : Sécurité, Timezone, Nettoyage, Backup
	 */
	private function initSystem()
	{
		// Token CSRF
		if (empty($_SESSION['csrf'])) {
			$_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(64));
		}

		// Fuseau horaire
		common::$timezone = $this->getData(['config', 'timezone']);
		date_default_timezone_set(common::$timezone);

		// Supprime les fichiers temporaires
		$lastClearTmp = mktime(0, 0, 0);
		if ($lastClearTmp > $this->getData(['core', 'lastClearTmp']) + 86400) {
			$iterator = new DirectoryIterator(common::TEMP_DIR);
			foreach ($iterator as $fileInfos) {
				if (
					$fileInfos->isFile() &&
					$fileInfos->getBasename() !== '.htaccess' &&
					$fileInfos->getBasename() !== '.gitkeep'
				) {
					@unlink($fileInfos->getPathname());
				}
			}
			$this->setData(['core', 'lastClearTmp', $lastClearTmp]);
		}

		// Gestion des sauvegardes automatiques
		$lastBackup = mktime(0, 0, 0);
		if (
			$this->getData(['config', 'autoBackup']) &&
			$lastBackup > $this->getData(['core', 'lastBackup']) + 86400 &&
			$this->getData(['user'])
		) {
			if (helper::autoBackup(common::BACKUP_DIR, ['backup', 'tmp', 'file'])) {
				$this->setData(['core', 'lastBackup', $lastBackup]);

				// Nettoyage des anciennes sauvegardes (> 30 jours)
				$iterator = new DirectoryIterator(common::BACKUP_DIR);
				$now = time();
				foreach ($iterator as $file) {
					if (
						$file->isFile() &&
						$file->getBasename() !== '.htaccess' &&
						$file->getMTime() + (86400 * 30) < $now
					) {
						@unlink($file->getPathname());
					}
				}
			}
		}
	}

	/**
	 * Orchestre la vérification et la génération des fichiers CSS
	 */
	private function manageCss()
	{
		// Initialisation des fichiers physiques
		$this->initCssFiles();

		// Génération du CSS du thème (Variables CSS)
		$this->buildThemeCss();

		// Génération du CSS de l'administration (Variables CSS)
		$this->buildAdminCss();
	}

	/**
	 * Crée les fichiers CSS de base s'ils n'existent pas
	 */
	private function initCssFiles()
	{
		$cssFiles = [
			'custom.css' => 'core/module/theme/resource/custom.css',
			'theme.css' => '',
			'admin.css' => ''
		];

		foreach ($cssFiles as $file => $source) {
			$filePath = common::DATA_DIR . $file;
			if (!file_exists($filePath)) {
				$content = $source ? @file_get_contents($source) : '';
				if ($content !== false || $source === '') {
					if ($this->secure_file_put_contents($filePath, $content)) {
						chmod($filePath, 0755);
					}
				}
			}
		}
	}

	/**
	 * Helper pour résoudre les polices de caractères
	 */
	private function resolveFonts(array $fontKeys, string $context)
	{
		$fontsAvailable = [
			'files' => $this->getData(['font', 'files']),
			'imported' => $this->getData(['font', 'imported']),
			'websafe' => common::$fontsWebSafe
		];

		$usedFontIds = [];
		foreach ($fontKeys as $key) {
			if ($context === 'theme') {
				$usedFontIds[] = $this->getData(['theme', $key, 'font']);
			} else {
				$usedFontIds[] = $this->getData(['admin', $key]);
			}
		}
		$usedFontIds = array_unique($usedFontIds);

		$resolvedFonts = [];
		foreach ($usedFontIds as $fontId) {
			$resolvedFonts[$fontId] = null;
			foreach (['websafe', 'imported', 'files'] as $typeFont) {
				if (isset($fontsAvailable[$typeFont][$fontId])) {
					$resolvedFonts[$fontId] = $fontsAvailable[$typeFont][$fontId]['font-family'];
					break;
				}
			}
		}
		return $resolvedFonts;
	}

	/**
	 * Génère le fichier theme.css avec Variables CSS
	 */
	private function buildThemeCss()
	{
		$themeCssPath = common::DATA_DIR . 'theme.css';
		
		$currentContent = file_exists($themeCssPath) ? file_get_contents($themeCssPath) : '';
		$cssVersion = preg_split('/\*+/', $currentContent);
		$configHash = md5(json_encode($this->getData(['theme'])));

		if (empty($cssVersion[1]) or $cssVersion[1] !== $configHash) {
			
			$fonts = $this->resolveFonts(['text', 'title', 'header', 'menu', 'footer'], 'theme');

			$css = '/*' . $configHash . '*/';

			// --- VARIABLES CSS ---
			$css .= ':root {';

			// Couleurs de base
			$css .= '--body-bg: ' . $this->getData(['theme', 'body', 'backgroundColor']) . ';';
			$css .= '--site-bg: ' . $this->getData(['theme', 'site', 'backgroundColor']) . ';';
			$css .= '--text-color: ' . $this->getData(['theme', 'text', 'textColor']) . ';';
			$css .= '--link-color: ' . $this->getData(['theme', 'text', 'linkColor']) . ';';
			$css .= '--title-color: ' . $this->getData(['theme', 'title', 'textColor']) . ';';
			$css .= '--header-bg: ' . $this->getData(['theme', 'header', 'backgroundColor']) . ';';
			$css .= '--header-text: ' . $this->getData(['theme', 'header', 'textColor']) . ';';
			$css .= '--menu-bg: ' . $this->getData(['theme', 'menu', 'backgroundColor']) . ';';
			$css .= '--menu-text: ' . $this->getData(['theme', 'menu', 'textColor']) . ';';
			$css .= '--menu-active: ' . $this->getData(['theme', 'menu', 'activeColor']) . ';';
			$css .= '--menu-active-text: ' . $this->getData(['theme', 'menu', 'activeTextColor']) . ';';
			$css .= '--footer-bg: ' . $this->getData(['theme', 'footer', 'backgroundColor']) . ';';
			$css .= '--footer-text: ' . $this->getData(['theme', 'footer', 'textColor']) . ';';
			$css .= '--block-bg: ' . $this->getData(['theme', 'block', 'backgroundColor']) . ';';
			$css .= '--block-border: ' . $this->getData(['theme', 'block', 'borderColor']) . ';';

			// Dimensions & Fontes
			$css .= '--site-width: ' . $this->getData(['theme', 'site', 'width']) . ';';
			$css .= '--site-radius: ' . $this->getData(['theme', 'site', 'radius']) . ';';
			$css .= '--site-shadow: ' . $this->getData(['theme', 'site', 'shadow']) . ';';
			$css .= '--site-margin: ' . ($this->getData(['theme', 'site', 'margin']) ? '0' : '20px') . ';';
			$css .= '--font-size: ' . $this->getData(['theme', 'text', 'fontSize']) . ';';
			$css .= '--header-height: ' . $this->getData(['theme', 'header', 'height']) . ';';
			$css .= '--menu-height: ' . $this->getData(['theme', 'menu', 'height']) . ';';
			$css .= '--footer-height: ' . $this->getData(['theme', 'footer', 'height']) . ';';

			// Injection des familles de polices
			foreach ($fonts as $id => $family) {
				if ($family) $css .= '--font-' . $id . ': ' . $family . ';';
			}

			// Variantes de couleurs (Générées via helper)
			$btnColors = helper::colorVariants($this->getData(['theme', 'button', 'backgroundColor']));
			$css .= '--btn-bg: ' . $btnColors['normal'] . ';';
			$css .= '--btn-hover: ' . $btnColors['darken'] . ';';
			$css .= '--btn-active: ' . $btnColors['veryDarken'] . ';';
			$css .= '--btn-text: ' . $btnColors['text'] . ';';

			$linkColors = helper::colorVariants($this->getData(['theme', 'text', 'linkColor']));
			$css .= '--link-normal: ' . $linkColors['normal'] . ';';
			$css .= '--link-hover: ' . $linkColors['darken'] . ';';

			$titleColors = helper::colorVariants($this->getData(['theme', 'title', 'textColor']));
			$css .= '--title-normal: ' . $titleColors['normal'] . ';';
			$css .= '--title-hover: ' . $titleColors['darken'] . ';';

			$menuColors = helper::colorVariants($this->getData(['theme', 'menu', 'backgroundColor']));
			$css .= '--menu-normal: ' . $menuColors['normal'] . ';';
			$css .= '--menu-hover: ' . $menuColors['darken'] . ';';
			$css .= '--menu-very-dark: ' . $menuColors['veryDarken'] . ';';

			$subColors = helper::colorVariants($this->getData(['theme', 'menu', 'backgroundColorSub']));
			$css .= '--submenu-bg: ' . $subColors['normal'] . ';';

			$bodyColors = helper::colorVariants($this->getData(['theme', 'body', 'backgroundColor']));
			$css .= '--body-normal: ' . $bodyColors['normal'] . ';';
			
			$blockColors = helper::colorVariants($this->getData(['theme', 'block', 'backgroundColor']));
			$css .= '--block-header-bg: ' . $blockColors['normal'] . ';';
			$css .= '--block-header-text: ' . $blockColors['text'] . ';';

			// Marges Footer
			if ($this->getData(['theme', 'footer', 'fixed']) === true && $this->getData(['theme', 'footer', 'position']) === 'body') {
				$h = (int)str_replace('px', '', $this->getData(['theme', 'footer', 'height']));
				$css .= '--margin-bottom-large: ' . (($h * 2) + 31) . 'px;';
				$css .= '--margin-bottom-small: ' . (($h * 2) + 93) . 'px;';
			} else {
				$css .= '--margin-bottom-large: var(--site-margin);';
				$css .= '--margin-bottom-small: var(--site-margin);';
			}

			$css .= '}'; // Fin :root

			// --- STYLES UTILISANT LES VARIABLES ---
			
			$css .= 'body { font-family: var(--font-' . $this->getData(['theme', 'text', 'font']) . '); }';
			$css .= 'body, .row > div { font-size: var(--font-size); }';
			$css .= 'body { color: var(--text-color); }';

			if ($img = $this->getData(['theme', 'body', 'image'])) {
				$css .= 'html { background-image: url("../file/source/' . $img . '"); background-position: ' . $this->getData(['theme', 'body', 'imagePosition']) . '; background-attachment: ' . $this->getData(['theme', 'body', 'imageAttachment']) . '; background-size: ' . $this->getData(['theme', 'body', 'imageSize']) . '; background-repeat: ' . $this->getData(['theme', 'body', 'imageRepeat']) . '; }';
				$css .= 'body { background-color: rgba(0,0,0,0); }';
			} else {
				$css .= 'html { background-color: var(--body-normal); }';
			}

			$css .= '#backToTop { background-color: ' . $this->getData(['theme', 'body', 'toTopbackgroundColor']) . '; color: ' . $this->getData(['theme', 'body', 'toTopColor']) . '; }';
			$css .= 'a { color: var(--link-normal); }';
			
			// TinyMCE & Textes
			$css .= 'div.mce-edit-area { font-family: var(--font-' . $this->getData(['theme', 'text', 'font']) . '); }';
			$css .= '.mce-content-body { font-family: var(--font-' . $this->getData(['theme', 'text', 'font']) . '); color: var(--text-color); background-color: var(--site-bg); font-size: var(--font-size); }';
			$css .= '.editorWysiwyg, .editorWysiwygComment { background-color: var(--site-bg); }';
			$css .= 'span.mce-text { background-color: unset !important; }';
			
			$inputs = 'input[type=password],input[type=email],input[type=text],input[type=date],input[type=time],input[type=week],input[type=month],input[type=datetime-local],input[type=number],input[type=file],.inputFile,select,textarea';
			$css .= $inputs . ' { color: var(--text-color); background-color: var(--site-bg); }';
			$css .= '.blogDate { color: var(--text-color); } .blogPicture img { border: 1px solid var(--text-color); box-shadow: 1px 1px 5px var(--text-color); }';

			// Layout
			$css .= '.container { max-width: var(--site-width); }';
			if ($this->getData(['theme', 'footer', 'fixed']) === true) {
				$css .= 'footer { position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1000; }';
			}

			// Media Queries
			if ($this->getData(['theme', 'site', 'width']) === '100%') {
				$css .= '@media (min-width: 769px) { #site { margin: 0 auto var(--margin-bottom-large) 0 !important; } }';
				$css .= '@media (max-width: 768px) { #site { margin: 0 auto var(--margin-bottom-small) 0 !important; } }';
				$css .= '#site.light { margin: 5% auto !important; } body, #bar, body > header, body > nav, body > footer { margin: 0 auto !important; }';
			} else {
				$css .= '@media (min-width: 769px) { #site { margin: var(--site-margin) auto var(--margin-bottom-large) auto !important; } }';
				$css .= '@media (max-width: 768px) { #site { margin: var(--site-margin) auto var(--margin-bottom-small) auto !important; } }';
				$css .= '#site.light { margin: 5% auto !important; } body { margin: 0px 10px; } #bar, body > header, body > nav, body > footer { margin: 0 -10px; }';
			}
			if ($this->getData(['theme', 'site', 'width']) === '750px') {
				$css .= '.button, button { font-size: 0.8em; }';
			}

			$css .= '#site { background-color: var(--site-bg); border-radius: var(--site-radius); box-shadow: var(--site-shadow) #212223; }';

			// Boutons
			$css .= '.speechBubble, .button, .button:hover, button[type=submit], .pagination a, .pagination a:hover, input[type=checkbox]:checked + label:before, input[type=radio]:checked + label:before, .helpContent { background-color: var(--btn-bg); color: var(--btn-text); }';
			$css .= '.helpButton span { color: var(--btn-bg); }';
			
			$inputsHover = 'input[type=text]:hover,input[type=date]:hover,input[type=time]:hover,input[type=week]:hover,input[type=month]:hover,input[type=datetime-local]:hover,input[type=number]:hover,input[type=password]:hover,input[type=file]:hover.inputFile:hover,select:hover,textarea:hover';
			$css .= $inputsHover . ' { border-color: var(--btn-bg); }';
			$css .= '.speechBubble:before { border-color: var(--btn-bg) transparent transparent transparent; }';
			$css .= '.button:hover, button[type=submit]:hover, .pagination a:hover, input[type=checkbox]:not(:active):checked:hover + label:before, input[type=checkbox]:active + label:before, input[type=radio]:checked:hover + label:before, input[type=radio]:not(:checked):active + label:before { background-color: var(--btn-hover); }';
			$css .= '.helpButton span:hover { color: var(--btn-hover); }';
			$css .= '.button:active, button[type=submit]:active, .pagination a:active { background-color: var(--btn-active); }';

			// Titres
			$css .= 'h1,h2,h3,h4,h5,h6,h1 a,h2 a,h3 a,h4 a,h5 a,h6 a { color: var(--title-normal); font-family: var(--font-' . $this->getData(['theme', 'title', 'font']) . '); font-weight: ' . $this->getData(['theme', 'title', 'fontWeight']) . '; text-transform: ' . $this->getData(['theme', 'title', 'textTransform']) . '; }';
			$css .= 'h1 a:hover,h2 a:hover,h3 a:hover,h4 a:hover,h5 a:hover,h6 a:hover { color: var(--title-hover); }';

			// Blocs
			$css .= '.block { border: 1px solid var(--block-border); } .block h4 { background-color: var(--block-header-bg); color: var(--block-header-text); }';

			// Header
			if ($this->getData(['theme', 'header', 'margin'])) {
				$css .= ($this->getData(['theme', 'menu', 'position']) === 'site-first') ? 'header { margin: 0 20px; }' : 'header { margin: 20px 20px 0 20px; }';
			}
			$css .= 'header { background-color: var(--header-bg); }';

			if ($this->getData(['theme', 'header', 'feature']) === 'wallpaper') {
				$css .= 'header { background-size: ' . $this->getData(['theme', 'header', 'imageContainer']) . '; height: var(--header-height); line-height: var(--header-height); text-align: ' . $this->getData(['theme', 'header', 'textAlign']) . '; }';
				if ($img = $this->getData(['theme', 'header', 'image'])) {
					$css .= 'header { background-image: url("../file/source/' . $img . '"); background-position: ' . $this->getData(['theme', 'header', 'imagePosition']) . '; background-repeat: ' . $this->getData(['theme', 'header', 'imageRepeat']) . '; }';
				}
				$css .= 'header span { color: var(--header-text); font-family: var(--font-' . $this->getData(['theme', 'header', 'font']) . '); font-weight: ' . $this->getData(['theme', 'header', 'fontWeight']) . '; font-size: ' . $this->getData(['theme', 'header', 'fontSize']) . '; text-transform: ' . $this->getData(['theme', 'header', 'textTransform']) . '; }';
			} elseif ($this->getData(['theme', 'header', 'feature']) === 'feature') {
				$css .= 'header { height: var(--header-height); min-height: var(--header-height); overflow: hidden; }';
			}

			// Menu
			$css .= 'nav, nav.navMain a { background-color: var(--menu-normal); }';
			$css .= 'nav a, #toggle span, nav a:hover { color: var(--menu-text); }';
			$css .= 'nav a:hover { background-color: var(--menu-hover); }';
			$css .= 'nav a.active { color: var(--menu-active-text); }';
			
			$css .= 'nav a.active { background-color: ' . ($this->getData(['theme', 'menu', 'activeColorAuto']) ? 'var(--menu-very-dark)' : 'var(--menu-active)') . '; }';
			
			$css .= 'nav #burgerText { color: ' . $menuColors['text'] . '; }';
			$css .= 'nav .navSub a { background-color: var(--submenu-bg); }';

			$css .= 'nav .navMain a.active { border-radius: ' . $this->getData(['theme', 'menu', 'radius']) . '; }';
			$css .= '#menu { text-align: ' . $this->getData(['theme', 'menu', 'textAlign']) . '; }';

            
			$levelColors = helper::colorLevels($this->getData(['theme', 'menu', 'backgroundColorSub']), 4, 15);
			foreach ($levelColors as $level => $colors) {
				$zIndex = 999 + ($level * 10);
				if ($level === 1) {
					$css .= 'nav > ul > li > ul.navSubmenu1, .navDepth0 > ul.navSubmenu1 { top: 100%; left: 0; z-index: ' . $zIndex . '; }';
				} else {
					$parentDepth = $level - 1;
					$css .= '.navDepth' . $parentDepth . ' > ul.navSubmenu' . $level . ' { z-index: ' . $zIndex . '; }';
				}
				$css .= 'nav .navLevel' . $level . ' { background-color: ' . $colors['normal'] . '; }';
				$css .= 'nav .navLevel' . $level . ':hover { background-color: ' . $colors['hover'] . '; }';
			}

			$paddingNav = ($this->getData(['theme', 'menu', 'position']) === 'site-first' || $this->getData(['theme', 'menu', 'position']) === 'site-second') ? '10px 10px 0 10px' : '0 10px';
			$css .= ($this->getData(['theme', 'menu', 'margin'])) ? 'nav { padding: ' . $paddingNav . '; }' : 'nav { margin: 0; }';
			if ($this->getData(['theme', 'menu', 'position']) === 'top') $css .= 'nav { padding: 0 10px; }';

			$css .= '#toggle span, #menu a { padding: var(--menu-height); font-family: var(--font-' . $this->getData(['theme', 'menu', 'font']) . '); font-weight: ' . $this->getData(['theme', 'menu', 'fontWeight']) . '; font-size: ' . $this->getData(['theme', 'menu', 'fontSize']) . '; text-transform: ' . $this->getData(['theme', 'menu', 'textTransform']) . '; }';

			// Footer
			$css .= ($this->getData(['theme', 'footer', 'margin'])) ? 'footer { padding: 0 20px; }' : 'footer { padding: 0; }';
			$css .= 'footer span, #footerText > p { color: var(--footer-text); font-family: var(--font-' . $this->getData(['theme', 'footer', 'font']) . '); font-weight: ' . $this->getData(['theme', 'footer', 'fontWeight']) . '; font-size: ' . $this->getData(['theme', 'footer', 'fontSize']) . '; text-transform: ' . $this->getData(['theme', 'footer', 'textTransform']) . '; }';
			$css .= 'footer { background-color: var(--footer-bg); color: var(--footer-text); }';
			$css .= 'footer #footersite > div, footer #footerbody > div { margin: var(--footer-height) 0; }';
			$css .= '@media (max-width: 768px) { footer #footerbody > div { padding: 2px; } }';
			$css .= '#footerSocials { text-align: ' . $this->getData(['theme', 'footer', 'socialsAlign']) . '; } #footerText > p { text-align: ' . $this->getData(['theme', 'footer', 'textAlign']) . '; } #footerCopyright { text-align: ' . $this->getData(['theme', 'footer', 'copyrightAlign']) . '; }';

			$this->secure_file_put_contents($themeCssPath, $css);
			$this->sendCacheBustingHeaders();
		}
	}

	/**
	 * Génère le fichier admin.css avec Variables CSS
	 */
	private function buildAdminCss()
	{
		$adminCssPath = common::DATA_DIR . 'admin.css';

		// Check la version rafraichissement du theme admin
		if (file_exists($adminCssPath)) {
			$cssVersion = preg_split('/\*+/', file_get_contents($adminCssPath));
		} else {
			$cssVersion = ['', ''];
		}
		
		$configHash = md5(json_encode($this->getData(['admin'])));

		if (empty($cssVersion[1]) or $cssVersion[1] !== $configHash) {

			// Résolution des polices
			$fonts = $this->resolveFonts(['fontText', 'fontTitle'], 'admin');

			// Version
			$css = '/*' . $configHash . '*/';

			// --- Définition des variables CSS à la racine ---
			$css .= ':root {';

			// Variables de couleurs pour l'admin
			$css .= '--admin-background-color: ' . $this->getData(['admin', 'backgroundColor']) . ';';
			$css .= '--admin-color-text: ' . $this->getData(['admin', 'colorText']) . ';';
			$css .= '--admin-color-title: ' . $this->getData(['admin', 'colorTitle']) . ';';
			$css .= '--admin-button-background-color: ' . $this->getData(['admin', 'backgroundColorButton']) . ';';
			$css .= '--admin-button-grey-background-color: ' . $this->getData(['admin', 'backgroundColorButtonGrey']) . ';';
			$css .= '--admin-button-red-background-color: ' . $this->getData(['admin', 'backgroundColorButtonRed']) . ';';
			$css .= '--admin-button-help-background-color: ' . $this->getData(['admin', 'backgroundColorButtonHelp']) . ';';
			$css .= '--admin-button-green-background-color: ' . $this->getData(['admin', 'backgroundColorButtonGreen']) . ';';
			$css .= '--admin-block-background-color: ' . $this->getData(['admin', 'backgroundBlockColor']) . ';';
			$css .= '--admin-block-border-color: ' . $this->getData(['admin', 'borderBlockColor']) . ';';

			// Variables de taille et espacement
			$css .= '--admin-width: ' . $this->getData(['admin', 'width']) . ';';
			$css .= '--admin-font-size: ' . $this->getData(['admin', 'fontSize']) . ';';
			$css .= '--admin-site-margin: ' . ($this->getData(['theme', 'site', 'margin']) ? '0' : '20px') . ';';

			// Marges pour le pied de page fixe
			if ($this->getData(['theme', 'footer', 'fixed']) === true && $this->getData(['theme', 'footer', 'position']) === 'body') {
				$marginBottomLarge = ((str_replace('px', '', $this->getData(['theme', 'footer', 'height'])) * 2) + 31) . 'px';
				$marginBottomSmall = ((str_replace('px', '', $this->getData(['theme', 'footer', 'height'])) * 2) + 93) . 'px';
			} else {
				$marginBottomSmall = 'var(--admin-site-margin)'; // Correction pour utiliser la var définie
				$marginBottomLarge = 'var(--admin-site-margin)';
			}
			$css .= '--admin-margin-bottom-large: ' . $marginBottomLarge . ';';
			$css .= '--admin-margin-bottom-small: ' . $marginBottomSmall . ';';

			// Variables de polices
			foreach ($fonts as $fontId => $fontFamily) {
				// Utilisation directe des IDs résolus pour le root
				$usedIds = [
					$this->getData(['admin', 'fontText']),
					$this->getData(['admin', 'fontTitle']),
				];
				if(in_array($fontId, $usedIds)) {
					$css .= '--admin-font-' . $fontId . ': ' . $fontFamily . ';';
				}
			}

			// Variantes de couleurs
			$adminColors = helper::colorVariants($this->getData(['admin', 'backgroundColor']));
			$css .= '--admin-color: ' . $adminColors['normal'] . ';';

			$textColors = helper::colorVariants($this->getData(['admin', 'colorText']));
			$css .= '--admin-text-color: ' . $textColors['normal'] . ';';
			$css .= '--admin-text-color-text: ' . $textColors['text'] . ';';

			$buttonColors = helper::colorVariants($this->getData(['admin', 'backgroundColorButton']));
			$css .= '--admin-button-color: ' . $buttonColors['normal'] . ';';
			$css .= '--admin-button-color-darken: ' . $buttonColors['darken'] . ';';
			$css .= '--admin-button-color-very-darken: ' . $buttonColors['veryDarken'] . ';';
			$css .= '--admin-button-color-text: ' . $buttonColors['text'] . ';';

			$buttonGreyColors = helper::colorVariants($this->getData(['admin', 'backgroundColorButtonGrey']));
			$css .= '--admin-button-grey-color: ' . $buttonGreyColors['normal'] . ';';
			$css .= '--admin-button-grey-color-darken: ' . $buttonGreyColors['darken'] . ';';
			$css .= '--admin-button-grey-color-very-darken: ' . $buttonGreyColors['veryDarken'] . ';';
			$css .= '--admin-button-grey-color-text: ' . $buttonGreyColors['text'] . ';';

			$buttonRedColors = helper::colorVariants($this->getData(['admin', 'backgroundColorButtonRed']));
			$css .= '--admin-button-red-color: ' . $buttonRedColors['normal'] . ';';
			$css .= '--admin-button-red-color-darken: ' . $buttonRedColors['darken'] . ';';
			$css .= '--admin-button-red-color-very-darken: ' . $buttonRedColors['veryDarken'] . ';';
			$css .= '--admin-button-red-color-text: ' . $buttonRedColors['text'] . ';';

			$buttonHelpColors = helper::colorVariants($this->getData(['admin', 'backgroundColorButtonHelp']));
			$css .= '--admin-button-help-color: ' . $buttonHelpColors['normal'] . ';';
			$css .= '--admin-button-help-color-darken: ' . $buttonHelpColors['darken'] . ';';
			$css .= '--admin-button-help-color-very-darken: ' . $buttonHelpColors['veryDarken'] . ';';
			$css .= '--admin-button-help-color-text: ' . $buttonHelpColors['text'] . ';';

			$buttonGreenColors = helper::colorVariants($this->getData(['admin', 'backgroundColorButtonGreen']));
			$css .= '--admin-button-green-color: ' . $buttonGreenColors['normal'] . ';';
			$css .= '--admin-button-green-color-darken: ' . $buttonGreenColors['darken'] . ';';
			$css .= '--admin-button-green-color-very-darken: ' . $buttonGreenColors['veryDarken'] . ';';
			$css .= '--admin-button-green-color-text: ' . $buttonGreenColors['text'] . ';';

			$blockColors = helper::colorVariants($this->getData(['admin', 'backgroundBlockColor']));
			$css .= '--admin-block-color: ' . $blockColors['normal'] . ';';
			$css .= '--admin-block-color-text: ' . $blockColors['text'] . ';';

			$css .= '}'; // Fin :root

			// --- Utilisation des variables CSS ---
			
			$css .= '#site { background-color: var(--admin-color); }';
			$css .= 'p, div, label, select, input, table, span { font-family: var(--admin-font-' . $this->getData(['admin', 'fontText']) . '); }';
			$css .= 'body, .row > div { font-size: var(--admin-font-size); }';
			$css .= 'body h1, h2, h3, h4 a, h5, h6 { font-family: var(--admin-font-' . $this->getData(['admin', 'fontTitle']) . '); color: var(--admin-color-title); }';
			$css .= '.container { max-width: var(--admin-width); }';

			// Pied de page fixe
			if ($this->getData(['theme', 'footer', 'fixed']) === true) {
				$css .= 'footer { position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1000; }';
			}

			// Responsive margins
			if ($this->getData(['admin', 'width']) === '100%') {
				$css .= '@media (min-width: 769px) { #site { margin: 0 auto var(--admin-margin-bottom-large) 0 !important; } }';
				$css .= '@media (max-width: 768px) { #site { margin: 0 auto var(--admin-margin-bottom-small) 0 !important; } }';
				$css .= '#site.light { margin: 5% auto !important; }';
				$css .= 'body { margin: 0 auto !important; }';
				$css .= '#bar, body > header, body > nav, body > footer { margin: 0 auto !important; }';
			} else {
				$css .= '@media (min-width: 769px) { #site { margin: var(--admin-site-margin) auto var(--admin-margin-bottom-large) auto !important; } }';
				$css .= '@media (max-width: 768px) { #site { margin: var(--admin-site-margin) auto var(--admin-margin-bottom-small) auto !important; } }';
				$css .= '#site.light { margin: 5% auto !important; }';
				$css .= 'body { margin: 0px 10px; }';
				$css .= '#bar, body > header, body > nav, body > footer { margin: 0 -10px; }';
			}

			// Ajustement pour les petites largeurs
			$css .= $this->getData(['admin', 'width']) === '750px' ? '.button, button { font-size: 0.8em; }' : '';

			// TinyMCE et éléments de texte
			$css .= 'body:not(.editorWysiwyg), body:not(editorWysiwygComment), span .zwiico-help { color: var(--admin-text-color); }';
			$css .= 'table thead tr, table thead tr .zwiico-help { background-color: var(--admin-text-color); color: var(--admin-text-color-text); }';
			$css .= 'table thead th { color: var(--admin-text-color-text); }';

			// Éléments de formulaire et boutons
			$css .= 'input[type=checkbox]:checked + label::before, .speechBubble { background-color: var(--admin-button-color); color: var(--admin-button-color-text); }';
			$css .= '.speechBubble::before { border-color: var(--admin-button-color) transparent transparent transparent; }';
			$css .= '.button { background-color: var(--admin-button-color); color: var(--admin-button-color-text); }';
			$css .= '.button:hover { background-color: var(--admin-button-color-darken); color: var(--admin-button-color-text); }';
			$css .= '.button:active { background-color: var(--admin-button-color-very-darken); color: var(--admin-button-color-text); }';

			// Boutons gris
			$css .= '.button.buttonGrey { background-color: var(--admin-button-grey-color); color: var(--admin-button-grey-color-text); }';
			$css .= '.button.buttonGrey:hover { background-color: var(--admin-button-grey-color-darken); color: var(--admin-button-grey-color-text); }';
			$css .= '.button.buttonGrey:active { background-color: var(--admin-button-grey-color-very-darken); color: var(--admin-button-grey-color-text); }';
			$css .= '.icoTextGrey { color: var(--admin-button-grey-color); }';
			$css .= '.groupTitleLabel { background-color: var(--admin-button-grey-color); color: var(--admin-button-grey-color-text); border-color: var(--admin-button-grey-color-darken); }';
			$css .= '.groupTitleLabel:hover { background-color: var(--admin-button-grey-color-very-darken); }';

			// Boutons rouges
			$css .= '.button.buttonRed { background-color: var(--admin-button-red-color); color: var(--admin-button-red-color-text); }';
			$css .= '.button.buttonRed:hover { background-color: var(--admin-button-red-color-darken); color: var(--admin-button-red-color-text); }';
			$css .= '.button.buttonRed:active { background-color: var(--admin-button-red-color-very-darken); color: var(--admin-button-red-color-text); }';
			$css .= '.icoTextRed { color: var(--admin-button-red-color); }';

			// Boutons d'aide
			$css .= '.button.buttonHelp { background-color: var(--admin-button-help-color); color: var(--admin-button-help-color-text); }';
			$css .= '.button.buttonHelp:hover { background-color: var(--admin-button-help-color-darken); color: var(--admin-button-help-color-text); }';
			$css .= '.button.buttonHelp:active { background-color: var(--admin-button-help-color-very-darken); color: var(--admin-button-help-color-text); }';

			// Boutons verts
			$css .= '.button.buttonGreen, button[type=submit] { background-color: var(--admin-button-green-color); color: var(--admin-button-green-color-text); }';
			$css .= '.button.buttonGreen:hover, button[type=submit]:hover { background-color: var(--admin-button-green-color-darken); color: var(--admin-button-green-color-text); }';
			$css .= '.button.buttonGreen:active, button[type=submit]:active { background-color: var(--admin-button-green-color-darken); color: var(--admin-button-green-color-text); }';
			$css .= '.icoTextGreen { color: var(--admin-button-green-color); }';

			// Blocs et éléments de formulaire
			$css .= '.buttonTab, .block { border: 1px solid var(--admin-block-border-color); }';
			$css .= '.buttonTab, .block h4 { background-color: var(--admin-block-color); color: var(--admin-block-color-text); }';
			$css .= 'table tr, input[type=email], input[type=date], input[type=time], input[type=month], input[type=week], input[type=datetime-local], input[type=text], input[type=number], input[type=password], select:not(#barSelectLanguage), select:not(#barSelectPage), textarea:not(.editorWysiwyg), textarea:not(.editorWysiwygComment), .inputFile {';
			$css .= 'background-color: var(--admin-block-color); color: var(--admin-block-color-text); border: 1px solid var(--admin-block-border-color); }';

			// Bordure du contour TinyMCE
			$css .= '.mce-tinymce { border: 1px solid var(--admin-block-border-color) !important; }';

			// Enregistre la personnalisation
			file_put_contents($adminCssPath, $css);
		}
	}

	/**
	 * Envoie les headers pour invalider le cache
	 */
	private function sendCacheBustingHeaders()
	{
		header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
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
		elseif (is_readable(common::MODULE_DIR . $classPath)) {
			require common::MODULE_DIR . $classPath;
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
			$this->isConnected() === true
			and ($this->getUser('role') === common::ROLE_BANNED
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
			and ($this->isConnected() === false
				or ($this->isConnected() === true
					and $this->getUser('role') < common::ROLE_ADMIN
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

		// Check l'accès à la page
		$access = null;
		if ($this->getData(['page', $this->getUrl(0)]) !== null) {
			if (
				$this->getData(['page', $this->getUrl(0), 'role']) === common::ROLE_VISITOR
				|| ($this->isConnected() === true
					// and $this->getUser('role') >= $this->getData(['page', $this->getUrl(0), 'role'])
					// Modification qui tient compte du profil de la page
					&& ($this->getUser('role') * 10 + $this->getUser('profil')) >= ($this->getData(['page', $this->getUrl(0), 'role']) * 10 + $this->getData(['page', $this->getUrl(0), 'profil']))
				)
			) {
				$access = true;
			} else {
				if (
					// Test pour vérifier que la paged'accueil est bien définie
					// Afin d'éviter un bouclage en cas d'incohérence dans les données
					is_string($this->getData(['locale', 'homePageId']))
					&& $this->getUrl(0) === $this->getData(['locale', 'homePageId'])
				) {
					$access = 'login';
				} else {
					$access = false;
				}
			}
			// Empêcher l'accès aux pages désactivées par URL directe
			if (
				($this->getData(['page', $this->getUrl(0), 'disable']) === true
					and $this->isConnected() === false
				) or ($this->getData(['page', $this->getUrl(0), 'disable']) === true
					and $this->isConnected() === true
					and $this->getUser('role') < common::ROLE_EDITOR
				)
			) {
				$access = false;
			}
			// Lève une erreur si l'url est celle d'une page avec des éléments surnuméraires  https://www.site.fr/page/truc
			if (
				array_key_exists($this->getUrl(0), $this->getData(['page']))
				and $this->getUrl(1)
				and $this->getData(['page', $this->getUrl(0), 'moduleId']) === ''
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
		// Récupérer les données utilisateurs une seule fois
		$usersData = $this->getData(['user']) ?: [];
		$accessInfo['userName'] = '';
		$accessInfo['pageId'] = '';

		// Vérifier si l'utilisateur est connecté et que ce n'est pas une page de cours/swap
		if (!empty($usersData) && $this->getUser('id')) {
			$currentUrl = $this->getUrl();
			$currentTime = time();
			$currentUserId = $this->getUser('id');

			foreach ($usersData as $userId => $userData) {
				if (!isset($userData['accessUrl']) || $userId === $currentUserId) {
					continue;
				}

				$urlParts = explode('/', $userData['accessUrl']);
				$isConcurrentAccess = !empty(array_intersect($urlParts, common::$concurrentAccess));
				$isAccessTimerValid = $currentTime < ($userData['accessTimer'] ?? 0) + common::ACCESS_TIMER;

				if ($userData['accessUrl'] === $currentUrl && $isConcurrentAccess && $isAccessTimerValid) {
					$access = false;
					$accessInfo['userName'] = ($userData['lastname'] ?? '') . ' ' . ($userData['firstname'] ?? '');
					$accessInfo['pageId'] = end($urlParts);
					break; // On sort dès qu'on trouve un accès concurrent
				}
			}
		}
		// Accès concurrent stocke la page visitée
		if (
			$this->isConnected() === true
			&& $this->getUser('id')
			&& !$this->isPost()
		) {
			$this->setData(['user', $this->getUser('id'), 'accessUrl', $this->getUrl()], false);
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
		$contentRight = $this->getData(['page', $this->getUrl(0), 'barRight']) ? $this->getPage($this->getData(['page', $this->getUrl(0), 'barRight']), common::$siteContent) : '';
		$inlineStyle[] = $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'css']) === null ? '' : $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'css']);
		$inlineScript[] = $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'js']) === null ? '' : $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'js']);
		$contentLeft = $this->getData(['page', $this->getUrl(0), 'barLeft']) ? $this->getPage($this->getData(['page', $this->getUrl(0), 'barLeft']), common::$siteContent) : '';
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
				'content' => $this->getPage($this->getUrl(0), common::$siteContent),
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
				$pageContent = $this->getPage($this->getUrl(0), common::$siteContent);

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
					// Check le rôle de l'utilisateur
					if (
						($module::$actions[$action] === common::ROLE_VISITOR
							or ($this->isConnected() === true
								and $this->getUser('role') >= $module::$actions[$action]
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
										common::$inputBefore[$postId . '_' . $subPostId] = $subPostValue;
									}
								} else {
									common::$inputBefore[$postId] = $postValue;
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
							$modulePath = in_array($moduleId, common::$coreModuleIds) ? 'core/' : '';
							// CSS
							$stylePath = $modulePath . common::MODULE_DIR . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.css';
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
							$scriptPath = $modulePath . common::MODULE_DIR . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.js.php';
							if (file_exists($scriptPath)) {
								ob_start();
								include $scriptPath;
								$this->addOutput([
									'script' => ob_get_clean()
								]);
							}
							// Vue
							$viewPath = $modulePath . common::MODULE_DIR . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.php';
							if (file_exists($viewPath)) {
								ob_start();
								include $viewPath;
								$modpos = $this->getData(['page', $this->getUrl(0), 'modulePosition']);
								if ($modpos === 'top') {
									$this->addOutput([
										'content' => ob_get_clean() . ($output['showPageContent'] ? $pageContent : '')
									]);
								} elseif ($modpos === 'free' && strstr($pageContent, '[MODULE]')) {
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
				}
			}
		}
		// Erreurs
		if ($access === 'login') {
			http_response_code(302);
			header('Location:' . helper::baseUrl() . 'user/login/');
			exit();
		}
		// Redirection vers la page de connexion si la page nécessite une authentification et que l'utilisateur n'est pas connecté
		if ($access === null) {
			// Envoyer vers la page de connexion
			if (
				// Vérifie si la redirection après connexion est activée dans la configuration
				$this->getData(['config', 'connect', 'redirectLogin']) ===  true
				// Vérifie que l'utilisateur n'est pas connecté
				&& $this->isConnected() === false
				// Vérifie que l'URL actuelle n'est pas déjà sur le contrôleur 'user'
				&& $this->getUrl(0) !== 'user'
				// Vérifie que l'URL actuelle n'est pas sur l'action 'login'
				&& $this->getUrl(1) !== 'login'
				// Vérifie que la page actuelle nécessite une authentification (est dans la liste des accès concurrents)
				&& in_array($this->getUrl(0), self::$concurrentAccess)
			) {
				http_response_code(302);
				header('Location:' . helper::baseUrl() . 'user/login/');
				exit();
			}
		}
		// Page protégée
		if ($access === false) {
			// Redirections 403 classiques
			if ($accessInfo['userName']) {
				http_response_code(403);
				$this->addOutput([
					'title' => 'Accès verrouillé',
					'content' => template::speech('<p>' . sprintf(helper::translate('La page %s est ouverte par l\'utilisateur %s</p><p><a style="color:inherit" href="javascript:history.back()">%s</a></p>'), $accessInfo['pageId'], $accessInfo['userName'], helper::translate('Retour')))

				]);
			} else {
				if (
					$this->getData(['locale', 'page403']) !== 'none'
					and $this->getData(['page', $this->getData(['locale', 'page403'])])
				) {
					http_response_code(302);
					header('Location:' . helper::baseUrl() . $this->getData(['locale', 'page403']));
				} else {
					http_response_code(403);
					$this->addOutput([
						'title' => 'Accès interdit',
						'content' => template::speech('<p>' . helper::translate('Vous n\'êtes pas autorisé à consulter cette page (erreur 403)') . '</p><p><a style="color:inherit" href="javascript:history.back()">' . helper::translate('Retour') . '</a></p>')
					]);
				}
			}
		} elseif ($this->output['content'] === '') {
			http_response_code(404);
			// Pour éviter une 404, bascule dans l'espace correct si la page existe dans cette langue.
			// Parcourir les espaces
			foreach (common::$languages as $langId => $value) {;
				if (
					// l'espace existe
					is_dir(common::DATA_DIR . $langId) &&
					file_exists(common::DATA_DIR . $langId . '/page.json')
				) {
					// Lire les données des pages en respectant la configuration de JsonDb
					$pagesId = $this->fetchDataFile('page', $langId, 'page');
					if (
						// La page existe
						is_array($pagesId) &&
						array_key_exists($this->getUrl(0), $pagesId)
					) {
						// Basculer
						$_SESSION['ZWII_SITE_CONTENT'] = $langId;
						header('Refresh:0; url=' . helper::baseUrl() . $this->getUrl());
						exit();
					}
				}
			}
			if (
				$this->getData(['locale', 'page404']) !== 'none'
				and $this->getData(['page', $this->getData(['locale', 'page404'])])
			) {
				header('Location:' . helper::baseUrl() . $this->getData(['locale', 'page404']));
			} else {
				$this->addOutput([
					'title' => 'Page indisponible',
					'content' => template::speech('<p>' . helper::translate('La page demandée n\'existe pas ou est introuvable (erreur 404)') . '</p><p><a style="color:inherit" href="javascript:history.back()">' . helper::translate('Retour') . '</a></p>')
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
			case common::DISPLAY_RAW:
				echo $this->output['content'];
				break;
				// Layout vide
			case common::DISPLAY_LAYOUT_BLANK:
				require 'core/layout/blank.php';
				break;
				// Affichage en JSON
			case common::DISPLAY_JSON:
				header('Content-Type: application/json');
				echo json_encode($this->output['content']);
				break;
				// RSS feed
			case common::DISPLAY_RSS:
				header('Content-type: application/rss+xml; charset=UTF-8');
				echo $this->output['content'];
				break;
				// Layout allégé
			case common::DISPLAY_LAYOUT_LIGHT:
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
			case common::DISPLAY_LAYOUT_MAIN:
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