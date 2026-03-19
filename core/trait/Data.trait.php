<?php


/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International 
 * @Copyright (C) 2026, Frédéric Tempez
 * @Licensed under the GNU General Public License v3.0 or later.
 * @link http://zwiicms.fr/
 */

/**
 * Trait Data
 * Gestion des données JsonDb
 * 
 * Organisation des méthodes :
 * 
 * 1. INSTANCIATION
 *    - jsonDB()                  : Instancie les bases JsonDb
 * 
 * 2. INITIALISATION
 *    - initData()                : Initialisation des données
 * 
 * 3. CHARGEMENT
 *    - loadDataFile()            : Charge une base (private)
 *    - ensureDataFileLoaded()    : Expose le chargement aux modules (protected)
 *    - loadModuleDataFiles()     : Précharge les bases d'un module
 * 
 * 4. SETTERS
 *    - setData()                 : Sauvegarde des données
 *    - saveDataFile()            : Écrit à la racine d'une base
 *    - saveDB()                  : Force l'enregistrement
 * 
 * 5. GETTERS
 *    - getData()                 : Accède aux données
 *    - fetchDataFile()           : Lit une base éphémère
 *    - isDataFileLoaded()        : Vérifie si une base est chargée
 *    - getDataFilesConfig()      : Retourne la configuration des bases (private)
 * 
 * 6. DELETER
 *    - deleteData()              : Supprime des données
 * 
 * 7. TOOLS
 *    - dataPath()                : Retourne un chemin localisé
 */
trait Data
{

	//
	// 1. INSTANCIATION
	//

	/**
	 * Instancie les bases JsonDb
	 * @param string $lang Langue
	 * @param array|null $dataFilesToLoad Liste des bases à charger (null = toutes)
	 */
	public function jsonDB($lang, $dataFilesToLoad = null)
	{
		// Instanciation de la classe des entrées / sorties avec JsonDb amélioré
		// Si aucune liste spécifiée, charger toutes les bases (comportement par défaut)
		if ($dataFilesToLoad === null) {
			$dataFilesToLoad = array_keys($this->dataFiles);
		}

		// Configuration par défaut globale
		$defaultConfig = self::JSONDB_DEFAULT_CONFIG;

		// Récupère les descripteurs pour les bases spécifiées
		foreach ($dataFilesToLoad as $keys) {
			if (array_key_exists($keys, $this->dataFiles)) {
				// Configuration de base
				$config = [
					'name' => $keys . '.json',
					'dir' => $this->dataPath($keys, $lang),
					'backup' => file_exists('site/data/.backup'),
					'update' => false,
				];

				// Fusion avec configuration spécifique si existe
				$dataFilesConfig = $this->getDataFilesConfig();
				if (isset($dataFilesConfig[$keys])) {
					$config = array_merge($config, $defaultConfig, $dataFilesConfig[$keys]);
				} else {
					// Sinon utiliser la configuration globale par défaut
					$config = array_merge($config, $defaultConfig);
				}

				// Utilisation de la classe JsonDb améliorée
				$this->dataFiles[$keys] = new \Prowebcraft\JsonDb($config);
			}
		}
	}

	//
	// 2. INITIALISATION
	//

	/**
	 * Initialisation des données
	 * @param string $module : nom du module à générer
	 * @param string $lang la langue à créer
	 * @param bool $sampleSite créer un site exemple en FR
	 * choix valides :  core config user theme page module
	 */
	public function initData($module, $lang, $sampleSite = false)
	{
		// Tableau avec les données vierges
		require_once('core/module/install/ressource/defaultdata.php');

		if (!file_exists(self::DATA_DIR . $lang)) {
			mkdir(self::DATA_DIR . $lang, 0755);
		}

		switch ($module) {
			case 'page':
			case 'module':
			case 'locale':
				// Création des sous-dossiers localisés
				if (!file_exists(self::DATA_DIR . $lang)) {
					mkdir(self::DATA_DIR . $lang, 0755);
				}
				if (!file_exists(self::DATA_DIR . $lang . '/content')) {
					mkdir(self::DATA_DIR . $lang . '/content', 0755);
				}
				// Site en français avec site exemple
				if ($lang == 'fr_FR' && $sampleSite === true && $module !== 'locale') {
					$this->setData([$module, \init::$siteTemplate[$module]]);
					// Création des pages
					foreach (\init::$siteContent as $key => $value) {
						$this->setPage($key, $value['content'], 'fr_FR');
					}
					// Version en langue étrangère ou fr_FR sans site de test
				} else {
					// En_EN par défaut si le contenu localisé n'est pas traduit
					$langDefault = array_key_exists($lang, \init::$defaultDataI18n) === true ? $lang : 'default';
					// Charger les données de cette langue
					$this->setData([$module, \init::$defaultDataI18n[$langDefault][$module]]);
					// Créer la page d'accueil, une seule page dans cette configuration
					$pageId = \init::$defaultDataI18n[$langDefault]['locale']['homePageId'];
					$content = \init::$defaultDataI18n[$langDefault]['html'];
					$this->setPage($pageId, $content, $lang);
				}
				break;
			default:
				// Installation des données des autres modules cad theme profil font config, admin et core
				$this->setData([$module, \init::$defaultData[$module]]);
				break;
		}
	}

	//
	// 3. CHARGEMENT
	//

	/**
	 * Charge (ou instancie si absent) une base JsonDb dans le cache partagé, en appliquant la configuration globale + spécifique (compression/chiffrement) pour la langue cible.
	 * @param string $dataFile Nom de la base à charger
	 * @param string $lang Langue (optionnel, utilise la langue courante par défaut)
	 */
	private function loadDataFile($dataFile, $lang = null)
	{
		// Instancie la JsonDb ciblée et la garde en cache partagé pour les lectures/écritures classiques
		if ($lang === null) {
			$lang = self::$siteContent;
		}

		// Vérifier si la base existe et n'est pas déjà chargée
		if (!array_key_exists($dataFile, $this->dataFiles) || empty($this->dataFiles[$dataFile])) {
			// Configuration de base
			$config = [
				'name' => $dataFile . '.json',
				'dir' => $this->dataPath($dataFile, $lang),
				'backup' => file_exists('site/data/.backup'),
				'update' => false,
			];

			// Fusionner avec la configuration JsonDb globale si définie
			if (isset($this->jsonDbConfig)) {
				$config = array_merge($config, $this->jsonDbConfig);
			} else {
				// Configuration par défaut si aucune configuration globale
				$config = array_merge($config, self::JSONDB_DEFAULT_CONFIG);
			}

			// Fusionner avec la configuration spécifique à la base de données
			$dataFilesConfig = $this->getDataFilesConfig();
			if (isset($dataFilesConfig[$dataFile])) {
				$config = array_merge($config, $dataFilesConfig[$dataFile]);
			}

			// Désactiver l'encryption si la clé est vide
			if (isset($config['encryption']) && $config['encryption'] && empty($config['encryptionKey'])) {
				$config['encryption'] = false;
			}

			// Utilisation de la classe JsonDb améliorée
			$this->dataFiles[$dataFile] = new \Prowebcraft\JsonDb($config);
		}
	}

	/**
	 * Assure qu'une base est présente dans le cache partagé en passant par loadDataFile, pour les modules qui n'ont pas accès à la méthode privée.
	 * @param string $dataFile Nom de la base
	 * @param string $lang Langue (optionnel)
	 */
	protected function ensureDataFileLoaded($dataFile, $lang = null)
	{
		// Expose le chargement cache aux modules sans ouvrir l'accès direct à loadDataFile
		if ($lang === null) {
			$lang = self::$siteContent;
		}
		// Charge la base demandée dans le cache partagé pour les appels module
		$this->loadDataFile($dataFile, $lang);
	}

	/**
	 * Précharge les bases nécessaires à un module (communes + spécifiques) pour la langue donnée, sans recharger celles déjà présentes en cache.
	 * @param string $moduleId ID du module
	 * @param string $lang Langue (optionnel)
	 */
	public function loadModuleDataFiles($moduleId, $lang = null)
	{
		// Précharge les bases nécessaires à un module (avec fallback sur la langue courante)
		if ($lang === null) {
			$lang = self::$siteContent;
		}

		// Bases communes requises par la plupart des modules
		$moduleRequiredBases = ['theme', 'font'];

		// Bases spécifiques selon le module
		switch ($moduleId) {
			case 'user':
			case 'config':
				$moduleRequiredBases[] = 'admin';
				$moduleRequiredBases[] = 'profil';
				break;
			case 'blog':
			case 'news':
				$moduleRequiredBases[] = 'blacklist';
				break;
			case 'language':
				$moduleRequiredBases[] = 'language';
				break;
		}

		// Charger les bases requises qui ne sont pas encore chargées
		foreach ($moduleRequiredBases as $dataFile) {
			if (!$this->isDataFileLoaded($dataFile)) {
				// Précharge la base requise pour le module afin d'éviter des accès paresseux plus loin
				$this->loadDataFile($dataFile, $lang);
			}
		}
	}

	//
	// 4. SETTERS
	//

	/**
	 * Sauvegarde des données
	 * @param array $keys Clé(s) des données (les premiers éléments sont le chemin, le dernier est la valeur)
	 * @param bool|null $save Stratégie de sauvegarde :
	 * - null (défaut) : Utilise la configuration actuelle de JsonDb (sauvegarde immédiate par défaut, sauf si setAutoSave(false) a été appelé).
	 * - true : Force la sauvegarde immédiate.
	 * - false : Empêche la sauvegarde immédiate (pour les traitements par lots).
	 * @return bool Succès de l'opération
	 */
	public function setData($keys = [], $save = null)
	{
		// Pas d'enregistrement lorsqu'une notice est présente ou tableau transmis vide
		if (
			!empty(self::$inputNotices) or
			empty($keys)
		) {
			return false;
		}

		// Validation des clés
		if (!is_array($keys) || count($keys) < 2) {
			return false;
		}

		// Empêcher la sauvegarde d'une donnée nulle.
		if (gettype($keys[count($keys) - 1]) === NULL) {
			return false;
		}

		// Vérifier si la base est chargée, sinon la charger automatiquement
		if (empty($this->dataFiles[$keys[0]])) {
			// Charge la base concernée pour disposer du descripteur avant écriture
			$this->loadDataFile($keys[0]);
		}

		// Vérifier que la base de données existe après tentative de chargement
		if (!isset($this->dataFiles[$keys[0]]) || empty($this->dataFiles[$keys[0]])) {
			return false;
		}

		// Initialisation du retour en cas d'erreur de descripteur
		$success = false;

		try {
			// Construire la requête dans la base si au moins 1 clé
			if (count($keys) >= 1) {
				// Descripteur de la base
				$db = (object) $this->dataFiles[$keys[0]];

				// La première clé est le nom du module/fichier
				$query = $keys[0];

				// Construire la requête (notation par points)
				// On boucle jusqu'à l'avant-dernier élément (le dernier étant la valeur)
				for ($i = 1; $i < count($keys) - 1; $i++) {
					$query .= '.' . $keys[$i];
				}

				// Appliquer la modification
				// $save étant null par défaut, c'est JsonDb qui décidera s'il faut écrire sur le disque
				// en fonction de son état interne (setAutoSave)
				$success = $db->set($query, $keys[count($keys) - 1], $save);
			}
		} catch (Exception $e) {
			// Log de l'erreur si possible
			if (method_exists($this, 'saveLog')) {
				$this->saveLog('Erreur setData: ' . $e->getMessage() . ' pour les clés: ' . implode('.', $keys));
			}
			return false;
		}

		return $success;
	}

	/**
	 * Écrit des données à la racine d'une base et persiste via JsonDb en appliquant la configuration et la langue ciblée (compression/chiffrement inclus).
	 * @param string $dataFile Nom de la base à sauvegarder
	 * @param mixed $data Données à écrire (placées à la racine de la base)
	 * @param string|null $lang Langue (optionnel, langue courante par défaut)
	 * @return bool Succès de l'écriture
	 */
	public function saveDataFile($dataFile, $data, $lang = null)
	{
		// Force un chargement avec la bonne config puis écrit et persiste la base ciblée
		if ($lang === null) {
			$lang = self::$siteContent;
		}

		// Charger la base avec la config adaptée si besoin
		// Charge/initialise la base pour appliquer la config (compression/chiffrement) avant sauvegarde
		$this->loadDataFile($dataFile, $lang);

		$db = (object) $this->dataFiles[$dataFile];
		$db->set($dataFile, $data);
		return $db->save();
	}

	/**
	 * Forçage de l'enregistrement
	 * @param mixed $module
	 * @return void
	 * @throws Exception Si la base n'est pas chargée (erreur logique)
	 */
	public function saveDB($module): void
	{
		// Vérifier que la base est bien chargée (ne pas masquer les erreurs logiques)
		if (empty($this->dataFiles[$module])) {
			throw new Exception("Tentative de sauvegarde d'une base non chargée : $module. La base doit être chargée avant d'être sauvegardée.");
		}

		$db = (object) $this->dataFiles[$module];
		$db->save();
	}

	//
	// 5. GETTERS
	//

	/**
	 * Accède aux données
	 * @param array $keys Clé(s) des données
	 * @return mixed
	 */
	public function getData($keys = [])
	{
		// Eviter une requete vide
		if (count($keys) >= 1) {
			// Vérifier si la base est chargée, sinon la charger automatiquement
			if (empty($this->dataFiles[$keys[0]])) {
				// Charge la base demandée avant lecture pour accéder aux données
				$this->loadDataFile($keys[0]);
			}

			// descripteur de la base
			$db = (object) $this->dataFiles[$keys[0]];
			$query = $keys[0];
			// Construire la requête
			for ($i = 1; $i < count($keys); $i++) {
				$query .= '.' . $keys[$i];
			}
			return $db->get($query);
		}
	}

	/**
	 * Lit une base JsonDb dans une instance éphémère (hors cache partagé) avec la configuration complète, et retourne la clé demandée (racine par défaut).
	 * @param string $dataFile Nom de la base
	 * @param string $lang Langue/chemin (optionnel)
	 * @param string|array|null $query Clé à lire (string notation point ou tableau). Par défaut, la racine porte le nom de la base.
	 * @return mixed Données lues ou null si absentes
	 */
	public function fetchDataFile($dataFile, $lang = null, $query = null)
	{
		// Lit une base isolée (nouvelle instance) pour éviter de polluer le cache partagé
		if ($lang === null) {
			$lang = self::$siteContent;
		}
		$config = [
			'name' => $dataFile . '.json',
			'dir' => $this->dataPath($dataFile, $lang),
			'backup' => file_exists('site/data/.backup'),
			'update' => false,
		];

		$config = isset($this->jsonDbConfig)
			? array_merge($config, $this->jsonDbConfig)
			: array_merge($config, self::JSONDB_DEFAULT_CONFIG);

		$dataFilesConfig = $this->getDataFilesConfig();
		if (isset($dataFilesConfig[$dataFile])) {
			$config = array_merge($config, $dataFilesConfig[$dataFile]);
		}

		if (isset($config['encryption']) && $config['encryption'] && empty($config['encryptionKey'])) {
			$config['encryption'] = false;
		}

		$db = new \Prowebcraft\JsonDb($config);

		if ($query === null) {
			$target = $dataFile;
		} elseif (is_array($query)) {
			$target = $dataFile;
			foreach ($query as $segment) {
				$target .= '.' . $segment;
			}
		} else {
			$target = $query;
		}

		return $db->get($target);
	}

	/**
	 * Indique si le descripteur JsonDb d'une base est déjà instancié dans le cache partagé.
	 * @param string $dataFile Nom de la base à vérifier
	 * @return bool True si la base est chargée, false sinon
	 */
	public function isDataFileLoaded($dataFile)
	{
		// Indique si le descripteur JsonDb est déjà en cache partagé
		return array_key_exists($dataFile, $this->dataFiles) && !empty($this->dataFiles[$dataFile]);
	}

	/**
	 * Construit la configuration par base en priorisant config.json, sinon les valeurs par défaut de l'install (defaultdata.php).
	 * @return array Configuration des bases de données
	 */
	private function getDataFilesConfig()
	{
		// Construit la config par base (priorité config.json, sinon défauts d'install)
		// 1. Essayer de lire depuis config.json (priorité 1)
		$configPath = self::DATA_DIR . 'config.json';
		if (file_exists($configPath)) {
			$configData = json_decode(file_get_contents($configPath), true);
			if (isset($configData['config']['database'])) {
				return $configData['config']['database'];
			}
		}

		// 2. Sinon, utiliser la configuration par défaut depuis defaultdata.php (priorité 2)
		require_once('core/module/install/ressource/defaultdata.php');
		return \init::$dataFilesConfig ?? [];
	}

	//
	// 6. DELETER
	//

	/**
	 * Supprime des données
	 * @param array $keys Clé(s) des données
	 */
	public function deleteData($keys)
	{
		// Vérifier si la base est chargée, sinon la charger automatiquement
		if (empty($this->dataFiles[$keys[0]])) {
			// Charge la base ciblée avant suppression pour éviter un descripteur vide
			$this->loadDataFile($keys[0]);
		}

		// descripteur de la base
		$db = (object) $this->dataFiles[$keys[0]];
		// Initialisation de la requête par le nom de la base
		$query = $keys[0];
		// Construire la requête
		for ($i = 1; $i <= count($keys) - 1; $i++) {
			$query .= '.' . $keys[$i];
		}
		// Effacer la donnée
		$success = $db->delete($query, true);
		return is_object($success);
	}

	//
	// 7. TOOLS
	//

	/**
	 * Retourne une chemin localisé pour l'enregistrement des données
	 * @param $stageId nom du module
	 * @param $lang langue des pages
	 * @return string du dossier à créer
	 */
	public function dataPath($id, $lang)
	{
		// Sauf pour les pages et les modules
		if (
			$id === 'page' ||
			$id === 'module' ||
			$id === 'locale'
		) {
			$folder = self::DATA_DIR . $lang . '/';
		} else {
			$folder = self::DATA_DIR;
		}
		return ($folder);
	}

}
