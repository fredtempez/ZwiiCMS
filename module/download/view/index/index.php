<?php if($module::$items): ?>
	<div class="row">
		<div class="col12">
			<?php foreach($module::$items as $itemId => $item): ?>
				<div class="row rowitem">
					<div class="col3 downloadLeft">
					<?php
						// Déterminer le nom de la miniature
						$parts = explode('/',$item['picture']);
						$thumb = str_replace ($parts[(count($parts)-1)],'mini_' . $parts[(count($parts)-1)], $item['picture']);
						// Créer la miniature si manquante
						if (!file_exists( self::FILE_DIR . 'thumb/' . $thumb) ) {
							$this->makeThumb(  self::FILE_DIR . 'source/' . $item['picture'],
											  self::FILE_DIR . 'thumb/' . $thumb,
											  self::THUMBS_WIDTH);
						}

					?>
						<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $itemId; ?>" class="downloadPicture">
							<img src="<?php echo helper::baseUrl(false) .  self::FILE_DIR . 'thumb/' . $thumb; ?>" alt="<?php echo $item['picture']; ?>">
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
							<?php echo template::ico('comment', 'left'); ?>
						</div>
						<div class="downloadDate">
							<i class="far fa-calendar-alt"></i>
							<?php echo mb_detect_encoding(strftime('%d %B %Y - %H:%M',  $item['publishedOn']), 'UTF-8', true)
										? strftime('%d %B %Y', $item['publishedOn'])
										: utf8_encode(strftime('%d %B %Y', $item['publishedOn']));  ?>
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
	<?php if ($this->getData(['module',$this->getUrl(0), 'config', 'feeds'])): ?>
		<div id="rssFeed">
			<a type="application/rss+xml" href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/rss'; ?>" target="_blank">
				<img  src='module/news/ressource/feed-icon-16.gif' />
				<?php 
					echo '<p>' . $this->getData(['module',$this->getUrl(0), 'config', 'feedsLabel']) . '</p>' ;
				?>
			</a>
		</div>
	<?php endif; ?>
<?php else: ?>
	<?php echo template::speech('Aucun item.'); ?>
<?php endif; ?>