<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Galerie')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditGalleryAdd', true, 'Ajouter une galerie', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'add'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditGalleryEdit', true, 'Éditer une galerie', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'edit'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditGalleryDelete', true, 'Effacer une galerie', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'delete'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('profilEditGalleryOption', true, 'Options des galeries', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'option'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilEditGalleryTheme', true, 'Thème des galeries', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'theme'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>