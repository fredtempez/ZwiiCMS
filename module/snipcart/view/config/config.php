<?php echo template::formOpen('snipcartConfigForm'); ?>
<div class="row">
		<div class="col2">
			<?php echo template::button('snipcartConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::submit('snipcartConfigSubmit', [
				'ico' => ''
			]); ?>
		</div>
	</div>
    <?php echo template::textarea('blogAddContent', [
		'class' => 'editorWysiwygSnipcart'
	]); ?>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Snipcart version n°
	<?php echo $module::VERSION; ?>
</div>
