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
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

 /**
 * Initialisation de Zwii
 */
// Remplace la directive htaccess
ini_set('session.use_trans_sid', FALSE);
// Démarre la session
session_start();

// Contrôle des conditions de fonctionnement
include_once('core/include/checkup.php');

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
