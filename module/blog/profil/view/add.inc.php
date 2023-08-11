<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Blog')); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddBlogAdd', true, 'Ajouter'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddBlogEdit', true, 'Éditer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddBlogDelete', true, 'Effacer'); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilAddBlogOption', true, 'Options'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilAddBlogComment', true, 'Gérer les commentaires'); ?>
                </div>
                <div class="col3 blogAddCommentOptions">
                    <?php echo template::checkbox('profilAddBlogCommentApprove', true, 'Approuver un commentaire'); ?>
                </div>
                <div class="col3 blogAddCommentOptions">
                    <?php echo template::checkbox('profilAddBlogCommentDelete', true, 'Effacer un commentaire'); ?>
                </div>
                <div class="col3 blogAddCommentOptions">
                    <?php echo template::checkbox('profilAddBlogCommentDeleteAll', true, 'Effacer tous les commentaires'); ?>
                </div>
            </div>
        </div>
    </div>
</div>