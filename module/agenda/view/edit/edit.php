<?php echo template::formOpen('edition_events'); ?>

<div class="row">
	<div class="col2">
		<?php echo template::button('edition_retour', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0),
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2 offset6">
		<?php echo template::button('edition_event_delete', [
			'class' => 'buttonRed',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/deleteEvent/' . $module::$evenement['id'],
			'value' => 'Supprimer',
			'ico' => 'cancel'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::submit('edition_enregistrer', [
			'ico' => 'check'
		]); ?>
	</div>
	<div class="block">
		<div class="row">
			<div class="col12">
				<?php echo template::textarea('edition_text', [
					'label' => 'Evènement',
					'class' => 'editorWysiwygComment',
					'value' => $module::$evenement['texte']
				]); ?>
			</div>
		</div>
		<div class="row">
			<div class="col4">
				<?php echo template::date('edition_date_debut', [
					'help' => 'Date de début',
					'label' => 'Date de début',
					'type' => 'datetime-local',
					'value' => $module::$evenement['datedebut'],

				]); ?>
			</div>

			<div class="col4">
				<?php echo template::date('edition_date_fin', [
					'help' => 'Date de fin',
					'label' => 'Date de fin',
					'type' => 'datetime-local',
					'value' => $module::$evenement['datefin'],
				]); ?>
			</div>
		</div>
		<div class="row">
			<?php if ($module::$evenement['categorie'] != '') { ?>
				<div class="col8">
					<?php echo template::select('edition_categorie', $module::$categorie, [
						'help' => 'Choix de la catégorie d\'évènement.',
						'label' => 'Catégorie d\'évènement',
						'selected' => $module::$evenement['categorie']
					]); ?>
				</div>
			<?php } else { ?>
				<div class="col4">
					<?php echo template::select('edition_couleur_fond', $module::$couleur, [
						'help' => 'Choix de la couleur du bandeau dans lequel le texte apparaît.',
						'label' => 'Couleur de fond',
						'selected' => $module::$evenement['couleurfond']
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::select('edition_couleur_texte', $module::$couleur, [
						'help' => 'Choix de la couleur du texte.',
						'label' => 'Couleur du texte',
						'selected' => $module::$evenement['couleurtexte']
					]); ?>
				</div>
			<?php } ?>
		</div>
		<div class="row">
			<div class="col4">
				<?php echo template::select('edition_groupe_lire', self::$groupPublics, [
					'help' => 'Choix du groupe minimal qui pourra voir et lire cet évènement',
					'label' => 'Accès en lecture',
					'selected' => $module::$evenement['groupe_lire']
				]); ?>
			</div>
			<div class="col4">
				<?php echo template::select('edition_groupe_mod', self::$groupNews, [
					'help' => 'Choix du groupe minimal qui pourra modifier ou supprimer cet évènement',
					'label' => 'Accès en modification',
					'selected' => $module::$evenement['groupe_mod']
				]); ?>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>