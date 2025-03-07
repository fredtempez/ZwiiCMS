<?php echo template::formOpen('newsAddForm'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('newsAddBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col2 offset7">
			<?php echo template::button('newsAddDraft', [
				'uniqueSubmission' => true,
				'value' => helper::translate('Brouillon')
			]); ?>
			<?php echo template::hidden('newsAddState', [
				'value' => true
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('newsAddPublish', [
				'value' => 'Publier',
				'uniqueSubmission' => true
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Informations générales');?></h4>
				<?php echo template::text('newsAddTitle', [
					'label' => 'Titre'
				]); ?>
			</div>
		</div>
	</div>
	<?php echo template::textarea('newsAddContent', [
		'class' => 'editorWysiwyg'
	]); ?>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Options de publication');?></h4>
				<div class="row">
					<div class="col4">
						<?php echo template::select('newsAddUserId', news::$users, [
							'label' => 'Auteur',
							'selected' => $this->getUser('id')
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::date('newsAddPublishedOn', [
							'help' => 'La news est consultable à partir du moment où la date de publication est passée.',
							'label' => 'Date de publication',
							'type' => 'datetime-local',
							'value' => floor(strtotime('now') / 60) * 60
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::date('newsAddPublishedOff', [
							'help' => 'La news est consultable Jusqu\'à cette date si elle est spécifiée. Pour annuler la date de dépublication, sélectionnez une date antérieure à la publication.',
							'label' => 'Date de dépublication',
							'type' => 'datetime-local',
							'value' => ''
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>