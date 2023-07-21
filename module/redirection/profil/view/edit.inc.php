<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo  helper::translate('Permissions sur le module') . ' ' .  helper::translate('Redirection'); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditRedirectionConfig', true, 'Configurer', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'redirection', 'config'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>