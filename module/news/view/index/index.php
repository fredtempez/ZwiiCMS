<?php if($module::$news): ?>
	<div class="row">
		<div class="col12">
			<?php foreach($module::$news as $newsId => $news): ?>
				<h1 class="newsTitle">
					<?php echo $news['title']; ?>
				</h1>
				<div class="newsDate">
					<i class="far fa-calendar-alt"></i>					
					<?php echo utf8_encode(strftime('%d %B %Y', $news['publishedOn'])); ?>
				</div>
				<div class="newsSignature">
					<?php echo $this->getData(['user', $news['userId'], 'firstname']) . ' ' . $this->getData(['user', $news['userId'], 'lastname']); ?>
				</div>				
				<div class="newsContent">
					<?php echo $news['content']; ?>
				</div>
				<div class="clearBoth"></div>				
				<hr />
			<?php endforeach; ?>
		</div>
	</div>
	<?php echo $module::$pages; ?>
<?php else: ?>
	<?php echo template::speech('Aucune news.'); ?>
<?php endif; ?>