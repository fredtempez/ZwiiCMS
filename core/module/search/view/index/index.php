<?php echo template::formOpen('searchForm'); ?>
	<div class="row">
		<div class="col12">
			<div class="block">
            <h4>Rechercher</h4>
                <div class="row">
                    <div class="col10 verticalAlignBottom">
                        <?php echo template::text('searchMotphraseclef', [
                            'label' => 'Mot ou phrase',
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
                    <div class="col12">
                        <?php echo template::checkbox('searchMotentier', true, 'Mot entier uniquement', [
                            'checked' => false
                        ]); ?>
                    </div>
                </div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
