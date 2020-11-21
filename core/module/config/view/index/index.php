<?php echo template::formOpen('configForm'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('configBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(false),
			'ico' => 'home',
			'value' => 'Accueil'
		]); ?>
	</div>
	<div class="col2 offset2">
		<?php echo template::button('configManageButton', [
			'href' => helper::baseUrl() . 'config/backup',
			'value' => 'Sauvegarder',
			'ico' => 'download'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('configManageButton', [
			'href' => helper::baseUrl() . 'config/manage',
			'value' => 'Restaurer',
			'ico' => 'upload'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('configAdvancedButton', [
			'href' => helper::baseUrl() . 'config/advanced',
			'value' => 'Avancée',
			'ico' => 'cog-alt',
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::submit('configSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Identité du site</h4>
			<div class="row">
				<div class="col9">
					<?php echo template::text('configTitle', [
						'label' => 'Titre du site',
						'value' => $this->getData(['locale', 'title']),
						'help'  => 'Il apparaît dans la barre de titre et les partages sur les réseaux sociaux.'
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::text('configVersion', [
						'label' => 'ZwiiCMS Version',
						'value' => common::ZWII_VERSION,
						'readonly' => true
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::textarea('configMetaDescription', [
						'label' => 'Description du site',
						'value' => $this->getData(['locale', 'metaDescription']),
						'help'  => 'La description d\'une page participe à son référencement, chaque page doit disposer d\'une description différente.'
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Pages spéciales</h4>
			<div class="row">
				<div class="col4">
					<?php
						$pages = $this->getData(['page']);
						foreach($pages as $page => $pageId) {
							if ($this->getData(['page',$page,'block']) === 'bar' ||
								$this->getData(['page',$page,'disable']) === true) {
								unset($pages[$page]);
							}
						}
						$orphans =  $this->getData(['page']);
						foreach($orphans as $page => $pageId) {
							if ($this->getData(['page',$page,'block']) === 'bar' ||
								$this->getData(['page',$page,'disable']) === true ||
								$this->getdata(['page',$page, 'position']) !== 0) {
								unset($orphans[$page]);
							}
						}
						echo template::select('configHomePageId', helper::arrayCollumn($pages, 'title', 'SORT_ASC'), [
						'label' => 'Accueil du site',
						'selected' =>$this->getData(['locale', 'homePageId']),
						'help' => 'La première page que vos visiteurs verront.'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('configLegalPageId', array_merge(['none' => 'Aucune'] , helper::arrayCollumn($pages, 'title', 'SORT_ASC') ) , [
						'label' => 'Mentions légales',
						'selected' => $this->getData(['locale', 'legalPageId']),
						'help' => 'Les mentions légales sont obligatoires en France. Une option du pied de page ajoute un lien discret vers cette page.'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('configSearchPageId', array_merge(['none' => 'Aucune'] , helper::arrayCollumn($pages, 'title', 'SORT_ASC') ) , [
						'label' => 'Recherche dans le site',
						'selected' => $this->getData(['locale', 'searchPageId']),
						'help' => 'Sélectionner la page "Recherche" ou une page contenant le module "Recherche" permet d\'activer un lien dans le pied de page. '
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php
						echo template::select('configPage403', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($orphans, 'title', 'SORT_ASC')), [
							'label' => 'Accès interdit, erreur 403',
							'selected' =>$this->getData(['locale', 'page403']),
							'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
						]); ?>
				</div>
				<div class="col4">
					<?php
						echo template::select('configPage404', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($orphans, 'title', 'SORT_ASC')), [
							'label' => 'Page inexistante, erreur 404',
							'selected' =>$this->getData(['locale', 'page404']),
							'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
						]); ?>
				</div>
				<div class="col4">
					<?php
						echo template::select('configPage302', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($orphans, 'title', 'SORT_ASC')), [
							'label' => 'Site en maintenance',
							'selected' =>$this->getData(['locale', 'page302']),
							'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
						]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>
