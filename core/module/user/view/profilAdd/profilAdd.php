<?php
echo Template::formOpen('profilAddForm'); ?>
<div class="row">
    <div class="col1">
        <?php
echo Template::button('profilAddBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'user/profil', 'value' => Template::ico('left') ]); ?>
    </div>
    <div class="col2 offset9">
        <?php
echo Template::submit('profilAddSubmit'); ?>
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
                            <?php
echo Template::text('profilAddName', [ 'label' => 'Nom du profil', 'value' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'name']) ]); ?>
                        </div>
                        <div class="col12">
                            <?php
echo Template::select('profilAddRole', user::$roleProfils, [ 'label' => 'Rôle associé', 'selected' => $this->getUrl(2) ]); ?>
                        </div>
                    </div>
                </div>
                <div class="col6">
                    <?php
echo Template::textarea('profilAddComment', [ 'label' => 'Commentaire', 'value' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'comment']) ]); ?>
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
echo Helper::translate('Compte de l\'utilisateur'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php
echo Template::checkbox('profilAddUserEdit', true, 'Éditer'); ?>
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
echo Template::checkbox('profilAddFileManager', true, 'Autorisé'); ?>
                </div>
                <div class="col6">
                    <?php
echo Template::select('profilAddPath', user::$sharePath, [ 'label' => 'Dossier', 'class' => 'filemanager', ]); ?>
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
echo Template::checkbox('profilAddFolderCreate', true, 'Ajouter', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php
echo Template::checkbox('profilAddFolderDelete', true, 'Effacer', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php
echo Template::checkbox('profilAddFolderRename', true, 'Renommer', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col2">
                                <?php
echo Template::checkbox('profilAddFolderCopycut', true, 'Presse Papier', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilAddFolderChmod', true, 'Droits sur les dossiers', ['class' => 'filemanager']); ?>
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
echo Template::checkbox('profilAddDownload', true, 'Télécharger', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilAddEdit', true, 'Éditer', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilAddCreate', true, 'Ajouter', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilAddRename', true, 'Renommer', ['class' => 'filemanager']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php
echo Template::checkbox('profilAddUpload', true, 'Téléverser', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilAddDelete', true, 'Effacer', ['class' => 'filemanager']); ?>
                            </div>

                            <div class="col3">
                                <?php
echo Template::checkbox('profilAddPreview', true, 'Prévisualiser', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilAddDuplicate', true, 'Dupliquer', ['class' => 'filemanager']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col3">
                                <?php
echo Template::checkbox('profilAddExtract', true, 'Extraire', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilAddCopycut', true, 'Presse papier', ['class' => 'filemanager']); ?>
                            </div>
                            <div class="col3">
                                <?php
echo Template::checkbox('profilAddChmod', true, 'Droits sur les fichiers', ['class' => 'filemanager']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row containerPage">
    <div class="col12">
        <div class="block">
            <h4>
                <?php
echo Helper::translate('Permissions sur les pages'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php
echo Template::checkbox('profilAddPageAdd', true, 'Ajouter'); ?>
                </div>
                <div class="col3">
                    <?php
echo Template::checkbox('profilAddPageEdit', true, 'Éditer'); ?>
                </div>
                <div class="col3">
                    <?php
echo Template::checkbox('profilAddPageDelete', true, 'Effacer'); ?>
                </div>
                <div class="col3">
                    <?php
echo Template::checkbox('profilAddPageDuplicate', true, 'Dupliquer'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col3">
                    <?php
echo Template::checkbox('profilAddPageModule', true, 'Module'); ?>
                </div>
                <div class="col3">
                    <?php
echo Template::checkbox('profilAddPagecssEditor', true, 'Éditeur CSS'); ?>
                </div>
                <div class="col3">
                    <?php
echo Template::checkbox('profilAddPagejsEditor', true, 'Éditeur JS'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="containerModule">
    <?php
foreach (user::$listModules as $moduleId): ?>
        <?php
if (file_exists('module/' . $moduleId . '/profil/view/add.inc.php')) { include('module/' . $moduleId . '/profil/view/add.inc.php'); } ?>
    <?php
endforeach; ?>
</div>
<?php
echo Template::formClose(); ?>
