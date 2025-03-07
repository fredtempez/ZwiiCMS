<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('News')); ?>·
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::checkbox('profilEditNewsAdd', true, 'Ajouter  un article', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'add'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilEditNewsEdit', true, 'Éditer  un article', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'edit'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilEditNewsDelete', true, 'Effacer un article', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'delete'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::checkbox('profilEditNewsOption', true, 'Options des articles', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'news', 'option'])
                    ]); ?>
                </div>

            </div>
        </div>
    </div>
</div>