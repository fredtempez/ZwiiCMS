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
        // Renommage les fichiers de données au pluriel
        $t = [
            'site/data/languages.json' => 'site/data/language.json',
            'site/data/fonts.json' => 'site/data/font.json'
        ];
        foreach ($t as $k => $v) {
            if (file_exists($k)) {
                $d = file_get_contents($k);
                $d = str_replace(basename($k,'.json'), basename($v, '.json'), $d);
                file_put_contents($v, $d);
                unlink($k);
            }
        }
        if (file_exists('core/module/install/ressource/i18n/languages.json')) {
            unlink ('core/module/install/ressource/i18n/languages.json');
        }
    }

}