<?php if($module::$news): ?>
	<div class="row">
		<div class="col12">
			<?php foreach($module::$news as $newsId => $news): ?>
				<div class="row ">
					<div class="col12">
						<h1 class="newsTitle" id="<?php echo $newsId;?>">
							<?php echo $news['title']; ?>
						</h1>
						<div class="newsContent">
							<?php echo $news['content']; ?>
						</div>
						<div class="newsSignature">
							<i class="far fa-calendar-alt"></i>
							<?php echo mb_detect_encoding(strftime('%d %B %Y', $news['publishedOn']), 'UTF-8', true)
										? strftime('%d %B %Y', $news['publishedOn'])
										: utf8_encode(strftime('%d %B %Y', $news['publishedOn'])); ?>
							- <?php echo $this->getData(['user', $news['userId'], 'firstname']) . ' ' . $this->getData(['user', $news['userId'], 'lastname']); ?>
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
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php echo $module::$pages; ?>
	<?php if ($this->getData(['module',$this->getUrl(0), 'config', 'feeds'])): ?>
		<div id="rssFeed">
			<a type="application/rss+xml" href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/rss'; ?> ">
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
