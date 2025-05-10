<?php echo template::formOpen( 'visualizerUploadForm',
    [
        'enctype' => 'multipart/form-data'
    ]
); ?>

<!-- Boutons Retour et submit (Enregistrer) -->
<div class="row">
	<div class="col1">
		<?php echo template::button('visualizerUploadBack', [
			'class' => 'buttonGrey',
			//'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0). '/config',
			'href' => helper::baseUrl() . $this->getUrl(0). '/config',
			'value' => template::ico('left'),
			'help' => 'Annuler'
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('visualizerUploadSubmit', [
			'value' => helper::translate('Transférer')
		]); ?>
	</div>
</div>
<!-- Dans un block un select pour choisir la phrase à afficher, la class template permet d'utiliser select, text, button, textarea, checkbox, ... -->
<div class="block">
	<h4><?php echo helper::translate('Transfert de fichier(s)'); ?></h4>
	<div class="row">
		<div class="col6">
			<?php echo template::select('visualizerUploadDir',
				[],
				[ // Choix du répertoire destinataire
					'label' => 'Répertoire destinataire:',
					'noDirty' => true, // Désactivé à cause des modifications en ajax,
				]					
			); ?>
		</div>
		<div class="col6">
			<?php echo template::upload('visualizerUploadFile', [ // Fichier à transférer
				'label' => 'Fichier(s) à transférer :',
				'accept' => '.gpx,.pdf',
				'uploadText' => 'Sélectionner ...',
				'placeholder' => 'Nom du(es) fichier(s) à transférer',
				'multiple' => true,
				//'required' => true,
			]); ?>
		</div>
	</div>
</div>

<!-- Fermeture du formulaire et affichage du n° de version -->
<?php echo template::formClose(); ?>

<div class="moduleVersion">
	<?php echo helper::translate('Version ') . $module::VERSION; ?>
</div>
