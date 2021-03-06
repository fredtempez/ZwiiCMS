<?php
/*
 * Fichier stat.php à inclure par le fichier body.inc.html 
 * Depuis la version 2.2 l'inscription dans body.inc.html est automatique
*/

// Paramètres
$fichiers_json = './site/file/statislite/json/';
$filtres_primaires = './site/file/statislite/filtres_primaires/';

// Initialisation du tableau $_SESSION et $indice ou 
// Détection d'inactivité de plus de 10 minutes => nouvel indice
if(!isset($_SESSION['indice']) || Time() - $_SESSION['actif'] > 600 ){
	$_SESSION['indice'] = bin2hex(openssl_random_pseudo_bytes(16));
	$_SESSION['actif'] = Time();
	unset($_SESSION['filtrage']);
}
$indice = $_SESSION['indice'];

// Identification de l'utilisateur
$zwii_user_id = 'visiteur';
if(isset($_COOKIE['ZWII_USER_ID'])){
	 $zwii_user_id = $_COOKIE['ZWII_USER_ID'];
}
// Nouvel user_id suite à une connexion ou à une déconnexion => nouvel indice
if(isset($log[$indice]['user_id'])){
	if($zwii_user_id != $log[$indice]['user_id']){
		$_SESSION['indice'] = bin2hex(openssl_random_pseudo_bytes(16));
		$indice = $_SESSION['indice'];
		$_SESSION['actif'] = Time();
		unset($_SESSION['filtrage']);
	}
}



// Lecture de l'adresse IP
// Si internet partagé
if (isset($_SERVER['HTTP_CLIENT_IP'])) {
	$ip = $_SERVER['HTTP_CLIENT_IP'];
}
// Si derrière un proxy
elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
// Sinon : IP normale
else {
	if(isset($_SERVER['REMOTE_ADDR'])){
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	else{
		$ip = '';
	}
}

// Si filtre_primaire.json n'existe pas on le crée
if(!is_file($fichiers_json.'filtre_primaire.json')){
	$json = '{}';
	$fp= json_decode($json, true);
	$fp['robots'] = array( 'ua' => 0, 'ip'=> 0);
	$json = json_encode($fp);
	file_put_contents($fichiers_json.'filtre_primaire.json',$json);
}

// Filtrage des robots, test uniquement une fois en début de session
if(!isset($_SESSION['filtrage'])){

   // Filtrage par HTTP_USER_AGENT
   // liste_bot.txt à mettre à jour avec les données du site http://d1.a.free.fr/downloads.php
   $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
   $UAbot = 0;
   if(is_file($filtres_primaires.'liste_bot.txt')){
	   $regex = file_get_contents($filtres_primaires.'liste_bot.txt');
	   $UAbot=preg_match( $regex, $user_agent );
   }
   //UA vide c'est considéré comme un robot
   if ($user_agent == ""){
		$UAbot=1;
	}
	
   // Filtrage par vos IP uniquement (CNIL) 
   $IPbot = 0;
   if(is_file($filtres_primaires.'liste_vos_ip.txt')){
	   $regex = file_get_contents($filtres_primaires.'liste_vos_ip.txt');
	   $IPbot = preg_match( $regex, $ip );
   }

   $resultat = 0;
   // Formation du résultat 1, 0
   if ($UAbot==1 || $IPbot==1){
		$resultat=1;
		// comptage des 'robots' par type UA ou IP
		if($UAbot == 1){
			$type = 'ua';
			// Enregistrement dans le fichier robots.json
			if(is_file($fichiers_json.'robots.json')){
				$json = file_get_contents($fichiers_json.'robots.json');
			}
			else{
				$json = '{}';
			}
			$robots = json_decode($json, true);
			$robots[date('Y/m/d H:i:s')] = $_SERVER['HTTP_USER_AGENT'];
			// Limitation aux 200 derniers robots
			if( count($robots) > 200){
				foreach($robots as $key=>$value){
					unset($robots[$key]);
					break;
				}
			}
			$json = json_encode($robots);
			file_put_contents($fichiers_json.'robots.json',$json);
			
		}
		else{
			$type = 'ip';
		}
		$json = file_get_contents($fichiers_json.'filtre_primaire.json');
		$fp = json_decode($json, true);
		$fp['robots'][$type] = $fp['robots'][$type] + 1;
		$json = json_encode($fp);
		file_put_contents($fichiers_json.'filtre_primaire.json',$json);
   }

   $_SESSION['filtrage'] = $resultat;
}

// Filtrage par QUERY STRING
$QSbot = 0;
if(is_file($filtres_primaires.'liste_querystring.txt')){
   $regex = file_get_contents($filtres_primaires.'liste_querystring.txt');
   $QSbot=preg_match( $regex, $_SERVER['QUERY_STRING'] );
}

// Si c'est un vrai visiteur et que la page n'est pas exclue on enregistre date et query string
if($_SESSION['filtrage'] == 0 && $QSbot == 0){

	// Lecture et décodage du fichier json en cours
	if(is_file($fichiers_json.'sessionLog.json')){
		$json = file_get_contents($fichiers_json.'sessionLog.json');
		if(strlen($json) < 20 ){ 
			$json = '{}';
		}
	}
	else{
		$json = '{}';
	}
	$log = json_decode($json, true);
	
	//Initialisation si c'est un nouvel indice
	if(!isset($log[$indice])){
		$log[$indice] = array('ip' => $ip, 'user_id'=> $zwii_user_id, 'userAgent' => $_SERVER['HTTP_USER_AGENT'], 'langage' => $_SERVER['HTTP_ACCEPT_LANGUAGE'], 'referer' => $_SERVER['HTTP_REFERER'], 'vues' => array(), 'client' => array() );
	}
	// Ajout de la vue sous la forme date et page vue
	$indice2 = count($log[$indice]['vues']);
	if( $_SERVER['QUERY_STRING'] != ''){
		$log[$indice]['vues'][$indice2] = date('Y/m/d H:i:s').' * '.$_SERVER['QUERY_STRING'];
	}
	else{
		$log[$indice]['vues'][$indice2] = date('Y/m/d H:i:s').' * Page d\'accueil';
	}

	// Encodage et sauvegarde
	$json = json_encode($log);
	file_put_contents($fichiers_json.'sessionLog.json',$json);
}

// Vatiable de session utilisée pour détecter l'activité
$_SESSION['actif'] = Time();

?>