<?php

/**
 * Vérification de la version de PHP
 */
if (version_compare(PHP_VERSION, '7.2.0', '<')) {
    displayErrorPage('PHP 7.2+ mini requis - PHP 7.2+ mini required');
}

if (version_compare(PHP_VERSION, '8.3.999', '>')) {
    displayErrorPage('PHP 8.3 pas encore supporté, installez PHP 7.n ou PHP 8.1.n - PHP 8.3 not yet supported, install PHP 7.n or PHP 8.1.n');
}

/**
 * Check les modules installés
 */
$e = [
    'gd',
    'json',
    'date',
    'mbstring',
    'zip',
    'intl',
    'exif',
    'Phar',
    'fileinfo',
    'session'
];
$m = get_loaded_extensions();
$b = false;
$missingModules = [];
foreach ($e as $k => $v) {
    if (array_search($v, $m) === false) {
        $b = true;
        $missingModules[] = $v;
    }
}
if ($b) {
    $errorMessage = 'ZwiiCMS ne peut pas démarrer ; les modules PHP suivants sont manquants : ' . implode(', ', $missingModules) . '<br />';
    $errorMessage .= 'ZwiiCMS cannot start, the following PHP modules are missing: ' . implode(', ', $missingModules);
    displayErrorPage($errorMessage);
}

/**
 * Contrôle les htaccess
 */
$d = [
    '',
    'site/data/',
    'site/backup/',
    'site/tmp/',
    // 'site/i18n/', pas contrôler pour éviter les pbs de mise à jour
];
foreach ($d as $key) {
    if (file_exists($key . '.htaccess') === false) {
        $errorMessage = 'ZwiiCMS ne peut pas démarrer, le fichier ' . $key . '.htaccess est manquant.<br />';
        $errorMessage .= 'ZwiiCMS cannot start, file ' . $key . '.htaccess is missing.';
        displayErrorPage($errorMessage);
    }
}


/**
 * Fonction pour afficher une page d'erreur stylisée
 */
function displayErrorPage($message)
{
    echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur - ZwiiCMS</title>
    <link rel="stylesheet" href="core\layout\error.css">
</head>
<body>
    <div class="error-container">
        <h1>Erreur</h1>
        <p>' . $message . '</p>
    </div>
</body>
</html>';
    exit;
}
