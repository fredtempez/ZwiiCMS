<?php echo template::formOpen('folderConfig'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('folderConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col2 offset9">
				<?php echo template::submit('folderConfigSubmit'); ?>
		</div>
	</div>
	<div class='row'>
		<div class="col12">
			<div class="block">
                <h4><?php echo helper::translate('Paramètres'); ?></h4>
                <div class="row">
                    <div class="col6">
                    <?php echo template::select('folderEditPath', $module::$sharePath, [
                        'label' => 'Dossier',
                        'class' => 'filemanager',
                        'selected' => '.' . $this->getData(['module', $this->getUrl(0), 'path'])
                    ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>