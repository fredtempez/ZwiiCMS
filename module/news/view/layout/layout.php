<?php echo template::formOpen('newsLayout'); ?>
<div class="row">
		<div class="col2">
			<?php echo template::button('newsLayoutBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
				<?php echo template::submit('newsLayoutSubmit'); ?>
		</div>
	</div>
    <div class="row">
		<div class="col12">
			<div class="block">
				<h4>Paramètres du module</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('newsLayoutShowFeeds', true, 'Lien du flux RSS', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'feeds']),
							'help' => 'Flux limité aux articles de la première page.'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('newsLayoutFeedslabel', [
							'label' => 'Etiquette RSS',
							'value' => $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4">
						<?php echo template::select('newsLayoutItemsperCol', $module::$columns, [
							'label' => 'Nombre de colonnes',
							'selected' => $this->getData(['module', $this->getUrl(0),'config', 'itemsperCol'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('newsLayoutItemsperPage', $module::$itemsList, [
							'label' => 'Articles par page',
							'selected' => $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('newsLayoutHeight', $module::$height, [
							'label' => 'Abrégé de l\'article',
							'selected' => $this->getData(['module', $this->getUrl(0),'config', 'height'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Thème du module</h4>
				<div class="row">
					<div class="col3">
						<?php echo template::select('newsThemeBorderStyle', $module::$borderStyle, [
							'label' => 'Bordure',
							'selected' => $this->getData(['module', $this->getUrl(0),'theme', 'borderStyle'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::select('newsThemeBorderWidth', $module::$borderWidth, [
							'label' => 'Epaisseur',
							'selected' => $this->getData(['module', $this->getUrl(0),'theme', 'borderWidth'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('newsThemeBorderColor', [
							'class' => 'colorPicker',
							'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Couleur de la bordure',
							'value' => $this->getData(['module', $this->getUrl(0),'theme', 'borderColor'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('newsThemeBackgroundColor', [
							'class' => 'colorPicker',
							'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Couleur du fond',
							'value' => $this->getData(['module', $this->getUrl(0),'theme', 'backgroundColor'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>