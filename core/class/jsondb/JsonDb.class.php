<?php

namespace Prowebcraft;

/**
 * JsonDb - Flat file JSON Database
 * Optimized Version:
 * - Removed internal caching (using direct memory access)
 * - Batch saving support (autoSave toggle)
 * - Secure atomic writes
 */
class JsonDb extends \Prowebcraft\Dot
{
    protected $db = '';
    protected $data = null;
    protected $config = [];
    protected $needsRecompression = false;

    // Active la sauvegarde automatique par défaut (true pour rétrocompatibilité)
    protected $autoSave = true;

    // Constants
    const MAX_FILE_WRITE_ATTEMPTS = 5;
    const RETRY_DELAY_SECONDS = 1;

    public function __construct($config = [])
    {
        $this->config = array_merge([
            'name' => 'data.json',
            'backup' => false,
            'dir' => getcwd(),
            'compression' => false, // Compression GZIP
            'encryption' => false,  // Chiffrement OpenSSL
            'encryptionKey' => null,
            'minify' => false       // Minification JSON
        ], $config);

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

    // --- MÉTHODES D'ÉCRITURE OPTIMISÉES ---

    /**
     * @param mixed $key
     * @param mixed $value
     * @param bool|null $save null = auto, true = force, false = defer
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
     * @param mixed $key
     * @param mixed $value
     * @param bool $pop
     * @param bool|null $save null = auto, true = force, false = defer
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
     * @param mixed $key
     * @param bool|null $save null = auto, true = force, false = defer
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
     * @param mixed $key
     * @param bool $format
     * @param bool|null $save null = auto, true = force, false = defer
     */
    public function clear($key = null, $format = false, $save = null)
    {
        parent::clear($key, $format);

        if ($this->shouldSave($save)) {
            $this->save();
        }
        return $this;
    }

    // --- GESTION DES FICHIERS ET SAUVEGARDE ---

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

            // Gestion de la compression
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
                            // Échec silencieux, contenu non compressé
                        }
                    }
                }
            }

            $this->data = json_decode($file_contents, true);

            // Validation des données
            if ($this->data === null) {
                $file_contents = trim($file_contents);
                if (empty($file_contents)) {
                   $this->data = [];
                } else {
                   throw new \InvalidArgumentException('Le fichier ' . $this->db . ' contient des données invalides.');
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
        }

        if ($this->data !== null && !is_array($this->data)) {
            throw new \InvalidArgumentException('Type de données invalide dans ' . $this->db);
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

    public function updateConfig($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Recharger les données depuis le disque
     */
    public function reload()
    {
        $this->loadData(true);
        return $this;
    }
}