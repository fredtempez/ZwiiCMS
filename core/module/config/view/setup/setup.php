<div id="setupContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Paramètres'); ?>
				</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::file('configFavicon', [
							'type' => 1,
							'language' => $this->getData(['user', $this->getUser('id'), 'language']),
							'help' => 'Pensez à supprimer le cache de votre navigateur si la favicon ne change pas.',
							'label' => 'Favicon',
							'value' => $this->getData(['config', 'favicon']),
							'folder' => $this->getData(['config', 'favicon']) ? dirname($this->getData(['config', 'favicon'])) : ''
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::file('configFaviconDark', [
							'type' => 1,
							'language' => $this->getData(['user', $this->getUser('id'), 'language']),
							'help' => 'Sélectionnez une icône adaptée à un thème sombre.<br>Pensez à supprimer le cache de votre navigateur si la favicon ne change pas.',
							'label' => 'Favicon thème sombre',
							'value' => $this->getData(['config', 'faviconDark']),
							'folder' => $this->getData(['config', 'faviconDark']) ? dirname($this->getData(['config', 'faviconDark'])) : ''
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
							'help' => 'Supprime le point d\'interrogation dans les URL, l\'option est indisponible avec les autres serveurs Web',
							'disabled' => helper::checkServerSoftware() === false and $module->isModRewriteEnabled()
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
				</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('configAutoUpdate', true, 'Rechercher une mise à jour en ligne', [
							'checked' => $this->getData(['config', 'autoUpdate']),
							'help' => 'La vérification est quotidienne. Option désactivée si la configuration du serveur ne le permet pas.',
							'disabled' => empty(helper::getOnlineVersion(common::ZWII_UPDATE_CHANNEL))
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('configAutoUpdateHtaccess', true, 'Préserver le fichier htaccess racine', [
							'checked' => $this->getData(['config', 'autoUpdateHtaccess']),
							'help' => 'Lors d\'une mise à jour automatique, conserve le fichier htaccess de la racine du site.',
							'disabled' => empty(helper::getOnlineVersion(common::ZWII_UPDATE_CHANNEL))
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
						<?php echo template::select('configAutoUpdateDelay', $module::$updateDelay, [
							'label' => 'Fréquence de recherche',
							'selected' => $this->getData(['config', 'autoUpdateDelay']),
						]); ?>
					</div>
					<div class="col3 offset1 verticalAlignBottom">
						<pre>Version installée : <strong><?php echo common::ZWII_VERSION; ?></strong></pre>
						<pre>Version en ligne  : <strong><?php echo helper::getOnlineVersion(common::ZWII_UPDATE_CHANNEL); ?></strong></pre>
					</div>
					<div class="col3 offset2 verticalAlignBottom">
						<?php echo template::button('configUpdateForced', [
							'ico' => 'download-cloud',
							'href' => helper::baseUrl() . 'install/update',
							'value' => $module::$updateButtonText,
							'class' => 'buttonRed',
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
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>ZwiiCMS <a href="https://zwiicms.fr" target="_blank">Site Web</a> - <a
						href="https://forum.zwiicms.fr" target="_blank">Forum</a>
				</h4>
				<div class="row textAlignCenter">
					<div class="col12">
						<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img
								alt="Licence Creative Commons" style="border-width:0"
								src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a>
						<p>Cette œuvre est mise à disposition selon les termes de la <a rel="license"
								href="http://creativecommons.org/licenses/by-nc-nd/4.0/">Licence Creative Commons
								Attribution - Pas d&#39;Utilisation Commerciale - Pas de Modification 4.0
								International.</a></p>
						<p>Pour voir une copie de cette licence, visitez
							http://creativecommons.org/licenses/by-nc-nd/4.0/ ou écrivez à Creative Commons, PO Box
							1866, Mountain View, CA 94042, USA.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>