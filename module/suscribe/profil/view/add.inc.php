<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Auto-inscription')); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddSuscribeEdit', true, 'Éditer inscription'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddSuscribeDelete', true, 'Effacer inscription'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddSuscribeUser', true, 'Valider inscriptions'); ?>
                </div>
            </div>
        </div>
    </div>
</div>