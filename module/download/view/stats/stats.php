<?php echo template::formOpen('statsConfig'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('statsConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::button('statsConfigAdd', [
				'class' => 'statsDeleteAll buttonRed',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/statsDeleteAll' . '/' . $this->getUrl(2) . '/'. $_SESSION['csrf'] ,
				'ico' => 'cancel',
				'value' => 'Tout effacer'
			]); ?>
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