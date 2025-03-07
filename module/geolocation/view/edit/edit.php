<?php echo template::formOpen('locationEditForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('locationEditBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('locationEditSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo helper::translate('Paramètres'); ?></h4>
			<div class="row">
				<div class="col12">
					<?php echo template::text('locationEditName', [
						'label' => 'Nom',
						'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'name'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::text('locationEditLat', [
						'label' => 'Latitude',
						'help' => 'Coordonnée décimale',
						'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'lat'])

					]); ?>
				</div>
				<div class="col6">
					<?php echo template::text('locationEditLong', [
						'label' => 'Longitude',
						'help' => 'Coordonnée décimale',
						'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'long'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::textarea('locationEditDescription', [
						'label' => 'Description',
						'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'description']),
						'class' => 'editorWysiwyg'
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>