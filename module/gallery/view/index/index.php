<?php
$instanceId = $this->getUrl(0); $scope = gallery::scopeClass($instanceId); $galleries = isset(gallery::$galleriesByInstance[$instanceId]) ? gallery::$galleriesByInstance[$instanceId] : []; $firstPictures = isset(gallery::$firstPicturesByInstance[$instanceId]) ? gallery::$firstPicturesByInstance[$instanceId] : []; ?>
<div class="<?php
echo $scope; ?>">
<?php
if ($galleries): ?>
	<div class="row galleryRow">
	<?php
foreach ($galleries as $galleryId => $gallery): ?>
		<div class="colPicture" data-position="<?php
echo $gallery['config']['position']; ?>">
			<a
				href="<?php
echo helper::baseUrl() . $instanceId; ?>/<?php
echo $galleryId; ?>"
				class="galleryPicture"
				style="background-image:url('<?php
echo $firstPictures[$galleryId]; ?>')"
			>
				<div class="galleryName"><?php
echo $gallery['config']['name']; ?></div>
			</a>
		</div>
	<?php
endforeach; ?>
	</div>
<?php
else: ?>
	<?php
echo template::speech('Aucune galerie'); ?>
<?php
endif; ?>
</div>
