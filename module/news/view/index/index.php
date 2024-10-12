<?php if ($module::$news): ?>
	<link rel="stylesheet" type="text/css"
		href="<?php echo helper::baseUrl(false) . $this->getData(['module', $this->getUrl(0), 'theme', 'style']); ?> " />
	<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'feeds'])): ?>
		<div id="rssFeed">
			<a type="application/rss+xml" href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/rss'; ?>" target="_blank">
				<img src='module/news/ressource/feed-icon-16.gif' />
				<?php
				echo $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel']) ? '<p>' . $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel']) . '</p>' : '';
				?>
			</a>
		</div>
	<?php endif; ?>
	<article>
		<div class="row">

			<?php foreach ($module::$news as $newsId => $news): ?>
				<div class="col<?php echo $module::$nbrCol; ?>">
					<div class="newsFrame">
						<h2 class="newsTitle" id="<?php echo $newsId; ?>">
							<?php echo '<a href="' . helper::baseUrl(true) . $this->getUrl(0) . '/' . $newsId . '">' . $news['title'] . '</a>'; ?>
						</h2>
						<div class="newsSignature">
							<!-- bloc signature -->
							<?php echo template::ico('user'); ?>
							<?php echo $news['userId']; ?>
							<!-- bloc Date -->
							<?php if (
								$this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true
								|| $this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true
							): ?>
								<?php echo template::ico('calendar-empty', ['margin' => 'left']); ?>
							<?php endif; ?>
							<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true): ?>
								<?php echo helper::dateUTF8($module::$dateFormat, $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn']), self::$i18nUI); ?>
							<?php endif; ?>
							<?php if (
								$this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true
								&& $this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true
							): ?>
								<?php echo '&nbsp;-&nbsp;'; ?>
							<?php endif; ?>
							<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true): ?>
								<?php echo helper::dateUTF8($module::$timeFormat, $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn']), self::$i18nUI); ?>
							<?php endif; ?> 
							<!-- Bloc edition -->
							<?php if (
								$this->isConnected() === true
								and
								( // Propriétaire
									($this->getUser('group') === self::GROUP_ADMIN)
								)
							): ?>
								<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/edit/' . $newsId; ?>">
									<?php echo template::ico('pencil', ['margin' => 'left']); ?> Éditer
								</a>
							<?php endif; ?>
						</div>
						<div class="newsContent">
							<?php echo $news['content']; ?>
							<?php if (
								$this->getData(['module', $this->getUrl(0), 'config', 'height']) !== -1
								&& strlen($this->getData(['module', $this->getUrl(0), 'posts', $newsId, 'content'])) >= $this->getData(['module', $this->getUrl(0), 'config', 'height'])
							): ?>
								<?php echo ' ... <a href="' . helper::baseUrl(true) . $this->getUrl(0) . '/' . $newsId . '"><span class="newsSuite">lire la suite</span></a>'; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</article>
	<?php echo $module::$pages; ?>
<?php else: ?>
	<?php echo template::speech('Aucune news'); ?>
<?php endif; ?>