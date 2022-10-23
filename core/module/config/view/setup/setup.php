<div id="setupContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Paramètres'); ?>
					<!--<span id="setupHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/parametres" target="_blank" title="Cliquer pour consulter l'aide en ligne">
							<?php //echo template::ico('help', ['margin' => 'left']); ?>
						</a>-->
					</span>
				</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::file('configFavicon', [
							'type' => 1,
							'language' => $this->getData(['user', $this->getUser('id'), 'language']),
							'help' => 'Pensez à supprimer le cache de votre navigateur si la favicon ne change pas.',
							'label' => 'Favicon',
							'value' => $this->getData(['config', 'favicon'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::file('configFaviconDark', [
							'type' => 1,
							'language' => $this->getData(['user', $this->getUser('id'), 'language']),
							'help' => 'Sélectionnez une icône adaptée à un thème sombre.<br>Pensez à supprimer le cache de votre navigateur si la favicon ne change pas.',
							'label' => 'Favicon thème sombre',
							'value' => $this->getData(['config', 'faviconDark'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('configTimezone', $module::$timezones, [
							'label' => 'Fuseau horaire',
							'selected' => $this->getData(['config', 'timezone']),
							'help' => 'Le fuseau horaire est utile au bon référencement'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('configCookieConsent', true, 'Message de consentement aux cookies', [
							'checked' => $this->getData(['config', 'cookieConsent']),
							'help' => 'Activation obligatoire selon les lois françaises sauf si vous utilisez votre propre système de consentement.'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('configRewrite', true, 'Apache URL intelligentes', [
							'checked' => helper::checkRewrite(),
							'help' => 	'Supprime le point d\'interrogation dans les URL, l\'option est indisponible avec les autres serveurs Web',
							'disabled' =>  stripos($_SERVER["SERVER_SOFTWARE"], 'nginx')
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Mise à jour automatisée'); ?>
					<!--<span id="updateHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/mise-a-jour" target="_blank" title="Cliquer pour consulter l'aide en ligne">
							<?php //echo template::ico('help', ['margin' => 'left']); ?>
						</a>
					</span>-->
				</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('configAutoUpdate', true, 'Rechercher une mise à jour en ligne', [
							'checked' => $this->getData(['config', 'autoUpdate']),
							'help' => 'La vérification est quotidienne. Option désactivée si la configuration du serveur ne le permet pas.',
							'disabled' => !$module::$onlineVersion
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('configAutoUpdateHtaccess', true, 'Préserver le fichier htaccess racine', [
							'checked' => $this->getData(['config', 'autoUpdateHtaccess']),
							'help' => 'Lors d\'une mise à jour automatique, conserve le fichier htaccess de la racine du site.',
							'disabled' => !$module::$onlineVersion
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo  '<pre>Version installée : <strong>' . common::ZWII_VERSION . '</strong></pre>'; ?>
						<?php echo  $module::$onlineVersion ? '<pre>Version en ligne  : <strong>'  . $module::$onlineVersion . '</strong></pre>' : ''; ?>
					</div>
					<div class="col4 verticalAlignBottom">
						<?php echo template::button('configUpdateForced', [
							'ico' => 'download-cloud',
							'href' => helper::baseUrl() . 'install/update',
							'value' => $module::$updateButtonText,
							'class' => 'buttonRed',
							'disabled' => !$module::$onlineVersion
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Maintenance'); ?>
					<!--<span id="maintenanceHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/mode-maintenance" target="_blank" title="Cliquer pour consulter l'aide en ligne">
							<?php //echo template::ico('help', ['margin' => 'left']); ?>
						</a>
					</span>-->
				</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('configAutoBackup', true, 'Sauvegarde automatique quotidienne du site', [
							'checked' => $this->getData(['config', 'autoBackup']),
							'help' => 'Une archive du dossier /site/data est conservée pendant 30 jours. Activation recommandée'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('configMaintenance', true, 'Site en maintenance', [
							'checked' => $this->getData(['config', 'maintenance'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4 offset1">
						<?php echo template::button('configBackupButton', [
							'href' => helper::baseUrl() . 'config/backup',
							'value' => 'Sauvegarder les données du site',
							'ico' => 'download-cloud'
						]); ?>
					</div>
					<div class="col4 offset1">
						<?php echo template::button('configRestoreButton', [
							'href' => helper::baseUrl() . 'config/restore',
							'value' => 'Restaurer les données du site',
							'ico' => 'upload-cloud'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4 offset1">
						<?php echo template::button('configBackupCopyButton', [
							'href' => helper::baseUrl() . 'config/copyBackups',
							'value' => 'Copier sauvegardes auto',
							'ico' => 'docs'
						]); ?>
					</div>
					<div class="col4 offset1">
						<?php echo template::button('configBackupDelButton', [
							'href' => helper::baseUrl() . 'config/delBackups',
							'value' => 'Vider dossier sauvegardes auto',
							'ico' => 'trash',
							'class' => 'buttonRed'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Scripts externes'); ?>
					<!--<span id="specialeHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/scripts-externes" target="_blank" title="Cliquer pour consulter l'aide en ligne">
							<?php //echo template::ico('help', ['margin' => 'left']); ?>
						</a>
					</span>-->
				</h4>
				<div class="row">
					<div class="col4 offset1 verticalAlignBottom">
						<?php echo template::button('socialScriptHead', [
							'href' => helper::baseUrl() . 'config/script/head',
							'value' => 'Script dans head',
							'ico' => 'pencil'
						]); ?>
					</div>
					<div class="col4 offset1 verticalAlignBottom">
						<?php echo template::button('socialScriptBody', [
							'href' => helper::baseUrl() . 'config/script/body',
							'value' => 'Script dans body',
							'ico' => 'pencil'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>