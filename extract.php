<?php
// Chemin vers le dossier principal contenant les scripts PHP
$rootDir = './core';

// Fonction pour parcourir récursivement les fichiers et sous-dossiers
function processDirectory($dir, &$translations) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            processDirectory($path, $translations);
        } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            extractTranslationsFromFile($path, $translations);
        }
    }
}

// Fonction pour extraire les traductions du fichier
function extractTranslationsFromFile($file, &$translations) {
    $content = file_get_contents($file);
    $pattern = "/helper::translate\s*\(\s*['\"](.*?)['\"]\s*\)/";
    preg_match_all($pattern, $content, $matches);

    if (!empty($matches[1])) {
        foreach ($matches[1] as $translation) {
            $unescapedTranslation = stripslashes($translation);
            $translations[] = $unescapedTranslation;
        }
    }
}

// Tableau pour stocker les traductions extraites
$translations = array();

// Traitement des fichiers dans le dossier principal
processDirectory($rootDir, $translations);

// Création du fichier JSON avec les traductions
$jsonFilePath = './translations.json';
$jsonContent = json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
file_put_contents($jsonFilePath, $jsonContent);

echo "Les traductions ont été extraites et sauvegardées dans : $jsonFilePath";
?>
