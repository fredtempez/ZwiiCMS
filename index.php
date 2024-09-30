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
 * @copyright Copyright (C) 2018-2024, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

 /**
 * Initialisation de Zwii
 */
// Remplace la directive htaccess
ini_set('session.use_trans_sid', FALSE);

// Crée un identifiant unique pour chaque site en fonction du nom de domaine ou autre
$siteId = md5($_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_FILENAME']); // Ou utilise un autre identifiant unique pour chaque site
// Change le nom de la session en fonction de cet identifiant
session_name('zwii_session_' . $siteId);

// Récupère dynamiquement le chemin du dossier dans lequel le script est exécuté
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);

// Si le chemin est vide (ce qui peut arriver si le site est à la racine), définis-le comme '/'
if ($scriptPath === '/' || $scriptPath === '\\' || $scriptPath === '.') {
    $scriptPath = '/';
}

// Définissez le chemin du cookie de session dynamiquement
session_set_cookie_params([
    'lifetime' => 0,
    'path' => $scriptPath, // Utilise le chemin du script pour restreindre la session à ce répertoire
    'domain' => $_SERVER['SERVER_NAME'], // Domaine par défaut
    'secure' => isset($_SERVER['HTTPS']), // Pour HTTPS, si nécessaire
    'httponly' => true,
    'samesite' => 'Lax' // Ou 'Strict' ou 'None' selon tes besoins
]);


// Démarre la session
session_start();

// Contrôle des conditions de fonctionnement
include_once('core/include/checkup.php');

// Mise à jour du système de BDD
include_once('core/include/pre-update.php');

/*
 *Localisation par défaut 

 * Locales :
 * french : free.fr
 * fr_FR : XAMPP Macos
 * fr_FR.utf8 : la majorité
*/
date_default_timezone_set('Europe/Paris');
setlocale (LC_ALL, 'fr_FR.UTF8', 'fr_FR', 'french');

/**
 * Chargement des classes
 */
require 'core/class/autoload.php';
autoload::autoloader();

/**
 * Chargement du coeur
 */
$core = new core;
spl_autoload_register('core::autoload');
echo $core->router();

