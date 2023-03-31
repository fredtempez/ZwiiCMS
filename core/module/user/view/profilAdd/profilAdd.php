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
                            <?php echo template::text('profilEditName', [
                                'label' => 'Nom du profil',
                                'value' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'name'])
                            ]); ?>
                        </div>
                        <div class="col12">
                            <?php echo template::select('profilEditGroup', $module::$groupProfils, [
                                'label' => 'Groupe',
                                'selected' => $this->getUrl(2)
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
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Gestionnaire de fichiers'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddShare', true, 'Activé'); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('profilAddPath', $module::$sharePath, [
                        'label' => 'Racine du dossier'
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
                                <?php echo template::checkbox('profilAddFolderCreate', true, 'Création'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddFolderDelete', true, 'Effacement', ); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddFolderRename', true, 'Nommage'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddFolderCopycut', true, 'Coupé collé'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddFolderChmod', true, 'Droits sur les dossiers'); ?>
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
                                <?php echo template::checkbox('profilAddDownload', true, 'Téléchargement'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddEdit', true, 'Edition'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddCreate', true, 'Création'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddRename', true, 'Nommage'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddUpload', true, 'Téléversement'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddDelete', true, 'Effacement'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col2">
                                <?php echo template::checkbox('profilAddPreview', true, 'Prévisualisation'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddDuplicate', true, 'Duplication'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddExtract', true, 'Extraction'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddCopycut', true, 'Coupé collé'); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilAddChmod', true, 'Droits sur les fichiers'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo template::formClose(); ?>