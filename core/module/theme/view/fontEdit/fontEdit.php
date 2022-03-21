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
							<?php echo template::checkbox('fontEditFontImported', true, 'Fonte en ligne', [
								'checked' => $this->getUrl(2) === 'imported' ? true : false
							]); ?>
					</div>
					<div class="col6">
							<?php echo template::checkbox('fontEditFontFile', true,'Fonte installée', [
								'checked' => $this->getUrl(2) === 'file' ? true : false
							]); ?>
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
							'value' => stripslashes($this->getData(['fonts', $this->getUrl(2), $this->getUrl(3), 'font-family']))
						]); ?>
					</div>
				</div>
				<div class="row" id="containerfontEditFile">
					<div class="col12">
						<?php echo template::file('fontEditFile', [
							'label' => 'Fichier de fonte (Format WOFF)',
                            'value' => $this->getUrl(2) === 'file' ? $this->getData(['fonts', $this->getUrl(2), $this->getUrl(3), 'resource']) : ''
						]); ?>
					</div>
				</div>
				<div class="row" id="containerfontEditUrl">
					<div class="col12">
						<?php echo template::text('fontEditUrl', [
							'label' => 'Url du fichier de fonte',
							'value' => $this->getUrl(2) === 'imported' ? $this->getData(['fonts', $this->getUrl(2), $this->getUrl(3), 'resource']) : ''
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>