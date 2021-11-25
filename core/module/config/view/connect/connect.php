<div id="connectContainer">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Sécurité de la connexion
					<span id="specialeHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/connexion" target="_blank">
							<?php echo template::ico('help', 'left');?>
						</a>
					</span>
				</h4>
				<div class="row">
					<div class="col3">
						<?php echo template::checkbox('connectCaptcha', true, 'Captcha à la connexion', [
							'checked' => $this->getData(['config', 'connect','captcha'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::checkbox('connectCaptchaStrong', true, 'Captcha complexe', [
							'checked' => $this->getData(['config', 'connect', 'captchaStrong']),
							'help' => 'Option recommandée pour sécuriser la connexion. S\'applique à tous les captchas du site. Le captcha simple se limite à une addition de nombres de 0 à 10. Le captcha complexe utilise quatre opérations de nombres de 0 à 20. Activation recommandée.'
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::select('connectCaptchaType', $module::$captchaTypes , [
								'label' => 'Type de captcha',
								'selected' => $this->getData(['config', 'connect', 'captchaType'])
							]); ?>
					</div>
					<div class="col3">
						<?php echo template::checkbox('connectAutoDisconnect', true, 'Déconnexion automatique', [
								'checked' => $this->getData(['config','connect', 'autoDisconnect']),
								'help' => 'Déconnecte les sessions ouvertes précédemment sur d\'autres navigateurs ou terminaux. Activation recommandée.'
							]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
						<?php echo template::select('connectAttempt', $module::$connectAttempt , [
							'label' => 'Connexions successives',
							'selected' => $this->getData(['config', 'connect', 'attempt'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::select('connectTimeout', $module::$connectTimeout , [
							'label' => 'Blocage après échecs',
							'selected' => $this->getData(['config', 'connect', 'timeout'])
						]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<label id="helpBlacklist">Liste noire :
							<?php echo template::help(
							'La liste noire énumère les tentatives de connexion à partir de comptes inexistants. Sont stockés : la date, l\'heure, le nom du compte et l\'IP.
							Après le nombre de tentatives autorisées, l\'IP et le compte sont bloqués.');
							?>
						</label>
						<?php echo template::button('ConnectBlackListDownload', [
							'href' => helper::baseUrl() . 'config/blacklistDownload',
							'value' => 'Télécharger la liste',
							'ico' => 'download'
						]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<?php echo template::button('CnnectBlackListReset', [
							'class' => 'buttonRed',
							'href' => helper::baseUrl() . 'config/blacklistReset',
							'value' => 'Réinitialiser la liste',
							'ico' => 'cancel'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Journalisation
					<span id="specialeHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/journalisation" target="_blank">
							<?php echo template::ico('help', 'left');?>
						</a>
					</span>
				</h4>
				<div class="row">
					<div class="col3">
						<?php echo template::checkbox('connectLog', true, 'Activer la journalisation', [
							'checked' => $this->getData(['config', 'connect', 'log'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::select('connectAnonymousIp', $module::$anonIP, [
							'label' => 'Anonymat des adresses IP',
							'selected' => $this->getData(['config', 'connect', 'anonymousIp']),
							'help' => 'La réglementation française impose un anonymat de niveau 2'
							]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<?php echo template::button('ConfigLogDownload', [
							'href' => helper::baseUrl() . 'config/logDownload',
							'value' => 'Télécharger le journal',
							'ico' => 'download'
						]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<?php echo template::button('ConnectLogReset', [
							'class' => 'buttonRed',
							'href' => helper::baseUrl() . 'config/logReset',
							'value' => 'Réinitialiser le journal',
							'ico' => 'cancel'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Cookies
					<span id="specialeHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/cookies" target="_blank">
								<?php echo template::ico('help', 'left');?>
						</a>
					</span>
				</h4>
				<div class="row">
					<div class="col6">
							<?php echo template::checkbox('configCookieConsent', true, 'Message de consentement aux cookies', [
								'checked' => $this->getData(['config', 'cookies', 'cookieConsent']),
								'help' => 'Activation obligatoire selon les lois françaises sauf si vous utilisez votre propre système de consentement.'
							]); ?>
					</div>
				</div>
				<div id="cookieContainer">	
					<div class="row">
						<div class="col12">
							<?php echo template::textarea('connectCookiesZwiiText', [
								'help' => 'Saisissez le message pour les cookies déposés par ZwiiCMS, nécessaires au fonctionnement et qui ne nécessitent pas de consentement.',
								'label' => 'Cookies Zwii',
								'value' => $this->getData(['config', 'cookies', 'cookiesZwiiText'])
							]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col12">
							<?php echo template::textarea('connectCookiesGaText', [
								'help' => 'Saisissez le message pour les cookies déposés par Google Analytics, le consentement est requis.',
								'label' => 'Cookies Google Analytics',
								'value' => $this->getData(['config', 'cookies', 'cookiesGaText'])
							]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col12">
							<?php echo template::text('connectCookiesTitleText', [
								'help' => 'Saisissez le titre de la fenêtre de gestion des cookies.',
								'label' => 'Titre de la fenêtre',
								'value' => $this->getData(['config', 'cookies', 'cookiesTitleText'])
							]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col6">
							<?php echo template::text('connectCookiesLinkMlText', [
								'help' => 'Saisissez le texte du lien vers les mentions légales.',
								'label' => 'Lien vers mentions légales',
								'value' => $this->getData(['config', 'cookies', 'cookiesLinkMlText'])
							]); ?>
						</div>
						<div class="col6">
							<?php echo template::text('connectCookiesCheckboxGaText', [
								'help' => 'Saisissez le texte de la case à cocher Google Analytics.',
								'label' => 'Checkbox Google Analytics',
								'value' => $this->getData(['config', 'cookies', 'cookiesCheckboxGaText'])
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
