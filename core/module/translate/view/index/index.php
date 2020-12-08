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
		<div class="col2 offset6">
		<?php echo template::button('configAdvancedButton', [
			'href' => helper::baseUrl() . 'translate/advanced',
			'value' => 'Avancée',
			'ico' => 'cog-alt',
		]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('translateFormSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block" id="flagsWrapper">
			<h4>Mode de traduction et affichage des drapeaux
				<span class="helpDisplayButton">
					<?php echo template::ico('help', 'left');?>
				</span>
			</h4>
				<div class="row">
					<div class="col4 offset4">
						<?php echo template::select('translateFR', ['none'=>'Drapeau masqué','site'=>'Drapeau affiché'], [
							'label' => 'Français',
							'selected' => $this->getData(['config', 'translate' , 'fr'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<div class="col8 offset2">
							<?php echo template::select('translateDE', $module::$translateOptions['de'], [
								'label' => 'Allemand',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'translate' , 'de'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translateEN', $module::$translateOptions['en'], [
								'label' => 'Anglais',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'translate' , 'en'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translateES', $module::$translateOptions['es'], [
								'label' => 'Espagnol',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'translate' , 'es'])
							]); ?>
						</div>
					</div>
					<div class="col6">
						<div class="col8 offset2">
							<?php echo template::select('translateIT', $module::$translateOptions['it'], [
								'label' => 'Italien',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'translate' , 'it'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translateNL', $module::$translateOptions['nl'], [
								'label' => 'Néerlandais',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'translate' , 'nl'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translatePT', $module::$translateOptions['pt'], [
								'label' => 'Portugais',
								'class' => 'translateFlagSelect',
								'selected' => $this->getData(['config', 'translate' , 'pt'])
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>Paramètres de la traduction automatique</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('translateScriptGoogle', true, 'Active le script de traduction automatique', [
								'checked' => $this->getData(['config','translate', 'scriptGoogle']),
								'help' => 'Le script Google Translate assure la traduction automatique du site.'
							]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('translateCredits', true, 'Afficher les crédits du script Google', [
							'checked' => $this->getData(['config','translate', 'showCredits']),
							'class' => 'translateGoogleScriptOption',
							'help' => 'Option recommandée pour le respect du droit d\'auteur'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('translateAutoDetect', true, 'Détection automatique de la langue', [
							'checked' => $this->getData(['config','translate', 'autoDetect']),
							'class' => 'translateGoogleScriptOption',
							'help'   => 'Détecte la langue du navigateur, dans ce mode il n\'est pas nécessaire d\'afficher les drapeaux.'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('translateAdmin', true, 'Traduction en mode connecté', [
								'checked' => $this->getData(['config','translate', 'admin']),
								'class' => 'translateGoogleScriptOption',
								'help'   => 'Traduit le site et l\'interface de ZwiiCMS quand un utilisateur est connecté'
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col10 helpDisplayContent">
		<p>Vous avez le choix entre une traduction automatique réalisée avec le script Google Traduction ou une traduction rédigée.
		Si vous sélectionnez la traduction rédigée, seule la page d'accueil est générée, à vous de rédiger le site dans la langue sélectionnée.
		Il est cependant possible de copier les pages et les modules  d'une langue vers une autre en cliquant sur le bouton de gestion avancée.</p>
		<p>Une traduction peut être cachée en masquant le drapeau, la suppression d'une traduction rédigée est définitive, pensez à sauvegarder.
		Afficher le drapeau français afin de revenir à la traduction originale.</p>
	</div>
<?php echo template::formClose(); ?>