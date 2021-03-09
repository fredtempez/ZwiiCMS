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
    <div class="col9">
        <div class="row">
            <div class="col9">
                <?php echo $module::$storeItem['content'];?>
            </div>
        </div>
    </div>
    <div class="col3">
        <div class="row">
            <div class="col12">
                <img src="https://www.zwiicms.fr/site/file/source/<?php echo $module::$storeItem['picture'];?>"/>
            </div>
        </div>
        <div class="row">
            <div class="col12">
            Version <?php echo $module::$storeItem['fileVersion'];?>
            </div>
        </div>
        <div class="row">
            <div class="col12"> 
             Publié le <?php echo $module::$storeItem['fileDate'];?>
            </div>
        </div>
    </div>
</div>