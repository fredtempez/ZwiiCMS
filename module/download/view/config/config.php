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
		<div class="col2 offset6">
			<?php echo template::button('downloadConfigAdd', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/add',
				'ico' => 'plus',
				'value' => 'Fichier'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('downloadConfigSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Paramètres du module</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('downloadConfigShowFeeds', true, 'Lien du flux RSS', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'feeds']),
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('downloadConfigFeedslabel', [
							'label' => 'Etiquette du flux',
							'value' => $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6 offset6">
						<?php echo template::select('blogConfigItemsperPage', $module::$ItemsList, [
							'label' => 'Articles par page',
							'selected' => $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
<?php if($module::$items): ?>
	<?php echo template::table([3, 1, 3, 1, 1, 1, 1, 1], $module::$items, ['Titre', 'Version', 'Du', 'Stats', 'État', 'Commentaires', '','']); ?>
	<?php echo $module::$pages; ?>
<?php else: ?>
	<?php echo template::speech('Aucun item.'); ?>
<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>

