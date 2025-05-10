<?php echo template::formOpen('visualizerConfigForm'); ?>

<!-- Boutons Retour et submit (Enregistrer) -->
<div class="row">
	<div class="col1">
		<?php echo template::button('visualizerConfigBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
			'value' => template::ico('left'),
			'help' => 'Annuler'
		]); ?>
	</div>
	<div class="col1 offset8">
		<?php echo template::button('visualizerUpload', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/upload',
			'value' => 'upload'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::submit('visualizerConfigSubmit', [
			'value' => helper::translate('Valider')
		]); ?>
	</div>
</div>
<!-- Dans un block un select pour choisir la phrase à afficher, la class template permet d'utiliser select, text, button, textarea, checkbox, ... -->
<div class="block">
	<!--div class="blockTitle"><?php echo helper::translate('Paramétrage'); ?></div-->
	<h4><?php echo helper::translate('Paramétrage'); ?></h4>
	<div class="row">
		<div class="col6">
			<a style="font-size:18px" href="module/visualizer/vendor/system/aideVisualisateurGPX.pdf" download> Fichier d'aide </a>
		</div>
	</div>
	<div class="row">
		<div class="col6">
			<?php echo template::text('visualizerName', [ // Nom du catalogue de randonnées
				//'help' => 'Bulle d\'aide',
				'label' => 'Nom du catalogue de randonnées (*) : ',
				'value' => $this->getData(['module', $this->getUrl(0), 'config', 'nameCata'])
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col6">
			<?php echo template::select('visualizerDirTrk', [], [ // sélection du répertoire des traces GPX
				'label' => 'Répertoire des traces GPX :',
				//'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'indexDirTrk']),
				'noDirty' => true,
			]); ?>
		</div>
		<div class="col6">
			<?php echo template::select('visualizerDirDocTrk', [], [ // sélection du répertoire du descrptif de chaque trace GPX
				'label' => 'Répertoire du descrptif de chaque trace GPX :',
				//'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'indexDirDocTrk']),
				'noDirty' => true,
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col6">
			<?php echo template::select('visualizerDirWP', [], [ // sélection du répertoire des services (WP) GPX
				'label' => 'Répertoire des services (WP) GPX :',
				//'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'indexDirWP']),
				'noDirty' => true, // Désactivé à cause des modifications en ajax,							
			]); ?>
		</div>
		<div class="col6">
			<?php echo template::select('visualizerDirDocWP', [], [ // sélection du répertoire du descrptif de chaque service GPX
				'label' => 'Répertoire du descrptif éventuel de chaque WP GPX :',
				//'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'indexDirDocWP']),
				'noDirty' => true, // Désactivé à cause des modifications en ajax,							
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col6">
			<?php echo template::text('visualizerKeyIGN', [ // Clé IGN pour scan25
				//'help' => 'Bulle d\'aide',
				'label' => 'Clé IGN pour scan25 :',
				'value' => $this->getData(['module', $this->getUrl(0), 'config', 'KeyIGN']),
			]); ?>
		</div>
		<div class="col6">
			<?php echo template::select('visualizerDisplayTrk', ['Faire un choix :', 'Oui', 'Non'], [ // Choix de l'affichage ou non des traces au démarrage
				'label' => 'Affichage des traces au démarrage (*) :',
				'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'DisplayTrk']),
				'noDirty' => true, // Désactivé à cause des modifications en ajax,							
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col6">
			<?php echo template::number('visualizerMapW', [ // Largeur en px de la carte
				'help' => 'Un nombre uniquement ! Si vide largeur = auto',
				'label' => 'Largeur en px de la carte :',
				'value' => $this->getData(['module', $this->getUrl(0), 'config', 'MapW']),
			]); ?>
		</div>
		<div class="col6">
			<?php echo template::number('visualizerMapH', [ // Hauteur en px de la carte
				'help' => 'Un nombre uniquement !',
				'label' => 'Hauteur en px de la carte (*) :',
				'value' => $this->getData(['module', $this->getUrl(0), 'config', 'MapH']),
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col6"> (*) : paramètre obligatoire.</div>
	</div>
</div>

<!-- Fermeture du formulaire et affichage du n° de version -->
<?php echo template::formClose(); ?>

<div class="moduleVersion">
	<?php echo helper::translate('Version ') . $module::VERSION; ?>
</div>