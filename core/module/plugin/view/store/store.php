<div class="row">
	<div class="col1">
		<?php echo template::button('configStoreBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl()  . 'plugin',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1 offset10">
		<?php echo template::button('configStoreUpload', [
			'href' => helper::baseUrl() . 'plugin/upload',
			'value' => template::ico('plus'),
			"help" => 'Importer depuis une archive ZIP'
		]); ?>
	</div>
</div>
<?php if($module::$storeList): ?>
	<?php echo template::table([2, 2, 1, 2, 2, 2, 1], $module::$storeList, ['Catégorie', 'Module', 'Version', 'Date', 'Pages', 'Obtenir']); ?>
<?php else: ?>
	<?php echo template::speech('Le catalogue est vide.'); ?>
<?php endif; ?>