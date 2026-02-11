<?php

namespace Prowebcraft;

/**
 * JsonDb - Base de données fichier plat JSON (Flat file JSON Database)
 * * Version optimisée avec :
 * - Cache APCu (accès mémoire direct, évite les I/O disque et le décodage JSON)
 * - Invalidation intelligente du cache
 * - Sauvegarde atomique (sécurisée contre les crashs pendant l'écriture)
 * - Gestion du chiffrement et de la compression
 */
class JsonDb extends \Prowebcraft\Dot
{
    /** @var string Chemin complet vers le fichier JSON */
    protected $db = '';

    /** @var array|null Données chargées en mémoire */
    protected $data = null;

    /** @var array Configuration de l'instance */
    protected $config = [];

    /** @var bool Indicateur si une recompression est nécessaire */
    protected $needsRecompression = false;

    /** @var bool Active la sauvegarde automatique (true par défaut) */
    protected $autoSave = true;

    // Constantes de configuration interne
    const MAX_FILE_WRITE_ATTEMPTS = 5; // Nombre d'essais pour l'écriture fichier
    const CACHE_TTL = 7200;            // Durée de vie du cache APCu (2 heures)

    /**
     * Constructeur de la base de données.
     * Fusionne la configuration et charge les données initiales.
     * * @param array $config Tableau de configuration (nom, dossier, compression, etc.)
     */
    public function __construct($config = [])
    {
        $this->config = array_merge([
            'name' => 'data.json',
            'backup' => false,
            'dir' => getcwd(),
            'compression' => false, // Compression GZIP
            'encryption' => false,  // Chiffrement OpenSSL
            'encryptionKey' => null,
            'minify' => false,      // Minification JSON
            'cachePrefix' => null    // Préfixe de cache personnalisé pour éviter les collisions
        ], $config);

        // Initialisation du chemin
        $this->db = $this->config['dir'] . $this->config['name'];

        $this->loadData();
        parent::__construct();
    }

    /**
     * Active ou désactive la sauvegarde automatique globale.
     * * Utile pour faire des modifications par lots (batch) sans écrire 
     * sur le disque à chaque changement de variable.
     *
     * @param bool $enabled True pour activer, False pour désactiver
     * @return $this
     */
    public function setAutoSave(bool $enabled)
    {
        $this->autoSave = $enabled;
        return $this;
    }

    /**
     * Détermine si une sauvegarde doit être déclenchée.
     * L'argument local $save est prioritaire sur la config globale $autoSave.
     * * @param bool|null $requestedSave Demande explicite de sauvegarde (ou non)
     * @return bool True si on doit sauvegarder
     */
    protected function shouldSave(?bool $requestedSave): bool
    {
        return $requestedSave ?? $this->autoSave;
    }

    // --- MÉTHODES D'ÉCRITURE SURCHARGÉES (Pour gérer l'AutoSave) ---

    /**
     * Définit une valeur (Surcharge de Dot::set).
     * * @param mixed $key Clé ou chemin (ex: 'users.1.name')
     * @param mixed $value Valeur à stocker
     * @param bool|null $save Stratégie de sauvegarde (null=auto, true=force, false=différé)
     * @return $this
     */
    public function set($key, $value = null, $save = null)
    {
        parent::set($key, $value);
        if ($this->shouldSave($save)) {
            $this->save();
        }
        return $this;
    }

    /**
     * Ajoute une valeur à un tableau (Surcharge de Dot::add).
     * * @param mixed $key Clé du tableau
     * @param mixed $value Valeur à ajouter
     * @param bool $pop Retirer le dernier élément (pile)
     * @param bool|null $save Stratégie de sauvegarde
     * @return $this
     */
    public function add($key, $value = null, $pop = false, $save = null)
    {
        parent::add($key, $value, $pop);
        if ($this->shouldSave($save)) {
            $this->save();
        }
        return $this;
    }

    /**
     * Supprime une clé (Surcharge de Dot::delete).
     * * @param mixed $key Clé à supprimer
     * @param bool|null $save Stratégie de sauvegarde
     * @return $this
     */
    public function delete($key, $save = null)
    {
        parent::delete($key);
        if ($this->shouldSave($save)) {
            $this->save();
        }
        return $this;
    }

    /**
     * Vide les données ou une clé spécifique.
     * * @param mixed $key Clé à vider (null pour tout vider)
     * @param bool $format Conserver la structure (tableau vide) ou supprimer
     * @param bool|null $save Stratégie de sauvegarde
     * @return $this
     */
    public function clear($key = null, $format = false, $save = null)
    {
        parent::clear($key, $format);
        if ($this->shouldSave($save)) {
            $this->save();
        }
        return $this;
    }

    // --- GESTION DES FICHIERS, CACHE ET SAUVEGARDE ---

    /**
     * Sauvegarde les données sur le disque de manière atomique et sécurisée.
     * Gère aussi l'invalidation du cache APCu.
     * * @throws \RuntimeException Si les données sont nulles ou le dossier non inscriptible.
     */
    public function save(): void
    {
        if ($this->data === null) {
            throw new \RuntimeException('Tentative de sauvegarde de données nulles');
        }

        $dir = dirname($this->db);
        if (!is_writable($dir)) {
            throw new \RuntimeException("Le dossier $dir n'est pas accessible en écriture.");
        }

        try {
            // Préparation (Minification, Chiffrement, Encodage JSON)
            $saveData = $this->prepareDataForSave();

            if (is_string($saveData)) {
                $encoded_data = $saveData;
            } else {
                $encoded_data = json_encode($saveData, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT | JSON_THROW_ON_ERROR);
            }

            // Compression GZIP si activée
            if ($this->config['compression']) {
                $encoded_data = gzcompress($encoded_data, 6);
            }

        } catch (\JsonException $e) {
            throw new \RuntimeException("Erreur d'encodage JSON : " . $e->getMessage());
        }

        // Écriture physique sur le disque
        $this->performAtomicSave($encoded_data, $dir);

        // --- INVALIDATION DU CACHE APCu ---
        // On supprime l'entrée du cache pour forcer les autres processus 
        // à recharger les données fraîches depuis le disque au prochain appel.
        if (function_exists('apcu_delete')) {
            $cacheKey = $this->getCacheKey();
            apcu_delete($cacheKey);
        }
    }

    /**
     * Prépare les données brutes pour la sauvegarde.
     * Applique la minification et le chiffrement si configurés.
     * * @return mixed Données prêtes à être encodées
     */
    protected function prepareDataForSave()
    {
        $data = $this->data;

        // Minification
        if ($this->config['minify']) {
            $jsonString = json_encode($data);
            $jsonString = $this->minifyJson($jsonString);
        } else {
            $jsonString = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        // Chiffrement
        if ($this->config['encryption'] && !empty($this->config['encryptionKey'])) {
            $data = [
                'encrypted' => true,
                'data' => $this->encryptData($jsonString, $this->config['encryptionKey'])
            ];
        } else {
            $data = $jsonString;
        }

        return $data;
    }

    /**
     * Charge les données en mémoire.
     * Logique : APCu (si dispo/valide) -> Disque -> Décodage -> Mise en cache.
     * * @param bool $reload Force le rechargement depuis le disque en ignorant le cache.
     * @return array|null Les données chargées
     * @throws \InvalidArgumentException Si le fichier est corrompu.
     */
    protected function loadData($reload = false): ?array
    {
        $cacheKey = $this->getCacheKey();
        
        // Vérification de l'existence de l'extension APCu
        $useCache = function_exists('apcu_fetch');

        // 1. Tentative de lecture depuis APCu (si pas de reload forcé)
        if (!$reload && $useCache) {
            $cached = apcu_fetch($cacheKey);
            
            // On vérifie si le cache est intègre
            if ($cached !== false && isset($cached['mtime']) && isset($cached['payload'])) {
                if (file_exists($this->db)) {
                    // SÉCURITÉ : On vérifie que le fichier n'a pas été modifié 
                    // manuellement (FTP/SSH) plus récemment que le cache.
                    if (filemtime($this->db) <= $cached['mtime']) {
                        $this->data = $cached['payload'];
                        return $this->data;
                    }
                }
            }
        }

        // 2. Chargement depuis le disque (si cache absent, invalide ou reload forcé)
        if ($this->data === null || $reload) {

            if (!file_exists($this->db)) {
                return null;
            }

            // Création d'un backup si configuré
            if ($this->config['backup']) {
                $this->createBackup();
            }

            $file_contents = file_get_contents($this->db);

            // Gestion de la décompression
            if ($this->isCompressed($file_contents)) {
                $file_contents = gzuncompress($file_contents);
                if (!$this->config['compression']) {
                    $this->needsRecompression = true;
                }
            } else {
                // Tente de récupérer des données compressées même si la config dit le contraire
                if ($this->config['compression']) {
                    $test_data = json_decode($file_contents, true);
                    if ($test_data === null) {
                        try {
                            $file_contents = gzuncompress($file_contents);
                        } catch (\Exception $e) {
                            // Ignorer, ce n'était pas compressé
                        }
                    }
                }
            }

            $this->data = json_decode($file_contents, true);

            // Validation du contenu
            if ($this->data === null) {
                $file_contents = trim($file_contents);
                if (empty($file_contents)) {
                   $this->data = [];
                } else {
                   throw new \InvalidArgumentException('Le fichier ' . $this->db . ' contient des données JSON invalides.');
                }
            }

            // Gestion du déchiffrement
            if (isset($this->data['encrypted']) && $this->data['encrypted']) {
                if (empty($this->config['encryptionKey'])) {
                    throw new \RuntimeException('Clé de chiffrement manquante');
                }
                $decrypted = $this->decryptData($this->data['data'], $this->config['encryptionKey']);
                $this->data = json_decode($decrypted, true);

                if ($this->data === null) {
                    throw new \InvalidArgumentException('Le déchiffrement a produit des données invalides.');
                }
            }

            // 3. Mise en cache APCu (si disponible)
            if ($useCache && function_exists('apcu_store') && $this->data !== null) {
                apcu_store($cacheKey, [
                    'mtime' => filemtime($this->db), // Date du fichier au moment de la lecture
                    'payload' => $this->data         // Données PHP prêtes à l'emploi
                ], self::CACHE_TTL);
            }
        }

        if ($this->data !== null && !is_array($this->data)) {
            throw new \InvalidArgumentException('Type de données invalide dans ' . $this->db . ' (Array attendu)');
        }

        return $this->data;
    }

    /**
     * Génère une clé unique pour le cache basée sur le chemin du fichier et l'identifiant du site.
     * @return string
     */
    protected function getCacheKey()
    {
        // Utilise le préfixe personnalisé si défini, sinon génère automatiquement
        if (!empty($this->config['cachePrefix'])) {
            $siteIdentifier = $this->config['cachePrefix'];
        } else {
            // Fallback automatique : hostname + chemin pour éviter les collisions entre sous-domaines
            $siteIdentifier = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'default';
        }
        
        $basePath = $this->db;
        return 'zwii_jdb_' . md5($basePath . '|' . $siteIdentifier);
    }

    /**
     * Effectue une sauvegarde atomique.
     * Écrit dans un fichier temporaire puis renomme pour éviter la corruption de données.
     * * @param string $encoded_data Données à écrire
     * @param string $dir Dossier de destination
     * @throws \RuntimeException En cas d'échec après plusieurs tentatives
     */
    protected function performAtomicSave($encoded_data, $dir)
    {
        $encoded_length = strlen($encoded_data);
        $max_attempts = self::MAX_FILE_WRITE_ATTEMPTS;

        for ($attempt = 0; $attempt < $max_attempts; $attempt++) {
            $temp_file = $dir . '/userdb_' . uniqid('', true) . '.tmp';

            try {
                // Petite pause progressive entre les tentatives
                if ($attempt > 0) {
                    usleep($attempt * 100000);
                }

                // Écriture avec verrou exclusif
                $written = file_put_contents($temp_file, $encoded_data, LOCK_EX);
                if ($written !== $encoded_length) {
                    throw new \RuntimeException("Erreur d'écriture du fichier temporaire (taille incorrecte)");
                }

                // Vérification intégrité
                if (!$this->verifyFileIntegrity($temp_file, $encoded_data)) {
                    throw new \RuntimeException("L'intégrité du fichier n'a pas pu être vérifiée");
                }

                chmod($temp_file, 0644);

                // Remplacement atomique du fichier final
                if ($this->atomicMove($temp_file, $this->db)) {
                    return; // Succès
                }

                error_log('Échec sauvegarde : déplacement atomique échoué (tentative ' . ($attempt + 1) . ')');
            } catch (\Exception $e) {
                error_log('Erreur de sauvegarde : ' . $e->getMessage());
            } finally {
                // Nettoyage fichier temporaire
                if (file_exists($temp_file)) {
                    @unlink($temp_file);
                }
            }
            
            // Attente exponentielle avant nouvel essai
            usleep(pow(2, $attempt) * 250000);
        }

        throw new \RuntimeException('Échec de sauvegarde après ' . $max_attempts . ' tentatives');
    }

    /**
     * Vérifie que le contenu écrit correspond exactement au contenu attendu.
     * @return bool
     */
    protected function verifyFileIntegrity($filename, $expected_content)
    {
        $actual_content = file_get_contents($filename);
        return $actual_content === $expected_content;
    }

    /**
     * Déplace ou renomme un fichier de manière compatible OS (Windows/Linux).
     * @return bool
     */
    protected function atomicMove($temp_file, $target_file)
    {
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            // Sur Windows, rename ne peut pas écraser un fichier existant
            if (file_exists($target_file)) {
                @chmod($target_file, 0666); // S'assurer qu'on peut écrire
                // @unlink($target_file); // Risqué si le copy échoue après
            }
            // Copy + Unlink est souvent plus stable sur Windows pour simuler l'atomique
            if (copy($temp_file, $target_file)) {
                return unlink($temp_file);
            }
        } else {
            // Sur Linux/Unix, rename est atomique
            return rename($temp_file, $target_file);
        }
        return false;
    }

    /**
     * Détecte si une chaîne est compressée (GZIP).
     * @return bool
     */
    protected function isCompressed($data)
    {
        return substr($data, 0, 2) === "\x78\x9c" || substr($data, 0, 2) === "\x78\xda" || substr($data, 0, 2) === "\x78\x5e";
    }

    /**
     * Crée une copie de sauvegarde du fichier actuel.
     */
    protected function createBackup()
    {
        $backup_path = $this->config['dir'] . DIRECTORY_SEPARATOR . $this->config['name'] . '.backup.' . date('Y-m-d_H-i-s');

        try {
            if (!copy($this->db, $backup_path)) {
                throw new \RuntimeException('Échec de la création de la sauvegarde');
            }
            $this->cleanupOldBackups();
        } catch (\Exception $e) {
            throw new \RuntimeException('Erreur de sauvegarde : ' . $e->getMessage());
        }
    }

    /**
     * Supprime les anciennes sauvegardes (garde les 5 plus récentes).
     */
    protected function cleanupOldBackups()
    {
        $backup_pattern = $this->config['dir'] . DIRECTORY_SEPARATOR . $this->config['name'] . '.backup.*';
        $backups = glob($backup_pattern);

        if (count($backups) > 5) {
            // Tri par date de modification décroissante
            usort($backups, function ($a, $b) {
                return filemtime($b) - filemtime($a);
            });

            // Suppression des fichiers excédentaires
            $to_delete = array_slice($backups, 5);
            foreach ($to_delete as $old_backup) {
                @unlink($old_backup);
            }
        }
    }

    /**
     * Chiffre les données (AES-256-CBC).
     */
    protected function encryptData($data, $key)
    {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }

    /**
     * Déchiffre les données.
     */
    protected function decryptData($encryptedData, $key)
    {
        $data = base64_decode($encryptedData);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * Minifie le JSON en retirant les espaces inutiles.
     */
    protected function minifyJson($json)
    {
        return preg_replace(['/\s+/', '/^\s+|\s+$/'], [' ', ''], $json);
    }

    /**
     * Applique de nouveaux changements de configuration.
     * Si les paramètres de compression/chiffrement changent, le fichier est réécrit.
     */
    public function applyConfigChanges($newConfig)
    {
        $configChanged = false;

        if (
            $this->config['compression'] !== ($newConfig['compression'] ?? $this->config['compression']) ||
            $this->config['encryption'] !== ($newConfig['encryption'] ?? $this->config['encryption']) ||
            $this->config['encryptionKey'] !== ($newConfig['encryptionKey'] ?? $this->config['encryptionKey']) ||
            $this->config['minify'] !== ($newConfig['minify'] ?? $this->config['minify'])
        ) {
            $configChanged = true;
        }

        if ($configChanged) {
            $this->reload();
            $this->config = array_merge($this->config, $newConfig);
            $this->save();
        }
    }

    /**
     * Met à jour la configuration en mémoire (sans sauvegarde immédiate).
     */
    public function updateConfig($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Force le rechargement des données depuis le disque.
     * @return $this
     */
    public function reload()
    {
        $this->loadData(true);
        return $this;
    }
}