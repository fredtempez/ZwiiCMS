<?php
// Récupération des données transmises par le contrôleur
$data = journal::$datas;
$limitePages = $data['limitePages'] ?? 20;
$users = $data['users'] ?? [];
$filterUser = $data['filterUser'] ?? '';
$filteredEntries = $data['filteredEntries'] ?? [];
$filtersEnabled = $data['filtersEnabled'] ?? [];
?>

<div class="row">
    <div class="col12">
        <h2><?= $limitePages ?> pages différentes dans le journal</h2>
        <?php if (!empty($users)): ?>
            <!-- Formulaire pour filtrer les entrées par utilisateur -->
            <form method="post" style="margin-bottom: 20px;">
                <label for="filtreUtilisateur">Filtrer par utilisateur :</label>
                <select name="filtreUtilisateur" id="filtreUtilisateur" onchange="this.form.submit()">
                    <?php foreach ($users as $key => $displayName): ?>
                        <option value="<?= helper::filter($displayName, helper::FILTER_STRING_SHORT) ?>" <?= ($filterUser === $displayName ? 'selected' : '') ?>>
                            <?= helper::filter(ucfirst($displayName), helper::FILTER_STRING_SHORT) ?>
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
            <?php foreach ($filteredEntries as $entry): ?>
                <div class="log-entry">
                    <div class="meta">
                        🕒 <?= helper::filter($entry['date'], helper::FILTER_STRING_SHORT) ?> à 
                        <?= helper::filter($entry['time'], helper::FILTER_STRING_SHORT) ?> — 
                        👤 <?= helper::filter($entry['user'], helper::FILTER_STRING_SHORT) ?>
                    </div>
                    <?php 
                    $pageUrl = explode('/', $entry['page'])[0];
                    $pageUrl = helper::filter($pageUrl, helper::FILTER_STRING_SHORT);
                    ?>
                    <div class="page">
                        📄 <a href="<?= helper::baseUrl() . $pageUrl ?>">
                            <?= helper::filter($entry['page'], helper::FILTER_STRING_SHORT) ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>