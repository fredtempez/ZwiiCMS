<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Téléversement')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddUploadConfig', true, 'Configurer un dépôt de fichiers'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddUploadData', true, 'Consulter les fichiers remis'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddUploadDownload', true, 'Télécharger tous les fichiers remis'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('profilAddUploadDelete', true, 'Effacer un fichier'); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilAddUploadDeleteAll', true, 'Effacer tous les fichiers'); ?>
                </div>
            </div>
        </div>
    </div>
</div>