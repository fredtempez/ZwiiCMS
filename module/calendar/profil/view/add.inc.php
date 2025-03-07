<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Calendrier')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddCalendarAdd', true, 'Ajouter un calendrier'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddCalendarEdit', true, 'Éditer un calendrier'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddCalendarDelete', true, 'Effacer un calendrier'); ?>
                </div>
            </div>
        </div>
    </div>
</div>