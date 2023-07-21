<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('News'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilEditNewsAdd', true, 'Ajouter', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'add'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilEditNewsEdit', true, 'Éditer', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'edit'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilEditNewsDelete', true, 'Supprimer', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'delete'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilEditNewsOption', true, 'Options', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'option'])
                    ]); ?>
                </div>

            </div>
        </div>
    </div>
</div>