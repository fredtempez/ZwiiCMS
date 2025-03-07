<?php echo template::formOpen('downloadAddForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('downloadAddBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset7">
		<?php echo template::button('downloadAddDraft', [
			'uniqueSubmission' => true,
			'value' => 'Brouillon'
		]); ?>
		<?php echo template::hidden('downloadAddState', [
			'value' => true
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::submit('downloadAddPublish', [
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
					<?php echo template::text('downloadAddTitle', [
						'label' => 'Titre'
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::text('downloadAddId', [
						'label' => 'Id Interne',
					]); ?>
				</div>>
				<div class="col3">
					<?php echo template::text('downloadAddVersion', [
						'label' => 'Version'
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::date('downloadAddversionDate', [
						'label' => 'Publiée le',
						'type' => 'datetime-local'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php echo template::text('downloadAddAuthor', [
						'label' => 'Auteur'
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::select('downloadAddLicense', download::$licenses, [
						'label' => 'Licence'
					]); ?>
				</div>
				<div class="col3">
					<?php if (download::$categories) {
						echo template::select('downloadAddCategorie', download::$categories, [
							'label' => 'Catégorie'
						]);
					} else {
						echo template::select('downloadAddCategorie', ['' => ''], [
							'label' => 'Pas de catégorie',
							'disabled' => true
						]);
					}
					?>
				</div>
				<div class="col3">
					<?php echo template::file('downloadAddThumb', [
						'label' => 'Capture d\'écran',
						'language' => $this->getData(['user', $this->getUser('id'), 'language']),
						'type' => 1,
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php echo template::select('downloadAddRessourceType', download::$ressourceType, [
						'label' => 'Type de ressource',
						'selected' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'ressourceType'])
					]); ?>
				</div>
				<div class="col9">
					<div class="row">
						<div class="col12">
							<?php echo template::file('downloadAddFile', [
								'label' => 'Fichier',
								'language' => $this->getData(['user', $this->getUser('id'), 'language']),
								'value' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'file']),
								'folder' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'file']) ? dirname($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'file'])) : ''
							]); ?>
						</div>
						<div class="col12">
							<?php echo template::text('downloadAddUrl', [
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
	<div class="row">
		<div class="col12">
			<?php echo template::textarea('downloadAddContent', [
				'class' => 'editorWysiwyg'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Options de publication</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::select('downloadAddUserId', download::$users, [
							'label' => 'Auteur',
							'selected' => $this->getUser('id'),
							'disabled' => $this->getUser('role') !== self::ROLE_ADMIN ? true : false
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::date('downloadAddPublishedOn', [
							'help' => 'L\'item n\'est visible qu\'après la date de publication prévue.',
							'label' => 'Date de publication',
							'value' => time(),
							'type' => 'datetime-local'
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('downloadAddConsent', download::$itemConsent, [
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
						<?php echo template::checkbox('downloadAddCommentClose', true, 'Fermer les commentaires', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'commentClose'])
						]); ?>
					</div>
					<div class="col4 commentOptionsWrapper ">
						<?php echo template::checkbox('downloadAddCommentApproved', true, 'Approbation par un modérateur', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'commentApproved']),
							''
						]); ?>
					</div>
					<div class="col4 commentOptionsWrapper">
						<?php echo template::select('downloadAddCommentMaxlength', download::$commentLength, [
							'help' => 'Choix du nombre maximum de caractères pour chaque commentaire de l\'item, mise en forme html comprise.',
							'label' => 'Caractères par commentaire',
							'selected' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'commentMaxlength'])
						]); ?>
					</div>

				</div>
				<div class="row">
					<div class="col3 commentOptionsWrapper offset2">
						<?php echo template::checkbox('downloadAddCommentNotification', true, 'Notification par email', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'commentNotification']),
						]); ?>
					</div>
					<div class="col4 commentOptionsWrapper">
						<?php echo template::select('downloadAddCommentGroupNotification', self::$roleNews, [
							'selected' => $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'commentGroupNotification']),
							'help' => 'Editeurs = éditeurs + administrateurs<br/> Membres = membres + éditeurs + administrateurs'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>