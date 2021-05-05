<?php echo template::formOpen('translateFormCopy'); ?>
<div class="row">
    <div class="col2">
        <?php echo template::button('translateFormCopyBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'translate',
            'ico' => 'left',
            'value' => 'Retour'
        ]); ?>
    </div>
    <div class="col2 offset8">
        <?php echo template::submit('translateFormCopySubmit'); ?>
    </div>
</div>
<div class="row">
   <div class="col12"> 
        <div class="block">
        <h4>Copie de site (traductions rédigées)</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('translateFormCopySource', $module::$languagesInstalled, [
                        'label' => 'Pages et les modules de'
                        ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('translateFormCopyTarget', $module::$languagesTarget, [
                        'label' => 'Vers'
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>