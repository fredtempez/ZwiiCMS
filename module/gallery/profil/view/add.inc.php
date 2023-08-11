<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Galerie')); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddGalleryAdd', true, 'Ajouter'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddGalleryEdit', true, 'Éditer'); ?>
                </div>
                <div class="col2">
                    <?php echo template::checkbox('profilAddGalleryDelete', true, 'Effacer'); ?>
                </div>
                <div class="col2">
                    <?php echo template::checkbox('profilAddGalleryOption', true, 'Options'); ?>
                </div>
                <div class="col2">
                    <?php echo template::checkbox('profilAddGalleryTheme', true, 'Thème'); ?>
                </div>
            </div>
        </div>
    </div>
</div>