<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Geogalerie')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddGeogalleryAdd', true, 'Ajouter une galerie'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddGeogalleryEdit', true, 'Éditer une galerie'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddGeogalleryDelete', true, 'Effacer une galerie'); ?>
                </div>
            </div>
                <div class="col6">
                    <?php echo template::checkbox('profilAddGeogalleryTheme', true, 'Thème des galeries'); ?>
                </div>
            </div>
        </div>
    </div>
</div>