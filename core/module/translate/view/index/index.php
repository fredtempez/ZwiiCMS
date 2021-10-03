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
		<div class="col2">
			<?php echo template::button('translateAdvancedHelp', [
				'class' => 'buttonHelp',
				'ico' => 'help',
				'value' => 'Aide'
			]); ?>
		</div>
		<div class="col3 offset3">
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
	<!-- Aide à propos de la configuration du site, view advanced -->
	<div class="helpDisplayContent">
		<?php echo file_get_contents( 'core/module/translate/view/index/index.help.html') ;?>
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
<?php echo template::formClose(); ?>
