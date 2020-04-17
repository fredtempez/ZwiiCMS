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
		<?php echo template::submit('configRestoreSubmit',[
			'value' => 'Restaurer',
			'ico' => 'upload'
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Restauration ou transfert d'un site</h4>
			<div class="row">		
				<div class="col8 offset2">		
					<?php echo template::file('configRestoreImportFile', [
						'label' => 'Sélectionnez une sauvegarde au format ZIP',
						'type' => 2,
						'help' => 'L\'archive a été déposée dans le gestionaire de fichiers. Les archives inférieures à la version 9 ne sont pas acceptées.'
					]); ?>
				</div>
			</div>			
			<div class="row">
				<div class="col8 offset2">
					<?php echo template::checkbox('configRestoreImportUser', true, 'Préserver les comptes des utilisateurs', [
						'checked' => true,
						'help' => 'Les données des utilisateurs installés ne sont pas écrasés par la restauration quand l\'option est active.'
					]); ?>		
				</div>	
			</div>			
		</div>
	</div>		
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Conversion des URL après transfert de site</h4>
			<div class="row">
				<div class="col5">
					<?php 
					if (is_null($this->getData(['core', 'baseUrl'])) ) {
						$baseUrlValue = 'Pas de donnée dans la sauvegarde';
						$buttonClass = 'disabled'; 
					} elseif ($this->getData(['core', 'baseUrl']) === '') {
						$baseUrlValue = '/';
						$buttonClass = (helper::baseUrl(true,false) !== $this->getData(['core', 'baseUrl']) ) ? '' : 'disabled';
					} else {
						$baseUrlValue = $this->getData(['core', 'baseUrl']);
						$buttonClass = (helper::baseUrl(true,false) !== $this->getData(['core', 'baseUrl']) ) ? '' : 'disabled';
					}
					echo template::text('configRestoreBaseURLToConvert', [
						'label' => 'Dossier d\'installation de l\'archive' ,
						'value' => $baseUrlValue,
						'readonly' => true,
						'help'  => 'Lors de la restauration d\'un backup d\'une version 9.2.10 ou supérieure, l\'URL de base est stockée dans la configuration sinon cette donnée est vide.'
					]); ?>
				</div>
				<div class="col5">
					<?php echo template::text('configRestoreCurrentURL', [
						'label' => 'Dossier du site actuel',
						'value' => helper::baseUrl(true,false),
						'readonly' => true,
						'help'  => 'Dossier du site installé.'
					]); ?>
				</div>			
				<div class="col2 verticalAlignBottom">
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
