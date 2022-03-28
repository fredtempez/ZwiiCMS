<?php echo template::formOpen('galleryConfigForm'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('galleryConfigBack', [
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col1 offset7">
		<?php echo template::button('galleryConfigOption', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/option/' . $_SESSION['csrf'],
			'value' => '',
			'ico' => 'sliders',
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('galleryConfigTheme', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/theme/' . $_SESSION['csrf'],
			'value' => '',
			'ico' => 'brush',
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('galleryAdd', [
			'ico' => '',
			'value' => template::ico('plus'),
			'href' => helper::baseUrl() . $this->getUrl(0) . '/add/',
		]); ?>
	</div>
</div>
<?php echo template::formClose(); ?>
<div class="row">
	<div class="col12">
		<?php if($module::$galleries): ?>
		<?php echo template::table([1, 4, 5, 1, 1], $module::$galleries, ['','Nom', 'Dossier cible', '', ''], ['id' => 'galleryTable'],$module::$galleriesId); ?>
		<?php echo template::hidden('galleryConfigFilterResponse'); ?>
		<?php else: ?>
			<?php echo template::speech('Aucune galerie.'); ?>
		<?php endif; ?>
	</div>
	<div class="moduleVersion">Version n°
		<?php echo $module::VERSION; ?>
	</div>
</div>
