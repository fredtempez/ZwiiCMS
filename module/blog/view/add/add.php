<?php echo template::formOpen('blogAddForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('blogAddBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col3 offset5">
			<?php echo template::button('blogAddDraft', [
				'uniqueSubmission' => true,
				'value' => 'Enregistrer en brouillon'
			]); ?>
			<?php echo template::hidden('blogAddState', [
				'value' => true
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('blogAddPublish', [
				'value' => 'Publier'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Informations générales</h4>
				<div class="row">
					<div class="col12">
						<?php echo template::text('blogAddTitle', [
							'label' => 'Titre'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4">
						<?php echo template::file('blogAddPicture', [
							'help' => 'Taille optimale de l\'image de couverture : ' . ((int) substr($this->getData(['theme', 'site', 'width']), 0, -2) - (20 * 2)) . ' x 350 pixels.',
							'label' => 'Image de couverture',
							'type' => 1
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('blogAddPictureSize', $module::$pictureSizes, [
							'label' => 'Largeur de l\'image'
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('blogAddPicturePosition', $module::$picturePositions, [
							'label' => 'Position',
							'help' => 'Le texte de l\'article est adapté autour de l\'image'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col12">
					<?php echo template::checkbox('blogAddHidePicture', true, 'Masquer l\'image dans l\'article', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'hidePicture'])
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo template::textarea('blogAddContent', [
		'class' => 'editorWysiwyg'
	]); ?>
	<div class="row">
		<div class="col6">
			<div class="block">
				<h4>Options de publication</h4>
				<?php echo template::select('blogAddUserId', $module::$users, [
					'label' => 'Auteur',
					'selected' => $this->getUser('id')
				]); ?>
				<?php echo template::date('blogAddPublishedOn', [
					'help' => 'L\'article n\'est visible qu\'après la date de publication prévue.',
					'label' => 'Date de publication',
					'value' => time()
				]); ?>
			</div>
		</div>
		<div class="col6">
			<div class="block">
				<h4>Options avancées</h4>
				<?php echo template::checkbox('blogAddCloseComment', true, 'Fermer les commentaires' ); ?>
				<?php echo template::checkbox('blogAddMailNotification', true, 'Notifier le commentaire aux groupes à partir de :', [
					'help' => 'Editeurs = éditeurs + administrateurs<br/> Membres = membres + éditeurs + administrateurs'
				]); ?>
				<?php echo template::select('blogAddGroupNotification', $module::$groupNews, [
						'label' => ''
				]); ?>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>