<?php
echo Template::formOpen('translateAddForm'); ?>
<div class="row">
    <div class="col1">
        <?php
echo Template::button('translateFormBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'language', 'value' => Template::ico('left') ]); ?>
    </div>
    <div class="col2 offset9">
        <?php
echo Template::submit('translateFormSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php
echo Helper::translate('Sélectionnez une langue'); ?>
            </h4>
            <div class="row">
                <div class="col4 offset4">
                    <?php
echo Template::select('translateAddContent', language::$i18nFiles, [ 'label' => 'Langues disponibles' ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo Template::formClose(); ?>
