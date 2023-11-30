<?php echo template::formOpen('userImportForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('userImportBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'user',
            'value' => template::ico('left')
        ]); ?>
    </div>

    <?php /**echo template::button('userHelp', [
       'href' => 'https://doc.zwiicms.fr/importation-d-une-liste-d-utilisateurs',
       'target' => '_blank',
       'value' => template::ico('help'),
       'class' => 'buttonHelp',
       'help' => 'Consulter l\'aide en ligne'
   ]);*/?>
    <div class="col1 offset8">
        <?php echo template::button('userImporTemplate', [
            'href' => helper::baseUrl() . 'user/template',
            'value' => template::ico('table'),
            'help' => 'Télécharger un modèle'
        ]); ?>
    </div>
    <div class="col2">
        <?php echo template::submit('userImportSubmit', [
            'value' => 'Importer'
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Importation de fichier plat CSV'); ?>
            </h4>
            <div class="row">
                <div class="col10">
                    <?php echo template::file('userImportCSVFile', [
                        'language' => $this->getData(['user', $this->getUser('id'), 'language']),
                        'label' => 'Liste d\'utilisateurs :'
                    ]); ?>
                </div>
                <div class="col2">
                    <?php echo template::select('userImportSeparator', $module::$separators, [
                        'label' => 'Séparateur'
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col12">
                    <?php echo template::checkbox('userImportNotification', true, 'Envoyer un message de confirmation', [
                        'checked' => false
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>
<?php if ($module::$users): ?>
    <div class="row">
        <div class="col12 textAlignCenter">
            <?php echo template::table([1, 2, 2, 1, 1, 1, 2, 1, 1], $module::$users, ['Id', 'Nom', 'Prénom', 'Groupe', 'Profil', 'Pseudo', 'eMail', 'Étiquettes', '']); ?>
            <?php echo template::ico('check'); ?> Compte créé |
            <?php echo template::ico('mail'); ?> Compte créé et notifié |
            <?php echo template::ico('cancel'); ?> Erreur dans le fichier ou le compte existe.
        </div>
    </div>
<?php endif; ?>