<?php
echo Template::formOpen('themeAdvancedForm'); ?>
<div class="row">
	<div class="col1">
		<?php
echo Template::button('themeAdvancedBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'theme', 'value' => Template::ico('left') ]); ?>
	</div>
	<div class="col1 offset8">
		<?php
echo Template::button('themeAdvancedReset', [ 'href' => Helper::baseUrl() . 'theme/reset/custom', 'class' => 'buttonRed', 'value' => Template::ico('cancel'), 'help' => 'Réinitialiser la feuille de style' ]); ?>
	</div>
	<div class="col2">
		<?php
echo Template::submit('themeAdvancedSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<?php
echo Template::textarea('themeAdvancedCss', [ 'value' => file_get_contents(self::DATA_DIR . 'custom.css'), 'class' => 'editor' ]); ?>
	</div>
</div>
<?php
echo Template::formClose(); ?>
