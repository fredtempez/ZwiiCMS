<?php
/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
*/
echo Template::formOpen('menuIndexForm'); ?>
<!-- Champ caché transportant l'ordre sérialisé -->
<?php
echo Template::hidden('menuEditOrder', ['id' => 'menuEditOrder', 'noDirty' => true]); ?>

<div class="row">
	    <div class="col1">
        <?php
echo Template::button('menuBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl(false), 'value' => Template::ico('home') ]); ?>
    </div>
	<div class="col2 offset9">
		<?php
echo Template::submit('menuIndexSubmit', [ 'uniqueSubmission' => true ]); ?>
	</div>
</div>

<div class="row">

	<!-- Arborescence du menu -->
	<div class="col12">
		<div class="block">
			<h4><?php
echo Helper::translate('Pages du site dans le menu principal'); ?></h4>
			<p class="notice"><?php
echo Helper::translate('Glissez les pages pour réorganiser le menu. Imbriquez une page sous une autre pour en faire une sous-page.'); ?></p>
			<ul id="menuEditTree" class="menuEditNested sortableList">
				<?php
echo menu::$menuHtml; ?>
				<?php
if (empty(menu::$menuHtml)): ?>
					<li class="menuEditEmpty"><?php
echo Helper::translate('Aucune page dans le menu'); ?></li>
				<?php
endif; ?>
			</ul>
		</div>
	</div>

</div>

<div class="row">

	<!-- Pages orphelines -->
	<div class="col12">
		<div class="block">
			<h4><?php
echo Helper::translate('Pages orphelines'); ?></h4>
			<p class="notice"><?php
echo Helper::translate('Ces pages ne sont pas visibles dans le menu. Déplacez-les vers le menu pour les y intégrer.'); ?></p>
			<ul id="menuEditOrphans" class="menuEditNested sortableList">
				<?php
foreach (menu::$orphans as $pageId => $pageData): ?>
					<li class="menuEditItem menuEditOrphan" data-id="<?php
echo htmlspecialchars($pageId, ENT_QUOTES); ?>" data-depth="0">
						<div class="menuEditItemRow">
							<span class="menuEditHandle zwiico-menu"></span>
							<span class="menuEditTitle"><?php
echo isset($pageData['title']) ? $pageData['title'] : htmlspecialchars($pageId, ENT_QUOTES); ?></span>
							<small class="menuEditId">(<?php
echo htmlspecialchars($pageId, ENT_QUOTES); ?>)</small>
						</div>
						<ul class="menuEditNested sortableList"></ul>
					</li>
				<?php
endforeach; ?>
				<?php
if (empty(menu::$orphans)): ?>
					<li class="menuEditEmpty"><?php
echo Helper::translate('Aucune page orpheline'); ?></li>
				<?php
endif; ?>
			</ul>
		</div>
	</div>

</div>

<?php
echo Template::formClose(); ?>
