<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Partage de ressources')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddFolderConfig', true, 'Ajouter un partage'); ?>
                </div>
            </div>
        </div>
    </div>
</div>