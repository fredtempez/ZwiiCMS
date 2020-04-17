<?php echo template::formOpen('configBackupForm'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('configBackupBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'config',
				'ico' => 'left',
				'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2 offset8">
		<?php echo template::submit('configBackupSubmit',[
			'value' => 'Télécharger',
			'ico' => 'download'
			]); ?>		
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Option de sauvegarde</h4>	
			<div class="row">
				<div class="col10">
					<?php echo template::checkbox('configBackupOption', true, 'Inclure le contenu du gestionnaire de fichiers', [
						'checked' => true, 	
						'help' => 'Cette option n\'est pas recommandée lorsque le contenu du gestionnaire de fichiers est très volumineux.'
					]); ?>		
				</div>	
			</div>
		</div>				
	</div>	
</div>
<?php echo template::formClose(); ?>
