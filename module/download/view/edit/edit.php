<?php echo template::formOpen('downloadEditForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('downloadEditBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col3 offset6">
		<?php echo template::button('downloadEditDraft', [
			'uniqueSubmission' => true,
			'value' => 'Enregistrer en brouillon'
		]); ?>
		<?php echo template::hidden('downloadEditState', [
			'value' => true
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::submit('downloadEditSubmit', [
			'value' => 'Publier'
		]); ?>

	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Informations sur la ressource</h4>
			<div class="row">
				<div class="col3">
					<?php echo template::text('downloadEditTitle', [
						'label' => 'Titre',
						'value' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'title'])
					]); ?>
				</div>>
				<div class="col3">
					<?php echo template::text('downloadEditId', [
						'label' => 'Id Interne',
						'value' => empty($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'id']))
							? $this->getUrl(2)
							: $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'id']),
					]); ?>
					<?php echo template::hidden('downloadEditIdOld', [
						'value' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'id'])
					]);
					?>
				</div>>
				<div class="col3">
					<?php echo template::text('downloadEditVersion', [
						'label' => 'Version',
						'value' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'version'])
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::date('downloadEditversionDate', [
						'label' => 'Publiée le',
						'type' => 'datetime-local',
						'value' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'versionDate'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php echo template::text('downloadEditAuthor', [
						'label' => 'Auteur',
						'value' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'author'])
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::select('downloadEditLicense', download::$licenses, [
						'label' => 'Licence',
						'selected' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'license'])
					]); ?>
				</div>
				<div class="col3">
					<?php if (download::$categories) {
						echo template::select('downloadEditCategorie', download::$categories, [
							'label' => 'Catégorie',
							'selected' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'category'])
						]);
					} else {
						echo template::select('downloadEditCategorie', ['' => ''], [
							'label' => 'Pas de catégorie',
							'disabled' => true
						]);
					}
					?>
				</div>
				<div class="col3">
					<?php echo template::file('downloadEditThumb', [
						'label' => 'Capture d\'écran',
						'language' => $this->getData(['user', $this->getUser('id'), 'language']),
						'type' => 1,
						'value' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'thumb']),
						'folder' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'thumb']) ? dirname($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'thumb'])) : ''
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php echo template::select('downloadEditRessourceType', download::$ressourceType, [
						'label' => 'Type de ressource',
						'selected' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'ressourceType'])
					]); ?>
				</div>
				<div class="col9">
					<div class="row">
						<div class="col12">
							<?php echo template::file('downloadEditFile', [
								'label' => 'Fichier',
								'language' => $this->getData(['user', $this->getUser('id'), 'language']),
								'value' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'file']),
								'folder' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'file']) ? dirname($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'file'])) : ''
							]); ?>
						</div>
						<div class="col12">
							<?php echo template::text('downloadEditUrl', [
								'label' => 'URL',
								'value' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'url']),
								'placeholder' => 'https://'
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<?php echo template::textarea('downloadEditContent', [
			'class' => 'editorWysiwyg',
			'value' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'content'])
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Options de publication</h4>
			<div class="row">
				<div class="col4">
					<?php echo template::select('downloadEditUserId', download::$users, [
						'label' => 'Auteur',
						'selected' => $this->getUser('id'),
						'disabled' => $this->getUser('role') !== self::ROLE_ADMIN ? true : false
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::date('downloadEditPublishedOn', [
						'help' => 'L\'item n\'est visible qu\'après la date de publication prévue.',
						'label' => 'Date de publication',
						'value' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'publishedOn']),
						'type' => 'datetime-local'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('downloadEditConsent', download::$itemConsent, [
						'label' => 'Edition - Suppression',
						'selected' => is_numeric($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'editConsent'])) ? $module::EDIT_ROLE : $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'editConsent']),
						'help' => 'Les utilisateurs des groupes supérieurs accèdent à l\'item sans restriction'
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Commentaires</h4>
			<div class="row">
				<div class="col4 ">
					<?php echo template::checkbox('downloadEditCommentClose', true, 'Fermer les commentaires', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'commentClose'])
					]); ?>
				</div>
				<div class="col4 commentOptionsWrapper ">
					<?php echo template::checkbox('downloadEditCommentApproved', true, 'Approbation par un modérateur', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'commentApproved']),
						''
					]); ?>
				</div>
				<div class="col4 commentOptionsWrapper">
					<?php echo template::select('downloadEditCommentMaxlength', download::$commentLength, [
						'help' => 'Choix du nombre maximum de caractères pour chaque commentaire de l\'item, mise en forme html comprise.',
						'label' => 'Caractères par commentaire',
						'selected' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'commentMaxlength'])
					]); ?>
				</div>

			</div>
			<div class="row">
				<div class="col3 commentOptionsWrapper offset2">
					<?php echo template::checkbox('downloadEditCommentNotification', true, 'Notification par email', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'commentNotification']),
					]); ?>
				</div>
				<div class="col4 commentOptionsWrapper">
					<?php echo template::select('downloadEditCommentGroupNotification', self::$roleNews, [
						'selected' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'commentGroupNotification']),
						'help' => 'Editeurs = éditeurs + administrateurs<br/> Membres = membres + éditeurs + administrateurs'
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>