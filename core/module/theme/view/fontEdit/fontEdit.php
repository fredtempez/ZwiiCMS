<?php echo template::formOpen('fontEditForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('fontEditBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'theme/fonts',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::button('fontEditHelp', [
				'href' => 'https://doc.zwiicms.fr/fontes#add',
				'target' => '_blank',
				'ico' => 'help',
				'value' => 'Aide',
				'class' => 'buttonHelp'
			]); ?>
		</div>
		<div class="col2 offset6">
			<?php echo template::submit('fontEditPublish', [
				'value' => 'Valider',
				'uniqueSubmission' => true
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Identité de la fonte</h4>
				<div class="row">
					<div class="col6">
						<?php switch ($this->getUrl(2)) {
							case 'imported':
								echo template::checkbox('fontEditFontImported', true, 'Fonte en ligne',[
									'checked' => true
								]);
								break;
							case 'files':
								echo template::checkbox('fontEditFontFile', true,'Fonte installée', [
									'checked' => true
								]);
								break;
						}
						?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::text('fontEditFontId', [
							'autocomplete' => 'off',
							'label' => 'Identifiant (sans espace ni majuscule)',
							'value' =>  $this->getUrl(3)

						]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('fontEditFontName', [
							'autocomplete' => 'off',
							'label' => 'Nom',
							'value' => $this->getData(['fonts', $this->getUrl(2), $this->getUrl(3), 'name'])
						]); ?>
					</div>

				</div>
				<div class="row">
					<div class="col12">
					<?php echo template::text('fontEditFontFamilyName', [
							'autocomplete' => 'off',
							'label' => 'Famille',
							'value' => $this->getData(['fonts', $this->getUrl(2), $this->getUrl(3), 'font-family'])
						]); ?>
					</div>
				</div>
				<div class="row" id="containerfontEditFile">
					<div class="col12">
						<?php switch ($this->getUrl(2)) {
								case 'imported':
									echo template::text('fontEditUrl', [
										'label' => 'Url du fichier de fonte',
										'value' => $this->getData(['fonts', $this->getUrl(2), $this->getUrl(3), 'ressource']),
										'class' => $this->getUrl(2) === 'imported' ? '' : 'noDisplay'
									]);
									break;
								case 'files':
									echo template::file('fontEditFile', [
										'label' => 'Fichier de fonte (Format WOFF)',
										'value' => $this->getData(['fonts', $this->getUrl(2), $this->getUrl(3), 'ressource']),
										'class' => $this->getUrl(2) === 'file' ? '' : 'noDisplay'
									]);
									break;
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>