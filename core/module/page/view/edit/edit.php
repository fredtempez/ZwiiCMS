<?php
echo Template::formOpen('pageEditForm'); ?>
	<!-- Variable transmise à TinyMCE -->
<div id="zwii_site_content" data-variable="<?php
echo htmlspecialchars(isset($_SESSION['ZWII_SITE_CONTENT']) ? $_SESSION['ZWII_SITE_CONTENT'] : 'home'); ?>"></div>
<div class="row">
	<div class="col1">
		<?php
echo Template::button('configModulesBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . $this->getUrl(2), 'value' => Template::ico('left') ]); ?>
	</div>
	<div class="col1 offset7">
		<?php
echo Template::button('pageEditDelete', [ 'class' => 'buttonRed', 'href' => Helper::baseUrl() . 'page/delete/' . $this->getUrl(2) . '/' . self::$siteContent, 'value' => Template::ico('trash'), 'help' => 'Effacer la page' ]); ?>
	</div>
	<div class="col1">
		<?php
echo Template::button('pageEditDuplicate', [ 'href' => Helper::baseUrl() . 'page/duplicate/' . $this->getUrl(2) . '/' . self::$siteContent, 'value' => Template::ico('clone'), 'help' => 'Dupliquer la page' ]); ?>
	</div>
	<div class="col2">
		<?php
echo Template::submit('pageEditSubmit', [ 'uniqueSubmission' => true ]); ?>
	</div>
</div>

<div class="tab">
	<?php
echo Template::button('pageEditContentButton', [ 'value' => 'Contenu', 'class' => 'buttonTab', ]); ?>
	<?php
echo Template::button('pageEditPositionButton', [ 'value' => 'Menu', 'class' => 'buttonTab', ]); ?>
	<?php
echo Template::button('pageEditExtensionButton', [ 'value' => 'Extension', 'class' => 'buttonTab', ]); ?>
	<?php
echo Template::button('pageEditLayoutButton', [ 'value' => 'Mise en page', 'class' => 'buttonTab', ]); ?>
	<?php
echo Template::button('pageEditPermissionButton', [ 'value' => 'Permission & SEO', 'class' => 'buttonTab', ]); ?>
</div>

<!-- Champ caché pour transmettre l'onglet-->
<?php
echo Template::hidden('containerSelected'); ?>

<div id="pageEditContentContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Titres'); ?>
				</h4>
				<div class="row">
					<div class="col8">
						<?php
echo Template::text('pageEditTitle', [ 'label' => 'Titre', 'value' => $this->getData(['page', $this->getUrl(2), 'title']) ]); ?>
					</div>
					<div class="col4">
						<?php
echo Template::text('pageEditShortTitle', [ 'label' => 'Titre court', 'value' => $this->getData(['page', $this->getUrl(2), 'shortTitle']), 'help' => 'Le titre court est affiché dans les menus. Il peut être identique au titre de la page.' ]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php
echo Template::checkbox('pageEditHideTitle', true, 'Titre masqué dans la page', [ 'checked' => $this->getData(['page', $this->getUrl(2), 'hideTitle']) ]); ?>
					</div>
					<div class="col6">
						<?php
echo Template::checkbox('pageEditbreadCrumb', true, 'Fil d\'Ariane dans le titre', [ 'checked' => $this->getData(['page', $this->getUrl(2), 'breadCrumb']), 'help' => 'Affiche le nom de la page parente suivi du nom de la page, le titre ne doit pas être masqué.' ]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<?php
echo Template::textarea('pageEditContent', [ 'class' => 'editorWysiwyg', 'value' => $this->getPage($this->getUrl(2), self::$siteContent) ]); ?>
		</div>
	</div>
</div>

<div id="pageEditPositionContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Emplacement dans le menu'); ?>
				</h4>
				<div class="blockContainer">
					<div class="row">
						<div class="col4">
							<?php
echo Template::select('pageEditPosition', [], [ 'label' => 'Position', 'help' => '\'Ne pas afficher\' crée une page orpheline non accessible par le biais des menus.' ]); ?>
						</div>
						<div class="col4">
							<?php
echo Template::select('pageEditParentPageId', page::$hierarchicalPageOptions, [ 'label' => 'Page parent', 'selected' => $this->getData(['page', $this->getUrl(2), 'parentPageId']), 'help' => 'Sélectionnez une page parent. Les pages sont affichées hiérarchiquement selon la profondeur configurée dans le thème.', 'class' => 'parentSelect' ]); ?>
						</div>
						<div class="col4">
							<?php
echo Template::select('pageEditExtraPosition', page::$extraPosition, [ 'label' => 'Emplacement', 'selected' => $this->getData(['page', $this->getUrl(2), 'extraPosition']), 'help' => 'Le menu accessoire est aligné à droite de la barre de menu, c\'est un emplacement réservé aux drapeaux et au bouton de connexion.' ]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col6">
							<?php
echo Template::checkbox('pageEditDisable', true, 'Page non cliquable', [ 'checked' => $this->getData(['page', $this->getUrl(2), 'disable']), 'help' => 'Option active en mode déconnecté uniquement, les pages enfants sont visibles et accessibles.' ]); ?>
						</div>
						<div class="col6">
							<?php
echo Template::checkbox('pageEditTargetBlank', true, 'S\'ouvre dans un nouvel onglet', [ 'checked' => $this->getData(['page', $this->getUrl(2), 'targetBlank']) ]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Options avancées'); ?>
				</h4>
				<div class="blockContainer">
					<div class="row">
						<div class="col3">
							<?php
echo Template::select('pageTypeMenu', page::$typeMenu, [ 'label' => 'Apparence', 'selected' => $this->getData(['page', $this->getUrl(2), 'typeMenu']) ]); ?>
						</div>
						<div class="col9">
							<?php
echo Template::file('pageIconUrl', [ 'help' => 'Sélectionnez une image ou une icône de petite dimension', 'language' => $this->getData(['user', $this->getUser('id'), 'language']), 'label' => 'Icône', 'value' => $this->getData(['page', $this->getUrl(2), 'iconUrl']), 'folder' => $this->getData(['page', $this->getUrl(2), 'iconUrl']) ? dirname($this->getData(['page', $this->getUrl(2), 'iconUrl'])) : '', ]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col6">
							<?php
echo Template::checkbox('pageEditHideMenuChildren', true, 'Masquer les pages enfants dans le menu horizontal', [ 'checked' => $this->getData(['page', $this->getUrl(2), 'hideMenuChildren']) ]); ?>
						</div>
						<div class="col6">
							<?php
echo Template::checkbox('pageEditHideMenuSide', true, 'Masquer la page et les pages enfants dans le menu d\'une barre latérale', [ 'checked' => $this->getData(['page', $this->getUrl(2), 'hideMenuSide']), 'help' => 'La page est affichée dans un menu horizontal mais pas dans le menu vertical d\'une barre latérale.' ]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="pageEditExtensionContainer" class="tabContent">
	<div class="row">
		<div class="col6">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Module'); ?>
				</h4>
				<div class="row">
					<div class="col10">
						<?php
echo Template::hidden('pageEditModuleRedirect'); ?>
						<?php
echo Template::select('pageEditModuleId', page::$moduleIds, [ 'help' => 'En cas de changement de module, les données du module précédent seront supprimées.', 'label' => 'Module', 'selected' => $this->getData(['page', $this->getUrl(2), 'moduleId']) ]); ?>
						<?php
echo Template::hidden('pageEditModuleIdOld', ['value' => $this->getData(['page', $this->getUrl(2), 'moduleId'])]); ?>
						<?php
echo Template::hidden('pageEditModuleIdOldText', [ 'value' => array_key_exists($this->getData(['page', $this->getUrl(2), 'moduleId']), page::$moduleIds) ? page::$moduleIds[$this->getData(['page', $this->getUrl(2), 'moduleId'])] : ucfirst($this->getData(['page', $this->getUrl(2), 'moduleId'])) ]); ?>
					</div>
					<?php
if ($this->getUser('permission',$this->getData(['page', $this->getUrl(2), 'moduleId']), 'config')):?>
					<div class="col2 verticalAlignBottom">
						<?php
echo Template::button('pageEditModuleConfig', [ 'uniqueSubmission' => true, 'value' => Template::ico('gear'), 'disabled' => empty($this->getData(['page', $this->getUrl(2), 'moduleId'])) , ]); ?>
					</div> 
					<?php
endif;?>
				</div>
				<div class="row">
					<div class="col12">
						<?php
echo Template::select('pageModulePosition', page::$modulePosition, [ 'help' => 'En position libre ajoutez le module en plaçant [MODULE] à l\'endroit voulu dans votre page.', 'label' => 'Position du module', 'selected' => $this->getData(['page', $this->getUrl(2), 'modulePosition']) ]); ?>
					</div>

				</div>
			</div>
		</div>
		<div class="col6">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Contenu avancé'); ?>
				</h4>
				<div class="row">
					<div class="col6 offset3">
						<?php
echo Template::button('pageEditCssEditor', [ 'href' => Helper::baseUrl() . 'page/cssEditor/' . $this->getUrl(2), 'value' => 'Éditeur CSS', 'help' => 'Feuille de style spécifique à la page.' ]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6 offset3">
						<?php
echo Template::button('pageEditJsEditor', [ 'href' => Helper::baseUrl() . 'page/jsEditor/' . $this->getUrl(2), 'value' => 'Éditeur JS', 'help' => 'Instructions JS ou jquery spécifiques à la page.' ]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="pageEditLayoutContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Gabarits et navigation'); ?>
				</h4>
				<div class="blockContainer">
					<div class="row">
						<div class="col6">
							<div class="row">
								<div class="col12">
									<?php
echo Template::select('pageEditBlock', page::$pageBlocks, [ 'label' => 'Gabarits de page - Barre latérale', 'help' => 'Pour définir la page comme barre latérale, choisissez l\'option dans la liste.', 'selected' => $this->getData(['page', $this->getUrl(2), 'block']) ]); ?>
								</div>
							</div>
						</div>
						<div class="col6">
							<!-- Sélection des barres latérales	 -->
							<?php
if ($this->getHierarchy($this->getUrl(2), false, true)): ?>
								<?php
echo Template::hidden('pageEditBarLeft', [ 'value' => $this->getData(['page', $this->getUrl(2), 'barLeft']) ]); ?>
							<?php
else: ?>
								<?php
echo Template::select('pageEditBarLeft', page::$pagesBarId, [ 'label' => 'Barre latérale gauche :', 'selected' => $this->getData(['page', $this->getUrl(2), 'barLeft']) ]); ?>
							<?php
endif; ?>
							<?php
if ($this->getHierarchy($this->getUrl(2), false, true)): ?>
								<?php
echo Template::hidden('pageEditBarRight', [ 'value' => $this->getData(['page', $this->getUrl(2), 'barRight']) ]); ?>
							<?php
else: ?>
								<?php
echo Template::select('pageEditBarRight', page::$pagesBarId, [ 'label' => 'Barre latérale droite :', 'selected' => $this->getData(['page', $this->getUrl(2), 'barRight']) ]); ?>
							<?php
endif; ?>
							<?php
echo Template::select('pageEditDisplayMenu', page::$displayMenu, [ 'label' => 'Contenu du menu vertical', 'selected' => $this->getData(['page', $this->getUrl(2), 'displayMenu']), 'help' => 'Par défaut le menu est affiché APRES le contenu de la page. Pour le positionner à un emplacement précis, insérez [MENU] dans le contenu de la page.' ]); ?>
						</div>
					</div>
					<div class="row navSelect">
						<div class="col4">
							<?php
echo Template::select('pageEditNavLeft', page::$navIconPosition, [ 'label' => 'Bouton de navigation gauche', 'selected' => $this->getData(['page', $this->getUrl(2), 'navLeft']), ]); ?>
						</div>
						<div class="col4">
							<?php
echo Template::select('pageEditNavTemplate', page::$navIconTemplate, [ 'label' => 'Modèle', 'selected' => $this->getData(['page', $this->getUrl(2), 'navTemplate']), ]); ?>
						</div>
						<div class="col4">
							<?php
echo Template::select('pageEditNavRight', page::$navIconPosition, [ 'label' => 'Bouton de navigation droit', 'selected' => $this->getData(['page', $this->getUrl(2), 'navRight']), ]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="pageEditPermissionContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php
echo Helper::translate('Permission et référencement'); ?>
				</h4>
				<div class="blockContainer">
					<div class="row">
						<div class='col6'>
							<?php
echo Template::select('pageEditRole', self::$rolePublics, [ 'label' => 'Rôle minimal pour accéder à la page', 'selected' => $this->getData(['page', $this->getUrl(2), 'role']), 'help' => 'Les rôles de niveau supérieur accèdent à la page.' ]); ?>
						</div>
						<div class="col6">
							<div class="pageEditGroupProfil displayNone"
								id="pageEditGroupProfil<?php
echo self::ROLE_MEMBER; ?>">
								<?php
echo Template::select('pageEditProfil' . self::ROLE_MEMBER, page::$userProfils[self::ROLE_MEMBER], [ 'label' => 'Profil minimal pour accéder à la page', 'selected' => $this->getData(['page', $this->getUrl(2), 'profil']), 'help' => 'Les profils de niveau supérieur accèdent à la page.', ]); ?>
							</div>
							<div class="pageEditGroupProfil displayNone"
								id="pageEditGroupProfil<?php
echo self::ROLE_EDITOR; ?>">
								<?php
echo Template::select('pageEditProfil' . self::ROLE_EDITOR, page::$userProfils[self::ROLE_EDITOR], [ 'label' => 'Profil minimal pour accéder à la page', 'selected' => $this->getData(['page', $this->getUrl(2), 'profil']), 'help' => 'Les profils de niveau supérieur accèdent à la page.', ]); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class='col12'>
							<?php
echo Template::text('pageEditMetaTitle', [ 'label' => 'Méta-titre', 'value' => $this->getData(['page', $this->getUrl(2), 'metaTitle']) ]); ?>
							<?php
echo Template::textarea('pageEditMetaDescription', [ 'label' => 'Méta-description', 'value' => $this->getData(['page', $this->getUrl(2), 'metaDescription']) ]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
echo Template::formClose(); ?>
