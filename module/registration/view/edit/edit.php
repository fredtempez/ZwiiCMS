<?php echo template::formOpen('registrationUserEditForm'); ?>
	<div class="row">
		<div class="col2">
			<?php if($this->getUrl(3)): ?>
				<?php echo template::button('registrationUserEditBack', [
					'class' => '',
					'href' => helper::baseUrl() . $this->geturl(0) . '/user',
					'ico' => 'left',
					'value' => 'Retour'
				]); ?>
			<?php else: ?>
				<?php echo template::button('registrationUserEditBack', [
					'class' => '',
					'href' => helper::baseUrl(false),
					'ico' => 'home',
					'value' => 'Accueil'
				]); ?>
			<?php endif; ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::submit('registrationUserEditSubmit',[
				'class' => 'green'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Confirmation de l'inscription</h4>
				<div class="row">
					<div class="col6">
						<div class="row">
							<div class="col6">
								<?php echo template::text('registrationUserEditFirstname', [
									'autocomplete' => 'off',
									'label' => 'Prénom',
									'value' => $this->getData(['user', $this->getUrl(2), 'firstname']),
									'disabled'=> true
								]); ?>
							</div>
							<div class="col6">
								<?php echo template::text('registrationUserEditLastname', [
									'autocomplete' => 'off',
									'label' => 'Nom',
									'value' => $this->getData(['user', $this->getUrl(2), 'lastname']),
									'disabled'=> true
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col12">
								<?php echo template::mail('registrationUserEditMail', [
									'autocomplete' => 'off',
									'label' => 'Adresse mail',
									'value' => $this->getData(['user', $this->getUrl(2), 'mail']),
									'disabled'=> true
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col6">
								<?php $status = $module::$statusGroups[$this->getData(['user', $this->getUrl(2), 'group'])];?>
								<?php echo template::text('resgistrationUserState', [
									'label' => 'État de l\'inscription',
									'value' => $status,
									'disabled'=> true,
									'help' => 'En attente : le mail n\'a pas encore été validé<br>Email validé : approbation nécessaire.'
								]);	?>
							</div>
							<div class="col6">
								<?php echo template::text('resgistrationUsertimer', [
									'label' => 'Date',
									'value' => helper::dateUTF8(date('Y-m-d G:i'), $this->getData(['user', $userId, 'timer'])),
									'disabled'=> true
								]);	?>
							</div>
						</div>
					</div>

					<div class="col6">
						<?php if($this->getUser('group') === self::GROUP_ADMIN): ?>
							<?php echo template::select('registrationUserEditGroup', self::$groupEdits, [
								'disabled' => ($this->getUrl(2) === $this->getUser('id')),
								'help' => ($this->getUrl(2) === $this->getUser('id') ? 'Impossible de modifier votre propre groupe.' : ''),
								'label' => 'Groupe <em>(Banni : en attente d\'approbation)</em>',
								'selected' => $this->getData(['user', $this->getUrl(2), 'group'])
							]); ?>
							Autorisations :
							<ul id="registrationUserEditGroupDescription<?php echo self::GROUP_MEMBER; ?>" class="registrationUserEditGroupDescription displayNone">
								<li>Accès aux pages privées membres</li>
							</ul>
							<ul id="registrationUserEditGroupDescription<?php echo self::GROUP_MODERATOR; ?>" class="registrationUserEditGroupDescription displayNone">
								<li>Accès aux pages privées membres et éditeurs</li>
								<li>Ajout - Édition - Suppression de pages</li>
								<li>Ajout - Édition  - Suppression de fichiers</li>
							</ul>
							<ul id="registrationUserEditGroupDescription<?php echo self::GROUP_ADMIN; ?>" class="registrationUserEditGroupDescription displayNone">
								<li>Accès à toutes les pages privées</li>
								<li>Ajout - Édition - Suppression de pages</li>
								<li>Ajout - Édition  - Suppression de fichiers</li>
								<li>Ajout / Édition / Suppression d'utilisateurs</li>
								<li>Configuration du site</li>
								<li>Personnalisation du thème</li>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>