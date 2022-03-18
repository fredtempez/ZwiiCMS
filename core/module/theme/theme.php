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
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
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
		'site' => self::GROUP_ADMIN,
		'admin' => self::GROUP_ADMIN,
		'manage' => self::GROUP_ADMIN,
		'export' => self::GROUP_ADMIN,
		'import' => self::GROUP_ADMIN,
		'save' => self::GROUP_ADMIN,
		'fonts' => self::GROUP_ADMIN,
		'fontAdd' => self::GROUP_ADMIN,
		'fontEdit' => self::GROUP_ADMIN,
		'fontDelete' => self::GROUP_ADMIN
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
	public static $containerWides = [
		'container' => 'Limitée au site',
		'none' => 'Etendue sur la page'
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
		'unset' => 'Libre', // texte dynamique cf header.js.php
		'100px' => 'Très petite (100px) ',
		'150px' => 'Petite (150px)',
		'200px' => 'Moyenne (200px)',
		'300px' => 'Grande (300px)',
		'400px' => 'Très grande (400px)',
	];
	public static $headerPositions = [
		'body' => 'Au dessus du site',
		'site' => 'Dans le site',
		'hide' => 'Cachée'
	];
	public static $headerFeatures = [
		'wallpaper' => 'Couleur unie ou papier-peint',
		'feature'   => 'Contenu personnalisé'
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
	public static $siteWidths = [
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
	public static $burgerContent = [
		'none' => 'Aucun',
		'title' => 'Titre du site',
		'logo' => 'Logo du site'
	];


	// Variable pour construire la liste des pages du site
	public static $pagesList = [];
	// Variable pour construire la liste des fontes installées
	public static $fontsList = [];
	// Variable pour détailler les fontes installées
	public static $fontsDetail = [];

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
				'backgroundColorButtonHelp'=> $this->getInput('adminColorHelp'),
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
					'displayCookie' => $this->getInput('themefooterDisplayCookie', helper::FILTER_BOOLEAN),
					'displayLegal' =>  $this->getInput('themeFooterDisplayLegal', helper::FILTER_BOOLEAN),
					'displaySearch' =>  $this->getInput('themeFooterDisplaySearch', helper::FILTER_BOOLEAN),
					'displayMemberBar'=> $this->getInput('themeFooterDisplayMemberBar', helper::FILTER_BOOLEAN),
					'template' => $this->getInput('themeFooterTemplate')
				]]);

				// Sauvegarder la configuration localisée
				$this->setData(['locale','legalPageId', $this->getInput('configLegalPageId')]);
				$this->setData(['locale','searchPageId', $this->getInput('configSearchPageId')]);

				// Valeurs en sortie
				$this->addOutput([
					'notification' => 'Modifications enregistrées',
					'redirect' => helper::baseUrl() . 'theme',
					'state' => true
				]);
			}
		}

		// Liste des pages
		self::$pagesList = $this->getData(['page']);
		foreach(self::$pagesList as $page => $pageId) {
			if ($this->getData(['page',$page,'block']) === 'bar' ||
				$this->getData(['page',$page,'disable']) === true) {
				unset(self::$pagesList[$page]);
			}
		}
		// Lire les fontes installées
		$this->enumFonts();
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
			// Modification des URL des images dans la bannière perso
			$featureContent = $this->getInput('themeHeaderText', null);
			$featureContent = str_replace(helper::baseUrl(false,false), './', $featureContent);

			/**
			* Stocker les images incluses dans la bannière perso dans un tableau
			*/
			preg_match_all('/<img[^>]+>/i',$featureContent, $results);
			foreach($results[0] as $value) {
				// Lire le contenu XML
				$sx = simplexml_load_string($value);
				// Élément à remplacer
				$files [] = str_replace('./site/file/source/','',(string) $sx[0]['src']);
			}

			// Sauvegarder
			$this->setData(['theme', 'header', [
				'backgroundColor' => $this->getInput('themeHeaderBackgroundColor'),
				'font' => $this->getInput('themeHeaderFont'),
				'fontSize' => $this->getInput('themeHeaderFontSize'),
				'fontWeight' => $this->getInput('themeHeaderFontWeight'),
				'height' => $this->getInput('themeHeaderHeight'),
				'wide' => $this->getInput('themeHeaderWide'),
				'image' => $this->getInput('themeHeaderImage'),
				'imagePosition' => $this->getInput('themeHeaderImagePosition'),
				'imageRepeat' => $this->getInput('themeHeaderImageRepeat'),
				'margin' => $this->getInput('themeHeaderMargin', helper::FILTER_BOOLEAN),
				'position' => $this->getInput('themeHeaderPosition'),
				'textAlign' => $this->getInput('themeHeaderTextAlign'),
				'textColor' => $this->getInput('themeHeaderTextColor'),
				'textHide' => $this->getInput('themeHeaderTextHide', helper::FILTER_BOOLEAN),
				'textTransform' => $this->getInput('themeHeaderTextTransform'),
				'linkHomePage' => $this->getInput('themeHeaderlinkHomePage',helper::FILTER_BOOLEAN),
				'imageContainer' => $this->getInput('themeHeaderImageContainer'),
				'tinyHidden' => $this->getInput('themeHeaderTinyHidden', helper::FILTER_BOOLEAN),
				'feature' => $this->getInput('themeHeaderFeature'),
				'featureContent' => $featureContent,
				'featureFiles' => 	$files
			]]);
			// Modification de la position du menu selon la position de la bannière
			if  ( $this->getData(['theme','header','position']) == 'site'  )
				{
					$this->setData(['theme', 'menu', 'position',str_replace ('body-','site-',$this->getData(['theme','menu','position']))]);
			}
			if  ( $this->getData(['theme','header','position']) == 'body')
				{
					$this->setData(['theme', 'menu', 'position',str_replace ('site-','body-',$this->getData(['theme','menu','position']))]);
			}
			// Menu accroché à la bannière qui devient cachée
			if  ( $this->getData(['theme','header','position']) == 'hide' &&
				  in_array( $this->getData(['theme','menu','position']) , ['body-first', 'site-first', 'body-first' , 'site-second'])
				 ) {
					$this->setData(['theme', 'menu', 'position','site']);
			}
			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'Modifications enregistrées',
				'redirect' => helper::baseUrl() . 'theme',
				'state' => true
			]);
		}
		// Lire les fontes installées
		$this->enumFonts();
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Personnalisation de la bannière',
			'vendor' => [
				'tinycolorpicker',
				'tinymce'
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
				'backgroundColorSub' => $this->getInput('themeMenuBackgroundColorSub'),
				'font' => $this->getInput('themeMenuFont'),
				'fontSize' => $this->getInput('themeMenuFontSize'),
				'fontWeight' => $this->getInput('themeMenuFontWeight'),
				'height' => $this->getInput('themeMenuHeight'),
				'wide' => $this->getInput('themeMenuWide'),
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
				'memberBar' =>  $this->getInput('themeMenuMemberBar', helper::FILTER_BOOLEAN),
				'burgerLogo' => $this->getInput('themeMenuBurgerLogo'),
				'burgerContent' => $this->getInput('themeMenuBurgerContent')
			]]);
			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'Modifications enregistrées',
				'redirect' => helper::baseUrl() . 'theme',
				'state' => true
			]);
		}
		// Lire les fontes installées
		$this->enumFonts();
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
	 * Options des fontes
	 */
	public function fonts() {

		// Polices liées au thème
		$used = [
			'Bannière' 		=> $this->getData (['theme', 'header', 'font']),
			'Menu' 			=> $this->getData (['theme', 'menu', 'font']),
			'Titre ' 		=> $this->getData (['theme', 'title', 'font']),
			'Texte'   		=> $this->getData (['theme', 'text', 'font']),
			'Pied de page' 	=> $this->getData (['theme', 'footer', 'font']),
			'Titre (admin)' => $this->getData (['admin', 'fontTitle' ]),
			'Admin (texte)' => $this->getData (['admin', 'fontText' ])
		];

		// Récupérer le détail des fontes installées
		$f = $this->getFonts();

		// Parcourir les fontes disponibles et construire le tableau pour le formulaire
		foreach ($f as $type => $typeValue) {

			foreach ($typeValue as $fontId => $fontValue) {
				// Fontes utilisées par les thèmes
				$fontUsed[$fontId] = '';
				foreach ($used as $key => $value) {
					if ( $value === $fontId) {
						$fontUsed[$fontId] .=  $key . '<br/>';
					}
				}
				self::$fontsDetail [] = [
					$fontId,
					'<span style="font-family:' . $f[$type][$fontId]['font-family'] . '">' . $f[$type][$fontId]['name'] . '</span>' ,
					$f[$type][$fontId]['font-family'],
					$fontUsed[$fontId],
					$type,
					$type !== 'websafe' ? 	template::button('themeFontEdit' . $fontId, [
												'class' => 'themeFontEdit',
												'href' => helper::baseUrl() . $this->getUrl(0) . '/fontEdit/' .  $type . '/' . $fontId . '/' . $_SESSION['csrf'],
												'value' => template::ico('pencil'),
												'disabled' => !empty($fontUsed[$fontId])
											])
										: '',
					$type !== 'websafe' ? 	template::button('themeFontDelete' . $fontId, [
												'class' => 'themeFontDelete buttonRed',
												'href' => helper::baseUrl() . $this->getUrl(0) . '/fontDelete/' . $type . '/' . $fontId . '/' . $_SESSION['csrf'],
												'value' => template::ico('cancel'),
												'disabled' => !empty($fontUsed[$fontId])
											])
										: ''
				];
			}
		}
		sort(self::$fontsDetail);
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Gestion des fontes',
			'view' => 'fonts'
		]);
	}

	/**
	 * Ajouter une fonte
	 */
	public function fontAdd() {
		// Soumission du formulaire
		if ($this->isPost()) {
			// Type d'import en ligne ou local
			$type = $this->getInput('fontAddFontImported', helper::FILTER_BOOLEAN) ? 'imported' : 'files';
			$typeFlip = $type === 'files' ? 'imported' : 'files';
			$ressource = $type === 'imported' ? $this->getInput('fontAddUrl', helper::FILTER_STRING_SHORT) : $this->getInput('fontAddFile',  helper::FILTER_STRING_SHORT);
			$fontId = $this->getInput('fontAddFontId',  helper::FILTER_STRING_SHORT, true);
			$fontName = $this->getInput('fontAddFontName',  helper::FILTER_STRING_SHORT, true);
			$fontFamilyName = $this->getInput('fontAddFontFamilyName',  helper::FILTER_STRING_SHORT, true);

			// Supprime la fonte si elle existe dans le type inverse
			if (is_array($this->getData(['fonts', $typeFlip, $fontId])) ) {
				$this->deleteData(['fonts', $typeFlip, $fontId ]);
			}
			// Stocker la fonte
			$this->setData(['fonts',
							$type,
							$fontId, [
								'name' => $fontName,
								'font-family' => $fontFamilyName,
								'resource' => $ressource
			]]);


			// Copier la fonte si le nom du fichier est fourni
			if ( $type === 'files' &&
					file_exists(self::FILE_DIR . 'source/' . $ressource)
			) {
				copy ( self::FILE_DIR . 'source/' . $ressource, self::DATA_DIR . $ressource );
			}

			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'La fonte a été créée',
				'redirect' => helper::baseUrl() . 'theme/fonts',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Ajouter une fonte',
			'view' => 'fontAdd'
		]);
	}


	/**
	 * Ajouter une fonte
	 */
	public function fontEdit() {
		// Soumission du formulaire
		if ($this->isPost()) {
			// Type d'import en ligne ou local
			$type = $this->getInput('fontEditFontImported', helper::FILTER_BOOLEAN) ? 'imported' : 'files';
			$typeFlip = $type === 'files' ? 'imported' : 'files';
			$ressource = $type === 'imported' ? $this->getInput('fontEditUrl', helper::FILTER_STRING_SHORT) : $this->getInput('fontEditFile',  helper::FILTER_STRING_SHORT);
			$fontId = $this->getInput('fontEditFontId',  helper::FILTER_STRING_SHORT, true);
			$fontName = $this->getInput('fontEditFontName',  helper::FILTER_STRING_SHORT, true);
			$fontFamilyName = $this->getInput('fontEditFontFamilyName',  helper::FILTER_STRING_SHORT, true);

			// Supprime la fonte si elle existe dans le type inverse
			if (is_array($this->getData(['fonts', $typeFlip, $fontId])) ) {
				$this->deleteData(['fonts', $typeFlip, $fontId ]);
			}
			// Stocker les fontes
			$this->setData(['fonts',
							$type,
							$fontId, [
								'name' => $fontName,
								'font-family' => $fontFamilyName,
								'resource' => $ressource
			]]);
			// Copier la fonte si le nom du fichier est fourni
			if ( $type === 'files' &&
					file_exists(self::FILE_DIR . 'source/' . $ressource)
			) {
				copy ( self::FILE_DIR . 'source/' . $ressource, self::DATA_DIR . $ressource );
			}

			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'La fonte a été actualisée',
				'redirect' => helper::baseUrl() . 'theme/fonts',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Editer une fonte',
			'view' => 'fontEdit'
		]);
	}

	/**
	 * Effacer une fonte
	 */
	public function fontDelete() {
		// Jeton incorrect
		if ($this->getUrl(4) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'theme/fonts',
				'notification' => 'Action non autorisée'
			]);
		}
		// Suppression
		else {

			// Effacer la fonte de la base
			$this->deleteData(['fonts', $this->getUrl(2), $this->getUrl(3)]);

			// Effacer le fichier existant
			if ( $this->getUrl(2) === 'file' &&
				file_exists(self::DATA_DIR . $this->getUrl(2)) ) {
				unlink(self::DATA_DIR . $this->getUrl(2));
			}

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . 'theme/fonts',
				'notification' => 'La fonte a été supprimée',
				'state' => true
			]);
		}
	}


	/**
	 * Réinitialisation de la personnalisation avancée
	 */
	public function reset() {
		// $url prend l'adresse sans le token
		$url = explode('&',$this->getUrl(2));

		if  ( isset($_GET['csrf'])
			 AND $_GET['csrf'] === $_SESSION['csrf']
			) {
			// Réinitialisation
			$redirect ='';
			switch ($url[0]) {
				case 'admin':
					$this->initData('admin');
					$redirect = helper::baseUrl() . 'theme/admin';
					break;
				case 'manage':
					$this->initData('theme');
					$redirect = helper::baseUrl() . 'theme/manage';
					break;
				case 'custom':
					unlink(self::DATA_DIR.'custom.css');
					$redirect = helper::baseUrl() . 'theme/advanced';
					break;
				default :
					$redirect = helper::baseUrl() . 'theme';
			}

			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'Réinitialisation effectuée',
				'redirect' => $redirect,
				'state' => true
			]);
		} else {
			// Valeurs en sortie
			$this->addOutput([
				'notification' => 'Jeton incorrect'
			]);
		}
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
		// Lire les fontes installées
		$this->enumFonts();
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
			$data = $this->import(self::FILE_DIR.'source/' . $zipFilename);
			if ($data['success']) {
				header("Refresh:0");
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'notification' => $data['notification'],
					'state' => $data['success'],
					'title' => 'Gestion des thèmes',
					'view' => 'manage'
				]);;
			}
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Gestion des thèmes',
			'view' => 'manage'
		]);
	}

	/**
	 * Importe un thème
	 * @param string Url du thème à télécharger
	 * @param @return array contenant $success = true ou false ; $ notification string message à afficher
	 */

	public function import($zipName = '') {

		if ($zipName !== '' &&
			file_exists($zipName)) {
			// Init variables de retour
			$success = false;
			$notification = '';
			// Dossier temporaire
			$tempFolder = uniqid();
			// Ouvrir le zip
			$zip = new ZipArchive();
			if ($zip->open($zipName) === TRUE) {
				mkdir (self::TEMP_DIR . $tempFolder, 0755);
				$zip->extractTo(self::TEMP_DIR . $tempFolder );
				$modele = '';
				// Archive de thème ?
				if (
					file_exists(self::TEMP_DIR . $tempFolder . '/site/data/custom.css')
					AND file_exists(self::TEMP_DIR . $tempFolder . '/site/data/theme.css')
					AND file_exists(self::TEMP_DIR . $tempFolder . '/site/data/theme.json')
					) {
						$modele = 'theme';
				}
				if(
					file_exists(self::TEMP_DIR . $tempFolder . '/site/data/admin.json')
					AND file_exists(self::TEMP_DIR . $tempFolder . '/site/data/admin.css')
				) {
						$modele = 'admin';

				}
				if (!empty($modele)
				) {
					// traiter l'archive
					$success = $zip->extractTo('.');

					// Substitution des fontes Google
					if ($modele = 'theme') {
						$c = $this->subFonts(self::DATA_DIR . 'theme.json');
						// Un remplacement nécessite la régénération de la feuille de style
						if ($c > 0
							AND file_exists(self::DATA_DIR . 'theme.css')
						) {
								unlink(self::DATA_DIR . 'theme.css');
						}
					}
					if ($modele = 'admin') {
						$c = $this->subFonts(self::DATA_DIR . 'admin.json');
						// Un remplacement nécessite la régénération de la feuille de style
						if ($c > 0
							AND file_exists(self::DATA_DIR . 'admin.css')
							) {
								unlink(self::DATA_DIR . 'admin.css');
						}
					}

					// traitement d'erreur
					$notification = $success ? 'Le thème  a été importé' : 'Erreur lors de l\'extraction, vérifiez les permissions.';


				} else {
					// pas une archive de thème
					$success = false;
					$notification = 'Ce n\'est pas l\'archive d\'un thème !';
				}
				// Supprimer le dossier temporaire même si le thème est invalide
				$this->removeDir(self::TEMP_DIR . $tempFolder);
				$zip->close();
			} else {
				// erreur à l'ouverture
				$success = false;
				$notification = 'Impossible d\'ouvrir l\'archive';
			}
			return (['success' => $success, 'notification' => $notification]);
		}

		return (['success' => false, 'notification' => 'Archive non spécifiée ou introuvable']);
	}



	/**
	 * Export du thème
	 */
	public function export() {
		// Make zip
			$zipFilename = $this->zipTheme($this->getUrl(2));
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
		$zipFilename = $this->zipTheme($this->getUrl(2));
		// Téléchargement du ZIP
		if (!is_dir(self::FILE_DIR.'source/theme')) {
			mkdir(self::FILE_DIR.'source/theme', 0755);
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
	 * construction du zip Fonction appelée par export() et save()
	 * @param string $modele theme ou admin
	 */
	private function zipTheme($modele) {
		// Creation du dossier
		$zipFilename  =  $modele . date('Y-m-d-H-i-s', time()) . '.zip';
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
					// Traite l'image dans le body
					if ($this->getData(['theme','body','image']) !== '' ) {
					$zip->addFile(self::FILE_DIR.'source/'.$this->getData(['theme','body','image']),
								self::FILE_DIR.'source/'.$this->getData(['theme','body','image'])
								);
					}
					// Traite l'image dans le header
					if ($this->getData(['theme','header','image']) !== '' ) {
					$zip->addFile(self::FILE_DIR.'source/'.$this->getData(['theme','header','image']),
								  self::FILE_DIR.'source/'.$this->getData(['theme','header','image'])
								);
					}
					// Traite les images du header perso
					if (!empty($this->getData(['theme','header','featureFiles'])) ) {
						foreach($this->getData(['theme','header','featureFiles']) as $value) {
							$zip->addFile(self::FILE_DIR . 'source/' . $value,
										  self::FILE_DIR . 'source/' . $value );
						}
					}
					break;
			}
			$ret = $zip->close();
		}
		return ($zipFilename);
	}

	/**
	 * Substitution des fontes de Google Fonts vers CdnFont grâce à un tableau de conversion
	 * Cette fonction est utilisée par l'import.
	 * @param string $file, nom du fichier json à convertir
	 * @return int nombre de substitution effectuées
	 */
	private function subFonts($file)   {
		// Tableau de substitution des fontes
		$fonts = [
			'Abril+Fatface' => 'abril-fatface',
			'Arimo' => 'arimo',
			'Arvo' => 'arvo',
			'Berkshire+Swash' => 'berkshire-swash',
			'Cabin' => 'genera',
			'Dancing+Script' => 'dancing-script',
			'Droid+Sans' => 'droid-sans-2',
			'Droid+Serif' => 'droid-serif-2',
			'Fira+Sans' => 'fira-sans',
			'Inconsolata' => 'inconsolata-2',
			'Indie+Flower' =>'indie-flower',
			'Josefin+Slab' => 'josefin-sans-std',
			'Lobster' => 'lobster-2',
			'Lora' => 'lora',
			'Lato' =>'lato',
			'Marvel' => 'montserrat-ace',
			'Old+Standard+TT' => 'old-standard-tt-3',
			'Open+Sans' =>'open-sans',
				// Corriger l'erreur de nom de police installée par défaut, il manquait un O en majuscule
			'open+Sans' =>'open-sans',
			'Oswald' =>'oswald-4',
			'PT+Mono' => 'pt-mono',
			'PT+Serif' =>'pt-serif',
			'Raleway' => 'raleway-5',
			'Rancho' => 'rancho',
			'Roboto' => 'Roboto',
			'Signika' => 'signika',
			'Ubuntu' => 'ubuntu',
			'Vollkorn' => 'vollkorn'
		];

		$data = file_get_contents($file);
		$count = 0;
		foreach ($fonts as $oldId => $newId){
			$data = str_replace($oldId, $newId, $data, $c);
			$count = $count + (int) $c;
		}
		// Sauvegarder la chaîne modifiée
		if ($count > 0) {
			file_put_contents($file, $data);
		}
		// Retourner le nombre d'occurrences
		return ($count);
	}


	// Retourne un tableau simple des fonts installées idfont avec le nom
	// Cette fonction est utile aux sélecteurs de fonts dans les formulaires.
	public function enumFonts() {
		// Récupère la liste des fontes installées
		$f = $this->getFonts();
		// Construit un tableau avec leur ID et leur famille
		foreach(['websafe', 'imported', 'files'] as $type) {
			if(array_key_exists($type, $f))  {
				foreach ($f[$type] as $fontId => $fontValue ) {
					$fonts [$fontId] = $fontValue['name'];
				}
			}
		}
		ksort($fonts);
		self::$fontsList = $fonts;
	}

}
