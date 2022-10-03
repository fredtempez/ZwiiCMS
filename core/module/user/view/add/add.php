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
			<?php echo template::text('userAddPseudo', [
				'autocomplete' => 'off',
				'label' => 'Pseudo'
			]); ?>
			<?php echo template::select('userAddSignature', $module::$signature, [
				'label' => 'Signature',
				'selected' => 1
			]); ?>
			<?php echo template::mail('userAddMail', [
				'autocomplete' => 'off',
				'label' => 'Adresse mail'
			]); ?>
			<?php echo template::select('userAddGroup', self::$groupNews, [
				'label' => 'Groupe',
				'selected' => self::GROUP_MEMBER
			]); ?>
			<div id="userAddMemberFiles" class="displayNone">
				<?php echo template::checkbox('userAddFiles', true, 'Partage de fichiers autorisé', [
					'checked' => false,
					'help' => 'Ce membre pourra téléverser ou télécharger des fichiers dans le dossier \'partage\' et ses sous-dossiers'
				]); ?>
			</div>
			<div id="userAddLabelAuth">
				<?php echo helper::translate('Permissions :'); ?>
			</div>
			<ul id="userAddGroupDescription<?php echo self::GROUP_MEMBER; ?>" class="userAddGroupDescription displayNone">
				<li><?php echo helper::translate('Accès aux pages privées'); ?></li>
			</ul>
			<ul id="userAddGroupDescription<?php echo self::GROUP_MODERATOR; ?>" class="userAddGroupDescription displayNone">
				<li><?php echo helper::translate('Accès aux pages privées'); ?></li>
				<li><?php echo helper::translate('Ajout / Édition / Suppression de pages'); ?></li>
				<li><?php echo helper::translate('Ajout - Édition  - Suppression de fichiers'); ?></li>
			</ul>
			<ul id="userAddGroupDescription<?php echo self::GROUP_ADMIN; ?>" class="userAddGroupDescription displayNone">
				<li><?php echo helper::translate('Administration complète du site'); ?></li>
			</ul>
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
<?php echo template::formClose(); ?>