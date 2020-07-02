<?php echo template::formOpen('blogEditForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('blogEditBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col3 offset5">
			<?php echo template::button('blogEditDraft', [
				'uniqueSubmission' => true,
				'value' => 'Enregistrer en brouillon'
			]); ?>
			<?php echo template::hidden('blogEditState', [
				'value' => true
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('blogEditSubmit', [
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
						<?php echo template::text('blogEditTitle', [
							'label' => 'Titre',
							'value' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'title'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4">
						<?php echo template::file('blogEditPicture', [
							'help' => 'Taille optimale de l\'image de couverture : ' . ((int) substr($this->getData(['theme', 'site', 'width']), 0, -2) - (20 * 2)) . ' x 350 pixels.',
							'label' => 'Image de couverture',
							'type' => 1,
							'value' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'picture'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('blogEditPictureSize', $module::$pictureSizes, [
							'label' => 'Largeur de l\'image',
							'selected' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'pictureSize'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('blogEditPicturePosition', $module::$picturePositions, [
							'label' => 'Position',
							'selected' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'picturePosition']),
							'help' => 'Le texte de l\'article est adapté autour de l\'image'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('blogEditHidePicture', true, 'Masquer l\'image dans l\'article', [
							'checked' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'hidePicture'])
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo template::textarea('blogEditContent', [
		'class' => 'editorWysiwyg',
		'value' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'content'])
	]); ?>
	<div class="row">
		<div class="col6">
			<div class="block">
				<h4>Options de publication</h4>
				<?php echo template::select('blogEditUserId', $module::$users, [
					'label' => 'Auteur',
					'selected' => $this->getUser('id')
				]); ?>
				<?php echo template::date('blogEditPublishedOn', [
					'help' => 'L\'article n\'est visible qu\'après la date de publication prévue.',
					'label' => 'Date de publication',
					'value' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'publishedOn'])
				]); ?>
			</div>
		</div>
		<div class="col6">
			<div class="block">
				<h4>Options avancées</h4>
				<?php echo template::checkbox('blogEditCloseComment', true, 'Fermer les commentaires', [
					'checked' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'closeComment'])
				]); ?>
				<?php echo template::checkbox('blogEditMailNotification', true, 'Notifier le commentaire aux groupes à partir de :', [
					'checked' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'mailNotification']),
					'help' => 'Editeurs = éditeurs + administrateurs<br/> Membres = membres + éditeurs + administrateurs'

				]); ?>
				<?php echo template::select('blogEditGroupNotification', $module::$groupNews, [
					'label' => '',
					'selected' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'groupNotification'])
				]); ?>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>