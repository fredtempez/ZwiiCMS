<?php
	//Mise en tableau des fichiers du répertoire qui contient les images
	//Début		
	$dir = $_REQUEST['Rep'];
	$ListFilesIMG= array();
	if (file_exists($dir)){
		$ListFilesIMG = array_diff(scandir($dir), array('.', '..'));
	}
	//Fin
	echo implode(";",$ListFilesIMG);
?>
