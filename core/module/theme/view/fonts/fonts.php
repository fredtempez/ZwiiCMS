<div class="row">
	<div class="col2">
		<?php echo template::button('themeFontBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'theme',
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2 offset8">
		<?php echo template::button('themeFontAdd', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/fontAdd',
			'ico' => 'plus',
			'value' => 'Fonte'
		]); ?>
	</div>
</div>
<?php if($module::$fontsList): ?>
  <?php echo template::table([4, 4, 3, 4, 1], $module::$fontsList, ['Family Name', 'Font Id', '', 'Accès', 'Effacer']); ?>
<?php else: ?>
  <?php echo template::speech('Aucune fonte !'); ?>
<?php endif; ?>