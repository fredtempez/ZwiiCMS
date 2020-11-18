<?php echo template::formOpen('themeSiteForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('themeSiteBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'theme',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::submit('themeSiteSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Couleurs</h4>
				<div class="row">
					<div class="col8">
						<div class="row">
							<div class="col6">
								<?php echo template::text('themeSiteBackgroundColor', [
									'class' => 'colorPicker',
									'help' => 'Le curseur horizontal règle le niveau de transparence.',
									'label' => 'Fond',
									'value' => $this->getData(['theme', 'site', 'backgroundColor'])
								]); ?>
							</div>
							<div class="col6">
								<?php echo template::text('themeTextTextColor', [
									'class' => 'colorPicker',
									'help' => 'Le curseur horizontal règle le niveau de transparence.',
									'label' => 'Texte',
									'value' => $this->getData(['theme', 'text', 'textColor'])
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col6">
								<?php echo template::text('themeTitleTextColor', [
									'class' => 'colorPicker',
									'help' => 'Le curseur horizontal règle le niveau de transparence.',
									'label' => 'Titres',
									'value' => $this->getData(['theme', 'title', 'textColor'])
								]); ?>
							</div>
							<div class="col6">
								<?php echo template::text('themeTextLinkColor', [
									'class' => 'colorPicker',
									'help' => 'Le curseur horizontal règle le niveau de transparence.',
									'label' => 'Liens',
									'value' => $this->getData(['theme',  'text', 'linkColor'])
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col6">
								<?php echo template::text('themeBlockBackgroundColor', [
									'class' => 'colorPicker',
									'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
									'label' => 'Arrière-plan des blocs',
									'value' => $this->getData(['theme', 'block', 'backgroundColor'])
								]); ?>
							</div>
							<div class="col6">
								<?php echo template::text('themeBlockBorderColor', [
									'class' => 'colorPicker',
									'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
									'label' => 'Bordure des blocs',
									'value' => $this->getData(['theme', 'block', 'borderColor'])
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col6 offset3">
								<?php echo template::text('themeButtonBackgroundColor', [
									'class' => 'colorPicker',
									'help' => 'Le curseur horizontal règle le niveau de transparence.',
									'label' => 'Boutons',
									'value' => $this->getData(['theme', 'button', 'backgroundColor'])
								]); ?>
							</div>
						</div>
					</div>
					<div class="col4 bodybackground">
						<div class="bgPreview">
							<div class="row">
								<div class="col6">
                                    <h1 class="headerPreview">Titre</h1>
                                    <h2 class="headerPreview">Sous-titre </h2>
                                </div>
                                <div class="col6">
                                    <?php echo template::button('themeSiteSubmitButtonPreview', [
                                        'class' => 'buttonSubmitPreview',
                                        'value' => 'Bouton'
                                    ]); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col12">
                                    <div class="block preview">
                                        <h4 class="preview">Bloc</h4>										<p class="textPreview">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
										<p><a href="#" class="urlPreview">Lorem ipsum dolor sit amet.</a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col6">
			<div class="block">
				<h4>Mise en forme du texte</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::select('themeTextFont', $module::$fonts, [
							'label' => 'Police',
							'selected' => $this->getData(['theme', 'text', 'font']),
							'fonts' => true
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::select('themeTextFontSize', $module::$siteFontSizes, [
							'label' => 'Taille',
							'help' => 'Taille de référence pour le site. Les tailles des polices de la bannière, de menu et de pied de page sont proportionnelles à cette taille.',
							'selected' => $this->getData(['theme', 'text', 'fontSize'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col6">
			<div class="block">
				<h4>Mise en forme des titres</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::select('themeTitleFont', $module::$fonts, [
							'label' => 'Police',
							'selected' => $this->getData(['theme', 'title', 'font']),
							'fonts' => true
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('themeTitleFontWeight', $module::$fontWeights, [
							'label' => 'Style',
							'selected' => $this->getData(['theme', 'title', 'fontWeight'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('themeTitleTextTransform', $module::$textTransforms, [
							'label' => 'Casse',
							'selected' => $this->getData(['theme', 'title', 'textTransform'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Apparence</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::select('themeSiteWidth', $module::$widths, [
							'label' => 'Largeur du site',
							'selected' => $this->getData(['theme', 'site', 'width'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('themeSiteRadius', $module::$radius, [
							'label' => 'Arrondi des angles',
							'selected' => $this->getData(['theme', 'site', 'radius'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('themeSiteShadow', $module::$shadows, [
							'label' => 'Ombre sur les bords du site',
							'selected' => $this->getData(['theme', 'site', 'shadow'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('themeSiteMargin',true, 'Pas de marge au-dessus et en-dessous du site', [
							'checked' => $this->getData(['theme', 'site', 'margin'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>