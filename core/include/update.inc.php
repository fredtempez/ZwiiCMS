<?php
/**
 * Mises à jour suivant les versions de Zwii
*/

// Version 9.0.0
if($this->getData(['core', 'dataVersion']) < 9000) {
	$this->deleteData(['theme', 'site', 'block']);
	if ($this->getData(['theme','menu','position']) === 'body-top') {
		$this->setData(['theme','menu','position','top']);
	}
	$this->setData(['theme', 'menu','fixed',false]);
	$this->setData(['core', 'dataVersion', 9000]);
	//$this->SaveData();
}
// Version 9.0.01
if($this->getData(['core', 'dataVersion']) < 9001) {
	$this->deleteData(['config', 'social', 'googleplusId']);
	$this->setData(['core', 'dataVersion', 9001]);
	//$this->SaveData();
}
// Version 9.0.08
if($this->getData(['core', 'dataVersion']) < 9008) {
	$this->setData(['theme', 'footer', 'textTransform','none']);
	$this->setData(['theme', 'footer', 'fontWeight','normal']);
	$this->setData(['theme', 'footer', 'fontSize','.8em']);
	$this->setData(['theme', 'footer', 'font','Open+Sans']);
	$this->setData(['core', 'dataVersion', 9008]);
	//$this->SaveData();
}
// Version 9.0.09
if($this->getData(['core', 'dataVersion']) < 9009) {
	$this->setData(['core', 'dataVersion', 9009]);
	//$this->SaveData();
}
// Version 9.0.10
if($this->getData(['core', 'dataVersion']) < 9010) {
	$this->deleteData(['config', 'social', 'googleplusId']);
	$this->setData(['core', 'dataVersion', 9010]);
	//$this->SaveData();
}
// Version 9.0.11
if($this->getData(['core', 'dataVersion']) < 9011) {
	if ($this->getData(['theme','menu','position']) === 'body')
		$this->setData(['theme','menu','position','site']);
	$this->setData(['core', 'dataVersion', 9011]);
	//$this->SaveData();
}
// Version 9.0.17
if($this->getData(['core', 'dataVersion']) < 9017) {
	$this->setData(['theme','footer','displayVersion', true ]);
	$this->setData(['core', 'dataVersion', 9017]);
	//$this->SaveData();
}
// Version 9.1.0
if($this->getData(['core', 'dataVersion']) < 9100) {
	$this->setData(['theme','footer','displayVersion', true ]);
	$this->setData(['theme','footer','displaySiteMap', true ]);
	$this->setData(['theme','footer','displayCopyright', false ]);
	$this->setData(['core', 'dataVersion', 9100]);
	//$this->SaveData();
}
// Version 9.2.00
if($this->getData(['core', 'dataVersion']) < 9200) {
	$this->setData(['theme','footer','template', 3 ]);
	$this->setData(['theme','footer','margin', true ]);
	$this->setData(['theme','footer','displayLegal', !empty($this->getdata(['config','legalPageId'])) ]);
	$this->setData(['theme','footer','displaySearch', false ]);
	$this->setData(['config','social','githubId', '' ]);
	$this->setData(['core', 'dataVersion', 9200]);
	//$this->SaveData();
}
// Version 9.2.05
if($this->getData(['core', 'dataVersion']) < 9205) {
	// Nettoyage Swiper
	if (file_exists('core/vendor/tinymce/templates/swiper.html')) {
		unlink ('core/vendor/tinymce/templates/swiper.html');
	}
	if (is_dir('core/vendor/swiper')) {
		$dir = getcwd();
		chdir('core/vendor/swiper');
		$files = glob('*');
		foreach($files as $file) unlink($file);
		chdir($dir);
		rmdir ('core/vendor/swiper/');
	}
	$this->setData(['core', 'dataVersion', 9205]);
	//$this->SaveData();
}
// Version 9.2.10
if($this->getData(['core', 'dataVersion']) < 9210) {

	// Utile pour l'installation d'un backup sur un autre serveur
	//$this->setData(['core', 'baseUrl', helper::baseUrl(false,false) ]);

	// Suppression d'une option de hauteur de la bannière
	if ($this->getData(['theme', 'header','height']) === 'none') {
		$this->setData(['theme', 'header','height','150px']);
	}
	// Changer le nom de la clé linkHome -> linkHomePage
	$this->setdata(['theme','header','linkHomePage',$this->getData(['theme','header','linkHome'])]);
	$this->deleteData(['theme','header','linkHome']);

	// Préparation des clés de légendes pour la v10
	// Construire une liste plate de parents et d'enfants

	$pageList = array();

	foreach ($this->getHierarchy(null,null,null) as $parentKey=>$parentValue) {
		$pageList [] = $parentKey;
		foreach ($parentValue as $childKey) {
			$pageList [] = $childKey;
		}
	}
	// Parcourir toutes les pages
	foreach ($pageList as $parentKey => $parent) {
		//La page a une galerie
		if ($this->getData(['page',$parent,'moduleId']) === 'gallery' ) {
			// Lire les données du module
			// Parcourir les dossiers de la galerie
			$tempData =  $this->getData(['module', $parent]);
			foreach ($tempData as $galleryKey => $galleryItem) {
				foreach ($galleryItem as $legendKey => $legendValue) {
					// Recherche la clé des légendes
					if ($legendKey === 'legend') {
						foreach ($legendValue as $itemKey=>$itemValue) {
							// Ancien nom avec un point devant l'extension ?
							if (strpos($itemKey,'.') > 0) {
								// Créer une nouvelle clé
								$this->setData(['module', $parent, $galleryKey, 'legend',str_replace('.','',$itemKey),$itemValue]);
								// Supprimer la valeur
								$this->deleteData(['module', $parent, $galleryKey, 'legend',$itemKey]);
							}
						}
					}
				}
			}
		}
	}
	$this->setData(['core', 'dataVersion', 9210]);
}
// Version 9.2.11
if($this->getData(['core', 'dataVersion']) < 9211) {
	$autoUpdate= mktime(0, 0, 0);
	$this->setData(['core', 'lastAutoUpdate', $autoUpdate]);
	$this->setData(['config','autoUpdate', true]);
	$this->setData(['core', 'dataVersion', 9211]);
}
// Version 9.2.12
if($this->getData(['core', 'dataVersion']) < 9212) {
	$this->setData(['theme','menu', 'activeColorAuto',true]);
	$this->setData(['theme','menu', 'activeColor','rgba(255, 255, 255, 1)']);
	$this->setData(['core', 'dataVersion', 9212]);
	//$this->SaveData();
}
// Version 9.2.15
if($this->getData(['core', 'dataVersion']) < 9215) {
	// Données de la barre de langue dans le menu
	$this->setData(['theme','menu','burgerTitle',true]);
	$this->setData(['core', 'dataVersion', 9215]);
}
// Version 9.2.16
if($this->getData(['core', 'dataVersion']) < 9216) {
	// Utile pour l'installation d'un backup sur un autre serveur
	// mais avec la réécriture d'URM
	$this->setData(['core', 'baseUrl', helper::baseUrl(true,false) ]);
	$this->setData(['core', 'dataVersion', 9216]);
}
// Version 9.2.21
if($this->getData(['core', 'dataVersion']) < 9221) {
	// Utile pour l'installation d'un backup sur un autre serveur
	// mais avec la réécriture d'URM
	$this->setData(['theme', 'body', 'toTopbackgroundColor', 'rgba(33, 34, 35, .8)' ]);
	$this->setData(['theme', 'body', 'toTopColor', 'rgba(255, 255, 255, 1)' ]);
	$this->setData(['core', 'dataVersion', 9221]);
}
// Version 9.2.23
if($this->getData(['core', 'dataVersion']) < 9223) {
	// Utile pour l'installation d'un backup sur un autre serveur
	// mais avec la réécriture d'URM
	$this->setData(['config', 'proxyUrl', '' ]);
	$this->setData(['config', 'proxyPort', '' ]);
	$this->setData(['config', 'proxyType', 'tcp://' ]);
	$this->setData(['core', 'dataVersion', 9223]);
}
// Version 9.2.27
if($this->getData(['core', 'dataVersion']) < 9227) {
	// Forcer la régénération du thème
	if (file_exists(self::DATA_DIR.'theme.css')) {
		unlink (self::DATA_DIR.'theme.css');
	}
	$this->setData(['core', 'dataVersion', 9227]);
}
// Version 10.0.00
if($this->getData(['core', 'dataVersion']) < 10000) {
	$this->setData(['config', 'faviconDark','faviconDark.ico']);

	//----------------------------------------
	// Mettre à jour les données des galeries
	$pageList = array();
	foreach ($this->getHierarchy(null,null,null) as $parentKey=>$parentValue) {
		$pageList [] = $parentKey;
		foreach ($parentValue as $childKey) {
			$pageList [] = $childKey;
		}
	}
	// Mise à jour des données pour la galerie v2
	foreach ($pageList as $parentKey => $parent) {
		//La page a une galerie
		if ($this->getData(['page',$parent,'moduleId']) === 'gallery' ) {
			// Parcourir les dossiers de la galerie
			$tempData =  $this->getData(['module', $parent]);
			$i = 1;
			foreach ($tempData as $galleryKey => $galleryItem) {
				// Ordre de tri des galeries
				if ( $this->getdata(['module',$parent,$galleryKey,'config','sort']) === NULL)  {
					$this->setdata(['module',$parent,$galleryKey,'config','sort','SORT_ASC']);
				}
				// Position de la galerie, tri manuel
				if ( $this->getdata(['module',$parent,$galleryKey,'config','position']) === NULL) {
					$this->setdata(['module',$parent,$galleryKey,'config','position',$i++]);
				}
				// Positions des images, tri manuel
				if ( $this->getdata(['module',$parent,$galleryKey,'positions']) === NULL) {
					$c = count($this->getdata(['module',$parent,$galleryKey,'legend']));
					$this->setdata(['module',$parent,$galleryKey,'positions', range(0,$c-1) ]);
				}
				// Image de couverture
				if ( $this->getdata(['module',$parent,$galleryKey,'config','homePicture']) === NULL)  {
					if (is_dir($this->getdata(['module',$parent,$galleryKey,'config','directory']))) {
						$iterator = new DirectoryIterator($this->getdata(['module',$parent,$galleryKey,'config','directory']));
						foreach($iterator as $fileInfos) {
							if($fileInfos->isDot() === false AND $fileInfos->isFile() AND @getimagesize($fileInfos->getPathname())) {
								$this->setdata(['module',$parent,$galleryKey,'config','homePicture',$fileInfos->getFilename()]);
								break;
							}
						}
					}
				}
			}
		}
	}
	// Contrôle des options php.ini pour la mise à jour auto
	if (helper::getUrlContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/version') ===  false) {
		$this->setData(['config','autoUpdate',false]);
	}

	$this->setData(['core', 'dataVersion', 10000]);
}
// Version 10.0.92
if ($this->getData(['core', 'dataVersion']) < 10092) {
	// Suppression du dossier fullpage
	if (is_dir('core/vendor/fullpage')) {
		$dir = getcwd();
		chdir('core/vendor/fullpage');
		$files = glob('*');
		foreach($files as $file) unlink($file);
		chdir($dir);
		rmdir ('core/vendor/fullpage/');
	}
	if (file_exists('core/vendor/tinymce/templates/fullPageSections.html')) {
		unlink ('core/vendor/tinymce/templates/fullPageSections.html'); }
	if (file_exists('core/vendor/tinymce/templates/fullPageSlides.html')) {
		unlink ('core/vendor/tinymce/templates/fullPageSlides.html'); }
	$this->setData(['core', 'dataVersion', 10092]);
}
// Version 10.0.93
if ($this->getData(['core', 'dataVersion']) < 10093) {
	// Déplacement du fichier admin.css dans data
	if (file_exists('core/layout/admin.css')) {
		copy('core/layout/admin.css',self::DATA_DIR.'admin.css');
		unlink('core/layout/admin.css');
	}
	//Déplacement d'un fichier de ressources
	if (file_exists('core/module/config/ressource/.htaccess'))	{
		unlink('core/module/config/ressource/.htaccess');
		rmdir ('core/module/config/ressource');
	}
	$this->setData(['core', 'dataVersion', 10093]);
	// Réorganisation du thème
	$this->setData(['theme','text','linkTextColor',$this->getData(['theme','link', 'textColor'])]);
}
// Version 10.1.04
if ($this->getData(['core', 'dataVersion']) < 10104) {
	$this->setData(['theme','text','linkColor','rgba(74, 105, 189, 1)']);
	$this->deleteData(['theme','text','linkTextColor']);
	$this->setdata(['theme','block','backgroundColor','rgba(236, 239, 241, 1)']);
	$this->setdata(['theme','block','borderColor','rgba(236, 239, 241, 1)']);
	$this->setdata(['theme','menu','radius','0px']);
	$this->setData(['core', 'dataVersion', 10104]);
}
// Version 10.2.00
if ($this->getData(['core', 'dataVersion']) < 10200) {
	// Paramètres du compte connecté
	if ($this->getUser('id')) {
		$this->setData(['user', $this->getUser('id'), 'connectFail',0]);
		$this->setData(['user', $this->getUser('id'), 'connectTimeout',0]);
		$this->setData(['user', $this->getUser('id'), 'accessTimer',0]);
		$this->setData(['user', $this->getUser('id'), 'accessUrl','']);
		$this->setData(['user', $this->getUser('id'), 'accessCsrf',$_SESSION['csrf']]);
	}
	// Paramètres de sécurité
	$this->setData(['config', 'connect', 'attempt',999]);
	$this->setData(['config', 'connect', 'timeout',0]);
	$this->setData(['config', 'connect', 'log',false]);
	// Thème
	$this->deleteData(['admin','colorButtonText']);
	// Remettre à zéro le thème pour la génération du CSS du blog
	if (file_exists(self::DATA_DIR . 'theme.css')) {
		unlink(self::DATA_DIR . 'theme.css');
	}
	// Créer les en-têtes du journal
	$d = 'Date;Heure;IP;Id;Action' . PHP_EOL;
	file_put_contents(self::DATA_DIR . 'journal.log',$d);
	// Init préservation htaccess
	$this->setData(['config','autoUpdateHtaccess',false]);
	// Options de barre de membre simple
	$this->setData(['theme','menu','memberBar',true]);

	// Thème Menu : couleur de page active non définie
	if (!$this->getData(['theme','menu','activeTextColor']) ) {
		$this->setData(['theme','menu','activeTextColor', $this->getData(['theme','menu','textColor']) ]);
	}
	$this->setData(['core', 'updateAvailable', false]);
	$this->setData(['core', 'dataVersion', 10200]);
}
// Version 10.2.01
if ($this->getData(['core', 'dataVersion']) < 10201) {
	// Options de barre de membre simple
	$this->setData(['theme','footer','displayMemberBar',false]);
	$this->deleteData(['theme','footer','displayMemberAccount']);
	$this->deleteData(['theme','footer','displayMemberLogout']);
	$this->setData(['core', 'dataVersion', 10201]);
}
// Version 10.3.00
if ($this->getData(['core', 'dataVersion']) < 10300) {
	// Options de barre de membre simple
	$this->setData(['config','page404','none']);
	$this->setData(['config','page403','none']);
	$this->setData(['config','page302','none']);
	// Module de recherche
	// Suppression du dossier search
	if (is_dir('core/module/search')) {
		$dir = getcwd();
		chdir('core/module/search');
		$files = glob('*');
		foreach($files as $file) unlink($file);
		chdir($dir);
		rmdir ('core/module/search/');
	}
	// Désactivation de l'option dans le pied de page
	$this->setData(['theme','footer','displaySearch',false]);
	// Inscription des nouvelles variables
	$this->setData(['config','searchPageId','']);

	// Mettre à jour les données des galeries
	$pageList = array();
	foreach ($this->getHierarchy(null,null,null) as $parentKey=>$parentValue) {
		$pageList [] = $parentKey;
		foreach ($parentValue as $childKey) {
			$pageList [] = $childKey;
		}
	}
	// Mise à jour des données de thème de la galerie
	// Les données de thème sont communes au site
	foreach ($pageList as $parentKey => $parent) {
		//La page a une galerie
		if ($this->getData(['page',$parent,'moduleId']) === 'gallery' ) {
			foreach ( $this->getData(['module', $parent]) as $galleryKey => $galleryItem) {
				// Transfert du theme dans une structure unique
				if ( is_array($this->getdata(['theme',$parent])) )  {
					$this->setdata(['theme','gallery',$this->getdata(['theme',$parent])]);
				}
			}
			$this->deleteData(['theme',$parent]);
		}
	}

	// Mise à jour du numéro de version
	$this->setData(['core', 'dataVersion', 10300]);
}
// Version 10.3.01
if ($this->getData(['core', 'dataVersion']) < 10301) {
	// Inscription des nouvelles variables
	if ($this->getData(['config','searchPageId']) === '') {
		$this->setData(['config','searchPageId','none']);
	}
	if ($this->getData(['config','legalPageId']) === '') {
		$this->setData(['config','legalPageId','none']);
	}
	$this->setData(['core', 'dataVersion', 10301]);
}
// Version 10.3.02
if ($this->getData(['core', 'dataVersion']) < 10302) {
	// Activation par défaut du captcha à la connexion
	$this->setData(['config', 'connect','captcha', true]);
	$this->setData(['core', 'dataVersion', 10302]);
}
// Version 10.3.03
if ($this->getData(['core', 'dataVersion']) < 10303) {
	// Activation par défaut du captcha à la connexion
	$this->setData(['config', 'captchaStrong', false]);
	$this->setData(['core', 'dataVersion', 10303]);
}
// Version 10.3.04
if ($this->getData(['core', 'dataVersion']) < 10304) {
	// Couleur des sous menus
	$this->setData(['theme', 'menu', 'backgroundColorSub', $this->getData(['theme', 'menu', 'backgroundColor']) ]);
	// Nettoyage du fichier de thème pour forcer une régénération
	if (file_exists(self::DATA_DIR . '/theme.css')) { // On ne sait jamais
		unlink (self::DATA_DIR . '/theme.css');
	}
	$this->setData(['core', 'dataVersion', 10304]);
}
// Version 10.3.06
if ($this->getData(['core', 'dataVersion']) < 10306) {
	// Liste des pages
	$pageList = array();
	foreach ($this->getHierarchy(null,null,null) as $parentKey=>$parentValue) {
		$pageList [] = $parentKey;
		foreach ($parentValue as $childKey) {
			$pageList [] = $childKey;
		}
	}
	// Mettre à jour les données des blogs les articles sont dans posts
	foreach ($pageList as $parentKey => $parent) {
		//La page a un blog
		if ($this->getData(['page',$parent,'moduleId']) === 'blog' ) {
			if (is_array($this->getData(['module', $parent]))) {
				foreach ( $this->getData(['module', $parent]) as $blogKey => $blogItem) {
					if ($blogKey === 'posts' OR $blogKey === 'config') {continue;}
					$data = $this->getdata(['module',$parent,$blogKey]);
					$this->deleteData(['module',$parent, $blogKey]);
					$this->setData([ 'module', $parent, 'posts', $blogKey, $data ]);
				}
			}
		}
	}
	foreach ($pageList as $parentKey => $parent) {
		//La page a une news
		if ($this->getData(['page',$parent,'moduleId']) === 'news' ) {
			if (is_array($this->getData(['module', $parent]))) {
				foreach ( $this->getData(['module', $parent]) as $newsKey => $newsItem) {
					if ($blogKey === 'posts' OR $blogKey === 'config') {continue;}
					$data = $this->getdata(['module',$parent,$newsKey]);
					$this->deleteData(['module',$parent, $newsKey]);
					$this->setData([ 'module', $parent, 'posts', $newsKey, $data ]);
				}
			}
		}
	}
	$this->setData(['core', 'dataVersion', 10306]);
}

// Version 10.3.08
if ($this->getData(['core', 'dataVersion']) < 10308) {
	// RAZ la mise à jour auto bug 10.3.07
	$this->setData(['core','updateAvailable', false]);
$this->setData(['core', 'dataVersion', 10308]);
}

// Version 10.4.00
if ($this->getData(['core', 'dataVersion']) < 10400) {
	// Ajouter le prénom comme pseudo et le pseudo comme signature
	foreach($this->getData(['user']) as $userId => $userIds){
		$this->setData(['user',$userId,'pseudo',$this->getData(['user',$userId,'firstname'])]);
		$this->setData(['user',$userId,'signature',2]);
	}

	// Ajouter les champs de blog v3
	// Liste des pages dans pageList
	$pageList = array();
	foreach ($this->getHierarchy(null,null,null) as $parentKey=>$parentValue) {
		$pageList [] = $parentKey;
		foreach ($parentValue as $childKey) {
			$pageList [] = $childKey;
		}
	}
	// Parcourir pageList et rechercher les modules de blog

	foreach ($pageList as $parentKey => $parent) {
		//La page est un blog
		if ($this->getData(['page',$parent,'moduleId']) === 'blog' ) {
			$articleIds = array_keys(helper::arrayCollumn($this->getData(['module', $parent, 'posts']), 'publishedOn', 'SORT_DESC'));
			foreach ($articleIds as $key => $article) {
				// Droits les deux groupes
				$this->setData(['module',  $parent, 'posts', $article,'editConsent', 3]);
				// Limite de taille 500
				$this->setData(['module',  $parent, 'posts', $article,'commentMaxlength', '500']);
				// Pas d'approbation des commentaires
				$this->setData(['module',  $parent, 'posts', $article,'commentApproved', false ]);
				// pas de notification
				$this->setData(['module',  $parent, 'posts', $article,'commentNotification', false ]);
				// groupe de notification
				$this->setData(['module',  $parent, 'posts', $article,'commentGroupNotification', 3 ]);
			}

			// Traitement des commentaires
			if ( is_array($this->getData(['module',  $parent, 'posts', $article,'comment'])) ) {
				foreach($this->getData(['module',  $parent, 'posts', $article,'comment']) as $commentId => $comment) {
					// Approbation
					$this->setData(['module',  $parent, 'posts', $article,'comment', $commentId, 'approval', true ]);
				}
			}
		}
	}

	// Création du fichier locale.json
	$this->setData(['locale','homePageId',$this->getData(['config','homePageId'])]);
	$this->setData(['locale','page404',$this->getData(['config','page404'])]);
	$this->setData(['locale','page403',$this->getData(['config','page403'])]);
	$this->setData(['locale','page302',$this->getData(['config','page302'])]);
	$this->setData(['locale','legalPageId',$this->getData(['config','legalPageId'])]);
	$this->setData(['locale','searchPageId',$this->getData(['config','searchPageId'])]);
	$this->setData(['locale','metaDescription',$this->getData(['config','metaDescription'])]);
	$this->setData(['locale','title',$this->getData(['config','title'])]);

	// Renommer les fichier de backup
	if ($this->getInput('configAdvancedFileBackup', helper::FILTER_BOOLEAN) === false) {
		$path = realpath('site/data');
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename)
		{
			if (strpos($filename,'back.json')) {
				rename($filename, str_replace('back.json','backup.json',$filename));
			}
		}
	}

	// Supprimer les fichiers CSS devenus inutiles du module search
	if (file_exists('module/search/ressource/theme.css') )
		unlink('module/search/ressource/theme.css');
	if (file_exists('module/search/ressource/vartheme.css') )
		unlink('module/search/ressource/vartheme.css');
	$this->deleteData(['theme','search','keywordColor']);

	// Nettoyer les modules avec des données null

	$modules = $this->getData(['module']);
	foreach($modules as $key => $value) {
		if (is_null($value) ) {
			unset($modules[$key]);
		}
	}
	$this->setData (['module',$modules]);

	$this->setData(['core', 'dataVersion', 10400]);

}

// Version 10.5.02
if ($this->getData(['core', 'dataVersion']) < 10502) {
	// Forcer la régénération du thème
	if (file_exists(self::DATA_DIR.'theme.css')) {
		unlink (self::DATA_DIR.'theme.css');
	}
	$this->setData(['core', 'dataVersion', 10502]);
}

// Version 10.6.00
if ($this->getData(['core', 'dataVersion']) < 10600) {

	// Mise à jour des données des modules autonomes

	// Liste des pages dans pageList
	$pageList = array();
	foreach ($this->getHierarchy(null,null,null) as $parentKey=>$parentValue) {
		$pageList [] = $parentKey;
		foreach ($parentValue as $childKey) {
			$pageList [] = $childKey;
		}
	}
	// Parcourir pageList et rechercher les modules au CSS autonomes
	foreach ($pageList as $parentKey => $parent) {
		if (
		 $this->getData(['page',$parent,'moduleId']) === 'search'
		 || $this->getData(['page',$parent,'moduleId']) === 'gallery'
		 || $this->getData(['page',$parent,'moduleId']) === 'news'
		){
			if(class_exists($parent)) {
				$module = new $moduleId;
				$module->update($parent);
			}
		}
	}
// Suppression de l'option d'objets par page gérées par les modules
$this->deleteData(['config','itemsperPage']);

$this->setData(['core', 'dataVersion', 10600]);
}

// Version 11.0.00
if ($this->getData(['core', 'dataVersion']) < 11000) {

	// Option de déconnexion auto activée
	$this->setData(['config','autoDisconnect',true]);

	// Mettre à jour les données de langue
	$this->setData(['config', 'i18n', 'enable', true ]);
	$this->setData(['config', 'i18n','scriptGoogle', false ]);
	$this->setData(['config', 'i18n','showCredits', false ]);
	$this->setData(['config', 'i18n','autoDetect', false ]);
	$this->setData(['config', 'i18n','admin', false ]);
	$this->setData(['config', 'i18n','fr', 'none' ]);
	$this->setData(['config', 'i18n','de', 'none' ]);
	$this->setData(['config', 'i18n','en', 'none' ]);
	$this->setData(['config', 'i18n','es', 'none' ]);
	$this->setData(['config', 'i18n','it', 'none' ]);
	$this->setData(['config', 'i18n','nl', 'none' ]);
	$this->setData(['config', 'i18n','pt', 'none' ]);

	// Supprimer les fichiers de backup
	if (file_exists('site/data/.backup')) unlink('site/data/.backup');
	$path = realpath('site/data');
	foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename)
	{
		if (strpos($filename,'backup.json')) {
			unlink($filename);
		}
	}

	// Externaliser les contenus des pages
	// Liste des pages dans pageList
	$pageList = array();
	// Creation du contenu de la page
	if (!is_dir(self::DATA_DIR . self::$i18n . '/content')) {
		mkdir(self::DATA_DIR . self::$i18n . '/content', 0755);
	}
	foreach ($this->getHierarchy(null,null,null) as $parentKey=>$parentValue) {
		$pageList [] = $parentKey;
		foreach ($parentValue as $childKey) {
			$pageList [] = $childKey;
		}
	}
	foreach ($pageList as $parentKey => $parent) {
		$content = $this->getData(['page', $parent, 'content']);
		//file_put_contents(self::DATA_DIR . self::$i18n . '/content/' . $parent . '.html', $content);
		$this->setPage($parent, $content, 'fr');
		$this->setData(['page', $parent, 'content', $parent . '.html']);
	}

	// Référencement
	$this->setData(['config','seo','robots',true]);

	$this->setData(['core', 'dataVersion', 11000]);
}

// Version 11.0.10
if ($this->getData(['core', 'dataVersion']) < 11010) {

	// Renommer une variable
	$data = $this->getData(['config', 'i18n', 'active']);
	$this->deleteData(['config', 'i18n', 'active']);
	$this->setData(['config', 'i18n', 'enable', $data ]);

	$this->setData(['core', 'dataVersion', 11010]);
}

// Version 11.1.00
if ($this->getData(['core', 'dataVersion']) < 11100) {

	// Anonymat des adresses iP de la journalisation
	$this->setData(['config', 'connect', 'anonymousIp', 2 ]);

	// Nouvelles options de contenu pour les écrans réduits
	if ($this->getData(['theme', 'menu', 'burgerTitle'])) {
		$this->setData(['theme', 'menu', 'burgerContent', 'title']);
	} else {
		$this->setData(['theme', 'menu', 'burgerContent', 'none']);
	}
	$this->setData(['theme', 'menu', 'burgerLogo', '']);

	$this->setData(['core', 'dataVersion', 11100]);
}

// Version 11.2.00
if ($this->getData(['core', 'dataVersion']) < 11200) {

	// Mise àjour des données de config
	$this->setData(['config', 'connect', 'captchaStrong', $this->getData(['config', 'captchaStrong'])]);
	$this->deleteData(['config', 'captchaStrong']);
	$this->setData(['config', 'connect', 'autoDisconnect', $this->getData(['config', 'autoDisconnect'])]);
	$this->deleteData(['config', 'autoDisconnect']);
	$this->setData(['config', 'connect', 'captchaType', 'alpha']);

	// Ajout de la variable shortTitle basée sur Title
	foreach ($this->getHierarchy(null,null,null) as $parentKey=>$parentValue) {
		$pageList [] = $parentKey;
		foreach ($parentValue as $childKey) {
			$pageList [] = $childKey;
		}
	}
	foreach ($pageList as $parentKey => $parent) {
		$this->setData(['page', $parent, 'shortTitle', $this->getData(['page', $parent, 'title']) ]);
	}

	// Incorporer les nouveaux champs du header et du menu
	$this->setData(['theme', 'header', 'feature', 'wallpaper']);
	$this->setData(['theme', 'header', 'featureContent', '<p>Bannière vide</p>']);
	$this->setData(['theme', 'header', 'container', 'container']);
	$this->setData(['theme', 'menu', 'container', 'container']);
	// Option des cookies dans le footer
	$this->setData(['theme', 'footer', 'displayCookie', false]);

	// Acceptation et paramétres des cookies RGPD
	$this->setData(['locale', 'cookies', 'cookiesZwiiText', 'Ce site utilise des cookies nécessaires à son fonctionnement, ils permettent de fluidifier son fonctionnement par exemple en mémorisant les données de connexion, la langue que vous avez choisie ou la validation de ce message.']);
	$this->setData(['locale', 'cookies', 'cookiesGaText', 'Il utilise également des cookies permettant de réaliser des statistiques de visites pour améliorer votre expérience utilisateur, ces cookies déposés par Google Analytics ont besoin de votre consentement.']);
	$this->setData(['locale', 'cookies', 'cookiesTitleText', 'Gérer les cookies']);
	$this->setData(['locale', 'cookies', 'cookiesLinkMlText', 'Consulter les mentions légales']);
	$this->setData(['locale', 'cookies', 'cookiesCheckboxGaText', 'Autorisation des cookies Google Analytics']);
	$this->setData(['locale', 'cookies', 'cookiesButtonText', 'J\'ai compris']);

	// Supppression de l'option de traduction en mode connecté
	$this->setData(['config','i18n', 'admin', false]);

	// Option de dévoilement du mdp
	$this->setData(['config', 'connect', 'showPassword', true]);

	// Mise à jour
	$this->setData(['core', 'dataVersion', 11200]);
}

// Version 11.2.02
if ($this->getData(['core', 'dataVersion']) < 11202) {

	// Renommer les champs
	$this->setData(['locale', 'cookies', 'mainLabel', 		$this->getData(['locale', 'cookies', 'cookiesZwiiText']) ]);
	$this->setData(['locale', 'cookies', 'gaLabel', 		$this->getData(['locale', 'cookies', 'cookiesGaText']) ]);
	$this->setData(['locale', 'cookies', 'titleLabel', 		$this->getData(['locale', 'cookies', 'cookiesTitleText']) ]);
	$this->setData(['locale', 'cookies', 'linkLegalLabel', 	$this->getData(['locale', 'cookies', 'cookiesLinkMlText']) ]);
	$this->setData(['locale', 'cookies', 'checkboxGaLabel', $this->getData(['locale', 'cookies', 'cookiesCheckboxGaText']) ]);
	$this->setData(['locale', 'cookies', 'buttonValidLabel',$this->getData(['locale', 'cookies', 'cookiesButtonText']) ]);
	// Effacer les anciens champs
	$this->deleteData(['locale', 'cookies', 'cookiesZwiiText']);
	$this->deleteData(['locale', 'cookies', 'cookiesGaText']);
	$this->deleteData(['locale', 'cookies', 'cookiesTitleText']);
	$this->deleteData(['locale', 'cookies', 'cookiesLinkMlText']);
	$this->deleteData(['locale', 'cookies', 'cookiesCheckboxGaText']);
	$this->deleteData(['locale', 'cookies', 'cookiesButtonText']);

	// Mise à jour
	$this->setData(['core', 'dataVersion', 11202]);
}

// Version 11.3.00
if ($this->getData(['core', 'dataVersion']) < 11300) {

	// tableau de substitution
	$fonts = [
		'Abril+Fatface' => 'abril-fatface',
		'Arimo' => 'arimo',
		'Arvo' => 'arvo',
		'Berkshire+Swash' => 'berkshire-swash',
		'Cabin' => 'genera',
		'Dancing+Script' => 'dancing-script',
		'Droid+Sans' => 'droid-sans-2',
		'Droid+Serif' => 'droid-serif-2',
		'Fira+Sans' => 'fira-sans',
		'Inconsolata' => 'inconsolata-2',
		'Indie+Flower' =>'indie-flower',
		'Josefin+Slab' => 'josefin-sans-std',
		'Lobster' => 'lobster-2',
		'Lora' => 'lora',
		'Lato' =>'lato',
		'Marvel' => 'montserrat-ace',
		'Old+Standard+TT' => 'old-standard-tt-3',
		'Open+Sans' =>'open-sans',
			// Corriger l'erreur de nom de police installée par défaut, il manquait un O en majuscule
		'open+Sans' =>'open-sans',
		'Oswald' =>'oswald-4',
		'PT+Mono' => 'pt-mono',
		'PT+Serif' =>'pt-serif',
		'Raleway' => 'raleway-5',
		'Rancho' => 'rancho',
		'Roboto' => 'Roboto',
		'Signika' => 'signika',
		'Ubuntu' => 'ubuntu',
		'Vollkorn' => 'vollkorn'
	];

	$this->setData(['theme', 'footer', 'font', $fonts[$this->getData (['theme', 'footer', 'font']) ] ]);
	$this->setData(['theme', 'header', 'font', $fonts[$this->getData (['theme', 'header', 'font' ]) ] ]);
	$this->setData(['theme', 'menu', 'font',   $fonts[$this->getData (['theme', 'menu', 'font' ]) ] ]);
	$this->setData(['theme', 'text', 'font',   $fonts[$this->getData (['theme', 'text', 'font' ]) ] ]);
	$this->setData(['theme', 'title', 'font',  $fonts[ $this->getData (['theme', 'title', 'font' ]) ] ]);
	$this->setData(['admin', 'fontTitle',  $fonts[ $this->getData (['admin', 'fontTitle' ]) ] ]);
	$this->setData(['admin', 'fontText',   $fonts[$this->getData (['admin','fontText' ]) ] ]);

	unlink(self::DATA_DIR . 'admin.css');
	unlink(self::DATA_DIR . 'theme.css');

	// Mise à jour
	$this->setData(['core', 'dataVersion', 11300]);
}
// Version 11.3.03
if ($this->getData(['core', 'dataVersion']) < 11303) {

	// Ajout de la variable shortTitle basée sur Title
	foreach ($this->getHierarchy(null,null,null) as $parentKey=>$parentValue) {
		$pageList [] = $parentKey;
		foreach ($parentValue as $childKey) {
			$pageList [] = $childKey;
		}
	}
	foreach ($pageList as $parentKey => $parent) {
		$this->setData(['page', $parent, 'extraPosition', false]);
	}

	// Mise à jour
	$this->setData(['core', 'dataVersion', 11303]);
}

// Version 11.4.00
if ($this->getData(['core', 'dataVersion']) < 11400) {

	$fonts = [
		'arimo' => 'Arimo',
		'arvo' => 'Arvo',
		'dancing-script' => 'Dancing Script',
		'droid-sans-2' => 'Droid Sans',
		'droid-serif-2' => 'Droid Serif',
		'indie-flower' => 'Indie Flower',
		'liberation-sans' => 'Liberation Sans',
		'liberation-serif' => 'Liberation Serif',
		'lobster-2' => 'Lobster',
		'lora' => 'Lora',
		'lato' => 'Lato',
		'old-standard-tt-3' => 'Old Standard TT',
		'open-sans' => 'Open Sans',
		'oswald-4' => 'Oswald',
		'pt-mono' => 'PT Mono',
		'pt-serif' => 'PT Serif',
		'rancho' => 'Rancho',
		'roboto' => 'Roboto',
		'ubuntu' => 'Ubuntu',
		'vollkorn' => 'Vollkorn'
	];
	$this->setData(['fonts', 'imported', $fonts]);

	// Mise à jour
	$this->setData(['core', 'dataVersion', 11400]);
}
