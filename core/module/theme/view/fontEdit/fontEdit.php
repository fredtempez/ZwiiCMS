<?php
echo Template::formOpen('fontEditForm'); ?>
<div class="row">
	<div class="col1">
		<?php
echo Template::button('fontEditBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'theme/font', 'value' => Template::ico('left') ]); ?>
	</div>
	<div class="col1">
		<?php
?>
	</div>
	<div class="col2 offset8">
		<?php
echo Template::submit('fontEditPublish', [ 'value' => 'Valider', 'uniqueSubmission' => true ]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php
echo Helper::translate('Identité de la fonte'); ?>
			</h4>
			<div class="row">
				<div class="col6">
					<?php
echo Template::checkbox('fontEditFontImported', true, 'Fonte en ligne', [ 'checked' => $this->getUrl(2) === 'imported' ? true : false ]); ?>
				</div>
				<div class="col6">
					<?php
echo Template::checkbox('fontEditFontFile', true, 'Fonte installée', [ 'checked' => $this->getUrl(2) === 'files' ? true : false ]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php
echo Template::text('fontEditFontId', [ 'autocomplete' => 'off', 'label' => 'Identifiant', 'value' => $this->getUrl(3), 'readonly' => true ]); ?>
				</div>
				<div class="col6">
					<?php
echo Template::text('fontEditFontName', [ 'autocomplete' => 'off', 'label' => 'Nom', 'value' => $this->getData(['font', $this->getUrl(2), $this->getUrl(3), 'name']) ]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php
echo Template::text('fontEditFontFamilyName', [ 'autocomplete' => 'off', 'label' => 'Famille', 'value' => stripslashes($this->getData(['font', $this->getUrl(2), $this->getUrl(3), 'font-family'])) ]); ?>
				</div>
			</div>
			<div class="row" id="containerfontEditFile">
				<div class="col12">
				<?php
echo Template::text('fontEditFile', [ 'label' => 'Fichier de fonte (Format WOFF)', 'value' => $this->getUrl(2) === 'files' ? $this->getData(['font', $this->getUrl(2), $this->getUrl(3), 'resource']) : '', 'readonly' => true ]); ?>
				</div>
			</div>
			<div class="row" id="containerfontEditUrl">
				<div class="col12">
					<?php
echo Template::text('fontEditUrl', [ 'label' => 'Url du fichier de fonte', 'value' => $this->getUrl(2) === 'imported' ? $this->getData(['font', $this->getUrl(2), $this->getUrl(3), 'resource']) : '' ]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
echo Template::formClose(); ?>
