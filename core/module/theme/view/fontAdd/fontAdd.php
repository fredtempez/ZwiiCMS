<?php echo template::formOpen('fontAddForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('fontAddBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/theme/font',
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
				<h4>Identification</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::text('fontAddFontId', [
							'autocomplete' => 'off',
							'label' => 'Identifiant',
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
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Source</h4>
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