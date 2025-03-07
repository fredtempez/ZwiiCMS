<?php echo template::formOpen('galleryThemeForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('galleryThemeBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('galleryThemeBack'); ?>
    </div>
</div>
<?php echo template::formClose(); ?>
<div class="row">
    <div class="col12">
        <div class="moduleVersion">Version n°
            <?php echo $module::VERSION; ?>
        </div>
    </div>
</div>