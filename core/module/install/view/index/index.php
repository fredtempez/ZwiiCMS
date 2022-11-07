<?php echo template::formOpen('installForm'); ?>
<h3>
	<?php echo helper::translate('Dans quelle langue utiliserez-vous Zwii ?'); ?>
</h3>
<div class="row">
    <div class="col6 offset3">
            <?php echo template::select('installLanguage', $module::$i18nFiles, [
                'label' =>  'Langues installées',
				'selected' => array_key_exists ('fr_FR', $module::$i18nFiles) ? 'fr_FR': reset($module::$i18nFiles),
            ]); ?>
    </div>
</div>
<div class="row">
	<div class="col3 offset9">
		<?php echo template::submit('installSubmit', [
			'value' => 'Suivant'
		]); ?>
	</div>
</div>
<?php echo template::formClose(); ?>