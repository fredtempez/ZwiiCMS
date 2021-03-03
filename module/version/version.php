<?php

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @license GNU General Public License, version 3
 * @author  :  Frédéric Tempez <frederic.tempez@outlook.com> * 
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @link http://zwiicms.com/
 */

class version extends common {

	public static $actions = [
		'index'=> self::GROUP_VISITOR

	];
	
	/**
	 * Retourne le numéro de version
	 */

	 public function index() {
		exit( common::ZWII_VERSION);
	 }	

}
