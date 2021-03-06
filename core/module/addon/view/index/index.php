<div class="row">
	<div class="col2">
		<?php echo template::button('configModulesBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(),
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2 offset6">
		<?php echo template::button('configModulesUpload', [
			'href' => helper::baseUrl() . 'addon/upload',
			'value' => 'Téléverser'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('configModulesStore', [
			'href' => helper::baseUrl() . 'addon/store',
			'value' => 'Catalogue'
		]); ?>
	</div>
</div>
<?php if($module::$modInstal): ?>
	<?php echo template::table([2, 2, 2, 2, 1, 1, 1, 1], $module::$modInstal, ['Module installé', 'Alias', 'Version', 'Page(s)', 'Supprimer', 'Exporter', 'Importer']); ?>
<?php else: ?>
	<?php echo template::speech('Aucun module installé.'); ?>
<?php endif; ?>
