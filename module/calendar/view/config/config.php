<?php echo template::formOpen('calendarConfigForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('calendarConfigBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1 offset9">
		<?php echo template::button('calendarTheme', [
			'href' =>  helper::baseUrl() . 'page/cssEditor/' . $this->getUrl(0),
			'value' => template::ico('brush')
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('calendarAdd', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/add',
			'value' => template::ico('plus'),
			'class' => 'buttonGreen'
		]); ?>
	</div>
</div>
<?php echo template::formClose(); ?>
<div class="row">
	<div class="col12">
		<?php if ($module::$calendars): ?>
			<?php echo template::table([6, 3, 3, 1, 1], $module::$calendars, ['Titre', 'Date', 'Horaire', '', '']); ?>
		<?php else: ?>
			<?php echo template::speech('Aucun événement'); ?>
		<?php endif; ?>
	</div>
	<div class="moduleVersion">Version n°
		<?php echo $module::VERSION; ?>
	</div>
</div>