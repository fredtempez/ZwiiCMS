<?php
echo Template::formOpen('userImportForm'); ?>
<div class="row">
    <div class="col1">
        <?php
echo Template::button('userImportBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'user', 'value' => Template::ico('left') ]); ?>
    </div>

    <?php
?>
    <div class="col1 offset8">
        <?php
echo Template::button('userImporTemplate', [ 'href' => Helper::baseUrl() . 'user/template', 'value' => Template::ico('table'), 'help' => 'Télécharger un modèle' ]); ?>
    </div>
    <div class="col2">
        <?php
echo Template::submit('userImportSubmit', [ 'value' => 'Importer' ]); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php
echo Helper::translate('Importation de fichier plat CSV'); ?>
            </h4>
            <div class="row">
                <div class="col10">
                    <?php
echo Template::file('userImportCSVFile', [ 'language' => $this->getData(['user', $this->getUser('id'), 'language']), 'label' => 'Liste d\'utilisateurs :' ]); ?>
                </div>
                <div class="col2">
                    <?php
echo Template::select('userImportSeparator', user::$separators, [ 'label' => 'Séparateur' ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col12">
                    <?php
echo Template::checkbox('userImportNotification', true, 'Envoyer un message de confirmation', [ 'checked' => false ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo Template::formClose(); ?>
<?php
if (user::$users): ?>
    <div class="row">
        <div class="col12 textAlignCenter">
            <?php
echo Template::table([1, 2, 2, 1, 1, 1, 2, 1, 1], user::$users, ['Id', 'Nom', 'Prénom', 'Rôle', 'Profil', 'Pseudo', 'eMail', 'Étiquettes', '']); ?>
            <?php
echo Template::ico('check'); ?> Compte créé |
            <?php
echo Template::ico('mail'); ?> Compte créé et notifié |
            <?php
echo Template::ico('cancel'); ?> Erreur dans le fichier ou le compte existe.
        </div>
    </div>
<?php
endif; ?>
