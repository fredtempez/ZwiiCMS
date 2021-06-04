<?php echo template::formOpen('configRestoreForm'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('configRestoreBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'config/advanced',
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2 offset8">
		<?php echo template::submit('configRestoreSubmit',[
			'value' => 'Restaurer'
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Archive à restaurer</h4>
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
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Conversion après la restauration<?php echo template::help('Conversion des URL des ressources multimédia entre deux sites aux arborescences différentes.');?></h4>
			<div class="row">
				<div class="col4 offset1">
					<?php
					if (is_null($this->getData(['core', 'baseUrl'])) ) {
						$baseUrlValue = 'Pas de donnée dans la sauvegarde';
						$buttonClass = 'disabled';
					} elseif ($this->getData(['core', 'baseUrl']) === '') {
						$baseUrlValue = '/';
						$buttonClass = helper::baseUrl(false,false) !== $this->getData(['core', 'baseUrl']) ? '' : 'disabled';
					} else {
						$baseUrlValue = str_replace('?','',$this->getData(['core', 'baseUrl']));
						$buttonClass = helper::baseUrl(false,false) !== $baseUrlValue ? '' : 'disabled';
					}
					echo template::text('configRestoreBaseURLToConvert', [
						'label' => 'Dossier de l\'archive' ,
						'value' => $baseUrlValue,
						'readonly' => true,
						'help'  => 'Le dossier de base du site est stockée dans la sauvegarde.'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::text('configRestoreCurrentURL', [
						'label' => 'Dossier du site actuel',
						'value' => helper::baseUrl(false,false),
						'readonly' => true
					]); ?>
				</div>
				<div class="col2 verticalAlignMiddle">
					<?php echo template::button('configRestoreUpdateBaseURLButton', [
						'href' => helper::baseUrl() . 'config/updateBaseUrl',
						'class' => $buttonClass,
						'value' => 'convertir'
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>
