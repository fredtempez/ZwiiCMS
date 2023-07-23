<?php echo template::formOpen('translateFormCopy'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('translateFormCopyBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'language',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('translateFormCopySubmit', [
            'value' => 'Copier'
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Sélectionnez la langue à copier vers une langue cible'); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('translateFormCopySource', $module::$languagesInstalled, [
                        'label' => 'Source'
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('translateFormCopyTarget', $module::$languagesTarget, [
                        'label' => 'Cible'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>