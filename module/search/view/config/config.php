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
					<div class="col6">
						<?php echo template::text('searchSubmitText', [
								'label' => 'Texte du bouton de soumission',
								'value' => $this->getData(['module', $this->getUrl(0), 'submitText']),
								'placeholder' => 'Rechercher'
							]); ?>
					</div>
					<div class="col6">
						<?php echo template::text('searchPlaceHolder', [
								'label' => 'Texte dans la zone de recherche',
								'value' => $this->getData(['module', $this->getUrl(0), 'placeHolder']),
								'placeholder' => 'Saisissez vos mots clés ou une phrase'
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