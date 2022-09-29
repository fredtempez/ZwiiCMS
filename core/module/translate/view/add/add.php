<?php echo template::formOpen('translateAddForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('translateFormBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'translate',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('translateFormSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo template::topic('Ajout d\'une longue de contenu'); ?>
            </h4>
            <div class="row">
                <div class="col4 offset4">
                    <?php echo template::select('translateAddContent', $module::$i18nFiles, [
                        'label' =>  'Langues disponibles'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>