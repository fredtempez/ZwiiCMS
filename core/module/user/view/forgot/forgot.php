<?php echo template::formOpen('userForgotForm'); ?>
<?php echo template::text('userForgotId', [
	'label' => 'Identifiant'
]); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('userForgotBack', [
			'href' => helper::baseUrl() . 'user/login/' . $this->getUrl(2),
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col3 offset7">
		<?php echo template::submit('userForgotSubmit', [
			'value' => 'Valider'
		]); ?>
	</div>
</div>
<?php echo template::formClose(); ?>