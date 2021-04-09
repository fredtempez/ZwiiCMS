<?php echo template::formOpen('newsConfig'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('newsConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),'posts',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset6">
			<?php echo template::button('newsConfigAdd', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/add',
				'ico' => 'plus',
				'value' => 'News'
			]); ?>
		</div>
		<div class="col2">
				<?php echo template::submit('newsConfigSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Paramètres du module</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('newsConfigShowFeeds', true, 'Lien du flux RSS', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'feeds']),
							'help' => 'Flux limité aux articles de la première page.'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('newsConfigFeedslabel', [
							'label' => 'Etiquette RSS',
							'value' => $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel'])
						]); ?>
					</div>
				</div>
				<!-- Block ajouté pour le sélecteur -->	
				<div class="row">
					<div class="col4">
						<?php echo template::select('newsConfigItemsperCol', $module::$Columns, [
							'label' => 'Pagination',
							'selected' => $this->getData(['module', $this->getUrl(0),'config', 'itemsperCol']),
							'help' => 'Nombre de colonnes par page'
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('newsConfigItemsperPage', $module::$ItemsList, [
							'label' => 'Articles par page',
							'selected' => $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::select('newsConfigItemsHeight', $module::$ItemsHeight, [
							'label' => 'Hauteur',
							'selected' => $this->getData(['module', $this->getUrl(0),'theme', 'itemsHeight'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if($module::$news): ?>
		<?php echo template::table([4, 4, 2, 1, 1], $module::$news, ['Titre', 'Date de publication', 'État', '', '']); ?>
		<?php echo $module::$pages; ?>
	<?php else: ?>
		<?php echo template::speech('Aucune news.'); ?>
	<?php endif; ?>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>