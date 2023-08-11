<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Carrousel')); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('profilEditSliderTheme', true, 'Thème', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'slider', 'theme'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilEditSliderDelete', true, 'Effacer', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'slider', 'delete'])
                    ]); ?>
                </div>

            </div>
        </div>
    </div>
</div>