<?php echo Template::formOpen('fontAddForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo Template::button('fontAddBack', [
			'class' => 'buttonGrey',
			'href' => Helper::baseUrl() . 'theme/font',
			'value' => Template::ico('left')
		]); ?>
	</div>
	<div class="col1">
		<?php /* echo Template::button('fontAddHelp', [
			'href' => 'https://doc.zwiicms.fr/fontes#add',
			'target' => '_blank',
			'value' => Template::ico('help'),
			'class' => 'buttonHelp'
		]); */ ?>
	</div>
	<div class="col2 offset8">
		<?php echo Template::submit('fontAddPublish', [
			'value' => 'Valider',
			'uniqueSubmission' => true
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo Helper::translate('Identité de la fonte'); ?>
			</h4>
			<div class="row">
				<div class="col6">
					<?php echo Template::checkbox('fontAddFontImported', true, 'Fonte en ligne', [
						'checked' => true
					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::checkbox('fontAddFontFile', true, 'Fonte installée', []); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo Template::text('fontAddFontId', [
						'autocomplete' => 'off',
						'label' => 'Identifiant',
						'placeholder' => 'big-marker-extrude'

					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::text('fontAddFontName', [
						'autocomplete' => 'off',
						'label' => 'Nom',
						'placeholder' => 'Big Marker Extrude'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo Template::text('fontAddFontFamilyName', [
						'autocomplete' => 'off',
						'label' => 'Famille',
						'placeholder' =>  "'Big Marker Extrude', sans-serif"
					]); ?>
				</div>
			</div>
			<div class="row" id="containerFontAddFile">
				<div class="col12">
					<?php echo Template::file('fontAddFile', [
						'language' => $this->getData(['user', $this->getUser('id'), 'language']),
						'label' => 'Fichier de fonte (Format WOFF)'
					]); ?>
				</div>
			</div>
			<div class="row" id="containerFontAddUrl">
				<div class="col12">
					<?php echo Template::text('fontAddUrl', [
						'label' => 'Url du fichier de fonte',
						'placeholder' => 'https://fonts.cdnfonts.com/css/big-marker-extrude'
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo Template::formClose(); ?>