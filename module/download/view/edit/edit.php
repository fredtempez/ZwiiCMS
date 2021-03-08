<?php echo template::formOpen('downloadEditForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('downloadEditBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col3 offset5">
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
				<h4>Informations générales</h4>

				<div class="row">
					<div class="col8">
						<?php echo template::text('downloadEditTitle', [
							'label' => 'Titre',
							'value' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'title'])
						]); ?>
					</div>
					<div class="col2">
						<?php echo template::text('downloadEditFileVersion', [
							'label' => 'Version',
							'value' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'fileVersion'])
						]); ?>
					</div>
					<div class="col2">
						<?php echo template::date('downloadEditFileDate', [
							'label' => 'Date',
							'value' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'fileDate'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::file('downloadEditFile', [
							'label' => 'Archive du fichier',
							'value' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'file'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::file('downloadEditPicture', [
							'label' => 'Capture d\'écran',
							'type' => 1,
							'value' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'picture'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo template::textarea('downloadEditContent', [
		'class' => 'editorWysiwyg',
		'value' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'content'])
	]); ?>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Options de publication</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::select('downloadEditUserId', $module::$users, [
							'label' => 'Auteur',
							'selected' => $this->getUser('id'),
							'disabled' => $this->getUser('group') !== self::GROUP_ADMIN ? true : false
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::date('downloadEditPublishedOn', [
							'help' => 'L\'item n\'est visible qu\'après la date de publication prévue.',
							'label' => 'Date de publication',
							'value' => time()
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('downloadEditConsent', $module::$itemConsent  , [
							'label' => 'Edition /  Suppression',
							'selected' => is_numeric($this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'editConsent'])) ? $module::EDIT_GROUP : $this->getData(['module', $this->getUrl(0), 'items',  $this->getUrl(2), 'editConsent']),
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
							'checked' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'commentClose'])
						]); ?>
					</div>
					<div class="col4 commentOptionsWrapper ">
						<?php echo template::checkbox('downloadEditCommentApproved', true, 'Approbation par un modérateur', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'items',  $this->getUrl(2), 'commentApproved']),
							''
						]); ?>
					</div>
					<div class="col4 commentOptionsWrapper">
						<?php echo template::select('downloadEditCommentMaxlength', $module::$commentLength,[
							'help' => 'Choix du nombre maximum de caractères pour chaque commentaire de l\'item, mise en forme html comprise.',
							'label' => 'Caractères par commentaire',
							'selected' => $this->getData(['module', $this->getUrl(0), 'items',  $this->getUrl(2), 'commentMaxlength'])
						]); ?>
					</div>

				</div>
				<div class="row">
					<div class="col3 commentOptionsWrapper offset2">
						<?php echo template::checkbox('downloadEditCommentNotification', true, 'Notification par email', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'items',  $this->getUrl(2), 'commentNotification']),
						]); ?>
					</div>
					<div class="col4 commentOptionsWrapper">
						<?php echo template::select('downloadEditCommentGroupNotification', $module::$groupNews, [
							'selected' => $this->getData(['module', $this->getUrl(0), 'items',  $this->getUrl(2), 'commentGroupNotification']),
							'help' => 'Editeurs = éditeurs + administrateurs<br/> Membres = membres + éditeurs + administrateurs'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
