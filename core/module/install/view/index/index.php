<?php echo template::formOpen('installForm'); ?>
<h3>
	<?php echo helper::translate('Dans quelle langue utiliserez-vous Zwii ?'); ?>
</h3>
<div class="row">
    <div class="col6 offset3">
            <?php echo template::select('installLanguage', $module::$i18nFiles, [
                'label' =>  'Langues installées',
				'selected' => isset(self::$i18nUI) ?  self::$i18nUI : 'fr_FR',
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