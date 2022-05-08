<div id="socialContainer">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Paramètres
					<span id="specialeHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/referencement" target="_blank"  title="Cliquer pour consulter l'aide en ligne">
							<?php echo template::ico('help', 'left');?>
						</a>
					</span>
				</h4>
				<div class="row">
					<div class="col4 offset1">
						<div class="row">
							<div class="col12">
								<?php echo template::button('socialMetaImage', [
								'href' => helper::baseUrl() . 'config/configMetaImage',
								'value' => 'Générer une capture Open Graph'

								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col12">
								<?php echo template::button('socialSiteMap', [
									'href' => helper::baseUrl() . 'config/siteMap',
									'value' => 'Générer sitemap.xml et robots.txt'
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col12">
								<?php echo template::checkbox('seoRobots', true, 'Autoriser les robots à référencer le site', [
									'checked' => $this->getData(['config', 'seo','robots'])
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
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Réseaux sociaux
					<span id="specialeHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/reseaux-sociaux" target="_blank"  title="Cliquer pour consulter l'aide en ligne">
							<?php echo template::ico('help', 'left');?>
						</a>
					</span>
				</h4>
				<div class="row">
					<div class="col3">
						<?php echo template::text('socialFacebookId', [
							'help' => 'Saisissez votre ID : https://www.facebook.com/[ID].',
							'label' => 'Facebook',
							'value' => $this->getData(['config', 'social', 'facebookId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialInstagramId', [
							'help' => 'Saisissez votre ID : https://www.instagram.com/[ID].',
							'label' => 'Instagram',
							'value' => $this->getData(['config', 'social', 'instagramId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialYoutubeId', [
							'help' => 'ID de la chaîne : https://www.youtube.com/channel/[ID].',
							'label' => 'Chaîne Youtube',
							'value' => $this->getData(['config', 'social', 'youtubeId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialYoutubeUserId', [
							'help' => 'Saisissez votre ID Utilisateur : https://www.youtube.com/user/[ID].',
							'label' => 'Youtube',
							'value' => $this->getData(['config', 'social', 'youtubeUserId'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
							<?php echo template::text('socialTwitterId', [
								'help' => 'Saisissez votre ID : https://twitter.com/[ID].',
								'label' => 'Twitter',
							'value' => $this->getData(['config', 'social', 'twitterId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialPinterestId', [
							'help' => 'Saisissez votre ID : https://pinterest.com/[ID].',
							'label' => 'Pinterest',
							'value' => $this->getData(['config', 'social', 'pinterestId'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('socialLinkedinId', [
							'help' => 'Saisissez votre ID Linkedin : https://fr.linkedin.com/in/[ID].',
							'label' => 'Linkedin',
							'value' => $this->getData(['config', 'social', 'linkedinId'])
						]); ?>
					</div>
					<div class="col3">
							<?php echo template::text('socialGithubId', [
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
