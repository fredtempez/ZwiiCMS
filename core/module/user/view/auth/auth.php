<?php echo template::formOpen('userAuthForm'); ?>
<div class="row">
    <div class="col6 offset3">
        <?php echo template::text('userAuthKey', [
            'label' => helper::translate('Clé reçue par courriel')
        ]); ?>
    </div>
</div>
<div class="row" id="buttonsContainer">
    <div class="col2" id="backContainer">
        <?php echo template::button('userAuthBack', [
            'href' => $this->getUrl(2) ? helper::baseUrl() . ' user/login' . str_replace('_', '/', str_replace('__', '#', $this->getUrl(2))) : helper::baseUrl() . ' user/login',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col3 offset7" id="loginContainer">
        <?php echo template::submit('userLoginSubmit', [
            'value' => helper::translate('Authentification'),
            'ico' => ''
        ]); ?>
    </div>
</div>
<?php echo template::formClose(); ?>