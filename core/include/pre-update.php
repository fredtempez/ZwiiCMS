<?php

/**
 * Mise à jour  à partir de la version 11.5.12
 * */
$version = json_decode(file_get_contents('site/data/core.json'), true);
if ($version['core']['dataVersion'] < 12000) {
    // Correspondance pour les dossiers de langue à convertir
    $languages = [
        'fr' => 'fr_FR',
        'en' => 'en_EN',
        'pt' => 'pt_PT'
    ];
    // Convertit les dossiers vers la nouvelle structure
    foreach ($languages as $key => $value) {
        if (
            is_dir('site/data/' . $key) &&
            !is_dir('site/data/' . $value)
        ) {
            rename('site/data/' . $key, self::DATA_DIR . $value);
        }
    }
}