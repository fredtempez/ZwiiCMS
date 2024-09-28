<?php echo template::formOpen('configForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('configBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl(false),
            'value' => template::ico('home')
        ]); ?>
    </div>
    <div class="col2 offset6">
        <?php echo template::button('configLocaleButton', [
            'value' => 'Identité',
            'href' => helper::baseUrl() . 'language/site'
        ]); ?>
    </div>
    <div class="col2">
        <?php echo template::submit('Submit'); ?>
    </div>
</div>

<div class="tab">
    <?php echo template::button('configSetupButton', [
        'value' => 'Configuration',
        'class' => 'buttonTab',
		//'href' => helper::baseUrl() . 'config/register/setup'
    ]); ?>
    <?php echo template::button('configSocialButton', [
        'value' => 'Référencement',
        'class' => 'buttonTab',
		//'href' => helper::baseUrl() . 'config/register/social'
    ]); ?>

    <?php echo template::button('configConnectButton', [
        'value' => 'Connexion',
        'class' => 'buttonTab',
		//'href' => helper::baseUrl() . 'config/register/connect'
    ]); ?>
    <?php echo template::button('configNetworkButton', [
        'value' => 'Réseau',
        'class' => 'buttonTab',
		//'href' => helper::baseUrl() . 'config/register/network' 
    ]); ?>
</div>

<?php include('core/module/config/view/setup/setup.php') ?>
<?php include('core/module/config/view/social/social.php') ?>
<?php include('core/module/config/view/connect/connect.php') ?>
<?php include('core/module/config/view/network/network.php') ?>
<?php echo template::formClose(); ?>