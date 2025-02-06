<?php echo template::formOpen('pageEditForm'); ?>
	<!-- Variable transmise à TinyMCE -->
<div id="zwii_site_content" data-variable="<?php echo htmlspecialchars(isset($_SESSION['ZWII_SITE_CONTENT']) ? $_SESSION['ZWII_SITE_CONTENT'] : 'home'); ?>"></div>
<div class="row">
	<div class="col1">
		<?php echo template::button('configModulesBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(2),
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1 offset7">
		<?php echo template::button('pageEditDelete', [
			'class' => 'buttonRed',
			'href' => helper::baseUrl() . 'page/delete/' . $this->getUrl(2) . '/' . self::$siteContent,
			'value' => template::ico('trash'),
			'help' => 'Effacer la page'
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('pageEditDuplicate', [
			'href' => helper::baseUrl() . 'page/duplicate/' . $this->getUrl(2) . '/' . self::$siteContent,
			'value' => template::ico('clone'),
			'help' => 'Dupliquer la page'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::submit('pageEditSubmit', [
			'uniqueSubmission' => true
		]); ?>
	</div>
</div>

<div class="tab">
	<?php echo template::button('pageEditContentButton', [
		'value' => 'Contenu',
		'class' => 'buttonTab',
		//'href' => helper::baseUrl() . 'page/register/content/' . $this->geturl(2)
	]); ?>
	<?php echo template::button('pageEditPositionButton', [
		'value' => 'Menu',
		'class' => 'buttonTab',
		//'href' => helper::baseUrl() . 'page/register/position/' . $this->geturl(2)
	]); ?>
	<?php echo template::button('pageEditExtensionButton', [
		'value' => 'Extension',
		'class' => 'buttonTab',
		//'href' => helper::baseUrl() . 'page/register/extension/' . $this->geturl(2)
	]); ?>
	<?php echo template::button('pageEditLayoutButton', [
		'value' => 'Mise en page',
		'class' => 'buttonTab',
		//'href' => helper::baseUrl() . 'page/register/layout/' . $this->geturl(2)
	]); ?>
	<?php echo template::button('pageEditPermissionButton', [
		'value' => 'Permission',
		'class' => 'buttonTab',
		//'href' => helper::baseUrl() . 'page/register/permission/' . $this->geturl(2)
	]); ?>
</div>

<!-- Champ caché pour transmettre l'onglet-->
<?php echo template::hidden('containerSelected'); ?>

<div id="pageEditContentContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php echo helper::translate('Titres'); ?>
				</h4>
				<div class="row">
					<div class="col8">
						<?php echo template::text('pageEditTitle', [
							'label' => 'Titre',
							'value' => $this->getData(['page', $this->getUrl(2), 'title'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::text('pageEditShortTitle', [
							'label' => 'Titre court',
							'value' => $this->getData(['page', $this->getUrl(2), 'shortTitle']),
							'help' => 'Le titre court est affiché dans les menus. Il peut être identique au titre de la page.'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('pageEditHideTitle', true, 'Titre masqué dans la page', [
							'checked' => $this->getData(['page', $this->getUrl(2), 'hideTitle'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('pageEditbreadCrumb', true, 'Fil d\'Ariane dans le titre', [
							'checked' => $this->getData(['page', $this->getUrl(2), 'breadCrumb']),
							'help' => 'Affiche le nom de la page parente suivi du nom de la page, le titre ne doit pas être masqué.'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<?php echo template::textarea('pageEditContent', [
				'class' => 'editorWysiwyg',
				'value' => $this->getPage($this->getUrl(2), self::$siteContent)
			]); ?>
		</div>
	</div>
</div>

<div id="pageEditPositionContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>
					<?php echo helper::translate('Emplacement dans le menu'); ?>
				</h4>
				<div class="blockContainer">
					<div class="row">
						<div class="col4">
							<?php echo template::select('pageEditPosition', [], [
								'label' => 'Position',
								'help' => '\'Ne pas afficher\' crée une page orpheline non accessible par le biais des menus.'
							]); ?>
						</div>
						<div class="col4">
							<?php if ($this->getHierarchy($this->getUrl(2), false)): ?>
								<?php echo template::hidden('pageEditParentPageId', [
									'value' => $this->getData(['page', $this->getUrl(2), 'parentPageId'])
								]); ?>
							<?php else: ?>
								<?php echo template::select('pageEditParentPageId', page::$pagesNoParentId, [
									'label' => 'Page parent',
									'selected' => $this->getData(['page', $this->getUrl(2), 'parentPageId'])
								]); ?>
							<?php endif; ?>
						</div>
						<div class="col4">
							<?php echo template::select('pageEditExtraPosition', page::$extraPosition, [
								'label' => 'Emplacement',
								'selected' => $this->getData(['page', $this->getUrl(2), 'extraPosition']),
								'help' => 'Le menu accessoire est aligné à droite de la barre de menu, c\'est un emplacement réservé aux drapeaux et au bouton de connexion.'
							]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col6">
							<?php echo template::checkbox('pageEditDisable', true, 'Page non cliquable', [
								'checked' => $this->getData(['page', $this->getUrl(2), 'disable']),
								'help' => 'Option active en mode déconnecté uniquement, les pages enfants sont visibles et accessibles.'
							]); ?>
						</div>
						<div class="col6">
							<?php echo template::checkbox('pageEditTargetBlank', true, 'S\'ouvre dans un nouvel onglet', [
								'checked' => $this->getData(['page', $this->getUrl(2), 'targetBlank'])
							]); ?>
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
					<?php echo helper::translate('Options avancées'); ?>
				</h4>
				<div class="blockContainer">
					<div class="row">
						<div class="col3">
							<?php echo template::select('pageTypeMenu', page::$typeMenu, [
								'label' => 'Apparence',
								'selected' => $this->getData(['page', $this->getUrl(2), 'typeMenu'])
							]); ?>
						</div>
						<div class="col9">
							<?php echo template::file('pageIconUrl', [
								'help' => 'Sélectionnez une image ou une icône de petite dimension',
								'language' => $this->getData(['user', $this->getUser('id'), 'language']),
								'label' => 'Icône',
								'value' => $this->getData(['page', $this->getUrl(2), 'iconUrl']),
								'folder' => $this->getData(['page', $this->getUrl(2), 'iconUrl']) ? dirname($this->getData(['page', $this->getUrl(2), 'iconUrl'])) : '',
							]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col6">
							<?php echo template::checkbox('pageEditHideMenuChildren', true, 'Masquer les pages enfants dans le menu horizontal', [
								'checked' => $this->getData(['page', $this->getUrl(2), 'hideMenuChildren'])
							]); ?>
						</div>
						<div class="col6">
							<?php echo template::checkbox('pageEditHideMenuSide', true, 'Masquer la page et les pages enfants dans le menu d\'une barre latérale', [
								'checked' => $this->getData(['page', $this->getUrl(2), 'hideMenuSide']),
								'help' => 'La page est affichée dans un menu horizontal mais pas dans le menu vertical d\'une barre latérale.'
							]); ?>
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
					<?php echo helper::translate('Module'); ?>
				</h4>
				<div class="row">
					<div class="col10">
						<?php echo template::hidden('pageEditModuleRedirect'); ?>
						<?php echo template::select('pageEditModuleId', page::$moduleIds, [
							'help' => 'En cas de changement de module, les données du module précédent seront supprimées.',
							'label' => 'Module',
							'selected' => $this->getData(['page', $this->getUrl(2), 'moduleId'])
						]); ?>
						<?php echo template::hidden('pageEditModuleIdOld', ['value' => $this->getData(['page', $this->getUrl(2), 'moduleId'])]); ?>
						<?php echo template::hidden('pageEditModuleIdOldText', [
							'value' => array_key_exists($this->getData(['page', $this->getUrl(2), 'moduleId']), page::$moduleIds) ? page::$moduleIds[$this->getData(['page', $this->getUrl(2), 'moduleId'])] : ucfirst($this->getData(['page', $this->getUrl(2), 'moduleId']))
						]); ?>
					</div>
					<div class="col2 verticalAlignBottom">
						<?php echo template::button('pageEditModuleConfig', [
							'disabled' => (bool) $this->getData(['page', $this->getUrl(2), 'moduleId']) === false,
							'uniqueSubmission' => true,
							'value' => template::ico('gear')
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col12">
						<?php echo template::select('pageModulePosition', page::$modulePosition, [
							'help' => 'En position libre ajoutez le module en plaçant [MODULE] à l\'endroit voulu dans votre page.',
							'label' => 'Position du module',
							'selected' => $this->getData(['page', $this->getUrl(2), 'modulePosition'])
						]); ?>
					</div>

				</div>
			</div>
		</div>
		<div class="col6">
			<div class="block">
				<h4>
					<?php echo helper::translate('Contenu avancé'); ?>
				</h4>
				<div class="row">
					<div class="col6 offset3">
						<?php echo template::button('pageEditCssEditor', [
							'href' => helper::baseUrl() . 'page/cssEditor/' . $this->getUrl(2),
							'value' => 'Éditeur CSS',
							'help' => 'Feuille de style spécifique à la page.'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col6 offset3">
						<?php echo template::button('pageEditJsEditor', [
							'href' => helper::baseUrl() . 'page/jsEditor/' . $this->getUrl(2),
							'value' => 'Éditeur JS',
							'help' => 'Instructions JS ou jquery spécifiques à la page.'
						]); ?>
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
					<?php echo helper::translate('Mise en page'); ?>
				</h4>
				<div class="blockContainer">
					<div class="row">
						<div class="col6">
							<div class="row">
								<div class="col12">
									<?php echo template::select('pageEditBlock', page::$pageBlocks, [
										'label' => 'Gabarits de page - Barre latérale',
										'help' => 'Pour définir la page comme barre latérale, choisissez l\'option dans la liste.',
										'selected' => $this->getData(['page', $this->getUrl(2), 'block'])
									]); ?>
								</div>
							</div>
						</div>
						<div class="col6">
							<!-- Sélection des barres latérales	 -->
							<?php if ($this->getHierarchy($this->getUrl(2), false, true)): ?>
								<?php echo template::hidden('pageEditBarLeft', [
									'value' => $this->getData(['page', $this->getUrl(2), 'barLeft'])
								]); ?>
							<?php else: ?>
								<?php echo template::select('pageEditBarLeft', page::$pagesBarId, [
									'label' => 'Barre latérale gauche :',
									'selected' => $this->getData(['page', $this->getUrl(2), 'barLeft'])
								]); ?>
							<?php endif; ?>
							<?php if ($this->getHierarchy($this->getUrl(2), false, true)): ?>
								<?php echo template::hidden('pageEditBarRight', [
									'value' => $this->getData(['page', $this->getUrl(2), 'barRight'])
								]); ?>
							<?php else: ?>
								<?php echo template::select('pageEditBarRight', page::$pagesBarId, [
									'label' => 'Barre latérale droite :',
									'selected' => $this->getData(['page', $this->getUrl(2), 'barRight'])
								]); ?>
							<?php endif; ?>
							<?php echo template::select('pageEditDisplayMenu', page::$displayMenu, [
								'label' => 'Contenu du menu vertical',
								'selected' => $this->getData(['page', $this->getUrl(2), 'displayMenu']),
								'help' => 'Par défaut le menu est affiché APRES le contenu de la page. Pour le positionner à un emplacement précis, insérez [MENU] dans le contenu de la page.'
							]); ?>
						</div>
					</div>
					<div class="row navSelect">
						<div class="col4">
							<?php echo template::select('pageEditNavLeft', page::$navIconPosition, [
								'label' => 'Bouton de navigation gauche',
								'selected' => $this->getData(['page', $this->getUrl(2), 'navLeft']),
							]); ?>
						</div>
						<div class="col4">
							<?php echo template::select('pageEditNavTemplate', page::$navIconTemplate, [
								'label' => 'Modèle',
								'selected' => $this->getData(['page', $this->getUrl(2), 'navTemplate']),
							]); ?>
						</div>
						<div class="col4">
							<?php echo template::select('pageEditNavRight', page::$navIconPosition, [
								'label' => 'Bouton de navigation droit',
								'selected' => $this->getData(['page', $this->getUrl(2), 'navRight']),
							]); ?>
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
					<?php echo helper::translate('Permission et référencement'); ?>
				</h4>
				<div class="blockContainer">
					<div class="row">
						<div class='col6'>
							<?php echo template::select('pageEditGroup', self::$groupPublics, [
								'label' => 'Rôle minimal pour accéder à la page',
								'selected' => $this->getData(['page', $this->getUrl(2), 'role']),
								'help' => 'Les groupes de niveau supérieur accèdent à la page.'
							]); ?>
						</div>
						<div class="col6">
							<div class="pageEditGroupProfil displayNone"
								id="pageEditGroupProfil<?php echo self::GROUP_MEMBER; ?>">
								<?php echo template::select('pageEditProfil' . self::GROUP_MEMBER, page::$userProfils[self::GROUP_MEMBER], [
									'label' => 'Profil minimal pour accéder à la page',
									'selected' => $this->getData(['page', $this->getUrl(2), 'profil']),
									'help' => 'Les profils de niveau supérieur accèdent à la page.',
								]); ?>
							</div>
							<div class="pageEditGroupProfil displayNone"
								id="pageEditGroupProfil<?php echo self::GROUP_EDITOR; ?>">
								<?php echo template::select('pageEditProfil' . self::GROUP_EDITOR, page::$userProfils[self::GROUP_EDITOR], [
									'label' => 'Profil minimal pour accéder à la page',
									'selected' => $this->getData(['page', $this->getUrl(2), 'profil']),
									'help' => 'Les profils de niveau supérieur accèdent à la page.',
								]); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class='col12'>
							<?php echo template::text('pageEditMetaTitle', [
								'label' => 'Méta-titre',
								'value' => $this->getData(['page', $this->getUrl(2), 'metaTitle'])
							]); ?>
							<?php echo template::textarea('pageEditMetaDescription', [
								'label' => 'Méta-description',
								//'maxlength' => '500',
								'value' => $this->getData(['page', $this->getUrl(2), 'metaDescription'])
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>