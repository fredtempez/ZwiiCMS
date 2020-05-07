<?php

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 *  * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 */

class config extends common {

	public static $actions = [
		'backup' => self::GROUP_ADMIN,
		'configMetaImage' => self::GROUP_ADMIN,
		'generateFiles' => self::GROUP_ADMIN,
		'updateRobots' => self::GROUP_ADMIN,
		'index' => self::GROUP_ADMIN,
		'manage' => self::GROUP_ADMIN,
		'updateBaseUrl' => self::GROUP_ADMIN
	];
	
	public static $timezones = [
		'Pacific/Midway'		=> '(GMT-11:00) Midway Island',
		'US/Samoa'				=> '(GMT-11:00) Samoa',
		'US/Hawaii'				=> '(GMT-10:00) Hawaii',
		'US/Alaska'				=> '(GMT-09:00) Alaska',
		'US/Pacific'			=> '(GMT-08:00) Pacific Time (US &amp; Canada)',
		'America/Tijuana'		=> '(GMT-08:00) Tijuana',
		'US/Arizona'			=> '(GMT-07:00) Arizona',
		'US/Mountain'			=> '(GMT-07:00) Mountain Time (US &amp; Canada)',
		'America/Chihuahua'		=> '(GMT-07:00) Chihuahua',
		'America/Mazatlan'		=> '(GMT-07:00) Mazatlan',
		'America/Mexico_City'	=> '(GMT-06:00) Mexico City',
		'America/Monterrey'		=> '(GMT-06:00) Monterrey',
		'Canada/Saskatchewan'	=> '(GMT-06:00) Saskatchewan',
		'US/Central'			=> '(GMT-06:00) Central Time (US &amp; Canada)',
		'US/Eastern'			=> '(GMT-05:00) Eastern Time (US &amp; Canada)',
		'US/East-Indiana'		=> '(GMT-05:00) Indiana (East)',
		'America/Bogota'		=> '(GMT-05:00) Bogota',
		'America/Lima'			=> '(GMT-05:00) Lima',
		'America/Caracas'		=> '(GMT-04:30) Caracas',
		'Canada/Atlantic'		=> '(GMT-04:00) Atlantic Time (Canada)',
		'America/La_Paz'		=> '(GMT-04:00) La Paz',
		'America/Santiago'		=> '(GMT-04:00) Santiago',
		'Canada/Newfoundland'	=> '(GMT-03:30) Newfoundland',
		'America/Buenos_Aires'	=> '(GMT-03:00) Buenos Aires',
		'Greenland'				=> '(GMT-03:00) Greenland',
		'Atlantic/Stanley'		=> '(GMT-02:00) Stanley',
		'Atlantic/Azores'		=> '(GMT-01:00) Azores',
		'Atlantic/Cape_Verde'	=> '(GMT-01:00) Cape Verde Is.',
		'Africa/Casablanca'		=> '(GMT) Casablanca',
		'Europe/Dublin'			=> '(GMT) Dublin',
		'Europe/Lisbon'			=> '(GMT) Lisbon',
		'Europe/London'			=> '(GMT) London',
		'Africa/Monrovia'		=> '(GMT) Monrovia',
		'Europe/Amsterdam'		=> '(GMT+01:00) Amsterdam',
		'Europe/Belgrade'		=> '(GMT+01:00) Belgrade',
		'Europe/Berlin'			=> '(GMT+01:00) Berlin',
		'Europe/Bratislava'		=> '(GMT+01:00) Bratislava',
		'Europe/Brussels'		=> '(GMT+01:00) Brussels',
		'Europe/Budapest'		=> '(GMT+01:00) Budapest',
		'Europe/Copenhagen'		=> '(GMT+01:00) Copenhagen',
		'Europe/Ljubljana'		=> '(GMT+01:00) Ljubljana',
		'Europe/Madrid'			=> '(GMT+01:00) Madrid',
		'Europe/Paris'			=> '(GMT+01:00) Paris',
		'Europe/Prague'			=> '(GMT+01:00) Prague',
		'Europe/Rome'			=> '(GMT+01:00) Rome',
		'Europe/Sarajevo'		=> '(GMT+01:00) Sarajevo',
		'Europe/Skopje'			=> '(GMT+01:00) Skopje',
		'Europe/Stockholm'		=> '(GMT+01:00) Stockholm',
		'Europe/Vienna'			=> '(GMT+01:00) Vienna',
		'Europe/Warsaw'			=> '(GMT+01:00) Warsaw',
		'Europe/Zagreb'			=> '(GMT+01:00) Zagreb',
		'Europe/Athens'			=> '(GMT+02:00) Athens',
		'Europe/Bucharest'		=> '(GMT+02:00) Bucharest',
		'Africa/Cairo'			=> '(GMT+02:00) Cairo',
		'Africa/Harare'			=> '(GMT+02:00) Harare',
		'Europe/Helsinki'		=> '(GMT+02:00) Helsinki',
		'Europe/Istanbul'		=> '(GMT+02:00) Istanbul',
		'Asia/Jerusalem'		=> '(GMT+02:00) Jerusalem',
		'Europe/Kiev'			=> '(GMT+02:00) Kyiv',
		'Europe/Minsk'			=> '(GMT+02:00) Minsk',
		'Europe/Riga'			=> '(GMT+02:00) Riga',
		'Europe/Sofia'			=> '(GMT+02:00) Sofia',
		'Europe/Tallinn'		=> '(GMT+02:00) Tallinn',
		'Europe/Vilnius'		=> '(GMT+02:00) Vilnius',
		'Asia/Baghdad'			=> '(GMT+03:00) Baghdad',
		'Asia/Kuwait'			=> '(GMT+03:00) Kuwait',
		'Europe/Moscow'			=> '(GMT+03:00) Moscow',
		'Africa/Nairobi'		=> '(GMT+03:00) Nairobi',
		'Asia/Riyadh'			=> '(GMT+03:00) Riyadh',
		'Europe/Volgograd'		=> '(GMT+03:00) Volgograd',
		'Asia/Tehran'			=> '(GMT+03:30) Tehran',
		'Asia/Baku'				=> '(GMT+04:00) Baku',
		'Asia/Muscat'			=> '(GMT+04:00) Muscat',
		'Asia/Tbilisi'			=> '(GMT+04:00) Tbilisi',
		'Asia/Yerevan'			=> '(GMT+04:00) Yerevan',
		'Asia/Kabul'			=> '(GMT+04:30) Kabul',
		'Asia/Yekaterinburg'	=> '(GMT+05:00) Ekaterinburg',
		'Asia/Karachi'			=> '(GMT+05:00) Karachi',
		'Asia/Tashkent'			=> '(GMT+05:00) Tashkent',
		'Asia/Kolkata'			=> '(GMT+05:30) Kolkata',
		'Asia/Kathmandu'		=> '(GMT+05:45) Kathmandu',
		'Asia/Almaty'			=> '(GMT+06:00) Almaty',
		'Asia/Dhaka'			=> '(GMT+06:00) Dhaka',
		'Asia/Novosibirsk'		=> '(GMT+06:00) Novosibirsk',
		'Asia/Bangkok'			=> '(GMT+07:00) Bangkok',
		'Asia/Jakarta'			=> '(GMT+07:00) Jakarta',
		'Asia/Krasnoyarsk'		=> '(GMT+07:00) Krasnoyarsk',
		'Asia/Chongqing'		=> '(GMT+08:00) Chongqing',
		'Asia/Hong_Kong'		=> '(GMT+08:00) Hong Kong',
		'Asia/Irkutsk'			=> '(GMT+08:00) Irkutsk',
		'Asia/Kuala_Lumpur'		=> '(GMT+08:00) Kuala Lumpur',
		'Australia/Perth'		=> '(GMT+08:00) Perth',
		'Asia/Singapore'		=> '(GMT+08:00) Singapore',
		'Asia/Taipei'			=> '(GMT+08:00) Taipei',
		'Asia/Ulaanbaatar'		=> '(GMT+08:00) Ulaan Bataar',
		'Asia/Urumqi'			=> '(GMT+08:00) Urumqi',
		'Asia/Seoul'			=> '(GMT+09:00) Seoul',
		'Asia/Tokyo'			=> '(GMT+09:00) Tokyo',
		'Asia/Yakutsk'			=> '(GMT+09:00) Yakutsk',
		'Australia/Adelaide'	=> '(GMT+09:30) Adelaide',
		'Australia/Darwin'		=> '(GMT+09:30) Darwin',
		'Australia/Brisbane'	=> '(GMT+10:00) Brisbane',
		'Australia/Canberra'	=> '(GMT+10:00) Canberra',
		'Pacific/Guam'			=> '(GMT+10:00) Guam',
		'Australia/Hobart'		=> '(GMT+10:00) Hobart',
		'Australia/Melbourne'	=> '(GMT+10:00) Melbourne',
		'Pacific/Port_Moresby'	=> '(GMT+10:00) Port Moresby',
		'Australia/Sydney'		=> '(GMT+10:00) Sydney',
		'Asia/Vladivostok'		=> '(GMT+10:00) Vladivostok',
		'Asia/Magadan'			=> '(GMT+11:00) Magadan',
		'Pacific/Auckland'		=> '(GMT+12:00) Auckland',
		'Pacific/Fiji'			=> '(GMT+12:00) Fiji',
		'Asia/Kamchatka'		=> '(GMT+12:00) Kamchatka'
	];
	// Nombre d'objets par page
	public static $ItemsList = [
		5 => '5 articles',
		10 => '10 articles',
		15 => '15 articles',
		20 => '20  articles'		
	];
		// Type de proxy
		public static $proxyType = [
			'tcp://' => 'TCP',
			'http://' => 'HTTP'
		];


	public function generateFiles() {
		// Mettre à jour le site map
		$successSitemap=$this->createSitemap();

		// Creer un fichier robots.txt
		$successRobots=$this->updateRobots();
		if ( $successSitemap === true &&
			 $successRobots >= 100) {
					$success = true;
				} else {
					$success = false;
		}
		// Valeurs en sortie
		$this->addOutput([
			'notification' => ($successSitemap === true && $successRobots >= 100) ? 'Création réussie' : 'Echec d\'écriture',
			'redirect' => helper::baseUrl() . 'config',
			'state' => ($successSitemap === true && $successRobots >=100)  ? true : false
		]);
	}

	/**
	 * Met à jour un fichier robots.txt lors du changement de réécriture 
	 */

	public function updateRobots() {
		// Créer le fichier robot si absent
		if (!file_exists('robots.txt')) {
			$this->createRobots();
		}
		// backup
		rename ('robots.txt','robots.bak');
		$fileold = fopen('robots.bak','r');
		$filenew = fopen('robots.txt','w');
		while(!feof($fileold))	{			
			$data = fgets($fileold);
			if (strpos($data,'sitemap.xml') == 0) {
				fwrite($filenew, $data);
			} else {
				fwrite($filenew, 'Sitemap: ' . helper::baseUrl(false) . 'sitemap.xml' . PHP_EOL);
				fwrite($filenew, 'Sitemap: ' . helper::baseUrl(false) . 'sitemap.xml.gz' . PHP_EOL);
				fwrite($filenew, '# ZWII CONFIG  ---------' . PHP_EOL);
				break;
			}
		}
		fclose($fileold);
		unlink('robots.bak');
		return(fclose($filenew));
	}

	/**
	 * Sauvegarde des données
	 */
	public function backup() {
		// Soumission du formulaire
		if($this->isPost()) {
			// Creation du ZIP		
			$filter = $this->getInput('configBackupOption',helper::FILTER_BOOLEAN) === true ? ['backup','tmp'] : ['backup','tmp','file'];
			$fileName = helper::autoBackup(self::TEMP_DIR,$filter);

			// Téléchargement du ZIP		
			header('Content-Type: application/zip');
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
			header('Content-Length: ' . filesize(self::TEMP_DIR . $fileName));
			readfile(self::TEMP_DIR . $fileName);
			// Valeurs en sortie
			$this->addOutput([
				'display' => self::DISPLAY_RAW
			]);
			unlink(self::TEMP_DIR . $fileName);
		} else {
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Télécharger une archive du site',
				'view' => 'backup'
			]);
		}
	}

	/**
	 * Réalise une copie d'écran du site
	 *  https://www.codexworld.com/capture-screenshot-website-url-php-google-api/
	 */
	public function configMetaImage() {
		// fonction désactivée pour un site local		
		if ( strpos(helper::baseUrl(false),'localhost') > 0 OR strpos(helper::baseUrl(false),'127.0.0.1') > 0)	{				
			$site = 'https://zwiicms.com/'; } else {
			$site = helper::baseUrl(false);	}
		
		$success= false;
		$googlePagespeedData = helper::urlGetContents('https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url='. $site .'&screenshot=true');
		if ($googlePagespeedData  !== false) {
			$googlePagespeedData = json_decode($googlePagespeedData, true);
			$screenshot = $googlePagespeedData['screenshot']['data'];
			$screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);
			$data = 'data:image/jpeg;base64,'.$screenshot;
			$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));			
			// Effacer la miniature
			if (file_exists(self::FILE_DIR.'thumb/screenshot.png')) {
				unlink (self::FILE_DIR.'thumb/screenshot.png');
			}
			file_put_contents( self::FILE_DIR.'source/screenshot.png',$data);
			$success =true;
		}

		// Valeurs en sortie
		$this->addOutput([
			'notification' => $success === true ? 'Image tag réinitialisée' : 'Erreur : image tag non créée',
			'redirect' => helper::baseUrl() . 'config',
			'state' => $success
		]);
	}	

	/**
	 * Procédure d'importation
	 */
	public function manage() {
		// Soumission du formulaire
		if($this->isPost()) {
			//if ($this->getInput('configManageImportFile'))
			$fileZip = $this->getInput('configManageImportFile');
			$file_parts = pathinfo($fileZip);
			$folder = date('Y-m-d-h-i-s', time());
			$zip = new ZipArchive();
			if ($file_parts['extension'] !== 'zip') {
				// Valeurs en sortie erreur
				$this->addOutput([
					'notification' => 'Le fichier n\'est pas une archive valide',
					'redirect' => helper::baseUrl() . 'config/manage',
					'state' => false
					]);
			}
			$successOpen = $zip->open(self::FILE_DIR . 'source/' . $fileZip);
			if ($successOpen === FALSE) {					
				// Valeurs en sortie erreur
				$this->addOutput([
					'notification' => 'Impossible de lire l\'archive',
					'redirect' => helper::baseUrl() . 'config/manage',
					'state' => false
					]);
			}
			// Lire le contenu de l'archive dans le tableau files
			for( $i = 0; $i < $zip->numFiles; $i++ ){ 
				$stat = $zip->statIndex( $i ); 
				$files [] = ( basename( $stat['name'] )); 
			}
			
			// Détermination de la version	à installer
			if (in_array('theme.json',$files) === true && 
				in_array('core.json',$files) === true && 
				in_array ('user.json', $files) === false ) { 
					// V9 pas de fichier user dans l'archive
					// Stocker le choix de conserver les users installées
					$version = '9';

			} elseif (in_array('theme.json',$files) === true && 					
				in_array('core.json',$files) === true && 
				in_array ('user.json', $files) === true && 
				in_array ('config.json', $files) === true ) {
					// V10 valide
					$version = '10';
					// Option active, les users sont stockées
					if ($this->getInput('configManageImportUser', helper::FILTER_BOOLEAN) === true ) { 
						$users = $this->getData(['user']); 
					}	
			} else { // Version invalide
				// Valeurs en sortie erreur
				$this->addOutput([
					'notification' => 'Cette archive n\'est pas une sauvegarde valide',
					'redirect' => helper::baseUrl() . 'config/manage',
					'state' => false
				]);
			}
			// Préserver les comptes des utilisateurs d'une version 9 si option cochée
			// Positionnement d'une  variable de session lue au constructeur
			if ($version === '9') {
				$_SESSION['KEEP_USERS'] = $this->getInput('configRestoreImportUser', helper::FILTER_BOOLEAN);
			}

			// Extraire le zip ou 'site/'
			$success = $zip->extractTo( 'site/' );				
			// Fermer l'archive	
			$zip->close();
			
			// Restaurer les users originaux d'une v10 si option cochée
			if (!empty($users) &&
				$version === '10' &&
				$this->getInput('configManageImportUser', helper::FILTER_BOOLEAN) === true) { 
					$this->setData(['user',$users]);											
			}
			/*
			if ($version === '9' ) {
				$this->importData($this->getInput('configManageImportUser', helper::FILTER_BOOLEAN));	
				$this->setData(['core','dataVersion',0]);
			}*/
			
			// Met à jours les URL dans les contenus de page
					
			// Message de notification
			$notification  = $success === true ? 'Sauvegarde importée avec succès' : 'Erreur d\'extraction'; 
			$redirect = $this->getInput('configManageImportUser', helper::FILTER_BOOLEAN) === true ?  helper::baseUrl() . 'config/manage' : helper::baseUrl() . 'user/login/';
			// Valeurs en sortie erreur	
			$this->addOutput([
				'notification' => $notification,
				'redirect' =>$redirect,
				'state' => $success
			]);
		} 
	
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Sauvegarder / Restaurer',
			'view' => 'manage'
		]);
	}


	/**
	 * Configuration
	 */
	public function index() {
		// Soumission du formulaire
		if($this->isPost()) {
			// Basculement en mise à jour auto
			// Remise à 0 du compteur
			if ($this->getData(['config','autoUpdate']) === false &&
				$this->getInput('configAutoUpdate', helper::FILTER_BOOLEAN) === true) {
					$this->setData(['core','lastAutoUpdate',0]);
				}
			if ($this->getInput('configLegalCheck', helper::FILTER_BOOLEAN) === true ) {
				$legalPageId = $this->getInput('configLegalPageId', helper::FILTER_ID);
			} else {
				$legalPageId = '';
			}

			$this->setData([
				'config',
				[
					'homePageId' => $this->getInput('configHomePageId', helper::FILTER_ID, true),
					'analyticsId' => $this->getInput('configAnalyticsId'),
					'autoBackup' => $this->getInput('configAutoBackup', helper::FILTER_BOOLEAN),
					'maintenance' => $this->getInput('configMaintenance', helper::FILTER_BOOLEAN),
					'cookieConsent' => $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN),
					'favicon' => $this->getInput('configFavicon'),
					'faviconDark' => $this->getInput('configFaviconDark'),
					'social' => [
						'facebookId' => $this->getInput('configSocialFacebookId'),
						'linkedinId' => $this->getInput('configSocialLinkedinId'),
						'instagramId' => $this->getInput('configSocialInstagramId'),
						'pinterestId' => $this->getInput('configSocialPinterestId'),
						'twitterId' => $this->getInput('configSocialTwitterId'),
						'youtubeId' => $this->getInput('configSocialYoutubeId'),
						'youtubeUserId' => $this->getInput('configSocialYoutubeUserId'),
						'githubId' => $this->getInput('configSocialGithubId')
					],
					'timezone' => $this->getInput('configTimezone', helper::FILTER_STRING_SHORT, true),
					'itemsperPage' => $this->getInput('configItemsperPage', helper::FILTER_INT,true),
					'legalPageId' => $this->getInput('configLegalPageId'),
					'metaDescription' => $this->getInput('configMetaDescription', helper::FILTER_STRING_LONG, true),					
					'title' => $this->getInput('configTitle', helper::FILTER_STRING_SHORT, true),
					'autoUpdate' => helper::urlGetContents('http://zwiicms.com/update/' . common::ZWII_UPDATE_CHANNEL . '/version') === false 
						? false
						: $this->getInput('configAutoUpdate', helper::FILTER_BOOLEAN),
					'proxyType' => $this->getInput('configProxyType'),
					'proxyUrl' => $this->getInput('configProxyUrl'),
					'proxyPort' => $this->getInput('configProxyPort',helper::FILTER_INT)	
				]
			]);
							
			if(self::$inputNotices === []) {
				// Ecrire les fichiers de script
				file_put_contents(self::DATA_DIR . 'head.inc.html',$this->getInput('configScriptHead',null));
				file_put_contents(self::DATA_DIR . 'body.inc.html',$this->getInput('configScriptBody',null));				
				// Active la réécriture d'URL
				$rewrite = $this->getInput('rewrite', helper::FILTER_BOOLEAN);
				if(
					$rewrite
					AND helper::checkRewrite() === false
				) {
					// Ajout des lignes dans le .htaccess
					file_put_contents(
						'.htaccess',
						PHP_EOL .
						'<ifModule mod_rewrite.c>' . PHP_EOL .
						"\tRewriteEngine on" . PHP_EOL .
						"\tRewriteBase " . helper::baseUrl(false, false) . PHP_EOL .
						"\tRewriteCond %{REQUEST_FILENAME} !-f" . PHP_EOL .
						"\tRewriteCond %{REQUEST_FILENAME} !-d" . PHP_EOL .
						"\tRewriteRule ^(.*)$ index.php?$1 [L]" . PHP_EOL .
						'</ifModule>',
						FILE_APPEND
					);
					// Change le statut de la réécriture d'URL (pour le helper::baseUrl() de la redirection)
					helper::$rewriteStatus = true;
				}
				// Désactive la réécriture d'URL
				elseif(
					$rewrite === false
					AND helper::checkRewrite()
				) {
					// Suppression des lignes dans le .htaccess
					$htaccess = explode('# URL rewriting', file_get_contents('.htaccess'));
					file_put_contents('.htaccess', $htaccess[0] . '# URL rewriting');
					// Change le statut de la réécriture d'URL (pour le helper::baseUrl() de la redirection)
					helper::$rewriteStatus = false;
				}
								// Met à jour la baseUrl
								$this->setData(['core', 'baseUrl', helper::baseUrl(true,false) ]);
			}
			// Générer robots.txt et sitemap
			$this->generateFiles();
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(),
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		}
		// Initialisation du screen 
		if (!file_exists(self::FILE_DIR.'source/screenshot.png')) {
			$this->configMetaImage();
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Configuration',
			'view' => 'index'
		]);
	}

			/**
		 * Met à jour les données de site avec l'adresse trannsmise
		 */
		public function updateBaseUrl () {		
			// Supprimer l'information de redirection
			$old = str_replace('?','',$this->getData(['core', 'baseUrl']));
			$new = helper::baseUrl(false,false);
			$success = false ;
			// Boucler sur les pages			
			foreach($this->getHierarchy(null,null,null) as $parentId => $childIds) {
				$content = $this->getData(['page',$parentId,'content']);
				$replace = str_replace( 'href="' . $old , 'href="'. $new , stripslashes($content),$c1) ;			
				$replace = str_replace( 'src="' . $old , 'src="'. $new , stripslashes($replace),$c2) ;			
				if ($c1 > 0 || $c2 > 0) {
					$success = true;
					$this->setData(['page',$parentId,'content', $replace ]);
				}
				foreach($childIds as $childId) {
					$content = $this->getData(['page',$childId,'content']);
					$replace = str_replace( 'href="' . $old , 'href="'. $new , stripslashes($content),$c1) ;			
					$replace = str_replace( 'src="' . $old , 'src="'. $new , stripslashes($replace),$c2) ;	
					if ($c1 > 0 || $c2 > 0) {
						$success = true;
						$this->setData(['page',$childId,'content', $replace ]);
					}
				}
			}	
			if ($success ===  true) {
				 $this->setData(['core','baseUrl',helper::baseUrl(true,false)]);
			}
			// Valeurs en sortie
			$this->addOutput([
				'notification' => $success ? 'Conversion effectuée' : 'Aucune conversion',
				'redirect' => helper::baseUrl() . 'config/manage',
				'state' => $success ? true : false
			]);
		}

}