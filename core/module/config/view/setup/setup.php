<div id="setupContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php
echo Helper::translate('Paramètres du site'); ?>
				<code><?php
echo Helper::canonicalBaseUrl();?></code>
				</h4>
				<div class="row">
					<div class="col3">
						<?php
echo Template::file('configFavicon', [ 'type' => 1, 'language' => $this->getData(['user', $this->getUser('id'), 'language']), 'help' => 'Pensez à supprimer le cache de votre navigateur si la favicon ne change pas.', 'label' => 'Favicon', 'value' => $this->getData(['config', 'favicon']), 'folder' => $this->getData(['config', 'favicon']) ? dirname($this->getData(['config', 'favicon'])) : '' ]); ?>
					</div>
					<div class="col3">
						<?php
echo Template::file('configFaviconDark', [ 'type' => 1, 'language' => $this->getData(['user', $this->getUser('id'), 'language']), 'help' => 'Sélectionnez une icône adaptée à un thème sombre.<br>Pensez à supprimer le cache de votre navigateur si la favicon ne change pas.', 'label' => 'Favicon thème sombre', 'value' => $this->getData(['config', 'faviconDark']), 'folder' => $this->getData(['config', 'faviconDark']) ? dirname($this->getData(['config', 'faviconDark'])) : '' ]); ?>
					</div>
					<div class="col3">
						<?php
echo Template::select('configTimezone', config::$timezones, [ 'label' => 'Fuseau horaire', 'selected' => $this->getData(['config', 'timezone']), 'help' => 'Le fuseau horaire est utile au bon référencement' ]); ?>
					</div>
					<div class="col3">
						<?php
echo Template::select('configTimerBar', config::$timerBar, [ 'label' => 'Barre d\'administration', 'selected' => $this->getData(['config', 'timerBar']), 'help' => 'Ce paramètre définit la permanence de la barre d\'administration en haut de l\'écran', ]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4 verticalAlignBottom">
						<?php
echo Template::checkbox('configCookieConsent', true, 'Message de consentement aux cookies', [ 'checked' => $this->getData(['config', 'cookieConsent']), 'help' => 'Activation obligatoire selon les lois françaises sauf si vous utilisez votre propre système de consentement.' ]); ?>
					</div>
					<div class="col4 verticalAlignBottom">
						<?php
echo Template::checkbox('configRewrite', true, 'Apache URL intelligentes', [ 'checked' => Helper::checkRewrite(), 'help' => 'Supprime le point d\'interrogation dans les URL, l\'option est indisponible avec les autres serveurs Web', 'disabled' => Helper::checkServerSoftware() === false and config::isModRewriteEnabled() ]); ?>
					</div>
					<div class="col4 verticalAlignBottom">
						<?php
echo Template::checkbox('configPageEditFullScreen', true, 'Édition des pages en plein écran', [ 'checked' => $this->getData(['config', 'editFullScreen']), 'help' => 'La bannière, le menu, le fond du site et le pied de page sont temporairement masqués.' ]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<?php
$updateMessage = Helper::getUrlContents(Core::ZWII_UPDATE_URL . Core::ZWII_UPDATE_CHANNEL . '/update-message.txt'); if (is_string($updateMessage) && trim($updateMessage) !== '') { $updateMessage = strip_tags($updateMessage, '<p><br><strong><em><a><ul><ol><li><h3><h4>'); } else { $updateMessage = ''; } ?>
				<h4><?php
echo Helper::translate('Mise à jour automatisée'); ?>
					&nbsp;|&nbsp;<?php
echo Helper::translate('ZwiiCMS version'); ?>&nbsp;:&nbsp;<strong><?php
echo Core::ZWII_VERSION; ?></strong>
					&nbsp;|&nbsp;
					<?php
echo Helper::translate('ZwiiCMS version'); ?>&nbsp;:&nbsp;<strong><?php
echo Helper::getOnlineVersion(Core::ZWII_UPDATE_CHANNEL); ?></strong>
					<?php
if ($updateMessage !== '') { ?>
					&nbsp;|&nbsp;
					<?php
echo Template::ico('help', [ 'id' => 'configUpdateMessageIcon', 'href' => '#', 'help' => 'Message de mise à jour', 'attr' => 'style="cursor: pointer;"', ]); ?>
					<?php
} ?>
				</h4>
				<?php
if ($updateMessage !== ''): ?>
					<div id="configUpdateMessageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
						<div style="background: #fff; padding: 20px; border-radius: 8px; max-width: 600px; max-height: 80vh; overflow: auto; margin: auto; position: relative; top: 10%;">
							<h4>Message de mise à jour</h4>
							<div><?php
echo $updateMessage; ?></div>
							<div style="text-align: right; margin-top: 15px;">
								<a href="#" id="configUpdateMessageClose" class="button">Fermer</a>
							</div>
						</div>
					</div>
				<?php
endif; ?>
				<div class="row">
					<div class="col3 verticalAlignBottom">
						<?php
echo Template::checkbox('configAutoUpdate', true, 'Rechercher une mise à jour en ligne', [ 'checked' => $this->getData(['config', 'autoUpdate']), 'help' => 'La vérification est quotidienne. Option désactivée si la configuration du serveur ne le permet pas.', 'disabled' => empty(Helper::getOnlineVersion(Core::ZWII_UPDATE_CHANNEL)) ]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<?php
echo Template::checkbox('configAutoUpdateHtaccess', true, 'Préserver le fichier htaccess racine', [ 'checked' => $this->getData(['config', 'autoUpdateHtaccess']), 'help' => 'Lors d\'une mise à jour automatique, conserve le fichier htaccess de la racine du site.', 'disabled' => empty(Helper::getOnlineVersion(Core::ZWII_UPDATE_CHANNEL)) ]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<?php
echo Template::select('configAutoUpdateDelay', config::$updateDelay, [ 'label' => 'Fréquence de recherche', 'selected' => $this->getData(['config', 'autoUpdateDelay']), ]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<?php
echo Template::button('configUpdateForced', [ 'ico' => 'download-cloud', 'href' => Helper::baseUrl() . 'install/update', 'value' => config::$updateButtonText, 'class' => 'buttonRed', ]); ?>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php
echo Helper::translate('Maintenance'); ?>
				</h4>
				<div class="row">
					<div class="col6">
						<?php
echo Template::checkbox('configAutoBackup', true, 'Sauvegarde automatique quotidienne du site', [ 'checked' => $this->getData(['config', 'autoBackup']), 'help' => 'Une archive du dossier /site/data est conservée pendant 30 jours. Activation recommandée' ]); ?>
					</div>
					<div class="col6">
						<?php
echo Template::checkbox('configMaintenance', true, 'Site en maintenance', [ 'checked' => $this->getData(['config', 'maintenance']) ]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4 offset1">
						<?php
echo Template::button('configBackupButton', [ 'href' => Helper::baseUrl() . 'config/backup', 'value' => 'Sauvegarder les données du site', 'ico' => 'download-cloud' ]); ?>
					</div>
					<div class="col4 offset1">
						<?php
echo Template::button('configRestoreButton', [ 'href' => Helper::baseUrl() . 'config/restore', 'value' => 'Restaurer les données du site', 'ico' => 'upload-cloud' ]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4 offset1">
						<?php
echo Template::button('configBackupCopyButton', [ 'href' => Helper::baseUrl() . 'config/copyBackups', 'value' => 'Copier sauvegardes auto', 'ico' => 'docs' ]); ?>
					</div>
					<div class="col4 offset1">
						<?php
echo Template::button('configBackupDelButton', [ 'href' => Helper::baseUrl() . 'config/delBackups', 'value' => 'Vider dossier sauvegardes auto', 'ico' => 'trash', 'class' => 'buttonRed' ]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php
echo Helper::translate('Scripts externes'); ?>
				</h4>
				<div class="row">
					<div class="col4 offset1 verticalAlignBottom">
						<?php
echo Template::button('socialScriptHead', [ 'href' => Helper::baseUrl() . 'config/script/head', 'value' => 'Script dans head', 'ico' => 'pencil' ]); ?>
					</div>
					<div class="col4 offset1 verticalAlignBottom">
						<?php
echo Template::button('socialScriptBody', [ 'href' => Helper::baseUrl() . 'config/script/body', 'value' => 'Script dans body', 'ico' => 'pencil' ]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php
echo Template::select('configCspFrames', [ 'all' => 'Autoriser toutes les iframes en https', 'list' => 'Autoriser seulement les sites de la liste', 'none' => 'Aucune iframe externe' ], [ 'label' => 'Iframes de sites externes', 'selected' => $this->getData(['config', 'cspFrames']) ?: 'all', 'help' => 'Cartes, vidéos et autres contenus d\'autres sites intégrés dans vos pages. L\'option la plus stricte est « Aucune iframe externe ».' ]); ?>
					</div>
					<div class="col6">
						<?php
echo Template::textarea('configCspFrameHosts', [ 'label' => 'Sites autorisés dans les iframes', 'value' => (string) $this->getData(['config', 'cspFrameHosts']), 'help' => 'Un site par ligne, sans https:// (par exemple www.youtube.com). Utilisé seulement avec l\'option « Autoriser seulement les sites de la liste ».' ]); ?>
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
						<p>
						<p></p><strong>ZwiiCMS</strong> is a trademark of Zwii project.
						The name "Zwiicms", logo and associated visual identity
						cannot be used to designate, promote
						or distribute a modified version of software
						without explicit authorization from project holder.
						Any modified version or fork must use a distinct name
						and not suggest an official affiliation with zwiicms project.
						Forks and redistributions must clearly mention
						the origin of zwiicms project in their documentation,
						their official website or their distribution channels,
						and cannot be limited to the sole preservation
						of author mentions in source files.</p>
						<p><strong>ZwiiCMS</strong> est une marque déposée du projet Zwii.
							Le nom "Zwiicms", le logo et l'identité visuelle associée
							ne peuvent être utilisés pour désigner, promouvoir
							ou distribuer une version modifiée du logiciel
							sans autorisation explicite du détenteur du projet.
							Toute version modifiée ou fork doit utiliser un nom distinct
							et ne pas suggérer une affiliation officielle avec le projet zwiicms.
							Les forks et redistributions doivent mentionner clairement
							l'origine du projet zwiicms dans leur documentation,
							leur site web officiel ou leurs canaux de distribution,
							et ne peuvent se limiter à la seule préservation
							des mentions d'auteur dans les fichiers sources.</p>
						<p><a href="https://www.gnu.org/licenses/gpl-3.0.html">GNU General Public License v3.0</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
