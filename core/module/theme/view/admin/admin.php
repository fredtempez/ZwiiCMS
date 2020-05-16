<?php echo template::formOpen('configAdminForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('configAdminBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'theme',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset6">
		<?php echo template::button('configAdminReset', [
				'class' => 'buttonRed',
				'href' => helper::baseUrl() . 'theme/resetAdmin',
				'value' => 'Réinitialiser'
			]); ?>		
		</div>
		<div class="col2">
			<?php echo template::submit('configAdminSubmit',[
				'value' => 'valider',
				'ico' => 'check'
			]); ?>
		</div>	
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
					<h4>Couleurs</h4>
				<div class="row">
					<div class="col3">
						<?php echo template::text('themeAdminBackgroundColor', [
							'class' => 'colorPicker',
							'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Arrière-plan',
							'value' => $this->getData(['admin', 'backgroundColor'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('themeAdmincolorTitle', [
							'class' => 'colorPicker',
							'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Titres',
							'value' => $this->getData(['admin', 'colorTitle'])
						]); ?>
					</div>                                        
					<div class="col3">
						<?php echo template::text('themeAdmincolorText', [
							'class' => 'colorPicker',
							'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Texte',
							'value' => $this->getData(['admin', 'colorText'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('themeAdmincolorButtonText', [
							'class' => 'colorPicker',
							'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Texte des boutons',
							'value' => $this->getData(['admin', 'colorButtonText'])
						]); ?>
					</div>  
				</div>
				<div class="row">
					<div class="col3">
						<?php echo template::text('themeAdmincolorButton', [
							'class' => 'colorPicker',
							'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Bouton standard',
							'value' => $this->getData(['admin', 'backgroundColorButton'])
						]); ?>
					</div>            
					<div class="col3">                 
						<?php echo template::text('themeAdmincolorGrey', [
							'class' => 'colorPicker',
							'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Bouton retour',
							'value' => $this->getData(['admin', 'backgroundColorButtonGrey'])
						]); ?>
					</div>            
					<div class="col3">
						<?php echo template::text('themeAdmincolorRed', [
							'class' => 'colorPicker',
							'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Bouton effacement',
							'value' => $this->getData(['admin', 'backgroundColorButtonRed'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('themeAdmincolorGreen', [
							'class' => 'colorPicker',
							'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Bouton validation',
							'value' => $this->getData(['admin', 'backgroundColorButtonGreen'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
					<h4>Mise en forme du texte</h4>
					<div class="row">
						<div class="col4">
							<?php echo template::select('themeFont', $module::$fonts, [
								'label' => 'Police du texte',
								'selected' => $this->getData(['admin', 'font']),
								'fonts' => true
							]); ?>
						</div>				
						<div class="col4">
							<?php echo template::select('themeTextFontSize', $module::$siteFontSizes, [
								'label' => 'Taille',
								'selected' => $this->getData(['admin', 'fontSize'])
							]); ?>
						</div>
						<div class="col4">
							<?php echo template::select('themeFontTitle', $module::$fonts, [
								'label' => 'Police des titres',
								'selected' => $this->getData(['admin', 'fontTitle']),
								'fonts' => true
							]); ?>
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>	