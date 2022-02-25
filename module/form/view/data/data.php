<div class="row">
	<div class="col1">
		<?php echo template::button('formDataBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1 offset9">
	<?php echo template::button('formDataDeleteAll', [
			'class' => 'formDataDeleteAll buttonRed',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/deleteall' . '/' . $_SESSION['csrf'],
			'value' => template::ico('trash'),
			'help' => 'Effacer toutes les données'
		]); ?>
	</div>
	<div class="col1">
	<?php echo template::button('formDataBack', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/export2csv' . '/' . $_SESSION['csrf'],
			'value' => template::ico('download'),
			'help' => 'Exporter toutes les données'
		]); ?>
	</div>
</div>
<?php if($module::$data): ?>
		<?php echo template::table([11, 1], $module::$data, ['Données', '']); ?>
		<?php echo $module::$pages; ?>
	<?php else: ?>
		<?php echo template::speech('Aucune donnée'); ?>
	<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>