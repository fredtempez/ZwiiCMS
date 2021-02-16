<?php echo template::formOpen('configModulesGestion'); ?>
    <div class="row">
        <div class="col2">
            <?php echo template::button('configModulesBack', [
                'class' => 'buttonGrey',
                'href' => helper::baseUrl(),
                'ico' => 'left',
                'value' => 'Retour'
            ]); ?>
        </div>
        <div class="col2 offset8">
            <?php echo template::submit('configModulesSubmit',[
                'value' => 'Valider',
                'ico' => 'check'
            ]); ?>
        </div>
    </div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>Installer ou mettre à jour un module </h4>
				<div class="row">
					<div class="col6 offset3">
						<?php echo template::file('configModulesInstallation', [
								'label' => 'Archive ZIP :',
								'type' => 2
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('configModulesCheck', true, 'Mise à jour forcée', [
								'checked' => false,
								'help' => 'Permet de forcer une mise à jour même si la version du module est inférieure ou égale à celle du module installé.',
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
<?php if($module::$modInstal): ?>
	<?php echo template::table([2, 3, 2, 3, 1, 1], $module::$modInstal, ['Module installé', 'Alias', 'Version', 'Page(s)', 'Supprimer', 'Exporter']); ?>
<?php else: ?>
	<?php echo template::speech('Aucun module installé.'); ?>
<?php endif; ?>





<?php
/*	var_dump(  helper::getModules( 'module' ) );
	echo '<br><br>';
	var_dump( helper::getModules('site/tmp/toto/module') );*/
?>

