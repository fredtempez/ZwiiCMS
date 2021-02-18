<?php echo template::formOpen('addonImportForm'); ?>
<div class="row">
    <div class="col2">
        <?php echo template::button('addonImportBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'addon',
            'ico' => 'left',
            'value' => 'Retour'
        ]); ?>
    </div>
    <div class="col2 offset8">
        <?php echo template::submit('addonImportSubmit', [
            'value' => 'Appliquer'
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
        <h4>Installer des données de module</h4>
            <div class="row">
                <div class="col6 offset3">
                    <?php echo template::file('addonImportFile', [
                            'label' => 'Archive ZIP :',
                            'type' => 2
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>