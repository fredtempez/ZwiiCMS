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
	<div class="col2">
		<?php echo template::button('Help', [
			'class' => 'buttonHelp',
			'ico' => 'help',
			'value' => 'Aide'
		]); ?>
	</div>
	<div class="col2 offset6">
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
<!-- Pages de configuration -->
<?php include ('core/module/config/view/setup/setup.php') ?>
<?php include ('core/module/config/view/locale/locale.php') ?>
<?php include ('core/module/config/view/social/social.php') ?>
<?php include ('core/module/config/view/connect/connect.php') ?>
<?php include ('core/module/config/view/network/network.php') ?>
<?php echo template::formClose(); ?>