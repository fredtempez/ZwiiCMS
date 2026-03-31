<?php
echo Template::formOpen('userAuthForm'); ?>
<div class="row">
    <div class="col4 offset4">
        <?php
echo Template::text('userAuthKey', [ 'label' => Helper::translate('Clé envoyée par message') ]); ?>
    </div>
</div>
<div class="row" id="buttonsContainer">
    <div class="col2" id="backContainer">
        <?php
echo Template::button('userAuthBack', [ 'href' => $this->getUrl(2) ? Helper::baseUrl() . 'user/login/' . str_replace('_', '/', str_replace('__', '#', $this->getUrl(2))) : Helper::baseUrl() . 'user/login', 'value' => Template::ico('left') ]); ?>
    </div>
    <div class="col3 offset7" id="loginContainer">
        <?php
echo Template::submit('userLoginSubmit', [ 'value' => 'Connexion', 'ico' => '' ]); ?>
    </div>
</div>
<?php
echo Template::formClose(); ?>
