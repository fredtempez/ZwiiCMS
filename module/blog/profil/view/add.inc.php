<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Blog'); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilAddBlogAdd', true, 'Ajouter'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddBlogEdit', true, 'Edit'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddBlogDelete', true, 'Supprimer'); ?>
                </div>
            </div>
            <div class="row">

                <div class="col4">
                    <?php echo template::checkbox('profilAddBlogOption', true, 'Options'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddBlogComment', true, 'Commentaire'); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilAddBlogCommentApprove', true, 'Approuver les commentaires'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6" s>
                    <?php echo template::checkbox('profilAddBlogCommentDelete', true, 'Supprimer les commentaires'); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilAddBlogCommentDeleteAll', true, 'Nettoyer les commentaires'); ?>
                </div>
            </div>
        </div>
    </div>
</div>