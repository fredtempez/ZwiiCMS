<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Blog')); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilEditBlogAdd', true, 'Ajouter', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'add'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilEditBlogEdit', true, 'Éditer', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'edit'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilEditBlogDelete', true, 'Effacer', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'delete'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilEditBlogOption', true, 'Options', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'option'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilEditBlogComment', true, 'Gérer les commentaires', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'comment'])
                    ]); ?>
                </div>
                <div class="col3 blogEditCommentOptions">
                    <?php echo template::checkbox('profilEditBlogCommentApprove', true, 'Approuver un commentaire', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'commentApprove'])
                    ]); ?>
                </div>
                <div class="col3 blogEditCommentOptions">
                    <?php echo template::checkbox('profilEditBlogCommentDelete', true, 'Effacer un commentaire', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'commentDelete'])
                    ]); ?>
                </div>
                <div class="col3 blogEditCommentOptions">
                    <?php echo template::checkbox('profilEditBlogCommentDeleteAll', true, 'Effacer tous les commentaires', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'blog', 'commentDeleteAll'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>