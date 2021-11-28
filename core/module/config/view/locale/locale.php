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
				<h4>Assignation des pages spéciales <?php echo template::flag('site', '20px');?>
					<span id="localeHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/localisation-et-identite" target="_blank">
							<?php echo template::ico('help', 'left');?>
						</a>
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
					<div class="col6">
						<?php echo template::text('localeLegalPageLabel', [
							'label' => 'Mentions légales',
							'placeholder' => 'Mentions légales',
							'value' => $this->getData(['locale', 'legalPageLabel'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('localeSearchPageLabel', [
							'label' => 'Rechercher',
							'placeholder' => 'Rechercher',
							'value' => $this->getData(['locale', 'searchPageLabel'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::text('localeSitemapPageLabel', [
							'label' => 'Plan du site',
							'placeholder' => 'Plan du site',
							'value' => $this->getData(['locale', 'sitemapPageLabel']),
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('localeCookiesFooterText', [
							'label' => 'Confidentialité des cookies',
							'value' => $this->getData(['locale', 'cookies', 'cookiesFooterText']),
							'placeHolder' => 'Confidentialité'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Message d'acceptation des Cookies <?php echo template::flag('site', '20px');?>
					<span id="specialeHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/cookies" target="_blank">
								<?php echo template::ico('help', 'left');?>
						</a>
					</span>
				</h4>
				<div class="row">
					<div class="col12">
						<?php echo template::text('localeCookiesTitleText', [
							'help' => 'Saisissez le titre de la fenêtre de gestion des cookies.',
							'label' => 'Titre de la fenêtre',
							'value' => $this->getData(['locale', 'cookies', 'cookiesTitleText']),
							'placeHolder' => 'Gérer les cookies'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col8">
						<?php echo template::textarea('localeCookiesZwiiText', [
							'help' => 'Saisissez le message pour les cookies déposés par ZwiiCMS, nécessaires au fonctionnement et qui ne nécessitent pas de consentement.',
							'label' => 'Cookies Zwii',
							'value' => $this->getData(['locale', 'cookies', 'cookiesZwiiText']),
							'placeHolder' => 'Ce site utilise des cookies nécessaires à son fonctionnement, ils permettent de fluidifier son fonctionnement par exemple en mémorisant les données de connexion, la langue que vous avez choisie ou la validation de ce message.'
						]); ?>
					</div>

					<div class="col4">
						<?php echo template::text('localeCookiesLinkMlText', [
							'help' => 'Saisissez le texte du lien vers les mentions légales,la page doit être définie dans la configuration du site.',
							'label' => 'Lien page des mentions légales.',
							'value' => $this->getData(['locale', 'cookies', 'cookiesLinkMlText']),
							'placeHolder' => 'Consulter  les mentions légales'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col8">
						<?php echo template::textarea('localeCookiesGaText', [
							'help' => 'Saisissez le message pour les cookies déposés par Google Analytics, le consentement est requis.',
							'label' => 'Cookies Google Analytics',
							'value' => $this->getData(['locale', 'cookies', 'cookiesGaText']),
							'placeHolder' => 'Il utilise également des cookies permettant de réaliser des statistiques de visites pour améliorer votre expérience utilisateur, ces cookies déposés par Google Analytics ont besoin de votre consentement.'
						]); ?>
					</div>

					<div class="col4">
						<?php echo template::text('localeCookiesCheckboxGaText', [
							'help' => 'Saisissez le texte de la case à cocher Google Analytics.',
							'label' => 'Checkbox Google Analytics',
							'value' => $this->getData(['locale', 'cookies', 'cookiesCheckboxGaText']),
							'placeHolder' => 'Autorisation des cookies Google Analytics'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4 offset4">
						<?php echo template::text('localeCookiesButtonText', [
							'label' => 'Bouton de validation',
							'value' => $this->getData(['locale', 'cookies', 'cookiesButtonText']),
							'placeHolder' => 'J\'ai compris'
						]); ?>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>