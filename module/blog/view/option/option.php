<?php echo template::formOpen('blogOption'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('blogOptionBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col2 offset9">
			<?php echo template::submit('blogOptionSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Index des articles</h4>
				<div class="row">
					<div class="col6">
					<?php echo template::select('blogOptionArticlesLenght', $module::$articlesLenght, [
							'label' => 'Disposition',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'articlesLenght'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::select('blogOptionItemsperPage', $module::$ArticlesListed, [
							'label' => 'Articles par page',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'itemsperPage'])
						]); ?>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Flux RSS</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('blogOptionShowFeeds', true, 'Lien du flux RSS', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'feeds']),
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('blogOptionFeedslabel', [
							'label' => 'Texte de l\'étiquette',
							'value' => $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>

