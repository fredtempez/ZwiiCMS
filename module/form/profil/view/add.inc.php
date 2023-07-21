<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Formulaire'); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddFormOption', true, 'Options'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddFormData', true, 'Gérer les données'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddFormExport2csv', true, 'Export CSV'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddFormDelete', true, 'Supprimer'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddFormDeleteAll', true, 'Tout Supprimer'); ?>
                </div>

            </div>
        </div>
    </div>
</div>