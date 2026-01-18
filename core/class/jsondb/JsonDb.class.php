<?php

namespace Prowebcraft;

/**
 * Improved JsonDb with enhanced caching, performance and security features.
 * Optimized Version:
 * - Batch saving support (autoSave toggle)
 * - Optimized cache key generation
 * - Safer data loading
 */
class JsonDb extends \Prowebcraft\Dot
{
    protected $db = '';
    protected $data = null;
    protected $config = [];
    protected $cache = [];
    protected $cacheEnabled = false;
    protected $cacheTTL = 3600;
    protected $cacheTags = [];
    protected $needsRecompression = false;

    // --- OPTIMISATION A : Gestion de la sauvegarde automatique ---
    /** @var bool Active la sauvegarde automatique par défaut (true pour rétrocompatibilité) */
    protected $autoSave = true;

    // Constants
    const MAX_FILE_WRITE_ATTEMPTS = 5;
    const RETRY_DELAY_SECONDS = 1;
    const MAX_CACHE_SIZE = 1000;
    const CACHE_CLEANUP_PROBABILITY = 100;

    public function __construct($config = [])
    {
        $this->config = array_merge([
            'name' => 'data.json',
            'backup' => false,
            'dir' => getcwd(),
            'cache' => false,
            'cacheTTL' => 3600,
            'compression' => false,
            'encryption' => false,
            'encryptionKey' => null,
            'minify' => false
        ], $config);

        $this->cacheEnabled = $this->config['cache'] ?? false;
        $this->cacheTTL = $this->config['cacheTTL'] ?? 3600;

        $this->loadData();
        parent::__construct();
    }

    /**
     * Active ou désactive la sauvegarde automatique globale.
     * Recommandé : mettre à false avant une boucle de modifications, puis appeler save() manuellement.
     *
     * @param bool $enabled
     * @return $this
     */
    public function setAutoSave(bool $enabled)
    {
        $this->autoSave = $enabled;
        return $this;
    }

    /**
     * Détermine si on doit sauvegarder.
     * L'argument local $save est prioritaire sur la config globale $autoSave.
     */
    protected function shouldSave(?bool $requestedSave): bool
    {
        return $requestedSave ?? $this->autoSave;
    }

    /**
     * Enhanced get with tag-based cache invalidation
     */
    public function get($key, $default = null, $asObject = false, $tags = [])
    {
        if (!$this->cacheEnabled || $key === null) {
            return parent::get($key, $default, $asObject);
        }

        $cacheKey = $this->generateCacheKey($key, $asObject);

        if ($this->isCachedWithTags($cacheKey, $tags)) {
            $this->cache[$cacheKey]['hits']++;
            return $this->cache[$cacheKey]['value'];
        }

        $value = parent::get($key, $default, $asObject);

        $this->cache[$cacheKey] = [
            'value' => $value,
            'expires' => time() + $this->cacheTTL,
            'tags' => $tags,
            'hits' => 0,
            'created' => time()
        ];

        if (mt_rand(1, self::CACHE_CLEANUP_PROBABILITY) === 1) {
            $this->optimizeCache();
        }

        return $value;
    }

    // --- SURCHARGE DES MÉTHODES D'ÉCRITURE AVEC OPTIMISATION BATCH ---

    public function set($key, $value = null, $save = null)
    {
        parent::set($key, $value);

        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }

        if ($this->shouldSave($save)) {
            $this->save();
        }
        return $this;
    }

    public function add($key, $value = null, $pop = false, $save = null)
    {
        parent::add($key, $value, $pop);

        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }

        if ($this->shouldSave($save)) {
            $this->save();
        }
        return $this;
    }

    public function delete($key, $save = null)
    {
        parent::delete($key);

        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }

        if ($this->shouldSave($save)) {
            $this->save();
        }
        return $this;
    }

    public function clear($key = null, $format = false, $save = null)
    {
        parent::clear($key, $format);

        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }

        if ($this->shouldSave($save)) {
            $this->save();
        }
        return $this;
    }

    // --- FIN DES MÉTHODES D'ÉCRITURE ---

    public function invalidateCacheByTag($tag)
    {
        $removed = 0;
        foreach ($this->cache as $key => $entry) {
            if (isset($entry['tags']) && in_array($tag, $entry['tags'])) {
                unset($this->cache[$key]);
                $removed++;
            }
        }
        return $removed;
    }

    protected function optimizeCache()
    {
        if (count($this->cache) <= self::MAX_CACHE_SIZE) {
            return;
        }

        uasort($this->cache, function ($a, $b) {
            if ($a['hits'] === $b['hits']) {
                return $a['created'] - $b['created'];
            }
            return $a['hits'] - $b['hits'];
        });

        $keepCount = (int) (self::MAX_CACHE_SIZE * 0.8);
        $this->cache = array_slice($this->cache, -$keepCount, null, true);
    }

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
            $saveData = $this->prepareDataForSave();

            if (is_string($saveData)) {
                $encoded_data = $saveData;
            } else {
                $encoded_data = json_encode($saveData, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT | JSON_THROW_ON_ERROR);
            }

            if ($this->config['compression']) {
                $encoded_data = gzcompress($encoded_data, 6);
            }

        } catch (\JsonException $e) {
            throw new \RuntimeException("Erreur d'encodage JSON : " . $e->getMessage());
        }

        $this->performAtomicSave($encoded_data, $dir);
    }

    protected function prepareDataForSave()
    {
        $data = $this->data;

        if ($this->config['minify']) {
            $jsonString = json_encode($data);
            $jsonString = $this->minifyJson($jsonString);
        } else {
            $jsonString = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

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

    protected function loadData($reload = false): ?array
    {
        if ($this->data === null || $reload) {
            $this->db = $this->config['dir'] . $this->config['name'];

            if (!file_exists($this->db)) {
                return null;
            }

            if ($this->config['backup']) {
                $this->createBackup();
            }

            $file_contents = file_get_contents($this->db);

            if ($this->isCompressed($file_contents)) {
                $file_contents = gzuncompress($file_contents);
                if (!$this->config['compression']) {
                    $this->needsRecompression = true;
                }
            } else {
                if ($this->config['compression']) {
                    $test_data = json_decode($file_contents, true);
                    if ($test_data === null) {
                        try {
                            $file_contents = gzuncompress($file_contents);
                        } catch (\Exception $e) {
                            // Échec silencieux
                        }
                    }
                }
            }

            $this->data = json_decode($file_contents, true);

            // --- OPTIMISATION C : Suppression du correctif dangereux "stripslashes" ---
            // Si $this->data est null ici, c'est que le fichier est vraiment corrompu
            // ou mal formaté, le "hack" des guillemets a été retiré par sécurité.

            if ($this->data === null) {
                // Tentative minimale de nettoyage (espaces blancs) avant erreur
                $file_contents = trim($file_contents);
                if (empty($file_contents)) {
                   $this->data = []; // Fichier vide = tableau vide
                } else {
                   throw new \InvalidArgumentException('Le fichier ' . $this->db . ' contient des données invalides. Contenu: ' . substr($file_contents, 0, 200));
                }
            }

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
        }

        if ($this->data !== null && !is_array($this->data)) {
            throw new \InvalidArgumentException('Type de données invalide dans ' . $this->db . '. Attendu: array, reçu: ' . gettype($this->data));
        }

        return $this->data;
    }

    protected function performAtomicSave($encoded_data, $dir)
    {
        $encoded_length = strlen($encoded_data);
        $max_attempts = self::MAX_FILE_WRITE_ATTEMPTS;

        for ($attempt = 0; $attempt < $max_attempts; $attempt++) {
            $temp_file = $dir . '/userdb_' . uniqid('', true) . '.tmp';

            try {
                if ($attempt > 0) {
                    usleep($attempt * 100000);
                }

                $written = file_put_contents($temp_file, $encoded_data, LOCK_EX);
                if ($written !== $encoded_length) {
                    throw new \RuntimeException("Erreur d'écriture du fichier temporaire");
                }

                if (!$this->verifyFileIntegrity($temp_file, $encoded_data)) {
                    throw new \RuntimeException("L'intégrité du fichier n'a pas pu être vérifiée");
                }

                chmod($temp_file, 0644);

                if ($this->atomicMove($temp_file, $this->db)) {
                    return;
                }

                error_log('Échec sauvegarde : déplacement atomique échoué (tentative ' . ($attempt + 1) . ')');
            } catch (\Exception $e) {
                error_log('Erreur de sauvegarde : ' . $e->getMessage());
            } finally {
                if (file_exists($temp_file)) {
                    @unlink($temp_file);
                }
            }

            usleep(pow(2, $attempt) * 250000);
        }

        throw new \RuntimeException('Échec de sauvegarde après ' . $max_attempts . ' tentatives');
    }

    protected function verifyFileIntegrity($filename, $expected_content)
    {
        $actual_content = file_get_contents($filename);
        return $actual_content === $expected_content;
    }

    protected function atomicMove($temp_file, $target_file)
    {
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            if (file_exists($target_file)) {
                @chmod($target_file, 0666);
            }
            if (copy($temp_file, $target_file)) {
                return unlink($temp_file);
            }
        } else {
            return rename($temp_file, $target_file);
        }
        return false;
    }

    protected function isCompressed($data)
    {
        return substr($data, 0, 2) === "\x78\x9c" || substr($data, 0, 2) === "\x78\xda" || substr($data, 0, 2) === "\x78\x5e";
    }

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

    protected function cleanupOldBackups()
    {
        $backup_pattern = $this->config['dir'] . DIRECTORY_SEPARATOR . $this->config['name'] . '.backup.*';
        $backups = glob($backup_pattern);

        if (count($backups) > 5) {
            usort($backups, function ($a, $b) {
                return filemtime($b) - filemtime($a);
            });

            $to_delete = array_slice($backups, 5);
            foreach ($to_delete as $old_backup) {
                @unlink($old_backup);
            }
        }
    }

    protected function encryptData($data, $key)
    {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }

    protected function decryptData($encryptedData, $key)
    {
        $data = base64_decode($encryptedData);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * --- OPTIMISATION B : Génération de clé de cache améliorée ---
     * Évite le coût du MD5 pour les clés simples.
     */
    protected function generateCacheKey($key, $asObject)
    {
        $suffix = $asObject ? '_obj' : '_arr';
        
        // Si c'est une chaîne simple et courte, on l'utilise directement (plus rapide que MD5)
        if (is_string($key) && strlen($key) < 64) {
            return $key . $suffix;
        }

        // Sinon (tableau ou chaîne très longue), on hache pour garder une clé courte
        return md5((is_array($key) ? implode('.', $key) : (string) $key) . $suffix);
    }

    protected function isCachedWithTags($cacheKey, $tags)
    {
        if (!isset($this->cache[$cacheKey])) {
            return false;
        }

        $entry = $this->cache[$cacheKey];

        if (time() >= $entry['expires']) {
            unset($this->cache[$cacheKey]);
            return false;
        }

        if (!empty($tags)) {
            $entryTags = $entry['tags'] ?? [];
            if (empty(array_intersect($tags, $entryTags))) {
                return false;
            }
        }

        return true;
    }

    protected function minifyJson($json)
    {
        return preg_replace(['/\s+/', '/^\s+|\s+$/'], [' ', ''], $json);
    }

    public function applyConfigChanges($newConfig)
    {
        $configChanged = false;

        if (
            $this->config['compression'] !== ($newConfig['compression'] ?? $this->config['compression']) ||
            $this->config['encryption'] !== ($newConfig['encryption'] ?? $this->config['encryption']) ||
            $this->config['encryptionKey'] !== ($newConfig['encryptionKey'] ?? $this->config['encryptionKey']) ||
            $this->config['cache'] !== ($newConfig['cache'] ?? $this->config['cache']) ||
            $this->config['cacheTTL'] !== ($newConfig['cacheTTL'] ?? $this->config['cacheTTL']) ||
            $this->config['minify'] !== ($newConfig['minify'] ?? $this->config['minify'])
        ) {
            $configChanged = true;
        }

        if ($configChanged) {
            $this->reload(true);
            $this->config = array_merge($this->config, $newConfig);
            $this->save();
        }
    }

    public function updateConfig($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    public function getDetailedCacheStats()
    {
        $total = count($this->cache);
        $valid = 0;
        $expired = 0;
        $totalHits = 0;
        $now = time();
        $memoryUsage = 0;

        foreach ($this->cache as $key => $entry) {
            if ($now < $entry['expires']) {
                $valid++;
            } else {
                $expired++;
            }
            $totalHits += $entry['hits'] ?? 0;
            $memoryUsage += strlen(serialize($entry));
        }

        return [
            'enabled' => $this->cacheEnabled,
            'ttl' => $this->cacheTTL,
            'total' => $total,
            'valid' => $valid,
            'expired' => $expired,
            'total_hits' => $totalHits,
            'average_hits' => $total > 0 ? round($totalHits / $total, 2) : 0,
            'memory_usage' => $memoryUsage,
            'memory_usage_mb' => round($memoryUsage / 1024 / 1024, 2)
        ];
    }

    public function clearCache($key = null)
    {
        if ($key === null) {
            $this->cache = [];
        } else {
            $cacheKey = $this->generateCacheKey($key, false);
            if (isset($this->cache[$cacheKey])) {
                unset($this->cache[$cacheKey]);
            }
            $cacheKeyObj = $this->generateCacheKey($key, true);
            if (isset($this->cache[$cacheKeyObj])) {
                unset($this->cache[$cacheKeyObj]);
            }
        }
        return $this;
    }

    public function reload($clearCache = false)
    {
        if ($clearCache) {
            $this->clearCache();
        }
        $this->loadData(true);
        return $this;
    }

    public function enableCache($enabled = true)
    {
        $this->cacheEnabled = $enabled;
        return $this;
    }

    public function setCacheTTL($seconds)
    {
        $this->cacheTTL = max(1, (int) $seconds);
        return $this;
    }

    public function cleanExpiredCache()
    {
        if (!$this->cacheEnabled || empty($this->cache)) {
            return 0;
        }

        $count = 0;
        $now = time();

        foreach ($this->cache as $key => $entry) {
            if ($now >= $entry['expires']) {
                unset($this->cache[$key]);
                $count++;
            }
        }

        return $count;
    }
}