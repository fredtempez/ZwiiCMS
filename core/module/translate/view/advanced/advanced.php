<?php echo template::formOpen('translateFormAdvanced'); ?>
<div class="row">
    <div class="col2">
        <?php echo template::button('translateFormAdvancedBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'translate',
            'ico' => 'left',
            'value' => 'Retour'
        ]); ?>
    </div>
    <div class="col2 offset8">
        <?php echo template::submit('translateFormAdvancedSubmit'); ?>
    </div>
</div>
<div class="row">
   <div class="col12"> 
        <div class="block">
        <h4>Copie de contenu</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('translateFormAdvancedSource', $module::$languagesInstalled, [
                        'label' => 'Copier les pages et les modules de'
                        ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('translateFormAdvancedTarget', $module::$languagesTarget, [
                        'label' => 'Vers'
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>