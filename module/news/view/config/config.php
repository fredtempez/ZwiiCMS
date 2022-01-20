<?php echo template::formOpen('newsConfig'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('newsConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),'posts',
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col1 offset9">
			<?php echo template::button('newsConfigAdd', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/add',
				'value' => template::ico('plus')
			]); ?>
		</div>
		<div class="col1">
			<?php echo template::button('newsConfigLayout', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/option',
				'value' => template::ico('brush')
			]); ?>
		</div>
	</div>

	<?php if($module::$news): ?>
		<?php echo template::table([4, 2, 2, 2, 1, 1], $module::$news, ['Titre', 'Publication', 'Dépublication', 'État', '', '']); ?>
		<?php echo $module::$pages; ?>
	<?php else: ?>
		<?php echo template::speech('Aucune news.'); ?>
	<?php endif; ?>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>