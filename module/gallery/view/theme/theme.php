<?php echo template::formOpen('galleryThemeForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('galleryThemeBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('galleryThemeBack'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Vignettes');?></h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::select('galleryThemeThumbWidth', $module::$galleryThemeSizeWidth, [
                        'label' => 'Largeur',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'theme','thumbWidth'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('galleryThemeThumbHeight', $module::$galleryThemeSizeHeight, [
                        'label' => 'Hauteur',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'theme','thumbHeight'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('galleryThemeThumbAlign', $module::$galleryThemeFlexAlign, [
                        'label' => 'Alignement',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'theme','thumbAlign'])
                    ]); ?>
                </div>
                <div class="col2">
                    <?php echo template::select('galleryThemeThumbMargin', $module::$galleryThemeMargin, [
                        'label' => 'Marge',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'theme','thumbMargin'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::select('galleryThemeThumbBorder', $module::$galleryThemeBorder, [
                        'label' => 'Bordure',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'theme','thumbBorder'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::text('galleryThemeThumbBorderColor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',
                        'label' => 'Couleur de la bordure',
                        'value' => $this->getData(['module', $this->getUrl(0), 'theme','thumbBorderColor'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('galleryThemeThumbRadius', $module::$galleryThemeRadius, [
                        'label' => 'Arrondi des angles',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'theme','thumbRadius'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::select('galleryThemeThumbShadows', $module::$galleryThemeShadows, [
                        'label' => 'Ombre',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'theme','thumbShadows'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::text('galleryThemeThumbShadowsColor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',
                        'label' => 'Couleur de l\'ombre',
                        'value' => $this->getData(['module', $this->getUrl(0), 'theme','thumbShadowsColor'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('galleryThemeThumbOpacity', $module::$galleryThemeOpacity, [
                        'label' => 'Opacité au survol',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'theme','thumbOpacity'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
        <h4><?php echo helper::translate('Légendes');?></h4>
        <div class="row">
            <div class="col3">
                <?php echo template::text('galleryThemeLegendTextColor', [
                    'class' => 'colorPicker',
                    'help' => 'Le curseur horizontal règle le niveau de transparence.',
                    'label' => 'Texte',
                    'value' => $this->getData(['module', $this->getUrl(0), 'theme','legendTextColor'])
                ]); ?>
            </div>
            <div class="col3">
                <?php echo template::text('galleryThemeLegendBgColor', [
                    'class' => 'colorPicker',
                    'help' => 'Le curseur horizontal règle le niveau de transparence.',
                    'label' => 'Arrière plan',
                    'value' => $this->getData(['module', $this->getUrl(0), 'theme','legendBgColor'])
                ]); ?>
            </div>
            <div class="col3">
                <?php echo template::select('galleryThemeLegendHeight', $module::$galleryThemeLegendHeight, [
                    'label' => 'Hauteur',
                    'selected' => $this->getData(['module', $this->getUrl(0), 'theme','legendHeight'])
                ]); ?>
            </div>
            <div class="col3">
                <?php echo template::select('galleryThemeLegendAlign', $module::$galleryThemeAlign, [
                    'label' => 'Alignement',
                    'selected' => $this->getData(['module', $this->getUrl(0), 'theme','legendAlign'])
                ]); ?>
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