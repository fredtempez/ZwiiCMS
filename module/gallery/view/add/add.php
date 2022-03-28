<?php echo template::formOpen('galleryAddForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('galleryAddBack', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config' ,
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
            <?php echo template::submit('galleryAddSubmit', [
                'ico' => 'plus',
                'value' => ' Ajouter',
                'class' => 'gallerySubmit'
            ]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Ajouter une galerie</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::text('galleryAddName', [
							'label' => 'Nom'
						]); ?>
					</div>
					<div class="col5">
						<?php echo template::hidden('galleryAddDirectoryOld', [
							'noDirty' => true // Désactivé à cause des modifications en ajax
						]); ?>
						<?php echo template::select('galleryAddDirectory', [], [
							'label' => 'Dossier cible',
							'noDirty' => true // Désactivé à cause des modifications en ajax
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col3">
						<?php echo template::select('galleryAddSort', $module::$sort, [
							'selected' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'sort']),
							'label' => 'Tri des images',
							'help' => 'Tri manuel : déplacez le images dans le tableau ci-dessous. L\'ordre est sauvegardé automatiquement.'
						]); ?>
					</div>
					<div class="col7 verticalAlignBottom">
						<div class="row">
							<div class="col12">
								<?php echo template::checkbox('galleryAddFullscreen', true, 'Mode plein écran automatique' , [
										'checked' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'fullScreen']),
										'help' => 'A l\'ouverture de la galerie, la première image est affichée en plein écran.'
									]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col12">
								<?php echo template::checkbox('galleryAddShowPageContent', true, 'Afficher le contenu de la page dans les galeries' , [
										'checked' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(2), 'config', 'showPageContent']),
										'help' => 'Le contenu de la page est toujours affiché dans la liste des galeries. Quand une seule galerie est disponible, il est possible de l\'afficher directement, cette option est utile dans ce cas précis.'
									]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
