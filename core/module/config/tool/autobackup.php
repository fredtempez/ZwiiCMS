<?php

/*
Ce script PHP est conçu pour être appelé via une requête HTTP GET avec une clé spécifique pour déclencher la création d'une archive ZIP de sauvegarde.

Exemple d'appel dans une URL :
http://example.com/chemin/vers/autobackup.php?key=your_secret_key

La clé doit être fournie en tant que paramètre "key" dans l'URL et correspondre à celle stockée dans le fichier "data.key" pour que la création de l'archive soit autorisée. Si la clé est valide, le script parcourt le répertoire spécifié et ajoute les fichiers à l'archive ZIP. Si la clé est invalide ou absente, le script affiche un message d'erreur et termine son exécution.

*/

// Vérification de la clé
if (isset ($_GET['key'])) {
    $key = $_GET['key'];
    $storedKey = file_get_contents('data.key');
    if ($key !== $storedKey) {
        http_response_code(401);
        exit();
    }
    // Création du ZIP
    $filter = ['backup', 'tmp'];
    $fileName = date('Y-m-d-H-i-s', time()) . '-rolling-backup.zip';
    $zip = new ZipArchive();
    $zip->open('../../../../site/backup/' . $fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $directory = '../../../../site';
    $files = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator(
                $directory,
                RecursiveDirectoryIterator::SKIP_DOTS
            ),
            function ($fileInfo, $key, $iterator) use ($filter) {
                return $fileInfo->isFile() || !in_array($fileInfo->getBaseName(), $filter);
            }
        )
    );
    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen(realpath($directory)) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    http_response_code(201);
}