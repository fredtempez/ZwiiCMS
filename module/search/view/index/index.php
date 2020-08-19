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
                        'checked' => $module::$motentier
                    ]); ?>
                </div>
            </div>
		</div>
	</div>
    <div class="col12">
        <div class="block">
            <?php if ( $module::$resultTitle )
                        echo '<h4>'.$module::$resultTitle . '</h4>'; ?>
            <?php if ($module::$resultList )
                        echo '<p>'.$module::$resultList.'</p>';
            ?>
            <?php if ($module::$resultError )
                        echo '<p>'.$module::$resultError.'</p>';
            ?>
        </div>
	</div>
<?php echo template::formClose(); ?>
