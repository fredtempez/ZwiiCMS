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
                <div class="col6">
                    bla bla expliquant le format d'import à respecter
                </div>
                <div class="col6">
                      <?php echo template::file('userImportCSVFile', [
                            'label' => 'Liste d\'utilisateurs :'
                      ]); ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>