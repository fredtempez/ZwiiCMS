<?php echo template::formOpen('fontAddForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('fontAddBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'theme/fonts',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::submit('fontAddPublish', [
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
							<?php echo template::checkbox('fontAddFontImported', true, 'Fonte téléchargée sur cdnFonts', [
								'help' => 'Police utilisée en ligne, se connecter sur cdnFonts pour récupérer les informations nécessaires.'
							]); ?>
					</div>
					<div class="col6">
							<?php echo template::checkbox('fontAddFontFile', true,'Fonte installée', [
								'help' => '<br/>Sélectionnez un fichier de fonte au format WOFF.'
							]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::text('fontAddFontId', [
							'autocomplete' => 'off',
							'label' => 'Identifiant (sans espace ni majuscule)',
							'placeholder' => 'perry-gothic'

						]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('fontAddFontName', [
							'autocomplete' => 'off',
							'label' => 'Nom (Font Family)',
							'placeholder' => 'PerryGothic'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col12">
						<?php echo template::file('fontAddFile', [
							'label' => 'Fichier de police (Format WOFF)',
							'placeholder' => 'https://fonts.cdnfonts.com/s/7896/PERRYGOT.woff'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>