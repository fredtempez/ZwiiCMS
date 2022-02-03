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
	<div class="col1 offset9">
		<?php echo template::button('configModulesStore', [
              'href' => helper::baseUrl() . 'plugin/store',
			  'value' => template::ico('shopping-basket'),
			  "help" => 'Catalogue de modules en ligne'
            ]); ?>
	</div>
</div>
<div class="row">
    <div class="col12">
        <div class="row textAlignCenter">
            <div class="col3">
                <?php echo template::button('configManageModuleButton', [
                    'value' => 'Modules installés',
					'class' => 'activeButton'
                ]); ?>
            </div>
            <div class="col3">
                <?php echo template::button('configManageDatasButton', [
                    'value' => 'Données des modules'
                ]); ?>
            </div>
        </div>
    </div>
</div>
<div id="manageModules">
	<?php if($module::$modulesInstalled): ?>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Sauvegarde des modules installés</h4>
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
				<h4>Désinstallation des modules orphelins</h4>
				<?php echo template::table([2, 2, 1, 6, 1], $module::$modulesOrphan, [ 'Modules', 'moduleId', 'Versions', '', '']); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
<div id="manageDatas" class="displayNone">
	<?php if($module::$modulesData): ?>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Données des modules installés</h4>
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
						<?php echo template::table([2, 2, 1, 5, 1, 1], $module::$modulesData, [ 'Modules', 'moduleId', 'Versions', 'Pages ' . template::flag( self::$i18n, '15px')  . ' (pageId)', '', '']); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>