<?php echo template::formOpen(
    'uploadFile',
    [
        'enctype' => 'multipart/form-data'
    ]
); ?>
<?php if (upload::$datas && !empty(upload::$datas['path']) && $this->isConnected()): ?>
<div class="row">
    <div class="col6 offset3 verticalAlignBottom">
        <?php echo template::upload('uploadInputFile', [
            'label' => upload::$datas['label'],
            'uploadText' => upload::$datas['uploadText'],
            'placeholder' => upload::$datas['placeholder'],
            'accept' => upload::$datas['accept'],
            'required' => true,
            'multiple' => upload::$datas['maxFiles'] > 1 ? true : false,
        ]); ?>
    </div>
    <div class="col2">
        <?php echo template::submit('uploadFileSubmit', [
            'value' => 'Envoyer'
        ]); ?>
    </div>
</div>
<?php else: ?>
    <div class="row">
        <div class="col6 offset3 textAlignCenter">
            <h3><?php echo helper::translate('Veuillez configurer le module !'); ?></h3>
            <?php if ($this->getUser('permission', 'upload', 'config')): ?>
                <a href="<?php echo helper::baseUrl() . $this->getUrl(0) . '/config'; ?>"><?php echo helper::translate('Configurer'); ?></a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php echo template::formClose(); ?>