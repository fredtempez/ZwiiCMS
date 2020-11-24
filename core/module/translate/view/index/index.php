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
			<h4>Configuration</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('translateActive', true, 'Traduction automatique', [
								'checked' => $this->getData(['config', 'translate', 'active']),
								'help'   => 'Traduction automatique du site hors connexion par le script Google Translate selon la langue du navigateur du visiteur.'
							]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('translateCredits', true, 'Afficher les crédits', [
								'checked' => $this->getData(['config', 'translate', 'showCredits']),
								'help' => 'Option vivement recommandée pour le respect du droit d\'auteur'
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>