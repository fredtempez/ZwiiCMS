<?php echo template::formOpen('creation_events'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('creation_retour', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0),
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<?php if( $this->getUser('group') >= $this->getData(['module', $this->getUrl(0), 'config', 'droit_creation'])): ?>	
		<script src="'. helper::baseUrl(false).'/core/vendor/tinymce/tinymce.min.js"></script>'
		<script src="'. helper::baseUrl(false).'/core/vendor/tinymce/jquery.tinymce.min.js"></script>'
		<?php if( $this->getUser('group') >= 2){
			echo '<script src="'. helper::baseUrl(false).'/module/agenda/vendor/js/init23.js"></script>';
		}
		else{
			echo '<script src="'. helper::baseUrl(false).'/module/agenda/vendor/js/init01.js"></script>';
		}
		echo '<link rel="stylesheet" href="'. helper::baseUrl(false).'/core/vendor/tinymce/init.css">';
		?>
		<!--Suite de la <div class="row"> -->
			<div class="col2 offset8">
				<?php echo template::submit('creation_enregistrer'); ?>
			</div>
		<!-- Fermeture de la <div class="row"> si test true -->
		</div>

		<div class="block">
		<h4>Créer un évènement</h4>	
		<div class="row">
			<div class="col12">
				<?php echo template::textarea('creation_text', [
					'label' => 'Evènement',
					'class' => 'editorWysiwygComment',
					'value' => 'Votre évènement du '.$module::$jour.'/'.$module::$mois.'/'.$module::$annee
				]); ?>
			</div>
		</div>

		<div class="row">
			<div class="col4">
				<?php echo template::date('creation_date_debut', [
					'help' => 'Choix de la date et de l\'heure de début de l\'évènement',
					'label' => 'Date de début',
					'value' => $module::$time_unix_deb,
					'type' => 'datetime-local',
				]); ?>
			</div>

			<div class="col4">
				<?php echo template::date('creation_date_fin', [
					'help' => 'Choix de la date et de l\'heure de fin de l\'évènement',
					'label' => 'Date de fin',
					'value' => $module::$time_unix_fin,
					'type' => 'datetime-local',
				]); ?>
			</div>
		</div>
		
		<div class="row">
			<?php if( is_file($module::DATAMODULE.'categories/categories.json') && $this->getData(['module', $this->getUrl(0), 'categories', 'valCategories' ]) ){ ?> 
				<div class="col8">
					<?php echo template::select('creation_categorie', $module::$categorie,[
						'help' => 'Choix de la catégorie d\'évènement.',
						'label' => 'Catégorie d\'évènement'
					]); ?>	
				</div>
			
			
			<?php }
			else{	?>
				<div class="col4">
				<?php echo template::select('creation_couleur_fond', $module::$couleur,[
						'help' => 'Choix de la couleur du bandeau dans lequel le texte apparaît.',
						'label' => 'Couleur de fond',
						'selected' => 'black'
					]); ?>	
				</div>
				<div class="col4">
				<?php echo template::select('creation_couleur_texte', $module::$couleur,[
						'help' => 'Choix de la couleur du texte.',
						'label' => 'Couleur du texte',
						'selected' => 'white'
					]); ?>	
				</div>
			<?php } ?>
			
		</div>
		<div class="row">
			<div class="col4">
				<?php echo template::select('creation_groupe_lire', self::$groupPublics,[
					'help' => 'Choix du groupe minimal qui pourra voir et lire cet évènement',
					'label' => 'Accès en lecture',
					'selected' => '0'
				]); ?>	
			</div>
			<div class="col4">
				<?php	
					$groupe_mini = $this->getUser('group');
					if ($groupe_mini == 3){ $groupe_mini = 2;}
				?>
				<?php echo template::select('creation_groupe_mod', self::$groupNews,[
					'help' => 'Choix du groupe minimal qui pourra modifier ou supprimer cet évènement',
					'label' => 'Accès en modification',
					'selected' => $groupe_mini
				]); ?>	
			</div>
		</div>
		<div class="row">
			<div class="col4">
			<?php echo template::checkbox('creation_mailing_validation', true, 'Envoi d\'un courriel', [
				'checked' => false,
				'help' => 'Case cochée un courriel sera adressé aux destinataires.'
			]); ?>
			</div>
			<div class="col4">
			<!-- Sélection d'un fichier txt ou csv -->
			<?php echo template::select('creation_mailing_adresses', $module::$liste_adresses, [
				'help' => 'Vous pouvez sélectionner ici un fichier txt ou csv qui contient une suite d\'adresses courielles séparées par une virgule.'.'<br/>'.'
				3 fichiers sont générés automatiquement à partir des utilisateurs inscrits et vous pouvez, en configuration, ajouter vos propres fichiers d\'adresses',
				'label' => 'Sélection d\'un fichier destinataires'
			]); ?>
			</div>
		</div>
			
	</div>
<?php endif; ?>
<?php echo template::formClose(); ?>

<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>