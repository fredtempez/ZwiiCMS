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
	<div class="col2 offset8">
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
						'help'  => 'Le titre apparaît dans la barre de titre et les partages sur les réseaux sociaux.'
					]); ?>
				</div>						
				<div class="col12">
					<?php echo template::textarea('configMetaDescription', [
						'label' => 'Description du site',
						'value' => $this->getData(['config', 'metaDescription']),
						'help'  => 'La description apparaît dans les partages sur les réseaux sociaux.'
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
				<div class="col3">
					<?php echo template::file('configFavicon', [
						'type' => 1,
						'help' => 'Pensez à supprimer le cache de votre navigateur si la favicon ne change pas.',
						'label' => 'Favicon thème clair',
						'value' => $this->getData(['config', 'favicon'])
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::file('configFaviconDark', [
						'type' => 1,
						'help' => 'Sélectionnez une icône adaptée à un thème sombre.<br>Pensez à supprimer le cache de votre navigateur si la favicon ne change pas.',
						'label' => 'Favicon thème sombre',
						'value' => $this->getData(['config', 'faviconDark'])
					]); ?>
				</div>				
				<div class="col6">
					<?php echo template::select('configItemsperPage', $module::$ItemsList, [
					'label' => 'Articles par page',
					'selected' => $this->getData(['config', 'itemsperPage']),
					'help' => 'Modules Blog et News'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::select('configTimezone', $module::$timezones, [
						'label' => 'Fuseau horaire',
						'selected' => $this->getData(['config', 'timezone']),
						'help' => 'Le fuseau horaire est utile au bon référencement'
					]); ?>	
				</div>
				<div class="col6">
					<?php  $listePageId =  array_merge(['' => 'Sélectionner'] , helper::arrayCollumn($this->getData(['page']), 'title', 'SORT_ASC') ); 
					?>
					<?php echo template::select('configLegalPageId', $listePageId , [
						'label' => 'Mentions légales',
						'selected' => $this->getData(['config', 'legalPageId']),
						'help' => 'Les mentions légales sont obligatoires en France'
					]); ?>
				</div>	
			</div>	
			<div class="row">			
				<div class="col6">
					<?php echo template::checkbox('configCookieConsent', true, 'Message de consentement aux cookies', [
						'checked' => $this->getData(['config', 'cookieConsent'])
					]); ?>
				</div>	
				<div class="col6">
					<?php echo template::checkbox('rewrite', true, 'Réécriture d\'URL', [
						'checked' => helper::checkRewrite(),
						'help' => 'Vérifiez d\'abord que votre serveur l\'autorise : ce n\'est pas le cas chez Free.'
					]); ?>
				</div>	
			</div>	
		</div>
	</div>	
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Réseaux sociaux</h4>
			<div class="row">
				<div class="col4">
					<?php echo template::text('configSocialFacebookId', [
						'help' => 'Saisissez votre ID : https://www.facebook.com/[ID].',
						'label' => 'Facebook',
						'value' => $this->getData(['config', 'social', 'facebookId'])
					]); ?>
				</div>
				<div class="col4">					
					<?php echo template::text('configSocialInstagramId', [
						'help' => 'Saisissez votre ID : https://www.instagram.com/[ID].',
						'label' => 'Instagram',
						'value' => $this->getData(['config', 'social', 'instagramId'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::text('configSocialTwitterId', [
						'help' => 'Saisissez votre ID : https://twitter.com/[ID].',
						'label' => 'Twitter',
						'value' => $this->getData(['config', 'social', 'twitterId'])
					]); ?>
				</div>				
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::text('configSocialYoutubeId', [
						'help' => 'ID de la chaîne : https://www.youtube.com/channel/[ID].',
						'label' => 'Chaîne Youtube',
						'value' => $this->getData(['config', 'social', 'youtubeId'])
					]); ?>
				</div>					
				<div class="col4">
					<?php echo template::text('configSocialYoutubeUserId', [
						'help' => 'Saisissez votre ID Utilisateur : https://www.youtube.com/user/[ID].',
						'label' => 'Youtube',
						'value' => $this->getData(['config', 'social', 'youtubeUserId'])
					]); ?>
				</div>										
				<div class="col4">
					<?php echo template::text('configSocialPinterestId', [
						'help' => 'Saisissez votre ID : https://pinterest.com/[ID].',
						'label' => 'Pinterest',
						'value' => $this->getData(['config', 'social', 'pinterestId'])
					]); ?>
				</div>															
			</div>
			<div class="row">			
				<div class="col4 offset2">
						<?php echo template::text('configSocialGithubId', [
							'help' => 'Saisissez votre ID Github : https://github.com/[ID].',
							'label' => 'Github',
							'value' => $this->getData(['config', 'social', 'githubId'])
						]); ?>
				</div>						
				<div class="col4">
					<?php echo template::text('configSocialLinkedinId', [
						'help' => 'Saisissez votre ID Linkedin : https://fr.linkedin.com/in/[ID].',
						'label' => 'Linkedin',
						'value' => $this->getData(['config', 'social', 'linkedinId'])
					]); ?>
				</div>						
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Sauvegardes</h4>	
			<div class="row">
				<div class="col6">
					<?php echo template::checkbox('configAutoBackup', true, 'Sauvegarde automatisée quotidienne partielle', [
							'checked' => $this->getData(['config', 'autoBackup']),
							'help' => '<p>Une archive contenant le dossier /site/data est copiée dans le dossier \'site/backup\'. La sauvegarde est conservée pendant 30 jours.</p><p>Le contenu du gestionnaire de fichiers n\'est pas sauvegardé.</p>'
					]); ?>						
				</div>
				<div class="col3">
					<?php echo template::button('configManageButtonBackup', [
						'href' => helper::baseUrl() . 'config/backup',
						'value' => 'Sauvegarde'
					]); ?>
				</div>	
				<div class="col3">
					<?php echo template::button('configManageButtonRestore', [
						'href' => helper::baseUrl() . 'config/restore',
						'value' => 'Restauration'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">				
					<?php echo template::checkbox('configMaintenance', true, 'Site en maintenance', [
						'checked' => $this->getData(['config', 'maintenance'])
					]); ?>	
				</div>			
			</div>		
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Mise à jour</h4>
			<?php $error = helper::urlGetContents('http://zwiicms.com/update/' . common::ZWII_UPDATE_CHANNEL . '/version');?>
			<?php if ($error !== false) : ?>
				<?php $error = true; ?>
			<?php endif;?>
			<div class="row">
				<div class="col12">
					<?php echo 'Vous disposez de ZwiiCMS version <strong>' . common::ZWII_VERSION . '</strong>' . '.'; ?>

					<?php if ($error): ?>
						<?php echo 'La version de la mise à jour en ligne est <strong>' . helper::urlGetContents('http://zwiicms.com/update/' . common::ZWII_UPDATE_CHANNEL . '/version') . '</strong>' . '.';?>
					<?php else: ?>						
						<p>Votre configuration interdit les mises à jour automatiques. <a href="https://zwiicms.com" target="_blank">Cliquez sur ce lien</a> pour connaître la version disponible en ligne.</p>
					<?php endif;?>					
				</div>	
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::checkbox('configAutoUpdate', true, 'Recherche de mise à jour automatisée ', [
							'checked' => $error ? $this->getData(['config', 'autoUpdate']) : false,
							'help' => 'Vérification de l\'existence d\'une mise à jour en ligne une fois par jour.',
							'disabled' => !$error
						]); ?>
				</div>			
				<div class="col3">
					<?php echo template::button('configUpdateForced', [
						'href' => helper::baseUrl() . 'install/update',
						'value' => 'Mise à jour forcée',
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
			<h4>Référencement</h4>
			<div class="row">
				<div class="col6">
					<div class="row">
						<div class="col10 offset1">
							<?php echo template::button('configMetaImage', [
								'href' => helper::baseUrl() . 'config/configMetaImage',
								'value' => 'Rafraîchir la capture d\'écran Open Graph'
							]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col10 offset1">
							<?php echo template::button('configSiteMap', [
								'href' => helper::baseUrl() . 'config/generateFiles',
								'value' => 'Rafraîchir sitemap.xml et robots.txt'
							]); ?>
						</div>
					</div>
				</div>
				<div class="col6 textAlignCenter">
					<img id="metaImage" src="<?php echo helper::baseUrl(false) . self::FILE_DIR.'source/screenshot.png';?>" data-tippy-content="Cette capture d'écran est nécessaire aux partages sur les réseaux sociaux. Elle est régénérée lorsque le fichier 'screenshot.png' est effacé du gestionnaire de fichiers." />
				</div>
			</div>
		</div>	
	</div>
</div>
<div class="row">
	<div class="col12">
		<?php 							
		// Lire le contenu des fichiers de script
		$headerFile = "";			
		if (file_exists( self::DATA_DIR . 'head.inc.html')) {
			$headerFile = file_get_contents (self::DATA_DIR . 'head.inc.html');
		}
		$bodyFile = "";
		if (file_exists( self::DATA_DIR . 'body.inc.html')) {
			$bodyFile = file_get_contents (self::DATA_DIR . 'body.inc.html');
		}
		?>
		<div class="block">
			<h4>Options avancées</h4>
			<div class="row">
				<div class="col3">
					<?php echo template::text('configAnalyticsId', [
						'help' => 'Saisissez l\'ID de suivi.',
						'label' => 'Google Analytics',
						'placeholder' => 'UA-XXXXXXXX-X',
						'value' => $this->getData(['config', 'analyticsId'])
					]); ?>
				</div>
			</div>	
			<div class="row">
				<div class="col6">
					<?php echo template::textarea('configScriptHead', [
						'label' => 'Insérer un script dans "Head"',
						'value' => $headerFile
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::textarea('configScriptBody', [
						'label' => 'Insérer un script dans "Body"',
						'value' => $bodyFile
					]); ?>
				</div>
			</div>
		</div>
	</div>		
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Paramètres réseaux</h4>
			<div class="row">	
				<div class="col2">
				<?php echo template::select('configProxyType', $module::$proxyType, [
					'label' => 'Type de proxy',
					'selected' => $this->getData(['config', 'proxyType'])
					]); ?>
				</div>	
				<div  class="col6">
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
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Versions système</h4>
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
<?php echo template::formClose(); ?>
