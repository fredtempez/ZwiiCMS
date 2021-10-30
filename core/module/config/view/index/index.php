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
		<?php echo template::button('configAdvancedHelp', [
			'class' => 'buttonHelp',
			'ico' => 'help',
			'value' => 'Aide'
		]); ?>
	</div>
	<div class="col2 offset6">
		<?php echo template::submit('configAdvancedSubmit'); ?>
	</div>
</div>
<div class="row">
    <div class="col12">
        <div class="row textAlignCenter">
            <div class="col2">
                <?php echo template::button('configAdvancedButton', [
                    'href' => helper::baseUrl() . 'config/index',
                    'value' => 'Paramètres'
                ]); ?>
            </div>
            <div class="col2">
                <?php echo template::button('configAdvancedButton', [
                    'href' => helper::baseUrl() . 'config/locale',
                    'value' => 'Localisation'
                ]); ?>
            </div>
            <div class="col2">
                <?php echo template::button('configAdvancedButton', [
                    'href' => helper::baseUrl() . 'config/social',
                    'value' => 'Référencement'
                ]); ?>
            </div>
            <div class="col2">
                <?php echo template::button('configAdvancedButton', [
                    'href' => helper::baseUrl() . 'config/safety',
                    'value' => 'Sécurité'
                ]); ?>
            </div>
            <div class="col2">
                <?php echo template::button('configAdvancedButton', [
                    'href' => helper::baseUrl() . 'config/network',
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
<?php include ('core/module/config/view/safety/safety.php') ?>
<?php include ('core/module/config/view/network/network.php') ?>
<?php echo template::formClose(); ?>