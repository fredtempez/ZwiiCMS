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
<?php if ($this->getUrl(2) >= self::GROUP_MODERATOR): ?>
    <div class="row">
        <div class="col12">
            <div class="block">
                <h4>
                    <?php echo helper::translate('Pages'); ?>
                </h4>
                <div class="row">
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPageAdd', false, 'Ajouter', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'add'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPageEdit', false, 'Editer', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'edit'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPageDelete', false, 'Supprimer', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'delete'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPageDuplicate', false, 'Dupliquer', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'duplicate'])
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPageModule', false, 'Module', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'module'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPagecssEditor', false, 'Editeur CSS', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'cssEditor'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilEditPagejsEditor', false, 'Editeur JS', [
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
                        <div class="col3">
                            <?php echo template::checkbox('profilEditBlogAdd', false, 'Ajouter', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'add'])
                            ]); ?>
                        </div>
                        <div class="col3">
                            <?php echo template::checkbox('profilEditBlogEdit', false, 'Edit', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'edit'])
                            ]); ?>
                        </div>
                        <div class="col3">
                            <?php echo template::checkbox('profilEditBlogDelete', false, 'Supprimer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'delete'])
                            ]); ?>
                        </div>
                        <div class="col3">
                            <?php echo template::checkbox('profilEditBlogConfig', false, 'Configuration', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'config'])
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col4">
                            <?php echo template::checkbox('profilEditBlogOption', false, 'Option', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'option'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditBlogComment', false, 'Commentaire', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'comment'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditBlogCommentApprouve', false, 'Approuver commentaire', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'commentApprove'])
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col6">
                            <?php echo template::checkbox('profilEditBlogCommentDelete', false, 'Supprimer commentaire', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'commentDelete'])
                            ]); ?>
                        </div>
                        <div class="col6">
                            <?php echo template::checkbox('profilEditBlogCommentDeleteAll', false, 'Nettoyer commentaires', [
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
                        <div class="col4">
                            <?php echo template::checkbox('profilEditNewsAdd', false, 'Ajouter', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'add'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditNewsEdit', false, 'Edit', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'edit'])
                            ]); ?>
                        </div>
                        <div class="col4">
                            <?php echo template::checkbox('profilEditNewsDelete', false, 'Supprimer', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'delete'])
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col6">
                            <?php echo template::checkbox('profilEditNewsConfig', false, 'Configuration', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'config'])
                            ]); ?>
                        </div>
                        <div class="col6">
                            <?php echo template::checkbox('profilEditNewsOption', false, 'Option', [
                                'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'option'])
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
                    <?php echo helper::translate('Galerie'); ?>
                </h4>
                <div class="row">
                    <div class="col4">
                        <?php echo template::checkbox('profilEditGalleryAdd', false, 'Ajouter', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'add'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilEditGalleryEdit', false, 'Edit', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'edit'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilEditGalleryDelete', false, 'Supprimer', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'delete'])
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col4">
                        <?php echo template::checkbox('profilEditGalleryConfig', false, 'Configuration', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'config'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilEditGalleryOption', false, 'Option', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'option'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilEditGalleryTheme', false, 'Theme', [
                            'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'gallery', 'theme'])
                        ]); ?>
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
                <?php echo helper::translate('Gestionnaire de fichiers'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilEditFileManager', false, 'Autorisé', [
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
                                <?php echo template::checkbox('profilEditFolderCreate', false, 'Création', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'create']),
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilEditFolderDelete', false, 'Effacement', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'delete'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilEditFolderRename', false, 'Nommage', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'rename'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilEditFolderCopycut', false, 'Copié collé', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'copycut'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditFolderChmod', false, 'Droits sur les dossiers', [
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
                                <?php echo template::checkbox('profilEditDownload', false, 'Téléchargement', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'download'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditEdit', false, 'Edition', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'edit'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditCreate', false, 'Création', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'create'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditRename', false, 'Nommage', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'rename'])
                                ]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php echo template::checkbox('profilEditUpload', false, 'Téléversement', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'upload'])
                                ]); ?>
                            </div>

                            <div class="col3">
                                <?php echo template::checkbox('profilEditDelete', false, 'Effacement', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'delete'])
                                ]); ?>
                            </div>

                            <div class="col3">
                                <?php echo template::checkbox('profilEditPreview', false, 'Prévisualisation', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'preview'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditDuplicate', false, 'Duplication', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'duplicate'])
                                ]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php echo template::checkbox('profilEditExtract', false, 'Extraction', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'extract'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditCopycut', false, 'Copié collé', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'copycut'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditChmod', false, 'Droits sur les fichiers', [
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