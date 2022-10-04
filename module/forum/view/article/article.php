<div class="row">
	<div class="col12">
		<?php $pictureSize =  $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'pictureSize']) === null ? '100' : $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'pictureSize']); ?>
		<?php if ($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'hidePicture']) == false) {
			echo '<img class="blogSujetPicture blogSujetPicture' . $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'picturePosition']) .
			' pict' . $pictureSize . '" src="' . helper::baseUrl(false) . self::FILE_DIR.'source/' . $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'picture']) .
			'" alt="' . $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'picture']) . '">';
		} ?>
		<?php echo $this->getData(['module', $this->getUrl(0),'posts', $this->getUrl(1), 'content']); ?>
	</div>
</div>
<div class="row verticalAlignMiddle">
	<div class="col12 blogDate">
		<!-- bloc signature et date -->
		<?php echo $module::$sujetSignature . ' - ';?>
		<?php echo template::ico('calendar-empty'); ?>
		<?php $date = mb_detect_encoding(PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn'])), 'UTF-8', true)
						? PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn']))
						: utf8_encode(PHP81_BC\strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn'])));
				$heure =  mb_detect_encoding(PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn'])), 'UTF-8', true)
						? PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn']))
						:  utf8_encode(PHP81_BC\strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn'])));
				echo $date . ' à ' . $heure;
		?>
		<!-- Bloc edition -->
		<?php if (

			$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
			AND
			(  // Propriétaire
				(
						$this->getData(['module',  $this->getUrl(0), 'posts', $this->getUrl(1),'editConsent']) === $module::EDIT_OWNER
						AND ( $this->getData(['module',  $this->getUrl(0), 'posts', $this->getUrl(1),'userId']) === $this->getUser('id')
						OR $this->getUser('group') === self::GROUP_ADMIN )
			)
			OR (
					// Groupe
					( $this->getData(['module',  $this->getUrl(0), 'posts',  $this->getUrl(1),'editConsent']) === self::GROUP_ADMIN
					OR $this->getData(['module',  $this->getUrl(0), 'posts',  $this->getUrl(1),'editConsent']) === self::GROUP_MODERATOR)
					AND $this->getUser('group') >=  $this->getData(['module',$this->getUrl(0), 'posts', $this->getUrl(1),'editConsent'])
			)
			OR (
					// Tout le monde
					$this->getData(['module',  $this->getUrl(0), 'posts',  $this->getUrl(1),'editConsent']) === $module::EDIT_ALL
					AND $this->getUser('group') >= $module::$actions['config']
				)
			)
		): ?>
				<a href ="<?php echo helper::baseUrl() . $this->getUrl(0) . '/edit/' . $this->getUrl(1) . '/' . $_SESSION['csrf'];?>">
					<?php echo template::ico('pencil');?> Éditer
				</a>
		<?php endif; ?>
		<!-- Bloc RSS-->
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
	</div>
</div>
<?php if($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'commentClose'])): ?>
	<p>Cet sujet ne reçoit pas de Réponse.</p>
<?php else: ?>
	<div class="row">
		<div class="col12" id="comment">
			<h3>
				<?php
					echo template::ico('comment', ['margin' => 'right']);
					if ($module::$nbCommentsApproved > 0) {
						echo $module::$nbCommentsApproved . ' Réponse' . ($module::$nbCommentsApproved > 1 ? 's' : '');
					} else {
						echo  'Pas encore de Réponse';
					}
				?>
			</h3>
		</div>
	</div>
	<?php echo template::formOpen('blogSujetForm'); ?>
		<?php echo template::text('blogSujetCommentShow', [
			'placeholder' => 'Rédiger un Réponse...',
			'readonly' => true
		]); ?>
		<div id="blogSujetCommentWrapper" class="displayNone">
				<?php if($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')): ?>
				<?php echo template::text('blogSujetUserName', [
					'label' => 'Nom',
					'readonly' => true,
					'value' => $module::$editCommentSignature
				]); ?>
				<?php echo template::hidden('blogSujetUserId', [
					'value' => $this->getUser('id')
				]); ?>
			<?php else: ?>
				<div class="row">
					<div class="col9">
						<?php echo template::text('blogSujetAuthor', [
							'label' => 'Nom'
						]); ?>
					</div>
					<div class="col1 textAlignCenter verticalAlignBottom">
						<div id="blogSujetOr">Ou</div>
					</div>
					<div class="col2 verticalAlignBottom">
						<?php echo template::button('blogSujetLogin', [
							'href' => helper::baseUrl() . 'user/login/' . str_replace('/', '_', $this->getUrl()) . '__comment',
							'value' => 'Connexion'
						]); ?>
					</div>
				</div>
			<?php endif; ?>
			<?php echo template::textarea('blogSujetContent', [
					'label' => 'Réponse avec maximum '.$this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'commentMaxlength']).' caractères',
					'class' => 'editorWysiwygComment',
					'noDirty' => true,
					'maxlength' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'commentMaxlength'])
			]); ?>
			<div id="blogSujetContentAlarm"> </div>
			<?php if($this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')): ?>
				<div class="row">
					<div class="col12">
						<?php echo template::captcha('blogSujetCaptcha', [
							'limit' => $this->getData(['config','connect', 'captchaStrong']),
							'type' => $this->getData(['config','connect', 'captchaType'])
						]); ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="row">
				<div class="col2 offset8">
					<?php echo template::button('blogSujetCommentHide', [
						'class' => 'buttonGrey',
						'value' => 'Annuler'
					]); ?>
				</div>
				<div class="col2">
					<?php echo template::submit('blogSujetSubmit', [
						'value' => 'Envoyer',
						'ico' => ''
					]); ?>
				</div>
			</div>
		</div>
<?php endif;?>
<div class="row">
	<div class="col12">
		<?php foreach($module::$comments as $commentId => $comment): ?>
			<div class="block">
				<h4><?php echo $module::$commentsSignature[$commentId]; ?>
				le <?php  echo mb_detect_encoding(PHP81_BC\strftime('%d %B %Y - %H:%M', $comment['createdOn']), 'UTF-8', true)
										? PHP81_BC\strftime('%d %B %Y - %H:%M', $comment['createdOn'])
										: utf8_encode(PHP81_BC\strftime('%d %B %Y - %H:%M', $comment['createdOn']));
				?>
				</h4>
				<?php echo $comment['content']; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<?php echo $module::$pages; ?>