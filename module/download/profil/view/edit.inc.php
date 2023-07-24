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
                <div class="col6">
                    <?php echo template::checkbox('profilEditDownloadCommentDelete', true, 'Supprimer le commentaire', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'download', 'commentDelete'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilEditDownloadCommentDeleteAll', true, 'Supprimer tout les commentaires', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'download', 'commentDeleteAll'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>