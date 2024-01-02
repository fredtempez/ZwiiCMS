<?php echo template::formOpen('fontEditForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('fontEditBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'theme/font',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1">
		<?php /* echo template::button('fontEditHelp', [
			'href' => 'https://doc.zwiicms.fr/fontes#add',
			'target' => '_blank',
			'value' => template::ico('help'),
			'class' => 'buttonHelp'
		]); */ ?>
	</div>
	<div class="col2 offset8">
		<?php echo template::submit('fontEditPublish', [
			'value' => 'Valider',
			'uniqueSubmission' => true
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo helper::translate('Identité de la fonte'); ?>
			</h4>
			<div class="row">
				<div class="col6">
					<?php echo template::checkbox('fontEditFontImported', true, 'Fonte en ligne', [
						'checked' => $this->getUrl(2) === 'imported' ? true : false
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::checkbox('fontEditFontFile', true, 'Fonte installée', [
						'checked' => $this->getUrl(2) === 'files' ? true : false
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
						'value' => $this->getData(['font', $this->getUrl(2), $this->getUrl(3), 'name'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::text('fontEditFontFamilyName', [
						'autocomplete' => 'off',
						'label' => 'Famille',
						'value' => stripslashes($this->getData(['font', $this->getUrl(2), $this->getUrl(3), 'font-family']))
					]); ?>
				</div>
			</div>
			<div class="row" id="containerfontEditFile">
				<div class="col12">
				<?php echo template::text('fontEditFile', [
						'label' => 'Fichier de fonte (Format WOFF)',
						'value' => $this->getUrl(2) === 'files' ? $this->getData(['font', $this->getUrl(2), $this->getUrl(3), 'resource']) : '',
						'disabled' => true
					]); ?>
				</div>
			</div>
			<div class="row" id="containerfontEditUrl">
				<div class="col12">
					<?php echo template::text('fontEditUrl', [
						'label' => 'Url du fichier de fonte',
						'value' => $this->getUrl(2) === 'imported' ? $this->getData(['font', $this->getUrl(2), $this->getUrl(3), 'resource']) : ''
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>