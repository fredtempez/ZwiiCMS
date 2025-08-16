<?php
// Chemin vers le fichier journal.log
$logPath = self::DATA_DIR . 'journal.log';
$entries = []; // Tableau pour stocker les entrées du journal
$users = []; // Tableau pour stocker les utilisateurs uniques

// Limite des pages à afficher, récupérée depuis la configuration ou par défaut à 20
$limitePages = $this->getData(['module', $this->getUrl(0), 'limite']) ?? 20;

// Filtres utilisateur définis dans la configuration (admin, visitor, tous)
$filtersEnabled = $this->getData(['module', $this->getUrl(0), 'filtres']) ?? ['admin', 'visitor', 'tous'];

// Vérifie si le fichier journal.log existe
if (is_file($logPath)) {
    // Lit les lignes du fichier journal.log en ignorant les lignes vides
    $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $pagesSeen = []; // Tableau pour suivre les pages déjà vues et éviter les doublons

    // Parcourt les lignes du fichier à l'envers pour commencer par les plus récentes
    foreach (array_reverse($lines) as $line) {
        // Divise chaque ligne en parties en utilisant le délimiteur ";"
        $parts = explode(';', $line);

        // Vérifie que la ligne a le bon format (5 parties)
        if (count($parts) === 5) {
            list($dateRaw, $time, $ip, $user, $page) = $parts;

            // Ignore les doublons de page
            if (isset($pagesSeen[$page])) {
                continue;
            }

            // Reformate la date de AAAAMMJJ à JJ/MM/AAAA
            $date = substr($dateRaw, 6, 2) . '/' . substr($dateRaw, 4, 2) . '/' . substr($dateRaw, 0, 4);

            // Ajoute l'entrée au tableau des entrées
            $entries[] = [
                'date' => $date,
                'time' => $time,
                'ip' => $ip,
                'user' => $user,
                'page' => $page
            ];

            // Normalisation de l'utilisateur pour la comparaison
            $lowerUser = strtolower($user);
            $addUser = false;

            // Vérifie si l'utilisateur doit être ajouté en fonction des filtres activés
            if (in_array('admin', $filtersEnabled) && $lowerUser === 'admin') {
                $addUser = true;
            } elseif (in_array('visitor', $filtersEnabled) && $lowerUser === 'visiteur') {
                $addUser = true;
            } elseif (in_array('tous', $filtersEnabled) && $lowerUser !== 'admin' && $lowerUser !== 'visiteur') {
                $addUser = true;
            }

            // Ajoute l'utilisateur au tableau des utilisateurs si nécessaire
            if ($addUser) {
                $users[$lowerUser] = $user;
            }

            // Marque la page comme vue
            $pagesSeen[$page] = true;

            // Arrête la boucle si le nombre maximum d'entrées est atteint
            if (count($entries) >= $limitePages) {
                break;
            }
        }
    }
}

// Utilisateur sélectionné dans le formulaire, par défaut 'admin'
$defaultUser = $users['admin'] ?? '';
$filterUser = $_POST['filtreUtilisateur'] ?? $defaultUser;

// Filtre les entrées en fonction de l'utilisateur sélectionné
$filteredEntries = $filterUser
    ? array_filter($entries, fn($e) => $e['user'] === $filterUser)
    : $entries;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal des éditions</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="row">
        <div class="col12">
            <h1>Journal des éditions (<?= $limitePages ?> pages différentes)</h1>
            <?php if (!empty($users)): ?>
                <!-- Formulaire pour filtrer les entrées par utilisateur -->
                <form method="post" style="margin-bottom: 20px;">
                    <label for="filtreUtilisateur">Filtrer par utilisateur :</label>
                    <select name="filtreUtilisateur" id="filtreUtilisateur" onchange="this.form.submit()">
                        <?php
                        // Ordre d'affichage : admin, visiteur, puis autres
                        $priorite = ['admin', 'visiteur'];
                        uksort($users, function ($a, $b) use ($priorite) {
                            $pa = array_search($a, $priorite);
                            $pb = array_search($b, $priorite);
                            $pa = $pa === false ? PHP_INT_MAX : $pa;
                            $pb = $pb === false ? PHP_INT_MAX : $pb;
                            return $pa <=> $pb ?: strcasecmp($a, $b);
                        });

                        // Affiche les options pour chaque utilisateur
                        foreach ($users as $key => $displayName): ?>
                            <option value="<?= htmlspecialchars($displayName) ?>" <?= ($filterUser === $displayName ? 'selected' : '') ?>>
                                <?= htmlspecialchars(ucfirst($displayName)) ?>
                            </option>
                        <?php endforeach; ?>
                        <?php if (in_array('tous', $filtersEnabled)): ?>
                            <option value="" <?= ($filterUser === '' ? 'selected' : '') ?>>Tous</option>
                        <?php endif; ?>
                    </select>
                    <noscript><input type="submit" value="Filtrer"></noscript>
                </form>
            <?php endif; ?>

            <!-- Affiche les entrées filtrées ou un message si aucune entrée n'est trouvée -->
            <?php if (empty($filteredEntries)): ?>
                <p>Aucune édition pour cet utilisateur</p>
            <?php else: ?>
                <?php foreach ($filteredEntries as $e): ?>
                    <div class="log-entry">
                        <div class="meta">
                            🕒 <?= htmlspecialchars($e['date']) ?> à <?= htmlspecialchars($e['time']) ?> — 👤 <?= htmlspecialchars($e['user']) ?>
                        </div>
                        <?php $pageUrl = explode('/', $e['page'])[0]; ?>
                        <div class="page">
                            📄 <a href="<?= helper::baseUrl() . $pageUrl ?>">
                                <?= htmlspecialchars($e['page']) ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>