<?php echo template::formOpen('pageCssEditorForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('pageCssEditorBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(2),
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('pageCssEditorSubmit'); ?>
	</div>
</div>
<div class="row">
<div class="col12">
		<?php echo helper::translate('Ne pas saisir les balises') . htmlentities(' <style></style>'); ?>
	</div>
	<div class="col12">
		<?php echo template::textarea('pageCssEditorContent', [
			'value' => empty($this->getData(['page', $this->getUrl(2), 'css'])) ? '' : $this->getData(['page', $this->getUrl(2), 'css']),
			'class' => 'editor'
		]); ?>
	</div>
</div>
<?php echo template::formClose(); ?>