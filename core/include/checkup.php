<?php
/**
 * Vérification de la version de PHP
 */
if (version_compare(PHP_VERSION, '8.1', '<')) { displayErrorPage('PHP 8.1+ mini requis - PHP 8.1+ mini required'); } if (version_compare(PHP_VERSION, '8.5.999', '>')) { displayErrorPage('PHP 8.6 pas encore supporté, installez PHP 8.1.n - PHP 8.5.n - PHP 8.6 not yet supported, install PHP 8.1.n - PHP 8.5.n'); } $e = [ 'gd', 'json', 'date', 'mbstring', 'zip', 'intl', 'exif', 'Phar', 'fileinfo', 'session' ]; $m = get_loaded_extensions(); $b = false; $missingModules = []; foreach ($e as $k => $v) { if (array_search($v, $m) === false) { $b = true; $missingModules[] = $v; } } if ($b) { $errorMessage = 'ZwiiCMS ne peut pas démarrer ; les modules PHP suivants sont manquants : ' . implode(', ', $missingModules) . '<br />'; $errorMessage .= 'ZwiiCMS cannot start, the following PHP modules are missing: ' . implode(', ', $missingModules); displayErrorPage($errorMessage); } $d = [ '', 'site/data/', 'site/backup/', 'site/tmp/', ]; foreach ($d as $key) { if (file_exists($key . '.htaccess') === false) { $errorMessage = 'ZwiiCMS ne peut pas démarrer, le fichier ' . $key . '.htaccess est manquant.<br />'; $errorMessage .= 'ZwiiCMS cannot start, file ' . $key . '.htaccess is missing.'; displayErrorPage($errorMessage); } } function displayErrorPage($message) { echo '<!DOCTYPE html>
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
</html>'; exit; }
