<div class="row">
	<div class="col1">
		<?php echo template::button('formDataBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset7">
		<?php echo template::button('formDataDeleteAll', [
			'class' => 'formDataDeleteAll buttonRed',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/deleteall' . '/' . $_SESSION['csrf'],
			'ico' => 'trash',
			'value' => 'Tout effacer'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('formDataBack', [
			'class' => 'blue',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/export2csv' . '/' . $_SESSION['csrf'],
			'ico' => 'download',
			'value' => 'Export CSV'
		]); ?>
	</div>
</div>
<?php if ($module::$data): ?>
	<?php echo template::table([11, 1], $module::$data, ['Réponses', '']); ?>
	<?php echo $module::$pagination; ?>
<?php else: ?>
	<?php echo template::speech('Aucune réponse !'); ?>
<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>