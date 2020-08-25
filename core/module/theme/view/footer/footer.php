<?php echo template::formOpen('themeFooterForm'); ?>
<div class="row">
    <div class="col2">
        <?php echo template::button('themeFooterBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'theme',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
    </div>
    <div class="col2 offset8">
        <?php echo template::submit('themeFooterSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col6">
        <div class="block">
            <h4>Couleurs</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::text('themeFooterBackgroundColor', [
							'class' => 'colorPicker',
							'label' => 'Fond',
                            'value' => $this->getData(['theme', 'footer', 'backgroundColor']),
                            'help'  => 'Quand le pied de page est dans le site, l\'arrière plan transparent montre le fond de la page. Quand le pied de page est hors du site, l\'arrière plan transparent montre le fond du site.'
						]); ?>
                </div>
                <div class="col6">
                    <?php echo template::text('themeFooterTextColor', [
							'class' => 'colorPicker',
							'label' => 'Texte',
							'value' => $this->getData(['theme', 'footer', 'textColor'])
						]); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col6">
        <div class="block">
            <h4>Informations</h4>
            <div class="row">
                <div class="col6">
                    <div class="row">
                        <?php echo template::checkbox('themefooterDisplayCopyright', true, 'Motorisé par', [
                                'checked' => $this->getData(['theme', 'footer','displayCopyright'])
                            ]); ?>
                    </div>
                    <div class="row">
                        <?php echo template::checkbox('themefooterDisplayVersion', true, 'Numéro de version', [
                                'checked' => $this->getData(['theme', 'footer','displayVersion'])
                            ]); ?>
                    </div>
                    <div class="row">
                        <?php echo template::checkbox('themeFooterDisplayLegal', true, 'Mentions légales', [
                                    'checked' => (bool) empty($this->getData(['config', 'legalPageId'])) ? false : $this->getData(['theme', 'footer', 'displayLegal']),
                                    'disabled' => (bool) empty($this->getData(['config', 'legalPageId'])) ? true : false,
                                    'help' => (bool) empty($this->getData(['config', 'legalPageId'])) ? 'Pour activer cette option, sélectionnez la page contenant les mentions légales dans la gestion du site' : ''
                            ]); ?>
                    </div>
                    <div class="row">
                        <?php echo template::checkbox('themeFooterDisplayMemberBar', true, 'Barre des membres', [
                                'checked' =>  $this->getData(['theme', 'footer', 'displayMemberBar']),
                                'help' => 'Affiche les icônes de gestion du compte et de déconnexion. Uniquement pour les membres simples une fois connectés.'
                        ]); ?>
                    </div>
                </div>
                <div class="col6">
                    <div class="row">
                        <?php echo template::checkbox('themefooterDisplaySiteMap', true, 'Plan du site', [
                                'checked' => $this->getData(['theme', 'footer', 'displaySiteMap']),
                                'help' => 'Un plan du site permet un meilleur référencement.'
                            ]); ?>
                    </div>
                    <div class="row">
                       <?php echo template::checkbox('themeFooterDisplaySearch', true, 'Rechercher', [
                                'checked' =>  $this->getData(['theme', 'footer', 'displaySearch'])
                            ]); ?>
                    </div>
                    <div class="row">
                        <?php echo template::checkbox('themeFooterLoginLink', true, 'Lien de connexion', [
                                    'checked' => $this->getData(['theme', 'footer', 'loginLink'])
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
            <h4>Contenu personnalisé</h4>
            <?php echo template::textarea('themeFooterText', [
					'label' => '<strong>Texte ou HTML</strong>',
					'value' => $this->getData(['theme', 'footer', 'text']),
					'class' => 'editorWysiwyg'
				]); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>Mise en forme du texte</h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::select('themeFooterFont', $module::$fonts, [
							'label' => 'Police',
							'selected' => $this->getData(['theme', 'footer', 'font']),
							'fonts' => true
						]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('themeFooterFontSize', $module::$footerFontSizes, [
							'label' => 'Taille',
							'help' => 'Proportionnelle à celle définie dans le site.',
							'selected' => $this->getData(['theme', 'footer', 'fontSize'])
						]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('themeFooterFontWeight', $module::$fontWeights, [
							'label' => 'Style',
							'selected' => $this->getData(['theme', 'footer', 'fontWeight'])
						]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('themeFooterTextTransform', $module::$textTransforms, [
							'label' => 'Casse',
							'selected' => $this->getData(['theme', 'footer', 'textTransform'])
						]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>Configuration</h4>
            <div class="row">
                <div class="col4">

                    <?php $footerBlockPosition =  is_null($this->getData(['theme', 'footer', 'template'])) ? $module::$footerblocks[3] : $module::$footerblocks [$this->getData(['theme', 'footer', 'template'])] ;?>
                    <?php echo template::select('themeFooterTemplate', $module::$footerTemplate, [
                            'label' => 'Disposition',
                            'selected' => is_null($this->getData(['theme', 'footer', 'template'])) ? 4 : $this->getData(['theme', 'footer', 'template'])
                        ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('themeFooterPosition', $module::$footerPositions, [
                            'label' => 'Position',
                            'selected' => $this->getData(['theme', 'footer', 'position'])
                        ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('themeFooterHeight', $module::$footerHeights, [
                            'label' => 'Marges verticales',
                            'selected' => $this->getData(['theme', 'footer', 'height'])
                        ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <p><strong>Contenu personnalisé</strong></p>
                    <?php echo template::select('themeFooterTextPosition', $footerBlockPosition, [
                            'label' => 'Emplacement',
                            'selected' => $this->getData(['theme', 'footer', 'textPosition']),
                            'class' => 'themeFooterContent'
                        ]); ?>
                    <?php echo template::select('themeFooterTextAlign', $module::$aligns, [
                            'label' => 'Alignement',
                            'selected' => $this->getData(['theme', 'footer', 'textAlign'])
                        ]); ?>
                </div>
                <div class="col4">
                    <p><strong>Réseaux sociaux</strong></p>
                    <?php echo template::select('themeFooterSocialsPosition', $footerBlockPosition, [
                            'label' => 'Emplacement',
                            'selected' => $this->getData(['theme', 'footer', 'socialsPosition']),
                            'class' => 'themeFooterContent'
                        ]); ?>
                    <?php echo template::select('themeFooterSocialsAlign', $module::$aligns, [
                            'label' => 'Alignement',
                            'selected' => $this->getData(['theme', 'footer', 'socialsAlign'])
                        ]); ?>
                </div>
                <div class="col4">
                    <p><strong>Informations</strong></p>
                    <?php echo template::select('themeFooterCopyrightPosition', $footerBlockPosition, [
                            'label' => 'Emplacement',
                            'selected' => $this->getData(['theme', 'footer', 'copyrightPosition']),
                            'class' => 'themeFooterContent'
                        ]); ?>
                    <?php echo template::select('themeFooterCopyrightAlign', $module::$aligns, [
                            'label' => 'Alignement',
                            'selected' => $this->getData(['theme', 'footer', 'copyrightAlign'])
                        ]); ?>
                </div>
                <div class="col6">
                    <div id="themeFooterPositionOptions">
                        <?php echo template::checkbox('themeFooterMargin', true, 'Alignement avec le contenu', [
                                'checked' => $this->getData(['theme', 'footer', 'margin'])
                            ]); ?>
                    </div>
                </div>
                <div class="col6">
                    <div id="themeFooterPositionFixed" class="displayNone">
                        <?php echo template::checkbox('themeFooterFixed', true, 'Pied de page fixe', [
							'checked' => $this->getData(['theme', 'footer', 'fixed'])
						]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>
