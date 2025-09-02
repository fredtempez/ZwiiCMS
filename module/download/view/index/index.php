<?php if ($module::$items): ?>
	<div class="row">
		<div class="col12">
			<?php foreach ($module::$items as $itemId => $item): ?>
				<div class="row rowitem">
					<div class="col3 downloadLeft">
						<!-- Déterminer le nom de la miniature -->
						<?php $thumb = $this->getThumb($item['thumb']); ?>
						<!-- Créer la miniature si manquante -->
						<?php if (file_exists(filename: $thumb) === false) {
							$this->makeThumb(
								$item['thumb'],         			  // Chemin complet du fichier source
								null,                     // Chemin de destination généré automatiquement
								self::THUMBS_WIDTH        // Largeur de la miniature
							);
						} ?>
						<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $itemId; ?>" class="downloadPicture">
							<img src="<?php echo helper::baseUrl(false) . $thumb; ?>" alt="<?php echo basename($item['thumb']); ?>">
						</a>

					</div>
					<div class="col9 downloadRight">
						<article>
							<h2 class="downloadTitle">
								<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $itemId; ?>">
									<?php echo $item['title']; ?>
								</a>
							</h2>
							<div class="downloadComment">
								<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $itemId; ?>#comment">
									<?php if ($item['comment']): ?>
										<?php echo count($item['comment']); ?>
									<?php endif; ?>
								</a>
								<?php echo template::ico('comment', ['margin' => 'left']); ?>
							</div>
							<div class="downloadDate">
								<i class="far fa-calendar-alt"></i>
								<?php echo mb_detect_encoding(\PHP81_BC\strftime('%d %B %Y - %H:%M', $item['versionDate']), 'UTF-8', true)
									? \PHP81_BC\strftime('%d %B %Y', $item['versionDate'])
									: utf8_encode(\PHP81_BC\strftime('%d %B %Y', $item['versionDate'])); ?>
							</div>
							<p class="downloadContent">
								<?php echo helper::subword(strip_tags($item['content']), 0, 400); ?>...
								<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $itemId; ?>">Lire la suite</a>
							</p>
						</article>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php echo $module::$pages; ?>
	<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'feeds'])): ?>
		<div id="rssFeed">
			<a type="application/rss+xml" href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/rss'; ?> ">
				<img src='module/download/ressource/feed-icon-16.gif' />
				<?php
				echo '<p>' . $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel']) . '</p>';
				?>
			</a>
		</div>
	<?php endif; ?>
<?php else: ?>
	<?php echo template::speech('Aucun item.'); ?>
<?php endif; ?>