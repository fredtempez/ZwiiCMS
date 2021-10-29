<?php echo template::formOpen('configAdvancedForm'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('configBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(false),
			'ico' => 'home',
			'value' => 'Accueil'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('configAdvancedHelp', [
			'class' => 'buttonHelp',
			'ico' => 'help',
			'value' => 'Aide'
		]); ?>
	</div>
	<div class="col2 offset6">
		<?php echo template::submit('configAdvancedSubmit'); ?>
	</div>
</div>
<!-- Aide à propos de la configuration du site, view advanced -->
<div class="helpDisplayContent">
	<?php echo file_get_contents( 'core/module/config/view/safety/safety.help.html') ;?>
</div>	
<div class="row">
	<div class="col12">
		<div class="row textAlignCenter">
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/index',
					'value' => 'Paramètres'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/locale',
					'value' => 'Localisation'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/social',
					'value' => 'Référencement'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/safety',
					'value' => 'Sécurité'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/network',
					'value' => 'Réseau'
				]); ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Sécurité de la connexion</h4>
			<div class="row">
				<div class="col4">
					<?php echo template::checkbox('configAdvancedConnectCaptcha', true, 'Captcha à la connexion', [
						'checked' => $this->getData(['config', 'connect','captcha'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::checkbox('configAdvancedCaptchaStrong', true, 'Captcha complexe', [
						'checked' => $this->getData(['config','captchaStrong']),
						'help' => 'Option recommandée pour sécuriser la connexion. S\'applique à tous les captchas du site. Le captcha simple se limite à une addition de nombres de 0 à 10. Le captcha complexe utilise quatre opérations de nombres de 0 à 20. Activation recommandée.'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::checkbox('configAdvancedAutoDisconnect', true, 'Déconnexion automatique', [
							'checked' => $this->getData(['config','autoDisconnect']),
							'help' => 'Déconnecte les sessions ouvertes précédemment sur d\'autres navigateurs ou terminaux. Activation recommandée.'
						]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php echo template::select('configAdvancedConnectAttempt', $module::$connectAttempt , [
						'label' => 'Connexions successives',
						'selected' => $this->getData(['config', 'connect', 'attempt'])
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::select('configAdvancedConnectTimeout', $module::$connectTimeout , [
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
					<?php echo template::button('configAdvancedConnectblacListDownload', [
						'href' => helper::baseUrl() . 'config/blacklistDownload',
						'value' => 'Télécharger la liste',
						'ico' => 'download'
					]); ?>
				</div>
				<div class="col3 verticalAlignBottom">
					<?php echo template::button('configAdvancedConnectReset', [
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
			<h4>Journalisation</h4>
			<div class="row">
				<div class="col3">
					<?php echo template::checkbox('configAdvancedConnectLog', true, 'Activer la journalisation', [
						'checked' => $this->getData(['config', 'connect', 'log'])
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::select('configAdvancedConnectAnonymousIp', $module::$anonIP, [
						'label' => 'Anonymat des adresses IP',
						'selected' => $this->getData(['config', 'connect', 'anonymousIp']),
						'help' => 'La réglementation française impose un anonymat de niveau 2'
						]); ?>
				</div>
				<div class="col3 verticalAlignBottom">
					<?php echo template::button('configAdvancedLogDownload', [
						'href' => helper::baseUrl() . 'config/logDownload',
						'value' => 'Télécharger le journal',
						'ico' => 'download'
					]); ?>
				</div>
				<div class="col3 verticalAlignBottom">
					<?php echo template::button('configAdvancedLogReset', [
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
<?php echo template::formClose(); ?>
