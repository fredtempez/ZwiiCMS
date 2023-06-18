<?php echo template::formOpen('profilAddForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('profilAddBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'user/profil',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('profilAddSubmit'); ?>
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
                            <?php echo template::text('profilAddName', [
                                'label' => 'Nom du profil',
                                'value' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'name'])
                            ]); ?>
                        </div>
                        <div class="col12">
                            <?php echo template::select('profilAddGroup', $module::$groupProfils, [
                                'label' => 'Groupe',
                                'selected' => $this->getUrl(2)
                            ]); ?>
                        </div>
                    </div>
                </div>
                <div class="col6">
                    <?php echo template::textarea('profilAddComment', [
                        'label' => 'Commentaire',
                        'value' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'comment'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row containerPage">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Pages'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddPageAdd', true, 'Ajouter'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddPageEdit', true, 'Éditer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddPageDelete', true, 'Effacer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddPageDuplicate', true, 'Dupliquer'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddPageModule', true, 'Module'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddPagecssEditor', true, 'Éditeur CSS'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddPagejsEditor', true, 'Éditeur JS'); ?>
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
                        <?php echo template::checkbox('profilAddBlogAdd', true, 'Ajouter'); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilAddBlogEdit', true, 'Edit'); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilAddBlogDelete', true, 'Supprimer'); ?>
                    </div>
                </div>
                <div class="row">

                    <div class="col4">
                        <?php echo template::checkbox('profilAddBlogOption', true, 'Options'); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilAddBlogComment', true, 'Commentaire'); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilAddBlogCommentApprove', true, 'Approuver les commentaires'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col6"s>
                        <?php echo template::checkbox('profilAddBlogCommentDelete', true, 'Supprimer les commentaires'); ?>
                    </div>
                    <div class="col6">
                        <?php echo template::checkbox('profilAddBlogCommentDeleteAll', true, 'Nettoyer les commentaires'); ?>
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
                        <?php echo template::checkbox('profilAddNewsAdd', true, 'Ajouter'); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilAddNewsEdit', true, 'Éditer'); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilAddNewsDelete', true, 'Supprimer'); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::checkbox('profilAddNewsOption', true, 'Options'); ?>
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
                        <?php echo template::checkbox('profilAddGalleryAdd', true, 'Ajouter'); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilAddGalleryEdit', true, 'Éditer'); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilAddGalleryDelete', true, 'Supprimer'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col6">
                        <?php echo template::checkbox('profilAddGalleryOption', true, 'Options'); ?>
                    </div>
                    <div class="col6">
                        <?php echo template::checkbox('profilAddGalleryTheme', true, 'Thème'); ?>
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
                        <?php echo template::checkbox('profilAddFormOption', true, 'Options'); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilAddFormData', true, 'Gérer les données'); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilAddFormExport2csv', true, 'Export CSV'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col4">
                        <?php echo template::checkbox('profilAddFormDelete', true, 'Supprimer'); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::checkbox('profilAddFormDeleteAll', true, 'Tout Supprimer'); ?>
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
                        <?php echo template::checkbox('profilEditRedirectionConfig', true, 'Configurer'); ?>
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
                        <?php echo template::checkbox('profilEditSearchConfig', true, 'Configurer'); ?>
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
                <?php echo helper::translate('Gestionnaire de fichiers'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddFileManager', true, 'Autorisé'); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('profilAddPath', $module::$sharePath, [
                        'label' => 'Dossier',
                        'class' => 'filemanager',
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
                                <?php echo template::checkbox('profilAddFolderCreate', true, 'Création', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddFolderDelete', true, 'Effacement', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddFolderRename', true, 'Nommage', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddFolderCopycut', true, 'Copié collé', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddFolderChmod', true, 'Droits sur les dossiers', ['class' => 'filemanager']); ?>
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
                                <?php echo template::checkbox('profilAddDownload', true, 'Téléchargement', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddEdit', true, 'Edition', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddCreate', true, 'Création', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddRename', true, 'Nommage', ['class' => 'filemanager']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php echo template::checkbox('profilAddUpload', true, 'Téléversement', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddDelete', true, 'Effacement', ['class' => 'filemanager']); ?>
                            </div>

                            <div class="col3">
                                <?php echo template::checkbox('profilAddPreview', true, 'Prévisualisation', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddDuplicate', true, 'Duplication', ['class' => 'filemanager']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php echo template::checkbox('profilAddExtract', true, 'Extraction', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddCopycut', true, 'Copié collé', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddChmod', true, 'Droits sur les fichiers', ['class' => 'filemanager']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>