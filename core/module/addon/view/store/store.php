<div class="row">
	<div class="col2">
		<?php echo template::button('configStoreBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(),
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col3 offset7">
		<?php echo template::button('configStoreUpload', [
			'href' => helper::baseUrl() . 'addon/upload',
			'value' => 'Téléverser un module'
		]); ?>
	</div>
</div>
<?php if($module::$storeList): ?>
	<?php echo template::table([4, 3, 4, 1], $module::$storeList, ['Module', 'Version', 'Date', 'Installer']); ?>
<?php else: ?>
	<?php echo template::speech('Le catalogue est vide.'); ?>
<?php endif; ?>