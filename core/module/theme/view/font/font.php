<div class="row">
	<div class="col1">
		<?php echo Template::button('themeFontBack', [
			'class' => 'buttonGrey',
			'href' => Helper::baseUrl() . 'theme',
			'value' => Template::ico('left')
		]); ?>
	</div>
	<div class="col1">
		<?php /* echo Template::button('pageEditHelp', [
			'href' => 'https://doc.zwiicms.fr/fontes',
			'target' => '_blank',
			'value' => Template::ico('help'),
			'class' => 'buttonHelp'
		]); */ ?>
	</div>
	<div class="col1 offset9">
		<?php echo Template::button('themeFontAdd', [
			'href' => Helper::baseUrl() . $this->getUrl(0) . '/fontAdd',
			'value' => Template::ico('plus'),
			'class' => 'buttonGreen',
			'help' => 'Ajouter une fonte'
		]); ?>
	</div>
</div>
<?php if (theme::$fontsDetail) : ?>
	<?php echo Template::table([2, 2, 3, 2, 1, 1, 1], theme::$fontsDetail, ['FontId', 'Nom', 'Famille', 'Affectation', 'Origine', '', ''], ['id' => 'dataTables']); ?>
<?php else : ?>
	<?php echo Template::speech('Aucune fonte !'); ?>
<?php endif; ?>