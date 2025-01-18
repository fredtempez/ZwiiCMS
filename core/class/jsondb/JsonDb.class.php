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
    // Tentative d'écriture après échec
    const MAX_FILE_WRITE_ATTEMPTS = 5;
    // Délais entre deux tentaives
    const RETRY_DELAY_SECONDS = 1;

    public function __construct($config = [])
    {
        $this->config = array_merge([
            'name' => 'data.json',
            'backup' => false,
            'dir' => getcwd()
        ], $config);
        $this->loadData();
        parent::__construct();
    }

    /**
     * Reload data from file
     * @return $this
     */
    public function reload()
    {
        $this->loadData(true);
        return $this;
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
        if ($save)
            $this->save();
        return $this;
    }

    /**
     * Delete path or array of paths
     *
     * @param mixed     $key Path or array of paths to delete
     * @param bool      $save Save data to database
     * @return $thisurn $this
     */
    public function delete($key, $save = true)
    {
        parent::delete($key);
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
        if ($save)
            $this->save();
        return $this;
    }


    /**
     * Local database upload
     * @param bool $reload Reboot data?
     * @return array|mixed|null
     */
    protected function loadData($reload = false)
    {
        if ($this->data === null || $reload) {
            $this->db = $this->config['dir'] . $this->config['name'];
            if (!file_exists($this->db)) {
                return null; // Rebuild database manage by CMS
            } else {
                if ($this->config['backup']) {
                    try {
                        copy($this->config['dir'] . DIRECTORY_SEPARATOR . $this->config['name'], $this->config['dir'] . DIRECTORY_SEPARATOR . $this->config['name'] . '.backup');
                    } catch (\Exception $e) {
                        error_log('Erreur de chargement : ' . $e);
                        exit('Erreur de chargement : ' . $e);
                    }
                }
            }
            $this->data = json_decode(file_get_contents($this->db), true);
            if (!$this->data === null) {
                throw new \InvalidArgumentException('Le fichier ' . $this->db
                . ' contient des données invalides.');
            }
        }
        return $this->data;
    }

    /**
     * Save database
     */
    public function save()
    {
        // Encode les données au format JSON avec les options spécifiées
        //$encoded_data = json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
        $encoded_data = json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);

        // Vérifie la longueur de la chaîne JSON encodée
        $encoded_length = strlen($encoded_data);

        // Initialise le compteur de tentatives
        $attempt = 0;

        // Tente d'encoder les données en JSON et de les sauvegarder jusqu'à 5 fois en cas d'échec
        while ($attempt < 5) {
            // Essaye d'écrire les données encodées dans le fichier de base de données
            $write_result = file_put_contents($this->db, $encoded_data, LOCK_EX); // Les utilisateurs multiples obtiennent un verrou

            //$now = \DateTime::createFromFormat('U.u', microtime(true));
            //file_put_contents("tmplog.txt", '[JsonDb][' . $now->format('H:i:s.u') . ']--' . $this->db . "\r\n", FILE_APPEND);

            // Vérifie si l'écriture a réussi
            if ($write_result === $encoded_length) {
                // Sort de la boucle si l'écriture a réussi
                break;
            }

            // Incrémente le compteur de tentatives
            $attempt++;

            // Attente 1/4 de seconde
            usleep(0.25);
        }

        // Vérifie si l'écriture a échoué même après plusieurs tentatives
        if ($write_result !== $encoded_length) {
            // Enregistre un message d'erreur dans le journal des erreurs
            error_log('Erreur d\'écriture, les données n\'ont pas été sauvegardées.');

            // Affiche un message d'erreur et termine le script
            exit('Erreur d\'écriture, les données n\'ont pas été sauvegardées.');
        }
    }
}