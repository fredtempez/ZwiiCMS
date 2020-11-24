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
			</div>
			<div class="row">
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>