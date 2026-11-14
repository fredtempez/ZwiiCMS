<?php
echo Template::formOpen('pageCssEditorForm'); ?>
<?php
$langParam = $this->getSiteContentQueryParam(); ?>
<input type="hidden" name="returnUrl" value="<?php
echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Helper::baseUrl() . 'page/edit/' . $this->getUrl(2) . $langParam; ?>">
<div class="row">
	<div class="col1">
		<?php
echo Template::button('pageCssEditorBack', [ 'class' => 'buttonGrey', 'href' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Helper::baseUrl() . 'page/edit/' . $this->getUrl(2) . $langParam, 'value' => Template::ico('left') ]); ?>
	</div>
	<div class="col2 offset9">
		<?php
echo Template::submit('pageCssEditorSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<?php
echo Template::textarea('pageCssEditorContent', [ 'value' => is_null($this->getData(['page', $this->getUrl(2), 'css'])) ? '' : $this->getData(['page', $this->getUrl(2), 'css']), 'class' => 'editor' ]); ?>
	</div>
</div>
<?php
echo Template::formClose(); ?>
