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
								'label' => 'Texte de l\'étiquette',
								'value' => $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel'])
							]); ?>
						</div>
					</div>
				</div>
			</div>
	</div>
<!-- Block ajouté pour le sélecteur -->	
<div class="block">
	<h4>Nombre de news par page</h4>
	<div class="row">
		<div class="col4">
			<?php echo template::select('newsConfigItemsperPage', $module::$ItemsList, [
				'label' => 'News par page',
				'selected' => $this->getData(['module', $this->getUrl(0),'config', 'itemsperPage']),
				'help' => 'Nombre de news par page'
			]); ?>
		</div>
		<div class="col4">
			<?php echo template::select('newsConfigListeCol', $module::$ListeCol, [
				'label' => 'Nombre de colonne',
				'selected' => $this->getData(['module', $this->getUrl(0),'config', 'listeCol']),
				'help' => 'Nombre de colonnes par page'
			]); ?>
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