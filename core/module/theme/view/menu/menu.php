<?php echo template::formOpen('themeMenuForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('themeMenuBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'theme',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col1">
        <?php /* echo template::button('themeMenuHelp', [
            'href' => 'https://doc.zwiicms.fr/menu',
            'target' => '_blank',
            'value' => template::ico('help'),
            'class' => 'buttonHelp'
        ]); */?>
    </div>
    <div class="col2 offset8">
        <?php echo template::submit('themeMenuSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Paramètres'); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php
                    if ($this->getData(['theme', 'header', 'position']) == "site") {
                        echo template::select('themeMenuPosition', $module::$menuPositionsSite, [
                            'label' => 'Position',
                            'selected' => $this->getData(['theme', 'menu', 'position'])
                        ]);
                    } else {
                        echo template::select('themeMenuPosition', $module::$menuPositionsBody, [
                            'label' => 'Position',
                            'selected' => $this->getData(['theme', 'menu', 'position'])
                        ]);
                    }
                    ?>
                </div>
                <div class="col6">
                    <?php echo template::select('themeMenuWide', $module::$containerWides, [
                        'label' => 'Largeur',
                        'selected' => $this->getData(['theme', 'menu', 'wide'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::select('themeMenuRadius', $module::$menuRadius, [
                        'label' => 'Bords arrondis',
                        'selected' => $this->getData(['theme', 'menu', 'radius']),
                        'help' => 'Autour de la page sélectionnée'
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('themeMenuHeight', $module::$menuHeights, [
                        'label' => 'Hauteur',
                        'selected' => $this->getData(['theme', 'menu', 'height'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('themeMenuTextAlign', $module::$aligns, [
                        'label' => 'Alignement',
                        'selected' => $this->getData(['theme', 'menu', 'textAlign'])
                    ]); ?>
                </div>
            </div>
            <div id="themeMenuPositionOptions" class="displayNone">
                <?php echo template::checkbox('themeMenuMargin', true, 'Aligner le menu avec le contenu', [
                    'checked' => $this->getData(['theme', 'menu', 'margin'])
                ]); ?>
            </div>
            <div id="themeMenuPositionFixed" class="displayNone">
                <?php echo template::checkbox('themeMenuFixed', true, 'Menu fixe', [
                    'checked' => $this->getData(['theme', 'menu', 'fixed'])
                ]); ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Contenu'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('themeMenuLoginLink', true, 'Lien de connexion', [
                        'checked' => $this->getData(['theme', 'menu', 'loginLink'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('themeMenuMemberBar', true, 'Barre de membre', [
                        'checked' =>  $this->getData(['theme', 'menu', 'memberBar']),
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('themeMenuBurgerContent', $module::$burgerContent, [
                        'label' => 'Menu burger dans écran réduit',
                        'selected' => $this->getData(['theme', 'menu', 'burgerContent']),
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div id="themeMenuBurgerLogoId" class="col6 offset6 <?php if ($this->getData(['theme', 'menu', 'burgerContent']) !== 'logo') echo 'displayNone'; ?>">
                    <?php $imageFile = file_exists(self::FILE_DIR . 'source/' . $this->getData(['theme', 'menu', 'burgerLogo'])) ? $this->getData(['theme', 'menu', 'burgerLogo']) : ""; ?>
                    <?php echo template::file('themeMenuBurgerLogo', [
                        'help' => 'Sélectionner une image de dimensions adaptées',
                        'language' => $this->getData(['user', $this->getUser('id'), 'language']),
                        'label' => 'Logo du menu burger',
                        'type' => 1,
                        'value' => $imageFile
                    ]);
                    ?>
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
                <div class="col4">
                    <?php echo template::text('themeMenuTextColor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',
                        'label' => 'Texte',
                        'value' => $this->getData(['theme', 'menu', 'textColor'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::text('themeMenuBackgroundColor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',
                        'label' => 'Arrière plan',
                        'value' => $this->getData(['theme', 'menu', 'backgroundColor'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::text('themeMenuBackgroundColorSub', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',
                        'label' => 'Fond du sous-menu',
                        'value' => $this->getData(['theme', 'menu', 'backgroundColorSub'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::text('themeMenuActiveTextColor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',
                        'label' => 'Couleur texte page active',
                        'value' => $this->getData(['theme', 'menu', 'activeTextColor'])
                    ]); ?>
                </div>
                <div class="col4 verticalAlignBottom">
                    <?php
                    echo template::checkbox('themeMenuActiveColorAuto', true, 'Couleur de fond automatique', [
                        'checked' => $this->getData(['theme', 'menu', 'activeColorAuto']),
                     ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::text('themeMenuActiveColor', [
                        'class' => 'colorPicker',
                        'help' => 'Couleur de fond de la page sélectionnée dans le menu.<br>Le curseur horizontal règle le niveau de transparence.',
                        'label' => 'Fond page active',
                        'value' => $this->getData(['theme', 'menu', 'activeColor'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Mise en forme du texte'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::select('themeMenuFont', $module::$fonts['name'], [
                        'label' => 'Fonte',
                        'selected' => $this->getData(['theme', 'menu', 'font']),
                        'font' => $module::$fonts['family']
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('themeMenuFontSize', $module::$menuFontSizes, [
                        'label' => 'Taille',
                        'help' => 'Proportionnelle à la taille définie dans le site.',
                        'selected' => $this->getData(['theme', 'menu', 'fontSize'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('themeMenuFontWeight', $module::$fontWeights, [
                        'label' => 'Style',
                        'selected' => $this->getData(['theme', 'menu', 'fontWeight'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('themeMenuTextTransform', $module::$textTransforms, [
                        'label' => 'Casse',
                        'selected' => $this->getData(['theme', 'menu', 'textTransform'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>