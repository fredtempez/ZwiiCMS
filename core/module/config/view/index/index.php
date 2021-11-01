<?php echo template::formOpen('configForm');?>
<div class="row">
	<div class="col2">
		<?php echo template::button('configBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(false),
			'ico' => 'home',
			'value' => 'Accueil'
		]); ?>
	</div>
	<div class="col2 offset8">
		<?php echo template::submit('Submit'); ?>
	</div>
</div>
<div class="row">
    <div class="col12">
        <div class="row textAlignCenter">
            <div class="col2">
                <?php echo template::button('configSetupButton', [
                    'value' => 'Paramètres'
                ]); ?>
            </div>
            <div class="col2">
                <?php echo template::button('configLocalButton', [
                    'value' => 'Localisation'
                ]); ?>
            </div>
            <div class="col2">
                <?php echo template::button('configSocialButton', [
                    'value' => 'Référencement'
                ]); ?>
            </div>
            <div class="col2">
                <?php echo template::button('configConnectButton', [
                    'value' => 'Connexion'
                ]); ?>
            </div>
            <div class="col2">
                <?php echo template::button('configNetworkButton', [
                    'value' => 'Réseau'
                ]); ?>
            </div>
        </div>
    </div>
</div>
<!-- Aide en ligne SETUP -->
<div class="row">
    <div class="col12 helpDisplayContainer" id="setupHelpContainer">
        <?php echo template::ico('cancel'); ?>
        <?php include ('core/module/config/view/setup/setup.help.html') ?>
    </div>
</div>
<div class="row">
    <div class="col12 helpDisplayContainer" id="updateHelpContainer">
        <?php echo template::ico('cancel'); ?>
        <?php include ('core/module/config/view/setup/setup_update.help.html') ?>
    </div>
</div>
<div class="row">
    <div class="col12 helpDisplayContainer" id="maintenanceHelpContainer">
        <?php echo template::ico('cancel'); ?>
        <?php include ('core/module/config/view/setup/setup_maintenance.help.html') ?>
    </div>
</div>
<!-- Pages de configuration -->
<?php include ('core/module/config/view/setup/setup.php') ?>
<?php include ('core/module/config/view/locale/locale.php') ?>
<?php include ('core/module/config/view/social/social.php') ?>
<?php include ('core/module/config/view/connect/connect.php') ?>
<?php include ('core/module/config/view/network/network.php') ?>
<?php echo template::formClose(); ?>