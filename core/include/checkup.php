<?php

/**
 * Vérification de la version de PHP
 */

if(version_compare(PHP_VERSION, '7.2.0', '<') ) {
	exit('PHP 7.2+ mini requis - PHP 7.2+ mini required');

}

if ( version_compare(PHP_VERSION, '8.1.999', '>') ) {
	exit('PHP 8.3 pas encore supporté, installez PHP 7.n ou PHP 8.1.n - PHP 8.3 not yet supported, install PHP 7.n or PHP 8.1.n');
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
foreach ($e as $k => $v) {
    if (array_search($v,$m) === false)  {
        $b = true;
        echo '<pre><p>Module ' . $v . ' manquant - Module ' . $v . ' missing.</p></pre>';
    }
}
if ($b)
    exit('<pre><p>ZwiiCMS ne peut pas démarrer ; activez les extensions requises - ZwiiCMS cannot run, enabled missing extensions.</p></pre>');
