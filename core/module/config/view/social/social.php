<div id="socialContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php echo helper::translate('Capture d\'écran Open Graph'); ?>
					<!--<span id="specialeHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/referencement" target="_blank" title="Cliquer pour consulter l'aide en ligne">
							<?php //echo template::ico('help', ['margin' => 'left']); ?>
						</a>
					</span>-->
				</h4>
				<div class="row">
					<div class="col7">
						<div class="row">
							<div class="col12">
								<?php echo template::text('seoKeyApi', [
									'label' => 'Clé de l\'API <a href="https://app.screenshotapi.net/" target="_blank">ScreenShotApi</a>',
									'value' => $this->getData(['config', 'seo', 'keyApi']),
									'help' => 'Créez un compte gratuit, recopier la clé , puis valider le formulaire avant de cliquer sur le bouton de génération'
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col6 offset3">
								<?php echo template::button('socialMetaImage', [
									'href' => helper::baseUrl() . 'config/configMetaImage',
									'value' => 'Générer une capture Open Graph'
								]); ?>
							</div>
						</div>
					</div>
					<div class="col5">
						<?php if (file_exists(self::FILE_DIR . 'source/screenshot.jpg')): ?>
							<div class="row">
								<div class="col8 offset2 textAlignCenter">
									<img src="<?php echo helper::baseUrl(false) . self::FILE_DIR . 'source/screenshot.jpg'; ?>"
										data-tippy-content="Cette capture d'écran est nécessaire aux partages sur les réseaux sociaux. Elle est régénérée lorsque le fichier 'screenshot.jpg' est effacé du gestionnaire de fichiers." />
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php echo helper::translate('Référencement'); ?>
				</h4>
				<div class="row">
					<div class="col4 offset1">
						<?php echo template::button('socialSiteMap', [
							'href' => helper::baseUrl() . 'config/sitemap',
							'value' => 'Générer sitemap.xml et robots.txt'
						]); ?>
					</div>
					<div class="col4 offset1">
						<?php echo template::checkbox('seoRobots', true, 'Autoriser les robots à référencer le site', [
							'checked' => $this->getData(['config', 'seo', 'robots'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php echo helper::translate('Réseaux sociaux'); ?>
					<!--<span id="specialeHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/reseaux-sociaux" target="_blank" title="Cliquer pour consulter l'aide en ligne">
							<?php //echo template::ico('help', ['margin' => 'left']); ?>
						</a>
					</span>-->
				</h4>
				<div class="row">
					<div class="col3">
						<?php echo template::text('socialFacebookId', [
							'help' => 'Saisissez votre ID : https://www.facebook.com/[ID].',
							'label' => template::ico('facebook', ['margin' => 'right']) . 'Facebook',
							'value' => $this->getData(['config', 'social', 'facebookId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialInstagramId', [
							'help' => 'Saisissez votre ID : https://www.instagram.com/[ID].',
							'label' => template::ico('instagram', ['margin' => 'right']) . 'Instagram',
							'value' => $this->getData(['config', 'social', 'instagramId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialTwitterId', [
							'help' => 'Saisissez votre ID : https://twitter.com/[ID].',
							'label' => template::ico('twitter', ['margin' => 'right']) . 'Twitter',
							'value' => $this->getData(['config', 'social', 'twitterId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialRedditId', [
							'help' => 'Saisissez votre ID Reddit : https://www.reddit.com/user/[ID].',
							'label' => template::ico('reddit', ['margin' => 'right']) . 'Reddit',
							'value' => $this->getData(['config', 'social', 'redditId'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
						<?php echo template::text('socialYoutubeId', [
							'help' => 'ID de la chaîne : https://www.youtube.com/channel/[ID].',
							'label' => template::ico('youtube', ['margin' => 'right']) . 'Chaîne Youtube',
							'value' => $this->getData(['config', 'social', 'youtubeId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialYoutubeUserId', [
							'help' => 'Saisissez votre ID Utilisateur : https://www.youtube.com/user/[ID].',
							'label' => template::ico('youtube', ['margin' => 'right']) . 'Youtube',
							'value' => $this->getData(['config', 'social', 'youtubeUserId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialVimeoId', [
							'help' => 'Saisissez votre ID Viemo : https://vimeo.com/[ID].',
							'label' => template::ico('vimeo', ['margin' => 'right']) . 'Vimeo',
							'value' => $this->getData(['config', 'social', 'vimeoId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialPinterestId', [
							'help' => 'Saisissez votre ID : https://pinterest.com/[ID].',
							'label' => template::ico('pinterest', ['margin' => 'right']) . 'Pinterest',
							'value' => $this->getData(['config', 'social', 'pinterestId'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
						<?php echo template::text('socialLinkedinId', [
							'help' => 'Saisissez votre ID Linkedin : https://fr.linkedin.com/in/[ID].',
							'label' => template::ico('linkedin', ['margin' => 'right']) . 'Linkedin',
							'value' => $this->getData(['config', 'social', 'linkedinId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialGithubId', [
							'help' => 'Saisissez votre ID Github : https://github.com/[ID].',
							'label' => template::ico('github', ['margin' => 'right']) . 'Github',
							'value' => $this->getData(['config', 'social', 'githubId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialTwitchId', [
							'help' => 'Saisissez votre ID Twitch : https://www.twitch.tv/[ID].',
							'label' => template::ico('twitch', ['margin' => 'right']) . 'Twitch',
							'value' => $this->getData(['config', 'social', 'twitchId'])
						]); ?>
					</div>

					<div class="col3">
						<?php echo template::text('socialSteamId', [
							'help' => 'Saisissez votre ID Viemo : https://steamcommunity.com/id/[ID].',
							'label' => template::ico('steam', ['margin' => 'right']) . 'Steam',
							'value' => $this->getData(['config', 'social', 'steamId'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>