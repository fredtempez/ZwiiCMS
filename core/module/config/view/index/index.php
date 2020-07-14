<?php echo template::formOpen('configForm'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('configBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(false),
			'ico' => 'home',
			'value' => 'Accueil'
		]); ?>
	</div>
	<div class="col2 offset4">
				<?php echo template::button('configManageButton', [
					'href' => helper::baseUrl() . 'config/backup',
					'value' => 'Sauvegarder',
					'ico' => 'download'
				]); ?>
			</div>
	<div class="col2">
		<?php echo template::button('configManageButton', [
			'href' => helper::baseUrl() . 'config/manage',
			'value' => 'Restaurer',
			'ico' => 'upload'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::submit('configSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Informations générales</h4>
			<div class="row">
				<div class="col4">
				<?php
					$pages = $this->getData(['page']);
					foreach($pages as $page => $pageId) {
						if ($this->getData(['page',$page,'block']) === 'bar' ||
						$this->getData(['page',$page,'disable']) === true) {
							unset($pages[$page]);
						}
					}
					echo template::select('configHomePageId', helper::arrayCollumn($pages, 'title', 'SORT_ASC'), [
					'label' => 'Page d\'accueil',
					'selected' =>$this->getData(['config', 'homePageId'])
					]); ?>
				</div>
				<div class="col8">
					<?php echo template::text('configTitle', [
						'label' => 'Titre du site',
						'value' => $this->getData(['config', 'title']),
						'help'  => 'Il apparaît dans la barre de titre et les partages sur les réseaux sociaux.'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::textarea('configMetaDescription', [
						'label' => 'Description du site',
						'value' => $this->getData(['config', 'metaDescription']),
						'help'  => 'Elle apparaît dans les partages sur les réseaux sociaux.'
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Paramètres</h4>
			<?php $error = helper::urlGetContents('http://zwiicms.com/update/' . common::ZWII_UPDATE_CHANNEL . '/version');?>
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
					<?php echo template::select('configItemsperPage', $module::$ItemsList, [
					'label' => 'Articles par page',
					'selected' => $this->getData(['config', 'itemsperPage']),
					'help' => 'Modules Blog et News'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::select('configTimezone', $module::$timezones, [
						'label' => 'Fuseau horaire',
						'selected' => $this->getData(['config', 'timezone']),
						'help' => 'Le fuseau horaire est utile au bon référencement'
					]); ?>
				</div>
				<div class="col4">
					<?php  $listePageId =  array_merge(['' => 'Sélectionner'] , helper::arrayCollumn($this->getData(['page']), 'title', 'SORT_ASC') );
					?>
					<?php echo template::select('configLegalPageId', $listePageId , [
						'label' => 'Mentions légales',
						'selected' => $this->getData(['config', 'legalPageId']),
						'help' => 'Les mentions légales sont obligatoires en France'
					]); ?>
				</div>
				<div class="col4 verticalAlignBottom">
					<?php echo template::checkbox('configCookieConsent', true, 'Message de consentement aux cookies', [
						'checked' => $this->getData(['config', 'cookieConsent'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::checkbox('rewrite', true, 'Réécriture d\'URL', [
						'checked' => helper::checkRewrite(),
						'help' => 'Vérifiez d\'abord que votre serveur l\'autorise : ce n\'est pas le cas chez Free.'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::checkbox('configMaintenance', true, 'Site en maintenance', [
						'checked' => $this->getData(['config', 'maintenance'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::checkbox('configAutoBackup', true, 'Sauvegarde automatique quotidienne', [
							'checked' => $this->getData(['config', 'autoBackup']),
							'help' => '<p>Une archive contenant le dossier /site/data est copiée dans le dossier \'site/backup\'. La sauvegarde est conservée pendant 30 jours.</p><p>Les fichiers du site ne sont pas sauvegardés automatiquement.</p>'
						]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::checkbox('configAutoUpdate', true, 'Mise à jour en ligne', [
							'checked' => $this->getData(['config', 'autoUpdate']),
							'help' => 'Vérifie une fois par jour l\'existence d\'une mise à jour.',
							'disabled' => !$error
						]); ?>
				</div>
				<div class="col4 ">
				<?php echo template::checkbox('configAutoUpdateHtaccess', true, 'Préserver htaccess', [
							'checked' => $this->getData(['config', 'autoUpdateHtaccess']),
							'help' => 'Lors d\'une mise à jour automatique, conserve le fichier htaccess de la racine du site.',
							'disabled' => !$error
						]); ?>
				</div>
				<div class="col4 ">
					<?php echo template::button('configUpdateForced', [
						'ico' => 'download-cloud',
						'href' => helper::baseUrl() . 'install/update',
						'value' => 'Mise à jour manuelle',
						'class' => 'buttonRed',
						'disabled' => !$error
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block" id="social">
			<h4><?php
			echo template::ico('plus','right');
			echo template::ico('minus','right');
			 ?>Réseaux sociaux</h4>
			 <div class="blockContainer">
				<div class="row">
					<div class="col3">
						<?php echo template::text('configSocialFacebookId', [
							'help' => 'Saisissez votre ID : https://www.facebook.com/[ID].',
							'label' => 'Facebook',
							'value' => $this->getData(['config', 'social', 'facebookId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('configSocialInstagramId', [
							'help' => 'Saisissez votre ID : https://www.instagram.com/[ID].',
							'label' => 'Instagram',
							'value' => $this->getData(['config', 'social', 'instagramId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('configSocialYoutubeId', [
							'help' => 'ID de la chaîne : https://www.youtube.com/channel/[ID].',
							'label' => 'Chaîne Youtube',
							'value' => $this->getData(['config', 'social', 'youtubeId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('configSocialYoutubeUserId', [
							'help' => 'Saisissez votre ID Utilisateur : https://www.youtube.com/user/[ID].',
							'label' => 'Youtube',
							'value' => $this->getData(['config', 'social', 'youtubeUserId'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
							<?php echo template::text('configSocialTwitterId', [
								'help' => 'Saisissez votre ID : https://twitter.com/[ID].',
								'label' => 'Twitter',
							'value' => $this->getData(['config', 'social', 'twitterId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('configSocialPinterestId', [
							'help' => 'Saisissez votre ID : https://pinterest.com/[ID].',
							'label' => 'Pinterest',
							'value' => $this->getData(['config', 'social', 'pinterestId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('configSocialLinkedinId', [
							'help' => 'Saisissez votre ID Linkedin : https://fr.linkedin.com/in/[ID].',
							'label' => 'Linkedin',
							'value' => $this->getData(['config', 'social', 'linkedinId'])
						]); ?>
					</div>
					<div class="col3">
							<?php echo template::text('configSocialGithubId', [
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
			<h4><?php
			echo template::ico('plus','right');
			echo template::ico('minus','right');
			 ?>Référencement</h4>
			 <div class="blockContainer">
				<div class="row">
					<div class="col4 offset1">
						<div class="row">
							<div class="col12">
								<?php echo template::button('configMetaImage', [
								'href' => helper::baseUrl() . 'config/configMetaImage',
								'value' => 'Capture Open Graph',
								'ico' => 'pencil'
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col12">
								<?php echo template::button('configSiteMap', [
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
		<div class="block" id="network">
			<h4><?php
			echo template::ico('plus','right');
			echo template::ico('minus','right');
			 ?>Réseau</h4>
			<div class="blockContainer">
				<div class="row">
					<div class="col2">
					<?php echo template::select('configProxyType', $module::$proxyType, [
						'label' => 'Type de proxy',
						'selected' => $this->getData(['config', 'proxyType'])
						]); ?>
					</div>
					<div  class="col8">
						<?php echo template::text('configProxyUrl', [
							'label' => 'Adresse du proxy',
							'placeholder' => 'cache.proxy.fr',
							'value' => $this->getData(['config', 'proxyUrl'])
						]); ?>
					</div>
					<div  class="col2">
						<?php echo template::text('configProxyPort', [
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
			<h4><?php
			echo template::ico('plus','right');
			echo template::ico('minus','right');
			?>Messagerie SMTP</h4>
			<div class="blockContainer">
				<div class="row">
					<div class="col12">
						<?php echo template::checkbox('configSmtpEnable', true, 'Activer SMTP', [
								'checked' => $this->getData(['config', 'smtp','enable']),
								'help' => 'Paramètres à utiliser lorsque votre hébergeur ne propose pas la fonctionnalité d\'envoi de mail.'
							]); ?>
					</div>
				</div>
				<div id="configSmtpParam">
					<div class="row">
						<div class="col8">
							<?php echo template::text('configSmtpHost', [
								'label' => 'Adresse SMTP',
								'placeholder' => 'smtp.fr',
								'value' => $this->getData(['config', 'smtp','host'])
							]); ?>
						</div>
						<div  class="col2">
							<?php echo template::text('configSmtpPort', [
									'label' => 'Port SMTP',
									'placeholder' => '589',
									'value' => $this->getData(['config', 'smtp','port'])
							]); ?>
						</div>
						<div  class="col2">
							<?php echo template::select('configSmtpAuth', $module::$SMTPauth, [
								'label' => 'Authentification',
								'selected' => $this->getData(['config', 'smtp','auth'])
							]); ?>
						</div>
					</div>
					<div id="configSmtpAuthParam">
						<div class="row">
							<div  class="col5">
								<?php echo template::text('configSmtpUsername', [
									'label' => 'Nom utilisateur',
									'value' => $this->getData(['config', 'smtp','username' ])
								]); ?>
							</div>
							<div  class="col5">
								<?php echo template::password('configSmtpPassword', [
									'label' => 'Mot de passe',
									'autocomplete' => 'off',
									'value' => $this->getData(['config', 'smtp','username' ]) ? helper::decrypt ($this->getData(['config', 'smtp','username' ]),$this->getData(['config','smtp','password'])) : ''
								]); ?>
							</div>
							<div  class="col2">
								<?php echo template::select('configSmtpSecure', $module::$SMTPEnc	, [
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
		<div class="block" id="login">
			<h4><?php
			echo template::ico('plus','right');
			echo template::ico('minus','right');
			?>Sécurité de la connexion</h4>
			<div class="blockContainer">
				<div class="row">
					<div class="col3">
						<?php echo template::select('configConnectAttempt', $module::$connectAttempt , [
							'label' => 'Connexions successives',
							'selected' => $this->getData(['config', 'connect', 'attempt'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::select('configConnectTimeout', $module::$connectTimeout , [
							'label' => 'Blocage après échecs',
							'selected' => $this->getData(['config', 'connect', 'timeout'])
						]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<label id="helpBlacklist">Blocage des comptes inconnus
							<?php echo template::help(
							'La liste noire énumère les tentatives de connexion à partir de comptes inexistants. Sont stockés : la date, l\'heure, le nom du compte et l\'IP.
							Après le nombre de tentatives autorisées, l\'IP et le compte sont bloqués.');
							?>
						</label>
						<?php echo template::button('configConnectblacListDownload', [
							'href' => helper::baseUrl() . 'config/blacklistDownload',
							'value' => 'Télécharger liste noire',
							'ico' => 'download'
						]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<?php echo template::button('ConfigConnectReset', [
							'class' => 'buttonRed',
							'href' => helper::baseUrl() . 'config/blacklistReset',
							'value' => 'Réinitialiser liste',
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
		<div class="block" id="logs">
			<h4><?php
			echo template::ico('plus','right');
			echo template::ico('minus','right');
			?>Journalisation</h4>
			<div class="blockContainer">
				<div class="row">
					<div class="col4 verticalAlignBottom">
						<?php echo template::checkbox('configConnectLog', true, 'Activer la journalisation', [
							'checked' => $this->getData(['config', 'connect', 'log'])
						]); ?>
					</div>
					<div class="col3 offset2">
						<?php echo template::button('ConfigLogDownload', [
							'href' => helper::baseUrl() . 'config/logDownload',
							'value' => 'Télécharger journal',
							'ico' => 'download'
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::button('ConfigLogReset', [
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
		<div class="block" id="script">
			<h4><?php
			echo template::ico('plus','right');
			echo template::ico('minus','right');
			?>Scripts</h4>
			<div class="blockContainer">
				<div class="row">
					<div class="col3">
						<?php echo template::text('configAnalyticsId', [
							'help' => 'Saisissez l\'ID de suivi.',
							'label' => 'Google Analytics',
							'placeholder' => 'UA-XXXXXXXX-X',
							'value' => $this->getData(['config', 'analyticsId'])
						]); ?>
					</div>
					<div class="col3 offset3 verticalAlignBottom">
						<?php echo template::button('configScriptHead', [
							'href' => helper::baseUrl() . 'config/script/head',
							'value' => 'Script dans head',
							'ico' => 'pencil'
						]); ?>
					</div>
					<div class="col3 verticalAlignBottom">
						<?php echo template::button('ConfigScriptBody', [
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
<div class="row">
	<div class="col12" >
		<div class="block" id="system">
			<h4><?php
			echo template::ico('plus','right');
			echo template::ico('minus','right');
			?>Système</h4>
			<div class="blockContainer">
				<div class="row">
					<div  class="col2">
						<?php echo template::text('configVersion', [
						'label' => 'ZwiiCMS',
						'readonly' => true,
						'value' => common::ZWII_VERSION
					]); ?>
					</div>
					<div  class="col2">
						<?php echo template::text('moduleBlogVersion', [
							'label' => 'Blog',
							'readonly' => true,
							'value' => blog::BLOG_VERSION
						]); ?>
					</div>
					<div  class="col2">
						<?php echo template::text('moduleFormVersion', [
							'label' => 'Form',
							'readonly' => true,
							'value' => form::FORM_VERSION
						]); ?>
					</div>
					<div  class="col2">
						<?php echo template::text('moduleGalleryVersion', [
							'label' => 'Gallery',
							'readonly' => true,
							'value' => gallery::GALLERY_VERSION
						]); ?>
					</div>
					<div  class="col2">
						<?php echo template::text('moduleNewsVersion', [
							'label' => 'News',
							'readonly' => true,
							'value' => news::NEWS_VERSION
						]); ?>
					</div>
					<div  class="col2">
						<?php echo template::text('moduleRedirectionVersion', [
							'label' => 'Redirection',
							'readonly' => true,
							'value' => redirection::REDIRECTION_VERSION
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>
