<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Téléversement')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditUploadConfig', true, 'Configurer un dépôt de fichiers', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'upload', 'config'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditUploadData', true, 'Consulter les fichiers remis', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'upload', 'data'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditUploadDownload', true, 'Télécharger tous les fichiers remis', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'upload', 'download'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('profilEditUploadDelete', true, 'Effacer un fichier', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'upload', 'delete'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilEditUploadDeleteAll', true, 'Effacer tous les fichiers', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'upload', 'deleteAll'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>