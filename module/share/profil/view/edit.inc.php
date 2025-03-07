<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Partage de ressources')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditFolderConfig', true, 'Configurer un partage', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'config'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>