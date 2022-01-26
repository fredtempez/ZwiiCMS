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
                <div class="col6 offset3">
                    <?php echo template::select('pluginExportSelectPage',  $module::$pagesList , [
                            'label' => 'Export depuis la page ' . template::flag('site', '20px'),
                            'help' => 'Pour exporter les données de module d\'une autre langue traduite, sélectionnez-la puis revenez sur cet écran' 
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>