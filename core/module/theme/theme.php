<?php

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 * @copyright  :  Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 */

class theme extends common {

	public static $actions = [
		'advanced' => self::GROUP_ADMIN,
		'body' => self::GROUP_ADMIN,
		'footer' => self::GROUP_ADMIN,
		'header' => self::GROUP_ADMIN,
		'index' => self::GROUP_ADMIN,
		'menu' => self::GROUP_ADMIN,
		'reset' => self::GROUP_ADMIN,
		'resetAdmin' => self::GROUP_ADMIN,
		'site' => self::GROUP_ADMIN,
		'admin' => self::GROUP_ADMIN,
		'manage' => self::GROUP_ADMIN,
		'export' => self::GROUP_ADMIN,
		'save' => self::GROUP_ADMIN
	];
	public static $aligns = [
		'left' => 'À gauche',
		'center' => 'Au centre',
		'right' => 'À droite'
	];
	public static $attachments = [
		'scroll' => 'Standard',
		'fixed' => 'Fixe'
	];
	public static $fonts = [
		'Abril+Fatface' => 'Abril Fatface',
		'Arimo' => 'Arimo',
		'Arvo' => 'Arvo',
		'Berkshire+Swash' => 'Berkshire Swash',
		'Cabin' => 'Cabin',
		'Dancing+Script' => 'Dancing Script',
		'Droid+Sans' => 'Droid Sans',
		'Droid+Serif' => 'Droid Serif',
		'Fira+Sans' => 'Fira Sans',
		'Inconsolata' => 'Inconsolata',
		'Indie+Flower' => 'Indie Flower',
		'Josefin+Slab' => 'Josefin Slab',
		'Lobster' => 'Lobster',
		'Lora' => 'Lora',
		'Lato' => 'Lato',
		'Marvel' => 'Marvel',
		'Old+Standard+TT' => 'Old Standard TT',
		'Open+Sans' => 'Open Sans',
		'Oswald' => 'Oswald',
		'PT+Mono' => 'PT Mono',
		'PT+Serif' => 'PT Serif',
		'Raleway' => 'Raleway',
		'Rancho' => 'Rancho',
		'Roboto' => 'Roboto',
		'Signika' => 'Signika',
		'Ubuntu' => 'Ubuntu',
		'Vollkorn' => 'Vollkorn'
	];
	public static $footerblocks = [
		1 => [
			'hide' => 'Masqué',
			'center' => 'Affiché' ],
		2 => [
			'hide' => 'Masqué',
			'left' => 'À gauche',
			'right' => 'À droite' ],
		3 => [
			'hide' => 'Masqué',
			'left' => 'À gauche',
			'center' => 'Au centre',
			'right' => 'À droite' ],
		4 => [
			'hide' => 'Masqué',
			'left' => 'En haut',
			'center' => 'Au milieu',
			'right' => 'En bas' ]
	];

	public static $fontWeights = [
		'normal' => 'Maigre',
		'bold' => 'Gras'
	];
	public static $footerHeights = [
		'0px' => 'Nulles (0px)',
		'5px' => 'Très petites (5px)',
		'10px' => 'Petites (10px)',
		'15px' => 'Moyennes (15px)',
		'20px' => 'Grandes (20px)'
	];
	public static $footerPositions = [
		'hide' => 'Caché',
		'site' => 'Dans le site',
		'body' => 'En dessous du site'
	];
	public static $footerFontSizes = [
		'.8em' => 'Très petite (80%)',
		'.9em' => 'Petite (90%)',
		'1em' => 'Standard (100%)',
		'1.1em' => 'Moyenne (110%)',
		'1.2em' => 'Grande (120%)',
		'1.3em' => 'Très grande (130%)'
	];
	public static $headerFontSizes = [
		'1.6em' => 'Très petite (160%)',
		'1.8em' => 'Petite (180%)',
		'2em' => 'Moyenne (200%)',
		'2.2em' => 'Grande (220%)',
		'2.4vmax' => 'Très grande (240%)'
	];
	public static $headerHeights = [
		'none' => 'Hauteur de l\'image sélectionnée',
		'100px' => 'Très petite (100px) ',
		'150px' => 'Petite (150px)',
		'200px' => 'Moyenne (200px)',
		'300px' => 'Grande (300px)',
		'400px' => 'Très grande (400px)'
	];
	public static $headerPositions = [
		'body' => 'Au dessus du site',
		'site' => 'Dans le site',
		'hide' => 'Cachée'
	];
	public static $imagePositions = [
		'top left' => 'En haut à gauche',
		'top center' => 'En haut au centre',
		'top right' => 'En haut à droite',
		'center left' => 'Au milieu à gauche',
		'center center' => 'Au milieu au centre',
		'center right' => 'Au milieu à droite',
		'bottom left' => 'En bas à gauche',
		'bottom center' => 'En bas au centre',
		'bottom right' => 'En bas à droite'
	];
	public static $menuFontSizes = [
		'.8em' => 'Très petite (80%)',
		'.9em' => 'Petite (90%)',
		'1em' => 'Standard (100%)',
		'1.1em' => 'Moyenne (110%)',
		'1.2em' => 'Grande (120%)',
		'1.3em' => 'Très grande (130%)'
	];
	public static $menuHeights = [
		'5px 10px' => 'Très petite',
		'10px' => 'Petite',
		'15px 10px' => 'Moyenne',
		'20px 15px' => 'Grande',
		'25px 15px' => 'Très grande'
	];
	public static $menuPositionsSite = [
		'top' => 'En-dehors du site',
		'site-first' => 'Avant la bannière',
		'site-second' => 'Après la bannière',
		'hide' => 'Caché'
	];
	public static $menuPositionsBody = [
		'top' => 'En-dehors du site',
		'body-first' => 'Avant la bannière',
		'body-second' => 'Après la bannière',
		'site' => 'Dans le site',
		'hide' => 'Caché'
	];
	public static $menuRadius = [
		'0px' => 'Aucun',
		'3px 3px 0px 0px' => 'Très léger',
		'6px 6px 0px 0px' => 'Léger',
		'9px 9px 0px 0px' => 'Moyen',
		'12px 12px 0px 0px' => 'Important',
		'15px 15px 0px 0px' => 'Très important'
	];
	public static $radius = [
		'0px' => 'Aucun',
		'5px' => 'Très léger',
		'10px' => 'Léger',
		'15px' => 'Moyen',
		'25px' => 'Important',
		'50px' => 'Très important'
	];
	public static $repeats = [
		'no-repeat' => 'Ne pas répéter',
		'repeat-x' => 'Sur l\'axe horizontal',
		'repeat-y' => 'Sur l\'axe vertical',
		'repeat' => 'Sur les deux axes'
	];
	public static $shadows = [
		'0px' => 'Aucune',
		'1px 1px 5px' => 'Très légère',
		'1px 1px 10px' => 'Légère',
		'1px 1px 15px' => 'Moyenne',
		'1px 1px 25px' => 'Importante',
		'1px 1px 50px' => 'Très importante'
	];
	public static $siteFontSizes = [
		'12px' => '12 pixels',
		'13px' => '13 pixels',
		'14px' => '14 pixels',
		'15px' => '15 pixels',
		'16px' => '16 pixels'
	];
	public static $bodySizes = [
		'auto' => 'Automatique',
		'100% 100%' => 'Image étirée (100% 100%)',
		'cover' => 'Responsive (cover)',
		'contain' => 'Responsive (contain)'
	];
	public static $textTransforms = [
		'none' => 'Standard',
		'lowercase' => 'Minuscules',
		'uppercase' => 'Majuscules',
		'capitalize' => 'Majuscule à chaque mot'
	];
	public static $widths = [
		'750px' => 'Petite (750 pixels)',
		'960px' => 'Moyenne (960 pixels)',
		'1170px' => 'Grande (1170 pixels)',
		'100%' => 'Fluide (100%)'
	];
	public static $headerWide = [
		'auto auto' => 'Automatique',
		'100% 100%' => 'Image étirée (100% 100%)',
		'cover' => 'Responsive (cover)',
		'contain' => 'Responsive (contain)'
	];
	public static $footerTemplate = [
		'1' => 'Une seule colonne',
		'2' => 'Deux colonnes : 1/2 - 1/2',
		'3' => 'Trois colonnes : 1/3 - 1/3 - 1/3',
		'4' => 'Trois lignes superposées'
	];

	/**
	 * Thème des écrans d'administration
	 */
	public function admin() {
		// Soumission du formulaire
		if($this->isPost()) {
			$this->setData(['admin', [
				'backgroundColor' 	=> $this->getInput('adminBackgroundColor'),
				'colorTitle' 		=> $this->getInput('adminColorTitle'),
				'colorText'			=> $this->getInput('adminColorText'),
				'colorButtonText' 	=> $this->getInput('adminColorButtonText'),
				'backgroundColorButton' 	=> $this->getInput('adminColorButton'),
				'backgroundColorButtonGrey'	=> $this->getInput('adminColorGrey'),
				'backgroundColorButtonRed'	=> $this->getInput('adminColorRed'),
				'backgroundColorButtonGreen'=> $this->getInput('adminColorGreen'),
				'fontText' 		=> $this->getInput('adminFontText'),
				'fontSize' 	=> $this->getInput('adminFontTextSize'),
				'fontTitle' => $this->getInput('adminFontTitle'),
				'backgroundBlockColor' => $this->getInput('adminBackGroundBlockColor'),
				'borderBlockColor' => $this->getInput('adminBorderBlockColor'),
			]]);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'Modifications enregistrées',
				'redirect' => helper::baseUrl() . 'theme/admin',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Administration',
			'view' => 'admin',
			'vendor' => [
				'tinycolorpicker'
			],
		]);
	}

	/**
	 * Mode avancé
	 */
	public function advanced() {
		// Soumission du formulaire
		if($this->isPost()) {
			// Enregistre le CSS
			file_put_contents(self::DATA_DIR.'custom.css', $this->getInput('themeAdvancedCss', null));
			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'Modifications enregistrées',
				'redirect' => helper::baseUrl() . 'theme/advanced',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Éditeur CSS',
			'vendor' => [
				'codemirror'
			],
			'view' => 'advanced'
		]);
	}

	/**
	 * Options de l'arrière plan
	 */
	public function body() {
		// Soumission du formulaire
		if($this->isPost()) {
			$this->setData(['theme', 'body', [
				'backgroundColor' => $this->getInput('themeBodyBackgroundColor'),
				'image' => $this->getInput('themeBodyImage'),
				'imageAttachment' => $this->getInput('themeBodyImageAttachment'),
				'imagePosition' => $this->getInput('themeBodyImagePosition'),
				'imageRepeat' => $this->getInput('themeBodyImageRepeat'),
				'imageSize' => $this->getInput('themeBodyImageSize'),
				'toTopbackgroundColor' => $this->getInput('themeBodyToTopBackground'),
				'toTopColor' => $this->getInput('themeBodyToTopColor')
			]]);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'Modifications enregistrées',
				'redirect' => helper::baseUrl() . 'theme',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Personnalisation de l\'arrière plan',
			'vendor' => [
				'tinycolorpicker'
			],
			'view' => 'body'
		]);
	}

	/**
	 * Options du pied de page
	 */
	public function footer() {
		// Soumission du formulaire
		if($this->isPost()) {
			if ( $this->getInput('themeFooterCopyrightPosition') === 'hide' &&
				 $this->getInput('themeFooterSocialsPosition') === 'hide' &&
				 $this->getInput('themeFooterTextPosition') === 'hide' 	) {
				// Valeurs en sortie
				$this->addOutput([
					'notification' => 'Sélectionnez au moins un contenu à afficher',
					'redirect' => helper::baseUrl() . 'theme/footer',
					'state' => false
				]);
			} else {
				$this->setData(['theme', 'footer', [
					'backgroundColor' => $this->getInput('themeFooterBackgroundColor'),
					'copyrightAlign' => $this->getInput('themeFooterCopyrightAlign'),
					'height' => $this->getInput('themeFooterHeight'),
					'loginLink' => $this->getInput('themeFooterLoginLink'),
					'margin' => $this->getInput('themeFooterMargin', helper::FILTER_BOOLEAN),
					'position' => $this->getInput('themeFooterPosition'),
					'fixed' => $this->getInput('themeFooterFixed', helper::FILTER_BOOLEAN),
					'socialsAlign' => $this->getInput('themeFooterSocialsAlign'),
					'text' => $this->getInput('themeFooterText', null),
					'textAlign' => $this->getInput('themeFooterTextAlign'),
					'textColor' => $this->getInput('themeFooterTextColor'),
					'copyrightPosition' => $this->getInput('themeFooterCopyrightPosition'),
					'textPosition' => $this->getInput('themeFooterTextPosition'),
					'socialsPosition' => $this->getInput('themeFooterSocialsPosition'),
					'textTransform' => $this->getInput('themeFooterTextTransform'),
					'font' => $this->getInput('themeFooterFont'),
					'fontSize' => $this->getInput('themeFooterFontSize'),
					'fontWeight' => $this->getInput('themeFooterFontWeight'),
					'displayVersion' => $this->getInput('themefooterDisplayVersion', helper::FILTER_BOOLEAN),
					'displaySiteMap' => $this->getInput('themefooterDisplaySiteMap', helper::FILTER_BOOLEAN),
					'displayCopyright' => $this->getInput('themefooterDisplayCopyright', helper::FILTER_BOOLEAN),
					'displayLegal' =>  $this->getInput('themeFooterDisplayLegal', helper::FILTER_BOOLEAN),
					'displaySearch' =>  $this->getInput('themeFooterDisplaySearch', helper::FILTER_BOOLEAN),
					'displayMemberBar'=> $this->getInput('themeFooterDisplayMemberBar', helper::FILTER_BOOLEAN),
					'template' => $this->getInput('themeFooterTemplate')
				]]);
				// Valeurs en sortie
				$this->addOutput([
					'notification' => 'Modifications enregistrées',
					'redirect' => helper::baseUrl() . 'theme',
					'state' => true
				]);
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Personnalisation du pied de page',
			'vendor' => [
				'tinycolorpicker',
				'tinymce'
			],
			'view' => 'footer'
		]);
	}

	/**
	 * Options de la bannière
	 */
	public function header() {
		// Soumission du formulaire
		if($this->isPost()) {
			// Si une image est positionnée, l'arrière en transparent.
			$this->setData(['theme', 'header', [
				'backgroundColor' => $this->getInput('themeHeaderBackgroundColor'),
				'textTransform' => $this->getInput('themeHeaderTextTransform'),
				'font' => $this->getInput('themeHeaderFont'),
				'fontSize' => $this->getInput('themeHeaderFontSize'),
				'fontWeight' => $this->getInput('themeHeaderFontWeight'),
				'height' => $this->getInput('themeHeaderHeight'),
				'image' => $this->getInput('themeHeaderImage'),
				'imagePosition' => $this->getInput('themeHeaderImagePosition'),
				'imageRepeat' => $this->getInput('themeHeaderImageRepeat'),
				'margin' => $this->getInput('themeHeaderMargin', helper::FILTER_BOOLEAN),
				'position' => $this->getInput('themeHeaderPosition'),
				'textAlign' => $this->getInput('themeHeaderTextAlign'),
				'textColor' => $this->getInput('themeHeaderTextColor'),
				'textHide' => $this->getInput('themeHeaderTextHide', helper::FILTER_BOOLEAN),
				'linkHomePage' => $this->getInput('themeHeaderlinkHomePage',helper::FILTER_BOOLEAN),
				'imageContainer' => $this->getInput('themeHeaderImageContainer')
			]]);
			// Modification de la position du menu selon la position de la bannière
			switch ($this->getInput('themeHeaderPosition') &&
					 $this->getData(['theme','menu','position']) !== 'site' &&
					 $this->getData(['theme','menu','position']) !== 'top' ) {
				case 'site' :
					$position = str_replace ('body','site',$this->getData(['theme','menu','position']));
				break;
				case 'body' :
					$position = str_replace ('site','body',$this->getData(['theme','menu','position']));
				break;
				default:
					$position = $this->getData(['theme','menu','position']);
			}
			$this->setData(['theme', 'menu', [
				'backgroundColor' => $this->getData(['theme', 'menu', 'backgroundColor']),
				'font' => $this->getData(['theme', 'menu', 'font']),
				'fontSize' => $this->getData(['theme', 'menu', 'fontSize']),
				'fontWeight' => $this->getData(['theme', 'menu', 'fontWeight']),
				'height' => $this->getData(['theme', 'menu', 'height']),
				'loginLink' => $this->getData(['theme', 'menu', 'loginLink']),
				'margin' => $this->getData(['theme', 'menu', 'margin']),
				'position' => $position,
				'textAlign' => $this->getData(['theme', 'menu', 'textAlign']),
				'textColor' => $this->getData(['theme', 'menu', 'textColor']),
				'textTransform' => $this->getData(['theme','menu','textTransform']),
				'fixed' => $this->getData(['theme','menu','fixed']),
				'activeColorAuto' => $this->getData(['theme','menu','activeColorAuto']),
				'activeColor' => $this->getData(['theme','menu','activeColor']),
				'radius' => $this->getData(['theme','menu','radius']),
				'burgerTitle' => $this->getData(['theme','menu','burgerTitle'])
			]]);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'Modifications enregistrées',
				'redirect' => helper::baseUrl() . 'theme',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Personnalisation de la bannière',
			'vendor' => [
				'tinycolorpicker'
			],
			'view' => 'header'
		]);
	}

	/**
	 * Accueil de la personnalisation
	 */
	public function index() {
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Personnalisation des thèmes',
			'view' => 'index'
		]);
	}

	/**
	 * Options du menu
	 */
	public function menu() {
		// Soumission du formulaire
		if($this->isPost()) {
			$this->setData(['theme', 'menu', [
				'backgroundColor' => $this->getInput('themeMenuBackgroundColor'),
				'font' => $this->getInput('themeMenuFont'),
				'fontSize' => $this->getInput('themeMenuFontSize'),
				'fontWeight' => $this->getInput('themeMenuFontWeight'),
				'height' => $this->getInput('themeMenuHeight'),
				'loginLink' => $this->getInput('themeMenuLoginLink', helper::FILTER_BOOLEAN),
				'margin' => $this->getInput('themeMenuMargin', helper::FILTER_BOOLEAN),
				'position' => $this->getInput('themeMenuPosition'),
				'textAlign' => $this->getInput('themeMenuTextAlign'),
				'textColor' => $this->getInput('themeMenuTextColor'),
				'textTransform' => $this->getInput('themeMenuTextTransform'),
				'fixed' => $this->getInput('themeMenuFixed', helper::FILTER_BOOLEAN),
				'activeColorAuto' => $this->getInput('themeMenuActiveColorAuto', helper::FILTER_BOOLEAN),
				'activeColor' => $this->getInput('themeMenuActiveColor'),
				'activeTextColor' => $this->getInput('themeMenuActiveTextColor'),
				'radius' => $this->getInput('themeMenuRadius'),
				'burgerTitle' => $this->getInput('themeMenuBurgerTitle', helper::FILTER_BOOLEAN),
				'memberBar' =>  $this->getInput('themeMenuMemberBar', helper::FILTER_BOOLEAN)
			]]);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'Modifications enregistrées',
				'redirect' => helper::baseUrl() . 'theme',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Personnalisation du menu',
			'vendor' => [
				'tinycolorpicker'
			],
			'view' => 'menu'
		]);
	}

	/**
	 * Réinitialisation de la personnalisation avancée
	 */
	public function reset() {
		// Supprime le fichier de personnalisation avancée
		unlink(self::DATA_DIR.'custom.css');
		// Valeurs en sortie
		$this->addOutput([
			'notification' => 'Personnalisation avancée réinitialisée',
			'redirect' => helper::baseUrl() . 'theme/advanced',
			'state' => true
		]);
	}

	/**
	 * Réinitialisation de la personnalisation avancée
	 */
	public function resetAdmin() {
		// Supprime le fichier de personnalisation avancée
		//unlink(self::DATA_DIR.'admin.json');
		$this->initData('admin');
		// Valeurs en sortie
		$this->addOutput([
			'notification' => 'Thème réinitialisé',
			'redirect' => helper::baseUrl() . 'theme/admin',
			'state' => true
		]);
	}

	/**
	 * Options du site
	 */
	public function site() {
		// Soumission du formulaire
		if($this->isPost()) {
			$this->setData(['theme', 'title', [
				'font' => $this->getInput('themeTitleFont'),
				'textColor' => $this->getInput('themeTitleTextColor'),
				'fontWeight' => $this->getInput('themeTitleFontWeight'),
				'textTransform' => $this->getInput('themeTitleTextTransform')
			]]);
			$this->setData(['theme', 'text', [
				'font' => $this->getInput('themeTextFont'),
				'fontSize' => $this->getInput('themeTextFontSize'),
				'textColor' => $this->getInput('themeTextTextColor'),
				'linkColor'=> $this->getInput('themeTextLinkColor')
			]]);
			$this->setData(['theme', 'site', [
				'backgroundColor' => $this->getInput('themeSiteBackgroundColor'),
				'radius' => $this->getInput('themeSiteRadius'),
				'shadow' => $this->getInput('themeSiteShadow'),
				'width' => $this->getInput('themeSiteWidth'),
				'margin' => $this->getInput('themeSiteMargin',helper::FILTER_BOOLEAN)
			]]);
			$this->setData(['theme', 'button', [
				'backgroundColor' => $this->getInput('themeButtonBackgroundColor')
			]]);
			$this->setData(['theme', 'block', [
				'backgroundColor' => $this->getInput('themeBlockBackgroundColor'),
				'borderColor' => $this->getInput('themeBlockBorderColor')
			]]);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'Modifications enregistrées',
				'redirect' => helper::baseUrl() . 'theme',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Personnalisation du site',
			'vendor' => [
				'tinycolorpicker',
				'tinymce'
			],
			'view' => 'site'
		]);
	}

	/**
	 * Import du thème
	 */
	public function manage() {
		if($this->isPost() ) {
			$zipFilename =	$this->getInput('themeManageImport', helper::FILTER_STRING_SHORT, true);
			$tempFolder = uniqid();
			$zip = new ZipArchive();
			if ($zip->open(self::FILE_DIR.'source/'.$zipFilename) === TRUE) {
				mkdir (self::TEMP_DIR . $tempFolder);
				$zip->extractTo(self::TEMP_DIR . $tempFolder );
				// Archive de thème ?
				if (( file_exists(self::TEMP_DIR . $tempFolder . '/site/data/custom.css')
					 AND file_exists(self::TEMP_DIR . $tempFolder . '/site/data/theme.css')
					 AND file_exists(self::TEMP_DIR . $tempFolder . '/site/data/theme.json')
					) OR (
						file_exists(self::TEMP_DIR . $tempFolder . '/site/data/admin.json')
					)
				) {
					// traiter l'archive
					$success = $zip->extractTo('.');
					// traitement de l'erreur
					$notification = $success ? 'Le thème a été importé' : 'Erreur lors de l\'extraction, vérifiez les permissions.';
					// Supprimmer le dossier temporaire
					$this->removeDir(self::TEMP_DIR . $tempFolder);
				} else {
					// pas une archive de thème
					$success = false;
					$notification = 'Ce n\'est pas l\'archive d\'un thème !';
				}
				$zip->close();
			} else {
				// erreur à l'ouverture
				$success = false;
				$notification = 'Impossible d\'ouvrir l\'archive';
			}
			// Valeurs en sortie
			$this->addOutput([
				'notification' => $notification,
				'state' => $success,
				'title' => 'Gestion des thèmes',
				'view' => 'manage'
			]);;
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Gestion des thèmes',
			'view' => 'manage'
		]);
	}



	/**
	 * Export du thème
	 */
	public function export() {
		// Make zip
			$zipFilename = $this->makezip($this->getUrl(2));
			// Téléchargement du ZIP
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Transfer-Encoding: binary');
			header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
			header('Content-Length: ' . filesize(self::TEMP_DIR . $zipFilename));
			readfile(self::TEMP_DIR . $zipFilename);
			// Nettoyage du dossier
			unlink (self::TEMP_DIR . $zipFilename);
			exit();
	}

	/**
	 * Export du thème
	 */
	public function save() {
		// Make zip
		$zipFilename = $this->makezip($this->getUrl(2));
		// Téléchargement du ZIP
		if (!is_dir(self::FILE_DIR.'source/theme')) {
			mkdir(self::FILE_DIR.'source/theme');
		}
		copy (self::TEMP_DIR . $zipFilename , self::FILE_DIR.'source/theme/' . $zipFilename);
		// Nettoyage du dossier
		unlink (self::TEMP_DIR . $zipFilename);
		// Valeurs en sortie
		$this->addOutput([
			'notification' => 'Archive <b>'.$zipFilename.'</b> sauvegardée avec succès',
			'redirect' => helper::baseUrl() . 'theme/manage',
			'state' => true
		]);
	}

	/**
	 * construction du zip
	 * @param string $modele theme ou admin
	 */
	private function makezip($modele) {
		// Creation du dossier
		$zipFilename  =  $modele . ' ' .date('d m Y').'  '.date('H i s ').'.zip';
		$zip = new ZipArchive();
		if ($zip->open(self::TEMP_DIR . $zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE ) === TRUE) {
			switch ($modele) {
				case 'admin':
					$zip->addFile(self::DATA_DIR.'admin.json',self::DATA_DIR.'admin.json');
					$zip->addFile(self::DATA_DIR.'admin.css',self::DATA_DIR.'admin.css');
					break;
				case 'theme':
					$zip->addFile(self::DATA_DIR.'theme.json',self::DATA_DIR.'theme.json');
					$zip->addFile(self::DATA_DIR.'theme.css',self::DATA_DIR.'theme.css');
					$zip->addFile(self::DATA_DIR.'custom.css',self::DATA_DIR.'custom.css');
					if ($this->getData(['theme','body','image']) !== '' ) {
					$zip->addFile(self::FILE_DIR.'source/'.$this->getData(['theme','body','image']),
								self::FILE_DIR.'source/'.$this->getData(['theme','body','image'])
								);
					}
					if ($this->getData(['theme','header','image']) !== '' ) {
					$zip->addFile(self::FILE_DIR.'source/'.$this->getData(['theme','header','image']),
								  self::FILE_DIR.'source/'.$this->getData(['theme','header','image'])
								);
					}
					break;
			}
			$ret = $zip->close();
		}
		return ($zipFilename);
	}

}
