<?php echo template::formOpen('configRestoreForm'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('configRestoreBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'config',
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2 offset8">
		<?php echo template::submit('configRestoreSubmit', [
			'value' => 'Restaurer',
			'uniqueSubmission' => true,
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>
				<?php echo template::topic('Archive à restaurer'); ?>
			</h4>
			<div class="row">
				<div class="col10 offset1">
					<div class="row">
						<?php echo template::file('configRestoreImportFile', [
							'label' => 'Sélectionnez une archive au format ZIP',
							'type' => 2,
							'help' => 'L\'archive a été déposée dans le gestionnaire de fichiers. Les archives inférieures à la version 9 ne sont pas acceptées.'
						]); ?>
					</div>
					<div class="row">
						<?php echo template::checkbox('configRestoreImportUser', true, 'Préserver les comptes des utilisateurs déjà installés', [
							'checked' => true
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>