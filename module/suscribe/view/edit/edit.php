<?php echo template::formOpen('registrationUserEditForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('registrationUserEditBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->geturl(0) . '/users',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('registrationUserEditSubmit', [
			'class' => 'green'
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Approbation de l'inscription</h4>
			<div class="row">
				<div class="col4">
					<?php echo template::text('registrationUserLabel', [
						'label' => 'Étiquettes',
						'help' => 'Les étiquettes sont séparées par des espaces',
					]); ?>
				</div>
				<div class="col4">
					<?php if ($this->getUser('role') === self::ROLE_ADMIN): ?>
						<?php echo template::select('registrationUserEditGroup', suscribe::$roles, [
							'disabled' => ($this->getUrl(2) === $this->getUser('id')),
							'help' => ($this->getUrl(2) === $this->getUser('id') ? 'Impossible de modifier votre propre role.' : ''),
							'label' => 'Rôle',
							'selected' => $this->getData(['module', $this->getUrl(0), 'registrationUsers', $this->getUrl(2), 'status'])
						]); ?>
					<?php endif; ?>
				</div>
				<div class="col4">
					<div class="registrationUserEditGroupProfil"
						id="registrationUserEditGroupProfil<?php echo self::ROLE_MEMBER; ?>">
						<?php echo template::select('registrationUserEditProfil' . self::ROLE_MEMBER, suscribe::$userProfils[self::ROLE_MEMBER], [
							'label' => 'Profil',
							'selected' => $this->getData(['user', $this->getUrl(2), 'profil']),
							'disabled' => $this->getUser('role') !== self::ROLE_ADMIN,
						]); ?>
					</div>
					<div class="registrationUserEditGroupProfil"
						id="registrationUserEditGroupProfil<?php echo self::ROLE_EDITOR; ?>">
						<?php echo template::select('registrationUserEditProfil' . self::ROLE_EDITOR, suscribe::$userProfils[self::ROLE_EDITOR], [
							'label' => 'Profil',
							'selected' => $this->getData(['user', $this->getUrl(2), 'profil']),
							'disabled' => $this->getUser('role') !== self::ROLE_ADMIN,
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col12">
						<div id="registrationUserCommentProfil<?php echo self::ROLE_MEMBER; ?>"
							class="col12  registrationUserCommentProfil">
							<?php echo template::textarea('registrationUserEditProfilComment' . self::ROLE_MEMBER, [
								'label' => 'Commentaire',
								'value' => implode("\n", suscribe::$userProfilsComments[self::ROLE_MEMBER]),
								'readonly' => true,

							]);
							?>
						</div>
						<div id="registrationUserCommentProfil<?php echo self::ROLE_EDITOR; ?>"
							class="col12  registrationUserCommentProfil">
							<?php echo template::textarea('registrationUserEditProfilComment' . self::ROLE_EDITOR, [
								'label' => 'Commentaire',
								'value' => implode("\n", suscribe::$userProfilsComments[self::ROLE_EDITOR]),
								'readonly' => true,

							]);
							?>
						</div>
						<div id="registrationUserCommentProfil<?php echo self::ROLE_ADMIN; ?>"
							class="col12  registrationUserCommentProfil">
							<?php echo template::textarea('registrationUserEditProfilComment' . self::ROLE_ADMIN, [
								'label' => 'Commentaire',
								'value' => implode("\n", suscribe::$userProfilsComments[self::ROLE_ADMIN]),
								'readonly' => true,
							]);
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Identité du compte</h4>
			<div class="row">
				<div class="col12">
					<div class="row">
						<div class="col6">
							<?php echo template::text('registrationUserEditFirstname', [
								'autocomplete' => 'off',
								'label' => 'Prénom',
								'value' => $this->getData(['module', $this->getUrl(0), 'users', $this->getUrl(2), 'firstname']),
								'disabled' => true
							]); ?>
						</div>
						<div class="col6">
							<?php echo template::text('registrationUserEditLastname', [
								'autocomplete' => 'off',
								'label' => 'Nom',
								'value' => $this->getData(['module', $this->getUrl(0), 'users', $this->getUrl(2), 'lastname']),
								'disabled' => true
							]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col12">
							<?php echo template::mail('registrationUserEditMail', [
								'autocomplete' => 'off',
								'label' => 'Adresse électronique',
								'value' => $this->getData(['module', $this->getUrl(0), 'users', $this->getUrl(2), 'mail']),
								'disabled' => true
							]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col8">
							<?php echo template::text('registrationUserState', [
								'label' => 'État de l\'inscription',
								'value' => suscribe::$statusGroups[$this->getData(['module', $this->getUrl(0), 'users', $this->getUrl(2), 'status'])],
								'disabled' => true,
								'help' => 'En attente : le mail n\'a pas encore été validé<br>Email validé : approbation nécessaire.'
							]); ?>
						</div>
						<div class="col4">
							<?php echo template::text('registrationUsertimer', [
								'label' => 'Date de demande',
								'value' => helper::dateUTF8(date('Y-m-d G:i'), $this->getData(['module', $this->getUrl(0), 'users', $this->getUrl(2), 'timer'])),
								'disabled' => true
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>