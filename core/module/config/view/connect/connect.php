<div id="connectContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Sécurité de la connexion'); ?>
				</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::checkbox('connectShowPassword', true, 'Dévoiler le mot de passe', [
							'checked' => $this->getData(['config', 'connect', 'showPassword']),
							'help' => 'Le survol d\'une icône de l\'écran de connexion affiche temporairement le mot de passe.'
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::checkbox('connectAutoDisconnect', true, 'Déconnexion automatique', [
							'checked' => $this->getData(['config', 'connect', 'autoDisconnect']),
							'help' => 'Déconnecte les sessions ouvertes précédemment sur d\'autres navigateurs ou terminaux. Activation recommandée.'
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::checkbox('connectRedirectLogin', true, 'Redirection vers la connexion', [
							'checked' => $this->getData(['config', 'connect', 'redirectLogin']),
							'help' => 'Cette redirection ne concerne que les pages d\'administration du site.'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
						<?php echo template::select('connectAttempt', $module::$connectAttempt, [
							'label' => 'Limitation des tentatives',
							'selected' => $this->getData(['config', 'connect', 'attempt'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::select('connectTimeout', $module::$connectTimeout, [
							'label' => 'Blocage après échecs',
							'selected' => $this->getData(['config', 'connect', 'timeout'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::select('connectAuthMail', array_merge([0 => 'Aucune'], self::$groupNews), [
							'label' => 'Validation par clé ⚠️',
							'selected' => $this->getData(['config', 'connect', 'mailAuth']),
							'help' => 'La connexion est confirmée à l\'aide d\'une clé transmise par messagerie. Depuis le groupe sélectionné et les groupes supérieurs.'
						]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<?php echo template::button('ConfigSendMail', [
							'href' => helper::baseUrl() . 'config/testmail',
							'value' => 'Message de test',
							'ico' => 'mail'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Captcha à la connexion'); ?>
				</h4>
				<div class="row">
					<div class="col3">
						<?php echo template::checkbox('connectCaptcha', true, 'Activer', [
							'checked' => $this->getData(['config', 'connect', 'captcha'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::checkbox('connectCaptchaStrong', true, 'Captcha complexe', [
							'checked' => $this->getData(['config', 'connect', 'captchaStrong']),
							'help' => 'Option recommandée pour sécuriser la connexion. S\'applique à tous les captchas du site. Le captcha simple se limite à une addition de nombres de 0 à 10. Le captcha complexe utilise quatre opérations de nombres de 0 à 20. Activation recommandée.'
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::select('connectCaptchaType', $module::$captchaTypes, [
							'label' => 'Type de captcha',
							'selected' => $this->getData(['config', 'connect', 'captchaType'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Journalisation'); ?>
				</h4>
				<div class="row">
					<div class="col6">
						<div class="row">
							<div class="col6">
								<?php echo template::checkbox('connectLog', true, 'Activer la journalisation', [
									'checked' => $this->getData(['config', 'connect', 'log'])
								]); ?>
							</div>
							<div class="col6">
								<?php echo template::select('connectAnonymousIp', $module::$anonIP, [
									'label' => 'Anonymat des adresses IP',
									'selected' => $this->getData(['config', 'connect', 'anonymousIp']),
									'help' => 'La règlementation française impose un anonymat de niveau 2'
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col6 ">
								<?php echo template::button('ConfigLogDownload', [
									'href' => helper::baseUrl() . 'config/logDownload',
									'value' => 'Télécharger le journal',
									'ico' => 'download'
								]); ?>
							</div>
							<div class="col6">
								<?php echo template::button('ConnectLogReset', [
									'class' => 'buttonRed',
									'href' => helper::baseUrl() . 'config/logReset',
									'value' => 'Réinitialiser le journal',
									'ico' => 'trash'
								]); ?>
							</div>
						</div>
					</div>
					<div class="col6 verticalAlignBottom">
						<div class="row">
							<div class="col6 verticalAlignBottom">
								<label id="helpBlacklist"><?php echo helper::translate('Liste noire'); ?>
									<?php echo template::help(
										'La liste noire énumère les tentatives de connexion à partir de comptes inexistants. Sont stockés : la date, l\'heure, le nom du compte et l\'IP.
							Après le nombre de tentatives autorisées, l\'IP et le compte sont bloqués.'
									);
									?>
								</label>
								<?php echo template::button('ConnectBlackListDownload', [
									'href' => helper::baseUrl() . 'config/blacklistDownload',
									'value' => 'Télécharger la liste',
									'ico' => 'download'
								]); ?>
							</div>
							<div class="col6 verticalAlignBottom">
								<?php echo template::button('CnnectBlackListReset', [
									'class' => 'buttonRed',
									'href' => helper::baseUrl() . 'config/blacklistReset',
									'value' => 'Réinitialiser la liste',
									'ico' => 'trash'
								]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>