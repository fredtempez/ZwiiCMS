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
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */
class config extends common
{
	public static $actions = [
		'backup' => self::ROLE_ADMIN,
		'copyBackups' => self::ROLE_ADMIN,
		'delBackups' => self::ROLE_ADMIN,
		'configMetaImage' => self::ROLE_ADMIN,
		'sitemap' => self::ROLE_ADMIN,
		'index' => self::ROLE_ADMIN,
		'restore' => self::ROLE_ADMIN,
		'updateBaseUrl' => self::ROLE_ADMIN,
		'script' => self::ROLE_ADMIN,
		'logReset' => self::ROLE_ADMIN,
		'logDownload' => self::ROLE_ADMIN,
		'blacklistReset' => self::ROLE_ADMIN,
		'blacklistDownload' => self::ROLE_ADMIN,
		'testmail' => self::ROLE_ADMIN,
	];

	public static $timezones = [
		'Pacific/Midway' => '(GMT-11:00) Midway Island',
		'US/Samoa' => '(GMT-11:00) Samoa',
		'US/Hawaii' => '(GMT-10:00) Hawaii',
		'US/Alaska' => '(GMT-09:00) Alaska',
		'US/Pacific' => '(GMT-08:00) Pacific Time (US &amp; Canada)',
		'America/Tijuana' => '(GMT-08:00) Tijuana',
		'US/Arizona' => '(GMT-07:00) Arizona',
		'US/Mountain' => '(GMT-07:00) Mountain Time (US &amp; Canada)',
		'America/Chihuahua' => '(GMT-07:00) Chihuahua',
		'America/Mazatlan' => '(GMT-07:00) Mazatlan',
		'America/Mexico_City' => '(GMT-06:00) Mexico City',
		'America/Monterrey' => '(GMT-06:00) Monterrey',
		'Canada/Saskatchewan' => '(GMT-06:00) Saskatchewan',
		'US/Central' => '(GMT-06:00) Central Time (US &amp; Canada)',
		'US/Eastern' => '(GMT-05:00) Eastern Time (US &amp; Canada)',
		'US/East-Indiana' => '(GMT-05:00) Indiana (East)',
		'America/Bogota' => '(GMT-05:00) Bogota',
		'America/Lima' => '(GMT-05:00) Lima',
		'America/Caracas' => '(GMT-04:30) Caracas',
		'Canada/Atlantic' => '(GMT-04:00) Atlantic Time (Canada)',
		'America/La_Paz' => '(GMT-04:00) La Paz',
		'America/Santiago' => '(GMT-04:00) Santiago',
		'Canada/Newfoundland' => '(GMT-03:30) Newfoundland',
		'America/Buenos_Aires' => '(GMT-03:00) Buenos Aires',
		'Greenland' => '(GMT-03:00) Greenland',
		'Atlantic/Stanley' => '(GMT-02:00) Stanley',
		'Atlantic/Azores' => '(GMT-01:00) Azores',
		'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
		'Africa/Casablanca' => '(GMT) Casablanca',
		'Europe/Dublin' => '(GMT) Dublin',
		'Europe/Lisbon' => '(GMT) Lisbon',
		'Europe/London' => '(GMT) London',
		'Africa/Monrovia' => '(GMT) Monrovia',
		'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
		'Europe/Belgrade' => '(GMT+01:00) Belgrade',
		'Europe/Berlin' => '(GMT+01:00) Berlin',
		'Europe/Bratislava' => '(GMT+01:00) Bratislava',
		'Europe/Brussels' => '(GMT+01:00) Brussels',
		'Europe/Budapest' => '(GMT+01:00) Budapest',
		'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
		'Europe/Ljubljana' => '(GMT+01:00) Ljubljana',
		'Europe/Madrid' => '(GMT+01:00) Madrid',
		'Europe/Paris' => '(GMT+01:00) Paris',
		'Europe/Prague' => '(GMT+01:00) Prague',
		'Europe/Rome' => '(GMT+01:00) Rome',
		'Europe/Sarajevo' => '(GMT+01:00) Sarajevo',
		'Europe/Skopje' => '(GMT+01:00) Skopje',
		'Europe/Stockholm' => '(GMT+01:00) Stockholm',
		'Europe/Vienna' => '(GMT+01:00) Vienna',
		'Europe/Warsaw' => '(GMT+01:00) Warsaw',
		'Europe/Zagreb' => '(GMT+01:00) Zagreb',
		'Europe/Athens' => '(GMT+02:00) Athens',
		'Europe/Bucharest' => '(GMT+02:00) Bucharest',
		'Africa/Cairo' => '(GMT+02:00) Cairo',
		'Africa/Harare' => '(GMT+02:00) Harare',
		'Europe/Helsinki' => '(GMT+02:00) Helsinki',
		'Europe/Istanbul' => '(GMT+02:00) Istanbul',
		'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
		'Europe/Kiev' => '(GMT+02:00) Kyiv',
		'Europe/Minsk' => '(GMT+02:00) Minsk',
		'Europe/Riga' => '(GMT+02:00) Riga',
		'Europe/Sofia' => '(GMT+02:00) Sofia',
		'Europe/Tallinn' => '(GMT+02:00) Tallinn',
		'Europe/Vilnius' => '(GMT+02:00) Vilnius',
		'Asia/Baghdad' => '(GMT+03:00) Baghdad',
		'Asia/Kuwait' => '(GMT+03:00) Kuwait',
		'Europe/Moscow' => '(GMT+03:00) Moscow',
		'Africa/Nairobi' => '(GMT+03:00) Nairobi',
		'Asia/Riyadh' => '(GMT+03:00) Riyadh',
		'Europe/Volgograd' => '(GMT+03:00) Volgograd',
		'Asia/Tehran' => '(GMT+03:30) Tehran',
		'Asia/Baku' => '(GMT+04:00) Baku',
		'Asia/Muscat' => '(GMT+04:00) Muscat',
		'Asia/Tbilisi' => '(GMT+04:00) Tbilisi',
		'Asia/Yerevan' => '(GMT+04:00) Yerevan',
		'Asia/Kabul' => '(GMT+04:30) Kabul',
		'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
		'Asia/Karachi' => '(GMT+05:00) Karachi',
		'Asia/Tashkent' => '(GMT+05:00) Tashkent',
		'Asia/Kolkata' => '(GMT+05:30) Kolkata',
		'Asia/Kathmandu' => '(GMT+05:45) Kathmandu',
		'Asia/Almaty' => '(GMT+06:00) Almaty',
		'Asia/Dhaka' => '(GMT+06:00) Dhaka',
		'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
		'Asia/Bangkok' => '(GMT+07:00) Bangkok',
		'Asia/Jakarta' => '(GMT+07:00) Jakarta',
		'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
		'Asia/Chongqing' => '(GMT+08:00) Chongqing',
		'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
		'Asia/Irkutsk' => '(GMT+08:00) Irkutsk',
		'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
		'Australia/Perth' => '(GMT+08:00) Perth',
		'Asia/Singapore' => '(GMT+08:00) Singapore',
		'Asia/Taipei' => '(GMT+08:00) Taipei',
		'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaan Bataar',
		'Asia/Urumqi' => '(GMT+08:00) Urumqi',
		'Asia/Seoul' => '(GMT+09:00) Seoul',
		'Asia/Tokyo' => '(GMT+09:00) Tokyo',
		'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
		'Australia/Adelaide' => '(GMT+09:30) Adelaide',
		'Australia/Darwin' => '(GMT+09:30) Darwin',
		'Australia/Brisbane' => '(GMT+10:00) Brisbane',
		'Australia/Canberra' => '(GMT+10:00) Canberra',
		'Pacific/Guam' => '(GMT+10:00) Guam',
		'Australia/Hobart' => '(GMT+10:00) Hobart',
		'Australia/Melbourne' => '(GMT+10:00) Melbourne',
		'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
		'Australia/Sydney' => '(GMT+10:00) Sydney',
		'Asia/Vladivostok' => '(GMT+10:00) Vladivostok',
		'Asia/Magadan' => '(GMT+11:00) Magadan',
		'Pacific/Auckland' => '(GMT+12:00) Auckland',
		'Pacific/Fiji' => '(GMT+12:00) Fiji',
		'Asia/Kamchatka' => '(GMT+12:00) Kamchatka'
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
		4 => 'Non tronquée',
		3 => 'Niveau 1 (192.168.12.x)',
		2 => 'Niveau 2 (192.168.x.x)',
		1 => 'Niveau 3 (192.x.x.x)',
	];

	public static $captchaTypes = [
		'num' => 'Chiffres',
		'alpha' => 'Lettres'
	];

	public static $updateDelay = [
		86400 => '1',
		172800 => '2',
		345600 => '4',
		604800 => '7',
		1209600 => '14',
	];

	// Langue traduite courante
	public static $i18nSite = 'fr_FR';

	// Variable pour construire la liste des pages du site
	public static $onlineVersion = '';

	public static $updateButtonText = 'Réinstaller';

	public static $imageOpenGraph = [];

	/**
	 * Génére les fichiers pour les crawlers
	 * Sitemap compressé et non compressé
	 * Robots.txt
	 */
	public function sitemap()
	{
		// La page n'existe pas
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Sauvegarde la tabulation
			$this->saveTabState('social');

			// Mettre à jour le site map
			$successSitemap = $this->updateSitemap();

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'config',
				'notification' => $successSitemap ? helper::translate('La carte du site a été mise à jour') : helper::translate("Echec de l'écriture, vérifiez les permissions"),
				'state' => $successSitemap
			]);
		}
	}

	/**
	 * Sauvegarde des données
	 */
	public function backup()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Sauvegarde la tabulation
			$this->saveTabState('setup');

			// Creation du ZIP
			$filter = $this->getInput('configBackupOption', helper::FILTER_BOOLEAN) === true ? ['backup', 'tmp'] : ['backup', 'tmp', 'file'];
			$fileName = helper::autoBackup(self::TEMP_DIR, $filter);
			// Créer le répertoire manquant
			if (!is_dir(self::FILE_DIR . 'source/backup')) {
				mkdir(self::FILE_DIR . 'source/backup', 0755);
			}
			// Copie dans les fichiers
			$success = copy(self::TEMP_DIR . $fileName, self::FILE_DIR . 'source/backup/' . $fileName);
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
				'title' => helper::translate('Sauvegarder'),
				'view' => 'backup'
			]);
		}
	}

	/**
	 * Réalise une copie d'écran du site
	 */
	public function configMetaImage()
	{
		// Sauvegarde la tabulation
		$this->saveTabState('social');

		// fonction désactivée pour un site local
		if (strpos(helper::baseUrl(false), 'localhost') > 0 or strpos(helper::baseUrl(false), '127.0.0.1') > 0) {
			$site = 'https://zwiicms.fr/';
		} else {
			$site = helper::baseUrl(false);
		}

		// Clé de l'API
		$token = $this->getData(['config', 'seo', 'keyApi']);

		// Succès de l'opération par défaut
		$success = false;
		$data = false;

		// lire l'API si le token est fourni
		if (!empty($token)) {
			// Tente de connecter 5 fois l'API
			for ($i = 0; $i < 5; $i++) {
				$data = helper::getUrlContents('https://shot.screenshotapi.net/screenshot?token=' . $token . '&url=' . $site . '&width=1200&height=627&output=json&file_type=jpeg&no_cookie_banners=true&wait_for_event=load');
				if ($data !== false) {
					break;
				}
			}
		}

		// Traitement des données reçues valides.
		if (!empty($token) && $data !== false) {
			$data = json_decode($data, true);
			$img = $data['screenshot'];
			// Effacer l'image et la miniature png
			if (file_exists(self::FILE_DIR . 'thumb/screenshot.jpg')) {
				unlink(self::FILE_DIR . 'thumb/screenshot.jpg');
			}
			if (file_exists(self::FILE_DIR . 'source/screenshot.jpg')) {
				unlink(self::FILE_DIR . 'source/screenshot.jpg');
			}
			$success = copy($img, self::FILE_DIR . 'source/screenshot.jpg');
		}

		$notification = empty($token)
			? "La clé de l'API ne peut pas être vide"
			: ($success === false ? 'Service en ligne inaccessible' : "Capture d'écran générée avec succès");

		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl() . 'config',
			'notification' => helper::translate($notification),
			'state' => ($success === false or empty($token)) ? false : true
		]);
	}

	/**
	 * Procédure d'importation
	 */
	public function restore()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Sauvegarde la tabulation
			$this->saveTabState('setup');

			$success = false;

			if ($this->getInput('configRestoreImportFile', null, true)) {
				$fileZip = $this->getInput('configRestoreImportFile');
				$file_parts = pathinfo($fileZip);
				// Validité du nom du fichier sélectionné
				if ($file_parts['extension'] !== 'zip') {
					// Valeurs en sortie erreur
					$this->addOutput([
						'title' => helper::translate('Restaurer'),
						'view' => 'restore',
						'notification' => helper::translate('Archive invalide'),
						'state' => false
					]);
				}
				// Ouverture de l'archive
				$zip = new ZipArchive();
				if ($zip->open(self::FILE_DIR . 'source/' . $fileZip) === FALSE) {
					// Valeurs en sortie erreur
					$this->addOutput([
						'title' => helper::translate('Restaurer'),
						'view' => 'restore',
						'notification' => helper::translate('Archive invalide'),
						'state' => false
					]);
				}

				// Extraction de l'archive dans un dossier temporaire
				$tmpDir = uniqid(8);
				$success = $zip->extractTo(self::TEMP_DIR . $tmpDir);
				// Version de l'archive
				$data = json_decode(file_get_contents(self::TEMP_DIR . $tmpDir . '/data/core.json'), true);
				$dataVersion = $data['core']['dataVersion'];
				// Version non prises en charge <9 ou erreur d'extraction
				if (intval(substr($dataVersion, 0, 1)) <= 9 or !$success) {
					// Valeurs en sortie erreur
					$this->addOutput([
						'title' => helper::translate('Restaurer'),
						'view' => 'restore',
						'notification' => helper::translate('Archive invalide'),
						'state' => false
					]);
				}

				// Fermer le zip
				$zip->close();

				// Option active, préservation des utilisateurs
				if ($this->getInput('configRestoreImportUser', helper::FILTER_BOOLEAN) === true) {
					$users = $this->getData(['user']);
				}

				// Copie dans le dossier /site/data
				$success = $this->copyDir(self::TEMP_DIR . $tmpDir, 'site/');
				$this->deleteDir(self::TEMP_DIR . $tmpDir);

				// Restaurer les users originaux d'une v10 si option cochée
				if (
					$this->getInput('configRestoreImportUser', helper::FILTER_BOOLEAN) === true
				) {
					$this->setData(['user', $users]);
				}
			}

			// Message de notification
			$notification = $success === true ? 'Restauration effectuée avec succès' : 'Erreur inconnue';
			$redirect = $this->getInput('configRestoreImportUser', helper::FILTER_BOOLEAN) === true ? helper::baseUrl() . 'config/restore' : helper::baseUrl() . 'user/login/';
			// Valeurs en sortie erreur
			$this->addOutput([
				'redirect' => $redirect,
				'notification' => helper::translate($notification),
				'state' => $success
			]);
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Restaurer'),
			'view' => 'restore'
		]);
	}

	/**
	 * Configuration
	 */
	public function index()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Basculement en mise à jour auto,  remise à 0 du compteur
			if (
				$this->getData(['config', 'autoUpdate']) === false &&
				$this->getInput('configAutoUpdate', helper::FILTER_BOOLEAN) === true
			) {
				$this->setData(['core', 'lastAutoUpdate', 0]);
			}

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
					'proxyPort' => $this->getInput('configProxyPort', helper::FILTER_INT),
					'autoUpdateDelay' => $this->getInput('configAutoUpdateDelay', helper::FILTER_INT),
					'social' => [
						'facebookId' => $this->getInput('socialFacebookId'),
						'linkedinId' => $this->getInput('socialLinkedinId'),
						'instagramId' => $this->getInput('socialInstagramId'),
						'pinterestId' => $this->getInput('socialPinterestId'),
						'twitterId' => $this->getInput('socialTwitterId'),
						'youtubeId' => $this->getInput('socialYoutubeId'),
						'youtubeUserId' => $this->getInput('socialYoutubeUserId'),
						'githubId' => $this->getInput('socialGithubId'),
						'redditId' => $this->getInput('socialRedditId'),
						'twitchId' => $this->getInput('socialTwitchId'),
						'vimeoId' => $this->getInput('socialVimeoId'),
						'steamId' => $this->getInput('socialSteamId'),
					],
					'smtp' => [
						'enable' => $this->getInput('smtpEnable', helper::FILTER_BOOLEAN),
						'host' => $this->getInput('smtpHost', helper::FILTER_STRING_SHORT),
						'port' => $this->getInput('smtpPort', helper::FILTER_INT),
						'auth' => $this->getInput('smtpAuth', helper::FILTER_BOOLEAN),
						'secure' => $this->getInput('smtpSecure', helper::FILTER_STRING_SHORT),
						'username' => $this->getInput('smtpUsername', helper::FILTER_STRING_SHORT),
						'password' => helper::encrypt($this->getInput('smtpPassword', helper::FILTER_STRING_SHORT), $this->getInput('smtpHost', helper::FILTER_STRING_SHORT)),
						'from' => $this->getInput('smtpFrom', helper::FILTER_MAIL, true),
					],
					'seo' => [
						'robots' => $this->getInput('seoRobots', helper::FILTER_BOOLEAN),
						'openGraphImage' => $this->getInput('seoOpenGraphImage', helper::FILTER_STRING_SHORT),
					],
					'connect' => [
						'attempt' => $this->getInput('connectAttempt', helper::FILTER_INT),
						'timeout' => $this->getInput('connectTimeout', helper::FILTER_INT),
						'log' => $this->getInput('connectLog', helper::FILTER_BOOLEAN),
						'anonymousIp' => $this->getInput('connectAnonymousIp', helper::FILTER_INT),
						'captcha' => $this->getInput('connectCaptcha', helper::FILTER_BOOLEAN),
						'captchaStrong' => $this->getInput('connectCaptchaStrong', helper::FILTER_BOOLEAN),
						'autoDisconnect' => $this->getInput('connectAutoDisconnect', helper::FILTER_BOOLEAN),
						'captchaType' => $this->getInput('connectCaptchaType'),
						'showPassword' => $this->getInput('connectShowPassword', helper::FILTER_BOOLEAN),
						'redirectLogin' => $this->getInput('connectRedirectLogin', helper::FILTER_BOOLEAN),
						'mailAuth' => $this->getInput('connectAuthMail', helper::FILTER_INT),
					],
					'defaultLanguageUI' => $this->getData(['config', 'defaultLanguageUI']),
				]
			]);

			// Sauvegarde la position des onglets de la vue de l'utilisateur courant
			$this->setData([
				'user',
				$this->getUser('id'),
				'view',
				[
					'config' => $this->getInput('containerSelected'),
					'page' => $this->getData(['user', $this->getUser('id'), 'view', 'page']),
				]
			]);

			// Efface les fichiers de backup lorsque l'option est désactivée
			if ($this->getInput('configFileBackup', helper::FILTER_BOOLEAN) === false) {
				$path = realpath('site/data');
				foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
					if (strpos($filename, 'backup.json')) {
						unlink($filename);
					}
				}
				if (file_exists('site/data/.backup'))
					unlink('site/data/.backup');
			} else {
				touch('site/data/.backup');
			}
			// Notice
			if (self::$inputNotices === []) {
				// Active la réécriture d'URL
				$rewrite = $this->getInput('configRewrite', helper::FILTER_BOOLEAN);
				if (
					$rewrite and
					helper::checkRewrite() === false
				) {
					// Ajout des lignes dans le .htaccess
					$fileContent = file_get_contents('.htaccess');
					$rewriteData =
						'# URL rewriting' . PHP_EOL
						. '<IfModule mod_rewrite.c>' . PHP_EOL
						. "\tRewriteEngine on" . PHP_EOL
						. "\tRewriteBase " . helper::baseUrl(false, false) . PHP_EOL
						. "\tRewriteCond %{REQUEST_FILENAME} !-f" . PHP_EOL
						. "\tRewriteCond %{REQUEST_FILENAME} !-d" . PHP_EOL
						. "\tRewriteRule ^(.*)\$ index.php?\$1 [L]" . PHP_EOL
						. '</IfModule>' . PHP_EOL
						. '# URL rewriting';
					$fileContent = str_replace('# URL rewriting', $rewriteData, $fileContent);
					$this->secure_file_put_contents(
						'.htaccess',
						$fileContent
					);
					// Change le statut de la réécriture d'URL (pour le helper::baseUrl() de la redirection)
					helper::$rewriteStatus = true;
				}
				// Désactive la réécriture d'URL
				elseif (
					$rewrite === false and
					helper::checkRewrite()
				) {
					// Suppression des lignes dans le .htaccess
					$fileContent = file_get_contents('.htaccess');
					$fileContent = explode('# URL rewriting', $fileContent);
					$fileContent = $fileContent[0] . '# URL rewriting' . $fileContent[2];
					$this->secure_file_put_contents(
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
				'title' => helper::translate('Configuration'),
				'view' => 'index',
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}

		// Activation du bouton de mise à jour
		if (
			helper::checkNewVersion(common::ZWII_UPDATE_CHANNEL) &&
			$this->getData(['core', 'updateAvailable']) === false &&
			$this->getData(['config', 'autoUpdate'])
		) {
			$this->setData(['core', 'updateAvailable', true]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . 'config',
			]);
		}

		// Variable de version
		if (helper::checkNewVersion(common::ZWII_UPDATE_CHANNEL)) {
			self::$updateButtonText = helper::translate('Mise à jour');
		}

		// Sélecteur de délais, compléter avec la traduction en jours
		foreach (self::$updateDelay as $key => $value) {
			self::$updateDelay[$key] = $key === 86400 ? $value . ' ' . helper::translate('jour') : $value . ' ' . helper::translate('jours');
		}

		// Paramètres de l'image OpenGraph
		$imagePath = self::FILE_DIR . 'source/' . $this->getData(['config', 'seo', 'openGraphImage']);

		// Par défaut
		self::$imageOpenGraph['type'] = '';
		self::$imageOpenGraph['size'] = '';
		self::$imageOpenGraph['wide'] = '';
		self::$imageOpenGraph['height'] = '';
		self::$imageOpenGraph['ratio'] = 0;
		if (
			$this->getData(['config', 'seo', 'openGraphImage']) &&
			file_exists($imagePath)
		) {
			// Infos sur l'image Open Graph
			$typeMime = exif_imagetype($imagePath);
			switch ($typeMime) {
				case IMAGETYPE_JPEG:
					$typeMime = 'jpeg';
					break;
				case IMAGETYPE_PNG:
					$typeMime = 'png';
					break;
				default:
					$typeMime = image_type_to_mime_type($typeMime);
			}
			self::$imageOpenGraph['type'] = $typeMime;
			$imageSize = getimagesize($imagePath);
			self::$imageOpenGraph['wide'] = $imageSize[0];
			self::$imageOpenGraph['height'] = $imageSize[1];
			self::$imageOpenGraph['ratio'] = self::$imageOpenGraph['wide'] / self::$imageOpenGraph['height'];

			self::$imageOpenGraph['size'] = filesize($imagePath);
			$tailleEnOctets = filesize($imagePath);

			if ($tailleEnOctets >= 1024 * 1024) {
				// Si la taille est supérieure ou égale à 1 Mo, afficher en mégaoctets
				self::$imageOpenGraph['size'] = round($tailleEnOctets / (1024 * 1024), 2) . ' Mo';
			} else {
				// Sinon, afficher en kilooctets
				self::$imageOpenGraph['size'] = round($tailleEnOctets / 1024, 2) . ' Ko';
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Configuration'),
			'view' => 'index'
		]);
	}

	public function script()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			// Sauvegarde la tabulation
			$this->saveTabState('setup');

			// Ecrire les fichiers de script
			if ($this->geturl(2) === 'head') {
				$this->secure_file_put_contents(self::DATA_DIR . 'head.inc.html', $this->getInput('configScriptHead', null));
			}
			if ($this->geturl(2) === 'body') {
				$this->secure_file_put_contents(self::DATA_DIR . 'body.inc.html', $this->getInput('configScriptBody', null));
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => helper::translate('Éditeur de script dans ' . ucfirst($this->geturl(2))),
				'vendor' => [
					'codemirror'
				],
				'view' => 'script',
				'state' => true
			]);
		}
		// Valeurs en sortie
		$this->addOutput([
			'title' => sprintf(helper::translate('Éditeur de script %s'), ucfirst($this->geturl(2))),
			'vendor' => [
				'codemirror'
			],
			'view' => 'script'
		]);
	}

	/**
	 * Vider le fichier de log
	 */
	public function logReset()
	{
		// Action interdite
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Sauvegarde la tabulation
			$this->saveTabState('setup');

			if (file_exists(self::DATA_DIR . 'journal.log')) {
				unlink(self::DATA_DIR . 'journal.log');
				// Créer les en-têtes des journaux
				$d = 'Date;Heure;IP;Id;Action' . PHP_EOL;
				$this->secure_file_put_contents(self::DATA_DIR . 'journal.log', $d);
				// Valeurs en sortie
				$this->addOutput([
					'title' => helper::translate('Configuration'),
					'view' => 'index',
					'notification' => helper::translate('Journal réinitialisé avec succès'),
					'state' => true
				]);
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'title' => helper::translate('Configuration'),
					'view' => 'index',
					'notification' => helper::translate('Aucun journal à effacer'),
					'state' => false
				]);
			}
		}
	}

	/**
	 * Télécharger le fichier de log
	 */
	public function logDownload()
	{
		// Action interdite
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Sauvegarde la tabulation
			$this->saveTabState('connect');

			$fileName = self::DATA_DIR . 'journal.log';
			if (file_exists($fileName)) {
				ob_start();
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="' . $fileName . '"');
				header('Content-Length: ' . filesize($fileName));
				ob_clean();
				ob_end_flush();
				readfile($fileName);
				exit();
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'title' => helper::translate('Configuration'),
					'view' => 'index',
					'notification' => helper::translate('Aucun fichier journal à télécharger'),
					'state' => false
				]);
			}
		}
	}

	/**
	 * Tableau des IP blacklistés
	 */
	public function blacklistDownload()
	{
		// Action interdite
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Sauvegarde la tabulation
			$this->saveTabState('connect');

			ob_start();
			$fileName = self::TEMP_DIR . 'blacklist.log';
			$d = "Date dernière tentative;Heure dernière tentative;Id;Adresse IP;Nombre d'échecs" . PHP_EOL;
			$this->secure_file_put_contents($fileName, $d);
			if (file_exists($fileName)) {
				$d = $this->getData(['blacklist']);
				$data = '';
				foreach ($d as $key => $item) {
					$data .= helper::dateUTF8('%Y %m %d', $item['lastFail'], self::$i18nUI) . ' - ' . helper::dateUTF8('%H:%M', time(), self::$i18nUI);
					$data .= $key . ';' . $item['ip'] . ';' . $item['connectFail'] . PHP_EOL;
				}
				$this->secure_file_put_contents($fileName, $data, FILE_APPEND);
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Transfer-Encoding: binary');
				header('Content-Disposition: attachment; filename="' . $fileName . '"');
				header('Content-Length: ' . filesize($fileName));
				ob_clean();
				ob_end_flush();
				readfile($fileName);
				unlink(self::TEMP_DIR . 'blacklist.log');
				exit();
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'title' => helper::translate('Configuration'),
					'view' => 'index',
					'notification' => helper::translate('Aucune liste noire à télécharger'),
					'state' => false
				]);
			}
		}
	}

	/**
	 * Réinitialiser les ip blacklistées
	 */
	public function blacklistReset()
	{
		// Action interdite
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Sauvegarde la tabulation
			$this->saveTabState('connect');

			if (file_exists(self::DATA_DIR . 'blacklist.json')) {
				$this->setData(['blacklist', []]);
				// Valeurs en sortie
				$this->addOutput([
					'title' => helper::translate('Configuration'),
					'view' => 'index',
					'notification' => helper::translate('Liste noire réinitialisée avec succès'),
					'state' => true
				]);
			} else {
				// Valeurs en sortie
				$this->addOutput([
					'title' => helper::translate('Configuration'),
					'view' => 'index',
					'notification' => helper::translate('Aucune liste noire à effacer'),
					'state' => false
				]);
			}
		}
	}

	/**
	 * Récupération des backups auto dans le gestionnaire de fichiers
	 */
	public function copyBackups()
	{
		// Action interdite
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Sauvegarde la tabulation
			$this->saveTabState('setup');

			$success = $this->copyDir(self::BACKUP_DIR, self::FILE_DIR . 'source/backup');

			// Valeurs en sortie
			$this->addOutput([
				'title' => helper::translate('Configuration'),
				'view' => 'index',
				'notification' => $success ? helper::translate('Copie terminée avec succès') : helper::translate('Copie terminée avec des erreurs'),
				'state' => $success
			]);
		}
	}

	/**
	 * Vider le dosser des sauvegardes automatisées
	 */
	public function delBackups()
	{
		// Action interdite
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Sauvegarde la tabulation
			$this->saveTabState('setup');

			$path = realpath(self::BACKUP_DIR);
			$success = $fail = 0;
			foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
				if (strpos($filename, '.zip')) {
					$r = unlink($filename);
					$success = $r === true ? $success + 1 : $success;
					$fail = $r === false ? $fail + 1 : $fail;
				}
			}
			// Valeurs en sortie
			$this->addOutput([
				'title' => helper::translate('Configuration'),
				'view' => 'index',
				'notification' => $success . helper::translate('Fichiers effacés') . ' - ' . helper::translate('Échecs') . ': ' . $fail,
				'state' => true
			]);
		}
	}

	/**
	 * Fonction pour vérifier la présence du module de réécriture
	 * @return bool
	 */
	public function isModRewriteEnabled()
	{
		// Check if Apache and mod_rewrite is loaded
		if (function_exists('apache_get_modules')) {
			$modules = apache_get_modules();
			return in_array('mod_rewrite', $modules);
		} else {
			// Fallback if not using Apache or unable to detect modules
			return getenv('HTTP_MOD_REWRITE') == 'On' || getenv('REDIRECT_STATUS') == '200';
		}
	}

	/**
	 * Envoi un message de test
	 * @return void
	 */
	public function testmail()
	{
		$sent = $this->sendMail(
			$this->getUser('mail'),
			helper::translate('Test de la messagerie du site'),
			'<strong>' . $this->getUser('firstname') . ' ' . $this->getUser('lastname') . '</strong>,<br><br>'
				. '<h4>' . helper::translate('Il semblerait que votre messagerie fonctionne correctement !') . '</h4>',
			null,
			'no-reply@localhost'
		);
		if ($sent !== true) {
			// Désactivation de l'authentification par email
			$this->setData(['config', 'connect', 'mailAuth', 0]);
			// Journalisation
			$this->saveLog($sent);
		}
		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl() . 'config/' . $this->getUrl(2),
			'state' => $sent === true ? true : false,
			'notification' => $sent === true ? helper::translate('Message de test envoyé avec succès') : helper::translate('Message non envoyé')
		]);
	}

	/**
	 * Méthode pour sauvegarder l'état de l'onglet dans les paramètres de l'utilisateur.
	 */
	private function saveTabState($input)
	{
		$this->setData([
			'user',
			$this->getUser('id'),
			'view',
			[
				'config' => $input,
				'page' => $this->getData(['user', $this->getUser('id'), 'view', 'page']),
			]
		]);
	}
}
