<?php
echo Template::formOpen('configScript'); ?>
<div class="row">
    <div class="col1">
        <?php
echo Template::button('configManageBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'config', 'value' => Template::ico('left') ]); ?>
    </div>
    <div class="col2 offset9">
        <?php
echo Template::submit('configManageSubmit', [ 'value' => 'Valider', 'ico' => 'check' ]); ?>
    </div>
</div>
<?php
if ($this->geturl(2) === 'head') : ?>
    <div class="row">
        <div class="col12">
            <?php
echo Template::textarea('configScriptHead', [ 'value' => file_exists(self::DATA_DIR . 'head.inc.html') ? file_get_contents(self::DATA_DIR . 'head.inc.html') : '', 'class' => 'editor' ]); ?>
        </div>
    </div>
<?php
endif ?>
<?php
if ($this->geturl(2) === 'body') : ?>
    <div class="row">
        <div class="col12">
            <?php
echo Template::textarea('configScriptBody', [ 'value' => file_exists(self::DATA_DIR . 'body.inc.html') ? file_get_contents(self::DATA_DIR . 'body.inc.html') : '', 'class' => 'editor' ]); ?>
        </div>
    </div>
<?php
endif ?>
<?php
echo Template::formClose(); ?>
