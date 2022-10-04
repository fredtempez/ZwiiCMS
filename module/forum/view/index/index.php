<?php if($module::$sujets): ?>
	<sujet>
		<?php foreach($module::$sujets as $sujetId => $sujet): ?>
			<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'sujetsLenght']) === 0): ?>
				<div class="row">
					<div class="col12">
						<h2 class="blogTitle">
								<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $sujetId; ?>">
									<?php echo $sujet['title']; ?>
								</a>
						</h2>
					</div>
				</div>
				<div class="row">
					<div class="col12">
						<?php if (  $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'picture']) &&
									file_exists( self::FILE_DIR.'source/' . $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'picture'])) ): ?>
								<?php $pictureSize =  $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'pictureSize']) === null ? '100' : $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'pictureSize']); ?>
								<?php if ($this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'hidePicture']) == false) {
									echo '<img class="blogSujetPicture blogSujetPicture' . $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'picturePosition']) .
									' pict' . $pictureSize . '" src="' . helper::baseUrl(false) . self::FILE_DIR.'source/' . $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'picture']) .
									'" alt="' . $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'picture']) . '">';
								} ?>
						<?php endif; ?>
					<?php echo $this->getData(['module', $this->getUrl(0),'posts', $sujetId, 'content']); ?>
					</div>
				</div>
				<div class="row verticalAlignMiddle">
					<div class="col6 blogDate">
						<!-- bloc signature et date -->
						<?php echo $module->signature($this->getData(['module', $this->getUrl(0),  'posts', $sujetId, 'userId']));?>
						<?php echo ' - ';?>
						<?php echo template::ico('calendar-empty'); ?>
						<?php $date = mb_detect_encoding(PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'publishedOn'])), 'UTF-8', true)
										? PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'publishedOn']))
										: utf8_encode(PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'publishedOn'])));
								$heure =  mb_detect_encoding(PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'publishedOn'])), 'UTF-8', true)
										? PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'publishedOn']))
										:  utf8_encode(PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'publishedOn'])));
								echo $date . ' à ' . $heure;
						?>
						<!-- Bloc edition -->
						<?php if (

							$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
							AND
							(  // Propriétaire
								(
										$this->getData(['module',  $this->getUrl(0), 'posts', $sujetId,'editConsent']) === $module::EDIT_OWNER
										AND ( $this->getData(['module',  $this->getUrl(0), 'posts', $sujetId,'userId']) === $this->getUser('id')
										OR $this->getUser('group') === self::GROUP_ADMIN )
							)
							OR (
									// Groupe
									( $this->getData(['module',  $this->getUrl(0), 'posts', $sujetId,'editConsent']) === self::GROUP_ADMIN
									OR $this->getData(['module',  $this->getUrl(0), 'posts', $sujetId,'editConsent']) === self::GROUP_MODERATOR)
									AND $this->getUser('group') >=  $this->getData(['module',$this->getUrl(0), 'posts', $sujetId,'editConsent'])
							)
							OR (
									// Tout le monde
									$this->getData(['module',  $this->getUrl(0), 'posts', $sujetId,'editConsent']) === $module::EDIT_ALL
									AND $this->getUser('group') >= $module::$actions['config']
								)
							)
						): ?>
								<a href ="<?php echo helper::baseUrl() . $this->getUrl(0) . '/edit/' .$sujetId . '/' . $_SESSION['csrf'];?>">
									<?php echo template::ico('pencil');?> Éditer
								</a>
						<?php endif; ?>
					</div>
					<div class="col6 textAlignRight" id="comment">
						<?php if($this->getData(['module', $this->getUrl(0), 'posts', $sujetId, 'commentClose'])): ?>
							<p>Cet sujet ne reçoit pas de Réponse.</p>
						<?php else: ?>
							<p>
								<?php echo template::ico('comment', ['margin' => 'right']); ?>
								<?php
									if ($module::$comments[$sujetId] > 0) {
										echo '<a href="'. helper::baseUrl() . $this->getUrl(0) . '/' . $sujetId .'">';
										echo $module::$comments[$sujetId] . ' Réponse' . ($module::$comments[$sujetId] > 1 ? 's' : '');
										echo '</a>';
									} else {
										echo  'Pas encore de Réponse';
									}
								?>
							</p>
						<?php endif; ?>
					</div>
				</div>
			<?php else: ?>
				<div class="row rowSujet">
					<?php if (  $sujet['picture'] &&
								file_exists( self::FILE_DIR . 'source/' . $sujet['picture']) ):?>
						<div class="col3">
							<?php // Déterminer le nom de la miniature
								$parts = explode('/',$sujet['picture']);
								$thumb = str_replace ($parts[(count($parts)-1)],'mini_' . $parts[(count($parts)-1)], $sujet['picture']);
								// Créer la miniature si manquante
								if (!file_exists( self::FILE_DIR . 'thumb/' . $thumb) ) {
									$this->makeThumb(  self::FILE_DIR . 'source/' . $sujet['picture'],
													self::FILE_DIR . 'thumb/' . $thumb,
													self::THUMBS_WIDTH);
								}
							?>
							<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $sujetId; ?>" class="blogPicture">
								<img src="<?php echo helper::baseUrl(false) .  self::FILE_DIR . 'thumb/' . $thumb; ?>" alt="<?php echo $sujet['picture']; ?>">
							</a>
						</div>
						<div class="col9">
					<?php else:?>
						<div class="col12">
					<?php endif;?>
						<h2 class="blogTitle">
							<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $sujetId; ?>">
								<?php echo $sujet['title']; ?>
							</a>
						</h2>
						<div class="blogComment">
							<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $sujetId; ?>#comment">
								<?php if ($sujet['comment']): ?>
									<?php echo count($sujet['comment']); ?>
								<?php endif; ?>
							</a>
							<?php echo template::ico('comment', ['margin' => 'left']); ?>
						</div>
						<div class="blogDate">
							<?php echo template::ico('calendar-empty'); ?>
							<?php echo mb_detect_encoding(PHP81_BC\strftime('%d %B %Y - %H:%M',  $sujet['publishedOn']), 'UTF-8', true)
										? PHP81_BC\strftime('%d %B %Y', $sujet['publishedOn'])
										: utf8_encode(PHP81_BC\strftime('%d %B %Y', $sujet['publishedOn']));  ?>
						</div>
						<p class="blogContent">
								<?php $lenght =  $this->getData(['module',$this->getUrl(0), 'config', 'sujetsLenght']) !== 0 ?  $this->getData(['module',$this->getUrl(0), 'config', 'sujetsLenght']) : 500 ?>
								<?php echo helper::subword(strip_tags($sujet['content'],'<br><p>'), 0, $lenght); ?>...
								<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/' . $sujetId; ?>">Lire la suite</a>
							</p>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</sujet>
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
	<?php echo template::speech('Aucun sujet.'); ?>
<?php endif; ?>
