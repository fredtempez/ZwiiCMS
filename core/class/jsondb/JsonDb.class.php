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
                        //todo make backup of database
                        copy($this->config['dir'] . DIRECTORY_SEPARATOR . $this->config['name'], $this->config['dir'] . DIRECTORY_SEPARATOR . $this->config['name'] . '.backup');
                    } catch (\Exception $e) {

                    }
                }
            }
            $this->data = json_decode(file_get_contents($this->db), true);
            if (!$this->data === null) {
                throw new \InvalidArgumentException('Database file ' . $this->db
                    . ' contains invalid json object. Please validate or remove file');
            }
        }
        return $this->data;
    }

    /**
     * Save database
     */
    public function save()
    {
        //$v = json_encode($this->data, JSON_UNESCAPED_UNICODE );
        $v = json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
        $l = strlen($v);
        $t = 0;
        while ($t < 5) {
            $w = file_put_contents($this->db, $v); // Multi user get a locker
            if ($w == $l) {
                break;
            }
            $t++;
        }
        if ($w !== $l) {
            exit('Erreur d\'écriture, les données n\'ont pas été sauvegardées');
        }

    }
}