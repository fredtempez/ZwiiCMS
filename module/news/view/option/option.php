<?php echo template::formOpen('newsOption'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('newsOptionBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('newsOptionSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo helper::translate('Paramètres'); ?></h4>
			<div class="row">
				<div class="col6">
					<?php echo template::checkbox('newsOptionButtonBack', true, 'Bouton de retour', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'buttonBack'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::checkbox('newsOptionShowPseudo', true, 'Signature', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'showPseudo'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php echo template::checkbox('newsOptionShowDate', true, 'Afficher la date', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'showDate']),
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::select('newsOptionDateFormat', news::$dateFormats, [
						'label' => 'Format des dates',
						'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'dateFormat'])
					]); ?>
				</div>
				<div class="col3 timeWrapper">
					<?php echo template::checkbox('newsOptionShowTime', true, 'Afficher l\'heure', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'showTime']),
					]); ?>
				</div>
				<div class="col3 timeWrapper">
					<?php echo template::select('newsOptionTimeFormat', news::$timeFormats, [
						'label' => 'Format des heures',
						'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'timeFormat'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::select('newsOptionItemsperCol', news::$columns, [
						'label' => 'Nombre de colonnes',
						'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'itemsperCol'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('newsOptionItemsperPage', news::$itemsList, [
						'label' => 'Articles par page',
						'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('newsOptionHeight', news::$height, [
						'label' => 'Abrégé de l\'article',
						'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'height'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::checkbox('newsOptionShowFeeds', true, 'Lien du flux RSS', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'feeds']),
						'help' => 'Flux limité aux articles de la première page.'
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::text('newsOptionFeedslabel', [
						'label' => 'Texte de l\'étiquette RSS',
						'value' => $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel'])
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo helper::translate('Thème'); ?></h4>
			<div class="row">
				<div class="col3">
					<?php echo template::select('newsThemeBorderStyle', news::$borderStyle, [
						'label' => 'Bordure',
						'selected' => $this->getData(['module', $this->getUrl(0), 'theme', 'borderStyle'])
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::select('newsThemeBorderWidth', news::$borderWidth, [
						'label' => 'Épaisseur',
						'selected' => $this->getData(['module', $this->getUrl(0), 'theme', 'borderWidth'])
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::text('newsThemeBorderColor', [
						'class' => 'colorPicker',
						'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
						'label' => 'Couleur de la bordure',
						'value' => $this->getData(['module', $this->getUrl(0), 'theme', 'borderColor'])
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::text('newsThemeBackgroundColor', [
						'class' => 'colorPicker',
						'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
						'label' => 'Couleur du fond',
						'value' => $this->getData(['module', $this->getUrl(0), 'theme', 'backgroundColor'])
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo news::VERSION; ?>
</div>