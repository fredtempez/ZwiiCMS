<?php echo template::formOpen('translateForm'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('translateFormBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl(),
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col1">
			<?php echo template::button('translateHelp', [
				'href' => 'https://doc.zwiicms.fr/prise-en-charge-des-langues-etrangeres',
				'target' => '_blank',
				'value' => template::ico('help'),
				'class' => 'buttonHelp',
				'help' => 'Consulter l\'aide en ligne'
			]); ?>
		</div>
		<div class="col1 offset7">
		<?php echo template::button('translateButton', [
			'href' => helper::baseUrl() . 'translate/copy',
			'value' => template::ico('docs'),
			'disabled' => $module::$siteTranslate,
			'help' => 'Copie de sites inter-langues'
		]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('translateFormSubmit'); ?>
		</div>
	</div>

	<div class="tab">
		<?php echo template::button('translateUiButton', [
			'value' => 'Interface',
			'class' => 'buttonTab'
		]); ?>
		<?php echo template::button('translateContentButton', [
			'value' => 'Contenu du site',
			'class' => 'buttonTab'
		]); ?>

	</div>

	<div id="uiContainer" class="tabContent">
		<div class="row">
			<div class="col12">
				<div class="block" id="flagsWrapper">
					<h4>
						<?php echo template::topic('Interface'); ?>
					</h4>
					<div class="row">
						<div class="col4 offset4">
							<?php echo template::select('translateUI', $module::$i18nFiles, [
								'label' =>  'Traductions installées',
								'selected' => $this->getData(['config', 'i18n' , 'interface']),
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="contentContainer" class="tabContent">
		<div class="row">
			<div class="col12">
				<div class="block" id="flagsWrapper">
					<h4>
						<?php echo template::topic('Traduction du contenu'); ?>
					</h4>
					<div class="row">
						<div class="col3">
							<?php echo template::select('translateFR', ['none'=>'Drapeau masqué','site'=>'Drapeau affiché'], [
								'label' =>  template::flag('', '30px'),
								'selected' => $this->getData(['config', 'i18n' , 'fr']),
							]); ?>
						</div>
						<div class="col3">
							<div class="col12">
								<?php echo template::select('translateDE', $module::$translateOptions['de'], [
									'label' => template::flag('de', '30px'),
									'class' => 'translateFlagSelect',
									'selected' => $this->getData(['config', 'i18n' , 'de'])
								]); ?>
							</div>
							<div class="col12">
								<?php echo template::select('translateEN', $module::$translateOptions['en'], [
									'label' => template::flag('en', '30px'),
									'class' => 'translateFlagSelect',
									'selected' => $this->getData(['config', 'i18n' , 'en'])
								]); ?>
							</div>
						</div>
						<div class="col3">
							<div class="col12">
								<?php echo template::select('translateES', $module::$translateOptions['es'], [
									'label' =>  template::flag('es', '30px'),
									'class' => 'translateFlagSelect',
									'selected' => $this->getData(['config', 'i18n' , 'es'])
								]); ?>
							</div>
							<div class="col12">
								<?php echo template::select('translateIT', $module::$translateOptions['it'], [
									'label' =>  template::flag('it', '30px'),
									'class' => 'translateFlagSelect',
									'selected' => $this->getData(['config', 'i18n' , 'it'])
								]); ?>
							</div>
						</div>
						<div class="col3">
							<div class="col12">
								<?php echo template::select('translateNL', $module::$translateOptions['nl'], [
									'label' =>  template::flag('nl', '30px'),
									'class' => 'translateFlagSelect',
									'selected' => $this->getData(['config', 'i18n' , 'nl'])
								]); ?>
							</div>
							<div class="col12">
								<?php echo template::select('translatePT', $module::$translateOptions['pt'], [
									'label' =>  template::flag('pt', '30px'),
									'class' => 'translateFlagSelect',
									'selected' => $this->getData(['config', 'i18n' , 'pt'])
								]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col12">
				<h1>Configuration du contenu du site en  <?php echo template::flag('site', '20px');?></h1>
			</div>
		</div>
		<div class="row">
			<div class="col12">
				<div class="block">
					<h4>Identité du site
						<span id="localeHelpButton" class="helpDisplayButton"  title="Cliquer pour consulter l'aide en ligne">
							<a href="https://doc.zwiicms.fr/localisation-et-identite" target="_blank">
								<?php echo template::ico('help', ['margin' => 'left']);?>
							</a>
						</span>
					</h4>
					<div class="row">
						<div class="col12">
							<?php echo template::text('localeTitle', [
								'label' => 'Titre du site' ,
								'value' => $this->getData(['locale', 'title']),
								'help'  => 'Il apparaît dans la barre de titre et les partages sur les réseaux sociaux.'
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
						<span id="localeHelpButton" class="helpDisplayButton"  title="Cliquer pour consulter l'aide en ligne">
							<a href="https://doc.zwiicms.fr/localisation-et-identite" target="_blank">
								<?php echo template::ico('help', ['margin' => 'left']);?>
							</a>
						</span>
					</h4>
					<div class="row">
						<div class="col4">
							<?php echo template::select('localeHomePageId', helper::arrayColumn($module::$pagesList, 'title', 'SORT_ASC'), [
									'label' => 'Accueil du site',
									'selected' =>$this->getData(['locale', 'homePageId']),
									'help' => 'La première page que vos visiteurs verront.'
							]); ?>
						</div>
						<div class="col4">
							<?php echo template::select('localePage403', array_merge(['none' => 'Page par défaut'],helper::arrayColumn($module::$orphansList, 'title', 'SORT_ASC')), [
									'label' => 'Accès interdit, erreur 403',
									'selected' =>$this->getData(['locale', 'page403']),
									'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
								]); ?>
						</div>
						<div class="col4">
							<?php echo template::select('localePage404', array_merge(['none' => 'Page par défaut'],helper::arrayColumn($module::$orphansList, 'title', 'SORT_ASC')), [
									'label' => 'Page inexistante, erreur 404',
									'selected' =>$this->getData(['locale', 'page404']),
									'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
								]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col4">
							<?php echo template::select('localeLegalPageId', array_merge(['none' => 'Aucune'] , helper::arrayColumn($module::$pagesList, 'title', 'SORT_ASC') ) , [
								'label' => 'Mentions légales',
								'selected' => $this->getData(['locale', 'legalPageId']),
								'help' => 'Les mentions légales sont obligatoires en France. Une option du pied de page ajoute un lien discret vers cette page.'
							]); ?>
						</div>
						<div class="col4">
							<?php echo template::select('localeSearchPageId', array_merge(['none' => 'Aucune'] , helper::arrayColumn($module::$pagesList, 'title', 'SORT_ASC') ) , [
								'label' => 'Recherche dans le site',
								'selected' => $this->getData(['locale', 'searchPageId']),
								'help' => 'Sélectionnez une page contenant le module \'Recherche\'. Une option du pied de page ajoute un lien discret vers cette page.'
							]); ?>
						</div>
						<div class="col4">
							<?php
								echo template::select('localePage302', array_merge(['none' => 'Page par défaut'],helper::arrayColumn($module::$orphansList, 'title', 'SORT_ASC')), [
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
					<h4>Etiquettes des pages spéciales
						<span id="labelHelpButton" class="helpDisplayButton"  title="Cliquer pour consulter l'aide en ligne">
							<a href="https://doc.zwiicms.fr/etiquettes-des-pages-speciales" target="_blank">
								<?php echo template::ico('help', ['margin' => 'left']);?>
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
								'label' => 'Cookies',
								'value' => $this->getData(['locale', 'cookies', 'cookiesFooterText']),
								'placeHolder' => 'Cookies'
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col12">
				<div class="block">
					<h4>Message d'acceptation des Cookies
						<span id="specialeHelpButton" class="helpDisplayButton"  title="Cliquer pour consulter l'aide en ligne">
							<a href="https://doc.zwiicms.fr/cookies" target="_blank">
									<?php echo template::ico('help', ['margin' => 'left']);?>
							</a>
						</span>
					</h4>
					<div class="row">
						<div class="col6">
							<?php echo template::text('localeCookiesTitleText', [
								'help' => 'Saisissez le titre de la fenêtre de gestion des cookies.',
								'label' => 'Titre de la fenêtre',
								'value' => $this->getData(['locale', 'cookies', 'titleLabel']),
								'placeHolder' => 'Gérer les cookies'
							]); ?>
						</div>
						<div class="col6">
							<?php echo template::text('localeCookiesButtonText', [
								'label' => 'Bouton de validation',
								'value' => $this->getData(['locale', 'cookies', 'buttonValidLabel']),
								'placeHolder' => 'J\'ai compris'
							]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col8">
							<?php echo template::textarea('localeCookiesZwiiText', [
								'help' => 'Saisissez le message pour les cookies déposés par ZwiiCMS, nécessaires au fonctionnement et qui ne nécessitent pas de consentement.',
								'label' => 'Cookies Zwii',
								'value' => $this->getData(['locale', 'cookies', 'mainLabel']),
								'placeHolder' => 'Ce site utilise des cookies nécessaires à son fonctionnement, ils permettent de fluidifier son fonctionnement par exemple en mémorisant les données de connexion, la langue que vous avez choisie ou la validation de ce message.'
							]); ?>
						</div>

						<div class="col4">
							<?php echo template::text('localeCookiesLinkMlText', [
								'help' => 'Saisissez le texte du lien vers les mentions légales,la page doit être définie dans la configuration du site.',
								'label' => 'Lien page des mentions légales.',
								'value' => $this->getData(['locale', 'cookies', 'linkLegalLabel']),
								'placeHolder' => 'Consulter  les mentions légales'
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
