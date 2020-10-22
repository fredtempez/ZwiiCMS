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
					'value' => 'Sauvegarder'
				]); ?>
			</div>
	<div class="col2">
		<?php echo template::button('configManageButton', [
			'href' => helper::baseUrl() . 'config/manage',
			'value' => 'Restaurer'
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
				<div class="col9">
					<?php echo template::text('configTitle', [
						'label' => 'Titre du site',
						'value' => $this->getData(['config', 'title']),
						'help'  => 'Il apparaît dans la barre de titre et les partages sur les réseaux sociaux.'
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::text('configVersion', [
						'label' => 'ZwiiCMS Version',
						'value' => common::ZWII_VERSION,
						'readonly' => true
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::textarea('configMetaDescription', [
						'label' => 'Description du site',
						'value' => $this->getData(['config', 'metaDescription']),
						'help'  => 'La description d\'une page participe à son référencement, chaque page doit disposer d\'une description différente.'
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Paramètres généraux</h4>
			<?php $error = helper::urlGetContents('http://zwiicms.fr/update/' . common::ZWII_UPDATE_CHANNEL . '/version');?>
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
				<div class="col4 verticalAlignBottom">
					<?php echo template::checkbox('configCookieConsent', true, 'Consentement aux cookies', [
						'checked' => $this->getData(['config', 'cookieConsent'])
					]); ?>
				</div>
				<div class="col4 verticalAlignBottom">
						<?php echo template::checkbox('configCaptchaStrong', true, 'Captcha renforcé', [
							'checked' => $this->getData(['config','captchaStrong']),
							'help' => 'Option recommandée pour sécuriser la connexion. S\'applique à tous les captchas du site.'
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
					<?php echo template::checkbox('configAutoBackup', true, 'Sauvegarde quotidienne', [
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
		<div class="block">
			<h4>Pages spéciales</h4>
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
						$orphans =  $this->getData(['page']);
						foreach($orphans as $page => $pageId) {
							if ($this->getData(['page',$page,'block']) === 'bar' ||
								$this->getData(['page',$page,'disable']) === true ||
								$this->getdata(['page',$page, 'position']) !== 0) {
								unset($orphans[$page]);
							}
						}
						echo template::select('configHomePageId', helper::arrayCollumn($pages, 'title', 'SORT_ASC'), [
						'label' => 'Accueil du site',
						'selected' =>$this->getData(['config', 'homePageId']),
						'help' => 'La première page que vos visiteurs verront.'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('configLegalPageId', array_merge(['none' => 'Aucune'] , helper::arrayCollumn($pages, 'title', 'SORT_ASC') ) , [
						'label' => 'Mentions légales',
						'selected' => $this->getData(['config', 'legalPageId']),
						'help' => 'Les mentions légales sont obligatoires en France. Une option du pied de page ajoute un lien discret vers cette page.'
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('configSearchPageId', array_merge(['none' => 'Aucune'] , helper::arrayCollumn($pages, 'title', 'SORT_ASC') ) , [
						'label' => 'Recherche dans le site',
						'selected' => $this->getData(['config', 'searchPageId']),
						'help' => 'Sélectionner la page "Recherche" ou une page contenant le module "Recherche" permet d\'activer un lien dans le pied de page. '
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php
						echo template::select('configPage403', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($orphans, 'title', 'SORT_ASC')), [
							'label' => 'Accès interdit, erreur 403',
							'selected' =>$this->getData(['config', 'page403']),
							'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
						]); ?>
				</div>
				<div class="col4">
					<?php
						echo template::select('configPage404', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($orphans, 'title', 'SORT_ASC')), [
							'label' => 'Page inexistante, erreur 404',
							'selected' =>$this->getData(['config', 'page404']),
							'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
						]); ?>
				</div>
				<div class="col4">
					<?php
						echo template::select('configPage302', array_merge(['none' => 'Page par défaut'],helper::arrayCollumn($orphans, 'title', 'SORT_ASC')), [
							'label' => 'Site en maintenance',
							'selected' =>$this->getData(['config', 'page302']),
							'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
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
						<label id="helpBlacklist">Comptes inexistants
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
				<div class="row">
					<div class="col3">
						<?php echo template::checkbox('configConnectCaptcha', true, 'Captcha à la connexion', [
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
<?php echo template::formClose(); ?>
