<?php echo template::formOpen('galleryAddForm'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('galleryAddBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config' ,
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col2 offset9">
            <?php echo template::submit('galleryAddSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4><?php echo helper::translate('Paramètres');?></h4>
				<div class="row">
					<div class="col6">
						<?php echo template::text('galleryAddName', [
							'label' => 'Nom'
						]); ?>
					</div>
					<div class="col6">
						<div class="displayNone">
							<?php echo template::hidden('galleryAddDirectoryOld', [
								'noDirty' => true // Désactivé à cause des modifications en ajax
							]); ?>
						</div>
						<?php echo template::select('galleryAddDirectory', [], [
							'label' => 'Dossier cible',
							'noDirty' => true // Désactivé à cause des modifications en ajax
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
