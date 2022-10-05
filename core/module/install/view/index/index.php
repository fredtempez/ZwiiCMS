<p>Renseignez les champs ci-dessous pour finaliser l'installation.</p>
<?php echo template::formOpen('installForm'); ?>
	<?php echo template::text('installId', [
		'autocomplete' => 'off',
		'label' => 'Identifiant'
	]); ?>
	<div class="row">
		<div class="col6">
			<?php echo template::password('installPassword', [
				'autocomplete' => 'off',
				'label' => 'Mot de passe'
			]); ?>
		</div>
		<div class="col6">
			<?php echo template::password('installConfirmPassword', [
				'autocomplete' => 'off',
				'label' => 'Confirmation'
			]); ?>
		</div>
	</div>
	<?php echo template::mail('installMail', [
		'autocomplete' => 'off',
		'label' => 'Adresse mail'
	]); ?>
	<div class="row">
		<div class="col6">
			<?php echo template::text('installFirstname', [
				'autocomplete' => 'off',
				'label' => 'Prénom'
			]); ?>
		</div>
		<div class="col6">
			<?php echo template::text('installLastname', [
				'autocomplete' => 'off',
				'label' => 'Nom'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
		<?php echo template::select('installTheme', $module::$themes, [
				'label' => 'Thème'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<?php echo template::checkbox('installDefaultData', true, 'Ne pas charger l\'exemple de site (utilisateurs avancés)', [
				'checked' => false
			]);
			?>
		</div>
	</div>
	<div class="row">
		<div class="col3 offset9">
			<?php echo template::submit('installSubmit', [
				'value' => 'Installer'
			]); ?>
		</div>
	</div>
<?php echo template::formClose(); ?>