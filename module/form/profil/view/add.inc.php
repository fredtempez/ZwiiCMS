<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php
echo sprintf('%s %s', helper::translate('Permissions'), helper::translate('Formulaire')); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php
echo template::checkbox('profilAddFormOption', true, 'Options du formulaire'); ?>
                </div>
                <div class="col6">
                    <?php
echo template::checkbox('profilAddFormData', true, 'Consulter les réponses'); ?>
                </div>
                </div>
            <div class="row">
                <div class="col4">
                    <?php
echo template::checkbox('profilAddFormDelete', true, 'Effacer une réponse'); ?>
                </div>
                <div class="col4">
                    <?php
echo template::checkbox('profilAddFormDeleteAll', true, 'Effacer toutes les réponses'); ?>
                </div>
                <div class="col4">
                    <?php
echo template::checkbox('profilAddFormExport2csv', true, 'Exporter toutes les réponses'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
