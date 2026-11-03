<div class="row">
	<div class="col1">
		<?php
echo Template::button('configModulesBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl(), 'value' => Template::ico('home') ]); ?>
	</div>
	<div class="col3 offset5">
		<?php
echo Template::button('pluginModulesStore', [ 'href' => Helper::baseUrl() . 'plugin/store', 'value' => 'Installer depuis le catalogue en ligne', 'ico' => 'shopping-basket', ]); ?>
	</div>
	<div class="col3">
		<?php
echo Template::button('pluginStoreUpload', [ 'href' => Helper::baseUrl() . 'plugin/upload', 'value' => 'Installer depuis une archive', 'ico' => 'file-archive', ]); ?>
	</div>
</div>
<?php
if (plugin::$modulesInstalled): ?>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Sauvegarde'); ?>
				</h4>
				<?php
echo Template::table([2, 2, 1, 6, 1], plugin::$modulesInstalled, ['Module', 'Identifiant', 'Version', 'Pages', '']); ?>
			</div>
		</div>
	</div>
<?php
else: ?>
	<?php
echo Template::speech('Aucun module installé.'); ?>
<?php
endif; ?>
<?php
if (plugin::$modulesOrphan): ?>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Modules orphelins'); ?>
				</h4>
				<?php
echo Template::table([2, 2, 1, 6, 1], plugin::$modulesOrphan, ['Module', 'Identifiant', 'Version', '', '']); ?>
			</div>
		</div>
	</div>
<?php
else: ?>
	<?php
echo Template::speech('Aucun module orphelin.'); ?>
<?php
endif; ?>
