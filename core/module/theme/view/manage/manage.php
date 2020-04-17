<?php echo template::formOpen('themeManageForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('themeManageBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'theme',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col6">
			<div class="block">
			<h4>Appliquer un thème archivé</h4>
				<div class="col10 offset1">
					<?php echo template::file('themeManageImport', [
							'label' => 'Archive ZIP :',
							'type' => 2
					]); ?>
				</div>
				<div class="col5 offset3">
					<?php echo template::submit('themeImportSubmit', [
						'value' => 'Appliquer'
					]); ?>
				</div>	
			</div>
		</div>
		<div class="col6">
			<div class="block">
			<h4>Sauvegarder le thème</h4>
			<div class="row">
				<div class="col8 offset2">
					<?php echo template::button('themeSave', [
						'href' => helper::baseUrl() . 'theme/save',
						'ico' => 'upload-cloud',
						'value' => 'Sauvegarder dans les fichiers'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col8 offset2">
					<?php echo template::button('themeExport', [
						'href' => helper::baseUrl() . 'theme/export',
						'ico' => 'download',
						'value' => 'Télécharger'
					]); ?>
				</div>		
			</div>
		</div>
	</div>	
<?php echo template::formClose(); ?>
