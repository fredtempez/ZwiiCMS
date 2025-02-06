<div class="row">
	<div class="col12">
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
			<?php echo $this->signature($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'userId'])); ?>
		<?php endif; ?>
		<!-- bloc date -->
		<?php if (
			$this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true
			|| $this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true
		): ?>
			<?php echo template::ico('calendar-empty', ['margin' => 'left']); ?>
		<?php endif; ?>
		<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true): ?>
			<?php echo helper::dateUTF8(news::$dateFormat, $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn']), self::$i18nUI); ?>
		<?php endif; ?>
		<?php if (
			$this->getData(['module', $this->getUrl(0), 'config', 'showDate']) === true
			&& $this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true
		): ?>
			<?php echo '&nbsp;-&nbsp;'; ?>
		<?php endif; ?>
		<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'showTime']) === true): ?>
			<?php echo helper::dateUTF8(news::$timeFormat, $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn']), self::$i18nUI); ?>
		<?php endif; ?> <!-- Bloc edition -->
		<?php if (
			$this->isConnected() === true
			and
			(  // Propriétaire
				($this->getUser('role') === self::GROUP_ADMIN)
			)
		): ?>
			<a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/edit/' . $this->getUrl(1); ?>">
				<?php echo template::ico('pencil', ['margin' => 'left']); ?>
				<?php echo helper::translate('Éditer'); ?>
			</a>
		<?php endif; ?>
		<!-- Bloc RSS-->
		<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'feeds'])): ?>
			<div id="rssFeed">
				<a type="application/rss+xml" href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/rss'; ?>"
					target="_blank">
					&nbsp;<img src='module/news/ressource/feed-icon-16.gif' />
					<?php
					echo '<p>' . $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel']) . '</p>';
					?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>