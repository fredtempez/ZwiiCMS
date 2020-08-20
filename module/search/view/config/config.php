<?php echo template::formOpen('searchConfig'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('searchConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
				<?php echo template::submit('searchConfigSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>Paramètres</h4>
				<div class="row">
					<div class="col3">
						<?php echo template::text('searchKeywordColor', [
							'class' => 'colorPicker',
							'help' => ' Cette couleur est commune à tous les modules de recherche. Le curseur horizontal règle le niveau de transparence.',
							'label' => 'Surlignement',
							'value' => $this->getData(['theme', 'search', 'keywordColor'])
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::text('searchSubmitText', [
								'label' => 'Texte du bouton',
								'value' => $this->getData(['module', $this->getUrl(0), 'submitText']) ? $this->getData(['module', $this->getUrl(0), 'submitText']) : 'Rechercher'
							]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('searchPlaceHolder', [
								'label' => 'Aide dans la zone de saisie',
								'value' => $this->getData(['module', $this->getUrl(0), 'placeHolder']) ? $this->getData(['module', $this->getUrl(0), 'placeHolder']) : 'Un ou plusieurs mots-clés séparés par un espace ou par +'
							]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col12">
						<?php echo template::checkbox('searchResultHideContent', true, 'Résultats : masquer le contenu de la page', [
							'checked' => $this->getData(['module', $this->getUrl(0), 'resultHideContent']),
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo $module::SEARCH_VERSION; ?>
</div>