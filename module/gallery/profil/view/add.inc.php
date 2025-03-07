<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Galerie')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddGalleryAdd', true, 'Ajouter une galerie'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddGalleryEdit', true, 'Éditer une galerie'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddGalleryDelete', true, 'Effacer une galerie'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('profilAddGalleryOption', true, 'Options des galeries'); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilAddGalleryTheme', true, 'Thème des galeries'); ?>
                </div>
            </div>
        </div>
    </div>
</div>