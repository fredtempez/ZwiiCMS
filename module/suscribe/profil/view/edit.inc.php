<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Auto-inscription')); ?>·
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilEditSuscribeEdit', true, 'Éditer inscription', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'suscribe', 'edit'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilEditSuscribeDelete', true, 'Effacer inscription', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'suscribe', 'delete'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilEditSuscribeUser', true, 'Valider inscriptions', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'suscribe', 'user'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>