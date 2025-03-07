<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Geogalerie')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditGeogalleryAdd', true, 'Ajouter une galerie', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'geogallery', 'add'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditGeogalleryEdit', true, 'Éditer une galerie', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'geogallery', 'edit'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditGeogalleryDelete', true, 'Effacer une galerie', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'geogallery', 'delete'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('profilEditGeogalleryTheme', true, 'Thème des galeries', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'geogallery', 'theme'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>