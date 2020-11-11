<?php echo template::formOpen('translateForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('translateFormBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::submit('translateFormSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>Configuration</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::checkbox('translateActive', true, 'Traduction automatique', [
								'checked' => $this->getData(['translate', 'active'])
							]); ?>
					</div>
					<div class="col6">
						<?php echo template::checkbox('translateCredits', true, 'Afficher les crédits', [
								'checked' => $this->getData(['translate', 'showCredits'])
							]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>