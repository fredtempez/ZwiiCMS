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
		$normalizedPicturePath = $this->getNormalizedFilePath($picture);
		$normalizedThumbPath = $this->getNormalizedFilePath(gallery::$thumbs[$picture]);
		?>
		<a
			href="<?php echo !empty($normalizedPicturePath) ? helper::baseUrl(false) . $normalizedPicturePath : '#'; ?>"
			<?php  if ( strpos($picture, gallery::$config['homePicture']) > 1)  { echo 'id="homePicture"'; }	?>
			class="galleryGalleryPicture"
			style="background-image:url('<?php echo !empty($normalizedThumbPath) ? helper::baseUrl(false) . $normalizedThumbPath : ''; ?>')"
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