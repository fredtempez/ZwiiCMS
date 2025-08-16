<?php echo template::formOpen('journalConfig'); ?>
<?php
// Chemin vers le fichier journal.log
$logPath = self::DATA_DIR . 'journal.log';

// Vérifie si le fichier journal.log existe
$fichierExiste = file_exists($logPath);
?>
<div class="row">
    <div class="col6">
        <!-- Affiche l'état du fichier journal.log -->
        <p>
            État du fichier journal.log :
            <strong style="color: <?= $fichierExiste ? 'green' : 'red' ?>">
                <?= $fichierExiste ? 'Présent' : 'Absent' ?>
            </strong>
        </p>
    </div>
</div>
<div class="row">
    <div class="col4">
        <!-- Champ pour l'identifiant Zwii -->
        <?php echo template::label('journalIdentifiant', 'Identifiant Zwii'); ?>
        <?php echo template::text('journalIdentifiant', [
            'value' => $this->getData(['module', $this->getUrl(0), 'identifiant']) ?? ''
        ]); ?>
    </div>
    <div class="col4">
        <!-- Champ pour le nombre de pages affichées -->
        <?php echo template::label('journalLimite', 'Nombre de pages affichées'); ?>
        <?php echo template::number('journalLimite', [
            'min' => 1, // Valeur minimale
            'max' => 50, // Valeur maximale
            'value' => $this->getData(['module', $this->getUrl(0), 'limite']) ?? 10 // Valeur par défaut
        ]); ?>
    </div>
</div>
<?php
// Récupère les filtres actuels ou utilise les valeurs par défaut
$filtresActuels = $this->getData(['module', $this->getUrl(0), 'filtres']) ?? ['admin', 'visitor', 'tous'];
?>
<div class="row">
    <div class="col4">
        <!-- Section pour les filtres d'utilisateurs -->
        <?php echo template::label('journalFiltres', 'Utilisateurs affichés dans le filtre :'); ?>
        <div style="margin-left: 1em;">
            <!-- Case à cocher pour le filtre Admin -->
            <input type="checkbox" id="adminCheck" name="journalFiltresAdmin"
                <?= in_array('admin', $filtresActuels) ? 'checked' : '' ?>>
            <label for="adminCheck">Admin</label><br>
            <!-- Case à cocher pour le filtre Visitor -->
            <input type="checkbox" id="visitorCheck" name="journalFiltresVisitor"
                <?= in_array('visitor', $filtresActuels) ? 'checked' : '' ?>>
            <label for="visitorCheck">Visitor</label><br>
            <!-- Case à cocher pour le filtre Tous -->
            <input type="checkbox" id="tousCheck" name="journalFiltresTous"
                <?= in_array('tous', $filtresActuels) ? 'checked' : '' ?>>
            <label for="tousCheck">Tous</label><br>
        </div>
    </div>
</div>
<!-- Bouton pour enregistrer la configuration -->
<?php echo template::submit('journalSubmit', ['value' => 'Enregistrer']); ?>
<div class="row">
    <div class="col4">
        <!-- Bouton pour vider le fichier journal.log -->
        <button type="submit" name="action" value="resetLog" class="btn red">
            🧽 Vider journal.log
        </button>
    </div>
</div>
<?php echo template::formClose(); ?>
<!-- Affiche la version du module -->
<div class="moduleVersion">Module version n°
    <?php echo $module::VERSION; ?>
</div>