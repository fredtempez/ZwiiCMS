<div class="row">
	<div class="col1">
		<?php
echo Template::button('configStoreBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'plugin', 'value' => Template::ico('left') ]); ?>
	</div>
</div>
<?php
if (plugin::$storeList) : ?>
	<?php
echo Template::table([2, 2, 1, 2, 2, 1], plugin::$storeList, ['Catégorie', 'Module', 'Version', 'Date', 'Page', '']); ?>
<?php
else : ?>
	<?php
echo Template::speech('Le catalogue est vide.'); ?>
<?php
endif; ?>
