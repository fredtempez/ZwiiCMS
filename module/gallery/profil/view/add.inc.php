<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Galerie'); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddGalleryAdd', true, 'Ajouter'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddGalleryEdit', true, 'Éditer'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddGalleryDelete', true, 'Supprimer'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('profilAddGalleryOption', true, 'Options'); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilAddGalleryTheme', true, 'Thème'); ?>
                </div>
            </div>
        </div>
    </div>
</div>