<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo  helper::translate('Permissions sur le module') . ' ' .  helper::translate('Téléchargement'); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadAdd', true, 'Ajouter'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadEdit', true, 'Éditer'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadDelete', true, 'Effacer'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadOption', true, 'Options'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadComment', true, 'Commentaire'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCommentApprove', true, 'Gérer les commentaires'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCategories', true, 'Catégories'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCategoryEdit', true, 'Editer une catégorie'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCategoryDelete', true, 'Effacer une catégorie'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCommentDelete', true, 'Effacer le commentaire'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCommentDeleteAll', true, 'Effacer tous les commentaires'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddDownloadCommentDeleteAllStats', true, 'Effacer toutes les statistiques'); ?>
                </div>
            </div>
        </div>
    </div>
</div>