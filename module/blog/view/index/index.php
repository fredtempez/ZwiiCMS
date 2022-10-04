<?php if($module::$articles): ?>
	<div class="row">
		<div class="col12">
			<?php foreach($module::$articles as $articleId => $article): ?>
				<div class="row rowArticle">
					<div class="col3">
					<?php if (  file_exists(self::FILE_DIR . 'source/' . $article['picture']) ): ?>
						<?php // Déterminer le nom de la miniature
							$parts = explode('/',$article['picture']);
							$thumb = str_replace ($parts[(count($parts)-1)],'mini_' . $parts[(count($parts)-1)], $article['picture']);
							// Créer la miniature si manquante
							if (!file_exists( self::FILE_DIR . 'thumb/' . $thumb) ) {
								$this->makeThumb(  self::FILE_DIR . 'source/' . $article['picture'],
												self::FILE_DIR . 'thumb/' . $thumb,
												self::THUMBS_WIDTH);
							}	
						?>
						<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>" class="blogPicture">
							<img src="<?php echo helper::baseUrl(false) .  self::FILE_DIR . 'thumb/' . $thumb; ?>" alt="<?php echo $article['picture']; ?>">
						</a>
					<?php endif;?>
					</div>
					<div class="col9">
						<h1 class="blogTitle">
							<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>">
								<?php echo $article['title']; ?>
							</a>
						</h1>
						<div class="blogComment">
							<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>#comment">
								<?php if ($article['comment']): ?>
									<?php echo count($article['comment']); ?>
								<?php endif; ?>
							</a>
							<?php echo template::ico('comment', 'left'); ?>
						</div>
						<div class="blogDate">
							<?php echo template::ico('calendar-empty'); ?>
							<?php echo mb_detect_encoding(PHP81_BC\strftime('%d %B %Y - %H:%M',  $article['publishedOn']), 'UTF-8', true)
										? PHP81_BC\strftime('%d %B %Y', $article['publishedOn'])
										: utf8_encode(PHP81_BC\strftime('%d %B %Y', $article['publishedOn']));  ?>
						</div>
						<p class="blogContent">
							<?php echo helper::subword(strip_tags($article['content'],'<br><p>'), 0, 400); ?>...
							<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>">Lire la suite</a>
						</p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php echo $module::$pages; ?>
	<?php if ($this->getData(['module',$this->getUrl(0), 'config', 'feeds'])): ?>
		<div id="rssFeed">
			<a type="application/rss+xml" href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/rss'; ?>" target="_blank">
				<img  src='module/blog/ressource/feed-icon-16.gif' />
				<?php
					echo '<p>' . $this->getData(['module',$this->getUrl(0), 'config', 'feedsLabel']) . '</p>' ;
				?>
			</a>
		</div>
	<?php endif; ?>
<?php else: ?>
	<?php echo template::speech('Aucun article.'); ?>
<?php endif; ?>
