<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Téléchargement')); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddDownloadAdd', true, 'Ajouter'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddDownloadEdit', true, 'Éditer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddDownloadDelete', true, 'Effacer'); ?>
                </div>

                <div class="col3">
                    <?php echo template::checkbox('profilAddDownloadOption', true, 'Options'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddDownloadComment', true, 'Gérer les commentaires'); ?>
                </div>
                <div class="col3 downloadAddCommentOptions">
                    <?php echo template::checkbox('profilAddDownloadCommentApprove', true, 'Approuver un commentaire'); ?>
                </div>
                <div class="col3 downloadAddCommentOptions">
                    <?php echo template::checkbox('profilAddDownloadCommentDelete', true, 'Effacer un commentaire'); ?>
                </div>
                <div class="col3 downloadAddCommentOptions">
                    <?php echo template::checkbox('profilAddDownloadCommentDeleteAll', true, 'Effacer tous les commentaires'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddDownloadCategories', true, 'Gérer les catégories'); ?>
                </div>
                <div class="col3 downloadAddCategoryOptions">
                    <?php echo template::checkbox('profilAddDownloadCategoryEdit', true, 'Éditer une catégorie'); ?>
                </div>
                <div class="col3 downloadAddCategoryOptions">
                    <?php echo template::checkbox('profilAddDownloadCategoryDelete', true, 'Effacer une catégorie'); ?>
                </div>
                <div class="col3 downloadAddCategoryOptions">
                    <?php echo template::checkbox('profilAddDownloadCommentDeleteAllStats', true, 'Effacer toutes les statistiques'); ?>
                </div>
            </div>
        </div>
    </div>
</div>