<?php
echo Template::formOpen('configModulesUpload'); ?>
<div class="row">
    <div class="col1">
        <?php
echo Template::button('configModulesBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'plugin', 'value' => Template::ico('left') ]); ?>
    </div>
    <div class="col1">
        <?php
?>
    </div>
    <div class="col2 offset8">
        <?php
echo Template::submit('configModulesSubmit', [ 'value' => 'Valider', 'ico' => 'check' ]); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php
echo Helper::translate('Installer ou mettre à jour un module téléchargé'); ?>
            </h4>
            <div class="row">
                <div class="col6 offset3">
                    <?php
echo Template::file('configModulesInstallation', [ 'language' => $this->getData(['user', $this->getUser('id'), 'language']), 'label' => 'Archive ZIP :', 'type' => 2 ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4 offset3">
                    <?php
echo Template::checkbox('configModulesCheck', true, 'Mise à jour forcée', [ 'checked' => false, 'help' => 'Permet de forcer une mise à jour même si la version du module est inférieure ou égale à celle du module installé.', ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo Template::formClose(); ?>
