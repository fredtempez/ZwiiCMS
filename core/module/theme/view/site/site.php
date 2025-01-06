<?php echo template::formOpen('themeSiteForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('themeSiteBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'theme',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1">
		<?php /* echo template::button('themeSiteHelp', [
			  'href' => 'https://doc.zwiicms.fr/site61863d315ffe0',
			  'target' => '_blank',
			  'value' => template::ico('help'),
			  'class' => 'buttonHelp'
		  ]); */?>
	</div>
	<div class="col2 offset8">
		<?php echo template::submit('themeSiteSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>
				<?php echo helper::translate('Paramètres'); ?>
			</h4>
			<div class="row">
				<div class="col4">
					<?php echo template::select('themeSiteWidth', theme::$siteWidths, [
						'label' => 'Largeur',
						'selected' => $this->getData(['theme', 'site', 'width'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('themeSiteRadius', theme::$radius, [
						'label' => 'Arrondi des angles',
						'selected' => $this->getData(['theme', 'site', 'radius'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('themeSiteShadow', theme::$shadows, [
						'label' => 'Ombre',
						'selected' => $this->getData(['theme', 'site', 'shadow'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::checkbox('themeSiteMargin', true, 'Pas de marge au-dessus et en dessous du site', [
						'checked' => $this->getData(['theme', 'site', 'margin'])
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>
				<?php echo helper::translate('Couleurs'); ?>
			</h4>
			<div class="row">
				<div class="col12">
					<div class="row">
						<div class="col4">
							<?php echo template::text('themeSiteBackgroundColor', [
								'class' => 'colorPicker',
								'help' => 'Le curseur horizontal règle le niveau de transparence.',
								'label' => 'Arrière plan',
								'value' => $this->getData(['theme', 'site', 'backgroundColor'])
							]); ?>
						</div>
						<div class="col4">
							<?php echo template::text('themeTextTextColor', [
								'class' => 'colorPicker',
								'help' => 'Le curseur horizontal règle le niveau de transparence.',
								'label' => 'Texte',
								'value' => $this->getData(['theme', 'text', 'textColor'])
							]); ?>
						</div>

						<div class="col4">
							<?php echo template::text('themeTitleTextColor', [
								'class' => 'colorPicker',
								'help' => 'Le curseur horizontal règle le niveau de transparence.',
								'label' => 'Titres',
								'value' => $this->getData(['theme', 'title', 'textColor'])
							]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col3">
							<?php echo template::text('themeTextLinkColor', [
								'class' => 'colorPicker',
								'help' => 'Le curseur horizontal règle le niveau de transparence.',
								'label' => 'Liens',
								'value' => $this->getData(['theme', 'text', 'linkColor'])
							]); ?>
						</div>
						<div class="col3">
							<?php echo template::text('themeBlockBackgroundColor', [
								'class' => 'colorPicker',
								'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
								'label' => 'Arrière plan des blocs',
								'value' => $this->getData(['theme', 'block', 'backgroundColor'])
							]); ?>
						</div>
						<div class="col3">
							<?php echo template::text('themeBlockBorderColor', [
								'class' => 'colorPicker',
								'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
								'label' => 'Bordure des blocs',
								'value' => $this->getData(['theme', 'block', 'borderColor'])
							]); ?>
						</div>
						<div class="col3">
							<?php echo template::text('themeButtonBackgroundColor', [
								'class' => 'colorPicker',
								'help' => 'Le curseur horizontal règle le niveau de transparence.',
								'label' => 'Boutons',
								'value' => $this->getData(['theme', 'button', 'backgroundColor'])
							]); ?>
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
			<h4>
				<?php echo helper::translate('Mise en forme du texte'); ?>
			</h4>
			<div class="row">
				<div class="col6">
					<?php
					echo template::select('themeTextFont', theme::$fonts['name'], [
						'label' => 'Fonte',
						'selected' => $this->getData(['theme', 'text', 'font']),
						'font' => theme::$fonts['family']
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::select('themeTextFontSize', theme::$siteFontSizes, [
						'label' => 'Taille',
						'help' => 'Les tailles des polices de la bannière, de menu et de pied de page sont proportionnelles à cette taille.',
						'selected' => $this->getData(['theme', 'text', 'fontSize'])
					]); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col6">
		<div class="block">
			<h4>
				<?php echo helper::translate('Mise en forme des titres'); ?>
			</h4>
			<div class="row">
				<div class="col4">
					<?php echo template::select('themeTitleFont', theme::$fonts['name'], [
						'label' => 'Fonte',
						'selected' => $this->getData(['theme', 'title', 'font']),
						'font' => theme::$fonts['family']
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('themeTitleFontWeight', theme::$fontWeights, [
						'label' => 'Style',
						'selected' => $this->getData(['theme', 'title', 'fontWeight'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('themeTitleTextTransform', theme::$textTransforms, [
						'label' => 'Casse',
						'selected' => $this->getData(['theme', 'title', 'textTransform'])
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>