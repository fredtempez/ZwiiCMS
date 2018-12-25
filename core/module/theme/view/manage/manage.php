<?php ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('themeBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl(false),
				'ico' => 'home',
				'value' => 'Accueil'
			]); ?>	
		</div>	
		<div class="col2">
			<?php echo template::button('themeImport', [
				'href' => helper::baseUrl() . 'theme/themeImport',
				'value' => 'Importer les données du thème'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::button('themeExport', [
				'href' => helper::baseUrl() . 'theme/themeExport',
				'value' => 'Exporter les données du thème'
			]); ?>
		</div>		
	</div>
<?php?>
