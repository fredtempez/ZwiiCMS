<?php echo template::formOpen('configForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('configBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl(false),
            'value' => template::ico('home')
        ]); ?>
    </div>
    <div class="col2 offset7">
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
    ]); ?>
    <?php echo template::button('configSocialButton', [
        'value' => 'Référencement',
        'class' => 'buttonTab',
    ]); ?>
    <?php echo template::button('configConnectButton', [
        'value' => 'Connexion',
        'class' => 'buttonTab',
    ]); ?>
    <?php echo template::button('configNetworkButton', [
        'value' => 'Réseau',
        'class' => 'buttonTab',
    ]); ?>
</div>

<!-- Champ caché pour transmettre l'onglet-->
<?php echo template::hidden('containerSelected'); ?>

<!-- Pages de formulaires -->
<?php include('core/module/config/view/setup/setup.php') ?>
<?php include('core/module/config/view/social/social.php') ?>
<?php include('core/module/config/view/connect/connect.php') ?>
<?php include('core/module/config/view/network/network.php') ?>
<?php echo template::formClose(); ?>