<?php
echo template::formOpen('pageEditForm');
	// Mise à jour de la liste des pages pour TinyMCE
	$this->pages2Json(); ?>
	<div class="row">
	<div class="col2">
			<?php $href = helper::baseUrl() . $this->getUrl(2); ?>
      <?php if ($this->getData(['page', $this->getUrl(2), 'moduleId']) === 'redirection' || 'code')$href = helper::baseUrl(); ?>
			<?php echo template::button('pageEditBack', [
				'class' => 'buttonGrey',
				'href' => $href,
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset6">
			<?php echo template::button('pageEditDelete', [
				'class' => 'buttonRed',
				'href' => helper::baseUrl() . 'page/delete/' . $this->getUrl(2) . '&csrf=' . $_SESSION['csrf'],
				'value' => 'Supprimer',
				'ico' => 'cancel'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('pageEditSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Informations générales</h4>
				<div class="row">
					<div class="col8">
						<?php echo template::text('pageEditTitle', [
							'label' => 'Titre',
							'value' => $this->getData(['page', $this->getUrl(2), 'title'])
						]); ?>
					</div>
					<div class="col4">
						<div class="row">
							<div class="col9">
								<?php echo template::hidden('pageEditModuleRedirect'); ?>
								<?php echo template::select('pageEditModuleId', $module::$moduleIds, [
									'help' => 'En cas de changement de module, les données du module précédent seront supprimées.',
									'label' => 'Module',
									'selected' => $this->getData(['page', $this->getUrl(2), 'moduleId'])
								]); ?>
								<?php echo template::hidden('pageEditModuleIdOld',['value' => $this->getData(['page', $this->getUrl(2), 'moduleId'])]); ?>
								<?php echo template::hidden('pageEditModuleIdOldText',[
									'value' => $this->getData(['page', $this->getUrl(2), 'moduleId']) !== '' ? $module::$moduleNames[$this->getData(['page', $this->getUrl(2), 'moduleId'])] : ''
									]); ?>
							</div>
							<div class="col3 verticalAlignBottom">
								<?php echo template::button('pageEditModuleConfig', [
									'disabled' => (bool) $this->getData(['page', $this->getUrl(2), 'moduleId']) === false,
									'uniqueSubmission' => true,	
									'value' => template::ico('gear')
								]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col4">
						<?php echo template::select('pageTypeMenu', $module::$typeMenu,[
								'help' => 'La page peut être représentée par une image de petite taille.',
								'label' => 'Apparence dans le menu horizontal',
								'selected' => $this->getData(['page', $this->getUrl(2), 'typeMenu'])
						]); ?>
					</div>
					<div class="col4">
                        <?php echo template::file('pageIconUrl', [
                            'label' => 'Icône du menu',
                            'value' => $this->getData(['page', $this->getUrl(2), 'iconUrl'])
                        ]); ?>
                    </div>
					<div class="col4">
					<?php echo template::select('configModulePosition', $module::$modulePosition,[
							'help' => 'En position libre ajoutez le module en plaçant [MODULE] à l\'endroit voulu dans votre page.',
							'label' => 'Position du module dans la page',
							'selected' => $this->getData(['page', $this->getUrl(2), 'modulePosition'])
						]); ?>
					</div>
                </div>
			</div>
		</div>
	</div>
	<?php echo template::textarea('pageEditContent', [
		'class' => 'editorWysiwyg',
		'value' => $this->getData(['page', $this->getUrl(2), 'content'])
	]); ?>
	<div class="row">
		<div class="col6" id="pageEditBlockLayout">
			<div class="block" >
				<h4>Mise en page</h4>
				<div class="row">
					<div class="col12">
						<?php echo template::select('pageEditBlock', $module::$pageBlocks, [
								'label' => 'Gabarits de page / Barre latérale',
								'help' => 'Pour définir la page comme barre latérale, choisissez l\'option dans la liste.',
								'selected' => $this->getData(['page', $this->getUrl(2) , 'block'])
						]); ?>	
					</div>
					<div class="col12">			
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
					</div>
					<div class="col12">								
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
					</div>
					<div class="col6">
						<?php echo template::checkbox('pageEditHideTitle', true, 'Titre masqué', [
							'checked' => $this->getData(['page', $this->getUrl(2), 'hideTitle'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('pageEditbreadCrumb', true, 'Fil d\'Ariane', [
							'checked' => $this->getData(['page', $this->getUrl(2), 'breadCrumb'])
						]); ?>
					</div>
					<div class="col12">
						<?php echo template::select('pageEditDisplayMenu', $module::$displayMenu, [
							'label' => 'Configuration du menu vertical',
							'selected' => $this->getData(['page', $this->getUrl(2), 'displayMenu']),
							'help' => 'Par défaut le menu est affiché APRES le contenu de la page. Pour le positionner à un emplacement précis, insérez [MENU] dans le contenu de la page.'
						]); ?>
					</div>
				</div>
			</div>
		</div>			
		<div class="col6"  id="pageEditMenu">
			<div class="block">
				<h4>Emplacement</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::select('pageEditPosition', [], [
							'label' => 'Position',
							'help' => '\'Ne pas afficher\' crée une page orpheline non accessible par le biais des menus.'
						]); ?>	
					</div>				
					<div class="col6">
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
				</div>								
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('pageEditTargetBlank', true, 'Nouvel onglet', [
							'checked' => $this->getData(['page', $this->getUrl(2), 'targetBlank'])
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('pageEditDisable', true, 'Désactivée', [
							'disabled' => (bool) ($this->getdata(['config','homePageId']) === $this->getUrl(2)) ? true : false,
							'checked' => (bool) ($this->getdata(['config','homePageId']) === $this->getUrl(2)) ? false : $this->getData(['page', $this->getUrl(2), 'disable']),	
							'help' => 'Une page désactivée n\'est pas cliquable en mode déconnecté, les pages enfants sont visibles et accessibles. La page d\'accueil n\'est pas désactivable.'
						]); ?>
					</div>
				</div>	
			</div>
		</div>
	</div>
	<div class='row' id="pageEditSeoWrapper">
		<div class="col12">
			<div class="block">
				<h4>Référencement</h4>
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
	<div class='row' id="pageEditAdvancedWrapper">
		<div class="col12">
			<div class="block">
				<h4>Options Avancées</h4>
				<div class="row">
				<div class="col6">
							<?php echo template::checkbox('pageEditHideMenuChildren', true, 'Masquer les pages enfants dans le menu horizontal', [
								'checked' => $this->getData(['page', $this->getUrl(2), 'hideMenuChildren']),
								'help' => 'Les pages enfants ne sont pas visibles dans le menu horizontal, elles se seront dans un menu vertical. La page doit intégrer un lien vers l\'une des pages enfants sinon elles ne seront pas accessibles.'
							]); ?>
					</div>					
					<div class="col6">
							<?php echo template::checkbox('pageEditHideMenuSide', true, 'Masquer la page dans le menu vertical', [
								'checked' => $this->getData(['page', $this->getUrl(2), 'hideMenuSide']),
								'help' => 'La page est affichée dans un menu horizontal mais pas dans un menu vertical inséré dans une barre latérale.'
							]); ?>
					</div>					
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
