<?php echo template::formOpen('gestion_categorie'); ?>

<div class="row">
	<div class="col2">
		<?php echo template::button('edition_retour', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0).'/config',
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2 offset8">
		<?php echo template::submit('edition_enregistrer',[
			'ico' => 'check'
		]); ?>
	</div>
</div>

<div class="block">
	<h4>Choix des couleurs des évènements par catégorie</h4>
	
	<div class="row">
		<div class="col4">
			<?php echo template::checkbox('val_categories', true, 'Choix des couleurs par catégorie', [
				'checked' => $this->getData(['module', $this->getUrl(0), 'categories', 'valCategories']),
				'help' => 'Si vous cochez cette case le choix des couleurs des évènements de l\'agenda se fera par catégorie.'
			]); ?>
		</div>
	</div>
</div>

<div class="block">
	<h4>Création ou modification d'une catégorie</h4>
	<div class="row">
		<div class="col4">
			<?php
			echo template::text('categorie_name', [
				'help' => 'Saisir un nom de catégorie, majuscules, accentuées, espaces autorisés',
				'label' => 'Nom de la catégorie'
			]);
			?>	
		</div>
		<div class="col4">
			<?php echo template::text('categorie_couleur_fond', [
				'class' => 'colorPicker',
				'help' => 'Le curseur horizontal règle le niveau de transparence.',
				'label' => 'Fond',
				'value' => 'rgba(0,0,0,1)'
			]); ?>
		</div>
		<div class="col4">
			<?php echo template::text('categorie_couleur_texte', [
				'class' => 'colorPicker',
				'help' => 'Le curseur horizontal règle le niveau de transparence.',
				'label' => 'Texte',
				'value' => 'rgba(255,255,255,1)'
			]); ?>
		</div>
	</div>
</div>

<?php echo template::formClose(); ?>

<?php echo template::table([5,3,3,1], $module::$tabCategories, ['Nom', 'Couleur du fond','couleur du texte','']); ?>

<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>