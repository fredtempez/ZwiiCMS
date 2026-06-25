<?php
echo Template::formOpen('configForm'); ?>
<div class="row">
    <div class="col1">
        <?php
echo Template::button('configBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl(false), 'value' => Template::ico('home') ]); ?>
    </div>
    <div class="col2 offset7">
        <?php
echo Template::button('configDashBoardButton', [ 'value' => 'Tableau de bord', 'href' => Helper::baseUrl() . 'dashboard', 'ico' => 'chart-line' ]); ?>
    </div>
    <div class="col2">
        <?php
echo Template::submit('Submit'); ?>
    </div>
</div>

<div class="tab">
    <?php
echo Template::button('configLocaleButton', [ 'value' => 'Identité', 'class' => 'buttonTab', ]); ?>
    <?php
echo Template::button('configSetupButton', [ 'value' => 'Configuration', 'class' => 'buttonTab', ]); ?>
    <?php
echo Template::button('configSocialButton', [ 'value' => 'Référencement', 'class' => 'buttonTab', ]); ?>
    <?php
echo Template::button('configConnectButton', [ 'value' => 'Connexion', 'class' => 'buttonTab', ]); ?>
    <?php
echo Template::button('configNetworkButton', [ 'value' => 'Réseau', 'class' => 'buttonTab', ]); ?>
</div>

<!-- Champ caché pour transmettre l'onglet-->
<?php
echo Template::hidden( 'containerSelected', ['value' => $this->getData(['user', $this->getUser('id'), 'view', 'config'])] ); ?>


<!-- Pages de formulaires -->
<?php
include('core/module/config/view/locale/locale.php') ?>
<?php
include('core/module/config/view/setup/setup.php') ?>
<?php
include('core/module/config/view/social/social.php') ?>
<?php
include('core/module/config/view/connect/connect.php') ?>
<?php
include('core/module/config/view/network/network.php') ?>
<?php
echo Template::formClose(); ?>
