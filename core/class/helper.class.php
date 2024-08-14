<?php

class helper
{

	/** Statut de la réécriture d'URL (pour éviter de lire le contenu du fichier .htaccess à chaque self::baseUrl()) */
	public static $rewriteStatus = null;

	/** Filtres personnalisés */
	const FILTER_BOOLEAN = 1;
	const FILTER_DATETIME = 2; // filtre pour le champ de formulaire A conserver pour la compatibilité
	const FILTER_FLOAT = 3;
	const FILTER_ID = 4;
	const FILTER_INT = 5;
	const FILTER_MAIL = 6;
	const FILTER_PASSWORD = 7;
	const FILTER_STRING_LONG = 8;
	const FILTER_STRING_SHORT = 9;
	const FILTER_TIMESTAMP = 10; // Saisie d'une date en locatime
	const FILTER_URL = 11;
	const FILTER_DATE = 12; // filtre pour le champ de formulaire
	const FILTER_TIME = 13; // filtre pour le champ de formulair
	const FILTER_MONTH = 14; // filtre pour le champ de formulair
	const FILTER_YEAR = 16; // filtre pour le champ de formulair




	/**
	 * Traduire le message dans la langue déterminée
	 */

	public static function translate($text)
	{

		// La traduction existe déjà dans le core
		/*
						  if (array_key_exists($text, core::$dialog) === false && !empty($text)) {
						  $dialogues = json_decode(file_get_contents('core/module/install/ressource/i18n/fr_FR.json' ), true);
						  $data = array_merge($dialogues,[$text =>  '']);
						  file_put_contents ('core/module/install/ressource/i18n/fr_FR.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), LOCK_EX);
						  }
						  */
		return (array_key_exists($text, core::$dialog) && !empty(core::$dialog[$text]) ? core::$dialog[$text] : $text);
	}

	/**
	 * Formate la date avec le script strftime en UTF8
	 * Date au format time()
	 * $format strftime
	 */
	public static function dateUTF8($format, $date, $locale = 'fr_FR')
	{
		require_once 'core/class/strftime/php-8.1-strftime.class.php';
		return mb_convert_encoding(\PHP81_BC\strftime($format, $date, $locale), 'UTF-8', mb_list_encodings());
	}

	/**
	 * Fonction pour assurer la traduction des messages
	 */
	public static function googleTranslate($to, $text)
	{
		if (!file_exists('site/i18n/' . $to . '.json')) {
			file_put_contents('site/i18n/' . $to . '.json', json_encode([]));
		}
		if (!empty($text)) {
			//Lecture des données en ligne
			$data = json_decode(file_get_contents('site/i18n/' . $to . '.json'), true);
			// Mode traduction
			if ($to !== 'fr_FR') {
				$arrayjson = json_decode(file_get_contents('https://clients5.google.com/translate_a/t?client=dict-chrome-ex&sl=auto&tl=' . $to . '&q=' . rawurlencode($text)), true);
				$response = $arrayjson[0][0];
				// Captation
				if ($data !== '') {
					if (array_key_exists($text, $data)) {
						$data[$text] = $response;
					} else {
						$data = array_merge($data, [$text => $response]);
					}
				}
				// Mode alimentation des chaines
			} else {
				// Créer la variable
				$data = array_merge($data, [$text => '']);
			}
			file_put_contents('site/i18n/' . $to . '.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

		}
	}


	/**
	 * Récupérer l'adresse IP sans tenir compte du proxy
	 * @param integer Niveau d'anonymat 0 aucun, 1 octet à droite, etc..
	 * @return string IP adress
	 * Cette fonction est utilisée par user
	 */

	public static function getIp($anon = 4)
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		// Anonymiser l'adresse IP v4
		$d = array_slice(explode('.', $ip), 0, $anon);
		$d = implode('.', $d);
		$j = array_fill(0, 4 - $anon, 'x');
		$k = implode('.', $j);
		$ip = count($j) == 0 ? $d : $d . '.' . $k;
		return $ip;
	}

	/**
	 * Fonction pour récupérer le numéro de version en ligne et le catalogue des modules
	 * @param string $url à récupérer
	 * @return mixed données récupérées
	 */

	public static function getUrlContents($url)
	{
		// Ejecter free.fr
		if (strpos(self::baseUrl(), 'free.fr') > 0) {
			return false;
		}
		if (
			function_exists('file_get_contents') &&
			ini_get('allow_url_fopen')
		) {
			$url_get_contents_data = @file_get_contents($url); // Masque un warning éventuel
		} elseif (function_exists('curl_version')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			$url_get_contents_data = curl_exec($ch);
			curl_close($ch);
		} elseif (
			function_exists('fopen') &&
			function_exists('stream_get_contents') &&
			ini_get('allow_url_fopen')
		) {
			$handle = fopen($url, "r");
			$url_get_contents_data = stream_get_contents($handle);
		} else {
			$url_get_contents_data = false;
		}
		return $url_get_contents_data;
	}

	/**
	 * Retourne les valeurs d'une colonne du tableau de données
	 * @param array $array Tableau cible
	 * @param string $column Colonne à extraire
	 * @param string $sort Type de tri à appliquer au tableau (SORT_ASC, SORT_DESC, ou null)
	 * @return array
	 */
	public static function arraycolumn($array, $column, $sort = null)
	{
		$newArray = [];
		if (empty($array) === false) {
			$newArray = array_map(function ($element) use ($column) {
				return $element[$column];
			}, $array);
			switch ($sort) {
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
	 * Compatibilité avec les anciens modules
	 */
	public static function arrayCollumn($array, $column, $sort = null)
	{
		return (helper::arrayColumn($array, $column, $sort));
	}



	/**
	 * Génère un backup des données de site
	 * @param string $folder dossier de sauvegarde
	 * @param array $exclude dossier exclus
	 * @return string nom du fichier de sauvegarde
	 */

	public static function autoBackup($folder, $filter = ['backup', 'tmp'])
	{
		// Creation du ZIP
		$baseName = str_replace('/', '', helper::baseUrl(false, false));
		$baseName = empty($baseName) ? 'ZwiiCMS' : $baseName;
		$fileName = $baseName . '-backup-' . date('Y-m-d-H-i-s', time()) . '.zip';
		$zip = new ZipArchive();
		$zip->open($folder . $fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		$directory = 'site/';
		//$filter = array('backup','tmp','file');
		$files = new RecursiveIteratorIterator(
			new RecursiveCallbackFilterIterator(
				new RecursiveDirectoryIterator(
					$directory,
					RecursiveDirectoryIterator::SKIP_DOTS
				),
				function ($fileInfo, $key, $iterator) use ($filter) {
					return $fileInfo->isFile() || !in_array($fileInfo->getBaseName(), $filter);
				}
			)
		);
		foreach ($files as $name => $file) {
			if (!$file->isDir()) {
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen(realpath($directory)) + 1);
				$zip->addFile($filePath, $relativePath);
			}
		}
		$zip->close();
		return ($fileName);
	}



	/**
	 * Retourne la liste des modules installés dans un tableau composé
	 * du nom réel
	 * du numéro de version
	 */
	public static function getModules()
	{
		$modules = array();
		$dirs = array_diff(scandir('module'), array('..', '.'));
		foreach ($dirs as $key => $value) {
			// Dossier non vide
			if (file_exists('module/' . $value . '/' . $value . '.php')) {
				// Lire les constantes en gérant les erreurs de nom de classe
				try {
					$class_reflex = new \ReflectionClass($value);
					$class_constants = $class_reflex->getConstants();
					// Constante REALNAME
					if (array_key_exists('REALNAME', $class_constants)) {
						$realName = $value::REALNAME;
					} else {
						$realName = ucfirst($value);
					}
					// Constante VERSION
					if (array_key_exists('VERSION', $class_constants)) {
						$version = $value::VERSION;
					} else {
						$version = '0.0';
					}
					// Constante UPDATE
					if (array_key_exists('UPDATE', $class_constants)) {
						$update = $value::UPDATE;
					} else {
						$update = '0.0';
					}
					// Constante DELETE
					if (array_key_exists('DELETE', $class_constants)) {
						$delete = $value::DELETE;
					} else {
						$delete = true;
					}
					// Constante DATADIRECTORY
					if (array_key_exists('DATADIRECTORY', $class_constants)) {
						$dataDirectory = $value::DATADIRECTORY;
					} else {
						$dataDirectory = '';
					}
					// Affection
					$modules[$value] = [
						'name' => $value,
						'realName' => $realName,
						'version' => $version,
						'update' => $update,
						'delete' => $delete,
						'dataDirectory' => $dataDirectory
					];
				} catch (Exception $e) {
					//  on ne fait rien
				}
			}
		}
		return ($modules);
	}



	/**
	 * Retourne true si le protocole est en TLS
	 * @return bool
	 */
	public static function isHttps()
	{
		if (
			(empty($_SERVER['HTTPS']) === false and $_SERVER['HTTPS'] !== 'off')
			or $_SERVER['SERVER_PORT'] === 443
		) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Retourne l'URL de base du site
	 * @param bool $queryString Affiche ou non le point d'interrogation
	 * @param bool $host Affiche ou non l'host
	 * @return string
	 */
	public static function baseUrl($queryString = true, $host = true)
	{
		// Protocole
		$protocol = helper::isHttps() === true ? 'https://' : 'http://';
		// Host
		if ($host) {
			$host = $protocol . $_SERVER['HTTP_HOST'];
		}
		// Pathinfo
		$pathInfo = pathinfo($_SERVER['PHP_SELF']);
		// Querystring
		if ($queryString and helper::checkRewrite() === false) {
			$queryString = '?';
		} else {
			$queryString = '';
		}
		return $host . rtrim($pathInfo['dirname'], ' ' . DIRECTORY_SEPARATOR) . '/' . $queryString;
	}

	/**
	 * Check le statut de l'URL rewriting
	 * @return bool
	 */
	public static function checkRewrite()
	{
		// N'interroge que le serveur Apache
		if (strpos($_SERVER["SERVER_SOFTWARE"], 'Apache') > 0) {
			self::$rewriteStatus = false;
		} else {
			// Ouvre et scinde le fichier .htaccess
			$htaccess = explode('# URL rewriting', file_get_contents('.htaccess'));
			// Retourne un boolean en fonction du contenu de la partie réservée à l'URL rewriting
			self::$rewriteStatus = (strpos($htaccess[1], 'RewriteEngine on') !== false);
		}
		return self::$rewriteStatus;
	}

	/**
	 * Renvoie le numéro de version de Zwii est en ligne
	 * @return string
	 */
	public static function getOnlineVersion($channel)
	{
		return (helper::getUrlContents(common::ZWII_UPDATE_URL . $channel . '/version'));
	}


	/**
	 * Check si une nouvelle version de Zwii est disponible
	 * @return bool
	 */
	public static function checkNewVersion($channel)
	{
		$version = helper::getOnlineVersion($channel);
		$update = false;
		if (!empty($version)) {
			$update = version_compare(common::ZWII_VERSION, $version) == -1;
		}
		return $update;
	}


	/**
	 * Génère des variations d'une couleur
	 * @param string $rgba Code rgba de la couleur
	 * @return array
	 */
	public static function colorVariants($rgba)
	{
		preg_match('#\(+(.*)\)+#', $rgba, $matches);
		$rgba = explode(', ', $matches[1]);
		return [
			'normal' => 'rgba(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . $rgba[3] . ')',
			'darken' => 'rgba(' . max(0, $rgba[0] - 15) . ',' . max(0, $rgba[1] - 15) . ',' . max(0, $rgba[2] - 15) . ',' . $rgba[3] . ')',
			'veryDarken' => 'rgba(' . max(0, $rgba[0] - 20) . ',' . max(0, $rgba[1] - 20) . ',' . max(0, $rgba[2] - 20) . ',' . $rgba[3] . ')',
			'text' => self::relativeLuminanceW3C($rgba) > .22 ? "#222" : "#DDD",
			'rgb' => 'rgb(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ')',
			'invert' => 'rgba (' .
				($rgba[0] < 128 ? 255 : 0) . ',' .
				($rgba[1] < 128 ? 255 : 0) . ',' .
				($rgba[1] < 128 ? 255 : 0) . ',' .
				($rgba[0] < 128 ? 255 : 0) . ')'
		];
	}

	/**
	 * Supprime un cookie
	 * @param string $cookieKey Clé du cookie à supprimer
	 */
	public static function deleteCookie($cookieKey)
	{
		unset($_COOKIE[$cookieKey]);
		setcookie($cookieKey, '', time() - 3600, helper::baseUrl(false, false), '', false, true);
	}

	/**
	 * Filtre une chaîne en fonction d'un tableau de données
	 * @param string $text Chaîne à filtrer
	 * @param int $filter Type de filtre à appliquer
	 * @return string
	 */
	public static function filter($text, $filter)
	{
		$text = is_null($text) ? $text : trim($text);
		switch ($filter) {
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
				$text = strip_tags(
					str_replace(
						explode(',', 'á,à,â,ä,ã,å,ç,é,è,ê,ë,í,ì,î,ï,ñ,ó,ò,ô,ö,õ,ú,ù,û,ü,ý,ÿ,\',", '),
						explode(',', 'a,a,a,a,a,a,c,e,e,e,e,i,i,i,i,n,o,o,o,o,o,u,u,u,u,y,y,-,-,-'),
						$text
					)
				);
				$text = preg_replace('/([^a-z0-9-])/', '', $text);
				// Supprime les emoji
				$text = preg_replace('/[[:^print:]]/', '', $text);
				// Supprime les tirets en fin de chaine (emoji en fin de nom)
				$text = rtrim($text, '-');
				// Cas où un identifiant est vide
				if (empty($text)) {
					$text = uniqid('');
				}
				// Un ID ne peut pas être un entier, pour éviter les conflits avec le système de pagination
				if (intval($text) !== 0) {
					$text = '_' . $text;
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
				$text = mb_substr(filter_var($text, FILTER_SANITIZE_FULL_SPECIAL_CHARS), 0, 500000);
				break;
			case self::FILTER_STRING_SHORT:
				$text = mb_substr(filter_var($text, FILTER_SANITIZE_FULL_SPECIAL_CHARS), 0, 500);
				break;
			case self::FILTER_TIMESTAMP:
				$text = date('Y-m-d H:i:s', $text);
				break;
			case self::FILTER_URL:
				$text = filter_var($text, FILTER_SANITIZE_URL);
				break;
			case self::FILTER_DATE:
				$text = date('Y-m-d', $text);
				break;
			case self::FILTER_TIME:
				$text = date('H:i', $text);
		}
		return $text;
	}

	/**
	 * Incrémente une clé en fonction des clés ou des valeurs d'un tableau
	 * @param mixed $key Clé à incrémenter
	 * @param array $array Tableau à vérifier
	 * @return string
	 */
	public static function increment($key, $array = [])
	{
		// Pas besoin d'incrémenter si la clef n'existe pas
		if ($array === []) {
			return $key;
		}
		// Incrémente la clef
		else {
			// Si la clef est numérique elle est incrémentée
			if (is_numeric($key)) {
				$newKey = $key;
				while (array_key_exists($newKey, $array) or in_array($newKey, $array)) {
					$newKey++;
				}
			}
			// Sinon l'incrémentation est ajoutée après la clef
			else {
				$i = 2;
				$newKey = $key;
				while (array_key_exists($newKey, $array) or in_array($newKey, $array)) {
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
	public static function minifyCss($css)
	{
		// Supprime les commentaires
		$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
		// Supprime les tabulations, espaces, nouvelles lignes, etc...
		$css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '     '], '', $css);
		$css = preg_replace(['(( )+{)', '({( )+)'], '{', $css);
		$css = preg_replace(['(( )+})', '(}( )+)', '(;( )*})'], '}', $css);
		$css = preg_replace(['(;( )+)', '(( )+;)'], ';', $css);
		// Convertir les codes entités
		$css = htmlspecialchars_decode($css);
		// Supprime les balises HTML
		$css = strip_tags($css);
		// Retourne le css minifié
		return $css;
	}

	/**
	 * Minimise du js
	 * @param string $js Js à minimiser
	 * @return string
	 */
	public static function minifyJs($js)
	{
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
	public static function pagination($array, $url, $item, $suffix = null)
	{
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
		if ($nbPage > 1) {
			for ($i = 1; $i <= $nbPage; $i++) {
				$disabled = ($i === $currentPage) ? ' class="disabled"' : false;
				$pages .= '<a href="' . helper::baseUrl() . $urlCurrent . '/' . $i . $suffix . '"' . $disabled . '>' . $i . '</a>';
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
	public static function relativeLuminanceW3C($rgba)
	{
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
	public static function sprintAttributes(array $array = [], array $exclude = [])
	{
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
		foreach ($array as $key => $value) {
			if (($value or $value === 0) and in_array($key, $exclude) === false) {
				// Désactive le message de modifications non enregistrées pour le champ
				if ($key === 'noDirty') {
					$attributes[] = 'data-no-dirty';
				}
				// Disabled
				// Readonly
				elseif (in_array($key, ['disabled', 'readonly'])) {
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
	public static function subword($text, $start, $length)
	{
		$text = trim($text);
		if (strlen($text) > $length) {
			$text = mb_substr($text, $start, $length);
			$text = mb_substr($text, 0, min(mb_strlen($text), mb_strrpos($text, ' ')));
		}
		return $text;
	}

	/**
	 * Cryptage
	 * @param string $key la clé d'encryptage
	 * @param string $string la chaine à coder
	 * @return string
	 */
	public static function encrypt($string, $key)
	{
		$encrypted = openssl_encrypt($string, "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));
		return base64_encode($encrypted);
	}

	/**
	 * Décryptage
	 * @param string $key la clé d'encryptage
	 * @param string $string la chaine à décoder
	 * @return string
	 */
	public static function decrypt($string, $key)
	{
		$decrypted = openssl_decrypt(base64_decode($string), "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));
		return $decrypted;
	}

}