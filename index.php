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
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */
ini_set('session.use_trans_sid', FALSE); $siteId = md5($_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_FILENAME']); session_name('zwii_session_' . $siteId); $scriptPath = dirname($_SERVER['SCRIPT_NAME']); if ($scriptPath === '/' || $scriptPath === '\\' || $scriptPath === '.') { $scriptPath = '/'; } session_set_cookie_params([ 'lifetime' => 0, 'path' => $scriptPath, 'domain' => $_SERVER['SERVER_NAME'], 'secure' => isset($_SERVER['HTTPS']), 'httponly' => true, 'samesite' => 'Lax' ]); session_start(); if (!headers_sent()) { header('X-Frame-Options: SAMEORIGIN'); header('X-Content-Type-Options: nosniff'); header('Referrer-Policy: strict-origin-when-cross-origin'); header( "Content-Security-Policy: " . "default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:; " . "img-src 'self' data: blob: https:; " . "font-src 'self' data: https:; " . "object-src 'none'; " . "base-uri 'self'; " . "form-action 'self'; " . "frame-ancestors 'self'" ); } include_once('core/include/checkup.php'); include_once('core/include/pre-update.php'); date_default_timezone_set('Europe/Paris'); setlocale (LC_ALL, 'fr_FR.UTF8', 'fr_FR', 'french'); require 'core/class/autoloader.php'; Autoloader::register(); Autoloader::preloadEssentialClasses(); $core = new Router(); echo $core->router();
