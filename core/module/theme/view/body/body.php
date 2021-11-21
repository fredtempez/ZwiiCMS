<?php echo template::formOpen('themeBodyForm'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('themeBodyBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'theme',
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('themeBodyHelp', [
			'href' => 'https://doc.zwiicms.fr/arriere-plan',
			'target' => '_blank',
			'ico' => 'help',
			'value' => 'Aide',
			'class' => 'buttonHelp'
		]); ?>
	</div>
	<div class="col2 offset6">
		<?php echo template::submit('themeBodySubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Couleurs</h4>
			<div class="row">
				<div class="col6">
					<?php echo template::text('themeBodyBackgroundColor', [
						'class' => 'colorPicker',
						'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
						'label' => 'Arrière-plan',
						'value' => $this->getData(['theme', 'body', 'backgroundColor'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::text('themeBodyToTopBackground', [
						'class' => 'colorPicker',
						'help' => 'Le curseur horizontal règle le niveau de transparence.',
						'label' => 'Fond icône haut de page',
						'value' => $this->getData(['theme', 'body', 'toTopbackgroundColor'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::text('themeBodyToTopColor', [
						'class' => 'colorPicker',
						'help' => 'Le curseur horizontal règle le niveau de transparence.',
						'label' => 'Icône haut de page',
						'value' => $this->getData(['theme', 'body', 'toTopColor'])
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Image</h4>
			<div class="row">
				<div class="col12">
					<?php
					$imageFile = file_exists(self::FILE_DIR.'source/'.$this->getData(['theme', 'body', 'image'])) ? $this->getData(['theme', 'body', 'image']) : "";
					echo template::file('themeBodyImage', [
						'help' => 'Sélectionner une image',
						'label' => 'Arrière-plan',
						'type' => 1,
						'value' => $imageFile
					]); ?>
				</div>
			</div>
			<div id="themeBodyImageOptions" class="displayNone">
				<div class="row">
					<div class="col6">
						<?php echo template::select('themeBodyImageRepeat', $module::$repeats, [
							'label' => 'Répétition',
							'selected' => $this->getData(['theme', 'body', 'imageRepeat'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::select('themeBodyImagePosition', $module::$imagePositions, [
							'label' => 'Position',
							'selected' => $this->getData(['theme', 'body', 'imagePosition'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::select('themeBodyImageAttachment', $module::$attachments, [
							'label' => 'Défilement',
							'selected' => $this->getData(['theme', 'body', 'imageAttachment'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::select('themeBodyImageSize', $module::$bodySizes, [
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
