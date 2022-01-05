<div class="row">
	<div class="col2">
		<?php echo template::button('userAddBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(false),
			'ico' => 'home',
			'value' => 'Accueil'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('userHelp', [
			'href' => 'https://doc.zwiicms.fr/gestion-des-utilisateurs',
			'target' => '_blank',
			'ico' => 'help',
			'value' => 'Aide',
			'class' => 'buttonHelp'
		]); ?>
	</div>
	<div class="col2 offset4">
		<?php echo template::button('userImport', [
			'href' => helper::baseUrl() . 'user/import',
			'ico' => 'plus',
			'value' => 'Importation'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('userAdd', [
			'href' => helper::baseUrl() . 'user/add',
			'ico' => 'plus',
			'value' => 'Utilisateur'
		]); ?>
	</div>
</div>
<?php echo template::table([3, 4, 3, 1, 1], $module::$users, ['Identifiant', 'Nom', 'Groupe', '', '']); ?>