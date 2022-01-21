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
			'class' => 'buttonHelp',
			'help' => 'Consulter l\'aide en ligne'
		]); ?>
	</div>
	<div class="col1 offset8">
          <?php echo template::button('configModulesStore', [
              'href' => helper::baseUrl() . 'addon/store',
			  'value' => template::ico('shopping-basket'),
			  "help" => 'Lister le catalogue en ligne'
            ]); ?>
      </div>
	<div class="col1">
		<?php echo template::button('configStoreUpload', [
			'href' => helper::baseUrl() . 'addon/upload',
			'value' => template::ico('plus'),
			"help" => 'Ajouter à partir d\'une archive ZIP'
		]); ?>
	</div>
</div>
<?php if($module::$modInstal): ?>
	<?php echo template::table([2, 2, 2, 2, 1, 1, 1], $module::$modInstal, ['Module installé', 'Alias', 'Version', 'Page(s)', 'Supprimer', 'Exporter', 'Importer']); ?>
<?php else: ?>
	<?php echo template::speech('Aucun module installé.'); ?>
<?php endif; ?>
