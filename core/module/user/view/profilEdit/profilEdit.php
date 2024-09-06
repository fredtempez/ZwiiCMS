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
                <?php echo helper::translate('Paramètres du profil'); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <div class="row">
                        <div class="col12">
                            <div class="row">
                                <div class="col6">
                                    <?php echo template::text('profilEditName', [
                                        'label' => 'Nom du profil',
                                        'value' => helper::translate($this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'name']))
                                    ]); ?>
                                    </div>
                                     <div class="col6">
                                    <?php echo template::select('profilEditProfil', $module::$profils,  [
                                        'label' => 'Hiérarchie',
                                        'help' => 'Rang 9 > rang 1. Le profil de rang 1 n\'est pas modifiable.',
                                        'selected' => $this->getUrl(3),
                                        'disabled' => $this->getUrl(3) === '1',
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col12">
                            <?php echo template::text('profilEditDisplayGroup', [
                                'label' => 'Groupe associé',
                                'value' => helper::translate(self::$groups[$this->getUrl(2)]),
                                'disabled' => true
                            ]); ?>
                            <?php echo template::hidden('profilEditGroup', [
                                'value' => $this->getUrl(2),
                            ]); ?>
                            <?php echo template::hidden('profilEditOldProfil', [
                                'value' => $this->getUrl(3),
                            ]); ?>
                        </div>
                    </div>
                </div>
                <div class="col6">
                    <?php echo template::textarea('profilEditComment', [
                        'label' => 'Commentaire',
                        'value' => helper::translate($this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'comment']))
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
                    <?php echo helper::translate('Permissions sur les pages'); ?>
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
                        <?php echo template::checkbox('profilEditPageDelete', true, 'Effacer', [
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
                        <?php echo template::checkbox('profilEditPageModule', true, 'Module', [
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
        <?php foreach (user::$listModules as $moduleId): ?>
            <?php if (file_exists('module/' . $moduleId . '/profil/view/edit.inc.php')) {
                include('module/' . $moduleId . '/profil/view/edit.inc.php');
            } ?>
        <?php endforeach; ?>
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
                                <?php echo template::checkbox('profilEditFolderCreate', true, 'Ajouter', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'create']),
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilEditFolderDelete', true, 'Effacer', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'delete'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilEditFolderRename', true, 'Renommer', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'rename'])
                                ]); ?>
                            </div>
                            <div class="col2">
                                <?php echo template::checkbox('profilEditFolderCopycut', true, 'Presse papier', [
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
                                <?php echo template::checkbox('profilEditDownload', true, 'Télécharger', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'download'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditEdit', true, 'Éditer', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'edit'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditCreate', true, 'Ajouter', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'create'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditRename', true, 'Renommer', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'rename'])
                                ]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php echo template::checkbox('profilEditUpload', true, 'Téléverser', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'upload'])
                                ]); ?>
                            </div>

                            <div class="col3">
                                <?php echo template::checkbox('profilEditDelete', true, 'Effacer', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'delete'])
                                ]); ?>
                            </div>

                            <div class="col3">
                                <?php echo template::checkbox('profilEditPreview', true, 'Prévisualiser', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'preview'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditDuplicate', true, 'Dupliquer', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'duplicate'])
                                ]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php echo template::checkbox('profilEditExtract', true, 'Extraire', [
                                    'class' => 'filemanager',
                                    'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'extract'])
                                ]); ?>
                            </div>
                            <div class="col3">
                                <?php echo template::checkbox('profilEditCopycut', true, 'Presse papier', [
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