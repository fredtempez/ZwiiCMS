<?php echo template::formOpen('blogConfig'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('blogConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0), 'posts',
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col1 offset9">
			<?php echo template::button('blogConfigOption', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/option',
				'value' => template::ico('cogs'),
				'help' => 'Options de configuration'
			]); ?>

		</div>
		<div class="col1">
			<?php echo template::button('blogConfigAdd', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/add',
				'value' => template::ico('plus'),
				'help' => 'Rédiger un article'
			]); ?>
		</div>
	</div>
<?php echo template::formClose(); ?>
<?php if($module::$articles): ?>
	<?php echo template::table([4, 4, 1, 1, 1, 1], $module::$articles, ['Titre', 'Date de publication', 'État', 'Commentaires', '','']); ?>
	<?php echo $module::$pages; ?>
<?php else: ?>
	<?php echo template::speech('Aucun article.'); ?>
<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>

