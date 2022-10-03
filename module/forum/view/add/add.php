<?php echo template::formOpen('blogAddForm'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('blogAddBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col2 offset7">
			<?php echo template::button('blogAddDraft', [
				'uniqueSubmission' => true,
				'value' => 'Brouillon'
			]); ?>
			<?php echo template::hidden('blogAddState', [
				'value' => true
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('blogAddPublish', [
				'value' => 'Publier',
				'uniqueSubmission' => true
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
					<div class="col12">
						<?php echo template::text('blogAddPermalink', [
							'label' => 'Permalink'
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
		<div class="col12">
			<div class="block">
				<h4>Options de publication</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::select('blogAddUserId', $module::$users, [
							'label' => 'Auteur',
							'selected' => $this->getUser('id'),
							'disabled' => $this->getUser('group') !== self::GROUP_ADMIN ? true : false
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::date('blogAddPublishedOn', [
							'help' => 'L\'sujet n\'est visible qu\'après la date de publication prévue.',
							'label' => 'Date de publication',
							'value' => time()
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('blogAddConsent', $module::$sujetConsent  , [
							'label' => 'Edition - Suppression',
							'selected' => $module::EDIT_ALL,
							'help' => 'Les utilisateurs des groupes supérieurs accèdent à l\'sujet sans restriction'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Réponses</h4>
				<div class="row">
					<div class="col4 ">
						<?php echo template::checkbox('blogAddCommentClose', true, 'Fermer les réponses'); ?>
					</div>
					<div class="col4 commentOptionsWrapper ">
						<?php echo template::checkbox('blogAddCommentApproved', true, 'Approbation par un modérateur'); ?>
					</div>
					<div class="col4 commentOptionsWrapper">
						<?php echo template::select('blogAddCommentMaxlength', $module::$commentLength,[
							'help' => 'Choix du nombre maximum de caractères pour chaque Réponse de l\'sujet, mise en forme html comprise.',
							'label' => 'Caractères par Réponse'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3 commentOptionsWrapper offset2">
						<?php echo template::checkbox('blogAddCommentNotification', true, 'Notification par email'); ?>
					</div>
					<div class="col4 commentOptionsWrapper">
						<?php echo template::select('blogAddCommentGroupNotification', $module::$groupNews); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
