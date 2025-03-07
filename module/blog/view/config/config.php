<?php echo template::formOpen('blogConfig'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('blogConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0) . '/' . self::$siteContent,
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col1 offset9">
			<?php echo template::button('blogConfigOption', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/option',
				'value' => template::ico('sliders'),
				'help' => 'Options de configuration'
			]); ?>

		</div>
		<div class="col1">
			<?php echo template::button('blogConfigAdd', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/add',
				'value' => template::ico('plus'),
				'class' => 'buttonGreen',
				'help' => 'Rédiger un article'
			]); ?>
		</div>
	</div>
<?php echo template::formClose(); ?>
<?php if(blog::$articles): ?>
	<?php echo template::table([4, 4, 1, 1, 1, 1], blog::$articles, ['Titre', 'Publication', 'État', 'Commentaires', '','']); ?>
	<?php echo blog::$pages; ?>
<?php else: ?>
	<?php echo template::speech('Aucun article'); ?>
<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo blog::VERSION; ?>
</div>

