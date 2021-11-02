<div id="setupContainer">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Configuration
					<span id="setupHelpButton" class="helpDisplayButton">
					<a href="https://doc.zwiicms.fr/parametres#setup" target="_blank">
						<?php echo template::ico('help', 'left');?>
					</a>
					</span>
				</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::file('configFavicon', [
							'type' => 1,
							'help' => 'Pensez à supprimer le cache de votre navigateur si la favicon ne change pas.',
							'label' => 'Favicon',
							'value' => $this->getData(['config', 'favicon'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::file('configFaviconDark', [
							'type' => 1,
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
						<?php echo template::checkbox('configRewrite', true, 'URL intelligentes', [
							'checked' => helper::checkRewrite(),
							'help' => 'Vérifiez d\'abord que votre serveur autorise l\'URL rewriting (ce qui n\'est pas le cas chez Free).'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Mise à jour automatisée
					<span id="updateHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/parametres#update" target="_blank">
							<?php echo template::ico('help', 'left');?>
						</a>
					</span>
				</h4>
				<?php $updateError = helper::urlGetContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/version');?>
				<div class="row">
					<div class="col4">
						<?php echo template::checkbox('configAutoUpdate', true, 'Rechercher une mise à jour en ligne', [
								'checked' => $this->getData(['config', 'autoUpdate']),
								'help' => 'La vérification est quotidienne. Option désactivée si la configuration du serveur ne le permet pas.',
								'disabled' => !$updateError
							]); ?>
					</div>
					<div class="col4">
						<?php echo template::checkbox('configAutoUpdateHtaccess', true, 'Préserver le fichier htaccess racine', [
								'checked' => $this->getData(['config', 'autoUpdateHtaccess']),
								'help' => 'Lors d\'une mise à jour automatique, conserve le fichier htaccess de la racine du site.',
								'disabled' => !$updateError
							]); ?>
					</div>
					<div class="col4">
						<?php echo template::button('configUpdateForced', [
							'ico' => 'download-cloud',
							'href' => helper::baseUrl() . 'install/update',
							'value' => 'Mise à jour manuelle',
							'class' => 'buttonRed',
							'disabled' => !$updateError
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Maintenance
					<span id="maintenanceHelpButton" class="helpDisplayButton">
					<a href="https://doc.zwiicms.fr/parametres#maintenance" target="_blank">
						<?php echo template::ico('help', 'left');?>
					</a>
					</span>
				</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('configAutoBackup', true, 'Sauvegarde automatique quotidienne du site', [
								'checked' => $this->getData(['config', 'autoBackup']),
								'help' => 'Une archive contenant le dossier /site/data est copiée dans le dossier \'site/backup\'. La sauvegarde est conservée pendant 30 jours.</p><p>Les fichiers du site ne sont pas sauvegardés automatiquement. Activation recommandée.'
							]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('configMaintenance', true, 'Site en maintenance', [
							'checked' => $this->getData(['config', 'maintenance'])
						]); ?>
					</div>
				</div>
				<div class="rows textAlignCenter">
					<div class="col3">
						<?php echo template::button('configBackupButton', [
							'href' => helper::baseUrl() . 'config/backup',
							'value' => 'Sauvegarder',
							'ico' => 'download-cloud'
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::button('configRestoreButton', [
							'href' => helper::baseUrl() . 'config/restore',
							'value' => 'Restaurer',
							'ico' => 'upload-cloud'
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::button('configBackupCopyButton', [
							'href' => helper::baseUrl() . 'config/copyBackups',
							'value' => 'Sauvegardes automatiques',
							'ico' => 'download-cloud'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
