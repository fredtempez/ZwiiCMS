<?php echo template::formOpen('galleriesOptionForm'); ?>
<div class="row">
    <div class="col2">
        <?php echo template::button('galleriesOptionBack', [
            'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
            'ico' => 'left',
            'value' => 'Retour'
        ]); ?>
    </div>
    <div class="col2 offset8">
        <?php echo template::submit('galleriesOptionSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>Galerie unique</h4>
            <div class="row">
                <div class="col12 verticalAlignBottom">
                    <?php echo template::checkbox('galleriesOptionShowUniqueGallery', true, 'Masquer l\'index des galeries lorsque le module ne contient qu\'une galerie' , [
                                'checked' => count($this->getData(['module', $this->getUrl(0), 'content'])) === 1
                                                ? $this->getData(['module', $this->getUrl(0), 'theme', 'showUniqueGallery'])
                                                : false,
                                'disabled' => count($this->getData(['module', $this->getUrl(0), 'content'])) > 1,
                                'help' => 'Cette option est active lorsque le module ne contient qu\'une seule galerie, elle permet d\'éviter la page listant toutes les galeries et affiche directement la galerie'
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