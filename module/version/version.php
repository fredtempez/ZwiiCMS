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

	const VERSION = '1.2';
	const REALNAME = 'Version';
	const DELETE = true;
	const UPDATE = '0.0';
	const DATADIRECTORY = ''; // Contenu localisé inclus par défaut (page.json et module.json)

	public static $actions = [
		'index'=> self::ROLE_VISITOR

	];

	/**
	 * Retourne le numéro de version
	 */

	 public function index() {
		header('Content-Type: application/json');
		echo(json_encode(common::ZWII_VERSION));
		exit;
	 }

}
