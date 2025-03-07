
<!-- Configuration du module agenda -->
<?php
if(! is_dir($module::DATAMODULE.'data/'.$this->getUrl(0))){ $readonly = true;}else{ $readonly = false;}
?>
<?php echo template::formOpen('configuration'); ?>

<div class="row">
	<div class="col2">
		<?php echo template::button('config_retour', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0),
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	
	<div class="col2 offset6">
		<?php echo template::button('config_gestion_categorie', [
			'class' => 'buttonBlue',
			'href' => helper::baseUrl() . $this->getUrl(0).'/category',
			'ico' => 'cogs',
			'value' => 'Catégories'
		]); ?>
	</div>

	<div class="col2">
		<?php echo template::submit('config_enregistrer',[
			'value' => 'Valider'	
		]); ?>
	</div>
</div>
	
<div class="block">
	<h4>Gérer les droits</h4>
		<div class="col6">
			<?php	echo template::select('config_droit_creation', $module::$groupNews, [
			'help' => 'Vous sélectionner ici le groupe à partir duquel il sera possible de créer un évènement dans l\'agenda',
			'id' => 'config_droit_creation',
			'disabled' => $readonly,
			'label' => 'Groupe minimum pour créer un évènement',
			'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'droit_creation'])
			]); ?>		
		</div>
		<div class="col6">
			<?php echo template::checkbox('config_droit_limite', true, 'Limitation du choix des groupes liés aux évènements', [
				'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'droit_limite']),
				'disabled' => $readonly,
				'help' => 'Si vous cochez cette case en mode création ou édition les choix de groupe, associés aux droits de lecture ou de modification d\'un évènement, seront fonction du groupe de l\'utilisateur.'
			]); ?>
		</div>
</div>

<div class="block">
	<h4>Affichage de l'agenda</h4>
		<div class="col4">
			<?php echo template::select('config_MaxiWidth', $module::$maxwidth,[
				'help' => 'Largeur maximale de l\'agenda en pixels. La sélection 100% correspond à la largeur du site définie en configuration - 40 pixels',
				'label' => 'Largeur maxi de l\'agenda',
				'selected' => $this->getData(['module', $this->getUrl(0),'config', 'maxiWidth'])
			]); ?>	
		</div>
</div>

<div class="block">
	<h4>Sauvegarder, restaurer un agenda</h4>	
	<div class="row">	
		<!--Sauvegarder l'agenda actuel-->
		<div class="col6">
			<?php
			echo template::text('config_sauve', [
				'help' => 'Saisir un nom de fichier sans extension , exemples agenda_20200113 ou monbelagenda',
				'disabled' => $readonly,
				'id' => 'config_sauvegarde',
				'label' => 'Sauvegarde de l\'agenda actuel'
				]);
			?>	
		</div>
		
		
		<!--Sélection d'un fichier de sauvegarde-->
		<div class="col6">
		<?php	echo template::select('config_restaure', $module::$savedFiles, [
				'help' => 'Vous pouvez sélectionner ici un fichier de restauration d\'un agenda. les fichiers events_YYYYMMDDHHMMSS.json sont des fichiers de sauvegarde automatique.',
				'id' => 'config_restauration',
				'disabled' => $readonly,
				'label' => 'Sélection d\'un fichier pour restauration d\'un agenda'
				]);	?>
		</div>
	</div>
	

	

</div>

<!--Tout supprimer-->
<div class="block">
		<h4>Attention ! supprime tous les évènements de l'agenda</h4>
		<div class="col4">
			<?php echo template::button('config_suptout', [
				'class' => 'configSup buttonRed',
				'disabled' => $readonly,
				'help' => 'En cas d\'erreur vous pouvez récupérer l\agenda par le bouton Agenda précédent',
				'href' => helper::baseUrl() . $this->getUrl(0).'/deleteall',
				'ico' => 'cancel',
				'value' => 'Supprimer tout'
			]); ?>
		</div>
</div>	

<!-- Sélection d'un fichier ics depuis le dossier site/file/source/agenda/ics  -->
<div class="block">
	<h4>Ajouter des évènements à l'agenda actuel depuis un fichier ics</h4>
	<div class="row">
		<div class="col6">
			<!-- Sélection d'un fichier ics -->
			<?php echo template::select('config_fichier_ics', $module::$icsFiles, [
				'help' => 'Vous pouvez sélectionner ici un fichier ics'.'<br/>'.'Les fichiers doivent être placés dans le dossier site/file/source/agenda/ics en utilisant le gestionnaire de fichiers de Zwii',
				'id' => 'config_fichier_ics',
				'label' => 'Sélection d\'un fichier ics pour ajouter des évènements'
			]); ?>
		</div>
	</div>
</div>

<!-- Sélection d'un carnet d'adresses au format txt ou csv avec séparateur virgule  -->
<div class="block">
	<h4>Ajouter un carnet d'adresses</h4>
	<div class="row">
		<div class="col6">
			<!-- Sélection d'un fichier csv ou txt -->
			<?php echo template::select('config_fichier_csv_txt', $module::$csvFiles, [
				'help' => 'Vous pouvez sélectionner ici un carnet d\'adresses au format csv ou txt avec séparateur virgule'.'<br/>'.'Les carnets doivent être placés dans le dossier site/file/source/agenda/adresses en utilisant le gestionnaire de fichiers de Zwii',
				'id' => 'config_fichier_csv_txt',
				'label' => 'Sélection d\'un fichier csv ou txt pour ajouter un carnet d\'adresses'
			]); ?>
		</div>
	</div>
</div>

<!-- Fin du formulaire principal à la mode Zwii -->	
<?php echo template::formClose(); ?>



<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>


