<?php echo template::formOpen('galleryEditForm'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('galleryEditBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col2 offset9">
			<?php echo template::submit('galleryEditSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>Paramètres de la galerie</h4>
			<div class="row">
				<div class="col6">
					<?php echo template::text('galleryEditName', [
						'label' => 'Nom',
						'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'name'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::hidden('galleryEditDirectoryOld', [
						'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'directory']),
						'noDirty' => true // Désactivé à cause des modifications en ajax
					]); ?>
					<?php echo template::select('galleryEditDirectory', [], [
						'label' => 'Dossier cible',
						'noDirty' => true // Désactivé à cause des modifications en ajax
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php echo template::select('galleryEditSort', $module::$sort, [
						'selected' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'sort']),
						'label' => 'Tri des images',
						'help' => 'Tri manuel : déplacez le images dans le tableau ci-dessous. L\'ordre est sauvegardé automatiquement.'
					]); ?>
				</div>
				<div class="col4 verticalAlignBottom">
					<?php echo template::checkbox('galleryEditFullscreen', true, 'Mode plein écran automatique' , [
							'checked' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'fullScreen']),
							'help' => 'A l\'ouverture de la galerie, la première image est affichée en plein écran.'
						]); ?>
				</div>
				<div class="col4 verticalAlignBottom">
					<?php echo template::checkbox('galleryEditShowPageContent', true, 'Contenu de la page dans la galerie' , [
							'checked' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'showPageContent']),
							'help' => 'Le contenu de la page est toujours affiché dans la liste des galeries. Quand une seule galerie est disponible, il est possible de l\'afficher directement, cette option est utile dans ce cas précis.'
						]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php if($module::$pictures): ?>
						<?php echo template::table([1, 4, 1, 5, 1], $module::$pictures, ['','Image', 'Couverture','Légende',''],['id' => 'galleryTable'], $module::$picturesId ); ?>
						<?php echo template::hidden('galleryEditFormResponse'); ?>
						<?php echo template::hidden('galleryEditFormGalleryName',['value' => $this->getUrl(2)]); ?>
					<?php else: ?>
						<?php echo template::speech('Aucune image.'); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="moduleVersion">Version n°
		<?php echo $module::VERSION; ?>
	</div>
<?php echo template::formClose(); ?>
