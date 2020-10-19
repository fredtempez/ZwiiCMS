<?php echo template::formOpen('themeMenuForm'); ?>
<div class="row">
    <div class="col2">
        <?php echo template::button('themeMenuBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'theme',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
    </div>
    <div class="col2 offset8">
        <?php echo template::submit('themeMenuSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>Couleur</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::text('themeMenuTextColor', [
							'class' => 'colorPicker',
							'help' => 'Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Texte',
							'value' => $this->getData(['theme', 'menu', 'textColor'])
						]); ?>
                </div>
                <div class="col6">
                    <?php echo template::text('themeMenuBackgroundColor', [
                            'class' => 'colorPicker',
                            'help' => 'Le curseur horizontal règle le niveau de transparence.',
                            'label' => 'Fond',
                            'value' => $this->getData(['theme', 'menu', 'backgroundColor'])
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>Page active</h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::text('themeMenuActiveTextColor', [
                            'class' => 'colorPicker',
                            'help' => 'Le curseur horizontal règle le niveau de transparence.',
                            'label' => 'Texte',
                            'value' => $this->getData(['theme', 'menu', 'activeTextColor'])
                        ]); ?>
                </div>
                <div class="col4 verticalAlignBottom">
                    <?php
                        echo template::checkbox('themeMenuActiveColorAuto', true, 'Couleur du fond automatique', [
                        'checked' => $this->getData(['theme', 'menu', 'activeColorAuto']),
                        'help' => 'La couleur de fond de la page active peut être définie automatique ou selon une couleur définie, comme par exemple celle de fond des pages.'
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::text('themeMenuActiveColor', [
							'class' => 'colorPicker',
							'help' => 'Couleur de fond de la page sélectionnée dans le menu.<br>Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Fond',
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
            <h4>Mise en forme du texte</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('themeMenuFont', $module::$fonts, [
								'label' => 'Police',
								'selected' => $this->getData(['theme', 'menu', 'font']),
                                'fonts' => true
							]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('themeMenuFontSize', $module::$menuFontSizes, [
								'label' => 'Taille',
								'help' => 'Proportionnelle à celle définie dans le site',
								'selected' => $this->getData(['theme', 'menu', 'fontSize'])
							]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('themeMenuFontWeight', $module::$fontWeights, [
							'label' => 'Style',
							'selected' => $this->getData(['theme', 'menu', 'fontWeight'])
						]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('themeMenuTextTransform', $module::$textTransforms, [
							'label' => 'Casse',
							'selected' => $this->getData(['theme', 'menu', 'textTransform'])
						]); ?>
                </div>
            </div>
            <div>
            </div>
        </div>
        <div class="row">
            <div class="col12">
                <div class="block">
                    <h4>Configuration</h4>
                    <div class="row">
                        <div class="col6">
                            <?php
                            if ( $this->getData(['theme', 'header', 'position']) == "site")
                            {	echo template::select('themeMenuPosition', $module::$menuPositionsSite, [
                                    'label' => 'Position',
                                    'selected' => $this->getData(['theme', 'menu', 'position'])
                                ]);
                            }else{
                            echo template::select('themeMenuPosition', $module::$menuPositionsBody, [
                                'label' => 'Position',
                                'selected' => $this->getData(['theme', 'menu', 'position'])
                            ]);	}
                            ?>
                        </div>
                        <div class="col6">
                            <?php echo template::select('themeMenuRadius', $module::$menuRadius, [
                            'label' => 'Bords arrondis',
                            'selected' => $this->getData(['theme', 'menu', 'radius']),
                            'help' => 'Autour de la page sélectionnée'
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col6">
                            <?php echo template::select('themeMenuHeight', $module::$menuHeights, [
							'label' => 'Hauteur',
							'selected' => $this->getData(['theme', 'menu', 'height'])
						]); ?>
                        </div>
                        <div class="col6">
                            <?php echo template::select('themeMenuTextAlign', $module::$aligns, [
							'label' => 'Alignement du contenu',
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
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
        <h4>Contenus</h4>
            <div class="row">
                <div class="col4">
                        <?php echo template::checkbox('themeMenuLoginLink', true, 'Lien de connexion', [
                                'checked' => $this->getData(['theme', 'menu', 'loginLink'])
                            ]); ?>
                </div>
                <div class="col4">
                        <?php echo template::checkbox('themeMenuMemberBar', true, 'Barre de membre', [
                                'checked' =>  $this->getData(['theme', 'menu', 'memberBar']),
                                'help' => 'Icônes de gestion de compte et de déconnexion. Uniquement pour les membres connectés'
                        ]); ?>
                    </div>
                <div class="col4">
                        <?php echo template::checkbox('themeMenuBurgerTitle', true, 'Titre du site dans le menu réduit', [
                                'checked' => $this->getData(['theme', 'menu', 'burgerTitle']),
                                'help' => 'Le menu burger remplace le menu complet lorsque la largeur de l\'écran  n\'est pas suffisante.'
                            ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>
