<?php echo template::formOpen('searchForm'); ?>
	<div class="row">
		<div class="col12">
			<div class="block">
            <h4>Recherche</h4>
                <div class="row">
                    <div class="col10 verticalAlignBottom">
                        <?php echo template::text('searchMotphraseclef', [
                            'label' => 'Mot ou phrase',
                            'value' => isset($_POST['searchMotphraseclef']) === true ? $_POST['searchMotphraseclef'] : '',
                            'help'  => 'Tout ou partie d\'un mot ou d\'une phrase, sans guillemets. N\'oubliez pas les accents.'
                        ]); ?>
                    </div>
                    <div class="col2 verticalAlignBottom">
                        <?php echo template::submit('pageEditSubmit', [
                            'value' => 'Ok'
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <?php echo template::checkbox('searchMotentier', true, 'Mot entier uniquement', [
                        'checked' => isset($_POST['searchMotentier']) === true ? $_POST['searchMotentier'] : ''
                    ]); ?>
                </div>
			</div>
		</div>
        <div class="col12">
			<div class="block">
				<h4>Résultat(s)</h4>
				<?php if (isset($_POST['result'])) {
                    echo $_POST['result'];
                } else {
                    echo "Rien à afficher";
                } ?>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
