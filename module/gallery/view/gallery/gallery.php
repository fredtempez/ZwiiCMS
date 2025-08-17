<div class="row <?php echo gallery::$config['backAlign'] . ' ' . gallery::$config['backPositionTop'];?>">
	<div class="col1">
		<?php echo template::button('galleryGalleryBack', [
			'href' => helper::baseUrl() . $this->getUrl(0),
			'value' => template::ico('left')
		]); ?>
	</div>
</div>
<div id="pictureContainer" class="row galleryRow  <?php echo (gallery::$config['fullScreen']);?> ">
<?php foreach(gallery::$pictures as $picture => $legend): ?>
	<div class="colPicture">
		<?php 

		// Générer le chemin de la miniature
		$thumbPath = $this->getThumb($picture);
		
		// Construire les URLs complètes
		$imageUrl = helper::baseUrl(false) . ltrim($picture, '/');
		$thumbUrl = helper::baseUrl(false) . ltrim($thumbPath, '/');
		?>
		<a
			href="<?php echo $picture; ?>"
			<?php if (strpos($picture, gallery::$config['homePicture']) !== false) { echo 'id="homePicture"'; } ?>
			class="galleryGalleryPicture"
			style="background-image:url('<?php echo $thumbUrl; ?>')"
			data-caption="<?php echo $legend; ?>"
		>
			<?php if($legend): ?>
				<div class="galleryGalleryName"><?php echo $legend; ?></div>
			<?php endif; ?>
		</a>
	</div>
<?php endforeach; ?>
</div>
<div class="row <?php echo gallery::$config['backAlign'] . ' ' . gallery::$config['backPositionBottom'];?>">
	<div class="col1">
		<?php echo template::button('galleryGalleryBack', [
			'href' => helper::baseUrl() . $this->getUrl(0),
			'value' => template::ico('left')
		]); ?>
	</div>
</div>