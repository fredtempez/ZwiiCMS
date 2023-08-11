<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('News')); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddNewsAdd', true, 'Ajouter'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddNewsEdit', true, 'Éditer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddNewsDelete', true, 'Effacer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddNewsOption', true, 'Options'); ?>
                </div>

            </div>
        </div>
    </div>
</div>