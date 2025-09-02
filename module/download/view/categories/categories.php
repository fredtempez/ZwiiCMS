<?php echo template::formOpen('categoriesForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('categoriesBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
</div>
<div class="row ">
	<div class="col10 offset1">
		<div class="block">
			<h4>Nouvelle catégorie</h4>
			<div class="row ">
				<div class="col10">
					<?php echo template::text('categoriesTitle', [
						'label' => 'Nom',
						'value' => $this->getData(['module', $this->getUrl(0), 'categories', $this->getUrl(2), 'title'])
					]); ?>
				</div>
				<div class="col2 verticalAlignBottom">
					<?php echo template::submit('categoriesSubmit', [
						'ico' => 'plus',
						'value' => '',
					]); ?>
				</div>
			</div>
		</div>
	</div>
	<?php echo template::formClose(); ?>
	<?php if ($module::$categories): ?>
		<?php echo template::table([2, 6, 1, 1], $module::$categories, ['Nom', 'URL', '', '']); ?>
		<?php echo $module::$pages; ?>
	<?php else: ?>
		<?php echo template::speech('Aucune catégorie'); ?>
	<?php endif; ?>
	<div class=" moduleVersion">Version n°
		<?php echo $module::VERSION; ?>
	</div>