<?php

/**
 * Created by PhpStorm.
 * User: Andrey Mistulov
 * Company: Aristos
 * Date: 14.03.2017
 * Time: 15:25
 */

namespace Prowebcraft;

/**
 * Class Data
 * @package Aristos
 */
class JsonDb extends \Prowebcraft\Dot
{
    protected $db = '';
    protected $data = null;
    protected $config = [];
    protected $cache = [];
    protected $cacheEnabled = false;
    protected $cacheTTL = 3600; // Durée de vie du cache en secondes (1 heure par défaut)

    // Tentative d'écriture après échec
    const MAX_FILE_WRITE_ATTEMPTS = 5;
    // Délais entre deux tentaives
    const RETRY_DELAY_SECONDS = 1;

    public function __construct($config = [])
    {
        $this->config = array_merge([
            'name' => 'data.json',
            'backup' => false,
            'dir' => getcwd(),
            'cache' => false,
            'cacheTTL' => 3600
        ], $config);
        
        // Initialisation du cache si activé dans la configuration
        $this->cacheEnabled = $this->config['cache'] ?? false;
        $this->cacheTTL = $this->config['cacheTTL'] ?? 3600;
        
        $this->loadData();
        parent::__construct();
    }

    /**
     * Reload data from file
     * @param bool $clearCache Force la suppression du cache
     * @return $this
     */
    public function reload($clearCache = false)
    {
        if ($clearCache) {
            $this->clearCache();
        }
        $this->loadData(true);
        return $this;
    }
    
    /**
     * Active ou désactive le cache
     * @param bool $enabled État d'activation du cache
     * @return $this
     */
    public function enableCache($enabled = true)
    {
        $this->cacheEnabled = $enabled;
        return $this;
    }
    
    /**
     * Définit la durée de vie du cache
     * @param int $seconds Durée en secondes
     * @return $this
     */
    public function setCacheTTL($seconds)
    {
        $this->cacheTTL = max(1, (int)$seconds);
        return $this;
    }
    
    /**
     * Vide le cache
     * @param string|null $key Clé spécifique à vider, null pour tout vider
     * @return $this
     */
    public function clearCache($key = null)
    {
        if ($key === null) {
            $this->cache = [];
        } elseif (isset($this->cache[$key])) {
            unset($this->cache[$key]);
        }
        return $this;
    }
    
    /**
     * Vérifie si une clé est en cache et valide
     * @param string $key Clé à vérifier
     * @return bool True si la clé est en cache et valide
     */
    public function isCached($key)
    {
        if (!$this->cacheEnabled) {
            return false;
        }
        
        $cacheKey = is_array($key) ? implode('.', $key) : (string)$key;
        return isset($this->cache[$cacheKey]) && time() < $this->cache[$cacheKey]['expires'];
    }
    
    /**
     * Retourne des statistiques sur le cache
     * @return array Statistiques du cache
     */
    public function getCacheStats()
    {
        $total = count($this->cache);
        $valid = 0;
        $expired = 0;
        $now = time();
        
        foreach ($this->cache as $entry) {
            if ($now < $entry['expires']) {
                $valid++;
            } else {
                $expired++;
            }
        }
        
        return [
            'enabled' => $this->cacheEnabled,
            'ttl' => $this->cacheTTL,
            'total' => $total,
            'valid' => $valid,
            'expired' => $expired,
            'memory_usage' => $this->getMemoryUsage()
        ];
    }
    
    /**
     * Calcule l'utilisation mémoire approximative du cache
     * @return int Taille en octets
     */
    protected function getMemoryUsage()
    {
        $usage = 0;
        
        foreach ($this->cache as $key => $entry) {
            // Taille approximative de la clé
            $usage += strlen($key);
            // Taille approximative de la valeur (sérialisation pour estimation)
            $usage += strlen(serialize($entry['value']));
            // Taille du timestamp d'expiration (entier)
            $usage += 8;
        }
        
        return $usage;
    }

    /**
     * Set value or array of values to path
     *
     * @param mixed      $key   Path or array of paths and values
     * @param mixed|null $value Value to set if path is not an array
     * @param bool $save Save data to database
     * @return $this
     */
    public function set($key, $value = null, $save = true)
    {
        parent::set($key, $value);
        
        // Invalider le cache pour cette clé
        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }
        
        if ($save)
            $this->save();
        return $this;
    }

    /**
     * Add value or array of values to path
     *
     * @param mixed      $key Path or array of paths and values
     * @param mixed|null $value Value to set if path is not an array
     * @param boolean    $pop Helper to pop out last key if value is an array
     * @param bool       $save    Save data to database
     * @return $this
     */
    public function add($key, $value = null, $pop = false, $save = true)
    {
        parent::add($key, $value, $pop);
        
        // Invalider le cache pour cette clé
        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }
        
        if ($save)
            $this->save();
        return $this;
    }

    /**
     * Delete path or array of paths
     *
     * @param mixed     $key Path or array of paths to delete
     * @param bool      $save Save data to database
     * @return $this
     */
    public function delete($key, $save = true)
    {
        parent::delete($key);
        
        // Invalider le cache pour cette clé
        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }
        
        if ($save)
            $this->save();
        return $this;
    }

    /**
     * Delete all data, data from path or array of paths and
     * optionally format path if it doesn't exist
     *
     * @param mixed|null $key Path or array of paths to clean
     * @param boolean    $format Format option
     * @param bool       $save Save data to database
     * @return $this
     */
    public function clear($key = null, $format = false, $save = true)
    {
        parent::clear($key, $format);
        
        // Invalider le cache pour cette clé ou tout le cache si $key est null
        if ($this->cacheEnabled) {
            $this->clearCache($key);
        }
        
        if ($save)
            $this->save();
        return $this;
    }

    /**
     * Charge les données depuis un fichier JSON.
     *
     * @param bool $reload Force le rechargement des données si true
     *
     * @return array|null Les données chargées ou null si le fichier n'existe pas
     *
     * @throws \RuntimeException En cas d'erreur lors de la création de la sauvegarde
     * @throws \InvalidArgumentException Si le fichier contient des données JSON invalides
     */
    protected function loadData($reload = false): ?array
    {
        if ($this->data === null || $reload) {
            $this->db = $this->config['dir'] . $this->config['name'];

            if (!file_exists($this->db)) {
                return null;  // Rebuild database managed by CMS
            }

            if ($this->config['backup']) {
                $backup_path = $this->config['dir'] . DIRECTORY_SEPARATOR . $this->config['name'] . '.backup';

                try {
                    if (!copy($this->db, $backup_path)) {
                        throw new \RuntimeException('Échec de la création de la sauvegarde');
                    }
                } catch (\Exception $e) {
                    throw new \RuntimeException('Erreur de sauvegarde : ' . $e->getMessage());
                }
            }

            $file_contents = file_get_contents($this->db);

            $this->data = json_decode($file_contents, true);

            if ($this->data === null) {
                throw new \InvalidArgumentException('Le fichier ' . $this->db . ' contient des données invalides.');
            }
        }

        return $this->data;
    }
    
    /**
     * Récupère une valeur depuis le chemin spécifié avec support de cache
     *
     * @param string|array|null $key Chemin à récupérer
     * @param mixed $default Valeur par défaut si le chemin n'existe pas
     * @param bool $asObject Convertir les tableaux en objets
     * @return mixed La valeur récupérée ou la valeur par défaut
     */
    public function get($key, $default = null, $asObject = false)
    {
        // Si le cache est désactivé, utiliser la méthode parente
        if (!$this->cacheEnabled || $key === null) {
            return parent::get($key, $default, $asObject);
        }
        
        // Générer une clé de cache unique avec le flag asObject
        $cacheKey = (is_array($key) ? implode('.', $key) : (string)$key) . ($asObject ? '_obj' : '_arr');
        
        // Vérifier si la valeur est en cache et si elle est encore valide
        if (isset($this->cache[$cacheKey]) && time() < $this->cache[$cacheKey]['expires']) {
            return $this->cache[$cacheKey]['value'];
        }
        
        // Récupérer la valeur depuis la méthode parente
        $value = parent::get($key, $default, $asObject);
        
        // Stocker la valeur dans le cache
        $this->cache[$cacheKey] = [
            'value' => $value,
            'expires' => time() + $this->cacheTTL
        ];
        
        // Nettoyage automatique du cache (1% de chance)
        if (mt_rand(1, 100) === 1) {
            $this->cleanExpiredCache();
        }
        
        return $value;
    }
    
    /**
     * Nettoie les entrées expirées du cache
     * @return int Nombre d'entrées supprimées
     */
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

    /**
     * Sauvegarde les données dans un fichier JSON.
     *
     * @throws \RuntimeException En cas d'erreur lors de la sauvegarde
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
            $encoded_data = json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT | JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \RuntimeException("Erreur d'encodage JSON : " . $e->getMessage());
        }

        $encoded_length = strlen($encoded_data);
        $max_attempts = 5;

        for ($attempt = 0; $attempt < $max_attempts; $attempt++) {
            $temp_file = $dir . '/userdb_' . uniqid('', true) . '.tmp';

            try {
                if ($attempt > 0) {
                    usleep($attempt * 100000);
                }

                // Écriture du fichier temporaire
                $written = file_put_contents($temp_file, $encoded_data, LOCK_EX);
                if ($written !== $encoded_length) {
                    throw new \RuntimeException("Erreur d'écriture du fichier temporaire");
                }
                chmod($temp_file, 0644);

                // STRATÉGIE SELON LE SYSTÈME
                if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
                    // Windows : copy + unlink
                    if (file_exists($this->db)) {
                        // S'assurer que le fichier cible n'est pas en lecture seule
                        @chmod($this->db, 0666);
                    }
                    if (copy($temp_file, $this->db)) {
                        unlink($temp_file);
                        return;
                    }
                } else {
                    // Linux/Unix : rename atomique
                    if (rename($temp_file, $this->db)) {
                        return;
                    }
                }

                error_log('Échec sauvegarde : écriture ou renommage/copie échoué (tentative ' . ($attempt + 1) . ')');
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
}
