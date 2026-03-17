<?php echo Template::formOpen('userAddForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo Template::button('userAddBack', [
			'class' => 'buttonGrey',
			'href' => Helper::baseUrl() . 'user',
			'value' => Template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo Template::submit('userAddSubmit'); ?>
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
					<?php echo Template::text('userAddFirstname', [
						'autocomplete' => 'off',
						'label' => 'Prénom'
					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::text('userAddLastname', [
						'autocomplete' => 'off',
						'label' => 'Nom'
					]); ?>
				</div>
			</div>

			<div class="row">
				<div class="col6">
					<?php echo Template::text('userAddPseudo', [
						'autocomplete' => 'off',
						'label' => 'Pseudo'
					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::select('userAddSignature', user::$signature, [
						'label' => 'Signature',
						'selected' => 1
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo Template::mail('userAddMail', [
						'autocomplete' => 'off',
						'label' => 'Adresse électronique'
					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::select('userAddLanguage', user::$languagesInstalled, [
						'label' => 'Langues'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo Template::text('userAddTags', [
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
				<?php echo Helper::translate('Authentification'); ?>
			</h4>
			<?php echo Template::text('userAddId', [
				'autocomplete' => 'off',
				'label' => 'Identifiant'
			]); ?>
			<?php echo Template::password('userAddPassword', [
				'autocomplete' => 'off',
				'label' => 'Mot de passe'
			]); ?>
			<?php echo Template::password('userAddConfirmPassword', [
				'autocomplete' => 'off',
				'label' => 'Confirmation'
			]); ?>
			<?php echo Template::checkbox(
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
				<?php echo Helper::translate('Permissions'); ?>
			</h4>
			<div class="row">
				<div class="col6">
					<?php echo Template::select('userAddRole', self::$roleNews, [
						'label' => 'Rôle',
						'selected' => self::ROLE_MEMBER
					]); ?>
				</div>
				<div class="col6">
					<div class="userAddGroupProfil displayNone"
						id="userAddGroupProfil<?php echo self::ROLE_MEMBER; ?>">
						<?php echo Template::select('userAddProfil' . self::ROLE_MEMBER, user::$userProfils[self::ROLE_MEMBER], [
							'label' => 'Profil',
						]); ?>
					</div>
					<div class="userAddGroupProfil displayNone"
						id="userAddGroupProfil<?php echo self::ROLE_EDITOR; ?>">
						<?php echo Template::select('userAddProfil' . self::ROLE_EDITOR, user::$userProfils[self::ROLE_EDITOR], [
							'label' => 'Profil',
						]); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div id="userCommentProfil<?php echo self::ROLE_MEMBER; ?>"
					class="col12 displayNone userCommentProfil">
					<?php echo Template::textarea('useraddProfilComment' . self::ROLE_MEMBER, [
						"value" => implode("\n", user::$userProfilsComments[self::ROLE_MEMBER])
					]);
					?>
				</div>
				<div id="userCommentProfil<?php echo self::ROLE_EDITOR; ?>"
					class="col12 displayNone userCommentProfil">
					<?php echo Template::textarea('useraddProfilComment2' . self::ROLE_EDITOR, [
						"value" => implode("\n", user::$userProfilsComments[self::ROLE_EDITOR])
					]);
					?>
				</div>
				<div id="userCommentProfil<?php echo self::ROLE_ADMIN; ?>" class="col12 displayNone userCommentProfil">
					<?php echo Template::textarea('useraddProfilComment' . self::ROLE_ADMIN, [
						"value" => implode("\n", user::$userProfilsComments[self::ROLE_ADMIN])
					]);
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo Template::formClose(); ?>