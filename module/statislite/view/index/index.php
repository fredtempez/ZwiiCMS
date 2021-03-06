<?php 

/*
 * Affichage des résultats
*/

	/* 
	 * Paramètres réglés en configuration du module
	*/
	// Affichage graphique : nombre de pages vues à afficher en commençant par la plus fréquente, de 0 à toutes
	$nbaffipagesvues = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffipagesvues']);
	// Affichage graphique : nombre de langues à afficher en commençant par la plus fréquente, de 0 à toutes
	$nbaffilangues = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffilangues']);
	// Affichage graphique : nombre de navigateurs à afficher en commençant par le plus fréquent, de 0 à toutes
	$nbaffinavigateurs = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffinavigateurs']);
	// Affichage graphique : nombre de systèmes d'exploitation à afficher en commençant par le plus fréquent, de 0 à tous
	$nbaffise = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffise']);
	// Affichage graphique : nombre de pays à afficher en commençant par le plus fréquent, de 0 à tous
	$nbaffipays = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffipays']);
	// Nombre de sessions affichées dans l'affichage détaillé
	$nbEnregSession = $this->getData(['module', $this->getUrl(0), 'config', 'nbEnregSession' ]);
	// Nombre de dates affichées dans l'affichage chronologique
	$nbAffiDates = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffidates' ]);
	// option avec geolocalisation
	$geolocalisation = $this->getData(['module', $this->getUrl(0), 'config', 'geolocalisation' ]);
	
	
	/*
	 * Affichage cumulé depuis le début de l'analyse soit depuis l'initialisation du fichier cumul.json
	 * le reset se fait actuellement uniquement par la suppression du fichier cumul.json
	*/
	$json = file_get_contents($module::$fichiers_json.'sessionLog.json');
	$log = json_decode($json, true);	 
	$json = file_get_contents($module::$fichiers_json.'cumul.json');
	$cumul = json_decode($json, true);
	$comptepagestotal = $module::$comptepages + $cumul['nb_clics'];
	$comptevisitetotal = $module::$comptevisite + $cumul['nb_visites'];
	$dureevisitestotal = $module::$dureevisites + $cumul['duree_visites'];
	if($comptevisitetotal != 0){
		$dureevisitemoyenne = conversionTime((int)($dureevisitestotal / $comptevisitetotal));
	}
	else{
		$dureevisitemoyenne = 0;
	}
	?>
	<div class="block">
		<h4>Visites depuis le <?php echo $cumul['date_debut']; ?></h4>
		<div class="row">
			<div class="col4"><h3>	
				<?php echo 'Nombre de pages vues = '.$comptepagestotal;?></h3>
			</div>
			<div class="col4"><h3>
				<?php echo 'Nombre de visites  = '.$comptevisitetotal;?></h3>
			</div>
			<div class="col4"><h3>
				<?php echo 'Durée moyenne = '.$dureevisitemoyenne;?></h3>
			</div>
		</div><br/><br/>
		<?php 
		if($nbaffipagesvues != 0){
			// Affichage des pages vues et de leur nombre de clics en prenant en compte cumul.json et sessionLog.json
			$pagesvues = array();
			foreach($log as $numSession=>$val){
				foreach($log[$numSession]['vues'] as $vues=>$values){
					$page = substr($values, 22, strlen($values));
					if(isset($pagesvues[$page])){
						$pagesvues[$page] = $pagesvues[$page] + 1;
					}
					else{
						$pagesvues[$page] = 1;
					}
				}
			}
			foreach($cumul['pages'] as $page=>$values){
				if(isset($pagesvues[$page])){
					$pagesvues[$page] = $pagesvues[$page] + $values;
				}
				else{
					$pagesvues[$page] = $values;
				}
			}
			arsort($pagesvues);
			if($nbaffipagesvues != 1000){
				$pagesvues = array_slice($pagesvues, 0, $nbaffipagesvues, true);
			}
			foreach($pagesvues as $key => $value){
				$scoremax = $pagesvues[$key];
				break;
			}
			?>
			<div class="blockgraph">
				<div class="stats multicolor">
					<h4>Pages vues, comptabilisées une seule fois par session</h4>
					<ul>
					<?php foreach($pagesvues as $page=>$score){
						// Adaptation de la longueur au score
						$long =ceil((float)($score/$scoremax)*10)*10;
						?>	<li><?php echo $page; ?><span class="percent v<?php echo $long; ?>"> <?php echo $score; ?></span></li>	
						<?php }
					?>
					</ul>
				</div>
			</div><br/><br/>
		<?php }
		
		// Affichage des langages préférés en prenant en compte cumul.json et sessionLog.json
		if($nbaffilangues != 0){
			$langues = array();
			foreach($log as $numSession=>$val){
				$lang = $log[$numSession]['client'][0];
				if($log[$numSession]['client'][0] != 'fichier langages.txt absent'){
					if(isset($langues[$lang])){
						$langues[$lang] = $langues[$lang] + 1;
					}
					else{
						$langues[$lang] = 1;
					}
				}
			}
			foreach($cumul['clients']['langage'] as $lang=>$values){
				if(isset($langues[$lang])){
					$langues[$lang] = $langues[$lang] + $values;
				}
				else{
					$langues[$lang] = $values;
				}
			}
			arsort($langues);
			if($nbaffilangues != 1000){
				$langues = array_slice($langues, 0, $nbaffilangues, true);
			}
			foreach($langues as $key => $value){
				$scoremax = $langues[$key];
				break;
			}
			?>
			<div class="blockgraph">
				<div class="stats grey_gradiant">
					<h4>Langages préférés</h4>
					<ul>
					<?php foreach($langues as $lang=>$score){
						// Adaptation de la longueur au score
						$long =ceil((float)($score/$scoremax)*10)*10;
						?>	<li><?php echo $lang; ?><span class="percent v<?php echo $long; ?>"> <?php echo $score; ?></span></li>	
						<?php }
					?>
					</ul>
				</div>
			</div><br/><br/>
		<?php }	
		
		// Affichage des navigateurs en prenant en compte cumul.json et sessionLog.json
		if($nbaffinavigateurs != 0){
			$navigateurs = array();
			foreach($log as $numSession=>$val){
				$nav = $log[$numSession]['client'][1];
				if($log[$numSession]['client'][1] != 'fichier navigateurs.txt absent'){
					if(isset($navigateurs[$nav])){
						$navigateurs[$nav] = $navigateurs[$nav] + 1;
					}
					else{
						$navigateurs[$nav] = 1;
					}
				}
			}
			foreach($cumul['clients']['navigateur'] as $navig=>$values){
				if(isset($navigateurs[$navig])){
					$navigateurs[$navig] = $navigateurs[$navig] + $values;
				}
				else{
					$navigateurs[$navig] = $values;
				}
			}
			arsort($navigateurs);
			if($nbaffinavigateurs != 1000){
				$navigateurs = array_slice($navigateurs, 0, $nbaffinavigateurs, true);
			}
			foreach($navigateurs as $key => $value){
				$scoremax = $navigateurs[$key];
				break;
			}
			?>
			<div class="blockgraph">
				<div class="stats green_gradiant">
					<h4>Navigateurs</h4>
					<ul>
					<?php foreach($navigateurs as $navig=>$score){
						// Adaptation de la longueur au score
						$long =ceil((float)($score/$scoremax)*10)*10;
						?>	<li><?php echo $navig; ?><span class="percent v<?php echo $long; ?>"> <?php echo $score; ?></span></li>	
						<?php }
					?>
					</ul>
				</div>
			</div><br/><br/>
		<?php } 
		
		// Affichage des systèmes d'exploitation en prenant en compte cumul.json et sessionLog.json
		if($nbaffise != 0){
			$systemes = array();
			foreach($log as $numSession=>$val){
				$syse = $log[$numSession]['client'][2];
				if($log[$numSession]['client'][2] != 'fichier systemes.txt absent'){
					if(isset($systemes[$syse])){
						$systemes[$syse] = $systemes[$syse] + 1;
					}
					else{
						$systemes[$syse] = 1;
					}
				}
			}
			foreach($cumul['clients']['systeme'] as $syse=>$values){
				if(isset($systemes[$syse])){
					$systemes[$syse] = $systemes[$syse] + $values;
				}
				else{
					$systemes[$syse] = $values;
				}
			}
			arsort($systemes);
			if($nbaffise != 1000){
				$systemes = array_slice($systemes, 0, $nbaffise, true);
			}
			foreach($systemes as $key => $value){
				$scoremax = $systemes[$key];
				break;
			}
			?>
			<div class="blockgraph">
				<div class="stats grey_gradiant">
					<h4>Systèmes d'exploitation</h4>
					<ul>
					<?php foreach($systemes as $syse=>$score){
						// Adaptation de la longueur au score
						$long =ceil((float)($score/$scoremax)*10)*10;
						?>	<li><?php echo $syse; ?><span class="percent v<?php echo $long; ?>"> <?php echo $score; ?></span></li>	
						<?php }
					?>
					</ul>
				</div>
			</div><br/><br/>
		<?php } 
		
		// Affichage des pays en prenant en compte cumul.json et sessionLog.json
		if($nbaffipays != 0 && $geolocalisation){
			$tabpays = array();
			foreach($log as $numSession=>$val){
				if($log[$numSession]['geolocalisation'] != 'Fichier  - clef_ipapi_com.txt  - absent , .'){
					// Extraction du pays
					$postiret = strpos($log[$numSession]['geolocalisation'], '-');
					$pays = substr($log[$numSession]['geolocalisation'], 0, $postiret - 1);
					if(isset($tabpays[$pays])){
						$tabpays[$pays] = $tabpays[$pays] + 1;
					}
					else{
						$tabpays[$pays] = 1;
					}
				}
			}
			foreach($cumul['clients']['localisation'] as $pays=>$values){
				if(isset($tabpays[$pays])){
					$tabpays[$pays] = $tabpays[$pays] + $values;
				}
				else{
					$tabpays[$pays] = $values;
				}
			}
			arsort($tabpays);
			if($nbaffipays != 1000){
				$tabpays = array_slice($tabpays, 0, $nbaffipays, true);
			}
			foreach($tabpays as $key => $value){
				$scoremax = $tabpays[$key];
				break;
			}
			?>
			<div class="blockgraph">
				<div class="stats multicolor">
					<h4>Pays</h4>
					<ul>
					<?php foreach($tabpays as $clefpays=>$score){
						// Adaptation de la longueur au score
						$long =ceil((float)($score/$scoremax)*10)*10;
						?>	<li><?php echo $clefpays; ?><span class="percent v<?php echo $long; ?>"> <?php echo $score; ?></span></li>	
						<?php }
					?>
					</ul>
				</div>
			</div><br/>
		<?php } ?>	
		<!-- Fin Affichage des pays-->
		
		<br/><h3>
		<div class="row">
			<div class="col4">
				<?php echo 'Robots détectés : '.$cumul['robots']['ua']; ?>
			</div>
			<div class="col4">
				<?php echo 'Sessions invalides : '.($cumul['robots']['np'] + $cumul['robots']['tv'] + $cumul['robots']['ue']);?>
			</div>
		</div></h3>
	<!-- Fermeture bloc principal -->
	</div>


	<?php
	/*
	 * Affichage des visites, pages vues, durée des x dernières dates du fichier chrono.json
	 *
	*/ 
	if( $nbAffiDates != 0){
		$json = file_get_contents($module::$fichiers_json.'chrono.json');
		$chrono = json_decode($json, true);
		// Mise à jour sans sauvegarde en prenant en compte sessionLog.json
		foreach($log as $numSession=>$val){
			$datetimei = strtotime(substr($log[$numSession]['vues'][0], 0 , 19));
			$nbpageparsession = count($log[$numSession]['vues']);
			// Si $nbpageparsession <=1 on force la valeur de $datetimef
			if($nbpageparsession <= 1){ 
				$datetimef = $datetimei + $timeVisiteMini;
			}
			else{
				$datetimef = strtotime(substr($log[$numSession]['vues'][$nbpageparsession - 1], 0 , 19));
			}
			$dureesession = $datetimef - $datetimei;
			$dateclef = substr($log[$numSession]['vues'][0], 0 , 10);
			if( ! isset($chrono[$dateclef])){
				$chrono[$dateclef] = array( 'nb_visites' => 0, 'nb_pages_vues' => 0, 'duree' =>0);
			}
			$chrono[$dateclef]['nb_visites']++;
			$chrono[$dateclef]['nb_pages_vues'] = $chrono[$dateclef]['nb_pages_vues'] + $nbpageparsession;
			$chrono[$dateclef]['duree'] = $chrono[$dateclef]['duree'] + $dureesession;
		}
		// Tri du tableau par clefs en commençant par la date la plus récente
		krsort($chrono);
		?>
		<div class="block">
			<h4>Affichage chronologique résumé</h4>	
			<?php
			$i = 0;
			foreach($chrono as $date=>$value){
				$dureeparvisite = '';
				if($chrono[$date]['nb_visites'] > 0){
					$dureeparvisite = conversionTime( (int)($chrono[$date]['duree'] / $chrono[$date]['nb_visites']));
				}
				?>
				<div class="row">
					<div class="col3">
						<?php echo '<strong>'.$date.'</strong> : Visites => '.$chrono[$date]['nb_visites']; ?>
					</div>
					<div class="col2">
						<?php echo 'Pages vues => '.$chrono[$date]['nb_pages_vues']; ?>
					</div>
					<div class="col3">
						<?php echo 'Durée totale => '.conversionTime($chrono[$date]['duree']); ?>
					</div>
					<div class="col4">
						<?php if($chrono[$date]['nb_visites'] > 0){ echo 'Durée moyenne par visite => '.$dureeparvisite; }?>
					</div>
				</div>
				<?php 
				$i++;
				if($i >= $nbAffiDates) { break;}
			} ?>
		</div>	
	<?php
	}
	/*
	 * Affichage détaillé pour les enregistrements du fichier affitampon.json
	 *
	*/
	$json = file_get_contents($module::$fichiers_json.'affitampon.json');
	$tampon = json_decode($json, true);
	// on change les clefs de $tampon : 0,1,2,3,...
	$i=0;
	$tableau = array();
	foreach($tampon as $key=>$value){
		$tableau[$i] = $value;
		$i++;
	}
	$tampon = $tableau;
	$nbsessiontampon = count($tampon);
	$tableau = array();
	for($i=0; $i < $nbEnregSession; $i++){
		$tableau[$i] = $tampon[$nbsessiontampon - 1 - $i];
		if($nbsessiontampon - 1 - $i == 0){ break;}
	}
	if( isset($tableau[0]['vues'][0])){
		// Recherche de la première date dans le fichier courant
		$datedebut = date('Y/m/d H:i:s');
		$datedebut = substr($tableau[count($tableau) - 1]['vues'][0], 0 , 19);
		?>
		<div class="block">
			<h4>Affichage détaillé des dernières visites</h4>	
			<?php
			$comptepages = 0;
			$comptevisites = 0;
			foreach($tableau as $num=>$values){
				$pagesvues ='';
				$nbpageparsession = count($tableau[$num]['vues']);
				$datetimei = strtotime(substr($tableau[$num]['vues'][0], 0 , 19));
				$datetimef = strtotime(substr($tableau[$num]['vues'][$nbpageparsession - 1], 0 , 19));
				$dureevisite = 0;
				for( $i=0 ; $i < $nbpageparsession - 1 ; $i++){
					$nompage = substr($tableau[$num]['vues'][$i], 22 , strlen($tableau[$num]['vues'][$i]));
					$dureepage = strtotime(substr($tableau[$num]['vues'][$i + 1], 0 , 19)) - strtotime(substr($tableau[$num]['vues'][$i], 0 , 19));
					$pagesvues .= $nompage.' ('.$dureepage.' s) - ';
					$dureevisite = $dureevisite + $dureepage;
				}
				$pagesvues .= substr($tableau[$num]['vues'][$nbpageparsession - 1], 22 , strlen($tableau[$num]['vues'][$nbpageparsession - 1]));
				// Affichages
				echo '<strong> - Début de session : '.substr($tableau[$num]['vues'][0], 0 , 19).'</strong><br/>';
				if($geolocalisation){
					echo ' >><em> Géolocalisation : '.$tableau[$num]['geolocalisation'].'</em><br/>';
				}
				echo ' - User Agent : '.$tableau[$num]['userAgent'].'<br/>';
				echo ' >><em> Système d\'exploitation : '.$tableau[$num]['client'][2].'</em><br/>';
				echo ' >><em> Navigateur : '.$tableau[$num]['client'][1].'</em><br/>';
				echo ' - Accept Language : '.$tableau[$num]['langage'].'<br/>';
				echo ' >><em> Langage préféré : '.$tableau[$num]['client'][0].'</em><br/>';
				echo ' - Referer : '.$tableau[$num]['referer'].'<br/>';
				echo '<em> - Nombre total de pages vues : '.$nbpageparsession.'</em><br/>';
				if($nbpageparsession >= 1){
					echo ' - Pages vues (durée) : '.$pagesvues.'<br/>';
				}
				else{
					echo ' - Pages vues : '.$pagesvues.'<br/>';
				}
				$dureevisite = conversionTime($dureevisite);
				if($dureevisite != '0 s'){
					echo '<em> - Durée de la visite > à '. $dureevisite.'</em><br/>'.'<br/>';
				}
				else{
					echo ' - Durée de la visite : ?'.'<br/>'.'<br/>';
				}
				$comptevisites++;
				$comptepages = $comptepages + $nbpageparsession;
			}
			
			// Affichage du bilan pour la période en cours
			echo '<strong>Visites depuis le '.$datedebut.'</strong><br/>'.'<br/>';
			echo ' - Nombre total de pages vues : '.$comptepages.'<br/>';
			echo ' - Nombre de visites : '.$comptevisites.'<br/>'.'<br/>';
		}
	?>
	</div>
	<?php
	/* Conversion secondes en heures minutes secondes */

	function conversionTime($Seconde){
		$Heure = 0;
		$Minute = 0;
		while ($Seconde >= 3600)
		{$Heure = $Heure + 1; $Seconde = $Seconde - 3600;}
		while ($Seconde >= 60)
		{$Minute = $Minute + 1; $Seconde = $Seconde - 60;}
		if ($Heure > 0)
		{$Convert = $Heure.' h '.$Minute.' min '.$Seconde.' s'; return $Convert;}
		elseif ($Minute > 0)
		{$Convert = $Minute.' min '.$Seconde.' s'; return $Convert;}
		else
		{$Convert = $Seconde.' s'; return $Convert;}
	}
?>





