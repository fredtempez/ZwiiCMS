<div class="row">
    <div class="col9">
        <div class="row">
            <div class="col12">
                <?php echo plugin::$storeItem['content'];  ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col12">
            <?php
            echo '<img class="downloadItemPicture" src="' . plugin::BASEURL_STORE . 'site/file/source/' . plugin::$storeItem['picture'] .
                '" alt="' . plugin::$storeItem['picture'] . '">';
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col12 textAlignCenter">
            <?php echo helper::translate('Version n°') . plugin::$storeItem['fileVersion']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col12 textAlignCenter">
            &nbsp;<?php echo helper::translate('date') . '&nbsp;' .  plugin::$storeItem['fileDate']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col12 textAlignCenter">
            <span>
                <?php echo helper::translate('Auteur :'); ?>
                <?php echo plugin::$storeItem['fileAuthor']; ?>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col12 textAlignCenter">
            <span>
                <?php echo helper::translate('Licence'); ?>
                &nbsp;
                <?php echo plugin::$storeItem['fileLicense']; ?>
            </span>
        </div>
    </div>
</div>