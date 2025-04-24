<?php echo template::formOpen('galleryEditForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('galleryEditBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('galleryEditSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<?php if (geogallery::$pictures): ?>
			<?php echo template::table([3, 4, 3, 1, 1], geogallery::$pictures, ['Image', 'Légende', 'Coordonnées', 'Position', 'Miniature'], ['id' => 'galleryTable'], geogallery::$picturesId); ?>
		<?php else: ?>
			<?php echo template::speech('Aucune image.'); ?>
		<?php endif; ?>
	</div>
	<?php echo template::formClose(); ?>
	<div class="moduleVersion">Version n°
		<?php echo geogallery::VERSION; ?>
	</div>