<?php echo template::formOpen('downloadConfig'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('downloadConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0), 'items',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block" id="params">
				<h4>Paramètres du module
					<div class="openClose">
						<?php
						echo template::ico('plus-circled','right');
						echo template::ico('minus-circled','right');
						?>
					</div>
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
								'label' => 'Etiquette du flux',
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
					<div class="row">
						<div class="col2 offset10">
							<?php echo template::submit('downloadConfigSubmit'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
<div class="row">
	<div class="col2">
	<?php echo template::button('downloadConfigCategories', [
		'href' => helper::baseUrl() . $this->getUrl(0) . '/categories',
		'value' => 'Catégories'
	]); ?>
	</div>
	<div class="col2 offset8">
		<?php echo template::button('downloadConfigAdd', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/add',
			'ico' => 'plus',
			'value' => 'Ressource'
		]); ?>
	</div>
</div>
<?php if($module::$items): ?>
	<?php echo template::table([2,2, 1, 2, 1, 1, 1, 1, 1], $module::$items, ['Titre', 'Catégorie ' . $module::$allCategories, 'Version', 'Du', 'Stats', 'État', 'Comm', '','']); ?>
	<?php echo $module::$pages; ?>
<?php else: ?>
	<?php echo template::speech('Aucune ressource'); ?>
<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>

