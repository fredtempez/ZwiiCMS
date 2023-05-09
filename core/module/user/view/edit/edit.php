<?php echo template::formOpen('userEditForm'); ?>
<div class="row">
	<div class="col1">
		<?php if ($this->getUser('group') === self::GROUP_ADMIN) : ?>
			<?php echo template::button('userEditBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'user',
				'value' => template::ico('left')
			]); ?>
		<?php else : ?>
			<?php echo template::button('userEditBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl(false),
				'value' => template::ico('home')
			]); ?>
		<?php endif; ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('userEditSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col6">
		<div class="block">
			<h4><?php echo helper::translate('Identité'); ?>
			</h4>
			<div class="row">
				<div class="col6">
					<?php echo template::text('userEditFirstname', [
						'autocomplete' => 'off',
						'disabled' => $this->getUser('group') > 2 ? false : true,
						'label' => 'Prénom',
						'value' => $this->getData(['user', $this->getUrl(2), 'firstname'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::text('userEditLastname', [
						'autocomplete' => 'off',
						'disabled' => $this->getUser('group') > 2 ? false : true,
						'label' => 'Nom',
						'value' => $this->getData(['user', $this->getUrl(2), 'lastname'])
					]); ?>
				</div>
			</div>
			<?php echo template::text('userEditPseudo', [
				'autocomplete' => 'off',
				'label' => 'Pseudo',
				'value' => $this->getData(['user', $this->getUrl(2), 'pseudo'])
			]); ?>
			<?php echo template::select('userEditSignature', $module::$signature, [
				'label' => 'Signature',
				'selected' => $this->getData(['user', $this->getUrl(2), 'signature'])
			]); ?>
			<?php echo template::mail('userEditMail', [
				'autocomplete' => 'off',
				'label' => 'Adresse électronique',
				'value' => $this->getData(['user', $this->getUrl(2), 'mail'])
			]); ?>
		</div>
	</div>
	<div class="col6">
		<div class="block">
			<h4><?php echo helper::translate('Authentification'); ?>
			</h4>
			<?php echo template::text('userEditId', [
				'autocomplete' => 'off',
				'help' => 'L\'identifiant est défini lors de la création du compte, il ne peut pas être modifié.',
				'label' => 'Identifiant',
				'readonly' => true,
				'value' => $this->getUrl(2)
			]); ?>
			<?php echo template::password('userEditOldPassword', [
				'autocomplete' => 'new-password', // remplace 'off' pour éviter le pré remplissage auto
				'label' => 'Ancien mot de passe'
			]); ?>
			<?php echo template::password('userEditNewPassword', [
				'autocomplete' => 'off',
				'label' => 'Nouveau mot de passe'
			]); ?>
			<?php echo template::password('userEditConfirmPassword', [
				'autocomplete' => 'off',
				'label' => 'Confirmation'
			]); ?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo helper::translate('Paramètres'); ?>
			</h4>
			<div class="row">
			<div class="col6">
				<?php echo template::select('userEditLanguage', $module::$languagesInstalled, [
					'label' =>  'Langues',
					'selected' => $this->getData(['user', $this->getUrl(2), 'language'])
				]); ?>
			</div>
			<div class="col6">
				<?php if ($this->getUser('group') === self::GROUP_ADMIN) : ?>
					<?php echo template::select('userEditGroup', self::$groupEdits, [
						'disabled' => ($this->getUrl(2) === $this->getUser('id')),
						'help' => ($this->getUrl(2) === $this->getUser('id') ? 'Impossible de modifier votre propre groupe.' : ''),
						'label' => 'Groupe',
						'selected' => $this->getData(['user', $this->getUrl(2), 'group'])
					]); ?>
					<div id="userEditMemberFiles" class="displayNone">
						<?php echo template::checkbox('userEditFiles', true, 'Partage de fichiers autorisé', [
							'checked' => $this->getData(['user', $this->getUrl(2), 'files']),
							'help' => 'Ce membre pourra téléverser ou télécharger des fichiers dans le dossier \'partage\' et ses sous-dossiers'
						]); ?>
					</div>
					<div id="userEditLabelAuth">
						<?php echo helper::translate('Permissions :'); ?>
					</div>
					<ul id="userEditGroupDescription<?php echo self::GROUP_MEMBER; ?>" class="userEditGroupDescription displayNone">
						<li><?php echo helper::translate('Accès aux pages privées'); ?></li>
					</ul>
					<ul id="userEditGroupDescription<?php echo self::GROUP_MODERATOR; ?>" class="userEditGroupDescription displayNone">
						<li><?php echo helper::translate('Accès aux pages privées'); ?></li>
						<li><?php echo helper::translate('Ajout - Édition - Suppression de pages'); ?></li>
						<li><?php echo helper::translate('Ajout - Édition  - Suppression de fichiers'); ?></li>
					</ul>
					<ul id="userEditGroupDescription<?php echo self::GROUP_ADMIN; ?>" class="userEditGroupDescription displayNone">
						<li><?php echo helper::translate('Administration complète du site'); ?></li>
					</ul>
				<?php endif; ?>
			</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>