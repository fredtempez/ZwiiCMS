<?php echo template::formOpen('configForm');?>
<div class="row">
	<div class="col1">
		<?php echo template::button('configBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(false),
            'value' => template::ico('home')
		]); ?>
	</div>
    <div class="col1">
			<?php echo template::button('configHelp', [
				'class' => 'buttonHelp',
                'href' => 'https://doc.zwiicms.fr/configuration-du-site',
                'target' => '_blank',
				'value' => template::ico('help')
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
                    'value' => 'Configuration'
                ]); ?>
            </div>
            <div class="col2">
                <?php echo template::button('configLocaleButton', [
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


<?php include ('core/module/config/view/setup/setup.php') ?>
<?php include ('core/module/config/view/locale/locale.php') ?>
<?php include ('core/module/config/view/social/social.php') ?>
<?php include ('core/module/config/view/connect/connect.php') ?>
<?php include ('core/module/config/view/network/network.php') ?>
<?php echo template::formClose(); ?>