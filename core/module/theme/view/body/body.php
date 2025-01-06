<?php echo template::formOpen('themeBodyForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('themeBodyBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'theme',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('themeBodySubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo helper::translate('Couleurs'); ?></h4>
			<div class="row">
				<div class="col6">
					<?php echo template::text('themeBodyBackgroundColor', [
						'class' => 'colorPicker',
						'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
						'label' => 'Arrière plan',
						'value' => $this->getData(['theme', 'body', 'backgroundColor'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::text('themeBodyToTopColor', [
						'class' => 'colorPicker',
						'help' => 'Le curseur horizontal règle le niveau de transparence.',
						'label' => 'Couleur icône haut de page',
						'value' => $this->getData(['theme', 'body', 'toTopColor'])
					]); ?>

				</div>
				<div class="col6">
					<?php echo template::text('themeBodyToTopBackground', [
						'class' => 'colorPicker',
						'help' => 'Le curseur horizontal règle le niveau de transparence.',
						'label' => 'Icône haut de page, couleur arrière-plan',
						'value' => $this->getData(['theme', 'body', 'toTopbackgroundColor'])
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo helper::translate('Image'); ?>
			</h4>
			<div class="row">
				<div class="col12">
					<?php
					$imageFile = file_exists(self::FILE_DIR . 'source/' . $this->getData(['theme', 'body', 'image'])) ? $this->getData(['theme', 'body', 'image']) : "";
					echo template::file('themeBodyImage', [
						'language' => $this->getData(['user', $this->getUser('id'), 'language']),
						'label' => 'Arrière plan',
						'type' => 1,
						'value' => $imageFile,
						'folder' => $imageFile ? dirname($imageFile) : ''
					]); ?>
				</div>
			</div>
			<div id="themeBodyImageOptions" class="displayNone">
				<div class="row">
					<div class="col6">
						<?php echo template::select('themeBodyImageRepeat', theme::$repeats, [
							'label' => 'Répétition',
							'selected' => $this->getData(['theme', 'body', 'imageRepeat'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::select('themeBodyImagePosition', theme::$imagePositions, [
							'label' => 'Position',
							'selected' => $this->getData(['theme', 'body', 'imagePosition'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::select('themeBodyImageAttachment', theme::$attachments, [
							'label' => 'Défilement',
							'selected' => $this->getData(['theme', 'body', 'imageAttachment'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::select('themeBodyImageSize', theme::$bodySizes, [
							'label' => 'Taille',
							'selected' => $this->getData(['theme', 'body', 'imageSize'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>