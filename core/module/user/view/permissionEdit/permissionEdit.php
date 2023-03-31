<?php echo template::formOpen('permissionEditForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('permissionEditBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'user/permission',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('permissionEditSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Identité'); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::text('permissionEditName', [
                        'label' => 'Profil',
                        'value' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'name'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::textarea('permissionEditComment', [
                        'label' => 'Commentaire',
                        'value' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'comment'])
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
                    <?php echo template::checkbox('permissionEditShare', true, 'Activé', [
                        'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'folder', 'share'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('permissionEditPath', $module::$sharePath, [
                        'label' => 'Racine du dossier',
                        'selected' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'folder', 'path'])
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
                                <?php echo template::checkbox('permissionEditFolderCreate', true, 'Création', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'folder', 'create'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditFolderDelete', true, 'Effacement', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'folder', 'delete'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditFolderRename', true, 'Nommage', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'folder', 'rename'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditFolderCopycut', true, 'Coupé collé', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'folder', 'copycut'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditFolderPermission', true, 'Permissions', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'folder', 'permission'])
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
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditDownload', true, 'Téléchargement', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'file', 'download'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditEdit', true, 'Edition', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'file', 'edit'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditCreate', true, 'Création', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'file', 'create'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditRename', true, 'Nommage', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'file', 'rename'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditUpload', true, 'Téléversement', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'file', 'upload'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditDelete', true, 'Effacement', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'file', 'delete'])
                                ]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditPreview', true, 'Prévisualisation', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'file', 'preview'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditDuplicate', true, 'Duplication', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'file', 'duplicate'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditExtract', true, 'Extraction', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'file', 'extract'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditCopycut', true, 'Coupé collé', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'file', 'copycut'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('permissionEditPermission', true, 'Permissions', [
                                    'checked' => $this->getData(['permission', $this->getUrl(2), $this->getUrl(3), 'file', 'permission'])
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