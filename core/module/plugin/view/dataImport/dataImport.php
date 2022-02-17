<?php echo template::formOpen('pluginImportForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('pluginImportBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'plugin',
			'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('pluginImportSubmit', [
            'value' => 'Appliquer'
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
        <h4>Installer des données de module</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::file('pluginImportFile', [
                            'label' => 'Archive ZIP :',
                            'type' => 2
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('pluginImportPage', $module::$pagesList, [
                            'label' => 'Importer le module dans la page ' . template::flag('site', '20px')
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>