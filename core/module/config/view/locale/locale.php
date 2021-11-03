<div id="localeContainer">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Langues étrangères</h4>
				<div class="row">
					<div class="col12">
						<?php echo template::checkbox('localei18n', true, 'Activer la gestion des langues étrangères', [
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
				<h4>Identité du site <?php echo template::flag('site', '20px');?>
					<span id="localeHelpButton" class="helpDisplayButton">
					<a href="https://doc.zwiicms.fr/localisation-et-identite" target="_blank">
						<?php echo template::ico('help', 'left');?>
					</a>
					</span>
				</h4>				
				<div class="row">
					<div class="col9">
						<?php echo template::text('localeTitle', [
							'label' => 'Titre du site' ,
							'value' => $this->getData(['locale', 'title']),
							'help'  => 'Il apparaît dans la barre de titre et les partages sur les réseaux sociaux.'
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('localeVersion', [
							'label' => 'ZwiiCMS Version',
							'value' => common::ZWII_VERSION,
							'readonly' => true
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col12">
						<?php echo template::textarea('localeMetaDescription', [
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
				<h4>Assignation des pages spéciales
					<a href="https://doc.zwiicms.fr/assignation-des-pages-speciales" target="_blank">	
					<span id="specialeHelpButton" class="helpDisplayButton">
						<?php echo template::ico('help', 'left');?>
					</span>
				</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::select('localeHomePageId', helper::arrayCollumn($module::$pagesList, 'title', 'SORT_ASC'), [
								'label' => 'Accueil du site',
								'selected' =>$this->getData(['locale', 'homePageId']),
								'help' => 'La première page que vos visiteurs verront.'
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('localePage403', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($module::$orphansList, 'title', 'SORT_ASC')), [
								'label' => 'Accès interdit, erreur 403',
								'selected' =>$this->getData(['locale', 'page403']),
								'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
							]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('localePage404', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($module::$orphansList, 'title', 'SORT_ASC')), [
								'label' => 'Page inexistante, erreur 404',
								'selected' =>$this->getData(['locale', 'page404']),
								'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
							]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4">
						<?php echo template::select('localeLegalPageId', array_merge(['none' => 'Aucune'] , helper::arrayCollumn($module::$pagesList, 'title', 'SORT_ASC') ) , [
							'label' => 'Mentions légales',
							'selected' => $this->getData(['locale', 'legalPageId']),
							'help' => 'Les mentions légales sont obligatoires en France. Une option du pied de page ajoute un lien discret vers cette page.'
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('localeSearchPageId', array_merge(['none' => 'Aucune'] , helper::arrayCollumn($module::$pagesList, 'title', 'SORT_ASC') ) , [
							'label' => 'Recherche dans le site',
							'selected' => $this->getData(['locale', 'searchPageId']),
							'help' => 'Sélectionnez une page contenant le module \'Recherche\'. Une option du pied de page ajoute un lien discret vers cette page.'
						]); ?>
					</div>
					<div class="col4">
						<?php
							echo template::select('localePage302', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($module::$orphansList, 'title', 'SORT_ASC')), [
								'label' => 'Site en maintenance',
								'selected' =>$this->getData(['locale', 'page302']),
								'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>Etiquettes des pages spéciales <?php echo template::flag('site', '20px');?>
					<span id="labelHelpButton" class="helpDisplayButton">
					<a href="https://doc.zwiicms.fr/etiquettes-des-pages-speciales" target="_blank">
						<?php echo template::ico('help', 'left');?>
						</a>
					</span>
				</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::text('localeLegalPageLabel', [
							'label' => 'Mentions légales',
							'placeholder' => 'Mentions légales',
							'value' => $this->getData(['locale', 'legalPageLabel'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::text('localeSearchPageLabel', [
							'label' => 'Rechercher',
							'placeholder' => 'Rechercher',
							'value' => $this->getData(['locale', 'searchPageLabel'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::text('localeSitemapPageLabel', [
							'label' => 'Plan du site',
							'placeholder' => 'Plan du site',
							'value' => $this->getData(['locale', 'sitemapPageLabel']),
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>