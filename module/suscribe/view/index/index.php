<?php echo template::formOpen('registrationAddForm'); ?>
<div class="<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'layout']); ?>">
	<?php echo template::text('registrationAddFirstname', [
		'autocomplete' => 'off',
		'label' => 'Prénom'
	]); ?>
	<?php echo template::text('registrationAddLastname', [
		'autocomplete' => 'off',
		'label' => 'Nom'
	]); ?>
	<?php echo template::text('registrationAddId', [
		'autocomplete' => 'off',
		'label' => 'Identifiant de connexion'
	]); ?>
	<?php echo template::mail('registrationAddMail', [
		'autocomplete' => 'off',
		'label' => 'Adresse électronique'
	]); ?>
</div>
<div class='submitContainer'>
	<div class="row">
		<div class="col2 offset10">
			<?php echo template::submit('registrationAddSubmit', [
				'value' => 'Envoyer'
			]); ?>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>