<?php echo Template::formOpen('themeBodyForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo Template::button('themeBodyBack', [
			'class' => 'buttonGrey',
			'href' => Helper::baseUrl() . 'theme',
			'value' => Template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo Template::submit('themeBodySubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo Helper::translate('Couleurs'); ?></h4>
			<div class="row">
				<div class="col6">
					<?php echo Template::text('themeBodyBackgroundColor', [
						'class' => 'colorPicker',
						'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
						'label' => 'Arrière plan',
						'value' => $this->getData(['theme', 'body', 'backgroundColor'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo Template::text('themeBodyToTopColor', [
						'class' => 'colorPicker',
						'help' => 'Le curseur horizontal règle le niveau de transparence.',
						'label' => 'Couleur icône haut de page',
						'value' => $this->getData(['theme', 'body', 'toTopColor'])
					]); ?>

				</div>
				<div class="col6">
					<?php echo Template::text('themeBodyToTopBackground', [
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
			<h4><?php echo Helper::translate('Image'); ?>
			</h4>
			<div class="row">
				<div class="col12">
					<?php
					$imagePath = $this->getData(['theme', 'body', 'image']);
					$normalizedImagePath = $this->getNormalizedFilePath($imagePath, $this->getUser('id'));
					$imageFile = !empty($normalizedImagePath) && file_exists($normalizedImagePath) ? $imagePath : "";
					echo Template::file('themeBodyImage', [
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
						<?php echo Template::select('themeBodyImageRepeat', theme::$repeats, [
							'label' => 'Répétition',
							'selected' => $this->getData(['theme', 'body', 'imageRepeat'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo Template::select('themeBodyImagePosition', theme::$imagePositions, [
							'label' => 'Position',
							'selected' => $this->getData(['theme', 'body', 'imagePosition'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo Template::select('themeBodyImageAttachment', theme::$attachments, [
							'label' => 'Défilement',
							'selected' => $this->getData(['theme', 'body', 'imageAttachment'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo Template::select('themeBodyImageSize', theme::$bodySizes, [
							'label' => 'Taille',
							'selected' => $this->getData(['theme', 'body', 'imageSize'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo Template::formClose(); ?>