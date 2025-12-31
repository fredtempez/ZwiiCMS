<?php

namespace Prowebcraft;

/**
 * Improved JsonDb with enhanced caching, performance and security features
 */
class JsonDb extends \Prowebcraft\Dot
{
    protected $db = '';
    protected $data = null;
    protected $config = [];
    protected $cache = [];
    protected $cacheEnabled = false;
    protected $cacheTTL = 3600;
    protected $cacheTags = []; // Nouveau: système de tags pour le cache
    
    // Constants
    const MAX_FILE_WRITE_ATTEMPTS = 5;
    const RETRY_DELAY_SECONDS = 1;
    const MAX_CACHE_SIZE = 1000; // Limite de taille du cache
    const CACHE_CLEANUP_PROBABILITY = 100; // 1% de chance de nettoyage

    public function __construct($config = [])
    {
        $this->config = array_merge([
            'name' => 'data.json',
            'backup' => false,
            'dir' => getcwd(),
            'cache' => false,
            'cacheTTL' => 3600,
            'compression' => false, // Nouveau: compression des données
            'encryption' => false,  // Nouveau: chiffrement optionnel
            'encryptionKey' => null
        ], $config);
        
        $this->cacheEnabled = $this->config['cache'] ?? false;
        $this->cacheTTL = $this->config['cacheTTL'] ?? 3600;
        
        $this->loadData();
        parent::__construct();
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
        
        // Vérifier le cache avec tags valides
        if ($this->isCachedWithTags($cacheKey, $tags)) {
            $this->cache[$cacheKey]['hits']++;
            return $this->cache[$cacheKey]['value'];
        }
        
        $value = parent::get($key, $default, $asObject);
        
        // Stocker avec tags et métadonnées
        $this->cache[$cacheKey] = [
            'value' => $value,
            'expires' => time() + $this->cacheTTL,
            'tags' => $tags,
            'hits' => 0,
            'created' => time()
        ];
        
        // Nettoyage automatique du cache
        if (mt_rand(1, self::CACHE_CLEANUP_PROBABILITY) === 1) {
            $this->optimizeCache();
        }
        
        return $value;
    }

    /**
     * Invalider le cache par tags
     */
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

    /**
     * Optimisation du cache: suppression des entrées les moins utilisées
     */
    protected function optimizeCache()
    {
        if (count($this->cache) <= self::MAX_CACHE_SIZE) {
            return;
        }

        // Trier par nombre de hits et par date de création
        uasort($this->cache, function($a, $b) {
            if ($a['hits'] === $b['hits']) {
                return $a['created'] - $b['created'];
            }
            return $a['hits'] - $b['hits'];
        });

        // Garder seulement les 80% les plus utilisés
        $keepCount = (int)(self::MAX_CACHE_SIZE * 0.8);
        $this->cache = array_slice($this->cache, -$keepCount, null, true);
    }

    /**
     * Enhanced save with compression and encryption options
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
            // Préparation des données
            $saveData = $this->prepareDataForSave();
            
            // Encodage JSON
            $encoded_data = json_encode($saveData, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT | JSON_THROW_ON_ERROR);
            
            // Compression si activée
            if ($this->config['compression']) {
                $encoded_data = gzcompress($encoded_data, 6);
            }

        } catch (\JsonException $e) {
            throw new \RuntimeException("Erreur d'encodage JSON : " . $e->getMessage());
        }

        $this->performAtomicSave($encoded_data, $dir);
    }

    /**
     * Prépare les données pour la sauvegarde (chiffrement si nécessaire)
     */
    protected function prepareDataForSave()
    {
        $data = $this->data;
        
        if ($this->config['encryption'] && !empty($this->config['encryptionKey'])) {
            $data = [
                'encrypted' => true,
                'data' => $this->encryptData(json_encode($data), $this->config['encryptionKey'])
            ];
        }
        
        return $data;
    }

    /**
     * Enhanced loadData with decryption support
     */
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
            
            // Décompression si nécessaire
            if ($this->config['compression'] && $this->isCompressed($file_contents)) {
                $file_contents = gzuncompress($file_contents);
            }

            $this->data = json_decode($file_contents, true);

            if ($this->data === null) {
                throw new \InvalidArgumentException('Le fichier ' . $this->db . ' contient des données invalides.');
            }

            // Déchiffrement si nécessaire
            if (isset($this->data['encrypted']) && $this->data['encrypted']) {
                if (empty($this->config['encryptionKey'])) {
                    throw new \RuntimeException('Clé de chiffrement manquante');
                }
                $decrypted = $this->decryptData($this->data['data'], $this->config['encryptionKey']);
                $this->data = json_decode($decrypted, true);
            }
        }

        return $this->data;
    }

    /**
     * Atomic save implementation with better error handling
     */
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

                // Écriture atomique avec vérification
                $written = file_put_contents($temp_file, $encoded_data, LOCK_EX);
                if ($written !== $encoded_length) {
                    throw new \RuntimeException("Erreur d'écriture du fichier temporaire");
                }

                // Vérification de l'intégrité
                if (!$this->verifyFileIntegrity($temp_file, $encoded_data)) {
                    throw new \RuntimeException("L'intégrité du fichier n'a pas pu être vérifiée");
                }

                chmod($temp_file, 0644);

                // Déplacement atomique selon le système
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

    /**
     * Vérifie l'intégrité du fichier écrit
     */
    protected function verifyFileIntegrity($filename, $expected_content)
    {
        $actual_content = file_get_contents($filename);
        return $actual_content === $expected_content;
    }

    /**
     * Déplacement atomique adapté au système
     */
    protected function atomicMove($temp_file, $target_file)
    {
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            // Windows: copie + suppression
            if (file_exists($target_file)) {
                @chmod($target_file, 0666);
            }
            if (copy($temp_file, $target_file)) {
                return unlink($temp_file);
            }
        } else {
            // Unix/Linux: rename atomique
            return rename($temp_file, $target_file);
        }
        return false;
    }

    /**
     * Vérifie si les données sont compressées
     */
    protected function isCompressed($data)
    {
        return substr($data, 0, 2) === "\x1f\x8b"; // Magic number gzip
    }

    /**
     * Création de sauvegarde améliorée
     */
    protected function createBackup()
    {
        $backup_path = $this->config['dir'] . DIRECTORY_SEPARATOR . $this->config['name'] . '.backup.' . date('Y-m-d_H-i-s');
        
        try {
            if (!copy($this->db, $backup_path)) {
                throw new \RuntimeException('Échec de la création de la sauvegarde');
            }
            
            // Nettoyage des anciennes sauvegardes (garder seulement les 5 dernières)
            $this->cleanupOldBackups();
            
        } catch (\Exception $e) {
            throw new \RuntimeException('Erreur de sauvegarde : ' . $e->getMessage());
        }
    }

    /**
     * Nettoyage des anciennes sauvegardes
     */
    protected function cleanupOldBackups()
    {
        $backup_pattern = $this->config['dir'] . DIRECTORY_SEPARATOR . $this->config['name'] . '.backup.*';
        $backups = glob($backup_pattern);
        
        if (count($backups) > 5) {
            usort($backups, function($a, $b) {
                return filemtime($b) - filemtime($a);
            });
            
            $to_delete = array_slice($backups, 5);
            foreach ($to_delete as $old_backup) {
                @unlink($old_backup);
            }
        }
    }

    /**
     * Méthodes utilitaires pour le chiffrement
     */
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
     * Génération de clé de cache améliorée
     */
    protected function generateCacheKey($key, $asObject)
    {
        return md5((is_array($key) ? implode('.', $key) : (string)$key) . ($asObject ? '_obj' : '_arr'));
    }

    /**
     * Vérification du cache avec tags
     */
    protected function isCachedWithTags($cacheKey, $tags)
    {
        if (!isset($this->cache[$cacheKey])) {
            return false;
        }
        
        $entry = $this->cache[$cacheKey];
        
        // Vérifier l'expiration
        if (time() >= $entry['expires']) {
            unset($this->cache[$cacheKey]);
            return false;
        }
        
        // Vérifier si les tags sont valides (si spécifiés)
        if (!empty($tags)) {
            $entryTags = $entry['tags'] ?? [];
            if (empty(array_intersect($tags, $entryTags))) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Statistiques détaillées du cache
     */
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

    // Méthodes existantes conservées pour compatibilité...
    public function set($key, $value = null, $save = true)
    {
        parent::set($key, $value);
        
        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }
        
        if ($save) {
            $this->save();
        }
        return $this;
    }

    public function add($key, $value = null, $pop = false, $save = true)
    {
        parent::add($key, $value, $pop);
        
        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }
        
        if ($save) {
            $this->save();
        }
        return $this;
    }

    public function delete($key, $save = true)
    {
        parent::delete($key);
        
        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }
        
        if ($save) {
            $this->save();
        }
        return $this;
    }

    public function clear($key = null, $format = false, $save = true)
    {
        parent::clear($key, $format);
        
        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }
        
        if ($save) {
            $this->save();
        }
        return $this;
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
        $this->cacheTTL = max(1, (int)$seconds);
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
