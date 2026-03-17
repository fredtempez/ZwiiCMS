<?php echo Template::formOpen('userEditForm'); ?>
<div class="row">
	<div class="col1">
		<?php if ($this->getUser('role') === self::ROLE_ADMIN): ?>
			<?php echo Template::button('userEditBack', [
				'class' => 'buttonGrey',
				'href' => Helper::baseUrl() . 'user',
				'value' => Template::ico('left')
			]); ?>
		<?php else: ?>
			<?php echo Template::button('userEditBack', [
				'class' => 'buttonGrey',
				'href' => Helper::baseUrl(false),
				'value' => Template::ico('home')
			]); ?>
		<?php endif; ?>
	</div>
	<div class="col2 offset9">
		<?php echo Template::submit('userEditSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col6">
		<div class="block">
			<h4>
				<?php echo Helper::translate('Identité'); ?>
			</h4>
			<div class="row">
				<div class="col6">
					<?php echo Template::text('userEditFirstname', [
						'autocomplete' => 'off',
						'disabled' => $this->getUser('role') > self::ROLE_EDITOR ? false : true,
						'label' => 'Prénom',
						'value' => $this->getData(['user', $this->getUrl(2), 'firstname'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::text('userEditLastname', [
						'autocomplete' => 'off',
						'disabled' => $this->getUser('role') > self::ROLE_EDITOR ? false : true,
						'label' => 'Nom',
						'value' => $this->getData(['user', $this->getUrl(2), 'lastname'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo Template::text('userEditPseudo', [
						'autocomplete' => 'off',
						'label' => 'Pseudo',
						'value' => $this->getData(['user', $this->getUrl(2), 'pseudo'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::select('userEditSignature', user::$signature, [
						'label' => 'Signature',
						'selected' => $this->getData(['user', $this->getUrl(2), 'signature'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo Template::mail('userEditMail', [
						'autocomplete' => 'off',
						'label' => 'Adresse électronique',
						'value' => $this->getData(['user', $this->getUrl(2), 'mail'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::select('userEditLanguage', user::$languagesInstalled, [
						'label' => 'Langue',
						'selected' => $this->getData(['user', $this->getUrl(2), 'language'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
				<?php echo Template::text('userEditTags', [
						'label' => 'Étiquettes',
						'disabled' => $this->getUser('role') > self::ROLE_EDITOR ? false : true,
						'value' => $this->getData(['user', $this->getUrl(2), 'tags']),
						'help' => 'Les étiquettes sont séparées par des espaces'
					]); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col6">
		<div class="block">
			<h4>
				<?php echo Helper::translate('Authentification'); ?>
			</h4>
			<?php echo Template::text('userEditId', [
				'autocomplete' => 'off',
				'help' => 'L\'identifiant est défini lors de la création du compte, il ne peut pas être modifié.',
				'label' => 'Identifiant',
				'readonly' => true,
				'value' => $this->getUrl(2)
			]); ?>
			<?php
			// Les admins ont le pouvoir de forcer le changement de mot de passe
			if ($this->getUser('role') < self::ROLE_ADMIN): ?>
				<?php echo Template::password('userEditOldPassword', [
					'autocomplete' => 'new-password',
					// remplace 'off' pour éviter le pré remplissage auto
					'label' => 'Ancien mot de passe',
				]); ?>
			<?php endif; ?>
			<?php echo Template::password('userEditNewPassword', [
				'autocomplete' => 'off',
				'label' => 'Nouveau mot de passe'
			]); ?>
			<?php echo Template::password('userEditConfirmPassword', [
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
				<?php echo Helper::translate('Permissions'); ?>
			</h4>
			<div class="row">
				<div class="col12">
					<?php if ($this->getUser('role') === self::ROLE_ADMIN): ?>
						<?php echo Template::select('userEditRole', self::$roleEdits, [
							'disabled' => ($this->getUrl(2) === $this->getUser('id')),
							'help' => ($this->getUrl(2) === $this->getUser('id') ? 'Impossible de modifier votre propre rôle.' : ''),
							'label' => 'Rôle',
							'selected' => $this->getData(['user', $this->getUrl(2), 'role']),
						]); ?>
					<?php else: ?>
						<?php echo Template::hidden('userEditRole', [
							'value' => $this->getData(['user', $this->getUrl(2), 'role'])
						]); ?>
					<?php endif; ?>
				</div>
				<div class="col12">
					<div class="userEditGroupProfil" id="userEditGroupProfil<?php echo self::ROLE_MEMBER; ?>">
						<?php echo Template::select('userEditProfil' . self::ROLE_MEMBER, user::$userProfils[self::ROLE_MEMBER], [
							'label' => 'Profil',
							'selected' => $this->getData(['user', $this->getUrl(2), 'profil']),
							'disabled' => $this->getUser('role') !== self::ROLE_ADMIN,
						]); ?>
					</div>
					<div class="userEditGroupProfil" id="userEditGroupProfil<?php echo self::ROLE_EDITOR; ?>">
						<?php echo Template::select('userEditProfil' . self::ROLE_EDITOR, user::$userProfils[self::ROLE_EDITOR], [
							'label' => 'Profil',
							'selected' => $this->getData(['user', $this->getUrl(2), 'profil']),
							'disabled' => $this->getUser('role') !== self::ROLE_ADMIN,
						]); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div id="userCommentProfil<?php echo self::ROLE_MEMBER; ?>" class="col12  userCommentProfil">
					<?php echo Template::textarea('userEditProfilComment' . self::ROLE_MEMBER, [
						'label' => 'Commentaire',
						'value' => implode("\n", user::$userProfilsComments[self::ROLE_MEMBER]),
						'disabled' => true,

					]);
					?>
				</div>
				<div id="userCommentProfil<?php echo self::ROLE_EDITOR; ?>" class="col12  userCommentProfil">
					<?php echo Template::textarea('userEditProfilComment' . self::ROLE_EDITOR, [
						'label' => 'Commentaire',
						'value' => implode("\n", user::$userProfilsComments[self::ROLE_EDITOR]),
						'disabled' => true,

					]);
					?>
				</div>
				<div id="userCommentProfil<?php echo self::ROLE_ADMIN; ?>" class="col12  userCommentProfil">
					<?php echo Template::textarea('userEditProfilComment' . self::ROLE_ADMIN, [
						'label' => 'Commentaire',
						'value' => implode("\n", user::$userProfilsComments[self::ROLE_ADMIN]),
						'disabled' => true,
					]);
					?>
				</div>

			</div>
		</div>
	</div>
</div>
<?php echo Template::formClose(); ?>