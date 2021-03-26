<?php echo template::formOpen('downloadAddForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('downloadAddBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col3 offset5">
			<?php echo template::button('downloadAddDraft', [
				'uniqueSubmission' => true,
				'value' => 'Enregistrer en brouillon'
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
				<h4>Informations générales</h4>
				<div class="row">
					<div class="col8">
						<?php echo template::text('downloadAddTitle', [
							'label' => 'Titre'
						]); ?>
					</div>
					<div class="col2">
						<?php echo template::text('downloadAddFileVersion', [
							'label' => 'Version'
						]); ?>
					</div>
					<div class="col2">
						<?php echo template::date('downloadAddFileDate', [
							'label' => 'Date'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::file('downloadAddFile', [
							'label' => 'Archive du fichier'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::file('downloadAddPicture', [
							'label' => 'Capture d\'écran',
							'type' => 1
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::text('downloadAddFileAuthor', [
							'label' => 'Auteur',
							'value' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'fileAuthor'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::select('downloadAddFileLicense', $module::$itemLicense, [
							'label' => 'Licence',
							'selected' => $this->getData(['module', $this->getUrl(0), 'items', $this->getUrl(2), 'fileLicense'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo template::textarea('downloadAddContent', [
		'class' => 'editorWysiwyg'
	]); ?>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Options de publication</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::select('downloadAddUserId', $module::$users, [
							'label' => 'Auteur',
							'selected' => $this->getUser('id'),
							'disabled' => $this->getUser('group') !== self::GROUP_ADMIN ? true : false
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::date('downloadAddPublishedOn', [
							'help' => 'L\'item n\'est visible qu\'après la date de publication prévue.',
							'label' => 'Date de publication',
							'value' => time()
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('downloadAddConsent', $module::$itemConsent  , [
							'label' => 'Edition /  Suppression',
							'selected' => $module::EDIT_ALL,
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
						<?php echo template::checkbox('downloadAddCommentClose', true, 'Fermer les commentaires'); ?>
					</div>
					<div class="col4 commentOptionsWrapper ">
						<?php echo template::checkbox('downloadAddCommentApproved', true, 'Approbation par un modérateur'); ?>
					</div>
					<div class="col4 commentOptionsWrapper">
						<?php echo template::select('downloadAddCommentMaxlength', $module::$commentLength,[
							'help' => 'Choix du nombre maximum de caractères pour chaque commentaire de l\'item, mise en forme html comprise.',
							'label' => 'Caractères par commentaire'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3 commentOptionsWrapper offset2">
						<?php echo template::checkbox('downloadAddCommentNotification', true, 'Notification par email'); ?>
					</div>
					<div class="col4 commentOptionsWrapper">
						<?php echo template::select('downloadAddCommentGroupNotification', $module::$groupNews); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
