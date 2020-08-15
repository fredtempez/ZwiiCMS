<?php echo template::formOpen('searchForm'); ?>
	<div class="row">
		<div class="col10 offset1">
            <div class="row">
                <div class="col10 verticalAlignBottom">
                    <?php echo template::text('searchMotphraseclef', [
                        'label' => 'Rechercher dans le site',
                        'help'  => 'Saisissez toute ou partie d\'un mot ou d\'une phrase, sans guillemets. N\'oubliez pas les accents.',
                        'value' => $module::$motclef
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
                        'checked' => $module::$motentier
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col12">
        <div class="block">
            <h4><?php echo $module::$resultTitle; ?></h4>
            <?php if (!empty($module::$resultList)) {
                echo $module::$resultList;
            } else {
                echo "Rien à afficher";
            } ?>
        </div>
	</div>
<?php echo template::formClose(); ?>
