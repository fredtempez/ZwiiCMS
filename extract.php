<?php
// Charger le contenu du fichier fr_FR.json
$json_file_path = 'module/slider/i18n/fr_FR.json';
$json_data = json_decode(file_get_contents($json_file_path), true);

// Extraire les clés du tableau JSON
$json_keys = array_keys($json_data);

// Parcourir les fichiers PHP dans les deux répertoires source 'module/blog/' et 'core/'
$core_directories = ['module/slider/', 'core/'];
$php_files = [];

foreach ($core_directories as $core_directory) {
    $php_files = array_merge($php_files, get_php_files($core_directory));
}

// Fonction récursive pour récupérer les fichiers PHP dans l'arborescence et exclure les dossiers 'i18n'
function get_php_files($directory) {
    $php_files = [];
    $files = scandir($directory);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $directory . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path) && $file !== 'i18n') {
            $php_files = array_merge($php_files, get_php_files($path));
        } elseif (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $php_files[] = $path;
        }
    }
    return $php_files;
}

// Créer un tableau pour stocker les clés présentes dans les fichiers PHP
$php_keys = [];

// Rechercher les clés du tableau JSON dans les fichiers PHP et les ajouter à $php_keys
foreach ($json_keys as $key) {
    foreach ($php_files as $php_file) {
        $content = file_get_contents($php_file);
        if (strpos($content, "'{$key}'") !== false) {
            $php_keys[] = $key;
            break;
        }
    }
}

// Supprimer les clés absentes du fichier fr_FR.json
$keys_to_remove = array_diff($json_keys, $php_keys);
foreach ($keys_to_remove as $key) {
    unset($json_data[$key]);
}

// Enregistrer les modifications dans le fichier fr_FR.json
file_put_contents($json_file_path, json_encode($json_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Le fichier fr_FR.json a été mis à jour avec succès !";
