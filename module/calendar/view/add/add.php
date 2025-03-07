<?php echo template::formOpen('calendarAddForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('calendarAddBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('calendarAddSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo helper::translate('Paramètres'); ?></h4>
			<div class="row">
				<div class="col4">
					<?php echo template::text('calendarAddEventName', [
						'label' => 'Titre',
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::date('calendarAddDate', [
						'label' => 'Date',
						'type' => 'date',
					]); ?>
				</div>
				<div class="col4">
				<?php echo template::text('calendarAddDateColor', [
					'class' => 'colorPicker',
					'help' => 'A ne paramétrer que sur un seul événement du jour. Le curseur horizontal règle le niveau de transparence.',
					'label' => 'Couleur de la date du jour',
				]); ?>
			</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::checkbox('calendarAddAllDay', true, 'Toute la journée', [
						'checked' => false
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::date('calendarAddTime', [
						'label' => 'Horaire',
						'type' => 'time',
						'help' => 'Ne pas indiquer d\'horaire quand l\'événement est sur la journée entière.',
					]); ?>
				</div>
				<div class="col4">
				<?php echo template::select('calendarAddDateClassName', $module::$classes, [
					'label' => 'Classe CSS',
					'help' => 'La feuille de style de la page contient ces classes.',
				]); ?>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>