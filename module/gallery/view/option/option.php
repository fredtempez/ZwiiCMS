<?php echo template::formOpen('galleriesOptionForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('galleriesOptionBack', [
            'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left'),
            'class' => 'buttonGrey'
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('galleriesOptionSubmit'); ?>
    </div>
</div>
<?php if(gallery::$formOptionSelect === 'galleries'): ?>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Paramètres');?></h4>
            <div class="row">
                <div class="col12">
                    <?php echo template::checkbox('galleriesOptionShowUniqueGallery', true, 'Masquer l\'index des galeries lorsque le module ne contient qu\'une seule galerie' , [
                                'checked' => count($this->getData(['module', $this->getUrl(0), 'content'])) === 1
                                                ? $this->getData(['module', $this->getUrl(0), 'config', 'showUniqueGallery'])
                                                : false,
                                'disabled' => count($this->getData(['module', $this->getUrl(0), 'content'])) > 1,
                                'help' => 'Cette option est active lorsque le module ne contient qu\'une seule galerie, elle permet d\'éviter la page listant toutes les galeries et affiche directement la galerie'
                        ]); ?>
                </div>
            </div>
            <div class="row" id="containerBackOptions">
                <div class="col6">
                    <?php echo template::select('galleryOptionBackPosition', gallery::$galleryOptionBackPosition, [
                        'label' => 'Position du bouton de retour à l\'index des galeries',
                        'selected' =>  $this->getData(['module', $this->getUrl(0), 'config', 'showUniqueGallery']) === true
                                        ? 'none'
                                        : $this->getData(['module', $this->getUrl(0), 'config','backPosition']),
                        'disabled' => count($this->getData(['module', $this->getUrl(0), 'content'])) === 1
                                        ? $this->getData(['module', $this->getUrl(0), 'config', 'showUniqueGallery'])
                                        : false,
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('galleryOptionBackAlign', gallery::$galleryOptionBackAlign, [
                        'label' => 'Alignement du bouton de retour',
                        'selected' =>  $this->getData(['module', $this->getUrl(0), 'config', 'showUniqueGallery']) === true
                                        ? 'none'
                                        : $this->getData(['module', $this->getUrl(0), 'config','backAlign']),
                        'disabled' => count($this->getData(['module', $this->getUrl(0), 'content'])) === 1
                                        ? $this->getData(['module', $this->getUrl(0), 'config', 'showUniqueGallery'])
                                        : false,
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php elseif(gallery::$formOptionSelect === 'gallery'): ?>
    <div class="row">
		<div class="col12">
			<div class="block">
			<h4><?php echo helper::translate('Paramètres');?></h4>
			<div class="row">
				<div class="col6">
					<?php echo template::text('galleryEditName', [
						'label' => 'Nom',
						'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(3), 'config', 'name'])
					]); ?>
				</div>
				<div class="col6">
                    <div class="displayNone">
                    <?php echo template::hidden('galleryEditDirectoryOld', [
                        'value' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(3), 'config', 'directory']),
                        'noDirty' => true // Désactivé à cause des modifications en ajax
                    ]); ?>
					</div>
                    <?php echo template::select('galleryEditDirectory', [], [
                        'label' => 'Dossier cible',
                        'noDirty' => true // Désactivé à cause des modifications en ajax
                    ]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php echo template::select('galleryEditSort', gallery::$sort, [
						'selected' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(3), 'config', 'sort']),
						'label' => 'Tri des images',
						'help' => 'Tri manuel : déplacez le images dans le tableau ci-dessous. L\'ordre est sauvegardé automatiquement.'
					]); ?>
				</div>
				<div class="col7 verticalAlignBottom">
					<div class="row">
						<div class="col12">
								<?php echo template::checkbox('galleryEditFullscreen', true, 'Mode plein écran automatique' , [
										'checked' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(3), 'config', 'fullScreen']),
										'help' => 'A l\'ouverture de la galerie, la première image est affichée en plein écran.'
									]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col12">
								<?php echo template::checkbox('galleryEditShowPageContent', true, 'Afficher le contenu de la page avec la galerie' , [
										'checked' => $this->getData(['module', $this->getUrl(0), 'content', $this->getUrl(3), 'config', 'showPageContent']),
										'help' => 'Le contenu de la page est toujours affiché dans la liste des galeries. Quand une seule galerie est disponible, il est possible de l\'afficher directement, cette option est utile dans ce cas précis.'
									]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php echo template::formClose(); ?>
<div class="row">
    <div class="col12">
        <div class="moduleVersion">Version n°
            <?php echo gallery::VERSION; ?>
        </div>
    </div>
</div>