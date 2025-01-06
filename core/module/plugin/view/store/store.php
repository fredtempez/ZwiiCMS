<div class="row">
	<div class="col1">
		<?php echo template::button('configStoreBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl()  . 'plugin',
			'value' => template::ico('left')
		]); ?>
	</div>
</div>
<?php if (plugin::$storeList) : ?>
	<?php echo template::table([2, 2, 1, 2, 2, 1], plugin::$storeList, ['Catégorie', 'Module', 'Version', 'Date', 'Page', '']); ?>
<?php else : ?>
	<?php echo template::speech('Le catalogue est vide.'); ?>
<?php endif; ?>