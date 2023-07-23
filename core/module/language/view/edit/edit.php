<?php echo template::formOpen('translateUIForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('translateUIFormBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'language',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('translateUIFormSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Paramètres'); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::text('translateEditVersion', [
                        'label' => 'Version n°',
                        'value' => $this->getData(['language', $this->getUrl(2), 'version'])
                    ]); ?>
                </div>
                <div class="col6">
						<?php echo template::date('translateEditDate', [
							'label' => 'Date de publication',
                            'type' => 'datetime-local',
							'value' => $this->getData(['language', $this->getUrl(2), 'date'])
						]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <div class="row">
                <?php  foreach ($module::$dialogues as $key => $value) : ?>
                    <div class="col6">
                        <?php echo sprintf('%g -', $key); ?>
                        <?php echo $value['source']; ?>
                    </div>
                    <div class="col6">
                        <?php  echo template::text('translateString' . $key, [
                            'label' => '',
                            'value' => $value['target']
                        ]); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php echo $module::$pages; ?>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>