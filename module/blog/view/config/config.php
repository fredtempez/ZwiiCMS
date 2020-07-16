<div class="row">
	<div class="col2">
		<?php echo template::button('blogConfigBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2 offset8">
		<?php echo template::button('blogConfigAdd', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/add',
			'ico' => 'plus',
			'value' => 'Article'
		]); ?>
	</div>
</div>
<?php if($module::$articles): ?>
	<?php echo template::table([4, 4, 1, 1, 1, 1], $module::$articles, ['Titre', 'Date de publication', 'État', 'Commentaires', '','']); ?>
	<?php echo $module::$pages; ?>
<?php else: ?>
	<?php echo template::speech('Aucun article.'); ?>
<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo $module::BLOG_VERSION; ?>
</div>
