<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Formulaire')); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('profilEditFormOption', true, 'Options du formulaire', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'option'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilEditFormData', true, 'Consulter les réponses', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'data'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditFormDelete', true, 'Effacer une réponse', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'delete'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditFormDeleteAll', true, 'Effacer toutes les réponses', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'deleteAll'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditFormExport2csv', true, 'Exporter toutes les réponses', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'export2csv'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>