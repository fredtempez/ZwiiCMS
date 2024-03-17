<?php
// Récupère le nombre de jours à partir de la variable GET 'days'
$days = isset($_GET['days']) ? (int)$_GET['days'] : 1; // Par défaut à 1 si non spécifié

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
?>
