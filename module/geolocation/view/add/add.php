<?php echo template::formOpen('locationAddForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('locationAddBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('locationAddSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo helper::translate('Paramètres'); ?></h4>
			<div class="row">
				<div class="col12">
					<?php echo template::text('locationAddName', [
						'label' => 'Nom'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::text('locationAddLat', [
						'label' => 'Latitude',
						'help' => 'Coordonnée décimale'

					]); ?>
				</div>
				<div class="col6">
					<?php echo template::text('locationAddLong', [
						'label' => 'Longitude',
						'help' => 'Coordonnée décimale'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::textarea('locationAddDescription', [
						'label' => 'Description',
						'class' => 'editorWysiwyg'
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>