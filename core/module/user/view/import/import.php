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
    <div class="col2 offset8">
		<?php echo template::submit('userImportSubmit'); ?>
	</div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
        <h4>Importation de fichier plat CSV</h4>
            <div class="row">
                <div class="col5">
                      <?php echo template::file('userImportCSVFile', [
                            'label' => 'Liste d\'utilisateurs :'
                      ]); ?>
                </div>
                <div class="col1">
					<?php echo template::select('userImportSeparator', $module::$separators, [
					'label' => 'Séparateur'
					]); ?>
                </div>
                <div class="col6">
                    <p>Les en-têtes obligatoires sont id, nom, prenom, email et groupe.</p>
                    <p>Valeurs de groupe : 1 membre - 2 éditeur - 3 administrateur </p>
                    <p><a href="core/module/user/ressource/template.csv">Modèle</a> à ouvrir avec un tableur.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>
<?php if ($module::$users): ?>
    <?php echo template::table([1, 3, 3, 1, 1, 2, 1, 1 ], $module::$users, ['Identifiant', 'Nom', 'Prénom','Groupe', 'Pseudo', 'eMail', 'Statut']); ?>  
<?php  endif;?>