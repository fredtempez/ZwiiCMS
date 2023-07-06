<?php echo template::formOpen('profilEditForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('profilEditBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'user/profil',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('profilEditSubmit'); ?>
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
                    <div class="row">
                        <div class="col12">
                            <?php echo template::text('profilEditName', [
                                'label' => 'Nom du profil',
                                'value' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'name'])
                            ]); ?>
                        </div>
                        <div class="col12">
                            <?php echo template::text('profilEditDisplayGroup', [
                                'label' => 'Groupe',
                                'value' => self::$groups[$this->getUrl(2)],
                                'disabled' => true
                            ]); ?>
                            <?php echo template::hidden('profilEditGroup', [
                                'value' => $this->getUrl(2),
                            ]); ?>
                            <?php echo template::hidden('profilEditProfil', [
                                'value' => $this->getUrl(3),
                            ]); ?>
                        </div>
                    </div>
                </div>
                <div class="col6">
                    <?php echo template::textarea('profilEditComment', [
                        'label' => 'Commentaire',
                        'value' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'comment'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($this->getUrl(2) >= self::GROUP_EDITOR): ?>
    <div class="row">
        <div class="col12">
            <div class="block">
                <h4>
                    <?php echo helper::translate('Pages'); ?>
                </h4>
                <div class="row">
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPageAdd', true, 'Ajouter', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'add'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPageEdit', true, 'Éditer', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'edit'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPageDelete', true, 'Supprimer', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'delete'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPageDuplicate', true, 'Dupliquer', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'duplicate'])
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPageModule', true, 'Gérer Module', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'module'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPagecssEditor', true, 'Éditeur CSS', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'cssEditor'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPagejsEditor', true, 'Éditeur JS', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'jsEditor'])
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="containerModule">
        <div class="row">
            <div class="col12">
                <div class="block">
                    <h4>
                        <?php echo helper::translate('Blog'); ?>
                    </h4>
                    <div class="row">
                        <div class="col4">
                            <?php echo template::checkbox('profilEditBlogAdd', true, 'Ajouter', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'add'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditBlogEdit', true, 'Éditer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'edit'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditBlogDelete', true, 'Supprimer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'delete'])
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col4">
                            <?php echo template::checkbox('profilEditBlogOption', true, 'Options', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'option'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditBlogComment', true, 'Gérer les commentaires', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'comment'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditBlogCommentApprove', true, 'Approuver les commentaires', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'commentApprove'])
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col6">
                            <?php echo template::checkbox('profilEditBlogCommentDelete', true, 'Supprimer les commentaires', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'commentDelete'])
                            ]); ?>
                        </div>
                        <div class="col6">
                            <?php echo template::checkbox('profilEditBlogCommentDeleteAll', true, 'Nettoyer les commentaires', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'commentDeleteAll'])
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
                        <?php echo helper::translate('News'); ?>
                    </h4>
                    <div class="row">
                        <div class="col3">
                            <?php echo template::checkbox('profilEditNewsAdd', true, 'Ajouter', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'add'])
                            ]); ?>
                        </div>
                        <div class="col3">
                            <?php echo template::checkbox('profilEditNewsEdit', true, 'Éditer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'edit'])
                            ]); ?>
                        </div>
                        <div class="col3">
                            <?php echo template::checkbox('profilEditNewsDelete', true, 'Supprimer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'delete'])
                            ]); ?>
                        </div>
                        <div class="col3">
                            <?php echo template::checkbox('profilEditNewsOption', true, 'Options', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'option'])
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
                        <?php echo helper::translate('Galerie'); ?>
                    </h4>
                    <div class="row">
                        <div class="col4">
                            <?php echo template::checkbox('profilEditGalleryAdd', true, 'Ajouter', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'add'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditGalleryEdit', true, 'Éditer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'edit'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditGalleryDelete', true, 'Supprimer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'delete'])
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col6">
                            <?php echo template::checkbox('profilEditGalleryOption', true, 'Options', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'option'])
                            ]); ?>
                        </div>
                        <div class="col6">
                            <?php echo template::checkbox('profilEditGalleryTheme', true, 'Thème', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'theme'])
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
                        <?php echo helper::translate('Formulaire'); ?>
                    </h4>
                    <div class="row">
                        <div class="col4">
                            <?php echo template::checkbox('profilEditFormOption', true, 'Options', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'option'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditFormData', true, 'Gérer les données', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'data'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditFormExport2csv', true, 'Export CSV', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'export2csv'])
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col6">
                            <?php echo template::checkbox('profilEditFormDelete', true, 'Supprimer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'delete'])
                            ]); ?>
                        </div>
                        <div class="col6">
                            <?php echo template::checkbox('profilEditFormDeleteAll', true, 'Tout Supprimer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'deleteAll'])
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
                        <?php echo helper::translate('Redirection'); ?>
                    </h4>
                    <div class="row">
                        <div class="col4">
                            <?php echo template::checkbox('profilEditRedirectionConfig', true, 'Configurer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'redirection', 'config'])
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
                        <?php echo helper::translate('Recherche'); ?>
                    </h4>
                    <div class="row">
                        <div class="col4">
                            <?php echo template::checkbox('profilEditSearchConfig', true, 'Configurer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'search', 'config'])
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="row">
        <div class="col12">
            <div class="block">
                <h4>
                    <?php echo helper::translate('Compte de l\'utilisateur'); ?>
                </h4>
                <div class="row">
                    <div class="col3">
                    <?php echo template::checkbox('profilEditUserEdit', true, 'Éditer', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'user', 'edit'])
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
                <?php echo helper::translate('Gestionnaire de fichiers'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilEditFileManager', true, 'Autorisé', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'filemanager'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('profilEditPath', $module::$sharePath, [
                        'label' => 'Dossier',
                        'class' => 'filemanager',
                        'selected' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'path'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col12">
                    <div class="block">
                        <h4>
                            <?php echo helper::translate('Permissions sur les dossiers'); ?>
                        </h4>
                        <div class="row">
                            <div class="col2">
                                <?php echo template::checkbox('profilEditFolderCreate', true, 'Création', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'create']),
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilEditFolderDelete', true, 'Effacement', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'delete'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilEditFolderRename', true, 'Nommage', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'rename'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilEditFolderCopycut', true, 'Copié collé', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'copycut'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditFolderChmod', true, 'Droits sur les dossiers', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'chmod'])
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
                            <?php echo helper::translate('Permissions sur les fichiers'); ?>
                        </h4>
                        <div class="row">
                            <div class="col3">
                                <?php echo template::checkbox('profilEditDownload', true, 'Téléchargement', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'download'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditEdit', true, 'Edition', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'edit'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditCreate', true, 'Création', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'create'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditRename', true, 'Nommage', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'rename'])
                                ]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php echo template::checkbox('profilEditUpload', true, 'Téléversement', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'upload'])
                                ]); ?>
                            </div>

                            <div class="col3">
                                <?php echo template::checkbox('profilEditDelete', true, 'Effacement', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'delete'])
                                ]); ?>
                            </div>

                            <div class="col3">
                                <?php echo template::checkbox('profilEditPreview', true, 'Prévisualisation', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'preview'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditDuplicate', true, 'Duplication', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'duplicate'])
                                ]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php echo template::checkbox('profilEditExtract', true, 'Extraction', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'extract'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditCopycut', true, 'Copié collé', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'copycut'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditChmod', true, 'Droits sur les fichiers', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'chmod'])
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>