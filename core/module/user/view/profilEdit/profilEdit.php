<?php
echo Template::formOpen('profilEditForm'); ?>
<div class="row">
    <div class="col1">
        <?php
echo Template::button('profilEditBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'user/profil', 'value' => Template::ico('left') ]); ?>
    </div>
    <div class="col2 offset9">
        <?php
echo Template::submit('profilEditSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php
echo Helper::translate('Paramètres du profil'); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <div class="row">
                        <div class="col12">
                            <div class="row">
                                <div class="col6">
                                    <?php
echo Template::text('profilEditName', [ 'label' => 'Nom du profil', 'value' => Helper::translate($this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'name'])) ]); ?>
                                </div>
                                <div class="col6">
                                    <?php
echo Template::select('profilEditProfil', user::$profils, [ 'label' => 'Hiérarchie', 'help' => "Rang 9 > rang 1. Le profil de rang 1 n'est pas modifiable.", 'selected' => $this->getUrl(3), 'disabled' => $this->getUrl(3) === '1', ]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col12">
                            <?php
echo Template::text('profilEditDisplayRole', [ 'label' => 'Rôle associé', 'value' => Helper::translate(self::$roles[$this->getUrl(2)]), 'disabled' => true ]); ?>
                            <?php
echo Template::hidden('profilEditRole', [ 'value' => $this->getUrl(2), ]); ?>
                            <?php
echo Template::hidden('profilEditOldProfil', [ 'value' => $this->getUrl(3), ]); ?>
                        </div>
                    </div>
                </div>
                <div class="col6">
                    <?php
echo Template::textarea('profilEditComment', [ 'label' => 'Commentaire', 'value' => Helper::translate($this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'comment'])) ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php
echo Helper::translate("Compte de l'utilisateur"); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php
echo Template::checkbox('profilEditUserEdit', true, 'Éditer', [ 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'user', 'edit']) ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php
echo Helper::translate('Gestionnaire de fichiers'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php
echo Template::checkbox('profilEditFileManager', true, 'Autorisé', [ 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'filemanager']) ]); ?>
                </div>
                <div class="col6">
                    <?php
echo Template::select('profilEditPath', user::$sharePath, [ 'label' => 'Dossier', 'class' => 'filemanager', 'selected' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'path']) ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col12">
                    <div class="block">
                        <h4>
                            <?php
echo Helper::translate('Permissions sur les dossiers'); ?>
                        </h4>
                        <div class="row">
                            <div class="col2">
                                <?php
echo Template::checkbox('profilEditFolderCreate', true, 'Ajouter', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'create']), ]); ?>
                            </div>
                            <div class="col2">
                                <?php
echo Template::checkbox('profilEditFolderDelete', true, 'Effacer', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'delete']) ]); ?>
                            </div>
                            <div class="col2">
                                <?php
echo Template::checkbox('profilEditFolderRename', true, 'Renommer', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'rename']) ]); ?>
                            </div>
                            <div class="col2">
                                <?php
echo Template::checkbox('profilEditFolderCopycut', true, 'Presse papier', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'copycut']) ]); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditFolderChmod', true, 'Droits sur les dossiers', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'folder', 'chmod']) ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col12">
                    <div class="block">
                        <h4>
                            <?php
echo Helper::translate('Permissions sur les fichiers'); ?>
                        </h4>
                        <div class="row">
                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditDownload', true, 'Télécharger', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'download']) ]); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditEdit', true, 'Éditer', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'edit']) ]); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditCreate', true, 'Ajouter', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'create']) ]); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditRename', true, 'Renommer', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'rename']) ]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditUpload', true, 'Téléverser', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'upload']) ]); ?>
                            </div>

                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditDelete', true, 'Effacer', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'delete']) ]); ?>
                            </div>

                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditPreview', true, 'Prévisualiser', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'preview']) ]); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditDuplicate', true, 'Dupliquer', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'duplicate']) ]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditExtract', true, 'Extraire', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'extract']) ]); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditCopycut', true, 'Presse papier', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'copycut']) ]); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilEditChmod', true, 'Droits sur les fichiers', [ 'class' => 'filemanager', 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'file', 'chmod']) ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if ($this->getUrl(2) >= self::ROLE_EDITOR): ?>
    <div class="row">
        <div class="col12">
            <div class="block">
                <h4>
                    <?php
echo Helper::translate('Permissions sur les pages'); ?>
                </h4>
                <div class="row">
                    <div class="col3">
                        <?php
echo Template::checkbox('profilEditPageAdd', true, 'Ajouter', [ 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'add']) ]); ?>
                    </div>
                    <div class="col3">
                        <?php
echo Template::checkbox('profilEditPageEdit', true, 'Éditer', [ 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'edit']) ]); ?>
                    </div>
                    <div class="col3">
                        <?php
echo Template::checkbox('profilEditPageDelete', true, 'Effacer', [ 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'delete']) ]); ?>
                    </div>
                    <div class="col3">
                        <?php
echo Template::checkbox('profilEditPageDuplicate', true, 'Dupliquer', [ 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'duplicate']) ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col3">
                        <?php
echo Template::checkbox('profilEditPageModule', true, 'Module', [ 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'module']) ]); ?>
                    </div>
                    <div class="col3">
                        <?php
echo Template::checkbox('profilEditPagecssEditor', true, 'Éditeur CSS', [ 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'cssEditor']) ]); ?>
                    </div>
                    <div class="col3">
                        <?php
echo Template::checkbox('profilEditPagejsEditor', true, 'Éditeur JS', [ 'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'page', 'jsEditor']) ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="containerModule">
        <?php
foreach (user::$listModules as $moduleId): ?>
            <?php
if (file_exists('module/' . $moduleId . '/profil/view/edit.inc.php')) { include('module/' . $moduleId . '/profil/view/edit.inc.php'); } ?>
        <?php
endforeach; ?>
    </div>
<?php
endif; ?>
<?php
echo Template::formClose(); ?>
