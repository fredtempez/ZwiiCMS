<?php echo template::formOpen('journalConfig'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('journalConfigBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0) . '/' . self::$siteContent,
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col1 offset8">
        <?php echo template::button('journalConfigReset', [
            'class' => 'buttonRed',
            'href' => helper::baseUrl() . 'journal/reset',
            'value' => template::ico('trash'),
            'help' => 'Vider le journal',
        ]); ?>
    </div>
    <div class="col2">
        <?php echo template::submit('journalConfigSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>Configuration du journal</h4>
            <div class="row">
                <div class="col12">
                    <?php
                    // Chemin vers le fichier journal.log
                    $logPath = self::DATA_DIR . 'journal.log';
                    // Vérifie si le fichier journal.log existe
                    ?>
                    <div class="row">
                        <div class="col6">
                            <?php echo template::text('journalLogStatus', [
                                'label' => 'État du fichier journal.log',
                                'value' => file_exists($logPath) ? 'Présent' : 'Absent',
                                'readonly' => true,
                                'class' => file_exists($logPath) ? 'success' : 'error'
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::label('journalIdentifiant', 'Identifiant Zwii'); ?>
                    <?php echo template::text('journalIdentifiant', [
                        'value' => $this->getData(['module', $this->getUrl(0), 'identifiant']) ?? ''
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::label('journalLimite', 'Nombre de pages affichées'); ?>
                    <?php echo template::number('journalLimite', [
                        'min' => 1,
                        'max' => 50,
                        'value' => $this->getData(['module', $this->getUrl(0), 'limite']) ?? 10
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
                        <input type="checkbox" id="adminCheck" name="journalFiltresAdmin" <?= in_array('admin', $filtresActuels) ? 'checked' : '' ?>>
                        <label for="adminCheck">Admin</label><br>
                        <!-- Case à cocher pour le filtre Visitor -->
                        <input type="checkbox" id="visitorCheck" name="journalFiltresVisitor" <?= in_array('visitor', $filtresActuels) ? 'checked' : '' ?>>
                        <label for="visitorCheck">Visitor</label><br>
                        <!-- Case à cocher pour le filtre Tous -->
                        <input type="checkbox" id="tousCheck" name="journalFiltresTous" <?= in_array('tous', $filtresActuels) ? 'checked' : '' ?>>
                        <label for="tousCheck">Tous</label><br>
                    </div>
                </div>
            </div>
            <?php echo template::formClose(); ?>
            <!-- Affiche la version du module -->
            <div class="moduleVersion">Module version n°
                <?php echo $module::VERSION; ?>
            </div>