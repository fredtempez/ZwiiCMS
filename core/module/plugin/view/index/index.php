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
			'help' => 'Installer depuis le catalogue en ligne'
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('configStoreUpload', [
			'href' => helper::baseUrl() . 'plugin/upload',
			'value' => template::ico('file-archive'),
			'help' => 'Installer depuis une archive'
		]); ?>
	</div>
</div>
<div class="tab">
	<?php echo template::button('configModuleButton', [
		'value' => 'Modules installés',
		'class' => ' buttonTab'
	]); ?>
	<?php echo template::button('configDataButton', [
		'value' => 'Données des modules',
		'class' => 'buttonTab'
	]); ?>
</div>
<div class="tabContent" id="moduleContainer">
	<?php if($module::$modulesInstalled): ?>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Sauvegarde</h4>
				<?php echo template::table([2, 2, 1, 5, 1, 1], $module::$modulesInstalled, [ 'Modules', 'moduleId', 'Versions', '', '', '']); ?>
			</div>
		</div>
	</div>
	<?php else: ?>
	<?php echo template::speech('Aucun module installé.'); ?>
	<?php endif; ?>
	<?php if($module::$modulesOrphan): ?>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Modules orphelins</h4>
				<?php echo template::table([2, 2, 1, 6, 1], $module::$modulesOrphan, [ 'Modules', 'moduleId', 'Versions', '', '']); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
<div class="tabContent displayNone" id="dataContainer">
	<?php if($module::$modulesData): ?>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Modules configurés <?php echo template::flag( self::$i18nContent, '20px'); ?>  </h4>
				<div class="row">
					<div class="col1 offset11">
						<?php echo template::button('configModuledataImport', [
					'href' => helper::baseUrl() . 'plugin/dataImport',
					'value' => template::ico('upload'),
					"help" => 'Importer des données de module dans une page libre'
					]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col12">
						<?php echo template::table([2, 2, 1, 5, 1, 1], $module::$modulesData, [ 'Modules', 'moduleId', 'Versions', 'Pages (pageId)', '', '']); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>