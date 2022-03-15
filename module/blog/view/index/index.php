<?php if($module::$articles): ?>
	<?php foreach($module::$articles as $articleId => $article): ?>
		<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'layout']) !== true): ?>
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
						<?php echo mb_detect_encoding(strftime('%d %B %Y - %H:%M',  $article['publishedOn']), 'UTF-8', true)
									? strftime('%d %B %Y', $article['publishedOn'])
									: utf8_encode(strftime('%d %B %Y', $article['publishedOn']));  ?>
					</div>
					<p class="blogContent">
							<?php $lenght =  $this->getData(['module',$this->getUrl(0), 'config', 'articlesLenght']) !== 0 ?  $this->getData(['module',$this->getUrl(0), 'config', 'articlesLenght']) : 500 ?>
							<?php echo helper::subword(strip_tags($article['content'],'<br><p>'), 0, $lenght); ?>...
							<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>">Lire la suite</a>
						</p>
				</div>
			</div>
		<?php else: ?>
			<div class="row">
				<div class="col12">
					<h1 class="blogTitle">
							<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>">
								<?php echo $article['title']; ?>
							</a>
					</h1>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php $pictureSize =  $this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'pictureSize']) === null ? '100' : $this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'pictureSize']); ?>
					<?php if ($this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'hidePicture']) == false) {
						echo '<img class="blogArticlePicture blogArticlePicture' . $this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'picturePosition']) .
						' pict' . $pictureSize . '" src="' . helper::baseUrl(false) . self::FILE_DIR.'source/' . $this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'picture']) .
						'" alt="' . $this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'picture']) . '">';
					} ?>
					<?php echo $this->getData(['module', $this->getUrl(0),'posts',$articleId, 'content']); ?>
				</div>
			</div>
			<div class="row verticalAlignMiddle">
				<div class="col12 blogDate">
					<!-- bloc signature et date -->
					<?php echo $module::$articleSignature . ' - ';?>
					<?php echo template::ico('calendar-empty'); ?>
					<?php $date = mb_detect_encoding(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'publishedOn'])), 'UTF-8', true)
									? strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'publishedOn']))
									: utf8_encode(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'publishedOn'])));
							$heure =  mb_detect_encoding(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'publishedOn'])), 'UTF-8', true)
									? strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'publishedOn']))
									:  utf8_encode(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts',$articleId, 'publishedOn'])));
							echo $date . ' à ' . $heure;
					?>
					<!-- Bloc edition -->
					<?php if (

						$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
						AND
						(  // Propriétaire
							(
									$this->getData(['module',  $this->getUrl(0), 'posts',$articleId,'editConsent']) === $module::EDIT_OWNER
									AND ( $this->getData(['module',  $this->getUrl(0), 'posts',$articleId,'userId']) === $this->getUser('id')
									OR $this->getUser('group') === self::GROUP_ADMIN )
						)
						OR (
								// Groupe
								( $this->getData(['module',  $this->getUrl(0), 'posts', $articleId,'editConsent']) === self::GROUP_ADMIN
								OR $this->getData(['module',  $this->getUrl(0), 'posts', $articleId,'editConsent']) === self::GROUP_MODERATOR)
								AND $this->getUser('group') >=  $this->getData(['module',$this->getUrl(0), 'posts',$articleId,'editConsent'])
						)
						OR (
								// Tout le monde
								$this->getData(['module',  $this->getUrl(0), 'posts', $articleId,'editConsent']) === $module::EDIT_ALL
								AND $this->getUser('group') >= $module::$actions['config']
							)
						)
					): ?>
							<a href ="<?php echo helper::baseUrl() . $this->getUrl(0) . '/edit/' .$articleId . '/' . $_SESSION['csrf'];?>">
								<?php echo template::ico('pencil');?> Editer
							</a>
					<?php endif; ?>
				</div>
			</div>
			<?php if($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'commentClose'])): ?>
				<p>Cet article ne reçoit pas de commentaire.</p>
			<?php else: ?>
				<h5 id="comment">
					<?php  //$commentsNb = count($module::$comments); ?>
					<?php $commentsNb =  $module::$comments[$articleId];?>
					<?php $s =  $commentsNb === 1 ? '': 's' ?>
					<?php
												<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>">
												<?php echo $article['title']; ?>
											</a>
					 echo $commentsNb > 0 ? $commentsNb . ' ' .  'commentaire' . $s : 'Pas encore de commentaire'; 
					 ?>
				</h5>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
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
