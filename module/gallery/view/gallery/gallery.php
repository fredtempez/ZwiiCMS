


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
<?php if ( $module::$config['mono'] !== true): ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('galleryGalleryBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0),
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
</div>
<?php endif; ?>