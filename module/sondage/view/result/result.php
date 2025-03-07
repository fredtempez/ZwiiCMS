<div class="row">
	<div class="col2">
		<?php echo template::button('formDataBack', [
			'class' => '',
			'href' => helper::baseUrl() . $this->getUrl(0),
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
</div>
<?php if($module::$data): ?>
		<?php echo template::table([11, 1], $module::$data, ['Réponses', '']); ?>
		<?php echo $module::$pagination; ?>
	<?php else: ?>
		<?php echo template::speech('Aucune réponse !'); ?>
	<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>