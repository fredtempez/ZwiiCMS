<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Geolocation')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditGeolocationAdd', true, 'Ajouter un point', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'geolocation', 'add'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditGeolocationEdit', true, 'Éditer un point', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'geolocation', 'edit'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditGeolocationDelete', true, 'Effacer un point', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'geolocation', 'delete'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>