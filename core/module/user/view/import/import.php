<?php echo template::formOpen('userImportForm'); ?>
<div class="row">
		<div class="col2">
			<?php echo template::button('userImportBack', [
                'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'user',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
	</div>
<?php echo template::formClose(); ?>