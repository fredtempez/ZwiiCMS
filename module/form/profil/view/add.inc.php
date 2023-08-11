<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Formulaire')); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilEditFormData', true, 'Gérer les données'); ?>
                </div>
                <div class="col2">
                    <?php echo template::checkbox('profilEditFormOption', true, 'Options'); ?>
                </div>
                <div class="col2">
                    <?php echo template::checkbox('profilEditFormDelete', true, 'Effacer'); ?>
                </div>
                <div class="col2">
                    <?php echo template::checkbox('profilEditFormDeleteAll', true, 'Tout Effacer'); ?>
                </div>
                <div class="col2">
                    <?php echo template::checkbox('profilEditFormExport2csv', true, 'Export CSV'); ?>
                </div>
            </div>
        </div>
    </div>
</div>