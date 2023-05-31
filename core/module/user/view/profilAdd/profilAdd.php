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
                    <?php echo template::checkbox('profilAddPageAdd', false, 'Ajouter'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddPageEdit', false, 'Editer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddPageDelete', false, 'Effacer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddPageDuplicate', false, 'Dupliquer'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddPageModule', false, 'Module'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddPagecssEditor', false, 'Editeur CSS'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddPagejsEditor', false, 'Editeur JS'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row containerPage">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Blog'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddBlogAdd', false, 'Ajouter'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddBlogEdit', false, 'Edit'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddBlogDelete', false, 'Supprimer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddBlogConfig', false, 'Configuration'); ?>
                </div>
            </div>
            <div class="row">
 
                <div class="col4">
                    <?php echo template::checkbox('profilAddBlogOption', false, 'Option'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddBlogComment', false, 'Commentaire'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddBlogCommentApprouve', false, 'Approuver commentaire'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('profilAddBlogCommentDelete', false, 'Supprimer commentaire'); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilAddBlogCommentDeleteAll', false, 'Nettoyer commentaires'); ?>
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
                    <?php echo template::checkbox('profilAddFileManager', false, 'Autorisé'); ?>
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
                                <?php echo template::checkbox('profilAddFolderCreate', false, 'Création', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddFolderDelete', false, 'Effacement', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddFolderRename', false, 'Nommage', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddFolderCopycut', false, 'Copié collé', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddFolderChmod', false, 'Droits sur les dossiers', ['class' => 'filemanager']); ?>
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
                                <?php echo template::checkbox('profilAddDownload', false, 'Téléchargement', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddEdit', false, 'Edition', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddCreate', false, 'Création', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddRename', false, 'Nommage', ['class' => 'filemanager']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php echo template::checkbox('profilAddUpload', false, 'Téléversement', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddDelete', false, 'Effacement', ['class' => 'filemanager']); ?>
                            </div>

                            <div class="col3">
                                <?php echo template::checkbox('profilAddPreview', false, 'Prévisualisation', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddDuplicate', false, 'Duplication', ['class' => 'filemanager']); ?>
                            </div>
                            </div>
                        <div class="row">
                            <div class="col3">
                                <?php echo template::checkbox('profilAddExtract', false, 'Extraction', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddCopycut', false, 'Copié collé', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilAddChmod', false, 'Droits sur les fichiers', ['class' => 'filemanager']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo template::formClose(); ?>