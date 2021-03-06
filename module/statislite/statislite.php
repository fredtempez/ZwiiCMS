<?php

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 *
 * Module StatisLite, un analyseur d'audience léger
 * Développé par Sylvain Lelièvre
 */

class statislite extends common {

	public static $actions = [
		'config' => self::GROUP_MODERATOR,
		'initJson' => self::GROUP_MODERATOR,
		'sauveJson' => self::GROUP_MODERATOR,
		'index' => self::GROUP_VISITOR
	];

	// Temps minimum de la visite
	public static $timeVisiteMini = [
		'10' => '10 secondes',
		'20' => '20 secondes',
		'30' => '30 secondes',
		'40' => '40 secondes',
		'60' => '1 minute',
		'120' => '2 minutes'
	];
	
	// Temps minimum de vue d'une page
	public static $timePageMini = [
		'3' => '3 secondes',
		'5' => '5 secondes',
		'10' => '10 secondes',
		'20' => '20 secondes',
		'40' => '40 secondes',
		'60' => '1 minute'
	];
	
	// Nombre de pages minimum visionnées
	public static $nbPageMini = [
		'1' => '1 page',
		'2' => '2 pages',
		'3' => '3 pages',
		'4' => '4 pages',
		'5' => '5 pages'
	];
	
	// Utilisateurs connectés à exclure des statistiques
	public static $users_exclus = [
		'4' => 'aucun',
		'1' => 'tous',
		'2' => 'editeurs et administrateurs',
		'3' => 'administrateurs'
	];
	
	// Nombre de visites affichées dans affichage détaillé
	public static $nbEnregSession = [
		'5' => '5 visites',
		'10' => '10 visites',
		'20' => '20 visites',
		'50' => '50 visites',
		'100' => '100 visites'
	];	
	
	// Nombre de dates affichées dans affichage chronologique
	public static $nbAffiDates = [
		'0' => 'aucune',
		'5' => '5 dates',
		'10' => '10 dates',
		'20' => '20 dates',
		'1000' => 'toutes les dates'		
	];
	
	// Nombre de pages vues affichées graphiquement
	public static $nbaffipagesvues = [
		'0' => 'aucune',
		'5' => '5 pages',
		'10' => '10 pages',
		'20' => '20 pages',
		'1000' => 'toutes les pages'
	];
	
	// Nombre de langues affichées graphiquement
	public static $nbaffilangues = [
		'0' => 'aucune',
		'5' => '5 langues',
		'10' => '10 langues',
		'20' => '20 langues',
		'1000' => 'toutes les langues'
	];
	
	// Nombre de navigateurs affichés graphiquement
	public static $nbaffinavigateurs = [
		'0' => 'aucun',
		'5' => '5 navigateurs',
		'10' => '10 navigateurs',
		'20' => '20 navigateurs',
		'1000' => 'tous les navigateurs'
	];
	
	// Nombre de systèmes d'exploitation affichés graphiquement
	public static $nbaffise = [
		'0' => 'aucun',
		'5' => '5 systèmes',
		'10' => '10 systèmes',
		'20' => '20 systèmes',
		'1000' => 'tous les systèmes'
	];
	
	// Nombre de pays affichés graphiquement
	public static $nbaffipays = [
		'0' => 'aucun',
		'5' => '5 pays',
		'10' => '10 pays',
		'20' => '20 pays',
		'1000' => 'tous les pays'
	];
	
	// Variables transmises à view/index/index.php
	public static $comptepages = 0;
	public static $comptevisite = 0;
	public static $dureevisites = 0;
	public static $fichiers_json = './site/file/statislite/json/';
	public static $tmp = './site/file/statislite/tmp/';
	public static $base = './site/file/statislite/';
	public static $json_sauve = './site/file/statislite/json_sauve/';
	public static $filtres_primaires = './site/file/statislite/filtres_primaires/';
	
	// Temps entre 2 mises à jour de cumul.json et chrono.json (11 minutes)
	public static $timemaj = 660;
	
	const STATISLITE_VERSION = '2.5';	

	/**
	 * Configuration
	 */
	public function config() {
	
		// Ajout dans le fichier site/file/statislite/filtres_primaires/liste_querystring.txt de pages à exclure des statistiques
		if( is_file( self::$filtres_primaires.'liste_querystring.txt')){
			$qs = file_get_contents(self::$filtres_primaires.'liste_querystring.txt');
			$qsnew = str_replace('pages_stat',$this->getUrl(0).'|page\/edit\/'.$this->getUrl(0).'|'.$this->getUrl(0).'\/config', $qs);
			file_put_contents( self::$filtres_primaires.'liste_querystring.txt', $qsnew);
		}

		// Soumission du formulaire
		if($this->isPost()) {
			$this->setData(['module', $this->getUrl(0), 'config',[
				'timeVisiteMini' => $this->getInput('statisliteConfigTimeVisiteMini', helper::FILTER_STRING_SHORT, true),
				'timePageMini' => $this->getInput('statisliteConfigTimePageMini', helper::FILTER_STRING_SHORT, true),
				'nbPageMini' => $this->getInput('statisliteConfigNbPageMini', helper::FILTER_STRING_SHORT, true),
				'usersExclus' => $this->getInput('statisliteConfigUsersExclus', helper::FILTER_STRING_SHORT, true),
				'nbEnregSession' => $this->getInput('statisliteConfigNbEnregSession', helper::FILTER_STRING_SHORT, true),
				'geolocalisation' => $this->getInput('statisliteConfigGeolocalisation', helper::FILTER_BOOLEAN),
				'nbaffipagesvues' => $this->getInput('statisliteConfigNbAffiPagesVues'), 
				'nbaffilangues' => $this->getInput('statisliteConfigNbAffiLangues'), 
				'nbaffinavigateurs' => $this->getInput('statisliteConfigNbAffiNavigateurs'),
				'nbaffise' => $this->getInput('statisliteConfigNbAffiSe'),
				'nbaffipays' => $this->getInput('statisliteConfigNbAffiPays'),
				'nbaffidates' => $this->getInput('statisLiteConfigNbAffiDates'),
				'config' => true
			]]);
			
			// Inclusion dans body.inc.html
			$str=[];
			$str[0] = '<!-- Ne pas effacer * Module Statislite inclusion dans body-->';
			$str[1] = '<?php if(is_dir("./module/statislite")) include "./module/statislite/include/stat.php"; ?>';
			$str[2] = '<!-- Module Statislite fin d\'inclusion -->';
			$strbody ='';
			foreach($str as $key=>$value){
				$strbody = $strbody.$value."\r\n";
			}
			// Si le fichier body.inc.html existe
			if( file_exists('./site/data/body.inc.html' )){
				$file = file_get_contents( './site/data/body.inc.html' );
				// Les chaînes ne sont pas trouvées
				if( strpos( $file, $str[0]) === false || strpos( $file, $str[2]) === false || strpos($file,$str[1]) === false  ){
					file_put_contents( './site/data/body.inc.html', $file."\r\n".$strbody);
				}
			}
			else{
				file_put_contents( './site/data/body.inc.html', $strbody );
			}
			
			// Restauration si le fichier sélectionné est un fichier cumul.json
			$file_cumul = $this->getInput('configRestoreJson', helper::FILTER_STRING_SHORT);
			if( strpos( $file_cumul, 'cumul.json' ) === 15){
				// Sauvegarde de sécurité des fichiers json
				$this->sauvegardeJson();
				$date = substr( $file_cumul, 0 , 15);
				$nameFile = [ '0'=>'cumul.json', '1'=>'affitampon.json', '2'=>'chrono.json', '3'=>'robots.json', '4'=>'sessionInvalide.json', '5'=>'sessionLog.json',   ];
				foreach( $nameFile as $key=>$file){
					if( is_file( self::$json_sauve.$date.$file )){
						file_put_contents(self::$fichiers_json.$file, file_get_contents(self::$json_sauve.$date.$file));
					}
				}
			}
		
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl().$this->getUrl(),
				'notification' => 'Modifications enregistrées',
				'state' => true
			]);
		}
		else{
			// Valeurs en sortie
			$this->addOutput([
				'title' => 'Configuration du module',
				'view' => 'config'
			]);
		}
	}


	/**
	 * Fonction initJson()
	 */
	public function initJson() {
		// Jeton incorrect
		if ($this->getUrl(2) !== $_SESSION['csrf']) {
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl()  . $this->getUrl(0) . '/config',
				'notification' => 'Action non autorisée'
			]);
		} else {
			// Sauvegarde de sécurité des fichiers json
			$this->sauvegardeJson();
			// Réinitialisation des fichiers json
				$this -> initcumul();
				$this -> initchrono();
				file_put_contents( self::$fichiers_json.'robots.json', '{}');
				file_put_contents( self::$fichiers_json.'sessionInvalide.json', '{}');
				file_put_contents( self::$fichiers_json.'affitampon.json', '{}');
				file_put_contents( self::$fichiers_json.'sessionLog.json', '{}');
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => 'Réinitialisation des fichiers json effectuée',
				'state' => true
			]);
			
		}
	}

	
	/**
	 * Fonction sauveJson()
	 */
	public function sauveJson() {
		// Sauvegarde des fichiers json
		$this->sauvegardeJson();
		// Valeurs en sortie
		$this->addOutput([
			'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'notification' => 'Sauvegarde des fichiers json effectuée',
			'state' => true
		]);
			
	}
	
	
	/**
	 * Fonction index()
	 */
	public function index() {
	
		// Si le module n'existe pas, on le crée avec des valeurs par défaut
		if( $this->getData(['module', $this->getUrl(0), 'config', 'config']) !== true){
			$this->setData(['module', $this->getUrl(0), 'config',[
				'timeVisiteMini' => '30',
				'timePageMini' => '5',
				'nbPageMini' => '2',
				'usersExclus' => '3',
				'nbEnregSession' => '5',
				'geolocalisation' => false,
				'nbaffipagesvues' => '10',
				'nbaffilangues' => '5',
				'nbaffinavigateurs' => '5',
				'nbaffise' => '5',
				'nbaffipays' => '5',
				'nbaffidates' => '5',
				'config' => false
			]]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl().$this->getUrl(0).'/config',
				'notification' => 'Module initialisé avec les valeurs par défaut',
				'state' => true
			]);
		}
		else{
			
			
			/* 
			 * Paramètres réglés en configuration du module
			*/
			// Temps minimum à passer sur le site en secondes pour valider une visite
			$timeVisiteMini = $this->getData(['module', $this->getUrl(0), 'config', 'timeVisiteMini' ]);
			// Temps minimum à passer sur une page pour la considérer comme vue
			$timePageMini = $this->getData(['module', $this->getUrl(0), 'config', 'timePageMini' ]);
			// Nombre de pages vues dans le site minimum
			$nbpagemini = $this->getData(['module', $this->getUrl(0), 'config', 'nbPageMini' ]);
			// Utilisateurs connectés à exclure des statistiques
			$usersExclus = $this->getData(['module', $this->getUrl(0), 'config', 'usersExclus' ]);
			// Affichage graphique : nombre de pages vues à afficher en commençant par la plus fréquente de 0 à toutes
			$nbaffipagesvues = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffipagesvues']);
			// Affichage graphique : nombre de langues à afficher en commençant par la plus fréquente de 0 à toutes
			$nbaffilangues = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffilangues']);
			// Affichage graphique : nombre de navigateurs à afficher en commençant par le plus fréquent de 0 à toutes
			$nbaffinavigateurs = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffinavigateurs']);
			// Affichage graphique : nombre de systèmes d'exploitation à afficher en commençant par le plus fréquent de 0 à toutes
			$nbaffise = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffise']);
			// Affichage graphique : nombre de pays à afficher en commençant par le plus fréquent de 0 à toutes
			$nbaffipays = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffipays']);
			// Nombre de sessions affichées dans l'affichage détaillé
			$nbEnregSession = $this->getData(['module', $this->getUrl(0), 'config', 'nbEnregSession' ]);
			// Nombre de dates affichées dans l'affichage chronologique
			$nbAffiDates = $this->getData(['module', $this->getUrl(0), 'config', 'nbaffidates' ]);
			// option avec geolocalisation
			$geolocalisation = $this->getData(['module', $this->getUrl(0), 'config', 'geolocalisation' ]);
			
			// Initialisations variables
			//self::$base = './site/file/statislite/';
			self::$comptevisite = 0;
			self::$comptepages = 0;
			self::$dureevisites = 0;
			$datedebut = date('Y/m/d H:i:s');

			// Initialisation du fichier cumul.json
			if(! is_file(self::$fichiers_json.'cumul.json')){
				$this -> initcumul();
			}
			
			// Initialisation du fichier chrono.json avec pour clef la date, pour valeurs le nombre visites, le nombre de pages vues, la durée totale
			if(! is_file(self::$fichiers_json.'chrono.json')){
				$this -> initchrono();
			}

			// Lecture et décodage du fichier sessionLog.json
			if( is_file(self::$fichiers_json.'sessionLog.json')){
				$json = file_get_contents(self::$fichiers_json.'sessionLog.json');
			}
			else{
				$json = '{}';
			}
			$log = json_decode($json, true);

			// Recherche de la première date dans le fichier sessionLog.json
			foreach($log as $numSession=>$values){
				$datedebut = substr($log[$numSession]['vues'][0], 0 , 19);
				break;
			}
			
			// Remplacement du nom de vue 'Page d'accueil' par le nom de la page d'accueil
			foreach($log as $numSession=>$values){
				foreach($values['vues'] as $key=>$value){
					if( substr($value, 22 , strlen($value)) == 'Page d\'accueil'){
						$log[$numSession]['vues'][$key] = substr($value, 0 , 19).' * '.$this->getData(['config','homePageId']);
					}
				}
			}

			/*
			 * Filtrage des vues et des visites dans le fichier sessionLog.json
			 * vues invalidées si : temps passé sur une page < $timePageMini ou 2 pages consécutives de même nom (si au moins 2 pages vues)
			 * vues invalidées si : elles ne correspondent pas à une page existante (le nom de la vue doit commencer par le nom d'une page existante)
			 * visites invalidées si: nombre de pages vues < $nbpagemini ou temps de visite < $timeVisiteMini.
			 * visites invalidées si : utilisateur connecté exclu des statistiques
			 * Comptage des vues par session et des visites validées,
			 * Comptage des sessions invalidées par $nbpagemini, $timeVisiteMini ou $usersExclus.
			*/
			
			foreach($log as $numSession=>$values){
				$nbpageparsession = count($log[$numSession]['vues']);
				// Eliminer les vues dont le nom ne commence pas par un nom de page existante
				$pages = $this->getData(['page']);
				foreach($pages as $page => $pageId) {
					if ($this->getData(['page',$page,'block']) === 'bar' ||
					$this->getData(['page',$page,'disable']) === true) {
						unset($pages[$page]);
					}
				}
				$renum = false;
				foreach($log[$numSession]['vues'] as $key => $nom_vue){
					$nom = substr($nom_vue, 22);
					if(strpos($nom,'/') === false){
						$debut_nom = $nom;
					}
					else{
						$debut_nom = substr($nom, 0, strpos($nom,'/'));
					}
					$occurence = false;
					foreach($pages as $page => $pageId){
						if($debut_nom == $page){
							$occurence = true;
						}
					}
					if($occurence === false){
						unset($log[$numSession]['vues'][$key]);
						$renum = true;
					}
				}
				if($renum){
					$i = 0;
					$tableau[$numSession] =  array('vues' => array() );
					foreach($log[$numSession]['vues'] as $key=>$value){
						$tableau[$numSession]['vues'][$i] = $value;
						$i++;
					}
					$log[$numSession]['vues'] = $tableau[$numSession]['vues'];
					$nbpageparsession = count($log[$numSession]['vues']);
				}
				// Eliminer les vues dont la durée est inférieure à $timePageMini et les vues n portant le même nom que la vue n+1
				// si il y a au moins 2 pages vues dans la session
				$renum = false;
				if( $nbpageparsession > 1){
					for($i = 0; $i < $nbpageparsession - 1; $i++){
						if( strtotime(substr($log[$numSession]['vues'][$i + 1], 0 , 19)) - strtotime(substr($log[$numSession]['vues'][$i], 0 , 19)) < $timePageMini
							|| substr($log[$numSession]['vues'][$i+1], 22 , strlen($log[$numSession]['vues'][$i+1])) == substr($log[$numSession]['vues'][$i], 22 , strlen($log[$numSession]['vues'][$i]))){
							unset($log[$numSession]['vues'][$i]);
							$renum = true;
						}
					}
				}
				// Si nécessaire renuméroter les clefs du tableau $log[$numSession]['vues'] : 0,1,2 etc...
				if($renum){
					$i = 0;
					$tableau[$numSession] =  array('vues' => array() );
					foreach($log[$numSession]['vues'] as $key=>$value){
						$tableau[$numSession]['vues'][$i] = $value;
						$i++;
					}
					$log[$numSession]['vues'] = $tableau[$numSession]['vues'];
					$nbpageparsession = count($log[$numSession]['vues']);
				}
				$ip = $log[$numSession]['ip'];
				$datetimei = strtotime(substr($log[$numSession]['vues'][0], 0 , 19));
				// Si $nbpageparsession <=1 on force la valeur de $datetimef
				if($nbpageparsession <= 1){ 
					$datetimef = $datetimei + $timeVisiteMini;
				}
				else{
					$datetimef = strtotime(substr($log[$numSession]['vues'][$nbpageparsession - 1], 0 , 19));
				}
				$dureesession = $datetimef - $datetimei;
				// Recherche du groupe (0,1,2,3) correspondant à l'utilisateur connecté
				$groupe_user_connected = 0;
				$user_connected = $log[$numSession]['user_id'];
				if( null !== $this->getData(['user', $user_connected,'group'])){
					if($user_connected != 'visiteur'){
						$groupe_user_connected = $this->getData(['user', $user_connected, 'group']);
					}
				}
				// Si le nombre de pages vues dans la session est >= $nbpagemini et si la durée de la session est >= $timeVisiteMini
				// et si l'utilisateur connecté n'est pas exclu des statistiques
				if( $nbpageparsession >= $nbpagemini && $dureesession >= $timeVisiteMini
					&& $groupe_user_connected < $usersExclus ){
						// Mises à jour des variables pour affichage des statistiques
						self::$comptepages = self::$comptepages + $nbpageparsession;
						self::$comptevisite++;
						self::$dureevisites = self::$dureevisites + $dureesession;
						// Modification des élèments null en ''
						if( is_null($log[$numSession]['referer'])){$log[$numSession]['referer'] = '';}
						if( is_null($log[$numSession]['langage'])){$log[$numSession]['langage'] = '';}
						if( is_null($log[$numSession]['userAgent'])){$log[$numSession]['userAgent'] = '';}
						// Recherche de $log[$numSession]['client'][0] : langage préféré
						$log[$numSession]['client'][0] = $this->langage($log[$numSession]['langage']);
						// Recherche de $log[$numSession]['client'][1] : navigateur
						$log[$numSession]['client'][1] = $this->navigateur($log[$numSession]['userAgent']);
						// Recherche de $log[$numSession]['client'][2] : système d'exploitation
						$log[$numSession]['client'][2] = $this->systeme($log[$numSession]['userAgent']);
						// Geolocalisation si elle n'a pas été faite et si l'IP n'est pas déjà détruite
						if(isset($log[$numSession]['ip'])){
							if($geolocalisation && ! isset($log[$numSession]['geolocalisation'])){
								$geo = $this->geolocalise($log[$numSession]['ip']);
								$log[$numSession]['geolocalisation'] = $geo['country_name'].' - '.$geo['city'];
							}
							// CNIL : ne pas mémoriser d'adresse IP
							unset($log[$numSession]['ip']);
						}
				}
				// Sinon on supprime cet enregistrement de sessionLog.json et on l'enregistre dans sessionInvalide.json
				// puis on enregistre dans cumul.json le résultat du filtrage par nombre de pages,temps de visite ou utilisateur exclu
				else{			
					// Lecture et décodage du fichier sessionInvalide.json
					if( is_file(self::$fichiers_json.'sessionInvalide.json') ){
						$json = file_get_contents(self::$fichiers_json.'sessionInvalide.json');
					}
					else{
						$json = '{}';
					}
					$poub = json_decode($json, true);
					$poub[$numSession] = $log[$numSession];
					// CNIL : même dans le fichier sessionInvalide.json on ne conserve pas d'IP
					unset($poub[$numSession]['ip']);
					unset($poub[$numSession]['client']);
					// Limitation de la taille du fichier sessionInvalide.json à 200 enregistrements
					if(count($poub) > 200){
						foreach($poub as $key=>$value){
							unset($poub[$key]);
							break;
						}
					}
					// Encodage et sauvegarde du fichier sessionInvalide.json
					$json = json_encode($poub);
					file_put_contents(self::$fichiers_json.'sessionInvalide.json',$json);
					// Suppression de la session
					unset($log[$numSession]);
					// Enregistrement dans cumul.json du résultat du filtrage
					// np + tv + ue >= nombre de sessionInvalide car une même session peut être éliminée plusieurs fois
					// A chaque fois np tv ou ue s'incrémente mais la sessionInvalide de même numéro de session est simplement modifiée
					if($nbpageparsession < $nbpagemini){
						$type = 'np';
					}
					elseif($dureesession < $timeVisiteMini){
						$type = 'tv';
					}
					else{
						$type = 'ue';
					}
					$json = file_get_contents(self::$fichiers_json.'cumul.json');
					$cumul = json_decode($json, true);
					$cumul['robots'][$type] = $cumul['robots'][$type] + 1;
					$json = json_encode($cumul);
					file_put_contents(self::$fichiers_json.'cumul.json',$json);
				}
			}
			
			
			/*
			 * Mise à jour du dossier affitampon.json destiné à l'affichage détaillé 
			 *
			*/
			if( is_file(self::$fichiers_json.'affitampon.json')){
				$json = file_get_contents(self::$fichiers_json.'affitampon.json');
			}
			else{
				$json='{}';
			}
			$tampon = json_decode($json, true);
			foreach($log as $numSession=>$values){
				$tampon[$numSession] = $log[$numSession];			
			}
			// Fichier limité à 200 enregistrements
			if( count($tampon) > 200){
				foreach($tampon as $key=>$value){
					unset($tampon[$key]);
					if(count($tampon) <= 200){ break; }
				}
			}
			$json = json_encode($tampon);
			file_put_contents(self::$fichiers_json.'affitampon.json',$json);
			
			
			/* 
			 * Sauvegarde des données de sessionLog.json vers cumul.json et chrono.json
			 * Réalisée si le dernier clic pour chaque session de sessionLog.json date de plus de $timemaj >= 10 minutes
			 * Objectif conserver dans sessionLog.json les sessions qui sont encore peut être actives
			 * Sauvegarde des données de filtre_primaire.json vers cumul.json
			*/

			$json = file_get_contents(self::$fichiers_json.'cumul.json');
			$cumul = json_decode($json, true);
			
			// Sauvegarde des données de filtre_primaire.json vers cumul.json
			$json = file_get_contents(self::$fichiers_json.'filtre_primaire.json');
			$fp = json_decode($json, true);
			$cumul['robots']['ua'] = $cumul['robots']['ua'] + $fp['robots']['ua'];
			$cumul['robots']['ip'] = $cumul['robots']['ip'] + $fp['robots']['ip'];
			$fp['robots']['ua'] = 0;
			$fp['robots']['ip'] = 0;
			$json = json_encode($fp);
			file_put_contents(self::$fichiers_json.'filtre_primaire.json',$json);
			
			// Sauvegarde des données de sessionLog.json vers cumul.json et chrono.json
			foreach($log as $numSession=>$values){
				$nbpageparsession = count($log[$numSession]['vues']);
				$nbpagesvalides = $nbpageparsession;
				$tab = $log;
				if( (time() - strtotime(substr($log[$numSession]['vues'][$nbpageparsession -1], 0, 19))) > self::$timemaj){
					// Comptage du nombre de pages dans la session en ne comptant qu'une fois les pages de même nom 
					// $nbpagesvalides sera utilisé par cumul.json et chrono.json, $tab est utilisé pour la maj du tableau $cumul['pages']
					if($nbpageparsession >= 2){
						foreach($tab[$numSession]['vues'] as $key=>$value){
							$nom = substr($value, 22 , strlen($value)); 
							//$date = strtotime(substr($value, 0 , 19)); ajouter dans le if && ( strtotime(substr($tab[$numSession]['vues'][$i], 0 , 19)) - $date) < 60)
							for($i=$key + 1 ; $i < $nbpageparsession; $i++){
								if( substr($tab[$numSession]['vues'][$i], 22 , strlen($tab[$numSession]['vues'][$i])) == $nom){
									unset($tab[$numSession]['vues'][$i]);
								}
							}
						}
						$nbpagesvalides = count($tab[$numSession]['vues']);
					}
					// Mise à jour du tableau $cumul
					$cumul['nb_clics'] = $cumul['nb_clics']  + $nbpagesvalides;
					$cumul['nb_visites']++;
					$cumul['date_fin'] = substr( $log[$numSession]['vues'][$nbpageparsession - 1], 0, 19);
					$datetimei = strtotime(substr($log[$numSession]['vues'][0], 0 , 19));
					$datetimef = strtotime(substr($log[$numSession]['vues'][$nbpageparsession - 1], 0 , 19));
					$dureesession = $datetimef - $datetimei;
					$cumul['duree_visites'] = $cumul['duree_visites'] + $dureesession;
					
					//langage préféré
					if($log[$numSession]['client'][0] != 'fichier langages.txt absent'){
						$clefreconnue = false;
						foreach($cumul['clients']['langage'] as $key => $value){
							// Si la clef == l'enregistrement dans log de la langue préférée on incrémente la valeur
							if( $key == $log[$numSession]['client'][0]){
								$cumul['clients']['langage'][$key]++;
								$clefreconnue = true;
							}
						}
						// Si une clef valide n'a pas été trouvée on la crée avec une valeur initialisée à 1
						if(!$clefreconnue){
							$cumul['clients']['langage'][$log[$numSession]['client'][0]] = 1;
						}
					}
					
					// Navigateur
					if($log[$numSession]['client'][1] != 'fichier navigateurs.txt absent'){
						$clefreconnue = false;
						foreach($cumul['clients']['navigateur'] as $key => $value){
							// Si la clef == l'enregistrement dans log du navigateur on incrémente la valeur
							if( $key == $log[$numSession]['client'][1]){
								$cumul['clients']['navigateur'][$key]++;
								$clefreconnue = true;
							}
						}
						// Si une clef valide n'a pas été trouvée on la crée avec une valeur initialisée à 1
						if(!$clefreconnue){
							$cumul['clients']['navigateur'][$log[$numSession]['client'][1]] = 1;
						}
					}
					
					// Systèmes d'exploitation
					if($log[$numSession]['client'][2] != 'fichier systemes.txt absent'){
						$clefreconnue = false;
						foreach($cumul['clients']['systeme'] as $key => $value){
							// Si la clef == l'enregistrement dans log du systeme on incrémente la valeur
							if( $key == $log[$numSession]['client'][2]){
								$cumul['clients']['systeme'][$key]++;
								$clefreconnue = true;
							}
						}
						// Si une clef valide n'a pas été trouvée on la crée avec une valeur initialisée à 1
						if(!$clefreconnue){
							$cumul['clients']['systeme'][$log[$numSession]['client'][2]] = 1;
						}
					}
					
					// Geolocalisation
					if($log[$numSession]['geolocalisation'] != 'Fichier  - clef_ipapi_com.txt  - absent , .'){
						// Extraction du pays
						$postiret = strpos($log[$numSession]['geolocalisation'], '-');
						$pays = substr($log[$numSession]['geolocalisation'], 0, $postiret - 1);
						$clefreconnue = false;
						foreach($cumul['clients']['localisation'] as $key => $value){
							// Si la clef == l'enregistrement dans log de la geolocalisation on incrémente la valeur
							if( $key == $pays){
								$cumul['clients']['localisation'][$key]++;
								$clefreconnue = true;
							}
						}
						// Si une clef valide n'a pas été trouvée on la crée avec une valeur initialisée à 1
						if(!$clefreconnue){
							$cumul['clients']['localisation'][$pays] = 1;
						}
					}
					
					// Mise à jour des variables liées au fichier sessionLog.json
					self::$comptepages = self::$comptepages - $nbpageparsession;
					self::$comptevisite--;
					self::$dureevisites = self::$dureevisites - ( $datetimef - $datetimei );
					
					// Enregistrement des pages vues dans $cumul à partir de $tab
					foreach($tab[$numSession]['vues'] as $vues=>$values){
						$page = substr($values, 22, strlen($values));
						if(isset($cumul['pages'][$page])){
							$cumul['pages'][$page] = $cumul['pages'][$page] + 1;
						}
						else{
							$cumul['pages'][$page] = 1;
						}
					}
					
					// Mise à jour du fichier chrono.json
					$dateclef = substr($log[$numSession]['vues'][0], 0 , 10);
					$json = file_get_contents(self::$fichiers_json.'chrono.json');
					$chrono = json_decode($json, true);
					if( ! isset($chrono[$dateclef])){
						$chrono[$dateclef] = array( 'nb_visites' => 0, 'nb_pages_vues' => 0, 'duree' =>0);
					}
					$chrono[$dateclef]['nb_visites']++;
					$chrono[$dateclef]['nb_pages_vues'] = $chrono[$dateclef]['nb_pages_vues'] + $nbpagesvalides;
					$chrono[$dateclef]['duree'] = $chrono[$dateclef]['duree'] + $dureesession;
					// Tri du tableau par clefs en commençant par la date la plus récente
					krsort($chrono);
					// Limitation aux 100 dernières dates
					if( count($chrono) > 100){
						$derniereclef = '';
						foreach($chrono as $key => $value){
							$derniereclef = $key;
						}
						unset($chrono[$derniereclef]);
					}
					// Encodage et sauvegarde de chrono.json
					$json = json_encode($chrono);
					file_put_contents(self::$fichiers_json.'chrono.json',$json);
					
					// Suppression des données sauvegardées
					unset($log[$numSession]);	
				}
			}
			// Mise à jour des fichiers sessionLog.json et cumul.json
			$json = json_encode($log);
			file_put_contents(self::$fichiers_json.'sessionLog.json',$json);
			$json = json_encode($cumul);
			file_put_contents(self::$fichiers_json.'cumul.json',$json);

		
			// Valeurs en sortie
			$this->addOutput([
				'showBarEditButton' => true,
				'showPageContent' => true,
				'view' => 'index'
			]);
		}
	}
	
	
	/*
	 * Fonctions
	*/
	
	/* Recherche de la langue préférée*/
	private function langage($lang){
		$langsigle = strtolower(substr($lang, 0, 2));
		// Ouvrir le fichier langages.txt et le transformer en array()
		if(is_file(self::$base.'langages.txt')){
			$chaine = file_get_contents(self::$base.'langages.txt');
			// Suppression des lf
			$chaine1 = str_replace("\r", '', $chaine);
			$chaine = str_replace("\n", '', $chaine);
			$langues = explode('*', $chaine);
			foreach($langues as $souschaine){
				$tablang = explode(',' , $souschaine);
				if($tablang[0] == $langsigle){
					return $tablang[1];
				}
			}
			return 'non reconnu';	
		}
		else{
			return 'fichier langages.txt absent';
		}
	}
	
	/* Recherche du navigateur */
	private function navigateur($navig){
		$navig = strtolower($navig);
		// Ouvrir le fichier navigateurs.txt et le transformer en array()
		if(is_file(self::$base.'navigateurs.txt')){
			$chaine = file_get_contents(self::$base.'navigateurs.txt');
			// Suppression des cr lf
			$chaine1 = str_replace("\r", '', $chaine);
			$chaine = str_replace("\n", '', $chaine1);
			$navigateurs = explode('*', $chaine);
			foreach($navigateurs as $souschaine){
				$tabnavig = explode(',' , $souschaine);
				if(strpos($navig, $tabnavig[0]) !== false){
					return $tabnavig[1];
				}
			}
			return 'non reconnu';	
		}
		else{
			return 'fichier navigateurs.txt absent';
		}	
	}
	
	/* Recherche du système d'exploitation */
	private function systeme($se){
		$se = strtolower($se);
		// Ouvrir le fichier systemes.txt et le transformer en array()
		if(is_file(self::$base.'systemes.txt')){
			$chaine = file_get_contents(self::$base.'systemes.txt');
			// Suppression des cr lf
			$chaine1 = str_replace("\r", '', $chaine);
			$chaine = str_replace("\n", '', $chaine1);
			$systemes = explode('*', $chaine);
			foreach($systemes as $souschaine){
				$tabse = explode(',' , $souschaine);
				if(strpos($se, $tabse[0]) !== false){
					return $tabse[1];
				}
			}
			return 'non reconnu';	
		}
		else{
			return 'fichier systemes.txt absent';
		}	
	}
	

	/* Geolocalisation */

	private function geolocalise($ip){
		// Géolocalisation avec le site www.ipapi.com qui offre 10000 requêtes / mois
		if( is_file(self::$base.'clef_ipapi_com.txt')){
			$access_key = file_get_contents(self::$base.'clef_ipapi_com.txt');
			// Requête
			$ch = curl_init('http://api.ipapi.com/'.$ip.'?access_key='.$access_key.'');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//Enregistrement des données
			$json = curl_exec($ch);
			curl_close($ch);
			// Decode JSON response:
			$api_result = json_decode($json, true);
		}
		else{
			$api_result = array( 'country_name'=>'Fichier ','city'=> 'clef_ipapi_com.txt ','latitude'=> 'absent','longitude' => '.');
		}
		return $api_result;
	}
	
	/* Initialisation de cumul.json */
	private function initcumul(){
		$json = '{}';
		$cumul = json_decode($json, true);
		$cumul['nb_clics'] = 0;
		$cumul['nb_visites'] = 0;
		$cumul['duree_visites'] = 0;
		$cumul['clients'] = array( 'systeme' => array(), 'navigateur' => array(), 'langage' => array(), 'localisation' => array());
		// $cumul['robots'] comptabilise toutes les visites (sessions) exclues
		// 'ua' pour user agent de robot, 'ip' pour ip exclu, 'np' pour nombre de pages vues insuffisantes
		// 'tv' pour temps de visite trop court, 'ue' pour utilisateur exclu
		$cumul['robots'] = array( 'ua' => 0, 'ip'=> 0, 'np'=> 0, 'tv'=> 0, 'ue'=>0);
		// Si sessionLog.json existe et n'est pas vide date_debut sera sa première date sinon ce sera la date actuelle
		$cumul['date_debut'] = date('Y/m/d H:i:s');
		if(is_file(self::$fichiers_json.'sessionLog.json')){
			$json = file_get_contents(self::$fichiers_json.'sessionLog.json');
			$log = json_decode($json, true);
			foreach($log as $numSession=>$values){
				if(isset($log[$numSession]['vues'][0])){
					$cumul['date_debut'] = substr($log[$numSession]['vues'][0], 0 , 19);
				}
				break;
			}
		}
		$cumul['date_fin'] = date('Y/m/d H:i:s');
		$cumul['pages'] = array();
		$json = json_encode($cumul);
		file_put_contents(self::$fichiers_json.'cumul.json',$json);
	}
	
	/* Initilaisation de chrono.json */
	private function initchrono(){
		$json = '{}';
		$chrono = json_decode($json, true);
		$chrono[date('Y/m/d')] = array( 'nb_visites' => 0, 'nb_pages_vues' => 0, 'duree' =>0);
		$json = json_encode($chrono);
		file_put_contents(self::$fichiers_json.'chrono.json',$json);
	}
	
	/* Sauvegarde des fichiers json */
	private function sauvegardeJson(){
		$date = date('YmdHis');
		if( is_file( self::$fichiers_json.'robots.json' )) copy( self::$fichiers_json.'robots.json', self::$json_sauve.$date.'_robots.json');
		if( is_file( self::$fichiers_json.'affitampon.json' )) copy( self::$fichiers_json.'affitampon.json', self::$json_sauve.$date.'_affitampon.json');
		if( is_file( self::$fichiers_json.'chrono.json' )) copy( self::$fichiers_json.'chrono.json', self::$json_sauve.$date.'_chrono.json');
		if( is_file( self::$fichiers_json.'sessionInvalide.json' )) copy( self::$fichiers_json.'sessionInvalide.json', self::$json_sauve.$date.'_sessionInvalide.json');
		if( is_file( self::$fichiers_json.'cumul.json' )) copy( self::$fichiers_json.'cumul.json', self::$json_sauve.$date.'_cumul.json');
		if( is_file( self::$fichiers_json.'sessionLog.json' )) copy( self::$fichiers_json.'sessionLog.json', self::$json_sauve.$date.'_sessionLog.json');
		
	}
	
}

