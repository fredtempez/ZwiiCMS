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
           'backup' => 5,
            'dir' => getcwd(),
           'template' => getcwd() . DIRECTORY_SEPARATOR . 'data.template.json'
        ], $config);
        $this->loadData();
        parent::__construct();
    }

    /**
     * Set value or array of values to path
     *
     * @param mixed      $key   Path or array of paths and values
     * @param mixed|null $value Value to set if path is not an array
     * @param bool      $save Save data to database
     * @return $this
     */
    public function set($key, $value = null, $save = true)
    {
        parent::set($key, $value);
        if ($save) $this->save();
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
        if ($save) $this->save();
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
        if ($save) $this->save();
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
        if ($save) $this->save();
        return $this;
    }


    /**
     * Local database upload
     * @param bool $reload Reboot data?
     * @return array|mixed|null
     */
    protected function loadData($reload = false) {
        if ($this->data === null || $reload) {
            // $this->db = $this->config['dir'] . DIRECTORY_SEPARATOR . $this->config['name'];
            $this->db = $this->config['dir'] . $this->config['name'];

            if (!file_exists($this->db)) {
                return null;
            } else {
                $this->data = json_decode(file_get_contents($this->db), true);
                if (!$this->data === null) {
                    throw new \InvalidArgumentException('Erreur de lecture du fichier de données ' . $this->db);
                }
            }
        }
        return $this->data;
    }

    /**
     * Saving to local database
     */
    public function save() {
        // 3 essais
		for($i = 0; $i <=3; $i++) {
			if(file_put_contents($this->db, json_encode($this->data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|LOCK_EX)) !== false) {
				break;
			}
			// Pause de 10 millisecondes
            usleep(10000);
            if ($i === 3) {
                throw new \InvalidArgumentException('Erreur d\'écriture du fichier de données ' . $this->db);
            }
		}
    }
}
