<div class="row">
	<div class="col2">
		<?php echo template::button('configModulesBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(),
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('configModulesHelp', [
			'href' => 'https://doc.zwiicms.fr/les-modules',
			'target' => '_blank',
			'ico' => 'help',
			'value' => 'Aide',
			'class' => 'buttonHelp'
		]); ?>
	</div>
	<div class="col2 offset4">
          <?php echo template::button('configModulesStore', [
              'href' => helper::baseUrl() . 'addon/store',
              'value' => 'Catalogue en ligne'
            ]); ?>
    </div>
	<div class="col2">
		<?php echo template::button('configStoreUpload', [
			'href' => helper::baseUrl() . 'addon/upload',
			'value' => 'Installer'
		]); ?>
	</div>
</div>
<?php if($module::$modInstal): ?>
	<?php echo template::table([2, 2, 2, 2, 1, 1, 1], $module::$modInstal, ['Module installé', 'Alias', 'Version', 'Page(s)', 'Supprimer', 'Exporter', 'Importer']); ?>
<?php else: ?>
	<?php echo template::speech('Aucun module installé.'); ?>
<?php endif; ?>
