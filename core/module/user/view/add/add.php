<?php echo template::formOpen('userAddForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('userAddBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'user',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('userAddSubmit'); ?>
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
					<?php echo template::text('userAddFirstname', [
						'autocomplete' => 'off',
						'label' => 'Prénom'
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::text('userAddLastname', [
						'autocomplete' => 'off',
						'label' => 'Nom'
					]); ?>
				</div>
			</div>

			<div class="row">
				<div class="col6">
					<?php echo template::text('userAddPseudo', [
						'autocomplete' => 'off',
						'label' => 'Pseudo'
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::select('userAddSignature', user::$signature, [
						'label' => 'Signature',
						'selected' => 1
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::mail('userAddMail', [
						'autocomplete' => 'off',
						'label' => 'Adresse électronique'
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::select('userAddLanguage', user::$languagesInstalled, [
						'label' => 'Langues'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::text('userAddTags', [
						'label' => 'Étiquettes',
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
			<?php echo template::text('userAddId', [
				'autocomplete' => 'off',
				'label' => 'Identifiant'
			]); ?>
			<?php echo template::password('userAddPassword', [
				'autocomplete' => 'off',
				'label' => 'Mot de passe'
			]); ?>
			<?php echo template::password('userAddConfirmPassword', [
				'autocomplete' => 'off',
				'label' => 'Confirmation'
			]); ?>
			<?php echo template::checkbox(
				'userAddSendMail',
				true,
				'Prévenir l\'utilisateur par mail'
			);
			?>
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
				<div class="col6">
					<?php echo template::select('userAddGroup', self::$roleNews, [
						'label' => 'Rôle',
						'selected' => self::ROLE_MEMBER
					]); ?>
				</div>
				<div class="col6">
					<div class="userAddGroupProfil displayNone"
						id="userAddGroupProfil<?php echo self::ROLE_MEMBER; ?>">
						<?php echo template::select('userAddProfil' . self::ROLE_MEMBER, user::$userProfils[self::ROLE_MEMBER], [
							'label' => 'Profil',
						]); ?>
					</div>
					<div class="userAddGroupProfil displayNone"
						id="userAddGroupProfil<?php echo self::ROLE_EDITOR; ?>">
						<?php echo template::select('userAddProfil' . self::ROLE_EDITOR, user::$userProfils[self::ROLE_EDITOR], [
							'label' => 'Profil',
						]); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div id="userCommentProfil<?php echo self::ROLE_MEMBER; ?>"
					class="col12 displayNone userCommentProfil">
					<?php echo template::textarea('useraddProfilComment' . self::ROLE_MEMBER, [
						"value" => implode("\n", user::$userProfilsComments[self::ROLE_MEMBER])
					]);
					?>
				</div>
				<div id="userCommentProfil<?php echo self::ROLE_EDITOR; ?>"
					class="col12 displayNone userCommentProfil">
					<?php echo template::textarea('useraddProfilComment2' . self::ROLE_EDITOR, [
						"value" => implode("\n", user::$userProfilsComments[self::ROLE_EDITOR])
					]);
					?>
				</div>
				<div id="userCommentProfil<?php echo self::ROLE_ADMIN; ?>" class="col12 displayNone userCommentProfil">
					<?php echo template::textarea('useraddProfilComment' . self::ROLE_ADMIN, [
						"value" => implode("\n", user::$userProfilsComments[self::ROLE_ADMIN])
					]);
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>