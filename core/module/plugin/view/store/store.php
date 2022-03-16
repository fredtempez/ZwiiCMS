<div class="row">
	<div class="col1">
		<?php echo template::button('configStoreBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl()  . 'plugin',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::button('configStoreUpload', [
			'href' => helper::baseUrl() . 'plugin/upload',
			'value' => 'Installer'
		]); ?>
	</div>
</div>
<?php if($module::$storeList): ?>
	<?php echo template::table([2, 2, 1, 2, 2, 1], $module::$storeList, ['Catégorie', 'Module', 'Version', 'Date', 'Pages', '']); ?>
<?php else: ?>
	<?php echo template::speech('Le catalogue est vide.'); ?>
<?php endif; ?>