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
				'href' => helper::baseUrl() . $this->getUrl(0) . '/option/gallery/' . $_SESSION['csrf'],
				'value' => template::ico('sliders')
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('galleryEditSubmit'); ?>
		</div>
	</div>
	<?php echo template::formClose(); ?>
	<div class="row">
		<div class="col12">
			<?php if($module::$pictures): ?>
				<?php echo template::table([1, 4, 1, 5, 1], $module::$pictures, ['#','Image', 'Couverture','Légende',''],['id' => 'galleryTable'], $module::$picturesId ); ?>
				<?php echo template::hidden('galleryEditFormResponse'); ?>
				<?php echo template::hidden('galleryEditFormGalleryName',['value' => $this->getUrl(2)]); ?>
			<?php else: ?>
				<?php echo template::speech('Aucune image.'); ?>
			<?php endif; ?>
		</div>
	<div class="moduleVersion">Version n°
		<?php echo $module::VERSION; ?>
	</div>

