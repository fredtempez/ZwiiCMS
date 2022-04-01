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
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>Options</h4>
            <div class="row">
                <div class="col12">
                    <?php echo template::checkbox('galleriesOptionShowUniqueGallery', true, 'Masquer l\'index des galeries lorsque le module ne contient qu\'une galerie' , [
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
                    <?php echo template::select('galleryOptionBackPosition', $module::$galleryOptionBackPosition, [
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
                    <?php echo template::select('galleryOptionBackAlign', $module::$galleryOptionBackAlign, [
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
<?php echo template::formClose(); ?>
<div class="row">
    <div class="col12">
        <div class="moduleVersion">Version n°
            <?php echo $module::VERSION; ?>
        </div>
    </div>
</div>