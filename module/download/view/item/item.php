<article>
	<div class="row">
		<div class="col10">
			<div class="downloadDate">
				<i class="far fa-calendar-alt"></i>
				<?php $date = mb_detect_encoding(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn'])), 'UTF-8', true)
								? strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn']))
								: utf8_encode(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn'])));
					  $heure =  mb_detect_encoding(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn'])), 'UTF-8', true)
					  			? strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn']))
								:  utf8_encode(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn'])));
					  echo $date . ' à ' . $heure; 
				?>
			</div>
		</div>
			<div class="col2">
			<?php if (
					$this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
					AND
					(  // Propriétaire
						(
							 $this->getData(['module',  $this->getUrl(0), 'items', $this->getUrl(1),'editConsent']) === $module::EDIT_OWNER
							 AND ( $this->getData(['module',  $this->getUrl(0), 'items', $this->getUrl(1),'userId']) === $this->getUser('id')
							 OR $this->getUser('group') === self::GROUP_ADMIN )
					)
					OR (
							// Groupe
							( $this->getData(['module',  $this->getUrl(0), 'items',  $this->getUrl(1),'editConsent']) === self::GROUP_ADMIN
							OR $this->getData(['module',  $this->getUrl(0), 'items',  $this->getUrl(1),'editConsent']) === self::GROUP_MODERATOR)
							AND $this->getUser('group') >=  $this->getData(['module',$this->getUrl(0), 'items', $this->getUrl(1),'editConsent'])
					)
					OR (
							// Tout le monde
							$this->getData(['module',  $this->getUrl(0), 'items',  $this->getUrl(1),'editConsent']) === $module::EDIT_ALL
							AND $this->getUser('group') >= $module::$actions['config']
						)
					)
				): ?>
					<?php echo template::button('downloadItemEdit', [
								'href' => helper::baseUrl() . $this->getUrl(0) . '/edit/' . $this->getUrl(1) . '/' . $_SESSION['csrf'],
								'value' => 'Editer'
					]); ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="row">
		<div class="col3">
			<div class="row">
				<div class="col12">
					<?php $pictureSize =  $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'pictureSize']) === null ? '100' : $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'pictureSize']); ?>
					<?php 
						echo '<img class="downloaditemPicture" src="' . helper::baseUrl(false) . self::FILE_DIR.'source/' . $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'picture']) .
						'" alt="' . $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'picture']) . '">';
					?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::button('downloadItemFile', [
						//'href' => self::FILE_DIR . 'source/' . $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'file']),
						'href' => helper::baseUrl() . $this->getUrl(0) . '/downloadFile/' . $this->getUrl(1) . '/' . $_SESSION['csrf'],
						'value' => 'Télécharger'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12 textAlignCenter">
				 	<?php echo 'Version n°' .  $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'fileVersion']); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12 textAlignCenter">
					<?php $date = mb_detect_encoding(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'fileDate'])), 'UTF-8', true)
						? strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'fileDate']))
						: utf8_encode(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'fileDate'])));
					?>
				 	<?php echo ' du ' .  $date; ?>
				</div>
			</div>
		</div>
		<div class="col9">
			<?php echo $this->getData(['module', $this->getUrl(0),'items', $this->getUrl(1), 'content']); ?>
		</div>
		<div class="col12">
			<p class="clearBoth signature"><?php echo $module::$itemSignature;?></p>
		</div>
	</div>
		<!-- Bloc RSS-->
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
	<?php if($this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'commentClose'])): ?>
		<p>Cet item ne reçoit pas de commentaire.</p>
	<?php else: ?>
		<h3 id="comment">
			<?php  //$commentsNb = count($module::$comments); ?>
			<?php $commentsNb = $module::$nbCommentsApproved; ?>
			<?php $s =  $commentsNb === 1 ? '': 's' ?>
			<?php echo $commentsNb > 0 ? $commentsNb . ' ' .  'commentaire' . $s : 'Pas encore de commentaire'; ?>
		</h3>
		<?php echo template::formOpen('downloaditemForm'); ?>
			<?php echo template::text('downloaditemCommentShow', [
				'placeholder' => 'Rédiger un commentaire...',
				'readonly' => true
			]); ?>
			<div id="downloaditemCommentWrapper" class="displayNone">
					<?php if($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')): ?>
					<?php echo template::text('downloaditemUserName', [
						'label' => 'Nom',
						'readonly' => true,
						'value' => $module::$editCommentSignature
					]); ?>
					<?php echo template::hidden('downloaditemUserId', [
						'value' => $this->getUser('id')
					]); ?>
				<?php else: ?>
					<div class="row">
						<div class="col9">
							<?php echo template::text('downloaditemAuthor', [
								'label' => 'Nom'
							]); ?>
						</div>
						<div class="col1 textAlignCenter verticalAlignBottom">
							<div id="downloaditemOr">Ou</div>
						</div>
						<div class="col2 verticalAlignBottom">
							<?php echo template::button('downloaditemLogin', [
								'href' => helper::baseUrl() . 'user/login/' . str_replace('/', '_', $this->getUrl()) . '__comment',
								'value' => 'Connexion'
							]); ?>
						</div>
					</div>
				<?php endif; ?>
				<?php echo template::textarea('downloaditemContent', [
						'label' => 'Commentaire avec maximum '.$this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'commentMaxlength']).' caractères',
						'class' => 'editorWysiwygComment',
						'noDirty' => true,
						'maxlength' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'commentMaxlength'])
				]); ?>
				<div id="downloaditemContentAlarm"> </div>
				<?php if($this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')): ?>
					<div class="row">
						<div class="col12">
							<?php echo template::captcha('downloaditemCaptcha', [
								'limit' => $this->getData(['config','captchaStrong'])
							]); ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="row">
					<div class="col2 offset8">
						<?php echo template::button('downloaditemCommentHide', [
							'class' => 'buttonGrey',
							'value' => 'Annuler'
						]); ?>
					</div>
					<div class="col2">
						<?php echo template::submit('downloaditemSubmit', [
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
					le <?php  echo mb_detect_encoding(strftime('%d %B %Y - %H:%M', $comment['createdOn']), 'UTF-8', true)
										 ? strftime('%d %B %Y - %H:%M', $comment['createdOn'])
										 : utf8_encode(strftime('%d %B %Y - %H:%M', $comment['createdOn']));
					?>
					<?php echo $comment['content']; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php echo $module::$pages; ?>
</article>