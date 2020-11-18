<?php echo template::formOpen('blogConfig'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('blogConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0), 'posts',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col3 offset3">
			<?php echo template::button('blogConfigComment', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/comment',
				'value' => 'Gérer les commentaires'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::button('blogConfigAdd', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/add',
				'ico' => 'plus',
				'value' => 'Article'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('blogConfigSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Paramètres du module</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('blogConfigShowFeeds', true, 'Lien du flux RSS', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'feeds']),
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('blogConfigFeedslabel', [
							'label' => 'Texte de l\'étiquette',
							'value' => $this->getData(['module', $this->getUrl(0), 'config', 'feedsLabel'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if($module::$articles): ?>
		<?php echo template::table([4, 4, 2, 1, 1], $module::$articles, ['Titre', 'Date de publication', 'État', '', '']); ?>
		<?php echo $module::$pages; ?>
	<?php else: ?>
		<?php echo template::speech('Aucun article.'); ?>
	<?php endif; ?>
	<?php echo template::formClose(); ?>
	<div class="moduleVersion">Version n°
		<?php echo $module::BLOG_VERSION; ?>
	</div>