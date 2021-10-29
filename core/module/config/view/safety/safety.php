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
	<?php echo file_get_contents( 'core/module/config/view/advanced/advanced.help.html') ;?>
</div>	
<div class="row">
	<div class="col12">
		<div class="row textAlignCenter">
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/index',
					'value' => 'Bases'
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
		<div class="block" id="login">
			<h4>Sécurité de la connexion
				<div class="openClose">
					<?php
					echo template::ico('plus-circled','right');
					echo template::ico('minus-circled','right');
					?>
				</div>
			</h4>
			<div class="blockContainer">
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
						<label id="helpBlacklist">Comptes inexistants
							<?php echo template::help(
							'La liste noire énumère les tentatives de connexion à partir de comptes inexistants. Sont stockés : la date, l\'heure, le nom du compte et l\'IP.
							Après le nombre de tentatives autorisées, l\'IP et le compte sont bloqués.');
							?>
						</label>
						<?php echo template::button('configAdvancedConnectblacListDownload', [
							'href' => helper::baseUrl() . 'config/blacklistDownload',
							'value' => 'Télécharger liste noire',
							'ico' => 'download'
						]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<?php echo template::button('configAdvancedConnectReset', [
							'class' => 'buttonRed',
							'href' => helper::baseUrl() . 'config/blacklistReset',
							'value' => 'Réinitialiser liste',
							'ico' => 'cancel'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('configAdvancedConnectCaptcha', true, 'Captcha à la connexion', [
							'checked' => $this->getData(['config', 'connect','captcha'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block" id="logs">
			<h4>Journalisation
				<div class="openClose">
					<?php
					echo template::ico('plus-circled','right');
					echo template::ico('minus-circled','right');
					?>
				</div>
			</h4>
			<div class="blockContainer">
				<div class="row">
					<div class="col4 offset2">
						<?php echo template::checkbox('configAdvancedConnectLog', true, 'Activer la journalisation', [
							'checked' => $this->getData(['config', 'connect', 'log'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('configAdvancedConnectAnonymousIp', $module::$anonIP, [
							'label' => 'Anonymat des adresses IP',
							'selected' => $this->getData(['config', 'connect', 'anonymousIp']),
							'help' => 'La réglementation française impose un anonymat de niveau 2'
							]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3 offset2">
						<?php echo template::button('configAdvancedLogDownload', [
							'href' => helper::baseUrl() . 'config/logDownload',
							'value' => 'Télécharger le journal',
							'ico' => 'download'
						]); ?>
					</div>
					<div class="col3 offset1">
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
</div>
<?php echo template::formClose(); ?>
