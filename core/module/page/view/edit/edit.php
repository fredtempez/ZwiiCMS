<?php echo template::formOpen('pageEditForm'); ?>
	<div class="row">
		<div class="col1">
			<?php $href = helper::baseUrl() . $this->getUrl(2); ?>
    		<?php if ($this->getData(['page', $this->getUrl(2), 'moduleId']) === 'redirection' || 'code')$href = helper::baseUrl(); ?>
			<?php echo template::button('pageEditBack', [
				'class' => 'buttonGrey',
				'href' => $href,
				'value' => template::ico('home')
			]); ?>
		</div>
		<div class="col1">
			<?php /**echo template::button('pageEditHelp', [
				'href' => 'https://doc.zwiicms.fr/edition-des-pages',
				'target' => '_blank',
				'value' => template::ico('help'),
				'class' => 'buttonHelp',
				'help' => 'Consulter l\'aide en ligne'
			]); */?>
		</div>
		<div class="col1 offset6">
			<?php echo template::button('pageEditDelete', [
				'class' => 'buttonRed',
				'href' => helper::baseUrl() . 'page/delete/' . $this->getUrl(2) . '&csrf=' . $_SESSION['csrf'],
				'value' => template::ico('trash'),
				'help' => 'Effacer la page'
			]); ?>
		</div>
		<div class="col1">
			<?php echo template::button('pageEditDuplicate', [
				'href' => helper::baseUrl() . 'page/duplicate/' . $this->getUrl(2) . '&csrf=' . $_SESSION['csrf'],
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
			'class' => 'buttonTab'
		]); ?>
		<?php echo template::button('PageEditPositionButton', [
			'value' => 'Menu',
			'class' => 'buttonTab'
		]); ?>
		<?php echo template::button('pageEditExtensionButton', [
			'value' => 'Extension',
			'class' => 'buttonTab'
		]); ?>
		<?php echo template::button('pageEditLayoutButton', [
			'value' => 'Mise en page',
			'class' => 'buttonTab'
		]); ?>
		<?php echo template::button('pageEditPermissionButton', [
			'value' => 'Permission',
			'class' => 'buttonTab'
		]); ?>
	</div>

	<div id="pageEditContentContainer" class="tabContent">
		<div class="row">
			<div class="col12">
				<div class="block">
					<h4>Titres
						<span id="infoHelpButton" class="helpDisplayButton">
							<a href="https://doc.zwiicms.fr/informations-generales" target="_blank"  title="Cliquer pour consulter l'aide en ligne">
								<?php echo template::ico('help', ['margin' => 'left']);?>
							</a>
						</span>
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
					'value' => $this->getPage($this->getUrl(2), self::$i18nContent)
				]); ?>
			</div>
		</div>
	</div>

	<div id="pageEditPositionContainer" class="tabContent">
		<div class="row">
			<div class="col12">
				<div class="block">
					<h4>Emplacement dans le menu
						<span id="positionHelpButton" class="helpDisplayButton">
							<a href="https://doc.zwiicms.fr/emplacement-dans-le-menu" target="_blank"  title="Cliquer pour consulter l'aide en ligne">
								<?php echo template::ico('help', ['margin' => 'left']);?>
							</a>
						</span>
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
								<?php	if($this->getHierarchy($this->getUrl(2), false)): ?>
									<?php echo template::hidden('pageEditParentPageId', [
										'value' => $this->getData(['page', $this->getUrl(2), 'parentPageId'])
									]); ?>
								<?php else: ?>
									<?php echo template::select('pageEditParentPageId', $module::$pagesNoParentId, [
										'label' => 'Page parent',
										'selected' => $this->getData(['page', $this->getUrl(2), 'parentPageId'])
									]); ?>
								<?php endif; ?>
							</div>
							<div class="col4">
									<?php echo template::select('pageEditExtraPosition', $module::$extraPosition, [
										'label' => 'Emplacement :',
										'selected' => $this->getData(['page', $this->getUrl(2), 'extraPosition']),
										'help' => 'Le petit accessoire est aligné à droite de la barre de menu, c\'est un emplacement réservé aux drapeaux et au bouton de connexion.'
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
					<h4>Options avancées
						<span id="advancedHelpButton" class="helpDisplayButton">
							<a href="https://doc.zwiicms.fr/options-d-emplacement-avancee" target="_blank"  title="Cliquer pour consulter l'aide en ligne">
								<?php echo template::ico('help', ['margin' => 'left']);?>
							</a>
						</span>
					</h4>
					<div class="blockContainer">
						<div class="row">
							<div class="col3">
								<?php echo template::select('pageTypeMenu', $module::$typeMenu,[
										'label' => 'Apparence',
										'selected' => $this->getData(['page', $this->getUrl(2), 'typeMenu'])
								]); ?>
							</div>
							<div class="col9">
								<?php echo template::file('pageIconUrl', [
									'help' => 'Sélectionnez une image ou une icône de petite dimension',
									'label' => 'Icône',
									'value' => $this->getData(['page', $this->getUrl(2), 'iconUrl'])
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
								<?php echo template::checkbox('pageEditHideMenuSide', true, 'Masquer la page et les pages enfants dans le menu d\'une barre latérale' , [
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
					<h4>Module</h4>
					<div class="row">
						<div class="col10">
							<?php echo template::hidden('pageEditModuleRedirect'); ?>
							<?php echo template::select('pageEditModuleId', $module::$moduleIds, [
								'help' => 'En cas de changement de module, les données du module précédent seront supprimées.',
								'label' => 'Module',
								'selected' => $this->getData(['page', $this->getUrl(2), 'moduleId'])
							]); ?>
							<?php echo template::hidden('pageEditModuleIdOld',['value' => $this->getData(['page', $this->getUrl(2), 'moduleId'])]); ?>
							<?php echo template::hidden('pageEditModuleIdOldText',[
								'value' => array_key_exists($this->getData(['page', $this->getUrl(2), 'moduleId']),$module::$moduleIds)? $module::$moduleIds[$this->getData(['page', $this->getUrl(2), 'moduleId'])] : ucfirst($this->getData(['page', $this->getUrl(2), 'moduleId']))
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
							<?php echo template::select('pageModulePosition', $module::$modulePosition,[
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
					<h4>Contenu avancé</h4>
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
					<h4>Mise en page
						<span id="layoutHelpButton" class="helpDisplayButton">
							<a href="https://doc.zwiicms.fr/mise-en-page-2" target="_blank"  title="Cliquer pour consulter l'aide en ligne">
								<?php echo template::ico('help', ['margin' => 'left']);?>
							</a>
						</span>
					</h4>
					<div class="blockContainer">
						<div class="row">
							<div class="col6">
								<div class="row">
									<div class="col12">
										<?php echo template::select('pageEditBlock', $module::$pageBlocks, [
												'label' => 'Gabarits de page / Barre latérale',
												'help' => 'Pour définir la page comme barre latérale, choisissez l\'option dans la liste.',
												'selected' => $this->getData(['page', $this->getUrl(2) , 'block'])
										]); ?>
									</div>
								</div>
							</div>
							<div class="col6">
								<!-- Sélection des barres latérales	 -->
								<?php if($this->getHierarchy($this->getUrl(2),false,true)): ?>
									<?php echo template::hidden('pageEditBarLeft', [
										'value' => $this->getData(['page', $this->getUrl(2), 'barLeft'])
									]); ?>
								<?php else: ?>
									<?php echo template::select('pageEditBarLeft', $module::$pagesBarId, [
										'label' => 'Barre latérale gauche :',
										'selected' => $this->getData(['page', $this->getUrl(2), 'barLeft'])
									]); ?>
								<?php endif; ?>
								<?php if($this->getHierarchy($this->getUrl(2),false,true)): ?>
									<?php echo template::hidden('pageEditBarRight', [
										'value' => $this->getData(['page', $this->getUrl(2), 'barRight'])
									]); ?>
								<?php else: ?>
									<?php echo template::select('pageEditBarRight', $module::$pagesBarId, [
										'label' => 'Barre latérale droite :',
										'selected' => $this->getData(['page', $this->getUrl(2), 'barRight'])
									]); ?>
								<?php endif; ?>
								<?php echo template::select('pageEditDisplayMenu', $module::$displayMenu, [
									'label' => 'Contenu du menu vertical',
									'selected' => $this->getData(['page', $this->getUrl(2), 'displayMenu']),
									'help' => 'Par défaut le menu est affiché APRES le contenu de la page. Pour le positionner à un emplacement précis, insérez [MENU] dans le contenu de la page.'
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
					<h4>Permission et référencement
					<span id="seoHelpButton" class="helpDisplayButton">
						<a href="https://doc.zwiicms.fr/permission-et-referencement" target="_blank" title="Cliquer pour consulter l'aide en ligne">
							<?php echo template::ico('help', ['margin' => 'left']);?>
						</a>
					</span>
					</h4>
					<div class="blockContainer">
						<div class="row">
							<div class='col6'>
								<?php echo template::select('pageEditGroup', self::$groupPublics, [
									'label' => 'Groupe requis pour accéder à la page :',
									'selected' => $this->getData(['page', $this->getUrl(2), 'group'])
								]); ?>
							</div>
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
