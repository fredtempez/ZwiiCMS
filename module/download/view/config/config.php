<div class="row">
	<div class="col1">
		<?php echo template::button('downloadConfigBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
			'value' => template::ico('left')
		]); ?>
	</div>	
	<div class="col1 offset8">
		<?php echo template::button('downloadConfigSetup', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/option',
			'value' => template::ico('sliders'),
			'help' => 'Options'
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('downloadConfigCategories', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/categories',
			'value' => template::ico('table'),
			'help' => 'Catégories'
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('downloadConfigAdd', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/add',
			'class' => 'buttonGreen',
			'value' => template::ico('plus'),
			'help' => 'Ajouter une ressource'
		]); ?>
	</div>
</div>
<?php if ($module::$items): ?>
	<?php echo template::table([2, 2, 1, 2, 1, 1, 1, 1, 1], $module::$items, ['Titre', 'Catégorie ' . $module::$allCategories, 'Version', 'Du', 'Stats', 'État', 'Comm', '', '']); ?>
	<?php echo $module::$pages; ?>
<?php else: ?>
	<?php echo template::speech('Aucune ressource'); ?>
<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>