<?php if($this->getData(['core','dataVersion']) > 10092){ 
	echo '<link rel="stylesheet" href="'. helper::baseUrl(false).'site/data/admin.css">';
}
else{
	echo '<link rel="stylesheet" href="'. helper::baseUrl(false).'core/layout/admin.css">';
} ?>

<?php echo template::formOpen('statisliteConfigForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('statisliteConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::submit('statisliteConfigSubmit', [
				'ico' => ''
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Paramétrage de StatisLight : filtrages</h4>
				<div class="row">
					<div class="col4">
						<?php echo template::select('statisliteConfigTimePageMini', $module::$timePageMini,[
							'help' => 'Temps minimum à passer sur une page pour valider la vue',
							'label' => 'Temps minimum sur une page',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'timePageMini'])
						]); ?>
					</div>
					<div class="col4">
					<?php echo template::select('statisliteConfigTimeVisiteMini', $module::$timeVisiteMini,[
							'help' => 'Temps minimum à passer sur le site pour valider la visite',
							'label' => 'Temps minimum de la visite',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'timeVisiteMini'])
						]); ?>
					</div>
					<div class="col4">
					<?php echo template::select('statisliteConfigNbPageMini', $module::$nbPageMini,[
							'help' => 'Nombre minimum de pages vues pour valider une visite. Pour le réglage \'1 page\' les contrôles de temps ne pourront pas se faire.',
							'label' => 'Nombre minimum de pages vues',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'nbPageMini'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4">
						<?php echo template::select('statisliteConfigUsersExclus', $module::$users_exclus,[
							'help' => 'Utilisateurs connectés à exclure des statistiques',
							'label' => 'Utilisateurs exclus',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'usersExclus'])
						]); ?>
					</div>
				</div>
			</div>

			<div class="block">
				<h4>Paramétrage de StatisLight : affichage graphique </h4>
				<div class="row">
					<!-- Affichage graphique des pages vues -->

					<div class="col4">
						<?php echo template::select('statisliteConfigNbAffiPagesVues', $module::$nbaffipagesvues,[
							'help' => 'Sélection du nombre de pages vues affichées en commençant par la plus fréquente.',
							'label' => 'Nombre de pages affichées',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'nbaffipagesvues'])
						]); ?>
					</div>

					<!-- Affichage graphique des langues préférées -->

					<div class="col4">
						<?php echo template::select('statisliteConfigNbAffiLangues', $module::$nbaffilangues,[
							'help' => 'Sélection du nombre de langues préférées affichées en commençant par la plus fréquente.',
							'label' => 'Nombre de langues affichées',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'nbaffilangues'])
						]); ?>
					</div>

					<!-- Affichage graphique des navigateurs -->

					<div class="col4">
						<?php echo template::select('statisliteConfigNbAffiNavigateurs', $module::$nbaffinavigateurs,[
							'help' => 'Sélection du nombre de navigateurs affichés en commençant par le plus fréquent.',
							'label' => 'Nombre de navigateurs affichées',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'nbaffinavigateurs'])
						]); ?>
					</div>
				</div>
				<div class="row">
					<!-- Affichage graphique des systèmes d'exploitation -->

					<div class="col4">
						<?php echo template::select('statisliteConfigNbAffiSe', $module::$nbaffise,[
							'help' => 'Sélection du nombre de systèmes d\'exploitation affichés en commençant par le plus fréquent.',
							'label' => 'Nombre de systèmes d\'exploitation affichés',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'nbaffise'])
						]); ?>
					</div>

				</div>
			</div>

			<div class="block">
				<h4>Paramétrage de StatisLight : affichage chronologique des dernières dates</h4>
				<div class="row">
					<div class="col4">
					<?php echo template::select('statisLiteConfigNbAffiDates', $module::$nbAffiDates,[
							'help' => 'Choix du nombre de dates affichées en commençant par la plus récente, avec pour chacune les nombres de visites et de pages vues, les durées totale et moyenne par visite. ',
							'label' => 'Nombre de dates affichées',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'nbaffidates'])
						]); ?>
					</div>
				</div>

			</div>

			<div class="block">
				<h4>Paramétrage de StatisLight : affichage détaillé des dernières sessions</h4>
				<div class="row">
					<div class="col4">
					<?php echo template::select('statisliteConfigNbEnregSession', $module::$nbEnregSession,[
							'help' => 'Choix du nombre de visites affichées de manière détaillée en commençant par la plus récente. ',
							'label' => 'Nombre de visites affichées',
							'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'nbEnregSession'])
						]); ?>
					</div>
				</div>

			</div>

			<div class="block">
				<h4>Affichage des sessions rejetées</h4>
				<?php if(is_file( $module::$fichiers_json.'robots.json')){	
					copy( $module::$fichiers_json.'robots.json', $module::$tmp.'robots.json');
					echo 'Log des 200 derniers robots : ';
					echo '<p><a href="'. helper::baseUrl(false).'site/file/statislite/tmp/robots.json" target="_blank">Fichier robots.json</a></p>';
				}
				if(is_file( $module::$fichiers_json.'sessionInvalide.json')){
					copy( $module::$fichiers_json.'sessionInvalide.json', $module::$tmp.'sessionInvalide.json');
					echo 'Log des 200 dernières sessions invalidées : ';
					echo '<p><a href="'.helper::baseUrl(false).'site/file/statislite/tmp/sessionInvalide.json" target="_blank">Fichier sessionInvalide.json</a></p>';
				}
				?>
			</div>
			
			<div class="block">
				<h4>Sauvegarde, restauration ou réinitialisation des statistiques</h4>
				<div class="row">
					<div class="col2">
						<?php echo template::button('configSauveJson', [
							'href' => helper::baseUrl() . $this->getUrl(0) . '/sauveJson',
							'ico' => 'download',
							'value' => 'Sauvegarder'
						]); ?>
					</div>	
					<div class="col2 offset8">
					<?php echo template::button('configInitJson', [
							'class' => 'configInitJson buttonRed',
							'href' => helper::baseUrl() . $this->getUrl(0) . '/initJson' . '/' . $_SESSION['csrf'],
							'ico' => 'cancel',
							'value' => 'Réinitialiser'
						]); ?>
					</div>
				</div>
				
				<div class="row">
					<!--Sélection d'un fichier de sauvegarde-->
					<div class="col8">
					<?php	if(is_dir( $module::$json_sauve )) {
								$dir = $module::$json_sauve;
								$files = scandir($dir);
								//Ne conserver que les fichiers xxxxxxxxx_cumul.json
								foreach( $files as $key=>$val){
									if( strpos($val, 'cumul.json') === false){
										unset( $files[$key]);
									}	
								}
								if (count($files) === 0 ){
									$files = array(0 => 'Pas de fichier de sauvegarde');
								}
								else{
									$files[0] = 'Votre choix...';
									// clef = valeur pour renvoyer le nom du fichier et non la clef de type numméro
									$files= array_combine($files,$files);
								}
							}
							else {
								$files = array(0 => 'Dossier votre_site/site/file/statislite/json_sauve inexistant');
							}
							echo template::select('configRestoreJson', $files, [
							'help' => 'Vous pouvez sélectionner ici un fichier de restauration, puis enregistrez la configuration. Une sauvegarde de sécurité des statistiques actuelles est réalisée.',
							'id' => 'config_restauration',
							'disabled' => $readonly,
							'label' => 'Sélection d\'un fichier pour restauration des statistiques',
							'selected' => 'Votre choix...'
							]);	?>
					</div>
				</div>
				
			</div>
			
		</div>
		
	</div>


<?php echo template::formClose(); ?>
<div class="moduleVersion">Module Statislite version n°
	<?php echo $module::STATISLITE_VERSION; ?>
</div>
