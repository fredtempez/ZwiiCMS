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
			<?php /**echo template::button('configHelp', [
				'class' => 'buttonHelp',
                'href' => 'https://doc.zwiicms.fr/configuration-du-site',
                'target' => '_blank',
				'value' => template::ico('help'),
				'help' => 'Consulter l\'aide en ligne'
			]); */?>
		</div>
	<div class="col2 offset8">
		<?php echo template::submit('Submit'); ?>
	</div>
</div>

<div class="tab">
    <?php echo template::button('configSetupButton', [
        'value' => 'Configuration',
        'class' => 'buttonTab'
    ]); ?>
    <?php echo template::button('configSocialButton', [
        'value' => 'Référencement',
        'class' => 'buttonTab'
    ]); ?>

    <?php echo template::button('configConnectButton', [
        'value' => 'Connexion',
        'class' => 'buttonTab'
    ]); ?>

    <?php echo template::button('configNetworkButton', [
        'value' => 'Réseau',
        'class' => 'buttonTab'
    ]); ?>
</div>

<?php include ('core/module/config/view/setup/setup.php') ?>
<?php include ('core/module/config/view/social/social.php') ?>
<?php include ('core/module/config/view/connect/connect.php') ?>
<?php include ('core/module/config/view/network/network.php') ?>
<?php echo template::formClose(); ?>