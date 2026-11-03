<?php
$instanceId = $this->getUrl(0); $uid = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $instanceId); $scope = gallery::scopeClass($instanceId); $config = isset(gallery::$configByInstance[$instanceId]) ? gallery::$configByInstance[$instanceId] : []; $pictures = isset(gallery::$picturesByInstance[$instanceId]) ? gallery::$picturesByInstance[$instanceId] : []; $thumbs = isset(gallery::$thumbsByInstance[$instanceId]) ? gallery::$thumbsByInstance[$instanceId] : []; ?>
<div class="<?php
echo $scope; ?>">
<div class="row <?php
echo $config['backAlign'] . ' ' . $config['backPositionTop']; ?>">
	<div class="col1">
		<?php
echo template::button('galleryGalleryBackTop-' . $uid, [ 'href' => helper::baseUrl() . $instanceId, 'value' => template::ico('left') ]); ?>
	</div>
</div>
<div id="pictureContainer-<?php
echo $uid; ?>" class="row galleryRow <?php
echo $config['fullScreen']; ?>">
<?php
foreach ($pictures as $picture => $legend): ?>
	<div class="colPicture">
		<?php
$thumbPath = isset($thumbs[$picture]) ? $thumbs[$picture] : gallery::thumbPath($picture); $thumbPath = $thumbPath ?: gallery::thumbPath($picture); $imageUrl = helper::baseUrl(false) . ltrim($picture, '/'); $thumbUrl = helper::baseUrl(false) . ltrim($thumbPath, '/'); $isHome = $config['homePicture'] !== '' && $config['homePicture'] !== null && basename($picture) === $config['homePicture']; ?>
		<a
			href="<?php
echo $picture; ?>"
			class="galleryGalleryPicture<?php
echo $isHome ? ' galleryHomePicture' : ''; ?>"
			style="background-image:url('<?php
echo $thumbUrl; ?>')"
			data-caption="<?php
echo $legend; ?>"
		>
			<?php
if ($legend): ?>
				<div class="galleryGalleryName"><?php
echo $legend; ?></div>
			<?php
endif; ?>
		</a>
	</div>
<?php
endforeach; ?>
</div>
<div class="row <?php
echo $config['backAlign'] . ' ' . $config['backPositionBottom']; ?>">
	<div class="col1">
		<?php
echo template::button('galleryGalleryBackBottom-' . $uid, [ 'href' => helper::baseUrl() . $instanceId, 'value' => template::ico('left') ]); ?>
	</div>
</div>
</div>
