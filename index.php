<?php

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

 /**
 * Initialisation de Zwii
 */
session_start();

/**
 * Vérification de la version de PHP
 */
if(version_compare(PHP_VERSION, '5.6.0', '<')) {
	exit('PHP 5.6+ required.');
}

/*
 *Localisation

 * Locales :
 * french : free.fr
 * fr_FR : XAMPP Macos
 * fr_FR.utf8 : la majorité
*/
date_default_timezone_set('Europe/Paris');
setlocale (LC_ALL,'french','fr_Fr','fr_FR.utf8');


/**
 * Chargement des classes
 */
require 'core/class/autoload.php';
autoload::autoloader();

/**
 * Chargement du coeur
 */
require 'core/core.php';
$core = new core;
spl_autoload_register('core::autoload');
echo $core->router();
