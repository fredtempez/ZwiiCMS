<?php echo template::formOpen('themeFooterForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('themeFooterBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'theme',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col1">
        <?php /* echo template::button('themeFooterHelp', [
         'href' => 'https://doc.zwiicms.fr/pied-de-page',
         'target' => '_blank',
         'value' => template::ico('help'),
         'class' => 'buttonHelp'
         ]); */?>
    </div>
    <div class="col2 offset8">
        <?php echo template::submit('themeFooterSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Paramètres'); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('themeFooterPosition', $module::$footerPositions, [
                        'label' => 'Position',
                        'selected' => $this->getData(['theme', 'footer', 'position'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('themeFooterHeight', $module::$footerHeights, [
                        'label' => 'Marges verticales',
                        'selected' => $this->getData(['theme', 'footer', 'height'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
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
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Couleurs'); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::text('themeFooterTextColor', [
                        'class' => 'colorPicker',
                        'label' => 'Texte',
                        'value' => $this->getData(['theme', 'footer', 'textColor'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::text('themeFooterBackgroundColor', [
                        'class' => 'colorPicker',
                        'label' => 'Arrière plan',
                        'value' => $this->getData(['theme', 'footer', 'backgroundColor']),
                        'help' => 'Quand le pied de page est dans le site, l\'arrière plan transparent montre le fond de la page. Quand le pied de page est hors du site, l\'arrière plan transparent montre le fond du site.'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Éléments'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('themefooterDisplayCopyright', true, 'Motorisé par', [
                        'checked' => $this->getData(['theme', 'footer', 'displayCopyright']),
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('themefooterDisplayVersion', true, 'Version', [
                        'checked' => $this->getData(['theme', 'footer', 'displayVersion']),

                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('themefooterDisplaySiteMap', true, 'Plan du site', [
                        'checked' => $this->getData(['theme', 'footer', 'displaySiteMap'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('themefooterDisplayCookie', true, 'Cookies', [
                        'checked' => $this->getData(['config', 'cookieConsent']) === true ? $this->getData(['theme', 'footer', 'displayCookie']) : false,
                        'help' => 'Disponible si le consentement des cookies est activé.',
                        'disabled' => !$this->getData(['config', 'cookieConsent'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('themeFooterLoginLink', true, 'Lien de connexion', [
                        'checked' => $this->getData(['theme', 'footer', 'loginLink']),
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('themeFooterMemberBar', true, 'Barre de membre', [
                        'checked' => $this->getData(['theme', 'footer', 'memberBar']),
                        'help' => 'Affiche les icônes de gestion du compte et de déconnexion des membres simples connectés.'
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('themeFooterDisplayLegal', true, 'Mentions légales', [
                        'checked' => $this->getData(['locale', 'legalPageId']) === 'none' ? false : $this->getData(['theme', 'footer', 'displayLegal']),
                        'disabled' => $this->getData(['locale', 'legalPageId']) === 'none' ? true : false,
                        'help' => 'Sélectionnez une page pour activer'
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('configLegalPageId', array_merge(['none' => 'Aucune'], helper::arrayColumn($module::$pagesList, 'title', 'SORT_ASC')), [
                        'label' =>  helper::translate('Mentions légales') . '&nbsp;' . template::flag('selected', '20px'),
                        'selected' => $this->getData(['locale', 'legalPageId'])
                    ]); ?>
                </div>

                <div class="col3">
                    <?php echo template::checkbox('themeFooterDisplaySearch', true, 'Rechercher dans le site', [
                        'checked' => $this->getData(['locale', 'searchPageId']) === 'none' ? false : $this->getData(['theme', 'footer', 'displaySearch']),
                        'disabled' => $this->getData(['locale', 'searchPageId']) === 'none' ? true : false,
                        'help' => 'Sélectionnez une page pour activer'
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('configSearchPageId', array_merge(['none' => 'Aucune'], helper::arrayColumn($module::$pagesList, 'title', 'SORT_ASC')), [
                        'label' =>  helper::translate('Rechercher dans le site') . '&nbsp;' . template::flag('selected', '20px'),
                        'selected' => $this->getData(['locale', 'searchPageId'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <?php echo template::textarea('themeFooterText', [
            'label' => '<div class="titleWysiwygContent">' . helper::translate('Contenu HTML') . '</div>',
            'value' => $this->getData(['theme', 'footer', 'text']),
            'class' => 'editorWysiwyg'
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Mise en forme du texte'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::select('themeFooterFont', $module::$fonts['name'], [
                        'label' => 'Fonte',
                        'selected' => $this->getData(['theme', 'footer', 'font']),
                        'font' => $module::$fonts['family']
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('themeFooterFontSize', $module::$footerFontSizes, [
                        'label' => 'Taille',
                        'help' => 'Proportionnelle à la taille définie dans le site.',
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
            <h4>
                <?php echo helper::translate('Disposition'); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php $footerBlockPosition = is_null($this->getData(['theme', 'footer', 'template'])) ? $module::$footerblocks[3] : $module::$footerblocks[$this->getData(['theme', 'footer', 'template'])]; ?>
                    <?php echo template::select('themeFooterTemplate', $module::$footerTemplate, [
                        'label' => 'Répartition',
                        'selected' => is_null($this->getData(['theme', 'footer', 'template'])) ? 4 : $this->getData(['theme', 'footer', 'template'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <p>
                        <strong>
                            <?php echo helper::translate('Contenu HTML'); ?>
                        </strong>
                    </p>
                    <div class="row">
                        <div class="col12">
                            <?php echo template::select('themeFooterTextPosition', $footerBlockPosition, [
                                'label' => 'Position',
                                'selected' => $this->getData(['theme', 'footer', 'textPosition']),
                                'class' => 'themeFooterContent'
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col12">
                            <?php echo template::select('themeFooterTextAlign', $module::$aligns, [
                                'label' => 'Alignement',
                                'selected' => $this->getData(['theme', 'footer', 'textAlign'])
                            ]); ?>
                        </div>
                    </div>
                </div>
                <div class="col4">
                    <p>
                        <strong>
                            <?php echo helper::translate('Réseaux sociaux'); ?>
                        </strong>
                    </p>
                    <div class="row">
                        <div class="col12">
                            <?php echo template::select('themeFooterSocialsPosition', $footerBlockPosition, [
                                'label' => 'Position',
                                'selected' => $this->getData(['theme', 'footer', 'socialsPosition']),
                                'class' => 'themeFooterContent'
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col12">
                            <?php echo template::select('themeFooterSocialsAlign', $module::$aligns, [
                                'label' => 'Alignement',
                                'selected' => $this->getData(['theme', 'footer', 'socialsAlign'])
                            ]); ?>
                        </div>
                    </div>
                </div>
                <div class="col4">
                    <p>
                        <strong>
                            <?php echo helper::translate('Informations'); ?>
                        </strong>
                    </p>
                    <div class="row">
                        <div class="col12">
                            <?php echo template::select('themeFooterCopyrightPosition', $footerBlockPosition, [
                                'label' => 'Position',
                                'selected' => $this->getData(['theme', 'footer', 'copyrightPosition']),
                                'class' => 'themeFooterContent'
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col12">
                            <?php echo template::select('themeFooterCopyrightAlign', $module::$aligns, [
                                'label' => 'Alignement',
                                'selected' => $this->getData(['theme', 'footer', 'copyrightAlign'])
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>