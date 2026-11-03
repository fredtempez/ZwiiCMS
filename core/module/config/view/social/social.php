<div id="socialContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Référencement'); ?>
				</h4>
				<div class="row">
					<div class="col4">
						<?php
echo Template::button('socialSiteMap', [ 'href' => Helper::baseUrl() . 'config/sitemap', 'value' => 'Générer sitemap.xml, robots.txt et llms.txt' ]); ?>
					</div>
					<div class="col4">
						<?php
echo Template::checkbox('seoRobots', true, 'Autoriser les robots à référencer le site', [ 'checked' => $this->getData(['config', 'seo', 'robots']) ]); ?>
					</div>
					<div class="col4">
						<?php
echo Template::checkbox('seoLlmsTxt', true, 'Autoriser les IA à consulter le site', [ 'checked' => $this->getData(['config', 'seo', 'llms']), 'help' => 'Le fichier llms.txt décrit le site et ses pages aux modèles de langage (IA). Régénéré avec le sitemap et à chaque enregistrement de la configuration. Décoché, le fichier llms.txt est remplacé par une interdiction explicite.' ]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
				</h4>
				<div class="row">
					<div class="col3">
						<?php
echo Template::text('socialFacebookId', [ 'help' => 'Saisissez votre ID : https://www.facebook.com/[ID].', 'label' => Template::ico('facebook', ['margin' => 'right']) . 'Facebook', 'value' => $this->getData(['config', 'social', 'facebookId']) ]); ?>
					</div>
					<div class="col3">
						<?php
echo Template::text('socialInstagramId', [ 'help' => 'Saisissez votre ID : https://www.instagram.com/[ID].', 'label' => Template::ico('instagram', ['margin' => 'right']) . 'Instagram', 'value' => $this->getData(['config', 'social', 'instagramId']) ]); ?>
					</div>
					<div class="col3">
						<?php
echo Template::text('socialTwitterId', [ 'help' => 'Saisissez votre ID : https://twitter.com/[ID].', 'label' => Template::ico('twitter', ['margin' => 'right']) . 'Twitter', 'value' => $this->getData(['config', 'social', 'twitterId']) ]); ?>
					</div>
					<div class="col3">
						<?php
echo Template::text('socialRedditId', [ 'help' => 'Saisissez votre ID Reddit : https://www.reddit.com/user/[ID].', 'label' => Template::ico('reddit', ['margin' => 'right']) . 'Reddit', 'value' => $this->getData(['config', 'social', 'redditId']) ]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
						<?php
echo Template::text('socialYoutubeId', [ 'help' => 'ID de la chaîne : https://www.youtube.com/channel/[ID].', 'label' => Template::ico('youtube', ['margin' => 'right']) . 'Chaîne Youtube', 'value' => $this->getData(['config', 'social', 'youtubeId']) ]); ?>
					</div>
					<div class="col3">
						<?php
echo Template::text('socialYoutubeUserId', [ 'help' => 'Saisissez votre ID Utilisateur : https://www.youtube.com/user/[ID].', 'label' => Template::ico('youtube', ['margin' => 'right']) . 'Youtube', 'value' => $this->getData(['config', 'social', 'youtubeUserId']) ]); ?>
					</div>
					<div class="col3">
						<?php
echo Template::text('socialVimeoId', [ 'help' => 'Saisissez votre ID Viemo : https://vimeo.com/[ID].', 'label' => Template::ico('vimeo', ['margin' => 'right']) . 'Vimeo', 'value' => $this->getData(['config', 'social', 'vimeoId']) ]); ?>
					</div>
					<div class="col3">
						<?php
echo Template::text('socialPinterestId', [ 'help' => 'Saisissez votre ID : https://pinterest.com/[ID].', 'label' => Template::ico('pinterest', ['margin' => 'right']) . 'Pinterest', 'value' => $this->getData(['config', 'social', 'pinterestId']) ]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
						<?php
echo Template::text('socialLinkedinId', [ 'help' => 'Saisissez votre ID Linkedin : https://fr.linkedin.com/in/[ID].', 'label' => Template::ico('linkedin', ['margin' => 'right']) . 'Linkedin', 'value' => $this->getData(['config', 'social', 'linkedinId']) ]); ?>
					</div>
					<div class="col3">
						<?php
echo Template::text('socialGithubId', [ 'help' => 'Saisissez votre ID Github : https://github.com/[ID].', 'label' => Template::ico('github', ['margin' => 'right']) . 'Github', 'value' => $this->getData(['config', 'social', 'githubId']) ]); ?>
					</div>
					<div class="col3">
						<?php
echo Template::text('socialTwitchId', [ 'help' => 'Saisissez votre ID Twitch : https://www.twitch.tv/[ID].', 'label' => Template::ico('twitch', ['margin' => 'right']) . 'Twitch', 'value' => $this->getData(['config', 'social', 'twitchId']) ]); ?>
					</div>

					<div class="col3">
						<?php
echo Template::text('socialSteamId', [ 'help' => 'Saisissez votre ID Viemo : https://steamcommunity.com/id/[ID].', 'label' => Template::ico('steam', ['margin' => 'right']) . 'Steam', 'value' => $this->getData(['config', 'social', 'steamId']) ]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
		<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Capture d\'écran Open Graph'); ?>
				</h4>
				<div class="row">
					<div class="col6">
						<div class="row">
							<div class="col12">
								<?php
echo Template::file('seoOpenGraphImage', [ 'language' => $this->getData(['user', $this->getUser('id'), 'language']), 'label' => 'Image Open Graph', 'value' => $this->getData(['config', 'seo', 'openGraphImage']), 'folder' => $this->getData(['config', 'seo', 'openGraphImage']) ? dirname($this->getData(['config', 'seo', 'openGraphImage'])) : '', 'type' => 1, 'help' => sprintf('%s : JPG - PNG<br />', Helper::translate('Format')) . sprintf('%s : 1200 x 630 pixels<br />', Helper::translate('Dimensions minimales')) . sprintf('%s : 1.91:1<br />', Helper::translate('Ratio')) . sprintf('%s : %s, %s<br />', Helper::translate('Taille maximale du fichier'), Helper::translate('5 Mo pour les images JPEG'), Helper::translate('1 Mo pour les images PNG')) ]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col10 textAlignCenter">
								<?php
if (!empty(config::$imageOpenGraph['type'])): ?>
									<p>
										<?php
echo sprintf('%s : <span id="screenType">%s</span>', Helper::translate('Format'), config::$imageOpenGraph['type']); ?>
									</p>
									<p>
										<?php
echo sprintf('%s : <span id="screenWide">%s</span> x <span id="screenHeight">%s</span> pixels', Helper::translate('Dimensions minimales'), config::$imageOpenGraph['wide'], config::$imageOpenGraph['height']); ?>
									</p>
									<p>
										<?php
echo sprintf('%s : <span id="screenRatio">%s</span><span id="screenFract">:1</span>', Helper::translate('Ratio'), round(config::$imageOpenGraph['ratio'], 2)); ?>
									</p>
									<p>
										<?php
echo sprintf('%s : <span id="screenWeight">%s</span>', Helper::translate('Poids'), config::$imageOpenGraph['size']); ?>
									</p>
								<?php
endif; ?>
							</div>
						</div>
					</div>
					<div class="col6">
						<?php
$openGraphImagePath = $this->getData(['config', 'seo', 'openGraphImage']); $normalizedOpenGraphImagePath = $this->getNormalizedFilePath($openGraphImagePath, $this->getUser('id')); if (!empty($openGraphImagePath) && !empty($normalizedOpenGraphImagePath) && file_exists($normalizedOpenGraphImagePath)): ?>
							<img
								src="<?php
echo $normalizedOpenGraphImagePath; ?>" />
						<?php
endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
