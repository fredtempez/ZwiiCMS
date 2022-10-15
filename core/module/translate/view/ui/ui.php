<?php echo template::formOpen('translateUIForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('translateUIFormBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'translate',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('translateUIFormSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <div class="row">
                <?php foreach ($module::$languagesUiInstalled as $key => $value) : ?>
                    <div class="col6">
                        <?php echo sprintf('%g -', $key); ?>
                        <?php echo $value['source']; ?>
                    </div>
                    <div class="col6">
                        <?php echo template::text('translateString' . array_search($value['target'], array_keys($module::$languagesUiInstalled)), [
                            'label' => '',
                            'value' => $value['target']
                        ]);  ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php echo $module::$pages; ?>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>