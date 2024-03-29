<div class="row">
    <div class="col12">
	    <?php echo $this->getData(['module', $this->getUrl(0),'posts', $this->getUrl(1), 'content']); ?>
    </div>
</div>
<div class="row verticalAlignMiddle">
	<div class="col12 newsDate">
		<!-- bloc signature et date -->
		<?php echo template::ico('user'); ?>
		<?php echo $module::$articleSignature . ' - ';?>
		<?php echo template::ico('calendar-empty'); ?>
		<?php echo helper::dateUTF8('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn'])) . ' - ' . helper::dateUTF8('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn'])); ?>
		<!-- Bloc edition -->
        <?php if (
            $this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
            AND
            (  // Propriétaire
                (	$this->getUser('group') === self::GROUP_ADMIN )
            )
            ): ?>
                <a href ="<?php echo helper::baseUrl() . $this->getUrl(0) . '/edit/' . $this->getUrl(1);?>">
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