<?php if ($module::$galleries): ?>
	<div id="map"></div>
<?php else: ?>
	<?php echo template::speech('Rien à afficher'); ?>
<?php endif; ?>