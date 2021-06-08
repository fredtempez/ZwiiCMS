<?php echo template::formOpen('userImportForm'); ?>
<div class="row">
    <div class="col2">
        <?php echo template::button('userImportBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'user',
            'ico' => 'left',
            'value' => 'Retour'
        ]); ?>
    </div>
    <div class="col2">
      <?php echo template::button('translateIndexHelp', [
        'class' => 'buttonHelp',
        'ico' => 'help',
        'value' => 'Aide'
      ]); ?>
    </div>
    <div class="col2 offset6">
		<?php echo template::submit('userImportSubmit', [
				'value' => 'Importer'
			]); ?>
	</div>
</div>
<!-- Aide à propos de la gestion des utilisateurs, view import -->
<div class="helpDisplayContent">
	<?php echo file_get_contents( 'core/module/user/view/import/import.help.html') ;?>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
        <h4>Importation de fichier plat CSV</h4>
            <div class="row">
                <div class="col6">
                      <?php echo template::file('userImportCSVFile', [
                            'label' => 'Liste d\'utilisateurs :'
                      ]); ?>
                </div>
                <div class="col2">
					<?php echo template::select('userImportSeparator', $module::$separators, [
					'label' => 'Séparateur'
					]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col12">
                    <?php echo template::checkbox('userImportNotification', true, 'Envoyer un message de confirmation', [
						'checked' => false
					]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>
<?php if ($module::$users): ?>
    <div class="row">
        <div class="col12 textAlignCenter">
        <?php echo template::table([1, 3, 3, 1, 1, 2, 1], $module::$users, ['Id', 'Nom', 'Prénom','Groupe', 'Pseudo', 'eMail', '']); ?>
        <?php echo template::ico('check');?> Compte créé | <?php echo template::ico('mail');?> Compte créé et notifié | <?php echo template::ico('cancel');?> Erreur dans le fichier, compte non créé.
        </div>
    </div>
<?php  endif;?>
