<?php
$i18nSite = 'fr';
if ( isset($_COOKIE['ZWII_I18N_SITE'])	) {
		$i18nSite = $_COOKIE['ZWII_I18N_SITE'];
}
?>
<?php echo template::formOpen('configForm');?>
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
	<div class="col2 offset4">
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
<!-- Aide à propos de la configuration du site, view index -->
<div class="helpDisplayContent">
	<?php echo file_get_contents( 'core/module/config/view/index/index.help.html') ;?>
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
			<h4>Identité du site</h4>
			<div class="row">
				<div class="col9">
					<?php echo template::text('configTitle', [
						'label' => 'Titre du site '. $i18nSite,
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
						'label' => 'Description du site ' . $i18nSite,
						'value' => $this->getData(['locale', 'metaDescription']),
						'help'  => 'La description d\'une page participe à son référencement, chaque page doit disposer d\'une description différente.'
					]); ?>
					
				</div>
			</div
			><p>Le titre et la description sont spécifiques aux traductions rédigées du site.</p>
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
			</div>
			<div class="row">
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
						'help' => 'Sélectionnez une page contenant le module \'Recherche\'. Une option du pied de page ajoute un lien discret vers cette page.'
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
			<p>Adaptez la sélection de ces pages pour chaque traduction rédigée.</p>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Etiquettes des pages spéciales dans le pied de page</h4>
			<div class="row">
				<div class="col4">
					<?php echo template::text('configLegalPageLabel', [
						'label' => 'Mentions légales ' . $i18nSite,
						'placeholder' => 'Mentions légales',
						'value' => $this->getData(['locale', 'legalPageLabel']),
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::text('configSearchPageLabel', [
						'label' => 'Rechercher ' . $i18nSite,
						'placeholder' => 'Rechercher',
						'value' => $this->getData(['locale', 'searchPageLabel']),
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::text('configSitemapLabel', [
						'label' => 'Plan du site ' . $i18nSite,
						'placeholder' => 'Plan du site',
						'value' => $this->getData(['locale', 'sitemaplabel']),
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>
