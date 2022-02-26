<div class="row">
	<div class="col1">
		<?php echo template::button('themeFontBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'theme',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('pageEditHelp', [
			'href' => 'https://doc.zwiicms.fr/fontes',
			'target' => '_blank',
			'value' => template::ico('help'),
			'class' => 'buttonHelp',
			'help' => 'Consulter l\'aide en ligne'
		]); ?>
	</div>
	<div class="col1 offset9">
		<?php echo template::button('themeFontAdd', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/fontAdd',
			'value' => template::ico('plus'),
			'help' => 'Ajouter une fonte'
		]); ?>
	</div>
</div>
<?php if($module::$fontsList): ?>
  <?php echo template::table([3, 3, 3, 3, 1], $module::$fontsList, ['Family Name', 'Font Id', 'Affectation', 'Ressource', 'Effacer']); ?>
<?php else: ?>
  <?php echo template::speech('Aucune fonte !'); ?>
<?php endif; ?>