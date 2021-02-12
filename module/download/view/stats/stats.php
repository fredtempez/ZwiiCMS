<?php echo template::formOpen('statsConfig'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('statsConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0), 'items',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset6">
			<?php echo template::button('statsConfigAdd', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/add',
				'ico' => 'plus',
				'value' => 'Item'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('statsConfigSubmit'); ?>
		</div>
	</div>
<?php echo template::formClose(); ?>
<?php if($module::$items): ?>
	<?php echo template::table([6, 6], $module::$items, ['Date', 'Adresse IP']); ?>
	<?php echo $module::$pages; ?>
<?php else: ?>
	<?php echo template::speech('Aucun item.'); ?>
<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>