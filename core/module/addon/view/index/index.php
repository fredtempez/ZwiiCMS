<div class="row">
	<div class="col1">
		<?php echo template::button('configModulesBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(),
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('configModulesHelp', [
			'href' => 'https://doc.zwiicms.fr/modules-utilisation-generique',
			'target' => '_blank',
			'value' => template::ico('help'),
			'class' => 'buttonHelp'
		]); ?>
	</div>
	<div class="col2 offset6">
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
<!-- Aide à propos de la gestion des modules, view index -->
<div class="helpDisplayContent">
	<?php echo file_get_contents( 'core/module/addon/view/index/index.help.html') ;?>
</div>
<?php if($module::$modInstal): ?>
	<?php echo template::table([2, 2, 2, 2, 1, 1, 1], $module::$modInstal, ['Module installé', 'Alias', 'Version', 'Page(s)', 'Supprimer', 'Exporter', 'Importer']); ?>
<?php else: ?>
	<?php echo template::speech('Aucun module installé.'); ?>
<?php endif; ?>
