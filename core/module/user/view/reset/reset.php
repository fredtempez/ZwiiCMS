<?php echo template::formOpen('userResetForm'); ?>
<div class="row">
	<div class="col6">
		<?php echo template::password('userResetNewPassword', [
			'label' => 'Nouveau mot de passe'
		]); ?>
	</div>
	<div class="col6">
		<?php echo template::password('userResetConfirmPassword', [
			'label' => 'Confirmation'
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col3 offset9">
		<?php echo template::submit('userResetSubmit', [
			'value' => 'Valider'
		]); ?>
	</div>
</div>
<?php echo template::formClose(); ?>