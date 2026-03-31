<?php
echo Template::formOpen('translateFormCopy'); ?>
<div class="row">
    <div class="col1">
        <?php
echo Template::button('translateFormCopyBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'language', 'value' => Template::ico('left') ]); ?>
    </div>
    <div class="col2 offset9">
        <?php
echo Template::submit('translateFormCopySubmit', [ 'value' => 'Copier' ]); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php
echo Helper::translate('Sélectionnez la langue à copier vers une langue cible'); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php
echo Template::select('translateFormCopySource', language::$languagesInstalled, [ 'label' => 'Source' ]); ?>
                </div>
                <div class="col6">
                    <?php
echo Template::select('translateFormCopyTarget', language::$languagesTarget, [ 'label' => 'Cible' ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo Template::formClose(); ?>
