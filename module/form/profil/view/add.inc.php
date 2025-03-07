<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Formulaire')); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('profilEditFormOption', true, 'Options du formulaire'); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilEditFormData', true, 'Consulter les réponses'); ?>
                </div>
                </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditFormDelete', true, 'Effacer une réponse'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditFormDeleteAll', true, 'Effacer toutes les réponses'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditFormExport2csv', true, 'Exporter toutes les réponses'); ?>
                </div>
            </div>
        </div>
    </div>
</div>