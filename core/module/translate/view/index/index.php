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
			<h4>Paramètres</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('translateActivated', true, 'Activer le mode multi-langues', [
								'checked' => $this->getData(['config','translate', 'activated'])
							]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('translateAdmin', true, 'Traduire les pages d\'administration', [
								'checked' => $this->getData(['config','translate', 'admin'])
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>Traduction automatique</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('translateAutoDetect', true, 'Détection automatique de langue', [
							'checked' => $this->getData(['config','translate', 'autoDetect']),
							'help'   => 'Détecte la langue du navigateur et effectue une traduction grâce à Google Translate.'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('translateCredits', true, 'Afficher les crédits du script Google', [
							'checked' => $this->getData(['config','translate', 'showCredits']),
							'help' => 'Option vivement recommandée pour le respect du droit d\'auteur'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
						<?php echo template::checkbox('translateFlagFR', true, 'Français', [
							'checked' => $this->getData(['config','translate', 'flagFR'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::checkbox('translateFlagDE', true, 'Allemand', [
							'checked' => $this->getData(['config','translate', 'flagDE'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::checkbox('translateFlagEN', true, 'Anglais', [
							'checked' => $this->getData(['config','translate', 'flagEN'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::checkbox('translateFlagES', true, 'Espagnol', [
							'checked' => $this->getData(['config','translate', 'flagES'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
						<?php echo template::checkbox('translateFlagIT', true, 'Italien', [
							'checked' => $this->getData(['config','translate', 'flagIT'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::checkbox('translateFlagNL', true, 'Néerlandais', [
							'checked' => $this->getData(['config','translate', 'flagNL'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::checkbox('translateFlagPT', true, 'Portugais', [
							'checked' => $this->getData(['config','translate', 'flagPT'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>