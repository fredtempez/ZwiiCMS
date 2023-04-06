<!-- Configuration du module snipcart pour le CMS Zwii  -->

<?php echo template::formOpen('snipcartOptionForm'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('snipcartOptionBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
				'ico' => 'left',
			]); ?>
		</div>
		<div class="col1 offset8">
			<?php echo template::submit('snipcartOptionSubmit', [
				'value' => 'Valider'
			]); ?>
		</div>
	</div>
	
	<div class="block">
		<h4>Paramétrage de Snipcart</h4>
		<div class="row">
			<div class="col4">
				<p></p>
				<?php echo template::checkbox('snipcartOptionValid', true, 'Activation de Snipcart', [
					'checked' => $this->getData(['module', $this->getUrl(0),'config','valid' ]),
					'help' => 'Case cochée Snipcart est activé avec la clef renseignée ici.'
				]); ?>
			</div>
		</div>
		<div class="row">
			<div class="col12">
			<?php echo template::text('snipcartOptionKey', [
					'help' => 'Saisir votre API KEY Snipcart disponible sur votre Dashboard par CONFIGURE puis API KEYS. ',
					'label' => 'API KEY',
					'value' => $this->getData(['module', $this->getUrl(0),'config','key' ])
				]); ?>
			</div>
		</div>
	</div>
	
	<?php	// Lecture de datadefault.json
	$filejson = file_get_contents($module::DATAMODULE.'/datadefault.json');
	$datajson = json_decode($filejson, true);
	?>
	
	<div class="block">
		<h4>Mode de création</h4>
		<div class="row">
			<div class="col8">
				<?php // Pour compatibilité avec version 1.1
				if( null === $this->getData(['module', $this->getUrl(0), 'config','template'])){
					$template = 'bouton_seul';
				}
				else{
					$template = $this->getData(['module', $this->getUrl(0), 'config','template']);
				}?>
				<?php echo template::select('snipcartOptionTemplate', $module::$choixTemplate,[
					'help' => 'Création seulement du bouton d\'ajout au panier ou du produit et du bouton simultanément',
					'label' => 'Choisir un mode',
					'selected' => $template
				]); ?>
			</div>
		</div>
	</div>
	
	<div class="block">
		<h4>Valeurs par défaut d'un bouton d'ajout au panier : Onglet général</h4>
		<div class="row">
			<div class="col4">
			<?php echo template::text('snipcartOptionPoids', [
					'help' => 'Poids par défaut en grammes. ',
					'label' => 'Poids en grammes',
					'value' => $this->getData(['module', $this->getUrl(0),'config','poids'])
				]); ?>
			</div>
			<div class="col4 offset4">
			<?php echo template::text('snipcartOptionTaxes', [
					'help' => 'Dénomination Snipcart des taxes par défaut. ',
					'label' => 'Taxes',
					'value' => $this->getData(['module', $this->getUrl(0),'config','taxes'])
				]); ?>
			</div>
		</div>
		<div class="row">
			<div class="col4">
				<p></p>
				<?php echo template::checkbox('snipcartOptionTransport', true, 'Frais de transport', [
					'checked' => $this->getData(['module', $this->getUrl(0),'config','transport']),
					'help' => 'Case cochée la case frais de transport sera cochée par défaut.'
				]); ?>
			</div>
		</div>	
		
	</div>
	
	<div class="block">
		<h4>Valeurs par défaut d'un bouton d'ajout au panier : Onglet Bouton</h4>
		<div class="row">
			<div class="col4">
			<?php echo template::text('snipcartOptionText', [
					'help' => 'Texte du bouton par défaut.',
					'label' => 'Texte',
					'value' => $this->getData(['module', $this->getUrl(0),'config','buttonText'])
				]); ?>
			</div>
			<div class="col4 offset4">
			<?php echo template::text('snipcartOptionWidth', [
					'help' => 'Largeur du bouton par défaut. ',
					'label' => 'Largeur',
					'value' => $this->getData(['module', $this->getUrl(0),'config','buttonWidth'])
				]); ?>
			</div>
		</div>
		<div class="row">
			<div class="col4">
			<?php echo template::text('snipcartOptionColor', [
					'class' => 'colorPicker',
					'help' => 'Choissez la couleur du texte du bouton avec le nuancier',
					'label' => 'Couleur du texte',
					'value' => $this->getData(['module', $this->getUrl(0),'config','buttonColor'])
				]); ?>
			</div>
			<div class="col4 offset4">
			<?php echo template::text('snipcartOptionBgColor', [
					'class' => 'colorPicker',
					'help' => 'Choissez la couleur du bouton avec le nuancier.',
					'label' => 'Couleur du fond',
					'value' => $this->getData(['module', $this->getUrl(0),'config','buttonBgColor'])
				]); ?>
			</div>
		</div>		
	</div>
	
	
	<div class="block">
	<h4>Documentation</h4>
	<p style="text-align: left;"><a href="https://app.snipcart.com/" target="_blank" rel="noopener" title="Page de connexion du site Snipcart">Page de connexion du site Snipcart</a></p>
	<p style="text-align: left;"><a href="./site/data/snipcart/module/snipcart_mode_emploi.pdf" target="_blank" title="Documentation Snipcart avec Zwii" rel="noopener">Documentation Snipcart avec Zwii</a></p>
	</div>

<?php echo template::formClose(); ?>
<div class="moduleVersion">Module Snipcart version n°
	<?php echo $module::VERSION; ?>
</div>
