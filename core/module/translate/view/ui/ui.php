<div id="UIContainer" class="tabContent">
    <div class="row">
        <div class="col12">
            <div class="block" id="flagsWrapper">
                <h4>
                    <?php echo template::topic('Traduire ZwiiCMS'); ?>
                </h4>
                <div class="row">
                    <div class="col3">
                        <?php echo template::select('translateI18n', $module::$i18nFiles, [
                            'label' =>  'Traductions installées',
                            'selected' => $this->getData(['config', 'i18n' , 'default']),
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>