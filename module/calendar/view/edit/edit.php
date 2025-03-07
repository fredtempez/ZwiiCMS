<?php echo template::formOpen('calendarEditForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('calendarEditBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('calendarEditSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo helper::translate('Paramètres'); ?></h4>
			<div class="row">
				<div class="col4">
					<?php echo template::text('calendarEditEventName', [
						'label' => 'Titre',
						'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'eventName'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::date('calendarEditDate', [
						'label' => 'Date',
						'type' => 'date',
						'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'date'])
					]); ?>
				</div>
				<div class="col4">
				<?php echo template::text('calendarEditDateColor', [
					'class' => 'colorPicker',
					'help' => 'A ne paramétrer que sur un seul événement du jour. Le curseur horizontal règle le niveau de transparence.',
					'label' => 'Couleur de la date du jour',
					'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'dateColor'])
				]); ?>
			</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::checkbox('calendarEditAllDay', true, 'Toute la journée', [
						'checked' => false
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::date('calendarEditTime', [
						'label' => 'Horaire',
						'type' => 'time',
						'help' => 'Ne pas indiquer d\'horaire quand l\'événement est sur la journée entière.',
						'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'time'])
					]); ?>
				</div>
				<div class="col4">
				<?php echo template::select('calendarEditDateClassName', $module::$classes, [
					'label' => 'Classe CSS',
					'help' => 'La feuille de style de la page contient ces classes.',
					'selected' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'className'])
				]); ?>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>