<div class="row">
	<div class="col2">
		<?php echo template::button('configStoreBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'addon',
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
</div>
<?php if($module::$storeList): ?>
	<?php echo template::table([4, 2, 1, 2, 2, 1], $module::$storeList, ['Catégorie', 'Module', 'Version', 'Date', 'Pages', '']); ?>
<?php else: ?>
	<?php echo template::speech('Le catalogue est vide.'); ?>
<?php endif; ?>