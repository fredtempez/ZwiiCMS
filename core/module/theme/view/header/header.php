<?php echo template::formOpen('themeHeaderForm'); ?>
<div class="row">
    <div class="col2">
        <?php echo template::button('themeHeaderBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'theme',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
    </div>
    <div class="col2">
      <?php echo template::button('themeHeaderHelp', [
        'href' => 'https://doc.zwiicms.fr/banniere',
        'target' => '_blank',
        'ico' => 'help',
        'value' => 'Aide',
        'class' => 'buttonHelp'
      ]); ?>
    </div>
    <div class="col2 offset6">
        <?php echo template::submit('themeHeaderSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>Paramètres</h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::select('themeHeaderPosition', $module::$headerPositions, [
							'label' => 'Position',
							'selected' => $this->getData(['theme', 'header', 'position'])
						]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('themeHeaderFeature', $module::$headerFeatures, [
							'label' => 'Nature de contenu',
							'selected' => $this->getData(['theme', 'header', 'feature'])
						]); ?>
                </div>

                <div class="col4">
                    <?php echo template::select('themeHeaderHeight', $module::$headerHeights, [
							'label' => 'Hauteur maximale',
                            'selected' => $this->getData(['theme', 'header', 'height']),
                            'help' => 'La hauteur maximale est de 600 pixels, même si les dimensions de l\'image sélectionnée sont supérieures. <br />Lorsque l\'adaptation est positionnée sur Responsive, la hauteur diminue proportionnellement à la largeur.'
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
<div class="row wallpaperContainer">
    <div class="col12">
        <div class="block">
            <h4>Couleurs</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::text('themeHeaderBackgroundColor', [
							'class' => 'colorPicker',
							'help' => 'Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Fond',
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
            <h4>Mise en forme du titre</h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::select('themeHeaderFont', $module::$fonts, [
							'label' => 'Police',
							'selected' => $this->getData(['theme', 'header', 'font']),
							'fonts' => true
						]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('themeHeaderFontSize', $module::$headerFontSizes, [
							'label' => 'Taille',
							'help' => 'Proportionnelle à celle définie dans le site.',
							'selected' => $this->getData(['theme', 'header', 'fontSize'])
						]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('themeHeaderFontWeight', $module::$fontWeights, [
							'label' => 'Style',
							'selected' => $this->getData(['theme', 'header', 'fontWeight'])
						]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4 offset2">
                    <?php echo template::select('themeHeaderTextTransform', $module::$textTransforms, [
							'label' => 'Casse',
							'selected' => $this->getData(['theme', 'header', 'textTransform'])
						]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('themeHeaderTextAlign', $module::$aligns, [
							'label' => 'Alignement du contenu',
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
            <h4>Papier peint</h4>
            <div class="row">
                <div class="col12">
                    <?php
                        $imageFile = file_exists(self::FILE_DIR.'source/'.$this->getData(['theme', 'header', 'image'])) ?
                                $this->getData(['theme', 'header', 'image']) : "";
                        echo template::file('themeHeaderImage', [
                            'help' => 'Sélectionner une image aux dimensions recommandées ci-dessous :',
                            'label' => 'Fond',
                            'type' => 1,
                            'value' => $imageFile
                    ]); ?>
                </div>
            </div>
            <div id="themeHeaderImageOptions" class="displayNone">
                <div class="row">
                    <div class="col4">
                        <?php echo template::select('themeHeaderImageRepeat', $module::$repeats, [
								'label' => 'Répétition',
								'selected' => $this->getData(['theme', 'header', 'imageRepeat'])
							]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('themeHeaderImageContainer', $module::$headerWide, [
                                'label' => 'Adaptation',
                                'selected' => $this->getData(['theme', 'header', 'imageContainer']),
                                'help' => 'Les modes responsives permettent de conserver des dimensions proportionnelles.<br />
                                    Cover pour une image plus grande que la bannière, Contain pour une image plus petite.
                                    Les modes Auto et Etiré ne provoquent pas de modification de la hauteur de la bannière.'
                            ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('themeHeaderImagePosition', $module::$imagePositions, [
								'label' => 'Position',
								'selected' => $this->getData(['theme', 'header', 'imagePosition'])
							]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col6">
                        <?php echo template::checkbox('themeHeaderTextHide', true, 'Masquer le titre du site', [
								'checked' => $this->getData(['theme', 'header', 'textHide'])
							]); ?>
                    </div>
                    <div id="themeHeaderShow" class="col6">
                        <?php echo template::checkbox('themeHeaderlinkHomePage', true, 'Bannière cliquable', [
                                'checked' => $this->getData(['theme', 'header', 'linkHomePage'])
                            ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col12 textAlignCenter">
                        <span id="themeHeaderImage">
                            Largeur : <span id="themeHeaderImageWidth"></span> | Hauteur : <span id="themeHeaderImageHeight"></span> | ratio : <span id="themeHeaderImageRatio"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row featureContainer">
    <div class="col12">
        <div class="block">
            <h4>Contenu personnalisé</h4>
            <div class="row">
                <div class="col12">
                    <?php echo template::textarea('themeHeaderContent', [
                        'class' => 'editorWysiwyg',
                        'value' => $this->getData(['theme', 'header', 'featureContent'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="featureContent" class="displayNone">
    <?php echo $this->getData(['theme','header','featureContent']);?>
</div>
<?php echo template::formClose(); ?>
