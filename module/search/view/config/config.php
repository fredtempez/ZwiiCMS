<?php echo template::formOpen('searchConfig'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('searchConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col2 offset9">
				<?php echo template::submit('searchConfigSubmit'); ?>
		</div>
	</div>
	<div class='row'>
		<div class="col12">
			<div class="block">
			<h4><?php echo helper::translate('Paramètres'); ?></h4>
				<div class="row">
					<div class="col6">
						<?php echo template::text('searchSubmitText', [
								'label' => 'Texte du bouton',
								'value' => $this->getData(['module', $this->getUrl(0), 'config', 'submitText'])
							]); ?>
					</div>
					<div class="col6">
						<?php echo template::select('searchPreviewLength', $module::$previewLength, [
								'label' => 'Dimension de l\'aperçu',
								'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'previewLength'])
							]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col12">
						<?php echo template::text('searchPlaceHolder', [
								'label' => 'Aide dans la zone de saisie',
								'value' => $this->getData(['module', $this->getUrl(0), 'config', 'placeHolder'])
							]); ?>
					</div>
					<div class="col12">
						<?php echo template::checkbox('searchResultHideContent', true, 'Masquer le contenu de la page dans les résultats', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'resultHideContent']),
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
				<?php echo helper::translate('Thème'); ?>
			</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::text('searchKeywordColor', [
							'class' => 'colorPicker',
							'help' =>  'Le curseur horizontal règle le niveau de transparence, le placer tout à la gauche pour un surlignement invisible.',
							'label' => 'Surlignement',
							'value' => $this->getData(['module', $this->getUrl(0), 'theme', 'keywordColor'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>