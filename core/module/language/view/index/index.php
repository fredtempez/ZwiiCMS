<?php
echo Template::formOpen('translateForm'); ?>
<div class="row">
	<div class="col1">
		<?php
echo Template::button('translateFormBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl(), 'value' => Template::ico('home') ]); ?>
	</div>
</div>

<div class="tab">
	<?php
echo Template::button('translateUiButton', [ 'value' => 'Interface', 'class' => 'buttonTab' ]); ?>
	<?php
echo Template::button('translateContentButton', [ 'value' => 'Site', 'class' => 'buttonTab' ]); ?>
</div>

<div id="uiContainer" class="tabContent">
	<div class="row">
		<div class="col12">

				<?php
if (language::$languagesUiInstalled): ?>
					<?php
echo Template::table([ 2, 2, 2, 5, 1], language::$languagesUiInstalled, ['Langues installées', 'Version', 'Date', '', '']); ?>
				<?php
endif; ?>

		</div>
	</div>
	<div class="row">
		<div class="col12">

				<?php
if (language::$languagesStore): ?>
					<?php
echo Template::table([2, 1, 2, 6, 1], language::$languagesStore, ['Catalogue de langues', 'Version', 'Date', '', '']); ?>
				<?php
endif; ?>
			</div>
	</div>
</div>
<div id="contentContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Outils de contenus localisés'); ?>
				</h4>
				<div class="col3 offset3">
					<?php
echo Template::button('translateButtonCopyContent', [ 'href' => Helper::baseUrl() . 'language/copy', 'ico' => 'docs', 'disabled' => language::$siteCopy, 'value' => 'Copie de sites localisés' ]); ?>
				</div>
				<div class="col3">
					<?php
echo Template::button('translateButtonAddContent', [ 'href' => Helper::baseUrl() . 'language/add', 'ico' => 'plus', 'class' => 'buttonGreen', 'value' => 'Nouveau site localisé' ]); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">

				<?php
if (language::$languagesInstalled): ?>
					<?php
echo Template::table([4, 5, 1, 1, 1], language::$languagesInstalled, ['Langues installées', '', '', '', '']); ?>
				<?php
endif; ?>
			</div>

	</div>
</div>

<?php
echo Template::formClose(); ?>
