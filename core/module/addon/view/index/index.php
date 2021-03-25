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
		<?php echo template::button('configModulesStore', [
			'href' => helper::baseUrl() . 'addon/store',
			'value' => 'Catalogue'
		]); ?>
	</div>
</div>
<?php if($module::$modInstal): ?>
	<?php echo template::table([2, 2, 2, 3, 1, 1, 1], $module::$modInstal, ['Module installé', 'Alias', 'Version', 'Page(s)', 'Supprimer', 'Exporter' . '<span class="helpDisplayButton">'.template::ico('help', 'left').'</span>', 'Importer']); ?>
<?php else: ?>
	<?php echo template::speech('Aucun module installé.'); ?>
<?php endif; ?>
<div class="helpDisplayContent">
	<p>Exporter produit une archive au nom du module contenant les pages concernées ainsi que les données et ressources utilisées par le module dans ces pages.</p>
	<p>Vous pouvez vous en servir comme d'une sauvegarde partielle ou pour transférer les pages et les données du module vers un autre site.</p>
	<p>Une fois le module installé l'import permet de restaurer les pages et les données sauvegardées.
	Si une page de même nom existe sur votre site vous serez invité à modifier son nom.</p>
</div>
