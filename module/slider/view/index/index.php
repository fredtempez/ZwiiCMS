<?php
$sliderId = $this->getUrl(0); $pictures = isset(slider::$picturesByInstance[$sliderId]) ? slider::$picturesByInstance[$sliderId] : []; ?>
<?php
if ($pictures): ?>
	<div id="wrapper-<?php
echo $sliderId; ?>" class="sliderWrapper" data-slider-id="<?php
echo $sliderId; ?>">
		<div class="rslides_container">
			<ul class="rslides" id="sliders-<?php
echo $sliderId; ?>" data-slider-id="<?php
echo $sliderId; ?>">
				<?php
foreach ($pictures as $picture => $options): ?>
					<?php
$normalizedPicturePath = $this->getNormalizedFilePath($picture, $this->getUser('id')); if ($normalizedPicturePath): ?>
						<?php
if (!empty($options['uri'])): ?>
							<a href="<?php
echo helper::baseUrl() . $options['uri']; ?>">
						<?php
endif; ?>
						<li>
							<img src="<?php
echo helper::baseUrl(false) . $normalizedPicturePath; ?>" alt="<?php
echo htmlspecialchars($options['legend'] ?? ''); ?>">
							<?php
if ($this->getData(['module', $this->getUrl(0), 'theme', 'caption']) === 'bottom' && !empty($options['legend'])): ?>
								<p class="caption"><?php
echo htmlspecialchars($options['legend']); ?></p>
							<?php
endif; ?>
						</li>
						<?php
if (!empty($options['uri'])): ?>
							</a>
						<?php
endif; ?>
					<?php
endif; ?>
				<?php
endforeach; ?>
			</ul>
		</div>
	</div>
<?php
else: ?>
	<?php
echo template::speech('Aucune image dans le dossier sélectionné.'); ?>
<?php
endif; ?>
