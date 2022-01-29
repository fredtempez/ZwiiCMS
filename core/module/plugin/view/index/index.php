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
              'href' => helper::baseUrl() . 'plugin/store',
			  'value' => template::ico('shopping-basket'),
			  "help" => 'Lister le catalogue en ligne'
            ]); ?>
      </div>
	<div class="col1">
		<?php echo template::button('configStoreUpload', [
			'href' => helper::baseUrl() . 'plugin/upload',
			'value' => template::ico('plus'),
			"help" => 'Ajouter à partir d\'une archive ZIP'
		]); ?>
	</div>
</div
<?php if($module::$modOrphans): ?>>
	<h3>Modules installés non utilisés  par une page : </h3>
	<?php echo template::table([2, 2, 1, 2, 2, 1, 1, 1], $module::$modOrphans, [ 'Module', 'moduleId', 'Version', '', '', '', '', 'Supprimer']); ?>
<?php endif; ?>
<?php if($module::$modInstal): ?>
	<h3>Modules utilisés : </h3>
	<?php echo template::table([2, 2, 1, 1, 4, 1, 1], $module::$modInstal, [ 'Module', 'moduleId', 'Version', 'Langue', 'Page (id)', '', '']); ?>
<?php else: ?>
	<?php echo template::speech('Aucun module installé.'); ?>
<?php endif; ?>
