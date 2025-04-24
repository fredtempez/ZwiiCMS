<?php if (geolocation::$locations): ?>
	<div id="map"></div>
<?php else: ?>
	<?php echo template::speech('Rien à afficher'); ?>
<?php endif; ?>