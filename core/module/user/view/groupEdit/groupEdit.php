<?php echo template::formOpen('groupEditForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('groupEditBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'user',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('groupEditSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Opérations sur les fichiers'); ?>
            </h4>
            <div class="row">
                <div class="col12">
                    <div class="row">
                        <div class="col2">
                            <?php echo template::checkbox('groupEditDownload', true, 'Téléchargement', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'file', 'download'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditEdit', true, 'Edition', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'file', 'edit'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditCreate', true, 'Création', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'file', 'create'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditRename', true, 'Nommage', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'file', 'rename'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditUpload', true, 'Téléversement', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'file', 'upload'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditDelete', true, 'Effacement', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'file', 'delete'])
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col2">
                            <?php echo template::checkbox('groupEditPreview', true, 'Prévisualisation', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'file', 'preview'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditDuplicate', true, 'Duplication', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'file', 'duplicate'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditExtract', true, 'Extraction', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'file', 'extract'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditCopycut', true, 'Coupé collé', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'file', 'copycut'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditPermission', true, 'Permissions', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'file', 'permission'])
                            ]); ?>
                        </div>
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
                <?php echo helper::translate('Opérations sur les dossiers'); ?>
            </h4>
            <div class="row">
                <div class="col12">
                    <div class="row">
                        <div class="col2">
                            <?php echo template::checkbox('groupEditFolderCreate', true, 'Création', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'folder', 'create'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditFolderDelete', true, 'Effacement', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'folder', 'delete'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditFolderRename', true, 'Nommage', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'folder', 'rename'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditFolderCopycut', true, 'Coupé collé', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'folder', 'copycut'])
                            ]); ?>
                        </div>
                        <div class="col2">
                            <?php echo template::checkbox('groupEditFolderPermission', true, 'Permissions', [
                                'checked' => $this->getData(['group', $this->getUrl(2), 'folder', 'permission'])
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>