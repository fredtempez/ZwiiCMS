<div class="row">
	<div class="col1">
		<?php echo template::button('themeFontBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'theme',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1">
		<?php /* echo template::button('pageEditHelp', [
			'href' => 'https://doc.zwiicms.fr/fontes',
			'target' => '_blank',
			'value' => template::ico('help'),
			'class' => 'buttonHelp'
		]); */ ?>
	</div>
	<div class="col1 offset9">
		<?php echo template::button('themeFontAdd', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/fontAdd',
			'value' => template::ico('plus'),
			'class' => 'buttonGreen',
			'help' => 'Ajouter une fonte'
		]); ?>
	</div>
</div>
<?php if ($module::$fontsDetail) : ?>
	<?php echo template::table([2, 2, 3, 2, 1, 1, 1], $module::$fontsDetail, ['FontId', 'Nom', 'Famille', 'Affectation', 'Origine', '', '']); ?>
<?php else : ?>
	<?php echo template::speech('Aucune fonte !'); ?>
<?php endif; ?>