<?php echo template::formOpen('configScript'); ?>
    <div class="row">
        <div class="col2">
            <?php echo template::button('configManageBack', [
                'class' => 'buttonGrey',
                'href' => helper::baseUrl() . 'config',
                'ico' => 'left',
                'value' => 'Retour'
            ]); ?>
        </div>
        <div class="col2 offset8">
            <?php echo template::submit('configManageSubmit',[
                'value' => 'Valider',
                'ico' => 'check'
            ]); ?>
        </div>
    </div>
    <?php if ($this->geturl(2) === 'head'): ?>
    <div class="row">
        <div class="col12">
            <?php echo template::textarea('configScriptHead', [
                'value' => file_exists( core::$data_dir . 'head.inc.html') ? file_get_contents (core::$data_dir . 'head.inc.html') : '' ,
                'class' => 'editor'
            ]); ?>
        </div>
    </div>
    <?php endif ?>
    <?php if ($this->geturl(2) === 'body'): ?>
    <div class="row">
        <div class="col12">
            <?php echo template::textarea('configScriptBody', [
                'value' => file_exists( core::$data_dir . 'body.inc.html') ? file_get_contents (core::$data_dir . 'body.inc.html') : '' ,
                'class' => 'editor'
            ]); ?>
        </div>
    </div>
    <?php endif ?>
<?php echo template::formClose(); ?>