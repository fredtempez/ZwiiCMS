<?php echo template::formOpen('themeAdvancedForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('themeAdvancedBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'theme',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1 offset8">
		<?php echo template::button('themeAdvancedReset', [
			'href' => helper::baseUrl() . 'theme/reset/custom',
			'class' => 'buttonRed',
			'value' => template::ico('cancel'),
			'help' => 'Réinitialiser la feuille de style'

		]); ?>
	</div>
	<div class="col2">
		<?php echo template::submit('themeAdvancedSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<?php echo template::textarea('themeAdvancedCss', [
			'value' => file_get_contents(self::DATA_DIR . 'custom.css'),
			'class' => 'editor'
		]); ?>
	</div>
</div>
<?php echo template::formClose(); ?>