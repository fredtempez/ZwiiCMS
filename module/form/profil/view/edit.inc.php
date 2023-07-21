<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Formulaire'); ?>
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::checkbox('profilEditFormOption', true, 'Options', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'option'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditFormData', true, 'Gérer les données', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'data'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::checkbox('profilEditFormExport2csv', true, 'Export CSV', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'export2csv'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('profilEditFormDelete', true, 'Supprimer', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'delete'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::checkbox('profilEditFormDeleteAll', true, 'Tout Supprimer', [
                        'checked' => $this->getData(['profil', $this->getUrl(2), $this->getUrl(3), 'form', 'deleteAll'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>