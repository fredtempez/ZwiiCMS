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
	<?php echo template::table([2, 2, 2, 2, 1, 1, 1, 1], $module::$modInstal, ['Module installé', 'Alias', 'Version', 'Page(s)', 'Supprimer', 'Exporter', '<span class="helpDisplayButton">'.template::ico('help', 'left').'</span>', 'Importer']); ?>
<?php else: ?>
	<?php echo template::speech('Aucun module installé.'); ?>
<?php endif; ?>
<div class="col10 helpDisplayContent">
	<p>Exporter produit une archive au nom du module contenant les pages concernées ainsi que les données et ressources utilisées par le module dans ces pages.
	Vous pouvez vous en servir comme d'une sauvegarde partielle ou pour transférer les pages et les données du module vers un autre site.</p>
	<p>Une fois le module installé l'import permet de restaurer les pages et les données sauvegardées. Si une page de même nom existe sur votre site vous serez invité à modifier son nom.</p>

</div>
