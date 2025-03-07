<?php echo template::formOpen('downloadConfig'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('downloadConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col2 offset9">
			<?php echo template::submit('downloadConfigSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Paramètres
				</h4>
				<div class="blockContainer">
					<div class="row">
						<div class="col4">
							<?php echo template::checkbox('downloadConfigShowFeeds', true, 'Lien du flux RSS', [
								'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'feeds']),
							]); ?>
						</div>
						<div class="col4">
							<?php echo template::text('downloadConfigFeedslabel', [
								'label' => 'Étiquette du flux',
								'value' => $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel'])
							]); ?>
						</div>
						<div class="col4">
							<?php echo template::select('blogConfigItemsperPage', $module::$ItemsList, [
								'label' => 'Articles par page',
								'selected' => $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage'])
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>

