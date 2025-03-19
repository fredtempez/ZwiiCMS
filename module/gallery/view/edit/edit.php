<?php echo template::formOpen('galleryEditForm'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('galleryEditBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col1 offset8">
			<?php echo template::button('galleryConfigOption', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/option/gallery/' . $this->getUrl(2),
				'value' => template::ico('sliders')
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('galleryEditSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<?php if(gallery::$pictures):?>
				<?php echo template::table([1, 4, 1, 5, 1], gallery::$pictures, ['Position','Image', 'Couverture','Légende',''],['id' => 'galleryTable'], [], gallery::$picturesId); ?>
				<?php echo template::hidden('galleryEditFormResponse'); ?>
				<?php echo template::hidden('galleryEditSort',['value' => $this->getData(['module',  $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'sort' ])]);?>
				<?php echo template::hidden('galleryEditFormGalleryName',['value' => $this->getUrl(2)]); ?>
			<?php else: ?>
				<?php echo template::speech('Aucune image.'); ?>
			<?php endif; ?>
		</div>
	<?php echo template::formClose(); ?>
	<div class="moduleVersion">Version n°
		<?php echo gallery::VERSION; ?>
	</div>

