<?php echo template::formOpen('searchForm'); ?>
<div class="row">
    <div class="col10 offset1">
        <div class="row">
            <div class="col9 verticalAlignMiddle">
                <?php echo template::text('searchMotphraseclef', [
                    'placeholder' => $this->getData(['module', $this->getUrl(0), 'config', 'placeHolder']) ? $this->getData(['module', $this->getUrl(0), 'config', 'placeHolder']) : 'Un ou plusieurs mots clef séparés par un espace',
                    'value' => $module::$motclef
                ]); ?>
            </div>
            <?php $col = empty($this->getData(['module', $this->getUrl(0), 'config', 'submitText'])) ? 'col1' : 'col3'; ?>
            <div class="<?php echo $col; ?> verticalAlignMiddle">
                <?php echo template::submit('pageEditSubmit', [
                    'value' => $this->getData(['module', $this->getUrl(0), 'config', 'submitText']),
                    'ico' => 'search'
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col12">
                <?php echo template::checkbox('searchMotentier', true, 'Mots approchants', [
                    'checked' => $module::$motentier,
                ]); ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <?php if ($module::$resultTitle)
            echo $module::$resultTitle; 
        ?>
        <?php if ($module::$resultList)
            echo '<p>' . $module::$resultList . '</p>';
        ?>
        <?php if ($module::$resultError)
            echo '<p>' . $module::$resultError . '</p>';
        ?>
    </div>
</div>
<?php echo template::formClose(); ?>