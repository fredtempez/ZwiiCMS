<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('News'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddNewsAdd', true, 'Ajouter'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddNewsEdit', true, 'Éditer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddNewsDelete', true, 'Supprimer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddNewsOption', true, 'Options'); ?>
                </div>

            </div>
        </div>
    </div>
</div>