<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo  helper::translate('Permissions sur le module') . ' ' .  helper::translate('Téléchargement'); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditDownloadAdd', true, 'Ajouter', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'download', 'add'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditDownloadEdit', true, 'Éditer', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'download', 'edit'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditDownloadDelete', true, 'Effacer`', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'download', 'delete'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditDownloadOption', true, 'Options', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'download', 'option'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditDownloadComment', true, 'Gérer les commentaires', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'download', 'comment'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditDownloadCommentApprove', true, 'Approuver un commentaire', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'download', 'commentApprove'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCategories', true, 'Catégories', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'categoryManage'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCategoryEdit', true, 'Editer une catégorie', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'categoryEdit'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCategoryDelete', true, 'Effacer une catégorie',[
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'categoryDelete'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCommentDelete', true, 'Effacer le commentaire',[
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'commentDelete'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCommentDeleteAll', true, 'Effacer tous les commentaires',[
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'comment', 'deleteAll'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCommentDeleteAllStats', true, 'Effacer toutes les statistiques',[
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'deleteAllStats'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>