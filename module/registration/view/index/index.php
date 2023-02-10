<?php echo template::formOpen('registrationAddForm'); ?>
	<div class="row">
		<div class="col8 offset2">
			<div class='block'>
				<h4>Identité</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::text('registrationAddFirstname', [
							'autocomplete' => 'off',
							'label' => 'Prénom'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('registrationAddLastname', [
							'autocomplete' => 'off',
							'label' => 'Nom'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col12">
					<?php echo template::mail('registrationAddMail', [
						'autocomplete' => 'off',
						'label' => 'Adresse mail'
					]); ?>
				</div>
			</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::hidden('registrationAddGroup', [
						'value' => self::GROUP_MEMBER
					]); ?>
				</div>
			</div>

		<div class='block'>
			<h4>Données de connexion</h4>
			<div class="row">
				<div class="col12">
					<?php echo template::text('registrationAddId', [
						'autocomplete' => 'off',
						'label' => 'Identifiant de connexion'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::password('registrationAddPassword', [
						'autocomplete' => 'off',
						'label' => 'Mot de passe'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::password('registrationAddConfirmPassword', [
						'autocomplete' => 'off',
						'label' => 'Confirmation du mot de passe'
					]);
					?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col12">
				<?php echo template::hidden('registrationAddTimer', [
					'value' => time()
				]);
				?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col2 offset8">
			<?php echo template::submit('registrationAddSubmit', [
				'value' => 'Envoyer',
				'class' => 'green'
			]); ?>
		</div>
	</div>
<?php echo template::formClose(); ?>
