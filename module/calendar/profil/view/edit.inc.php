<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Calendrier')); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditCalendarAdd', true, 'Ajouter un calendrier', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'calendar', 'add'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditCalendarEdit', true, 'Éditer un calendrier', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'calendar', 'edit'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditCalendarDelete', true, 'Effacer un calendrier', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'calendar', 'delete'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>