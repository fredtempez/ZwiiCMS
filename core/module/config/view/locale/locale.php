<?php echo template::formOpen('configForm');?>
<!-- Aide à propos de la configuration du site, view index -->
<div class="helpDisplayContent">
	<?php echo file_get_contents( 'core/module/config/view/locale/locale.help.html') ;?>
</div>
<div class="row">
	<div class="col2">
		<?php echo template::button('configBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(false),
			'ico' => 'home',
			'value' => 'Accueil'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('addonIndexHelp', [
			'class' => 'buttonHelp',
			'ico' => 'help',
			'value' => 'Aide'
		]); ?>
	</div>
	<div class="col2 offset6">
		<?php echo template::submit('configSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="row textAlignCenter">
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/index',
					'value' => 'Paramètres'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/locale',
					'value' => 'Localisation'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/social',
					'value' => 'Référencement'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/safety',
					'value' => 'Sécurité'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/network',
					'value' => 'Réseau'
				]); ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Langues étrangères</h4>
			<div class="row">
				<div class="col12">
					<?php echo template::checkbox('configI18n', true, 'Activer la gestion des langues étrangères', [
							'checked' => $this->getData(['config', 'i18n', 'enable']),
							'help'=> 'Une nouvelle icône apparaîtra dans la barre d\'administration. Consultez  l\'aide de la page concernée pour en apprendre plus.'
						]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Identité du site (en langue <?php echo template::flag('site', '20px');?> )</h4>
			<p><em>Cette page doit être adaptée à chaque traduction rédigée.</em></p>
			<div class="row">
				<div class="col9">
					<?php echo template::text('configTitle', [
						'label' => 'Titre du site' ,
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
<div class="row">
	<div class="col12">
		<div class="block">
		<h4>Etiquettes des pages spéciales</h4>
			<div class="row">
				<div class="col4">
					<?php echo template::text('configLegalPageLabel', [
						'label' => 'Mentions légales',
						'placeholder' => 'Mentions légales',
						'value' => $this->getData(['locale', 'legalPageLabel'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::text('configSearchPageLabel', [
						'label' => 'Rechercher',
						'placeholder' => 'Rechercher',
						'value' => $this->getData(['locale', 'searchPageLabel'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::text('configSitemapPageLabel', [
						'label' => 'Plan du site',
						'placeholder' => 'Plan du site',
						'value' => $this->getData(['locale', 'sitemapPageLabel']),
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Assignation des pages spéciales</h4>
			<div class="row">
				<div class="col4">
					<?php echo template::select('configHomePageId', helper::arrayCollumn($module::$pagesList, 'title', 'SORT_ASC'), [
							'label' => 'Accueil du site',
							'selected' =>$this->getData(['locale', 'homePageId']),
							'help' => 'La première page que vos visiteurs verront.'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('configPage403', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($module::$orphansList, 'title', 'SORT_ASC')), [
							'label' => 'Accès interdit, erreur 403',
							'selected' =>$this->getData(['locale', 'page403']),
							'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
						]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('configPage404', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($module::$orphansList, 'title', 'SORT_ASC')), [
							'label' => 'Page inexistante, erreur 404',
							'selected' =>$this->getData(['locale', 'page404']),
							'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
						]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::select('configLegalPageId', array_merge(['none' => 'Aucune'] , helper::arrayCollumn($module::$pagesList, 'title', 'SORT_ASC') ) , [
						'label' => 'Mentions légales',
						'selected' => $this->getData(['locale', 'legalPageId']),
						'help' => 'Les mentions légales sont obligatoires en France. Une option du pied de page ajoute un lien discret vers cette page.'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('configSearchPageId', array_merge(['none' => 'Aucune'] , helper::arrayCollumn($module::$pagesList, 'title', 'SORT_ASC') ) , [
						'label' => 'Recherche dans le site',
						'selected' => $this->getData(['locale', 'searchPageId']),
						'help' => 'Sélectionnez une page contenant le module \'Recherche\'. Une option du pied de page ajoute un lien discret vers cette page.'
					]); ?>
				</div>
				<div class="col4">
					<?php
						echo template::select('configPage302', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($module::$orphansList, 'title', 'SORT_ASC')), [
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
