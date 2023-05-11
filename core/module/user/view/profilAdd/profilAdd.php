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
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Gestionnaire de fichiers'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddFileManager', true, 'Activé'); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('profilAddPath', $module::$sharePath, [
                        'label' => 'Racine du dossier',
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
                                <?php echo template::checkbox('profilAddFolderCopycut', true, 'Coupé collé', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
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
                            <div class="col2">
                                <?php echo template::checkbox('profilAddDownload', true, 'Téléchargement', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddEdit', true, 'Edition', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddCreate', true, 'Création', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddRename', true, 'Nommage', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddUpload', true, 'Téléversement', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddDelete', true, 'Effacement', ['class' => 'filemanager']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col2">
                                <?php echo template::checkbox('profilAddPreview', true, 'Prévisualisation', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddDuplicate', true, 'Duplication', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddExtract', true, 'Extraction', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddCopycut', true, 'Coupé collé', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
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