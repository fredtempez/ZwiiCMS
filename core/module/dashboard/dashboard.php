<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class dashboard extends common
{

    public static $actions = [
        'index' => self::GROUP_ADMIN,
    ];



    public static $infos = [];

    /**
     * Dashboard
     */
    public function index()
    {

        self::$infos['webserver'] = $_SERVER['SERVER_SOFTWARE'];
        self::$infos['php']['version'] =  phpversion();
        self::$infos['php']['extension'] = get_loaded_extensions();

        self::$infos['system']['memory'] = memory_get_usage() . ' octets';
        self::$infos['system']['peek'] 	= 'Pic de mémoire utilisée : ' . memory_get_peak_usage() . ' octets';

        $loadAverage = sys_getloadavg();
        self::$infos['system']['charge'] =  'Charge moyenne (1 min / 5 min / 15 min) : ' . implode(' / ', $loadAverage) . '</P>';

        // Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Tableau de bord'),
			'view' => 'index'
		]);
    }

}