<div class="row">
	<div class="col9">
		<div class="row">
			<div class="col12">
				<?php echo $this->getData(['module', $this->getUrl(0),'items', $this->getUrl(1), 'content']); ?>
			</div>
		</div>
		<div class="row verticalAlignMiddle">
			<div class="col12 downloadDate">
				<?php echo $module::$itemSignature . ' - ';?>
				<i class="far fa-calendar-alt"></i>
				<?php $date = mb_detect_encoding(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn'])), 'UTF-8', true)
								? strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn']))
								: utf8_encode(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn'])));
						$heure =  mb_detect_encoding(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn'])), 'UTF-8', true)
								? strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn']))
								:  utf8_encode(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'publishedOn'])));
						echo $date . ' à ' . $heure; 
				?>

				<!-- Bouton d'édition -->
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
					<a href ="<?php echo helper::baseUrl() . $this->getUrl(0) . '/edit/' . $this->getUrl(1) . '/' . $_SESSION['csrf'];?>">
						<?php echo template::ico('pencil');?> Editer
					</a>
					<?php endif; ?>
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
			</div>
		</div>
	</div>
	<div class="col3">
		<div class="row">
			<div class="col12">
				<?php $pictureSize =  $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'pictureSize']) === null ? '100' : $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'pictureSize']); ?>
				<?php 
					echo '<img class="downloadItemPicture" src="' . helper::baseUrl(false) . self::FILE_DIR.'source/' . $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'picture']) .
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
		<div class="row">
			<div class="col12 textAlignCenter">
				<span>Auteur :
				<?php echo $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'fileAuthor']); ?>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col12 textAlignCenter">
				<span>Licence :
				<?php echo $module::$itemLicense[$this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'fileLicense'])]; ?>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col12 textAlignCenter">
				<span>Téléchargements :
				<?php echo $module::$statSum; ?>
				</span>
			</div>
		</div>
	</div>
</div>
<!-- Bloc commentaire -->
<div class="row">
	<div class="col9">
		<?php if($this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'commentClose'])): ?>
			<p>Cet item ne reçoit pas de commentaire.</p>
		<?php else: ?>
			<h3 id="comment">
				<?php  //$commentsNb = count($module::$comments); ?>
				<?php $commentsNb = $module::$nbCommentsApproved; ?>
				<?php $s =  $commentsNb === 1 ? '': 's' ?>
				<?php echo $commentsNb > 0 ? $commentsNb . ' ' .  'commentaire' . $s : 'Pas encore de commentaire'; ?>
			</h3>
			<?php echo template::formOpen('downloadItemForm'); ?>
				<?php echo template::text('downloadItemCommentShow', [
					'placeholder' => 'Rédiger un commentaire...',
					'readonly' => true
				]); ?>
				<div id="downloadItemCommentWrapper" class="displayNone">
						<?php if($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')): ?>
						<?php echo template::text('downloadItemUserName', [
							'label' => 'Nom',
							'readonly' => true,
							'value' => $module::$editCommentSignature
						]); ?>
						<?php echo template::hidden('downloadItemUserId', [
							'value' => $this->getUser('id')
						]); ?>
					<?php else: ?>
						<div class="row">
							<div class="col9">
								<?php echo template::text('downloadItemAuthor', [
									'label' => 'Nom'
								]); ?>
							</div>
							<div class="col1 textAlignCenter verticalAlignBottom">
								<div id="downloadItemOr">Ou</div>
							</div>
							<div class="col2 verticalAlignBottom">
								<?php echo template::button('downloadItemLogin', [
									'href' => helper::baseUrl() . 'user/login/' . str_replace('/', '_', $this->getUrl()) . '__comment',
									'value' => 'Connexion'
								]); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php echo template::textarea('downloadItemContent', [
							'label' => 'Commentaire avec maximum '.$this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'commentMaxlength']).' caractères',
							'class' => 'editorWysiwygComment',
							'noDirty' => true,
							'maxlength' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(1), 'commentMaxlength'])
					]); ?>
					<div id="downloadItemContentAlarm"> </div>
					<?php if($this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')): ?>
						<div class="row">
							<div class="col12">
								<?php echo template::captcha('downloadItemCaptcha', [
									'limit' => $this->getData(['config','captchaStrong'])
								]); ?>
							</div>
						</div>
					<?php endif; ?>
					<div class="row">
						<div class="col2 offset8">
							<?php echo template::button('downloadItemCommentHide', [
								'class' => 'buttonGrey',
								'value' => 'Annuler'
							]); ?>
						</div>
						<div class="col2">
							<?php echo template::submit('downloadItemSubmit', [
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
	</div>
</div>
<?php echo $module::$pages; ?>