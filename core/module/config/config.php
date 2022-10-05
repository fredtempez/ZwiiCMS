<?php

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

class config extends common {

	public static $actions = [
		'backup' => self::GROUP_ADMIN,
		'copyBackups'=> self::GROUP_ADMIN,
		'configMetaImage' => self::GROUP_ADMIN,
		'siteMap' => self::GROUP_ADMIN,
		'index' => self::GROUP_ADMIN,
		'restore' => self::GROUP_ADMIN,
		'updateBaseUrl' => self::GROUP_ADMIN,
		'script' => self::GROUP_ADMIN,
		'logReset' => self::GROUP_ADMIN,
		'logDownload'=> self::GROUP_ADMIN,
		'blacklistReset' => self::GROUP_ADMIN,
		'blacklistDownload' => self::GROUP_ADMIN
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
	// Type de proxy
	public static $proxyType = [
		'tcp://' => 'TCP',
		'http://' => 'HTTP'
	];
	// Authentification SMTP
	public static $SMTPauth = [
		true => 'Oui',
		false => 'Non'
	];
	// Encryptation SMTP
	public static $SMTPEnc = [
		'' => 'Aucune',
		'tls' => 'START TLS',
		'ssl' => 'SSL/TLS'
	];
	// Sécurité de la  connexion - tentative max avant blocage
	public static $connectAttempt = [
		999 => 'Sécurité désactivée',
		3 => '3 tentatives',
		5 => '5 tentatives',
		10 => '10 tentatives'
	];
	// Sécurité de la connexion - durée du blocage
	public static $connectTimeout = [
		0 => 'Sécurité désactivée',
		300 => '5 minutes',
		600 => '10 minutes',
		900 => '15 minutes'
	];
	// Anonymisation des IP du journal
	public static $anonIP = [
		4 => 'Non tronquées',
		3 => 'Niveau 1 (192.168.12.x)',
		2 => 'Niveau 2 (192.168.x.x)',
		1 => 'Niveau 3 (192.x.x.x)',
	];
	public static $captchaTypes = [
		'num' => 'Chiffres',
		'alpha'	  => 'Lettres'
	];

	// Langue traduite courante
	public static $i18nSite = 'fr';

	// Variable pour construire la liste des pages du site
	public static $pagesList = [];
	public static $orphansList = [];
	public static $onlineVersion = '';
	public static $updateButtonText = 'Réinstaller';

	/**
	 * Génére les fichiers pour les crawlers
	 * Sitemap compressé et non compressé
	 * Robots.txt
	 */
	public function siteMap() {

		// Mettre à jour le site map
		$successSitemap=$this->createSitemap();

		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl() . 'config',
			'notification' => $successSitemap ? 'La carte du site a été mise à jour' : 'Echec d\'écriture, la carte du site n\'a pas été mise à jour',
			'state' => $successSitemap
		]);
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
			// Créer le répertoire manquant
			if (!is_dir(self::FILE_DIR.'source/backup')) {
				mkdir(self::FILE_DIR.'source/backup', 0755);
			}
			// Copie dans les fichiers
			$success = copy (self::TEMP_DIR . $fileName , self::FILE_DIR.'source/backup/' . $fileName);
			// Détruire le temporaire
			unlink(self::TEMP_DIR . $fileName);
			// Valeurs en sortie
			$this->addOutput([
				'display' => self::DISPLAY_JSON,
				'content' => json_encode($success)
			]);
		} else {
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Sauvegarder',
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
			$site = 'https://zwiicms.fr/'; } else {
			$site = helper::baseUrl(false);	}

		// Succès de la'opération par défaut
		$success = false;

		// Tente de connecter 5 fois l'API Google
		for ($i=0; $i < 5 ; $i++) { 
			$googlePagespeedData = helper::getUrlContents('https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url='. $site .'&screenshot=true');
			if ($googlePagespeedData !== false) {
				break;
			}
		}
		
		// Traitement des données reçues valides.  
		if ($googlePagespeedData  !== false) {
			$googlePagespeedData = json_decode($googlePagespeedData, true);
			$data = str_replace('_','/',$googlePagespeedData['lighthouseResult']['audits']['final-screenshot']['details']['data']);
			$data = str_replace('-','+',$data);
			$img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
			// Effacer l'image et la miniature png
			if (file_exists(self::FILE_DIR.'thumb/screenshot.jpg')) {
				unlink (self::FILE_DIR.'thumb/screenshot.jpg');
			}
			if (file_exists(self::FILE_DIR.'source/screenshot.jpg')) {
				unlink (self::FILE_DIR.'source/screenshot.jpg');
			}
			$success = file_put_contents( self::FILE_DIR.'source/screenshot.jpg',$img) ;

		}
		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl() . 'config',
			'notification' => $success === false  ? 'Service inaccessible ou erreur d\'écriture de l\'image' : 'Image générée avec succès',
			'state' => $success === false ? false : true
		]);
	}

	/**
	 * Procédure d'importation
	 */
	public function restore() {
		// Soumission du formulaire
		if($this->isPost() ) {

			$success = false;

			if ($this->getInput('configRestoreImportFile', null, true) ) {

				$fileZip = $this->getInput('configRestoreImportFile');
				$file_parts = pathinfo($fileZip);
				$folder = date('Y-m-d-h-i-s', time());
				$zip = new ZipArchive();
				if ($file_parts['extension'] !== 'zip') {
					// Valeurs en sortie erreur
					$this->addOutput([
						'title' => 'Restaurer',
						'view' => 'restore',
						'notification' => 'Le fichier n\'est pas une archive valide',
						'state' => false
						]);
				}
				$successOpen = $zip->open(self::FILE_DIR . 'source/' . $fileZip);
				if ($successOpen === FALSE) {
					// Valeurs en sortie erreur
					$this->addOutput([
						'title' => 'Restaurer',
						'view' => 'restore',
						'notification' => 'Impossible de lire l\'archive',
						'state' => false
						]);
				}
				// Lire le contenu de l'archive dans le tableau files
				for( $i = 0; $i < $zip->numFiles; $i++ ){
					$stat = $zip->statIndex( $i );
					$files [] = ( basename( $stat['name'] ));
				}

				// Lire la dataversion
				$tmpDir = uniqid(4);
				$success = $zip->extractTo( self::TEMP_DIR . $tmpDir );
				$data = file_get_contents( self::TEMP_DIR . $tmpDir . '/data/core.json');
				$obj = json_decode($data);
				$dataVersion = strval ($obj->core->dataVersion);
				switch (strlen($dataVersion)) {
					case 4:
						if (substr($dataVersion,0,1) === '9' ) {
							$version = 9;
						} else {
							$version = 0;
						}
						break;
					case 5:
						$version = substr($dataVersion,0,2);
						break;
					default:
						$version = 0;
						break;
				}
				$this->removeDir(self::TEMP_DIR . $tmpDir );

				if ($version >= 10 )	{
						// Option active, les users sont stockées
						if ($this->getInput('configRestoreImportUser', helper::FILTER_BOOLEAN) === true ) {
							$users = $this->getData(['user']);
					}
				} elseif ($version === 0) { // Version invalide
					// Valeurs en sortie erreur
					$this->addOutput([
						'title' => 'Restaurer',
						'view' => 'restore',
						'notification' => 'Cette archive n\'est pas une sauvegarde valide',
						'state' => false
					]);
				}
				// Préserver les comptes des utilisateurs d'une version 9 si option cochée
				// Positionnement d'une  variable de session lue au constructeurs
				if ($version === 9) {
					$_SESSION['KEEP_USERS'] = $this->getInput('configRestoreImportUser', helper::FILTER_BOOLEAN);
				}
				// Extraire le zip ou 'site/'
				$this->removeDir(self::DATA_DIR);
				$success = $zip->extractTo( 'site/' );
				// Fermer l'archive
				$zip->close();


				// Restaurer les users originaux d'une v10 si option cochée
				if (!empty($users) &&
					$version >= 10 &&
					$this->getInput('configRestoreImportUser', helper::FILTER_BOOLEAN) === true) {
						$this->setData(['user',$users]);
				}
			}
			// Message de notification
			$notification  = $success === true ? 'Restaurer effectuée avec succès' : 'Erreur inconnue';
			$redirect = $this->getInput('configRestoreImportUser', helper::FILTER_BOOLEAN) === true ?  helper::baseUrl() . 'config/restore' : helper::baseUrl() . 'user/login/';
			// Valeurs en sortie erreur
			$this->addOutput([
				/*'title' => 'Restaurer',
				'view' => 'restore',*/
				'redirect' => $redirect,
				'notification' => $notification,
				'state' => $success
			]);
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Restaurer',
			'view' => 'restore'
		]);
	}


	/**
	 * Configuration
	 */
	public function index() {
		// Soumission du formulaire
		if($this->isPost()) {

			// Basculement en mise à jour auto,  remise à 0 du compteur
			if ($this->getData(['config','autoUpdate']) === false &&
				$this->getInput('configAutoUpdate', helper::FILTER_BOOLEAN) === true) {
					$this->setData(['core','lastAutoUpdate',0]);
				}

			// Eviter déconnexion automatique après son activation
			if ( $this->getData(['config','connect', 'autoDisconnect']) === false
				 AND $this->getInput('configAutoDisconnect',helper::FILTER_BOOLEAN) === true ) {
				$this->setData(['user',$this->getuser('id'),'accessCsrf',$_SESSION['csrf']]);
			}
				// Répercuter la suppression de la page dans la configuration du footer
				if ( $this->getData(['theme','footer','displaySearch']) === true
				AND $this->getInput('configSearchPageId') === 'none'
				){
					$this->setData(['theme', 'footer', 'displaySearch', false]);
			}
			if ( $this->getData(['theme','footer','displayLegal']) === true
				AND $this->getInput('configLegalPageId') === 'none'
				){
					$this->setData(['theme', 'footer', 'displayLegal', false]);
			}

			// Sauvegarder les locales
			$this->setData([
				'locale',
				[
					'homePageId' => $this->getInput('localeHomePageId', helper::FILTER_ID, true),
					'page404' => $this->getInput('localePage404'),
					'page403' => $this->getInput('localePage403'),
					'page302' => $this->getInput('localePage302'),
					'legalPageId' => $this->getInput('localeLegalPageId'),
					'searchPageId' => $this->getInput('localeSearchPageId'),
					'searchPageLabel' => empty($this->getInput('localeSearchPageLabel', helper::FILTER_STRING_SHORT))  ? 'Rechercher' : $this->getInput('localeSearchPageLabel', helper::FILTER_STRING_SHORT),
					'legalPageLabel' => empty($this->getInput('localeLegalPageLabel', helper::FILTER_STRING_SHORT)) ? 'Mentions légales' : $this->getInput('localeLegalPageLabel', helper::FILTER_STRING_SHORT),
					'sitemapPageLabel' => empty($this->getInput('localeSitemapPageLabel', helper::FILTER_STRING_SHORT))  ? 'Plan du site' : $this->getInput('localeSitemapPageLabel', helper::FILTER_STRING_SHORT),
					'metaDescription' => $this->getInput('localeMetaDescription', helper::FILTER_STRING_LONG, true),
					'title' => $this->getInput('localeTitle', helper::FILTER_STRING_SHORT, true),
					'cookies' => [
						// Les champs sont obligatoires si l'option consentement des cookies est active
						'mainLabel'	=> $this->getInput('localeCookiesZwiiText', helper::FILTER_STRING_LONG, $this->getData(['config', 'cookieConsent', helper::FILTER_BOOLEAN])),
						'gaLabel'	=> $this->getInput('localeCookiesGaText', helper::FILTER_STRING_LONG, $this->getData(['config', 'cookieConsent', helper::FILTER_BOOLEAN])),
						'titleLabel'	=> $this->getInput('localeCookiesTitleText', helper::FILTER_STRING_SHORT, $this->getData(['config', 'cookieConsent', helper::FILTER_BOOLEAN])),
						'linkLegalLabel'	=> $this->getInput('localeCookiesLinkMlText', helper::FILTER_STRING_SHORT, $this->getData(['config', 'cookieConsent', helper::FILTER_BOOLEAN])),
						'checkboxGaLabel'	=> $this->getInput('localeCookiesCheckboxGaText', helper::FILTER_STRING_SHORT, $this->getData(['config', 'cookieConsent', helper::FILTER_BOOLEAN])),
						'cookiesFooterText' =>  $this->getInput('localeCookiesFooterText', helper::FILTER_STRING_SHORT, $this->getData(['config', 'cookieConsent', helper::FILTER_BOOLEAN])),
						'buttonValidLabel' =>$this->getInput('localeCookiesButtonText', helper::FILTER_STRING_SHORT, $this->getData(['config', 'cookieConsent', helper::FILTER_BOOLEAN]))
					]
				]
			]);

			// Sauvegarder la configuration
			$this->setData([
				'config',
				[
					'favicon' => $this->getInput('configFavicon'),
					'faviconDark' => $this->getInput('configFaviconDark'),
					'timezone' => $this->getInput('configTimezone', helper::FILTER_STRING_SHORT, true),
					'autoUpdate' => $this->getInput('configAutoUpdate', helper::FILTER_BOOLEAN),
					'autoUpdateHtaccess' => $this->getInput('configAutoUpdateHtaccess', helper::FILTER_BOOLEAN),
					'autoBackup' => $this->getInput('configAutoBackup', helper::FILTER_BOOLEAN),
					'maintenance' => $this->getInput('configMaintenance', helper::FILTER_BOOLEAN),
					'cookieConsent' => $this->getInput('configCookieConsent', helper::FILTER_BOOLEAN),
					'proxyType' => $this->getInput('configProxyType'),
					'proxyUrl' => $this->getInput('configProxyUrl'),
					'proxyPort' => $this->getInput('configProxyPort',helper::FILTER_INT),
					'social' => [
						'facebookId' => $this->getInput('socialFacebookId'),
						'linkedinId' => $this->getInput('socialLinkedinId'),
						'instagramId' => $this->getInput('socialInstagramId'),
						'pinterestId' => $this->getInput('socialPinterestId'),
						'twitterId' => $this->getInput('socialTwitterId'),
						'youtubeId' => $this->getInput('socialYoutubeId'),
						'youtubeUserId' => $this->getInput('socialYoutubeUserId'),
						'githubId' => $this->getInput('socialGithubId')
					],
					'smtp' => [
						'enable' => $this->getInput('smtpEnable',helper::FILTER_BOOLEAN),
						'host' => $this->getInput('smtpHost',helper::FILTER_STRING_SHORT,$this->getInput('smtpEnable',helper::FILTER_BOOLEAN)),
						'port' => $this->getInput('smtpPort',helper::FILTER_INT,$this->getInput('smtpEnable',helper::FILTER_BOOLEAN)),
						'auth' => $this->getInput('smtpAuth',helper::FILTER_BOOLEAN),
						'secure' => $this->getInput('smtpSecure',helper::FILTER_BOOLEAN),
						'username' => $this->getInput('smtpUsername',helper::FILTER_STRING_SHORT,$this->getInput('smtpAuth',helper::FILTER_BOOLEAN)),
						'password' =>helper::encrypt($this->getData(['config','smtp','username']),$this->getInput('smtpPassword',null,$this->getInput('smtpAuth',helper::FILTER_BOOLEAN))),
						'sender' => $this->getInput('smtpSender',helper::FILTER_MAIL)
					],
					'seo' => [
						'robots' => $this->getInput('seoRobots',helper::FILTER_BOOLEAN),
						'analyticsId' => $this->getInput('seoAnalyticsId')
					],
					'connect' => [
						'attempt' => $this->getInput('connectAttempt',helper::FILTER_INT),
						'timeout' => $this->getInput('connectTimeout',helper::FILTER_INT),
						'log' => $this->getInput('connectLog',helper::FILTER_BOOLEAN),
						'anonymousIp' => $this->getInput('connectAnonymousIp',helper::FILTER_INT),
						'captcha' => $this->getInput('connectCaptcha',helper::FILTER_BOOLEAN),
						'captchaStrong' => $this->getInput('connectCaptchaStrong',helper::FILTER_BOOLEAN),
						'autoDisconnect' => $this->getInput('connectAutoDisconnect',helper::FILTER_BOOLEAN),
						'captchaType' => $this->getInput('connectCaptchaType'),
						'showPassword' => $this->getInput('connectShowPassword',helper::FILTER_BOOLEAN),
						'redirectLogin' => $this->getInput('connectRedirectLogin',helper::FILTER_BOOLEAN)
					],
					'i18n' => [
						'enable' 			=> $this->getInput('localei18n',helper::FILTER_BOOLEAN),
						'scriptGoogle'      => $this->getData(['config', 'i18n', 'scriptGoogle']),
						'showCredits' 	 	=> $this->getData(['config', 'i18n', 'showCredits']),
						'autoDetect' 	 	=> $this->getData(['config', 'i18n', 'autoDetect']),
						//'admin'			 	=> $this->getData(['config', 'i18n', 'admin']),
						'fr'		 		=> $this->getData(['config', 'i18n', 'fr']),
						'de' 		 		=> $this->getData(['config', 'i18n', 'de']),
						'en' 			 	=> $this->getData(['config', 'i18n', 'en']),
						'es' 			 	=> $this->getData(['config', 'i18n', 'es']),
						'it' 			 	=> $this->getData(['config', 'i18n', 'it']),
						'nl' 			 	=> $this->getData(['config', 'i18n', 'nl']),
						'pt' 			 	=> $this->getData(['config', 'i18n', 'pt'])
					]
				]
			]);

			// Efface les fichiers de backup lorsque l'option est désactivée
			if ($this->getInput('configFileBackup', helper::FILTER_BOOLEAN) === false) {
				$path = realpath('site/data');
				foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename)
				{
					if (strpos($filename,'backup.json')) {
						unlink($filename);
					}
				}
				if (file_exists('site/data/.backup')) unlink('site/data/.backup');
			} else {
				touch('site/data/.backup');
			}
			// Notice
			if(self::$inputNotices === []) {
				// Active la réécriture d'URL
				$rewrite = $this->getInput('configRewrite', helper::FILTER_BOOLEAN);
				if(
					$rewrite
					AND helper::checkRewrite() === false
				) {
					// Ajout des lignes dans le .htaccess
					$fileContent = file_get_contents('.htaccess');
					$rewriteData = 	PHP_EOL .
									'# URL rewriting' .  PHP_EOL .
									'<IfModule mod_rewrite.c>' . PHP_EOL .
									"\tRewriteEngine on" . PHP_EOL .
									"\tRewriteBase " . helper::baseUrl(false, false) . PHP_EOL .
									"\tRewriteCond %{REQUEST_FILENAME} !-f" . PHP_EOL .
									"\tRewriteCond %{REQUEST_FILENAME} !-d" . PHP_EOL .
									"\tRewriteRule ^(.*)$ index.php?$1 [L]" . PHP_EOL .
									'</IfModule>'. PHP_EOL .
									'# URL rewriting' . PHP_EOL ;
					$fileContent = str_replace('# URL rewriting', $rewriteData, $fileContent);
					file_put_contents(
						'.htaccess',
						$fileContent
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
					$fileContent = file_get_contents('.htaccess');
					$fileContent = explode('# URL rewriting', $fileContent);
					$fileContent = $fileContent[0] . '# URL rewriting' . $fileContent[2];
					file_put_contents(
						'.htaccess',
						$fileContent
					);
					// Change le statut de la réécriture d'URL (pour le helper::baseUrl() de la redirection)
					helper::$rewriteStatus = false;
				}
			}
			// Générer robots.txt et sitemap
			$this->siteMap();
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Configuration',
				'view' => 'index',
				'notification' => 'Modifications enregistrées ' ,
				'state' => true
			]);
		}
		// Générer la list des pages disponibles
		self::$pagesList = $this->getData(['page']);
		foreach(self::$pagesList as $page => $pageId) {
			if ($this->getData(['page',$page,'block']) === 'bar' ||
				$this->getData(['page',$page,'disable']) === true) {
				unset(self::$pagesList[$page]);
			}
		}

		self::$orphansList =  $this->getData(['page']);
		foreach(self::$orphansList as $page => $pageId) {
			if ($this->getData(['page',$page,'block']) === 'bar' ||
				$this->getData(['page',$page,'disable']) === true ||
				$this->getdata(['page',$page, 'position']) !== 0) {
				unset(self::$orphansList[$page]);
			}
		}

		// Variable de version
		self::$onlineVersion = helper::getUrlContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/version');
		if (self::$onlineVersion > common::ZWII_VERSION) {
			self::$updateButtonText = "Mettre à jour" ;
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Configuration',
			'view' => 'index'
		]);
	}


	public function script() {
		// Soumission du formulaire
		if($this->isPost()) {
			// Ecrire les fichiers de script
			if ($this->geturl(2) === 'head') {
				file_put_contents(self::DATA_DIR . 'head.inc.html',$this->getInput('configScriptHead',null));
			}
			if ($this->geturl(2) === 'body') {
				file_put_contents(self::DATA_DIR . 'body.inc.html',$this->getInput('configScriptBody',null));
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Éditeur de script dans ' . ucfirst($this->geturl(2)) ,
				'vendor' => [
					'codemirror'
				],
				'view' => 'script',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Éditeur de script dans ' . ucfirst($this->geturl(2)) ,
			'vendor' => [
				'codemirror'
			],
			'view' => 'script'
		]);
	}


	/**
	 * Vider le fichier de log
	 */

	public function logReset() {
		if ( file_exists(self::DATA_DIR . 'journal.log') ) {
			unlink(self::DATA_DIR . 'journal.log');
			// Créer les en-têtes des journaux
			$d = 'Date;Heure;IP;Id;Action' . PHP_EOL;
			file_put_contents(self::DATA_DIR . 'journal.log',$d);
			// Valeurs en sortie
				$this->addOutput([
				'title' => 'Configuration',
				'view' => 'index',
				'notification' => 'Journal réinitialisé avec succès',
				'state' => true
			]);
		} else {
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Configuration',
				'view' => 'index',
				'notification' => 'Aucun journal à effacer',
				'state' => false
			]);
		}

	 }



	 /**
	  * Télécharger le fichier de log
	  */
	  public function logDownload() {
		$fileName = self::DATA_DIR . 'journal.log';
		if (file_exists($fileName)) {
			ob_start();
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
			header('Content-Length: ' . filesize($fileName));
			ob_clean();
			ob_end_flush();
			readfile( $fileName);
			exit();
		} else {
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Configuration',
				'view' => 'index',
				'notification' => 'Aucun fichier journal à télécharger',
				'state' => false
			]);
		}
	}

	/**
	 * Tableau des IP blacklistés
	 */
	public function blacklistDownload () {
		ob_start();
		$fileName = self::TEMP_DIR . 'blacklist.log';
		$d = 'Date dernière tentative;Heure dernière tentative;Id;Adresse IP;Nombre d\'échecs' . PHP_EOL;
		file_put_contents($fileName,$d);
		if ( file_exists($fileName) ) {
			$d = $this->getData(['blacklist']);
			$data = '';
			foreach ($d as $key => $item) {
				$data .= mb_detect_encoding(\PHP81_BC\strftime('%d/%m/%y',$item['lastFail']), 'UTF-8', true)
						? \PHP81_BC\strftime('%d/%m/%y',$item['lastFail']) . ';' . utf8_encode(\PHP81_BC\strftime('%R',$item['lastFail'])) . ';'
						: utf8_encode(\PHP81_BC\strftime('%d/%m/%y',$item['lastFail'])) . ';' . utf8_encode(\PHP81_BC\strftime('%R',$item['lastFail'])) . ';' ;
				$data .= $key  . ';' . $item['ip'] . ';' .  $item['connectFail']  . PHP_EOL;
			}
			file_put_contents($fileName,$data,FILE_APPEND);
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
			header('Content-Length: ' . filesize($fileName));
			ob_clean();
			ob_end_flush();
			readfile( $fileName);
			unlink(self::TEMP_DIR . 'blacklist.log');
			exit();
		} else {
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Configuration',
				'view' => 'index',
				'notification' => 'Aucune liste noire à télécharger',
				'state' => false
			]);
		}
	}

	/**
	 * Réinitialiser les ip blacklistées
	 */

	public function blacklistReset() {
		if ( file_exists(self::DATA_DIR . 'blacklist.json') ) {
			$this->setData(['blacklist',[]]);
			// Valeurs en sortie
				$this->addOutput([
				'title' => 'Configuration',
				'view' => 'index',
				'notification' => 'Liste noire réinitialisée avec succès',
				'state' => true
			]);
		} else {
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Configuration',
				'view' => 'index',
				'notification' => 'Pas de liste à effacer',
				'state' => false
			]);
		}
	}

	/**
	 * Récupération des backups auto dans le gestionnaire de fichiers
	 */
	public function copyBackups() {

		$success = $this->copyDir(self::BACKUP_DIR, self::FILE_DIR . 'source/backup' );
	
		// Valeurs en sortie
		$this->addOutput([
			'title' => 'Configuration',
			'view' => 'index',
			'notification' => 'Copie terminée' . ($success ? ' avec succès' : ' avec des erreurs'),
			'state' => $success
		]);
	}


	/**
	 * Fonction de parcours des données de module
	 * @param string $find donnée à rechercher
	 * @param string $replace donnée à remplacer
	 * @param array tableau à analyser
	 * @param int count nombres d'occurrences
	 * @return array avec les valeurs remplacées.
	 */
	private function recursive_array_replace ($find, $replace, $array, &$count) {
		if (!is_array($array)) {
			return str_replace($find, $replace, $array, $count);
		}

		$newArray = [];
		foreach ($array as $key => $value) {
			$newArray[$key] = $this->recursive_array_replace($find, $replace, $value,$c);
			$count += $c;
		}
		return $newArray;
	}
}
