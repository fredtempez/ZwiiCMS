<?php echo template::formOpen('newsEditForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('newsEditBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col2 offset6">
			<?php echo template::button('newsEditDraft', [
				'uniqueSubmission' => true,
				'value' => helper::translate('Brouillon')
			]); ?>
			<?php echo template::hidden('newsEditState', [
				'value' => true
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('newsEditSubmit', [
				'value' => 'Publier',
				'uniqueSubmission' => true
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Informations générales');?></h4>
				<?php echo template::text('newsEditTitle', [
					'label' => 'Titre',
					'value' => $this->getData(['module', $this->getUrl(0),'posts', $this->getUrl(2), 'title'])
				]); ?>
			</div>
		</div>
	</div>
	<?php echo template::textarea('newsEditContent', [
		'class' => 'editorWysiwyg',
		'value' => $this->getData(['module', $this->getUrl(0),'posts', $this->getUrl(2), 'content'])
	]); ?>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Options de publication');?></h4>
				<div class="row">
					<div class="col4">
						<?php echo template::select('newsEditUserId', $module::$users, [
							'label' => 'Auteur',
							'selected' => $this->getUser('id')
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::date('newsEditPublishedOn', [
							'help' => 'La news est consultable à partir du moment où la date de publication est passée.',
							'label' => 'Date de publication',
							'type' => 'datetime-local',
							'value' => $this->getData(['module', $this->getUrl(0),'posts', $this->getUrl(2), 'publishedOn'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::date('newsEditPublishedOff', [
							'help' => 'La news est consultable Jusqu\'à cette date si elle est spécifiée. Pour annuler la date de dépublication, sélectionnez une date antérieure à la publication.',
							'label' => 'Date de dépublication',
							'type' => 'datetime-local',
							'value' => $this->getData(['module', $this->getUrl(0),'posts', $this->getUrl(2), 'publishedOff'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>