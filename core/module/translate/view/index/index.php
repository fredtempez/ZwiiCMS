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
			'value' => template::ico('cogs'),
			'disabled' => $module::$siteTranslate,
			'help' => 'Utilitaire de copie de site inter-langues'
		]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('translateFormSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>Traduction automatique</h4>
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
