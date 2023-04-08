<?php echo template::formOpen('snipcartConfigForm'); ?>
<div class="row">
		<div class="col1">
			<?php echo template::button('snipcartConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col1 offset8">
			<?php echo template::button('snipcartConfigBack', [
				'href' => helper::baseUrl() . $this->getUrl(0) . '/option',
				'value' => template::ico('sliders')
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('snipcartConfigSubmit'); ?>
		</div>
	</div>
    <?php echo template::textarea('blogAddContent', [
		'class' => 'editorWysiwygSnipcart'
	]); ?>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Snipcart version n°
	<?php echo $module::VERSION; ?>
</div>
