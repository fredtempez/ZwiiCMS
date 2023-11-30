<?php echo template::formOpen('userEditForm'); ?>
<div class="row">
	<div class="col1">
		<?php if ($this->getUser('group') === self::GROUP_ADMIN): ?>
			<?php echo template::button('userEditBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'user',
				'value' => template::ico('left')
			]); ?>
		<?php else: ?>
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
			<h4>
				<?php echo helper::translate('Identité'); ?>
			</h4>
			<div class="row">
				<div class="col6">
					<?php echo template::text('userEditFirstname', [
						'autocomplete' => 'off',
						'disabled' => $this->getUser('group') > self::GROUP_EDITOR ? false : true,
						'label' => 'Prénom',
						'value' => $this->getData(['user', $this->getUrl(2), 'firstname'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::text('userEditLastname', [
						'autocomplete' => 'off',
						'disabled' => $this->getUser('group') > self::GROUP_EDITOR ? false : true,
						'label' => 'Nom',
						'value' => $this->getData(['user', $this->getUrl(2), 'lastname'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::text('userEditPseudo', [
						'autocomplete' => 'off',
						'label' => 'Pseudo',
						'value' => $this->getData(['user', $this->getUrl(2), 'pseudo'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::select('userEditSignature', $module::$signature, [
						'label' => 'Signature',
						'selected' => $this->getData(['user', $this->getUrl(2), 'signature'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::mail('userEditMail', [
						'autocomplete' => 'off',
						'label' => 'Adresse électronique',
						'value' => $this->getData(['user', $this->getUrl(2), 'mail'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::select('userEditLanguage', $module::$languagesInstalled, [
						'label' => 'Langue',
						'selected' => $this->getData(['user', $this->getUrl(2), 'language'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
				<?php echo template::text('userEditTags', [
						'label' => 'Etiquettes',
						'value' => $this->getData(['user', $this->getUrl(2), 'tags']),
						'help' => 'Le séparateur d\'étiquettes est l\'espace'
					]); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col6">
		<div class="block">
			<h4>
				<?php echo helper::translate('Authentification'); ?>
			</h4>
			<?php echo template::text('userEditId', [
				'autocomplete' => 'off',
				'help' => 'L\'identifiant est défini lors de la création du compte, il ne peut pas être modifié.',
				'label' => 'Identifiant',
				'readonly' => true,
				'value' => $this->getUrl(2)
			]); ?>
			<?php
			// Les admins ont le pouvoir de forcer le changement de mot de passe
			if ($this->getUser('group') < self::GROUP_ADMIN): ?>
				<?php echo template::password('userEditOldPassword', [
					'autocomplete' => 'new-password',
					// remplace 'off' pour éviter le pré remplissage auto
					'label' => 'Ancien mot de passe',
				]); ?>
			<?php endif; ?>
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
	<div class="col6 offset3">
		<div class="block">
			<h4>
				<?php echo helper::translate('Permissions'); ?>
			</h4>
			<div class="row">
				<div class="col12">
					<?php if ($this->getUser('group') === self::GROUP_ADMIN): ?>
						<?php echo template::select('userEditGroup', self::$groupEdits, [
							'disabled' => ($this->getUrl(2) === $this->getUser('id')),
							'help' => ($this->getUrl(2) === $this->getUser('id') ? 'Impossible de modifier votre propre groupe.' : ''),
							'label' => 'Groupe',
							'selected' => $this->getData(['user', $this->getUrl(2), 'group']),
						]); ?>
					<?php else: ?>
						<?php echo template::hidden('userEditGroup', [
							'value' => $this->getData(['user', $this->getUrl(2), 'group'])
						]); ?>
					<?php endif; ?>
				</div>
				<div class="col12">
					<div class="userEditGroupProfil" id="userEditGroupProfil<?php echo self::GROUP_MEMBER; ?>">
						<?php echo template::select('userEditProfil' . self::GROUP_MEMBER, $module::$userProfils[self::GROUP_MEMBER], [
							'label' => 'Profil',
							'selected' => $this->getData(['user', $this->getUrl(2), 'profil']),
							'disabled' => $this->getUser('group') !== self::GROUP_ADMIN,
						]); ?>
					</div>
					<div class="userEditGroupProfil" id="userEditGroupProfil<?php echo self::GROUP_EDITOR; ?>">
						<?php echo template::select('userEditProfil' . self::GROUP_EDITOR, $module::$userProfils[self::GROUP_EDITOR], [
							'label' => 'Profil',
							'selected' => $this->getData(['user', $this->getUrl(2), 'profil']),
							'disabled' => $this->getUser('group') !== self::GROUP_ADMIN,
						]); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div id="userCommentProfil<?php echo self::GROUP_MEMBER; ?>" class="col12  userCommentProfil">
					<?php echo template::textarea('userEditProfilComment' . self::GROUP_MEMBER, [
						'label' => 'Commentaire',
						'value' => implode("\n", $module::$userProfilsComments[self::GROUP_MEMBER]),
						'disabled' => true,

					]);
					?>
				</div>
				<div id="userCommentProfil<?php echo self::GROUP_EDITOR; ?>" class="col12  userCommentProfil">
					<?php echo template::textarea('userEditProfilComment' . self::GROUP_EDITOR, [
						'label' => 'Commentaire',
						'value' => implode("\n", $module::$userProfilsComments[self::GROUP_EDITOR]),
						'disabled' => true,

					]);
					?>
				</div>
				<div id="userCommentProfil<?php echo self::GROUP_ADMIN; ?>" class="col12  userCommentProfil">
					<?php echo template::textarea('userEditProfilComment' . self::GROUP_ADMIN, [
						'label' => 'Commentaire',
						'value' => implode("\n", $module::$userProfilsComments[self::GROUP_ADMIN]),
						'disabled' => true,
					]);
					?>
				</div>

			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>