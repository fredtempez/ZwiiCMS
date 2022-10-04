<?php if($module::$news): ?>
	<div class="row">
		<?php foreach($module::$news as $newsId => $news): ?>
			<div class="newsFrame col<?php echo $module::$nbrCol ;?>" >
				<h2 class="newsTitle" id="<?php echo $newsId;?>">
					<?php echo '<a href="'. helper::baseUrl(true) . $this->getUrl(0) . '/' . $newsId . '">' . $news['title'] . '</a>'; ?>
				</h2>
				<div class="newsContent">
					<?php echo $news['content']; ?>
					<?php if ( $this->getData(['module', $this->getUrl(0), 'config', 'height']) !== -1
					 		   && strlen($this->getData(['module', $this->getUrl(0), 'posts', $newsId, 'content'])) >= $this->getData(['module', $this->getUrl(0), 'config', 'height'])):?>
					<?php echo ' ... <a href="'. helper::baseUrl(true) . $this->getUrl(0) . '/' . $newsId . '"><span class="newsSuite">lire la suite</span></a>'; ?>
					<?php endif; ?>
				</div>
				<div class="newsSignature">
					<?php echo template::ico('calendar-empty'); ?>
					<?php echo mb_detect_encoding(PHP81_BC\strftime('%d %B %Y', $news['publishedOn']), 'UTF-8', true)
								? PHP81_BC\strftime('%d %B %Y', $news['publishedOn'])
								: utf8_encode(PHP81_BC\strftime('%d %B %Y', $news['publishedOn'])); ?>
							- <?php echo $news['userId']; ?>
							<!-- Bloc edition -->
							<?php if (

								$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
								AND
								(  // Propriétaire
									(	$this->getUser('group') === self::GROUP_ADMIN )
								)
							): ?>
									<a href ="<?php echo helper::baseUrl() . $this->getUrl(0) . '/edit/' . $newsId . '/' . $_SESSION['csrf'];?>">
										<?php echo template::ico('pencil');?> Editer
									</a>
							<?php endif; ?>
				</div>
		</div>
		<?php endforeach; ?>
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
	<?php echo template::speech('Aucune news.'); ?>
<?php endif; ?>
