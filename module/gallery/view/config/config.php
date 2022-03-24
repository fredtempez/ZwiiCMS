<?php echo template::formOpen('galleryConfigForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('galleryConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::button('galleryConfigBack', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/theme/' . $_SESSION['csrf'],
				'value' => template::ico('brush','right') . 'Thème'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Ajouter une galerie</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::text('galleryConfigName', [
							'label' => 'Nom'
						]); ?>
					</div>
					<div class="col5">
						<?php echo template::hidden('galleryConfigDirectoryOld', [
							'noDirty' => true // Désactivé à cause des modifications en ajax
						]); ?>
						<?php echo template::select('galleryConfigDirectory', [], [
							'label' => 'Dossier cible',
							'noDirty' => true // Désactivé à cause des modifications en ajax
						]); ?>
					</div>
					<div class="col1 verticalAlignBottom">
						<?php echo template::submit('galleryConfigSubmit', [
							'ico' => '',
							'value' => template::ico('plus'),
							'class' => 'gallerySubmit'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
						<?php echo template::select('galleryConfigSort', $module::$sort, [
							'selected' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'sort']),
							'label' => 'Tri des images',
							'help' => 'Tri manuel : déplacez le images dans le tableau ci-dessous. L\'ordre est sauvegardé automatiquement.'
						]); ?>
					</div>
					<div class="col4 verticalAlignBottom">
						<?php echo template::checkbox('galleryConfigFullscreen', true, 'Mode plein écran automatique' , [
								'checked' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'fullScreen']),
								'help' => 'A l\'ouverture de la galerie, la première image est affichée en plein écran.'
							]); ?>
					</div>
					<div class="col4 verticalAlignBottom">
						<?php echo template::checkbox('galleryConfigShowPageContent', true, 'Contenu de la page dans la galerie' , [
								'checked' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'showPageContent']),
								'help' => 'Le contenu de la page est toujours affiché dans la liste des galeries. Quand une seule galerie est disponible, il est possible de l\'afficher directement, cette option est utile dans ce cas précis.'
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
<div class="row">
	<div class="col12">
		<?php if($module::$galleries): ?>
		<?php echo template::table([1, 4, 5, 1, 1], $module::$galleries, ['','Nom', 'Dossier cible', '', ''], ['id' => 'galleryTable'],$module::$galleriesId); ?>
		<?php echo template::hidden('galleryConfigFilterResponse'); ?>
		<?php else: ?>
			<?php echo template::speech('Aucune galerie.'); ?>
		<?php endif; ?>
	</div>
	<div class="moduleVersion">Version n°
		<?php echo $module::VERSION; ?>
	</div>
</div>
