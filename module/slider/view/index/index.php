<?php if (slider::$pictures): ?>
	<div id="wrapper">
		<div class="rslides_container">
			<ul class="rslides" id="sliders">
				<?php foreach (slider::$pictures as $picture => $options): ?>
					<?php 
					// Get normalized file path with user context
					$normalizedPicturePath = $this->getNormalizedFilePath($picture, $this->getUser('id'));
					if ($normalizedPicturePath): ?>
						<?php if (!empty($options['uri'])): ?>
							<a href="<?php echo helper::baseUrl() . $options['uri']; ?>">
						<?php endif; ?>
						<li>
							<img src="<?php echo helper::baseUrl(false) . $normalizedPicturePath; ?>" alt="<?php echo htmlspecialchars($options['legend'] ?? ''); ?>">
							<?php if ($this->getData(['module', $this->getUrl(0), 'theme', 'caption']) === 'bottom' && !empty($options['legend'])): ?>
								<p class="caption"><?php echo htmlspecialchars($options['legend']); ?></p>
							<?php endif; ?>
						</li>
						<?php if (!empty($options['uri'])): ?>
							</a>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php else: ?>
	<?php echo template::speech('Aucune image dans le dossier sélectionné.'); ?>
<?php endif; ?>