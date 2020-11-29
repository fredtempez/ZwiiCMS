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
		<div class="col2 offset8">
			<?php echo template::submit('translateFormSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>Activation</h4>
				<div class="row">
					<div class="col4 offset4">
						<?php echo template::select('translateFR', ['non'=>'Masqué','site'=>'Affiché'], [
							'label' => 'Français',
							'selected' => $this->getData(['config', 'translate' , 'fr'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<div class="col8 offset2">
							<?php echo template::select('translateDE', $module::$typeTranslate, [
								'label' => 'Allemand',
								'selected' => $this->getData(['config', 'translate' , 'de'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translateEN', $module::$typeTranslate, [
								'label' => 'Anglais',
								'selected' => $this->getData(['config', 'translate' , 'en'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translateES', $module::$typeTranslate, [
								'label' => 'Espagnol',
								'selected' => $this->getData(['config', 'translate' , 'es'])
							]); ?>
						</div>
					</div>
					<div class="col6">
						<div class="col8 offset2">
							<?php echo template::select('translateIT', $module::$typeTranslate, [
								'label' => 'Italien',
								'selected' => $this->getData(['config', 'translate' , 'it'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translateNL', $module::$typeTranslate, [
								'label' => 'Néerlandais',
								'selected' => $this->getData(['config', 'translate' , 'nl'])
							]); ?>
						</div>
						<div class="col8 offset2">
							<?php echo template::select('translatePT', $module::$typeTranslate, [
								'label' => 'Portugais',
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
							'help' => 'Option recommandée pour le respect du droit d\'auteur'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('translateAutoDetect', true, 'Détection automatique de la langue', [
							'checked' => $this->getData(['config','translate', 'autoDetect']),
							'help'   => 'Détecte la langue du navigateur, dans ce mode il n\'est pas nécessaire d\'afficher les drapeaux.'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('translateAdmin', true, 'Traduction en mode connecté', [
								'checked' => $this->getData(['config','translate', 'admin']),
								'help'   => 'Traduit le site et l\'interface de ZwiiCMS quand un utilisateur est connecté'
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>