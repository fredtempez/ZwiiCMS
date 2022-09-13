<div class="row">
	<div class="col9">
		<div class="row">
			<div class="col12">
				<?php echo $module::$storeItem['content'];  ?>
			</div>
		</div>
	</div>
    <div class="row">
        <div class="col12">
            <?php
                echo '<img class="downloadItemPicture" src="' . $module::BASEURL_STORE . 'site/file/source/' . $module::$storeItem['picture'] .
                '" alt="' . $module::$storeItem['picture'] . '">';
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col12 textAlignCenter">
            <?php echo template::transcribe('Version n°') . $module::$storeItem['fileVersion']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col12 textAlignCenter">
            &nbsp;<?php echo template::transcribe('du') . '&nbsp;' .  $module::$storeItem['fileDate']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col12 textAlignCenter">
            <span>
                <?php echo template::transcribe('Auteur :'); ?>
                <?php echo $module::$storeItem['fileAuthor']; ?>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col12 textAlignCenter">
            <span>
                <?php echo template::transcribe('Licence :'); ?>
                &nbsp;
                <?php echo $module::$storeItem['fileLicense']; ?>
            </span>
        </div>
    </div>
</div>