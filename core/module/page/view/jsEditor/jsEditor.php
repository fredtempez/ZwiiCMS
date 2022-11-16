<?php echo template::formOpen('pageJsEditorForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('pageJsEditorBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(2),
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('pageJsEditorSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<?php echo helper::translate('Ne pas saisir les balises') . htmlentities(' <script></script>'); ?>
	</div>
	<div class="col12">
		<?php echo template::textarea('pageJsEditorContent', [
			'value' => empty($this->getData(['page', $this->getUrl(2), 'js'])) ? '' : $this->getData(['page', $this->getUrl(2), 'js']),
			'class' => 'editor'
		]); ?>
	</div>
</div>
<?php echo template::formClose(); ?>