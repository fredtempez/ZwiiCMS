<div class="row">
	<div class="col2">
		<?php echo template::button('configItemBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'addon/store',
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
        <h4> <?php echo $module::$storeItem['title'];?>
            ; version  <?php echo $module::$storeItem['fileVersion'];?>
             du <?php echo $module::$storeItem['fileDate'];?></h4>
            <div class="row">
                <div class="col9">
                    <?php echo $module::$storeItem['content'];?>
                </div>
                <div class="col3">
                    <div class="row">
                        <div class="col12">
                            <img src="https://www.zwiicms.fr/site/file/source/<?php echo $module::$storeItem['picture'];?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col12 textAlignCenter">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>