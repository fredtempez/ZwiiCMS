<div class="row <?php echo $module::$config['backAlign'] . ' ' . $module::$config['backPositionTop'];?>">
	<div class="col1">
		<?php echo template::button('galleryGalleryBack', [
			'href' => helper::baseUrl() . $this->getUrl(0),
			'value' => template::ico('left')
		]); ?>
	</div>
</div>
<div id="pictureContainer" class="row galleryRow  <?php echo ($module::$config['fullScreen']);?> ">
<?php foreach($module::$pictures as $picture => $legend): ?>
	<div class="colPicture">
		<a
			href="<?php echo helper::baseUrl(false) . $picture; ?>"
			<?php  if ( strpos($picture, $module::$config['homePicture']) > 1)  { echo 'id="homePicture"'; }	?>
			class="galleryGalleryPicture"
			style="background-image:url('<?php echo helper::baseUrl(false) . $module::$thumbs[$picture]; ?>')"
			data-caption="<?php echo $legend; ?>"
		>
			<?php if($legend): ?>
				<div class="galleryGalleryName"><?php echo $legend; ?></div>
			<?php endif; ?>
		</a>
	</div>
<?php endforeach; ?>
</div>
<div class="row <?php echo $module::$config['backAlign'] . ' ' . $module::$config['backPositionBottom'];?>">
	<div class="col1">
		<?php echo template::button('galleryGalleryBack', [
			'href' => helper::baseUrl() . $this->getUrl(0),
			'value' => template::ico('left')
		]); ?>
	</div>
</div>