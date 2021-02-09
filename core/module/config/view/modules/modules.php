<?php echo template::formOpen('configModulesGestion'); ?>
    <div class="row">
        <div class="col2">
            <?php echo template::button('configModulesBack', [
                'class' => 'buttonGrey',
                'href' => helper::baseUrl() . 'config',
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
			<h4>Installer un module </h4>
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
						<?php echo template::checkbox('configModulesCheck', true, 'Valider la mise à jour d\'un module déjà installé', [
								'checked' => false,
								'help' => 'Vérifier sur le forum que ce module supporte la mise à jour par réinstallation des fichiers.',
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php echo template::formClose(); ?>

<?php if($module::$modInstal): ?>
	<?php echo template::table([3, 3, 1, 1, 3, 1], $module::$modInstal, ['Module installé', 'alias', 'version', 'utilisé', 'page(s)', '']); ?>
<?php else: ?>
	<?php echo template::speech('Aucun module installé.'); ?>
<?php endif; ?>