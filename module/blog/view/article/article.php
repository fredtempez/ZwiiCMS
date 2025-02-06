<div class="row">
	<div class="col12">
		<?php $pictureSize = $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'pictureSize']) === null ? '100' : $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'pictureSize']); ?>
		<?php if ($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'hidePicture']) == false) {
			echo '<img class="blogArticlePicture blogArticlePicture' . $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'picturePosition']) .
				' pict' . $pictureSize . '" src="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'picture']) .
				'" alt="' . $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'picture']) . '">';
		} ?>
		<?php echo $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'content']); ?>
	</div>
</div>
<div class="row verticalAlignMiddle">
	<div class="col6 textAlignLeft">
		<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'buttonBack'])): ?>
			<a href="<?php echo helper::baseUrl() . $this->getUrl(0); ?>">
				<?php echo template::ico('left') . helper::translate('Retour'); ?>
			</a>
		<?php endif; ?>
	</div>
	<div class="col6 newsDate textAlignRight">
		<!-- bloc signature -->
		<?php if (
			$this->getData(['module', $this->getUrl(0), 'config', 'showPseudo']) === true
		): ?>
			<?php echo template::ico('user'); ?>
			<?php echo $this->signature($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'userId'])) ?>
		<?php endif; ?>

		<!-- bloc date -->
		<?php if (
			$this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true
			|| $this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true
		): ?>
			<?php echo template::ico('calendar-empty', ['margin' => 'left']); ?>
		<?php endif; ?>
		<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true): ?>
			<?php echo helper::dateUTF8(blog::$dateFormat, $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn']), self::$i18nUI); ?>
		<?php endif; ?>
		<?php if (
			$this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true
			&& $this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true
		): ?>
			<?php echo '&nbsp;-&nbsp;'; ?>
		<?php endif; ?>
		<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true): ?>
			<?php echo helper::dateUTF8(blog::$timeFormat, $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn']), self::$i18nUI); ?>
		<?php endif; ?>
		<!-- Bloc edition -->
		<?php if (
			$this->isConnected() === true
			and
			(  // Propriétaire
				($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'editConsent']) === blog::EDIT_OWNER
					and ($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'userId']) === $this->getUser('id')
						or $this->getUser('role') === self::GROUP_ADMIN)
				)
				or (
						// Role
					($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'editConsent']) === self::GROUP_ADMIN
						or $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'editConsent']) === self::GROUP_EDITOR)
					and $this->getUser('role') >= $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'editConsent'])
				)
				or (
					// Tout le monde
					$this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'editConsent']) === blog::EDIT_ALL
					and $this->getUser('role') >= blog::$actions['config']
				)
			)
		): ?>
			&nbsp;-&nbsp;
			<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/edit/' . $this->getUrl(1); ?>">
				<?php echo template::ico('pencil'); ?> Éditer
			</a>
		<?php endif; ?>
		<!-- Bloc RSS-->
		<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'feeds'])): ?>
			&nbsp;-&nbsp;
			<div id="rssFeed">
				<a type="application/rss+xml" href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/rss'; ?>"
					target="_blank">
					<img src='module/blog/ressource/feed-icon-16.gif' />
					<?php
					echo '<p>' . $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel']) . '</p>';
					?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php if ($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'commentClose'])): ?>
	<p>Cet article ne reçoit pas de commentaire.</p>
<?php else: ?>
	<div class="row">
		<div class="col12" id="comment">
			<h3>
				<?php
				echo template::ico('comment', ['margin' => 'right']);
				if (blog::$nbCommentsApproved > 0) {
					echo blog::$nbCommentsApproved . ' commentaire' . (blog::$nbCommentsApproved > 1 ? 's' : '');
				} else {
					echo 'Pas encore de commentaire';
				}
				?>
			</h3>
		</div>
	</div>
	<?php echo template::formOpen('blogArticleForm'); ?>
	<?php echo template::text('blogArticleCommentShow', [
		'placeholder' => 'Rédiger un commentaire...',
		'readonly' => true
	]); ?>
	<div id="blogArticleCommentWrapper" class="displayNone">
		<?php if ($this->isConnected() === true): ?>
			<?php echo template::text('blogArticleUserName', [
				'label' => 'Nom',
				'readonly' => true,
				'value' => blog::$editCommentSignature
			]); ?>
			<?php echo template::hidden('blogArticleUserId', [
				'value' => $this->getUser('id')
			]); ?>
		<?php else: ?>
			<div class="row">
				<div class="col9">
					<?php echo template::text('blogArticleAuthor', [
						'label' => 'Nom'
					]); ?>
				</div>
				<div class="col1 textAlignCenter verticalAlignBottom">
					<div id="blogArticleOr">Ou</div>
				</div>
				<div class="col2 verticalAlignBottom">
					<?php echo template::button('blogArticleLogin', [
						'href' => helper::baseUrl() . 'user/login/' . str_replace('/', '_', $this->getUrl()) . '__comment',
						'value' => 'Connexion'
					]); ?>
				</div>
			</div>
		<?php endif; ?>
		<?php echo template::textarea('blogArticleContent', [
			'label' => 'Commentaire avec maximum ' . $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'commentMaxlength']) . ' caractères',
			'class' => 'editorWysiwygComment',
			'noDirty' => true,
			'maxlength' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'commentMaxlength'])
		]); ?>
		<div id="blogArticleContentAlarm"> </div>
		<?php if ($this->isConnected() === false): ?>
			<div class="row">
				<div class="col12">
					<?php echo template::captcha('blogArticleCaptcha', [
						'limit' => $this->getData(['config', 'connect', 'captchaStrong']),
						'type' => $this->getData(['config', 'connect', 'captchaType'])
					]); ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col2 offset8">
				<?php echo template::button('blogArticleCommentHide', [
					'class' => 'buttonGrey',
					'value' => 'Annuler'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::submit('blogArticleSubmit', [
					'value' => 'Envoyer',
					'ico' => ''
				]); ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<div class="row">
	<div class="col12">
		<?php foreach (blog::$comments as $commentId => $comment): ?>
			<div class="block">
				<h4>
					<?php echo template::ico('user'); ?>
					<?php echo blog::$commentsSignature[$commentId]; ?>
					<?php echo template::ico('calendar-empty'); ?>
					<?php echo helper::dateUTF8(blog::$dateFormat, $comment['createdOn'], self::$i18nUI) . ' - ' . helper::dateUTF8(blog::$timeFormat, $comment['createdOn'], self::$i18nUI); ?>
				</h4>
				<?php echo $comment['content']; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<?php echo blog::$pages; ?>