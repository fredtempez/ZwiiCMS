<div class="row">
	<div class="col1">
		<?php echo template::button('configModulesBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(),
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1">
		<?php /**echo template::button('pluginHelp', [
			'href' => 'https://doc.zwiicms.fr/gestion-des-modules',
			'target' => '_blank',
			'value' => template::ico('help'),
			'class' => 'buttonHelp',
			'help' => 'Consulter l\'aide en ligne'
		]);*/ ?>
	</div>
	<div class="col1 offset8">
		<?php echo template::button('pluginModulesStore', [
			'href' => helper::baseUrl() . 'plugin/store',
			'value' => template::ico('shopping-basket'),
			'help' => 'Installer depuis le catalogue en ligne'
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('pluginStoreUpload', [
			'href' => helper::baseUrl() . 'plugin/upload',
			'value' => template::ico('file-archive'),
			'help' => 'Installer depuis une archive'
		]); ?>
	</div>
</div>
<div class="tab">
	<?php echo template::button('pluginModuleButton', [
		'value' => 'Modules installés',
		'class' => ' buttonTab'
	]); ?>
	<?php echo template::button('pluginDataButton', [
		'value' => 'Données des modules',
		'class' => 'buttonTab'
	]); ?>
</div>
<div class="tabContent" id="moduleContainer">
	<?php if ($module::$modulesInstalled) : ?>
		<div class="row">
			<div class="col12">
				<div class="block">
					<h4><?php echo helper::translate('Sauvegarde'); ?>
					</h4>
					<?php echo template::table([2, 2, 1, 5, 1, 1], $module::$modulesInstalled, ['Module', 'Identifiant', 'Version', '', '', '']); ?>
				</div>
			</div>
		</div>
	<?php else : ?>
		<?php echo template::speech('Aucun module installé.'); ?>
	<?php endif; ?>
	<?php if ($module::$modulesOrphan) : ?>
		<div class="row">
			<div class="col12">
				<div class="block">
					<h4><?php echo helper::translate('Modules orphelins'); ?>
					</h4>
					<?php echo template::table([2, 2, 1, 6, 1], $module::$modulesOrphan, ['Module', 'Identifiant', 'Version', '', '']); ?>
				</div>
			</div>
		</div>
	<?php else : ?>
		<?php echo template::speech('Aucun module orphelin.'); ?>
	<?php endif; ?>
</div>
<div class="tabContent displayNone" id="dataContainer">
	<?php if ($module::$modulesData) : ?>
		<div class="row">
			<div class="col12">
				<div class="block">
					<h4>
						<?php echo helper::translate('Modules configurés'); ?>
					</h4>
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
							<?php echo template::table([4, 1, 4, 1, 1, 1], $module::$modulesData, ['Module', 'Version', 'Page associée', '', '', '']); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php else : ?>
		<?php echo template::speech('Aucune donnée de module.'); ?>
	<?php endif; ?>
</div>