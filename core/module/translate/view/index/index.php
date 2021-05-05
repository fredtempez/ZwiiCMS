<?php echo template::formOpen('translateForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('translateFormBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl(),
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col3 offset5">
		<?php echo template::button('configAdvancedButton', [
			'href' => helper::baseUrl() . 'translate/copy',
			'value' => 'Utilitaire de copie',
			'ico' => 'cog-alt',
			'disabled' => $module::$siteTranslate
		]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('translateFormSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>Traduction automatique
				<span class="helpDisplayButton">
					<?php echo template::ico('help', 'left');?>
				</span>
			</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('translateScriptGoogle', true, 'Active le script de traduction automatique', [
								'checked' => $this->getData(['config','i18n', 'scriptGoogle']),
								'help' => 'Le script Google Translate assure la traduction automatique du site.'
							]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('translateAutoDetect', true, 'Détection automatique de la langue du navigateur', [
							'checked' => $this->getData(['config','i18n', 'autoDetect']),
							'class' => 'translateGoogleScriptOption',
							'help'   => 'Détecte la langue du navigateur, dans ce mode il n\'est pas nécessaire d\'afficher les drapeaux.'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('translateCredits', true, 'Afficher les crédits du script Google', [
							'checked' => $this->getData(['config','i18n', 'showCredits']),
							'class' => 'translateGoogleScriptOption',
							'help' => 'Option recommandée pour le respect du droit d\'auteur'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('translateAdmin', true, 'Traduction en mode connecté', [
								'checked' => $this->getData(['config','i18n', 'admin']),
								'class' => 'translateGoogleScriptOption',
								'help'   => 'Traduit le site et l\'interface de ZwiiCMS quand un utilisateur est connecté'
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block" id="flagsWrapper">
			<h4>Mode de traduction et affichage des drapeaux</h4>
				<div class="row">
					<div class="col4 offset4">
						<?php echo template::select('translateFR', ['none'=>'Drapeau masqué','site'=>'Drapeau affiché'], [
							'label' => 'Français',
							'selected' => $this->getData(['config', 'i18n' , 'fr'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<div class="col8 offset2">
							<?php echo template::select('translateDE', $module::$translateOptions['de'], [
								'label' => 'Allemand',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'i18n' , 'de'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translateEN', $module::$translateOptions['en'], [
								'label' => 'Anglais',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'i18n' , 'en'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translateES', $module::$translateOptions['es'], [
								'label' => 'Espagnol',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'i18n' , 'es'])
							]); ?>
						</div>
					</div>
					<div class="col6">
						<div class="col8 offset2">
							<?php echo template::select('translateIT', $module::$translateOptions['it'], [
								'label' => 'Italien',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'i18n' , 'it'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translateNL', $module::$translateOptions['nl'], [
								'label' => 'Néerlandais',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'i18n' , 'nl'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translatePT', $module::$translateOptions['pt'], [
								'label' => 'Portugais',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'i18n' , 'pt'])
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col10 helpDisplayContent">
		<p>Vous avez le choix entre une traduction automatique réalisée avec le script Google Traduction ou une traduction rédigée. La traduction automatique offre deux possibilités, la détection automatique de la langue du navigateur même celle-ci n'est pas
		matérialisée par un drapeau. Le clic sur un drapeau forcera la traduction dans cette langue, pour cela sélectionnez le drapeau puis l'option de "Traduction automatique".
		La traduction automatique est réalisée à partir du site en version française.</p>
		<p>Avec l'option "Traduction rédigée", une nouveau sité est généré, seule la page d'accueil est crée, il faut alors rédiger les pages dans la langue sélectionnée.
		Il est possible de copier les pages et les modules  d'une langue vers une autre à l'aide de l'utilitaire de copie.
		Quand un drapeau est masqué, la traduction est effacée, pensez à sauvegarder.
		Afficher le drapeau français  pour revenir à la traduction dans la langue originale.</p>
	</div>
<?php echo template::formClose(); ?>