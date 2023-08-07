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

    /**
     * Dashboard
     */
    public function index()
    {
        // Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Tableau de bord'),
			'view' => 'index'
		]);
    }

}