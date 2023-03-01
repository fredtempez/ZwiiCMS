<?php
class init extends common
{
	public static $defaultData = [
		'config' => [
			'autoBackup' => true,
			'autoUpdate' => true,
			'autoUpdateHtaccess' => false,
			'favicon' => 'favicon.ico',
			'faviconDark' => 'faviconDark.ico',
			'maintenance' => false,
			'cookieConsent' => true,
			'social' => [
				'facebookId' => 'facebook',
				'instagramId' => '',
				'pinterestId' => '',
				'twitterId' => '',
				'youtubeId' => '',
				'youtubeUserId' => '',
				'githubId' => ''
			],
			'timezone' => 'Europe/Paris',
			'proxyUrl' => '',
			'proxyPort' => '',
			'proxyType' => 'tcp://',
			'smtp' => [
				'enable' => false,
			],
			'seo' => [
				'robots' => true,
				'keyApi' => 'XXXXXXX-XXXXXXX-XXXXXXX-XXXXXXX'
			],
			'connect' => [
				'timeout' => 600,
				'attempt' => 3,
				'log' => false,
				'anonymousIp' => 2,
				'captcha' => true,
				'captchaStrong' => false,
				"captchaType" => 'num',
				'autoDisconnect' => true,
				'showPassword' => true,
				'redirectLogin' => true
			]
		],
		'core' => [
			'dataVersion' => 12300,
			'lastBackup' => 0,
			'lastClearTmp' => 0,
			'lastAutoUpdate' => 0,
			'updateAvailable' => false
		],
		'locale' => [
			'homePageId' => 'accueil',
			'page302' => 'none',
			'page403' => 'none',
			'page404' => 'none',
			'legalPageId' => 'none',
			'searchPageId' => 'none',
			'searchPageLabel' => 'Rechercher',
			'sitemapPageLabel' => 'Plan du site',
			'legalPageLabel' => 'Mentions légales',
			'metaDescription' => 'Zwii est un CMS sans base de données qui permet de créer et gérer facilement un site web sans aucune connaissance en programmation.',
			'title' => 'Votre site en quelques clics !',
			'cookies' => [
				'mainLabel' => 'Ce site utilise des cookies nécessaires à son fonctionnement, ils permettent de fluidifier son fonctionnement par exemple en mémorisant les données de connexion, la langue que vous avez choisie ou la validation de ce message.',
				'titleLabel' => 'Cookies essentiels',
				'linkLegalLabel' => 'Consulter  les mentions légales',
				'cookiesFooterText' => 'Cookies',
				'buttonValidLabel' => 'J\'ai compris'
			]
		],
		'font' => [
			'files' => [],
			'imported' => [
				'arimo' => [
					'name' => 'Arimo',
					'font-family' => 'Arimo,  sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/arimo'
				],
				'arvo' => [
					'name' => 'Arvo',
					'font-family' => 'Arvo,  sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/arvo'
				],
				'dancing-script' => [
					'name' => 'Dancing Script',
					'font-family' => '\'Dancing Script\', sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/dancing-script'
				],
				'droid-sans-2' => [
					'name' => 'Droid Sans',
					'font-family' =>  '\'Droid Sans\', sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/droid-sans-2'
				],
				'droid-serif-2' => [
					'name' => 'Droid Serif',
					'font-family' =>  '\'Droid Serif\', serif',
					'resource' => 'https://fonts.cdnfonts.com/css/droid-serif-2'
				],
				'indie-flower' => [
					'name' => 'Indie Flower',
					'font-family' => '\'Indie Flower\', sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/indie-flower'
				],
				'liberation-sans' => [
					'name' => 'Liberation Sans',
					'font-family' => '\'Liberation Sans\', sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/liberation-sans'
				],
				'liberation-serif' => [
					'name' => 'Liberation Serif',
					'font-family' => '\'Liberation Serif\', serif',
					'resource' => 'https://fonts.cdnfonts.com/css/liberation-serif'
				],
				'lobster-2' => [
					'name' => 'Lobster',
					'font-family' => 'Lobster, sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/lobster-2'
				],
				'lato' => [
					'name' => 'lato',
					'font-family' => 'Lato, sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/lato'
				],
				'lora' => [
					'name' => 'Lora',
					'font-family' => 'Lora, serif',
					'resource' => 'https://fonts.cdnfonts.com/css/lora'
				],
				'old-standard-tt-3' => [
					'name' => 'Old Standard TT',
					'font-family' => '\'Old Standard TT\', serif',
					'resource' => 'https://fonts.cdnfonts.com/css/old-standard-tt-3'
				],
				'open-sans' => [
					'name' => 'Open Sans',
					'font-family' => '\'Open Sans\', sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/open-sans'
				],
				'oswald-4' => [
					'name' => 'Oswald',
					'font-family' => 'Oswald, sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/oswald-4'
				],
				'pt-mono' => [
					'name' => 'PT Mono',
					'font-family' => '\'PT Mono\', monospace',
					'resource' => 'https://fonts.cdnfonts.com/css/pt-mono'
				],
				'pt-serif' => [
					'name' => 'PR Serif',
					'font-family' => '\'PT Serif\', serif',
					'resource' => 'https://fonts.cdnfonts.com/css/pt-serif'
				],
				'rancho' => [
					'name' => 'Rancho',
					'font-family' => 'Rancho, sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/rancho'
				],
				'roboto' => [
					'name' => 'Roboto',
					'font-family' => 'Roboto, sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/roboto'
				],
				'ubuntu' => [
					'name' => 'Ubuntu',
					'font-family' => 'Ubuntu, sans-serif',
					'resource' => 'https://fonts.cdnfonts.com/css/ubuntu'
				],
				'vollkorn' => [
					'name' => 'Vollkorn',
					'font-family' => 'Vollkorn, serif',
					'resource' => 'https://fonts.cdnfonts.com/css/vollkorn'
				]
			]
		],
		'page' => [
			'accueil' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'accueil.html',
				'hideTitle' => false,
				'homePageId' => true,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => '',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 1,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Accueil',
				'shortTitle' => 'Accueil',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => 'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			]
		],
		'module' => [],
		'user' => [],
		'theme' =>  [
			'body' => [
				'backgroundColor' => 'rgba(236, 239, 241, 1)',
				'image' => '',
				'imageAttachment' => 'scroll',
				'imageRepeat' => 'no-repeat',
				'imagePosition' => 'top center',
				'imageSize' => 'auto',
				'toTopbackgroundColor' => 'rgba(33, 34, 35, .8)',
				'toTopColor' => 'rgba(255, 255, 255, 1)'
			],
			'footer' => [
				'backgroundColor' => 'rgba(255, 255, 255, 1)',
				'font' => 'georgia',
				'fontSize' => '.8em',
				'fontWeight' => 'normal',
				'height' => '5px',
				'loginLink' => true,
				'margin' => true,
				'position' => 'site',
				'textColor' => 'rgba(33, 34, 35, 1)',
				'copyrightPosition' => 'right',
				'copyrightAlign' => 'right',
				'text' => '<p>Pied de page personnalisé</p>',
				'textPosition' => 'left',
				'textAlign' => 'left',
				'textTransform' => 'none',
				'socialsPosition' => 'center',
				'socialsAlign' => 'center',
				'displayVersion' => true,
				'displaySiteMap' => true,
				'displayCopyright' => false,
				'displayCookie' => false,
				'displayLegal' => false,
				'displaySearch' => false,
				'memberBar' => true,
				'template' => '3'
			],
			'header' => [
				'backgroundColor' => 'rgba(32, 59, 82, 1)',
				'font' => 'arial',
				'fontSize' => '2em',
				'fontWeight' => 'normal',
				'height' => '150px',
				'image' => 'banniere960.jpg',
				'imagePosition' => 'center center',
				'imageRepeat' => 'no-repeat',
				'margin' => false,
				'position' => 'site',
				'textAlign' => 'center',
				'textColor' => 'rgba(255, 255, 255, 1)',
				'textHide' => false,
				'textTransform' => 'none',
				'linkHomePage' => true,
				'imageContainer' => 'auto',
				'tinyHidden' => true,
				'feature' => 'wallpaper',
				'featureContent' => '<p>Bannière vide</p>',
				'width' => 'container'
			],
			'menu' => [
				'backgroundColor' => 'rgba(32, 59, 82, 1)',
				'backgroundColorSub' => 'rgba(32, 59, 82, 1)',
				'font' => 'arial',
				'fontSize' => '1em',
				'fontWeight' => 'normal',
				'height' => '15px 10px',
				'loginLink' => false,
				'margin' => false,
				'position' => 'site-second',
				'textAlign' => 'left',
				'textColor' => 'rgba(255, 255, 255, 1)',
				'textTransform' => 'none',
				'fixed' => false,
				'activeColorAuto' => true,
				'activeColor' => 'rgba(255, 255, 255, 1)',
				'activeTextColor' => 'rgba(255, 255, 255, 1)',
				'radius' => '0px',
				'memberBar' => false,
				'burgerLogo' => '',
				'burgerContent' => 'title',
				'width' => 'container'
			],
			'site' => [
				'backgroundColor' => 'rgba(255, 255, 255, 1)',
				'radius' => '0px',
				'shadow' => '0px 0px 0px',
				'width' => '960px'
			],
			'block' => [
				'backgroundColor' => 'rgba(236, 239, 241, 1)',
				'borderColor' => 'rgba(236, 239, 241, 1)'
			],
			'text' => [
				'font' => 'georgia',
				'fontSize' => '13px',
				'textColor' => 'rgba(33, 34, 35, 1)',
				'linkColor' => 'rgba(74, 105, 189, 1)'
			],
			'title' => [
				'font' => 'arial',
				'fontWeight' => 'normal',
				'textColor' => 'rgba(74, 105, 189, 1)',
				'textTransform' => 'none'
			],
			'button' => [
				'backgroundColor' => 'rgba(32, 59, 82, 1)'
			],
			'version' => 0
		],
		'admin' => [
			'backgroundColor' => 'rgba(255, 255, 255, 1)',
			'fontText' => 'georgia',
			'fontSize' => '13px',
			'fontTitle' => 'arial',
			'colorText' => 'rgba(33, 34, 35, 1)',
			'colorTitle' => 'rgba(74, 105, 189, 1)',
			'backgroundColorButton' => 'rgba(63, 125, 250, 1)',
			'backgroundColorButtonGrey' => 'rgba(170, 180, 188, 1)',
			'backgroundColorButtonRed' => 'rgba(217, 95, 78, 1)',
			'backgroundColorButtonGreen' => 'rgba(100, 207, 8, 1)',
			'backgroundColorButtonHelp' => 'rgba(255, 153, 0, 1)',
			'backgroundBlockColor' => 'rgba(236, 239, 241, 1)',
			'borderBlockColor' => 'rgba(190, 202, 209, 1)'
		],
		'blacklist' => [],
		'language'=> [],
	];
	public static $defaultDataI18n = [
		'en_EN' => [
			'locale' => [
				'homePageId' => 'home',
				'page302' => 'none',
				'page403' => 'none',
				'page404' => 'none',
				'legalPageId' => 'none',
				'searchPageId' => 'none',
				'searchPageLabel' => 'none',
				'sitemapPageLabel' => 'none',
				'legalPageLabel' => 'legals',
				'metaDescription' => 'Zwii is a database-free CMS that makes it easy to create and manage a website without any programming knowledge.',
				'title' => 'Your site in a few clicks!',
				'cookies' => [
					'mainLabel' => 'This site uses cookies necessary for its operation, they make it possible to streamline its operation for example by memorizing the connection data, the language you have chosen or the validation of this message.',
					'titleLabel' => 'Essential cookies',
					'linkLegalLabel' => 'Consult the legal notice',
					'cookiesFooterText' => 'Cookies',
					'buttonValidLabel' => 'Ok'
				]
			],
			'page' => [
				'home' => [
					'typeMenu' => 'text',
					'iconUrl' => '',
					'disable' => false,
					'content' => 'home.html',
					'hideTitle' => false,
					'homePageId' => true,
					'breadCrumb' => false,
					'metaDescription' => '',
					'metaTitle' => '',
					'moduleId' => '',
					'modulePosition' => 'bottom',
					'parentPageId' => '',
					'position' => 1,
					'group' => self::GROUP_VISITOR,
					'targetBlank' => false,
					'title' => 'Home page',
					'shortTitle' => 'Home',
					'block' => '12',
					'barLeft' => '',
					'barRight' => '',
					'displayMenu' => 'none',
					'hideMenuSide' => false,
					'hideMenuChildren' => false,
					'extraPosition' => false,
					'css' => '',
					'js' => ''
				]
			],
			'html' => 'Welcome in this ZwiiCMS, congratulations this is you first page !'
		]
	];


	public static $siteData = [
		'page' => [
			'accueil' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'accueil.html',
				'hideTitle' => false,
				'homePageId' => true,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => '',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 1,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Accueil',
				'shortTitle' => 'Accueil',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => 'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'enfant' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'enfant.html',
				'hideTitle' => false,
				'breadCrumb' => true,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => '',
				'modulePosition' => 'bottom',
				'parentPageId' => 'accueil',
				'position' => 1,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Page Enfant',
				'shortTitle' => 'Enfant',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' =>  'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'privee' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'privee.html',
				'hideTitle' => false,
				'breadCrumb' => true,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => '',
				'parentPageId' => '',
				'modulePosition' => 'bottom',
				'position' => 2,
				'group' => self::GROUP_MEMBER,
				'targetBlank' => false,
				'title' => 'Page privée',
				'shortTitle' => 'Privée',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' =>  'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'mise-en-page' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'mise-en-page.html',
				'hideTitle' => false,
				'breadCrumb' => true,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => '',
				'parentPageId' => 'accueil',
				'modulePosition' => 'bottom',
				'position' => 2,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Mise en page',
				'shortTitle' => 'Mise en page',
				'block' => '4-8',
				'barLeft' => 'barre',
				'barRight' => '',
				'displayMenu' =>  'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'menu-lateral' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'menu-lateral.html',
				'hideTitle' => false,
				'breadCrumb' => true,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => '',
				'parentPageId' => 'accueil',
				'modulePosition' => 'bottom',
				'position' => 3,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Barre latérale avec menu',
				'shortTitle' => 'Menu latéral',
				'block' => '9-3',
				'barLeft' => '',
				'barRight' => 'barrelateraleavecmenu',
				'displayMenu' =>  'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'blog' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'blog.html',
				'hideTitle' => false,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => 'blog',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 3,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Blog',
				'shortTitle' => 'Blog',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' =>  'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'galeries' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'galeries.html',
				'hideTitle' => false,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => 'gallery',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 4,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Galeries d\'images',
				'shortTitle' => 'Galeries',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' =>  'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'site-de-zwii' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => '',
				'hideTitle' => false,
				'homePageId' => false,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => 'redirection',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 5,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => true,
				'title' => 'Site de Zwii',
				'shortTitle' => 'Site de Zwii',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => 'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'contact' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'contact.html',
				'hideTitle' => false,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => 'form',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 6,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Contact',
				'shortTitle' => 'Contact',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => 'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'barre' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'barre.html',
				'hideTitle' => false,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => '',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 0,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Barre latérale',
				'shortTitle' => 'Barre latérale',
				'block' => 'bar',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => 'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'barrelateraleavecmenu' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'barrelateraleavecmenu.html',
				'hideTitle' => false,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => '',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 0,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Barre latérale avec menu',
				'shortTitle' => 'Barre latérale avec menu',
				'block' => 'bar',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => 'parents',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'mentions-legales' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'mentions-legales.html',
				'hideTitle' => true,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => 'Mentions Légales',
				'moduleId' => '',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 0,
				'group' => 0,
				'targetBlank' => false,
				'title' => 'Mentions légales',
				'shortTitle' => 'Mentions légales',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => 'none',
				'hideMenuSide' => false,
				'hideMenuHead' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'erreur302' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'erreur302.html',
				'hideTitle' => false,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => '',
				'modulePosition' => '',
				'parentPageId' => '',
				'position' => 0,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Maintenance en cours',
				'shortTitle' => 'Maintenance en cours',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => 'none',
				'hideMenuSide' => true,
				'hideMenuHead' => true,
				'hideMenuChildren' => true,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'erreur403' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'erreur403.html',
				'hideTitle' => false,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => '',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 0,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Erreur 403',
				'shortTitle' => 'Erreur 403',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => 'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'erreur404' => [
				'typeMenu' => 'text',
				'iconUrl' => '',
				'disable' => false,
				'content' => 'erreur404.html',
				'hideTitle' => false,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => 'search',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 0,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Erreur 404',
				'shortTitle' => 'Erreur 404',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => 'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => false,
				'css' => '',
				'js' => ''
			],
			'recherche' => [
				'typeMenu' => 'icon',
				'iconUrl' => 'icones/loupe.png',
				'disable' => false,
				'content' => 'recherche.html',
				'hideTitle' => true,
				'breadCrumb' => false,
				'metaDescription' => '',
				'metaTitle' => '',
				'moduleId' => 'search',
				'modulePosition' => 'bottom',
				'parentPageId' => '',
				'position' => 7,
				'group' => self::GROUP_VISITOR,
				'targetBlank' => false,
				'title' => 'Recherche dans le site',
				'shortTitle' => 'Rechercher',
				'block' => '12',
				'barLeft' => '',
				'barRight' => '',
				'displayMenu' => 'none',
				'hideMenuSide' => false,
				'hideMenuChildren' => false,
				'extraPosition' => true,
				'css' => '',
				'js' => ''
			],
		],
		'module' => [
			'blog' => [
				'config' => [
					'feeds' => true,
					'feedsLabel' => 'Syndication RSS',
					'itemsperPage' => 4
				],
				'posts' => [
					'mon-premier-article' => [
						'closeComment' => false,
						'comment' => [
							'58e11d09e5aff' => [
								'author' => 'Rémi',
								'content' => 'Article bien rédigé et très pertinent, bravo !',
								'createdOn' => 1421748000,
								'userId' => '',
								'approval' => true
							]
						],
						'content' => '<p>Et eodem impetu Domitianum praecipitem per scalas itidem funibus constrinxerunt, eosque coniunctos per ampla spatia civitatis acri raptavere discursu. iamque artuum et membrorum divulsa conpage superscandentes corpora mortuorum ad ultimam truncata deformitatem velut exsaturati mox abiecerunt in flumen.</p><p>Ex his quidam aeternitati se commendari posse per statuas aestimantes eas ardenter adfectant quasi plus praemii de figmentis aereis sensu carentibus adepturi, quam ex conscientia honeste recteque factorum, easque auro curant inbracteari, quod Acilio Glabrioni delatum est primo, cum consiliis armisque regem superasset Antiochum. quam autem sit pulchrum exigua haec spernentem et minima ad ascensus verae gloriae tendere longos et arduos, ut memorat vates Ascraeus, Censorius Cato monstravit. qui interrogatus quam ob rem inter multos... statuam non haberet malo inquit ambigere bonos quam ob rem id non meruerim, quam quod est gravius cur inpetraverim mussitare.</p><p>Latius iam disseminata licentia onerosus bonis omnibus Caesar nullum post haec adhibens modum orientis latera cuncta vexabat nec honoratis parcens nec urbium primatibus nec plebeiis.</p>',
						'picture' => 'galerie/landscape/meadow.jpg',
						'picturePosition' => 'left',
						'hidePicture' => false,
						'pictureSize' => 20,
						'picturePosition' => 'left',
						'publishedOn' => 1548790902,
						'state' => true,
						'title' => 'Mon premier article',
						'userId' => '', // Géré au moment de l'installation
						'editConsent' => 'all',
						'commentMaxlength' => '500',
						'commentApproved' =>  false,
						'commentClose' =>  false,
						'commentNotification' =>  false,
						'commentGroupNotification' =>  1
					],
					'mon-deuxieme-article' => [
						'closeComment' => false,
						'comment' => [],
						'content' => '<p>Et prima post Osdroenam quam, ut dictum est, ab hac descriptione discrevimus, Commagena, nunc Euphratensis, clementer adsurgit, Hierapoli, vetere Nino et Samosata civitatibus amplis inlustris.</p><p>Ob haec et huius modi multa, quae cernebantur in paucis, omnibus timeri sunt coepta. et ne tot malis dissimulatis paulatimque serpentibus acervi crescerent aerumnarum, nobilitatis decreto legati mittuntur: Praetextatus ex urbi praefecto et ex vicario Venustus et ex consulari Minervius oraturi, ne delictis supplicia sint grandiora, neve senator quisquam inusitato et inlicito more tormentis exponeretur.</p><p>Sed ut tum ad senem senex de senectute, sic hoc libro ad amicum amicissimus scripsi de amicitia. Tum est Cato locutus, quo erat nemo fere senior temporibus illis, nemo prudentior; nunc Laelius et sapiens (sic enim est habitus) et amicitiae gloria excellens de amicitia loquetur. Tu velim a me animum parumper avertas, Laelium loqui ipsum putes. C. Fannius et Q. Mucius ad socerum veniunt post mortem Africani; ab his sermo oritur, respondet Laelius, cuius tota disputatio est de amicitia, quam legens te ipse cognosces.</p>',
						'picture' => 'galerie/landscape/desert.jpg',
						'hidePicture' => false,
						'picturePosition' => 'right',
						'pictureSize' => 40,
						'picturePosition' => 'right',
						'publishedOn' => 1550432502,
						'state' => true,
						'title' => 'Mon deuxième article',
						'userId' => '', // Géré au moment de l'installation
						'editConsent' => 'all',
						'commentMaxlength' => '500',
						'commentApproved' =>  false,
						'commentClose' =>  false,
						'commentNotification' =>  false,
						'commentGroupNotification' =>  1
					],
					'mon-troisieme-article' => [
						'closeComment' => true,
						'comment' => [],
						'content' => '<p>Rogatus ad ultimum admissusque in consistorium ambage nulla praegressa inconsiderate et leviter proficiscere inquit ut praeceptum est, Caesar sciens quod si cessaveris, et tuas et palatii tui auferri iubebo prope diem annonas. hocque solo contumaciter dicto subiratus abscessit nec in conspectum eius postea venit saepius arcessitus.</p><p>Proinde concepta rabie saeviore, quam desperatio incendebat et fames, amplificatis viribus ardore incohibili in excidium urbium matris Seleuciae efferebantur, quam comes tuebatur Castricius tresque legiones bellicis sudoribus induratae.</p><p>Inter has ruinarum varietates a Nisibi quam tuebatur accitus Vrsicinus, cui nos obsecuturos iunxerat imperiale praeceptum, dispicere litis exitialis certamina cogebatur abnuens et reclamans, adulatorum oblatrantibus turmis, bellicosus sane milesque semper et militum ductor sed forensibus iurgiis longe discretus, qui metu sui discriminis anxius cum accusatores quaesitoresque subditivos sibi consociatos ex isdem foveis cerneret emergentes, quae clam palamve agitabantur, occultis Constantium litteris edocebat inplorans subsidia, quorum metu tumor notissimus Caesaris exhalaret.</p>',
						'picture' => 'galerie/landscape/iceberg.jpg',
						'hidePicture' => false,
						'picturePosition' => 'left',
						'pictureSize' => 100,
						'picturePosition' => 'left',
						'publishedOn' => 1550864502,
						'state' => true,
						'title' => 'Mon troisième article',
						'userId' => '', // Géré au moment de l'installation
						'editConsent' => 'all',
						'commentMaxlength' => '500',
						'commentApproved' =>  false,
						'commentClose' =>  false,
						'commentNotification' =>  false,
						'commentGroupNotification' =>  1
					],
				],
			],
			'galeries' => [
				'content' => [
					'beaux-paysages' => [
						'config' => [
							'name' => 'Beaux paysages',
							'directory' => self::FILE_DIR . 'source/galerie/landscape',
							'homePicture' => 'iceberg.jpg',
							'sort' => 'SORT_ASC',
							'position' => 1
						],
						'legend' => [
							'desertjpg' => 'Un désert',
							'icebergjpg' => 'Un iceberg',
							'meadowjpg' => 'Une prairie'
						],
						'positions' => [
							'desertjpg' => 3,
							'icebergjpg' => 1,
							'meadowjpg' => 2
						]
					],
					'espace' => [
						'config' => [
							'name' => 'Espace',
							'directory' => self::FILE_DIR . 'source/galerie/space',
							'homePicture' => 'nebula.jpg',
							'sort' => 'SORT_ASC',
							'position' => 2
						],
						'legend' => [
							'earthjpg' => 'La Terre et la Lune',
							'cosmosjpg' => 'Le cosmos',
							'nebulajpg' => 'Une nébuleuse'
						],
						'positions' => [
							'earthjpg' => 1,
							'cosmosjpg' => 3,
							'nebulajpg' => 2
						]
					],
				],
				'theme' => [
					'thumbAlign' 	   => 'center',
					'thumbWidth' 	   => '18em',
					'thumbHeight'	   => '15em',
					'thumbMargin'	   => '.5em',
					'thumbBorder'	   => '.1em',
					'thumbOpacity'	   => '.7',
					'thumbBorderColor' => 'rgba(221, 221, 221, 1)',
					'thumbRadius'      => '.3em',
					'thumbShadows'     => '1px 1px 10px',
					'thumbShadowsColor' => 'rgba(125, 125, 125, 1)',
					'legendHeight'	   => '.375em',
					'legendAlign'	   => 'center',
					'legendTextColor'  => 'rgba(255, 255, 255, 1)',
					'legendBgColor'	   => 'rgba(0, 0, 0, .6)',
					'style'            =>  'site/data/modules/gallery/galeries.css'
				],
				'config' => [
					'versionData'      =>  '3.0'
				],
			],
			'site-de-zwii' => [
				'url' => 'https://zwiicms.fr/',
				'count' => 0
			],
			'contact' => [
				'config' => [
					'button' => '',
					'captcha' => true,
					'group' => self::GROUP_ADMIN,
					'pageId' => '',
					'subject' => ''
				],
				'data' => [],
				'input' => [
					[
						'name' => 'Adresse électronique',
						'position' => 1,
						'required' => true,
						'type' => 'mail',
						'values' => ''
					],
					[
						'name' => 'Sujet',
						'position' => 2,
						'required' => true,
						'type' => 'text',
						'values' => ''
					],
					[
						'name' => 'Message',
						'position' => 3,
						'required' => true,
						'type' => 'textarea',
						'values' => ''
					]
				]
			]
		],
		'locale' => [
			'homePageId' => 'accueil',
			'page302' => 'none',
			'page403' => 'none',
			'page404' => 'none',
			'legalPageId' => 'none',
			'searchPageId' => 'none',
			'metaDescription' => 'Zwii est un CMS sans base de données qui permet de créer et gérer facilement un site web sans aucune connaissance en programmation.',
			'title' => 'Votre site en quelques clics !'
		]
	];

	public static $siteContent = [
		'accueil' => [
			'content' => '<h2>Bienvenue sur votre nouveau site Zwii !</h2>
						<p><strong>Un email contenant le récapitulatif de votre installation vient de vous être envoyé.</strong></p>
						<p>Connectez-vous dès maintenant à votre espace membre afin de créer un site à votre image ! Vous pourrez personnaliser le thème, créer des pages, ajouter des utilisateurs et bien plus encore !</p>
						<p>Si vous avez besoin d\'aide ou si vous cherchez des informations sur Zwii, n\'hésitez pas à jeter un œil à notre <a title="Forum" href="https://forum.zwiicms.fr/">forum</a>.</p>'
		],
		'enfant' => [
			'content' => '<p>Vous pouvez assigner des parents à vos pages afin de mieux organiser votre menu !</p>
						<div class="row">
						<div class="col4"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ac dolor arcu. Cras dignissim finibus nisi, vulputate egestas mauris faucibus ultricies. Nullam ornare pretium eleifend. Donec placerat purus ut turpis dapibus condimentum. Fusce at leo pharetra nisl vestibulum fermentum. Maecenas feugiat justo at semper tincidunt. Integer in blandit lorem.</p></div>
						<div class="col4"><p>Ergo ego senator inimicus, si ita vultis, homini, amicus esse, sicut semper fui, rei publicae debeo. Quid? si ipsas inimicitias, depono rei publicae causa, quis me tandem iure reprehendet, praesertim cum ego omnium meorum consiliorum atque factorum exempla semper ex summorum hominum consiliis atque factis mihi censuerim petenda.</p></div>
						<div class="col4"><p>Principium autem unde latius se funditabat, emersit ex negotio tali. Chilo ex vicario et coniux eius Maxima nomine, questi apud Olybrium ea tempestate urbi praefectum, vitamque suam venenis petitam adseverantes inpetrarunt ut hi, quos suspectati sunt, ilico rapti conpingerentur in vincula, organarius Sericus et Asbolius palaestrita et aruspex Campensis.</p></div>
						</div>'
		],
		'privee' => [
			'content' => '<p>Cette page n\'est visible que des membres de votre site !</p>
						<div class="row">
							<div class="col6"><p>Eius populus ab incunabulis primis ad usque pueritiae tempus extremum, quod annis circumcluditur fere trecentis, circummurana pertulit bella, deinde aetatem ingressus adultam post multiplices bellorum aerumnas Alpes transcendit et fretum, in iuvenem erectus et virum ex omni plaga quam orbis ambit inmensus, reportavit laureas et triumphos, iamque vergens in senium et nomine solo aliquotiens vincens ad tranquilliora vitae discessit.</p></div>
							<div class="col6"><p>Exsistit autem hoc loco quaedam quaestio subdifficilis, num quando amici novi, digni amicitia, veteribus sint anteponendi, ut equis vetulis teneros anteponere solemus. Indigna homine dubitatio! Non enim debent esse amicitiarum sicut aliarum rerum satietates; veterrima quaeque, ut ea vina, quae vetustatem ferunt, esse debet suavissima; verumque illud est, quod dicitur, multos modios salis simul edendos esse, ut amicitiae munus expletum sit.</p></div>
						</div>'
		],
		'mise-en-page' => [
			'content' => '<p>Vous pouvez ajouter une ou deux barres latérales aux pages de votre site. Cette mise en page se définit dans les paramètres de page et peut s\'appliquer à l\'ensemble du site ou à certaines pages en particulier, au gré de vos désirs.</p>
						<p>Pour créer une barre latérale à partir d\'une "Nouvelle page" ou transformer une page existante en barre latérale, sélectionnez l\'option dans la liste des gabarits. On peut bien sûr définir autant de barres latérales qu\'on le souhaite.</p>
						<p>Cette nouvelle fonctionnalité autorise toutes sortes d\'utilisations : texte, encadrés, images, vidéos... ou simple marge blanche. Seule restriction : on ne peut pas installer un module dans une barre latérale.</p>
						<p>La liste des barres disponibles et leur emplacement s\'affichent en fonction du gabarit que vous aurez choisi.'
		],
		'menu-lateral' => [
			'content' => '<p>Cette page illustre la possibilité d\'ajouter un menu dans les barres latérales.<br>
						Deux types de menus sont disponibles : l\'un reprenant les rubriques du menu principal comme celui-ci, l\'autre listant les pages d\'une même rubrique. Le choix du type de menu se fait dans la page de configuration d\'une barre latérale.</p>
						<p>Pour ajouter un menu à une page, choisissez une barre latérale avec menu dans la page de configuration. Les bulles d\'aide de la rubrique "Menu" expliquent comment masquer une page.</p>'
		],
		'blog' => [
			'content' => '<p>Cette page contient une instance du module de blog. Cliquez sur un article afin de le lire et de poster des commentaires.</p>'
		],
		'galeries' => [
			'content' => '<p>Cette page contient une instance du module de galeries photos. Cliquez sur la galerie ci-dessous afin de voir les photos qu\'elle contient.</p>'
		],
		'site-de-zwii' => [
			'content' => ''
		],
		'contact' => [
			'content' => '<p>Cette page contient un exemple de formulaire conçu à partir du module de génération de formulaires. Il est configuré pour envoyer les données saisies par mail aux administrateurs du site.</p>'
		],
		'barre' => [
			'content' => '<div class="block"><h4>ZwiiCMS</h4><h2>Le CMS sans base de données à l\'installation simple et rapide</h2></div>'
		],
		'barrelateraleavecmenu' => [
			'content' => '<p>&nbsp;</p>'
		],
		'mentions-legales' => [
			'content' => '<h1 style="text-align: center;">Conditions g&eacute;n&eacute;rales d\'utilisation</h1>
			<h1 style="text-align: center;">En vigueur au 01/06/2020</h1>
			<p><strong>Avertissement</strong>Cette page fictive est donn&eacute;e &agrave; titre indicatif elle a &eacute;t&eacute; r&eacute;alis&eacute;e &agrave; l\'aide d\'un g&eacute;n&eacute;rateur : <a href="https://www.legalplace.fr" target="_blank" rel="noopener">https://www.legalplace.fr</a></p>
			<p justify="">Les pr&eacute;sentes conditions g&eacute;n&eacute;rales d\'utilisation (dites &laquo; CGU &raquo;) ont pour objet l\'encadrement juridique des modalit&eacute;s de mise &agrave; disposition du site et des services par et de d&eacute;finir les conditions d&rsquo;acc&egrave;s et d&rsquo;utilisation des services par &laquo; l\'Utilisateur &raquo;.</p>
			<p justify="">Les pr&eacute;sentes CGU sont accessibles sur le site &agrave; la rubrique &laquo;CGU&raquo;.</p>
			<p justify="">Toute inscription ou utilisation du site implique l\'acceptation sans aucune r&eacute;serve ni restriction des pr&eacute;sentes CGU par l&rsquo;utilisateur. Lors de l\'inscription sur le site via le Formulaire d&rsquo;inscription, chaque utilisateur accepte express&eacute;ment les pr&eacute;sentes CGU en cochant la case pr&eacute;c&eacute;dant le texte suivant : &laquo; Je reconnais avoir lu et compris les CGU et je les accepte &raquo;.</p>
			<p justify="">En cas de non-acceptation des CGU stipul&eacute;es dans le pr&eacute;sent contrat, l\'Utilisateur se doit de renoncer &agrave; l\'acc&egrave;s des services propos&eacute;s par le site.</p>
			<p justify="">www.site.com se r&eacute;serve le droit de modifier unilat&eacute;ralement et &agrave; tout moment le contenu des pr&eacute;sentes CGU.</p>
			<h2>Article 1 : Les mentions l&eacute;gales</h2>
			<p justify="">L&rsquo;&eacute;dition et la direction de la publication du site www.site.com est assur&eacute;e par John Doe, domicili&eacute; 1 rue de Paris - 75016 PARIS.</p>
			<p justify="">Num&eacute;ro de t&eacute;l&eacute;phone est 0102030405</p>
			<p justify="">Adresse e-mail john.doe@zwiicms.fr.</p>
			<p justify="">L\'h&eacute;bergeur du site www.site.com est la soci&eacute;t&eacute; Nom de l\'h&eacute;bergeur, dont le si&egrave;ge social est situ&eacute; au 12 rue de Lyon - 69001 Lyon, avec le num&eacute;ro de t&eacute;l&eacute;phone : 0401020305.</p>
			<h2>ARTICLE 2&nbsp;: Acc&egrave;s au site</h2>
			<p justify="">Le site www.site.com permet &agrave; l\'Utilisateur un acc&egrave;s gratuit aux services suivants :</p>
			<p justify="">Le site internet propose les services suivants :</p>
			<p justify="">Publication</p>
			<p justify="">Le site est accessible gratuitement en tout lieu &agrave; tout Utilisateur ayant un acc&egrave;s &agrave; Internet. Tous les frais support&eacute;s par l\'Utilisateur pour acc&eacute;der au service (mat&eacute;riel informatique, logiciels, connexion Internet, etc.) sont &agrave; sa charge.</p>
			<p justify="">L&rsquo;Utilisateur non membre n\'a pas acc&egrave;s aux services r&eacute;serv&eacute;s. Pour cela, il doit s&rsquo;inscrire en remplissant le formulaire. En acceptant de s&rsquo;inscrire aux services r&eacute;serv&eacute;s, l&rsquo;Utilisateur membre s&rsquo;engage &agrave; fournir des informations sinc&egrave;res et exactes concernant son &eacute;tat civil et ses coordonn&eacute;es, notamment son Adresse électronique.</p>
			<p justify="">Pour acc&eacute;der aux services, l&rsquo;Utilisateur doit ensuite s\'identifier &agrave; l\'aide de son identifiant et de son mot de passe qui lui seront communiqu&eacute;s apr&egrave;s son inscription.</p>
			<p justify="">Tout Utilisateur membre r&eacute;guli&egrave;rement inscrit pourra &eacute;galement solliciter sa d&eacute;sinscription en se rendant &agrave; la page d&eacute;di&eacute;e sur son espace personnel. Celle-ci sera effective dans un d&eacute;lai raisonnable.</p>
			<p justify="">Tout &eacute;v&eacute;nement d&ucirc; &agrave; un cas de force majeure ayant pour cons&eacute;quence un dysfonctionnement du site ou serveur et sous r&eacute;serve de toute interruption ou modification en cas de maintenance, n\'engage pas la responsabilit&eacute; de www.site.com. Dans ces cas, l&rsquo;Utilisateur accepte ainsi ne pas tenir rigueur &agrave; l&rsquo;&eacute;diteur de toute interruption ou suspension de service, m&ecirc;me sans pr&eacute;avis.</p>
			<p justify="">L\'Utilisateur a la possibilit&eacute; de contacter le site par messagerie &eacute;lectronique &agrave; l&rsquo;Adresse électronique de l&rsquo;&eacute;diteur communiqu&eacute; &agrave; l&rsquo;ARTICLE 1.</p>
			<h2>ARTICLE 3 : Collecte des donn&eacute;es</h2>
			<p justify="">Le site est exempt&eacute; de d&eacute;claration &agrave; la Commission Nationale Informatique et Libert&eacute;s (CNIL) dans la mesure o&ugrave; il ne collecte aucune donn&eacute;e concernant les Utilisateurs.</p>
			<h2>ARTICLE 4&nbsp;: Propri&eacute;t&eacute; intellectuelle</h2>
			<p>Les marques, logos, signes ainsi que tous les contenus du site (textes, images, son&hellip;) font l\'objet d\'une protection par le Code de la propri&eacute;t&eacute; intellectuelle et plus particuli&egrave;rement par le droit d\'auteur.</p>
			<p>L\'Utilisateur doit solliciter l\'autorisation pr&eacute;alable du site pour toute reproduction, publication, copie des diff&eacute;rents contenus. Il s\'engage &agrave; une utilisation des contenus du site dans un cadre strictement priv&eacute;, toute utilisation &agrave; des fins commerciales et publicitaires est strictement interdite.</p>
			<p>Toute repr&eacute;sentation totale ou partielle de ce site par quelque proc&eacute;d&eacute; que ce soit, sans l&rsquo;autorisation expresse de l&rsquo;exploitant du site Internet constituerait une contrefa&ccedil;on sanctionn&eacute;e par l&rsquo;article L 335-2 et suivants du Code de la propri&eacute;t&eacute; intellectuelle.</p>
			<p>Il est rappel&eacute; conform&eacute;ment &agrave; l&rsquo;article L122-5 du Code de propri&eacute;t&eacute; intellectuelle que l&rsquo;Utilisateur qui reproduit, copie ou publie le contenu prot&eacute;g&eacute; doit citer l&rsquo;auteur et sa source.</p>
			<h2>ARTICLE 5&nbsp;: Responsabilit&eacute;</h2>
			<p justify="">Les sources des informations diffus&eacute;es sur le site www.site.com sont r&eacute;put&eacute;es fiables mais le site ne garantit pas qu&rsquo;il soit exempt de d&eacute;fauts, d&rsquo;erreurs ou d&rsquo;omissions.</p>
			<p justify="">Les informations communiqu&eacute;es sont pr&eacute;sent&eacute;es &agrave; titre indicatif et g&eacute;n&eacute;ral sans valeur contractuelle. Malgr&eacute; des mises &agrave; jour r&eacute;guli&egrave;res, le site www.site.com&nbsp;ne peut &ecirc;tre tenu responsable de la modification des dispositions administratives et juridiques survenant apr&egrave;s la publication. De m&ecirc;me, le site ne peut &ecirc;tre tenue responsable de l&rsquo;utilisation et de l&rsquo;interpr&eacute;tation de l&rsquo;information contenue dans ce site.</p>
			<p justify="">L\'Utilisateur s\'assure de garder son mot de passe secret. Toute divulgation du mot de passe, quelle que soit sa forme, est interdite. Il assume les risques li&eacute;s &agrave; l\'utilisation de son identifiant et mot de passe. Le site d&eacute;cline toute responsabilit&eacute;.</p>
			<p justify="">Le site www.site.com&nbsp;ne peut &ecirc;tre tenu pour responsable d&rsquo;&eacute;ventuels virus qui pourraient infecter l&rsquo;ordinateur ou tout mat&eacute;riel informatique de l&rsquo;Internaute, suite &agrave; une utilisation, &agrave; l&rsquo;acc&egrave;s, ou au t&eacute;l&eacute;chargement provenant de ce site.</p>
			<p justify="">La responsabilit&eacute; du site ne peut &ecirc;tre engag&eacute;e en cas de force majeure ou du fait impr&eacute;visible et insurmontable d\'un tiers.</p>
			<h2>ARTICLE 6&nbsp;: Liens hypertextes</h2>
			<p justify="">Des liens hypertextes peuvent &ecirc;tre pr&eacute;sents sur le site. L&rsquo;Utilisateur est inform&eacute; qu&rsquo;en cliquant sur ces liens, il sortira du site www.site.com. Ce dernier n&rsquo;a pas de contr&ocirc;le sur les pages web sur lesquelles aboutissent ces liens et ne saurait, en aucun cas, &ecirc;tre responsable de leur contenu.</p>
			<h2>ARTICLE 7 : Cookies</h2>
			<p justify="">L&rsquo;Utilisateur est inform&eacute; que lors de ses visites sur le site, un cookie peut s&rsquo;installer automatiquement sur son logiciel de navigation.</p>
			<p justify="">Les cookies sont de petits fichiers stock&eacute;s temporairement sur le disque dur de l&rsquo;ordinateur de l&rsquo;Utilisateur par votre navigateur et qui sont n&eacute;cessaires &agrave; l&rsquo;utilisation du site www.site.com. Les cookies ne contiennent pas d&rsquo;information personnelle et ne peuvent pas &ecirc;tre utilis&eacute;s pour identifier quelqu&rsquo;un. Un cookie contient un identifiant unique, g&eacute;n&eacute;r&eacute; al&eacute;atoirement et donc anonyme. Certains cookies expirent &agrave; la fin de la visite de l&rsquo;Utilisateur, d&rsquo;autres restent.</p>
			<p justify="">L&rsquo;information contenue dans les cookies est utilis&eacute;e pour am&eacute;liorer le site www.site.com.</p>
			<p justify="">En naviguant sur le site, L&rsquo;Utilisateur les accepte.</p>
			<p justify="">L&rsquo;Utilisateur doit toutefois donner son consentement quant &agrave; l&rsquo;utilisation de certains cookies.</p>
			<p justify="">A d&eacute;faut d&rsquo;acceptation, l&rsquo;Utilisateur est inform&eacute; que certaines fonctionnalit&eacute;s ou pages risquent de lui &ecirc;tre refus&eacute;es.</p>
			<p justify="">L&rsquo;Utilisateur pourra d&eacute;sactiver ces cookies par l&rsquo;interm&eacute;diaire des param&egrave;tres figurant au sein de son logiciel de navigation.</p>
			<h2>ARTICLE 8&nbsp;: Droit applicable et juridiction comp&eacute;tente</h2>
			<p justify="">La l&eacute;gislation fran&ccedil;aise s\'applique au pr&eacute;sent contrat. En cas d\'absence de r&eacute;solution amiable d\'un litige n&eacute; entre les parties, les tribunaux fran&ccedil;ais seront seuls comp&eacute;tents pour en conna&icirc;tre.</p>
			<p justify="">Pour toute question relative &agrave; l&rsquo;application des pr&eacute;sentes CGU, vous pouvez joindre l&rsquo;&eacute;diteur aux coordonn&eacute;es inscrites &agrave; l&rsquo;ARTICLE 1.</p>'
		],
		'erreur302' => [
			'content' => '<p>Notre site est actuellement en maintenance. Nous sommes d&eacute;sol&eacute;s pour la g&ecirc;ne occasionn&eacute;e et faisons notre possible pour &ecirc;tre rapidement de retour.</p>
			<div class="row"><div class="col4 offset8 textAlignCenter"><a href="./?user/login" id="maintenanceLogin" name="maintenanceLogin" class="button"><span class="zwiico-lock zwiico-margin-right"></span>Administration</a></div></div>'
		],
		'erreur403' => [
			'content' => '<h2 style="text-align: center;">Vous n\'êtes pas autorisé à accéder à cette page...</h2><p style="text-align: center;">Personnalisez cette page à votre convenance sans qu\'elle apparaisse dans les menus.<p>'
		],
		'erreur404' => [
			'content' => '<h2 style="text-align: center;">Oups ! La page demandée est introuvable...</h2><p style="text-align: center;">Personnalisez cette page à votre convenance sans qu\'elle apparaisse dans les menus.<p>'
		],
		'recherche' => [
			'content' => '<h1>Rechercher dans le site</h1>'
		]
	];
}
