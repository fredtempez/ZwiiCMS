<div id="setup">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Paramètres</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::file('configAdvancedFavicon', [
							'type' => 1,
							'help' => 'Pensez à supprimer le cache de votre navigateur si la favicon ne change pas.',
							'label' => 'Favicon',
							'value' => $this->getData(['config', 'favicon'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::file('configAdvancedFaviconDark', [
							'type' => 1,
							'help' => 'Sélectionnez une icône adaptée à un thème sombre.<br>Pensez à supprimer le cache de votre navigateur si la favicon ne change pas.',
							'label' => 'Favicon thème sombre',
							'value' => $this->getData(['config', 'faviconDark'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('configAdvancedTimezone', $module::$timezones, [
							'label' => 'Fuseau horaire',
							'selected' => $this->getData(['config', 'timezone']),
							'help' => 'Le fuseau horaire est utile au bon référencement'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('configAdvancedCookieConsent', true, 'Message de consentement aux cookies', [
							'checked' => $this->getData(['config', 'cookieConsent']),
							'help' => 'Activation obligatoire selon les lois françaises sauf si vous utilisez votre propre système de consentement.'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('rewrite', true, 'URL intelligentes', [
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
				<h4>Mises à jour automatisée</h4>
				<?php $updateError = helper::urlGetContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/version');?>
				<div class="row">
					<div class="col4">
						<?php echo template::checkbox('configAdvancedAutoUpdate', true, 'Rechercher une mise à jour en ligne', [
								'checked' => $this->getData(['config', 'autoUpdate']),
								'help' => 'La vérification est quotidienne. Option désactivée si la configuration du serveur ne le permet pas.',
								'disabled' => !$updateError
							]); ?>
					</div>
					<div class="col4">
						<?php echo template::checkbox('configAdvancedAutoUpdateHtaccess', true, 'Préserver le fichier htaccess racine', [
								'checked' => $this->getData(['config', 'autoUpdateHtaccess']),
								'help' => 'Lors d\'une mise à jour automatique, conserve le fichier htaccess de la racine du site.',
								'disabled' => !$updateError
							]); ?>
					</div>
					<div class="col4">
						<?php echo template::button('configAdvancedUpdateForced', [
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
				<h4>Maintenance</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('configAdvancedAutoBackup', true, 'Sauvegarde automatique quotidienne du site', [
								'checked' => $this->getData(['config', 'autoBackup']),
								'help' => 'Une archive contenant le dossier /site/data est copiée dans le dossier \'site/backup\'. La sauvegarde est conservée pendant 30 jours.</p><p>Les fichiers du site ne sont pas sauvegardés automatiquement. Activation recommandée.'
							]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('configAdvancedMaintenance', true, 'Site en maintenance', [
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
							'value' => 'Backups Auto &#10140; FileManager'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
