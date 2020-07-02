<div class="row">
	<div class="col2">
		<?php echo template::button('formDataBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2 offset6">
	<?php echo template::button('formDataDeleteAll', [
			'class' => 'formDataDeleteAll buttonRed',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/deleteall' . '/' . $_SESSION['csrf'],
			'ico' => 'cancel',
			'value' => 'Tout effacer'
		]); ?>
	</div>
	<div class="col2">
	<?php echo template::button('formDataBack', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/export2csv' . '/' . $_SESSION['csrf'],
			'ico' => 'download',			
			'value' => 'Export CSV'
		]); ?>
	</div>
</div>
<?php echo template::table([11, 1], $module::$data, ['Données', '']); ?>
<?php echo $module::$pagination; ?>
<div class="moduleVersion">Version n°
	<?php echo $module::FORM_VERSION; ?>
</div>