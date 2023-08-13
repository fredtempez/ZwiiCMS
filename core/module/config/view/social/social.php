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
					<div class="col6">
						<div class="row">
							<div class="col12">
								<?php echo template::file('seoOpenGraphImage', [
									'language' => $this->getData(['user', $this->getUser('id'), 'language']),
									'label' => 'Image Open Graph',
									'value' => $this->getData(['config', 'seo', 'openGraphImage']),
									'type' => 1,
									'help' =>  sprintf('%s : JPG - PNG<br />', helper::translate('Format')) .
									 sprintf('%s : 1200 x 630 pixels<br />', helper::translate('Dimensions minimales')) .
									 sprintf('%s : 1.91:1<br />', helper::translate('Ratio')) .
									 sprintf('%s : %s, %s<br />', helper::translate('Taille maximale du fichier'), helper::translate('5 Mo pour les images JPEG'), helper::translate('1 Mo pour les images PNG'))
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col10 textAlignCenter">
								<?php if( $module::$imageOpenGraph['type']): ?>
								<p>
									<?php echo sprintf('%s : <span id="screenType">%s</span>', helper::translate('Format'), $module::$imageOpenGraph['type']); ?>
								</p>
								<p>
									<?php echo sprintf('%s : <span id="screenWide">%s</span> x <span id="screenHeight">%s</span> pixels', helper::translate('Dimensions minimales'), $module::$imageOpenGraph['wide'], $module::$imageOpenGraph['height'] ); ?>
								</p>
								<p>
									<?php echo sprintf('%s : <span id="screenRatio">%s</span><span id="screenFract">:1</span>' , helper::translate('Ratio'), round($module::$imageOpenGraph['ratio'], 2)); ?>
								</p>
								<p>
									<?php echo sprintf('%s : <span id="screenWeight">%s</span>', helper::translate('Poids'), $module::$imageOpenGraph['size']); ?>
								</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="col6">
						<?php if (
							$this->getData(['config', 'seo', 'openGraphImage']) &&
							file_exists(self::FILE_DIR .  'source/' . $this->getData(['config', 'seo', 'openGraphImage']))
						): ?>
							<img
								src="<?php echo self::FILE_DIR .  'source/' . $this->getData(['config', 'seo', 'openGraphImage']); ?>" />
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