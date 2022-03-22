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
<div class="row galleryRow">
<?php foreach($module::$pictures as $picture => $legend): ?>
	<div class="colPicture">
		<?php var_dump ( $this->getData(['module',$this->getUrl(0),'content',$this->getUrl(1),'config','fullScreen'])); ?>
		<a
			href="<?php echo helper::baseUrl(false) . $picture; ?>"
			<?php if ( strpos($picture, $this->getData(['module',$this->getUrl(0),'content',$this->getUrl(1),'config','homePicture'])) > 0)  {
							echo 'id="homePicture"'; }	?>
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