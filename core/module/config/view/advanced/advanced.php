<?php echo template::formOpen('configAdvancedForm'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('configAdvancedBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'config',
			'ico' => 'left',
			'value' => 'Retour'
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
		<div class="block">
			<h4>Maintenance</h4>
			<div class="row">
				<div class="col12">
						<?php echo template::checkbox('configAdvancedMaintenance', true, 'Site en maintenance', [
							'checked' => $this->getData(['config', 'maintenance'])
						]); ?>
					</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::button('configBackupButton', [
						'href' => helper::baseUrl() . 'config/backup',
						'value' => 'Sauvegarder',
						'ico' => 'download-cloud'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::button('configRestoreButton', [
						'href' => helper::baseUrl() . 'config/restore',
						'value' => 'Restaurer',
						'ico' => 'upload-cloud'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::button('configBackupCopyButton', [
						'href' => helper::baseUrl() . 'config/copyBackups',
						'value' => 'Backups Auto &#10140; FileManager'
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Réglages</h4>
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
			<div class="row">
				<div class="col6">
					<?php echo template::checkbox('configAdvancedAutoBackup', true, 'Sauvegarde automatique quotidienne du site', [
							'checked' => $this->getData(['config', 'autoBackup']),
							'help' => 'Une archive contenant le dossier /site/data est copiée dans le dossier \'site/backup\'. La sauvegarde est conservée pendant 30 jours.</p><p>Les fichiers du site ne sont pas sauvegardés automatiquement. Activation recommandée.'
						]); ?>
				</div>
				<div class="col6">
					<?php echo template::checkbox('configAdvancedFileBackup', true, 'Créer un backup des données json', [
							'checked' => file_exists('site/data/.backup'),
							'help' => 'Un fichier .backup.json est généré à chaque édition ou effacement d\'une donnée. La désactivation entraîne la suppression de ces fichiers.'
						]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::checkbox('configAdvancedCaptchaStrong', true, 'Captcha complexe', [
						'checked' => $this->getData(['config','captchaStrong']),
						'help' => 'Option recommandée pour sécuriser la connexion. S\'applique à tous les captchas du site. Le captcha simple se limite à une addition de nombres de 0 à 10. Le captcha complexe utilise quatre opérations de nombres de 0 à 20. Activation recommandée.'
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::checkbox('configAdvancedAutoDisconnect', true, 'Déconnexion automatique de la session', [
							'checked' => $this->getData(['config','autoDisconnect']),
							'help' => 'Déconnecte les sessions ouvertes précédemment sur d\'autres navigateurs ou terminaux. Activation recommandée.'
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
			<?php $updateError = helper::urlGetContents('http://zwiicms.fr/update/' . common::ZWII_UPDATE_CHANNEL . '/version');?>
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
		<div class="block" id="social">
			<h4>Réseaux sociaux
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
						<?php echo template::text('configAdvancedSocialFacebookId', [
							'help' => 'Saisissez votre ID : https://www.facebook.com/[ID].',
							'label' => 'Facebook',
							'value' => $this->getData(['config', 'social', 'facebookId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('configAdvancedSocialInstagramId', [
							'help' => 'Saisissez votre ID : https://www.instagram.com/[ID].',
							'label' => 'Instagram',
							'value' => $this->getData(['config', 'social', 'instagramId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('configAdvancedSocialYoutubeId', [
							'help' => 'ID de la chaîne : https://www.youtube.com/channel/[ID].',
							'label' => 'Chaîne Youtube',
							'value' => $this->getData(['config', 'social', 'youtubeId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('configAdvancedSocialYoutubeUserId', [
							'help' => 'Saisissez votre ID Utilisateur : https://www.youtube.com/user/[ID].',
							'label' => 'Youtube',
							'value' => $this->getData(['config', 'social', 'youtubeUserId'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
							<?php echo template::text('configAdvancedSocialTwitterId', [
								'help' => 'Saisissez votre ID : https://twitter.com/[ID].',
								'label' => 'Twitter',
							'value' => $this->getData(['config', 'social', 'twitterId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('configAdvancedSocialPinterestId', [
							'help' => 'Saisissez votre ID : https://pinterest.com/[ID].',
							'label' => 'Pinterest',
							'value' => $this->getData(['config', 'social', 'pinterestId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('configAdvancedSocialLinkedinId', [
							'help' => 'Saisissez votre ID Linkedin : https://fr.linkedin.com/in/[ID].',
							'label' => 'Linkedin',
							'value' => $this->getData(['config', 'social', 'linkedinId'])
						]); ?>
					</div>
					<div class="col3">
							<?php echo template::text('configAdvancedSocialGithubId', [
								'help' => 'Saisissez votre ID Github : https://github.com/[ID].',
								'label' => 'Github',
								'value' => $this->getData(['config', 'social', 'githubId'])
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block" id="ceo">
			<h4>Référencement
				<div class="openClose">
					<?php
					echo template::ico('plus-circled','right');
					echo template::ico('minus-circled','right');
					?>
				</div>
			</h4>
			 <div class="blockContainer">
				<div class="row">
					<div class="col4 offset1">
						<div class="row">
							<div class="col12">
								<?php echo template::button('configAdvancedMetaImage', [
								'href' => helper::baseUrl() . 'config/configMetaImage',
								'value' => 'Capture Open Graph',
								'ico' => 'pencil'
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col12">
								<?php echo template::button('configAdvancedSiteMap', [
									'href' => helper::baseUrl() . 'config/generateFiles',
									'value' => 'Sitemap.xml / Robots.txt',
									'ico' => 'pencil'
								]); ?>
							</div>
						</div>
					</div>
					<div class="col6 offset1">
						<?php if (file_exists(self::FILE_DIR.'source/screenshot.jpg')): ?>
							<div class="row">
								<div class="col8 offset2 textAlignCenter">
									<img src="<?php echo helper::baseUrl(false) . self::FILE_DIR.'source/screenshot.jpg';?>" data-tippy-content="Cette capture d'écran est nécessaire aux partages sur les réseaux sociaux. Elle est régénérée lorsque le fichier 'screenshot.jpg' est effacé du gestionnaire de fichiers." />
								</div>
						</div>
						<?php endif;?>
					</div>
				</div>
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
					<div class="col4 verticalAlignBottom">
						<?php echo template::checkbox('configAdvancedConnectLog', true, 'Activer la journalisation', [
							'checked' => $this->getData(['config', 'connect', 'log'])
						]); ?>
					</div>
					<div class="col3 offset2">
						<?php echo template::button('configAdvancedLogDownload', [
							'href' => helper::baseUrl() . 'config/logDownload',
							'value' => 'Télécharger journal',
							'ico' => 'download'
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::button('configAdvancedLogReset', [
							'class' => 'buttonRed',
							'href' => helper::baseUrl() . 'config/logReset',
							'value' => 'Réinitialiser journal',
							'ico' => 'cancel'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block" id="network">
			<h4>Réseau
				<div class="openClose">
					<?php
					echo template::ico('plus-circled','right');
					echo template::ico('minus-circled','right');
					?>
				</div>
			</h4>
			<div class="blockContainer">
				<div class="row">
					<div class="col2">
					<?php echo template::select('configAdvancedProxyType', $module::$proxyType, [
						'label' => 'Type de proxy',
						'selected' => $this->getData(['config', 'proxyType'])
						]); ?>
					</div>
					<div  class="col8">
						<?php echo template::text('configAdvancedProxyUrl', [
							'label' => 'Adresse du proxy',
							'placeholder' => 'cache.proxy.fr',
							'value' => $this->getData(['config', 'proxyUrl'])
						]); ?>
					</div>
					<div  class="col2">
						<?php echo template::text('configAdvancedProxyPort', [
							'label' => 'Port du proxy',
							'placeholder' => '6060',
							'value' => $this->getData(['config', 'proxyPort'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block" id="smtp">
			<h4>Messagerie SMTP
				<div class="openClose">
					<?php
					echo template::ico('plus-circled','right');
					echo template::ico('minus-circled','right');
					?>
				</div>
			</h4>
			<div class="blockContainer">
				<div class="row">
					<div class="col12">
						<?php echo template::checkbox('configAdvancedSmtpEnable', true, 'Activer SMTP', [
								'checked' => $this->getData(['config', 'smtp','enable']),
								'help' => 'Paramètres à utiliser lorsque votre hébergeur ne propose pas la fonctionnalité d\'envoi de mail.'
							]); ?>
					</div>
				</div>
				<div id="configAdvancedSmtpParam">
					<div class="row">
						<div class="col8">
							<?php echo template::text('configAdvancedSmtpHost', [
								'label' => 'Adresse SMTP',
								'placeholder' => 'smtp.fr',
								'value' => $this->getData(['config', 'smtp','host'])
							]); ?>
						</div>
						<div  class="col2">
							<?php echo template::text('configAdvancedSmtpPort', [
									'label' => 'Port SMTP',
									'placeholder' => '589',
									'value' => $this->getData(['config', 'smtp','port'])
							]); ?>
						</div>
						<div  class="col2">
							<?php echo template::select('configAdvancedSmtpAuth', $module::$SMTPauth, [
								'label' => 'Authentification',
								'selected' => $this->getData(['config', 'smtp','auth'])
							]); ?>
						</div>
					</div>
					<div id="configAdvancedSmtpAuthParam">
						<div class="row">
							<div  class="col5">
								<?php echo template::text('configAdvancedSmtpUsername', [
									'label' => 'Nom utilisateur',
									'value' => $this->getData(['config', 'smtp','username' ])
								]); ?>
							</div>
							<div  class="col5">
								<?php echo template::password('configAdvancedSmtpPassword', [
									'label' => 'Mot de passe',
									'autocomplete' => 'off',
									'value' => $this->getData(['config', 'smtp','username' ]) ? helper::decrypt ($this->getData(['config', 'smtp','username' ]),$this->getData(['config','smtp','password'])) : ''
								]); ?>
							</div>
							<div  class="col2">
								<?php echo template::select('configAdvancedSmtpSecure', $module::$SMTPEnc	, [
									'label' => 'Sécurité',
									'selected' => $this->getData(['config', 'smtp','secure'])
								]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block" id="script">
			<h4>Scripts
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
						<?php echo template::text('configAdvancedAnalyticsId', [
							'help' => 'Saisissez l\'ID de suivi.',
							'label' => 'Google Analytics',
							'placeholder' => 'UA-XXXXXXXX-X',
							'value' => $this->getData(['config', 'analyticsId'])
						]); ?>
					</div>
					<div class="col3 offset3 verticalAlignBottom">
						<?php echo template::button('configAdvancedScriptHead', [
							'href' => helper::baseUrl() . 'config/script/head',
							'value' => 'Script dans head',
							'ico' => 'pencil'
						]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<?php echo template::button('configAdvancedScriptBody', [
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

<?php echo template::formClose(); ?>
