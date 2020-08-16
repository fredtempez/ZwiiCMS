<?php echo template::formOpen('searchForm'); ?>
	<div class="row">
		<div class="col10 offset1">
            <div class="row">
                <div class="col9 verticalAlignMiddle">
                    <?php echo template::text('searchMotphraseclef', [
                        'placeholder' => $this->getData(['module',$this->getUrl(0),'placeHolder']) ? $this->getData(['module',$this->getUrl(0),'placeHolder']) : $module::$defaultPlaceHolder,
                        'value' => $module::$motclef
                    ]); ?>
                </div>
                <div class="col3 verticalAlignMiddle">
                    <?php echo template::submit('pageEditSubmit', [
                        'value' => $this->getData(['module',$this->getUrl(0),'submitText']) ? $this->getData(['module',$this->getUrl(0),'submitText']) : $module::$defaultButtonText
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
    <?php if ($module::$resultTitle && $module::$resultList): ?>
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
    <?php endif;?>
<?php echo template::formClose(); ?>
