<div class="row">
	<div class="col2">
		<?php echo template::button('themeFontBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'theme',
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('pageEditHelp', [
			'href' => 'https://doc.zwiicms.fr/fontes',
			'target' => '_blank',
			'ico' => 'help',
			'value' => 'Aide',
			'class' => 'buttonHelp'
		]); ?>
	</div>
	<div class="col2 offset6">
		<?php echo template::button('themeFontAdd', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/fontAdd',
			'ico' => 'plus',
			'value' => 'Fonte'
		]); ?>
	</div>
</div>
<?php if($module::$fontsDetail): ?>
  <?php echo template::table([2, 2, 3, 3, 1, 1], $module::$fontsDetail, ['FontId', 'Nom', 'Famille', 'Affectation', 'Origine', '']); ?>
<?php else: ?>
  <?php echo template::speech('Aucune fonte !'); ?>
<?php endif; ?>