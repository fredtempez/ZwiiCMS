<?php echo template::formOpen('themeHeaderForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('themeHeaderBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'theme',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col1">
        <?php /* echo template::button('themeHeaderHelp', [
            'href' => 'https://doc.zwiicms.fr/banniere',
            'target' => '_blank',
            'value' => template::ico('help'),
            'class' => 'buttonHelp'
        ]); */?>
    </div>
    <div class="col2 offset8">
        <?php echo template::submit('themeHeaderSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Paramètres'); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::select('themeHeaderPosition', $module::$headerPositions, [
                        'label' => 'Position',
                        'selected' => $this->getData(['theme', 'header', 'position'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('themeHeaderFeature', $module::$headerFeatures, [
                        'label' => 'Contenu',
                        'selected' => $this->getData(['theme', 'header', 'feature'])
                    ]); ?>
                </div>

                <div class="col4">
                    <?php echo template::select('themeHeaderHeight', $module::$headerHeights, [
                        'label' => 'Hauteur maximale',
                        'selected' => $this->getData(['theme', 'header', 'height']),
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::select('themeHeaderWide', $module::$containerWides, [
                        'label' => 'Largeur',
                        'selected' => $this->getData(['theme', 'header', 'wide'])
                    ]); ?>
                </div>
                <div class="col4">
                    <div id="themeHeaderSmallDisplay" class="displayNone">
                        <?php echo template::checkbox('themeHeaderTinyHidden', true, 'Masquer la bannière en écran réduit', [
                            'checked' => $this->getData(['theme', 'header', 'tinyHidden'])
                        ]); ?>
                    </div>
                </div>
                <div class="col4">
                    <div id="themeHeaderPositionOptions" class="displayNone">
                        <?php echo template::checkbox('themeHeaderMargin', true, 'Aligner la bannière avec le contenu', [
                            'checked' => $this->getData(['theme', 'header', 'margin'])
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Couleurs'); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::text('themeHeaderBackgroundColor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',
                        'label' => 'Arrière plan',
                        'value' => $this->getData(['theme', 'header', 'backgroundColor'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::text('themeHeaderTextColor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',
                        'label' => 'Texte',
                        'value' => $this->getData(['theme', 'header', 'textColor'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row wallpaperContainer">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Mise en forme du titre'); ?>
            </h4>
            <div class="row">
                <div class="col12">
                    <?php echo template::checkbox('themeHeaderTextHide', true, 'Titre masqué', [
                        'checked' => $this->getData(['theme', 'header', 'textHide'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col3">
                    <?php echo template::select('themeHeaderFont', $module::$fonts['name'], [
                        'label' => 'Fonte',
                        'selected' => $this->getData(['theme', 'header', 'font']),
                        'font' => $module::$fonts['family']
                    ]); ?>
                </div>
                <div class="col2">
                    <?php echo template::select('themeHeaderFontSize', $module::$headerFontSizes, [
                        'label' => 'Taille',
                        'help' => 'Proportionnelle à la taille définie dans le site.',
                        'selected' => $this->getData(['theme', 'header', 'fontSize'])
                    ]); ?>
                </div>
                <div class="col2">
                    <?php echo template::select('themeHeaderFontWeight', $module::$fontWeights, [
                        'label' => 'Style',
                        'selected' => $this->getData(['theme', 'header', 'fontWeight'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('themeHeaderTextTransform', $module::$textTransforms, [
                        'label' => 'Casse',
                        'selected' => $this->getData(['theme', 'header', 'textTransform'])
                    ]); ?>
                </div>
                <div class="col2">
                    <?php echo template::select('themeHeaderTextAlign', $module::$aligns, [
                        'label' => 'Alignement',
                        'selected' => $this->getData(['theme', 'header', 'textAlign'])
                    ]); ?>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="row wallpaperContainer">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Papier peint'); ?>
            </h4>
            <div class="row">
                <div class="col12">
                    <?php $imageFile = file_exists(self::FILE_DIR . 'source/' . $this->getData(['theme', 'header', 'image'])) ? $this->getData(['theme', 'header', 'image']) : ""; ?>
                    <?php echo template::file('themeHeaderImage', [
                        'label' => 'Image',
                        'language' => $this->getData(['user', $this->getUser('id'), 'language']),
                        'type' => 1,
                        'value' => $imageFile
                    ]);
                    ?>
                    <span class="themeHeaderImageOptions displayNone" id="themeHeaderImageInfo">
                        <?php echo helper::translate('Largeur de l\'image'); ?> <span id="themeHeaderImageWidth"></span> ; <?php echo helper::translate('Largeur du site :'); ?> <?php echo $this->getData(['theme', 'site', 'width']); ?>
                        |
                        <?php echo helper::translate('Hauteur de l\'image'); ?> <span id="themeHeaderImageHeight"></span>
                        |
                        <?php echo helper::translate('Ratio'); ?> <span id="themeHeaderImageRatio"></span>
                    </span>
                </div>
            </div>
            <div class="themeHeaderImageOptions" class="displayNone">
                <div class="row">
                    <div class="col3">
                        <?php echo template::select('themeHeaderImageRepeat', $module::$repeats, [
                            'label' => 'Répétition',
                            'selected' => $this->getData(['theme', 'header', 'imageRepeat'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::select('themeHeaderImageContainer', $module::$headerWide, [
                            'label' => 'Adaptation',
                            'selected' => $this->getData(['theme', 'header', 'imageContainer']),
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::select('themeHeaderImagePosition', $module::$imagePositions, [
                            'label' => 'Position',
                            'selected' => $this->getData(['theme', 'header', 'imagePosition'])
                        ]); ?>
                    </div>
                    <div id="themeHeaderShow" class="col3">
                        <?php echo template::checkbox('themeHeaderlinkHomePage', true, 'Bannière cliquable', [
                            'checked' => $this->getData(['theme', 'header', 'linkHomePage'])
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row featureContainer">
    <div class="col12">
        <div class="row">
            <div class="col12">
                <?php echo template::textarea('themeHeaderText', [
                    'label' => '<div class="titleWysiwygContent">' . helper::translate('Contenu HTML') . '</div>',
                    'class' => 'editorWysiwyg',
                    'value' => $this->getData(['theme', 'header', 'featureContent'])
                ]); ?>
            </div>
        </div>
    </div>
</div>
<div id="featureContent" class="displayNone">
    <?php echo $this->getData(['theme', 'header', 'featureContent']); ?>
</div>
<?php echo template::formClose(); ?>