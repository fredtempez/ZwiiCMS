<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'feeds'])): ?>
	<div id="rssFeed">
		<a type="application/rss+xml" href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/rss'; ?>" target="_blank">
			<img src='module/blog/ressource/feed-icon-16.gif' />
			<?php
			echo $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel']) ? '<p>' . $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel']) . '</p>' : '';
			?>
		</a>
	</div>
<?php endif; ?>
<?php if (blog::$articles): ?>
		<?php foreach (blog::$articles as $articleId => $article): ?>
			<article id="article">
			<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'layout']) === true): ?>
				<!-- Présentation moderne -->
				<div class="row">
					<div class="col12">
						<h2 class="blogTitle">
							<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>">
								<?php echo $article['title']; ?>
							</a>
						</h2>
					</div>
				</div>
				<div class="row">
					<div class="col6 blogEdit">
						<!-- bloc signature -->
						<?php if (
							$this->getData(['module', $this->getUrl(0), 'config', 'showPseudo']) === true
						): ?>
							<?php echo template::ico('user'); ?>
							<?php echo $this->signature($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'userId'])); ?>
						<?php endif; ?>
						<!-- bloc Date -->
						<?php if (
							$this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true
							|| $this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true
						): ?>
							<?php echo template::ico('calendar-empty', ['margin' => 'left']); ?>
						<?php endif; ?>
						<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true): ?>
							<?php echo helper::dateUTF8(blog::$dateFormat, $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'publishedOn']), self::$i18nUI); ?>
						<?php endif; ?>
						<?php if (
							$this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true
							&& $this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true
						): ?>
							<?php echo '&nbsp;-&nbsp;'; ?>
						<?php endif; ?>
						<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true): ?>
							<?php echo helper::dateUTF8(blog::$timeFormat, $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'publishedOn']), self::$i18nUI); ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="col12">
						<?php if (
							$this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'picture']) &&
							file_exists($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'picture']))
						): ?>
							<?php $pictureSize = $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'pictureSize']) === null ? '100' : $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'pictureSize']); ?>
							<?php if ($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'hidePicture']) == false) {
								echo '<img class="blogArticlePicture blogArticlePicture' . $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'picturePosition']) .
									' pict' . $pictureSize . '" src="' . $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'picture']) .
									'" alt="' . basename($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'picture'])) . '">';
							} ?>
						<?php endif; ?>
						<?php echo $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'content']); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6 blogEdit">
						<!-- Bloc edition -->
						<?php if (
							$this->isConnected() === true
							and
							( // Propriétaire
								($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'editConsent']) === blog::EDIT_OWNER
									and ($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'userId']) === $this->getUser('id')
										or $this->getUser('role') === self::ROLE_ADMIN)
								)
								or (
										// Rôle
									($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'editConsent']) === self::ROLE_ADMIN
										or $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'editConsent']) === self::ROLE_EDITOR)
									and $this->getUser('role') >= $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'editConsent'])
								)
								or (
									// Tout le monde
									$this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'editConsent']) === blog::EDIT_ALL
									and $this->getUser('role') >= blog::$actions['config']
								)
							)
						): ?>
							<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/edit/' . $articleId; ?>">
								<?php echo template::ico('pencil'); ?> Éditer
							</a>
						<?php endif; ?>
					</div>
					<div class="col6 textAlignRight" id="comment">
						<?php if ($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'commentClose'])): ?>
							<p>Cet article ne reçoit pas de commentaire.</p>
						<?php else: ?>
							<p>
								<?php echo template::ico('comment', ['margin' => 'right']); ?>
								<?php
								if (blog::$comments[$articleId] > 0) {
									echo '<a href="' . helper::baseUrl() . $this->getUrl(0) . '/' . $articleId . '">';
									echo blog::$comments[$articleId] . ' commentaire' . (blog::$comments[$articleId] > 1 ? 's' : '');
									echo '</a>';
								} else {
									echo 'Pas encore de commentaire';
								}
								?>
							</p>
						<?php endif; ?>
					</div>
				</div>
				<!-- Présentation classique -->
			<?php else: ?>
				<div class="row">
					<!-- Bloc image -->
					<?php if (
						$article['picture'] &&
						file_exists($article['picture'])
					): ?>
					<!-- Afficher l'image si elle existe -->
					<div class="col3">
						<?php
							// Déterminer le nom de la miniature
							$picture = $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'picture']);
							$thumb = $this->getThumb($picture);
							// Créer la miniature si manquante
							if (file_exists($thumb) === false) {
								$this->makeThumb(
									$picture,
									null,
									self::THUMBS_WIDTH
								);
							}
						?>
						<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>" class="blogPicture">
							<img src="<?php echo $thumb; ?>" alt="<?php echo basename($article['picture']); ?>">
						</a>
					</div>
					<!-- Col9 pour le contenu -->
					<div class="col9">
				<?php else: ?>
						<!-- Pas d'image c'est un blog 12 -->
						<div class="col12">
				<?php endif; ?>
				<!-- On continue avec le titre -->
				<h2 class="blogTitle">
					<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>">
						<?php echo $article['title']; ?>
					</a>
				</h2>
				<!-- Bloc commentaire -->
				<div class="blogComment">
					<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>#comment">
						<?php if (blog::$comments[$articleId]): ?>
							<?php echo blog::$comments[$articleId]; ?>
							<?php echo template::ico('comment', ['margin' => 'left']); ?>
						<?php endif; ?>
					</a>
				</div>
				<!-- Bloc date -->
				<div class="blogDate">
					<!-- bloc signature -->
					<?php if (
						$this->getData(['module', $this->getUrl(0), 'config', 'showPseudo']) === true
					): ?>
						<?php echo template::ico('user'); ?>
						<?php echo $this->signature($this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'userId'])); ?>
					<?php endif; ?> <!-- bloc date -->
					<?php if (
						$this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true
						|| $this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true
					): ?>
						<?php echo template::ico('calendar-empty', ['margin' => 'left']); ?>
					<?php endif; ?>
					<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true): ?>
						<?php echo helper::dateUTF8(blog::$dateFormat, $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'publishedOn']), self::$i18nUI); ?>
					<?php endif; ?>
					<?php if (
						$this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true
						&& $this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true
					): ?>
						<?php echo '&nbsp;-&nbsp;'; ?>
					<?php endif; ?>
					<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true): ?>
						<?php echo helper::dateUTF8(blog::$timeFormat, $this->getData(['module', $this->getUrl(0), 'posts', $articleId, 'publishedOn']), self::$i18nUI); ?>
					<?php endif; ?>
				</div>
				<!-- Bloc contenu -->
				<div class="blogContent">
					<?php $lenght = $this->getData(['module', $this->getUrl(0), 'config', 'articlesLenght']); ?>
					<?php if ($lenght > 0): ?>
						<?php ?>
						<?php echo helper::subword($article['content'], 0, $lenght); ?>...
						<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $articleId; ?>">
							<button class="readMoreButton">
								<?php echo helper::translate('Lire la suite'); ?>
							</button>
						</a>
					<?php else: ?>
						<?php echo $article['content']; ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</article>
	<?php endforeach; ?>
<?php echo blog::$pages; ?>
<?php else: ?>
	<?php echo template::speech('Aucun article'); ?>
<?php endif; ?>