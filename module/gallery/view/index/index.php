<?php if($module::$galleries): ?>
	<div class="row galleryRow">
	<?php foreach($module::$galleries as $galleryId => $gallery): ?>
			<div class="colPicture" div="pos<?php echo $gallery['config']['position']; ?>" >
				<a
					href="<?php echo helper::baseUrl() . $this->getUrl(0); ?>/<?php echo $galleryId; ?>"
					class="galleryPicture"
					style="background-image:url('<?php echo $module::$firstPictures[$galleryId];?>')"
				>
					<div class="galleryName"><?php echo $gallery['config']['name']; ?></div>
				</a>
			</div>
	<?php endforeach; ?>
	</div>
<?php else: ?>
	<?php echo template::speech('Aucune galerie'); ?>
<?php endif; ?>