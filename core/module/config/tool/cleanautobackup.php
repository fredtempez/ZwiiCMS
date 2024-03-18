<?php

/*
Ce script PHP est conçu pour supprimer les fichiers ayant l'extension 'tar.gz' dans un répertoire de sauvegarde si leur dernière modification remonte à un certain nombre de jours spécifié via une requête HTTP GET.

Exemple d'appel dans une URL avec le nombre de jours spécifié :
http://example.com/chemin/vers/script.php?days=7&key=your_secret_key

Le script vérifie également la présence et la validité d'une clé spécifique pour déclencher son exécution. La clé doit être fournie en tant que paramètre "key" dans l'URL et correspondre à celle stockée dans le fichier "data.key" pour que la suppression des fichiers soit autorisée. Si la clé est invalide ou absente, le script affiche un message d'erreur et termine son exécution.

*/

// Vérification de la clé
if (isset ($_GET['key'])) {
    // Récupération de la clé fournie en GET
    $key = $_GET['key'];

    // Récupération de la clé stockée dans le fichier data.key
    $storedKey = file_get_contents('data.key');

    // Vérification de correspondance entre les clés
    if ($key !== $storedKey) {
        http_response_code(401);
        exit();
    }
} else {

    // Récupère le nombre de jours à partir de la variable GET 'days'
    $days = isset ($_GET['days']) ? (int) $_GET['days'] : 1; // Par défaut à 1 si non spécifié

    // Chemin vers le répertoire contenant les fichiers
    $directory = '../../../../site/backup/'; // Remplacez par le chemin réel

    // Convertit le nombre de jours en secondes
    $timeLimit = strtotime("-$days days");

    // Crée un nouvel objet DirectoryIterator
    foreach (new DirectoryIterator($directory) as $file) {
        // Vérifie si l'élément courant est un fichier et a l'extension 'tar.gz'
        if ($file->isFile() && $file->getExtension() === 'tar.gz') {
            // Vérifie si le fichier a été modifié avant la limite de temps
            if ($file->getMTime() < $timeLimit) {
                // Supprime le fichier
                unlink($file->getRealPath());
            }
        }
    }
    // Si la clé est manquante, affiche un message d'erreur et arrête l'exécution du script
    http_response_code(201);
}