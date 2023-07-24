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
                <div class="col6" s>
                    <?php echo template::checkbox('profilAddDownloadCommentDelete', true, 'Supprimer le commentaire'); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilAddDownloadCommentDeleteAll', true, 'Supprimer tout les commentaires'); ?>
                </div>
            </div>
        </div>
    </div>
</div>