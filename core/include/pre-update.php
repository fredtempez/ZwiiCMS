<?php

/**
 * Mise à jour avant v12
 * */

if (file_exists('site/data/core.json')) {
    $version = json_decode(file_get_contents('site/data/core.json'), true);

    // Avant version 12.0.00
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
                $end = rename('site/data/' . $key, 'site/data/' . $value);
            }
        }
        sleep(1);
    }

    // Renomme les bases de données
    if ($version['core']['dataVersion'] < 12400) {
        if (file_exists('core/module/install/ressource/i18n/languages.json'))
            rename('core/module/install/ressource/i18n/languages.json', 'core/module/install/ressource/i18n/language.json');
        if (file_exists('site/data/languages.json'))
            rename('site/data/languages.json', 'site/data/language.json');
        if (file_exists('site/data/fonts.json'))
            rename('site/data/fonts.json', 'site/data/font.json');
    }

}