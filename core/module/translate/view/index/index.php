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
			<h4>Langues supportées</h4>
				<div class="row">
					<div class="col6">
						<b>Traduction automatique :</b>
						<div class="col12">
						<?php echo template::checkbox('translateScriptFlagDE', true, 'Allemand', [
							'checked' => $this->getData(['config','translate', 'scriptDE'])
						]); ?>
						</div>
						<div class="col12">
							<?php echo template::checkbox('translateScriptFlagEN', true, 'Anglais', [
								'checked' => $this->getData(['config','translate', 'scriptEN'])
							]); ?>
						</div>
						<div class="col12">
							<?php echo template::checkbox('translateScriptFlagES', true, 'Espagnol', [
								'checked' => $this->getData(['config','translate', 'scriptES'])
								]); ?>
						</div>
						<div class="col12">
							<?php echo template::checkbox('translateScriptFlagIT', true, 'Italien', [
								'checked' => $this->getData(['config','translate', 'scriptIT'])
							]); ?>
						</div>
						<div class="col12">
							<?php echo template::checkbox('translateScriptFlagNL', true, 'Néerlandais', [
								'checked' => $this->getData(['config','translate', 'scriptNL'])
							]); ?>
						</div>
						<div class="col12">
							<?php echo template::checkbox('translateScriptFlagPT', true, 'Portugais', [
								'checked' => $this->getData(['config','translate', 'scriptPT'])
							]); ?>
						</div>
						<div class="col12">
							<?php echo template::checkbox('translateAutoDetect', true, 'Détection automatique', [
								'checked' => $this->getData(['config','translate', 'autoDetect']),
								'help'   => 'Détecte la langue du navigateur.'
							]); ?>

						</div>
					</div>
					<div class="col6">
					<b>Traduction rédigée :</b>
						<div class="col12">
							<?php echo template::checkbox('translateSiteFlagEN', true, 'Anglais', [
								'checked' => $this->getData(['config', 'translate', 'siteEN'])
							]); ?>
						</div>
						<div class="col12">
								<?php echo template::checkbox('translateSiteFlagDE', true, 'Allemand', [
									'checked' => $this->getData(['config', 'translate', 'siteDE'])
								]); ?>
						</div>
						<div class="col12">
							<?php echo template::checkbox('translateSiteFlagES', true, 'Espagnol', [
								'checked' => $this->getData(['config', 'translate', 'siteES'])
								]); ?>
						</div>
						<div class="col12">
							<?php echo template::checkbox('translateSiteFlagIT', true, 'Italien', [
								'checked' => $this->getData(['config', 'translate', 'siteIT'])
							]); ?>
						</div>
						<div class="col12">
							<?php echo template::checkbox('translateSiteFlagNL', true, 'Néerlandais', [
								'checked' => $this->getData(['config', 'translate', 'siteNL'])
							]); ?>
						</div>
						<div class="col12">
							<?php echo template::checkbox('translateSiteFlagPT', true, 'Portugais', [
								'checked' => $this->getData(['config', 'translate', 'sitePT'])
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
				<h4>Paramètres de traduction automatique</h4>
					<div class="row">
						<div class="col4">
							<?php echo template::checkbox('translateScriptGoogle', true, 'Active le script de traduction automatique', [
									'checked' => $this->getData(['config','translate', 'scriptGoogle'])
								]); ?>
						</div>
						<div class="col4">
							<?php echo template::checkbox('translateAdmin', true, 'Mode connexion', [
									'checked' => $this->getData(['config','translate', 'admin']),
									'help' => 'Traduction automatique du site et de l\'interface du CMS'
								]); ?>
						</div>
						<div class="col4">
							<?php echo template::checkbox('translateCredits', true, 'Afficher les crédits du script Google', [
								'checked' => $this->getData(['config','translate', 'showCredits']),
								'help' => 'Option vivement recommandée pour le respect du droit d\'auteur'
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>