<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Geolocation')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddGeolocationAdd', true, 'Ajouter un point'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddGeolocationEdit', true, 'Éditer un point'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddGeolocationDelete', true, 'Effacer un point'); ?>
                </div>
            </div>
        </div>
    </div>
</div>