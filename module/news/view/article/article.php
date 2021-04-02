<div class="row">
    <div class="col12">
	    <?php echo $this->getData(['module', $this->getUrl(0),'posts', $this->getUrl(1), 'content']); ?>
    </div>
</div>
<div class="row verticalAlignMiddle">
	<div class="col12 newsDate">
		<!-- bloc signature et date -->
		<?php echo $module::$articleSignature . ' - ';?>
		<i class="far fa-calendar-alt"></i>
		<?php $date = mb_detect_encoding(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn'])), 'UTF-8', true)
						? strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn']))
						: utf8_encode(strftime('%d %B %Y', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn'])));
				$heure =  mb_detect_encoding(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn'])), 'UTF-8', true)
						? strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn']))
						:  utf8_encode(strftime('%H:%M', $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'publishedOn'])));
				echo $date . ' à ' . $heure; 
		?>
		<!-- Bloc edition -->
        <?php if (
            $this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
            AND
            (  // Propriétaire
                (	$this->getUser('group') === self::GROUP_ADMIN )
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