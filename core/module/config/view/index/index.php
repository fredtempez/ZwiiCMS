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

<!-- SETUP -->
<div class="row helpDisplayContainer" id="setupHelpContainer">
    <div class="col12">
        <?php echo template::ico('cancel'); ?>
        <?php include ('core/module/config/view/setup/setup0.help.html') ?>
    </div>
</div>
<div class="row helpDisplayContainer" id="updateHelpContainer">
    <div class="col12">
        <?php echo template::ico('cancel'); ?>
        <?php include ('core/module/config/view/setup/setup1.help.html') ?>
    </div>
</div>
<div class="row helpDisplayContainer" id="maintenanceHelpContainer">
    <div class="col12">
        <?php echo template::ico('cancel'); ?>
        <?php include ('core/module/config/view/setup/setup2.help.html') ?>
    </div>
</div>
<?php include ('core/module/config/view/setup/setup.php') ?>

<!-- LOCALISATION --> 
<div class="row helpDisplayContainer" id="localeHelpContainer">
    <div class="col12">
        <?php echo template::ico('cancel'); ?>
        <?php include ('core/module/config/view/locale/locale0.help.html') ?>
    </div>
</div>
<div class="row helpDisplayContainer" id="labelHelpContainer">
    <div class="col12">
        <?php echo template::ico('cancel'); ?>
        <?php include ('core/module/config/view/locale/locale1.help.html') ?>
    </div>
</div>
<div class="row helpDisplayContainer" id="specialeHelpContainer">
    <div class="col12">
        <?php echo template::ico('cancel'); ?>
        <?php include ('core/module/config/view/locale/locale2.help.html') ?>
    </div>
</div>
<?php include ('core/module/config/view/locale/locale.php') ?>


<?php include ('core/module/config/view/social/social.php') ?>
<?php include ('core/module/config/view/connect/connect.php') ?>
<?php include ('core/module/config/view/network/network.php') ?>
<?php echo template::formClose(); ?>