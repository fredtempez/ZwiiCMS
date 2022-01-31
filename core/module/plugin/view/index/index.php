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
			'href' => 'https://doc.zwiicms.fr/gestion-des-modules',
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
</div>
<?php if($module::$modulesOrphan): ?>
	<h3>Suppression des modules orphelins</h3>
	<?php echo template::table([2, 2, 1, 6, 1], $module::$modulesOrphan, [ 'Module', 'moduleId', 'Version', '', '']); ?>
<?php endif; ?>
<?php if($module::$modulesInstalled): ?>
	<h3>Sauvegarde des modules</h3>
	<?php echo template::table([2, 2, 1, 5, 1, 1], $module::$modulesInstalled, [ 'Module', 'moduleId', 'Version', '', '', '']); ?>
<?php endif; ?>
<?php if($module::$modulesData): ?>
	<h3>Sauvegarde des données des modules installés</h3>
	<?php echo template::table([2, 2, 1, 1, 4, 1, 1], $module::$modulesData, [ 'Module', 'moduleId', 'Version', 'Langue', 'Page (id)', '', '']); ?>
<?php else: ?>
	<?php echo template::speech('Aucun module installé.'); ?>
<?php endif; ?>
