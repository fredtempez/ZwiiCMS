<?php

/**
 * Mises à jour suivant les versions de Zwii
 */

// Pas d'installation depuis une version inférieur
if (
	$this->getData(['core', 'dataVersion']) < 9227
) {
	// Arrêt du script
	exit('ZwiiCMS version 12 est incompatible avec la base de données installée. L\'installation d\'une version intermédiaire 10 ou 11 est nécessaire.');
}

// Version 10.0.00
if ($this->getData(['core', 'dataVersion']) < 10000) {
	$this->setData(['config', 'faviconDark', 'faviconDark.ico']);

	//----------------------------------------
	// Mettre à jour les données des galeries
	$hierarchy = array();
	foreach ($this->getHierarchy() as $parentKey => $parentValue) {
		$hierarchy[] = $parentKey;
		foreach ($parentValue as $childKey) {
			$hierarchy[] = $childKey;
		}
	}
	// Mise à jour des données pour la galerie v2
	foreach ($hierarchy as $parentKey => $parent) {
		//La page a une galerie
		if ($this->getData(['page', $parent, 'moduleId']) === 'gallery') {
			// Parcourir les dossiers de la galerie
			$tempData = $this->getData(['module', $parent]);
			$i = 1;
			foreach ($tempData as $galleryKey => $galleryItem) {
				// Ordre de tri des galeries
				if ($this->getdata(['module', $parent, $galleryKey, 'config', 'sort']) === NULL) {
					$this->setdata(['module', $parent, $galleryKey, 'config', 'sort', 'SORT_ASC']);
				}
				// Position de la galerie, tri manuel
				if ($this->getdata(['module', $parent, $galleryKey, 'config', 'position']) === NULL) {
					$this->setdata(['module', $parent, $galleryKey, 'config', 'position', $i++]);
				}
				// Positions des images, tri manuel
				if ($this->getdata(['module', $parent, $galleryKey, 'positions']) === NULL) {
					$c = count($this->getdata(['module', $parent, $galleryKey, 'legend']));
					$this->setdata(['module', $parent, $galleryKey, 'positions', range(0, $c - 1)]);
				}
				// Image de couverture
				if ($this->getdata(['module', $parent, $galleryKey, 'config', 'homePicture']) === NULL) {
					if (is_dir($this->getdata(['module', $parent, $galleryKey, 'config', 'directory']))) {
						$iterator = new DirectoryIterator($this->getdata(['module', $parent, $galleryKey, 'config', 'directory']));
						foreach ($iterator as $fileInfos) {
							if ($fileInfos->isDot() === false and $fileInfos->isFile() and @getimagesize($fileInfos->getPathname())) {
								$this->setdata(['module', $parent, $galleryKey, 'config', 'homePicture', $fileInfos->getFilename()]);
								break;
							}
						}
					}
				}
			}
		}
	}
	// Contrôle des options php.ini pour la mise à jour auto
	if (helper::getUrlContents(common::ZWII_UPDATE_URL . common::ZWII_UPDATE_CHANNEL . '/version') === false) {
		$this->setData(['config', 'autoUpdate', false]);
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
		foreach ($files as $file)
			unlink($file);
		chdir($dir);
		rmdir('core/vendor/fullpage/');
	}
	if (file_exists('core/vendor/tinymce/templates/fullPageSections.html')) {
		unlink('core/vendor/tinymce/templates/fullPageSections.html');
	}
	if (file_exists('core/vendor/tinymce/templates/fullPageSlides.html')) {
		unlink('core/vendor/tinymce/templates/fullPageSlides.html');
	}
	$this->setData(['core', 'dataVersion', 10092]);
}
// Version 10.0.93
if ($this->getData(['core', 'dataVersion']) < 10093) {
	// Déplacement du fichier admin.css dans data
	if (file_exists('core/layout/admin.css')) {
		copy('core/layout/admin.css', self::DATA_DIR . 'admin.css');
		unlink('core/layout/admin.css');
	}
	//Déplacement d'un fichier de ressources
	if (file_exists('core/module/config/ressource/.htaccess')) {
		unlink('core/module/config/ressource/.htaccess');
		rmdir('core/module/config/ressource');
	}
	$this->setData(['core', 'dataVersion', 10093]);
	// Réorganisation du thème
	$this->setData(['theme', 'text', 'linkTextColor', $this->getData(['theme', 'link', 'textColor'])]);
}
// Version 10.1.04
if ($this->getData(['core', 'dataVersion']) < 10104) {
	$this->setData(['theme', 'text', 'linkColor', 'rgba(74, 105, 189, 1)']);
	$this->deleteData(['theme', 'text', 'linkTextColor']);
	$this->setdata(['theme', 'block', 'backgroundColor', 'rgba(236, 239, 241, 1)']);
	$this->setdata(['theme', 'block', 'borderColor', 'rgba(236, 239, 241, 1)']);
	$this->setdata(['theme', 'menu', 'radius', '0px']);
	$this->setData(['core', 'dataVersion', 10104]);
}
// Version 10.2.00
if ($this->getData(['core', 'dataVersion']) < 10200) {
	// Paramètres du compte connecté
	if ($this->getUser('id')) {
		$this->setData(['user', $this->getUser('id'), 'connectFail', 0]);
		$this->setData(['user', $this->getUser('id'), 'connectTimeout', 0]);
		$this->setData(['user', $this->getUser('id'), 'accessTimer', 0]);
		$this->setData(['user', $this->getUser('id'), 'accessUrl', '']);
		$this->setData(['user', $this->getUser('id'), 'accessCsrf', $_SESSION['csrf']]);
	}
	// Paramètres de sécurité
	$this->setData(['config', 'connect', 'attempt', 999]);
	$this->setData(['config', 'connect', 'timeout', 0]);
	$this->setData(['config', 'connect', 'log', false]);
	// Thème
	$this->deleteData(['admin', 'colorButtonText']);
	// Remettre à zéro le thème pour la génération du CSS du blog
	if (file_exists(self::DATA_DIR . 'theme.css')) {
		unlink(self::DATA_DIR . 'theme.css');
	}
	// Créer les en-têtes du journal
	$d = 'Date;Heure;IP;Id;Action' . PHP_EOL;
	file_put_contents(self::DATA_DIR . 'journal.log', $d);
	// Init préservation htaccess
	$this->setData(['config', 'autoUpdateHtaccess', false]);
	// Options de barre de membre simple
	$this->setData(['theme', 'menu', 'memberBar', true]);

	// Thème Menu : couleur de page active non définie
	if (!$this->getData(['theme', 'menu', 'activeTextColor'])) {
		$this->setData(['theme', 'menu', 'activeTextColor', $this->getData(['theme', 'menu', 'textColor'])]);
	}
	$this->setData(['core', 'updateAvailable', false]);
	$this->setData(['core', 'dataVersion', 10200]);
}
// Version 10.2.01
if ($this->getData(['core', 'dataVersion']) < 10201) {
	// Options de barre de membre simple
	$this->setData(['theme', 'footer', 'displayMemberBar', false]);
	$this->deleteData(['theme', 'footer', 'displayMemberAccount']);
	$this->deleteData(['theme', 'footer', 'displayMemberLogout']);
	$this->setData(['core', 'dataVersion', 10201]);
}
// Version 10.3.00
if ($this->getData(['core', 'dataVersion']) < 10300) {
	// Options de barre de membre simple
	$this->setData(['config', 'page404', 'none']);
	$this->setData(['config', 'page403', 'none']);
	$this->setData(['config', 'page302', 'none']);
	// Module de recherche
	// Suppression du dossier search
	if (is_dir('core/module/search')) {
		$dir = getcwd();
		chdir('core/module/search');
		$files = glob('*');
		foreach ($files as $file)
			unlink($file);
		chdir($dir);
		rmdir('core/module/search/');
	}
	// Désactivation de l'option dans le pied de page
	$this->setData(['theme', 'footer', 'displaySearch', false]);
	// Inscription des nouvelles variables
	$this->setData(['config', 'searchPageId', '']);

	// Mettre à jour les données des galeries
	$hierarchy = array(); foreach ($this->getHierarchy() as $parentKey => $parentValue) {
		$hierarchy[] = $parentKey;
		foreach ($parentValue as $childKey) {
			$hierarchy[] = $childKey;
		}
	}
	// Mise à jour des données de thème de la galerie
	// Les données de thème sont communes au site
	foreach ($hierarchy as $parentKey => $parent) {
		//La page a une galerie
		if ($this->getData(['page', $parent, 'moduleId']) === 'gallery') {
			foreach ($this->getData(['module', $parent]) as $galleryKey => $galleryItem) {
				// Transfert du theme dans une structure unique
				if (is_array($this->getdata(['theme', $parent]))) {
					$this->setdata(['theme', 'gallery', $this->getdata(['theme', $parent])]);
				}
			}
			$this->deleteData(['theme', $parent]);
		}
	}

	// Mise à jour du numéro de version
	$this->setData(['core', 'dataVersion', 10300]);
}
// Version 10.3.01
if ($this->getData(['core', 'dataVersion']) < 10301) {
	// Inscription des nouvelles variables
	if ($this->getData(['config', 'searchPageId']) === '') {
		$this->setData(['config', 'searchPageId', 'none']);
	}
	if ($this->getData(['config', 'legalPageId']) === '') {
		$this->setData(['config', 'legalPageId', 'none']);
	}
	$this->setData(['core', 'dataVersion', 10301]);
}
// Version 10.3.02
if ($this->getData(['core', 'dataVersion']) < 10302) {
	// Activation par défaut du captcha à la connexion
	$this->setData(['config', 'connect', 'captcha', true]);
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
	$this->setData(['theme', 'menu', 'backgroundColorSub', $this->getData(['theme', 'menu', 'backgroundColor'])]);
	// Nettoyage du fichier de thème pour forcer une régénération
	if (file_exists(self::DATA_DIR . '/theme.css')) { // On ne sait jamais
		unlink(self::DATA_DIR . '/theme.css');
	}
	$this->setData(['core', 'dataVersion', 10304]);
}
// Version 10.3.06
if ($this->getData(['core', 'dataVersion']) < 10306) {
	// Liste des pages
	$hierarchy = array();
	foreach ($this->getHierarchy() as $parentKey => $parentValue) {
		$hierarchy[] = $parentKey;
		foreach ($parentValue as $childKey) {
			$hierarchy[] = $childKey;
		}
	}
	// Mettre à jour les données des blogs les articles sont dans posts
	foreach ($hierarchy as $parentKey => $parent) {
		//La page a un blog
		if ($this->getData(['page', $parent, 'moduleId']) === 'blog') {
			if (is_array($this->getData(['module', $parent]))) {
				foreach ($this->getData(['module', $parent]) as $blogKey => $blogItem) {
					if ($blogKey === 'posts' or $blogKey === 'config') {
						continue;
					}
					$data = $this->getdata(['module', $parent, $blogKey]);
					$this->deleteData(['module', $parent, $blogKey]);
					$this->setData(['module', $parent, 'posts', $blogKey, $data]);
				}
			}
		}
	}
	foreach ($hierarchy as $parentKey => $parent) {
		//La page a une news
		if ($this->getData(['page', $parent, 'moduleId']) === 'news') {
			if (is_array($this->getData(['module', $parent]))) {
				foreach ($this->getData(['module', $parent]) as $newsKey => $newsItem) {
					if ($blogKey === 'posts' or $blogKey === 'config') {
						continue;
					}
					$data = $this->getdata(['module', $parent, $newsKey]);
					$this->deleteData(['module', $parent, $newsKey]);
					$this->setData(['module', $parent, 'posts', $newsKey, $data]);
				}
			}
		}
	}
	$this->setData(['core', 'dataVersion', 10306]);
}

// Version 10.3.08
if ($this->getData(['core', 'dataVersion']) < 10308) {
	// RAZ la mise à jour auto bug 10.3.07
	$this->setData(['core', 'updateAvailable', false]);
	$this->setData(['core', 'dataVersion', 10308]);
}

// Version 10.4.00
if ($this->getData(['core', 'dataVersion']) < 10400) {
	// Ajouter le prénom comme pseudo et le pseudo comme signature
	foreach ($this->getData(['user']) as $userId => $userIds) {
		$this->setData(['user', $userId, 'pseudo', $this->getData(['user', $userId, 'firstname'])]);
		$this->setData(['user', $userId, 'signature', 2]);
	}

	// Ajouter les champs de blog v3
	// Liste des pages dans pageList
	$hierarchy = array();
	foreach ($this->getHierarchy() as $parentKey => $parentValue) {
		$hierarchy[] = $parentKey;
		foreach ($parentValue as $childKey) {
			$hierarchy[] = $childKey;
		}
	}
	// Parcourir pageList et rechercher les modules de blog

	foreach ($hierarchy as $parentKey => $parent) {
		//La page est un blog
		if ($this->getData(['page', $parent, 'moduleId']) === 'blog') {
			$articleIds = array_keys(helper::arrayColumn($this->getData(['module', $parent, 'posts']), 'publishedOn', 'SORT_DESC'));
			foreach ($articleIds as $key => $article) {
				// Droits les deux groupes
				$this->setData(['module', $parent, 'posts', $article, 'editConsent', 3]);
				// Limite de taille 500
				$this->setData(['module', $parent, 'posts', $article, 'commentMaxlength', '500']);
				// Pas d'approbation des commentaires
				$this->setData(['module', $parent, 'posts', $article, 'commentApproved', false]);
				// pas de notification
				$this->setData(['module', $parent, 'posts', $article, 'commentNotification', false]);
				// groupe de notification
				$this->setData(['module', $parent, 'posts', $article, 'commentGroupNotification', 3]);
			}

			// Traitement des commentaires
			if (is_array($this->getData(['module', $parent, 'posts', $article, 'comment']))) {
				foreach ($this->getData(['module', $parent, 'posts', $article, 'comment']) as $commentId => $comment) {
					// Approbation
					$this->setData(['module', $parent, 'posts', $article, 'comment', $commentId, 'approval', true]);
				}
			}
		}
	}

	// Création du fichier locale.json
	$this->setData(['locale', 'homePageId', $this->getData(['config', 'homePageId'])]);
	$this->setData(['locale', 'page404', $this->getData(['config', 'page404'])]);
	$this->setData(['locale', 'page403', $this->getData(['config', 'page403'])]);
	$this->setData(['locale', 'page302', $this->getData(['config', 'page302'])]);
	$this->setData(['locale', 'legalPageId', $this->getData(['config', 'legalPageId'])]);
	$this->setData(['locale', 'searchPageId', $this->getData(['config', 'searchPageId'])]);
	$this->setData(['locale', 'metaDescription', $this->getData(['config', 'metaDescription'])]);
	$this->setData(['locale', 'title', $this->getData(['config', 'title'])]);

	// Renommer les fichier de backup
	if ($this->getInput('configAdvancedFileBackup', helper::FILTER_BOOLEAN) === false) {
		$path = realpath('site/data');
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
			if (strpos($filename, 'back.json')) {
				rename($filename, str_replace('back.json', 'backup.json', $filename));
			}
		}
	}

	// Supprimer les fichiers CSS devenus inutiles du module search
	if (file_exists('module/search/ressource/theme.css'))
		unlink('module/search/ressource/theme.css');
	if (file_exists('module/search/ressource/vartheme.css'))
		unlink('module/search/ressource/vartheme.css');
	$this->deleteData(['theme', 'search', 'keywordColor']);

	// Nettoyer les modules avec des données null

	$modules = $this->getData(['module']);
	foreach ($modules as $key => $value) {
		if (is_null($value)) {
			unset($modules[$key]);
		}
	}
	$this->setData(['module', $modules]);

	$this->setData(['core', 'dataVersion', 10400]);
}

// Version 10.5.02
if ($this->getData(['core', 'dataVersion']) < 10502) {
	// Forcer la régénération du thème
	if (file_exists(self::DATA_DIR . 'theme.css')) {
		unlink(self::DATA_DIR . 'theme.css');
	}
	$this->setData(['core', 'dataVersion', 10502]);
}

// Version 10.6.00
if ($this->getData(['core', 'dataVersion']) < 10600) {

	// Mise à jour des données des modules autonomes

	// Liste des pages dans pageList
	$hierarchy = array();
	foreach ($this->getHierarchy() as $parentKey => $parentValue) {
		$hierarchy[] = $parentKey;
		foreach ($parentValue as $childKey) {
			$hierarchy[] = $childKey;
		}
	}
	// Parcourir pageList et rechercher les modules au CSS autonomes
	foreach ($hierarchy as $parentKey => $parent) {
		if (
			$this->getData(['page', $parent, 'moduleId']) === 'search'
			|| $this->getData(['page', $parent, 'moduleId']) === 'gallery'
			|| $this->getData(['page', $parent, 'moduleId']) === 'news'
		) {
			if (class_exists($parent)) {
				$module = new $moduleId;
				$module->update($parent);
			}
		}
	}
	// Suppression de l'option d'objets par page gérées par les modules
	$this->deleteData(['config', 'itemsperPage']);

	$this->setData(['core', 'dataVersion', 10600]);
}

// Version 11.0.00
if ($this->getData(['core', 'dataVersion']) < 11000) {

	// Option de déconnexion auto activée
	$this->setData(['config', 'autoDisconnect', true]);

	// Mettre à jour les données de langue
	$this->setData(['config', 'i18n', 'enable', true]);
	$this->setData(['config', 'i18n', 'scriptGoogle', false]);
	$this->setData(['config', 'i18n', 'showCredits', false]);
	$this->setData(['config', 'i18n', 'autoDetect', false]);
	$this->setData(['config', 'i18n', 'admin', false]);
	$this->setData(['config', 'i18n', 'fr', 'none']);
	$this->setData(['config', 'i18n', 'de', 'none']);
	$this->setData(['config', 'i18n', 'en', 'none']);
	$this->setData(['config', 'i18n', 'es', 'none']);
	$this->setData(['config', 'i18n', 'it', 'none']);
	$this->setData(['config', 'i18n', 'nl', 'none']);
	$this->setData(['config', 'i18n', 'pt', 'none']);

	// Supprimer les fichiers de backup
	if (file_exists('site/data/.backup'))
		unlink('site/data/.backup');
	$path = realpath('site/data');
	foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
		if (strpos($filename, 'backup.json')) {
			unlink($filename);
		}
	}

	// Externaliser les contenus des pages
	// Liste des pages dans pageList
	$hierarchy = array();
	// Creation du contenu de la page
	if (!is_dir(self::DATA_DIR . self::$i18nContent . '/content')) {
		mkdir(self::DATA_DIR . self::$i18nContent . '/content', 0755);
	}
	foreach ($this->getHierarchy() as $parentKey => $parentValue) {
		$hierarchy[] = $parentKey;
		foreach ($parentValue as $childKey) {
			$hierarchy[] = $childKey;
		}
	}
	foreach ($hierarchy as $parentKey => $parent) {
		$content = $this->getData(['page', $parent, 'content']);
		//file_put_contents(self::DATA_DIR . self::$i18nContent . '/content/' . $parent . '.html', $content);
		$this->setPage($parent, $content, 'fr');
		$this->setData(['page', $parent, 'content', $parent . '.html']);
	}

	// Référencement
	$this->setData(['config', 'seo', 'robots', true]);

	$this->setData(['core', 'dataVersion', 11000]);
}

// Version 11.0.10
if ($this->getData(['core', 'dataVersion']) < 11010) {

	// Renommer une variable
	$data = $this->getData(['config', 'i18n', 'active']);
	$this->deleteData(['config', 'i18n', 'active']);
	$this->setData(['config', 'i18n', 'enable', $data]);

	$this->setData(['core', 'dataVersion', 11010]);
}

// Version 11.1.00
if ($this->getData(['core', 'dataVersion']) < 11100) {

	// Anonymat des adresses iP de la journalisation
	$this->setData(['config', 'connect', 'anonymousIp', 2]);

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
	foreach ($this->getHierarchy() as $parentKey => $parentValue) {
		$hierarchy[] = $parentKey;
		foreach ($parentValue as $childKey) {
			$hierarchy[] = $childKey;
		}
	}
	foreach ($hierarchy as $parentKey => $parent) {
		$this->setData(['page', $parent, 'shortTitle', $this->getData(['page', $parent, 'title'])]);
	}

	// Incorporer les nouveaux champs du header et du menu
	$this->setData(['theme', 'header', 'feature', 'wallpaper']);
	$this->setData(['theme', 'header', 'featureContent', '<p>Bannière vide</p>']);
	$this->setData(['theme', 'header', 'container', 'container']);
	$this->setData(['theme', 'menu', 'container', 'container']);
	// Option des cookies dans le footer
	$this->setData(['theme', 'footer', 'displayCookie', false]);

	// Acceptation et Paramètres des cookies RGPD
	$this->setData(['locale', 'cookies', 'cookiesZwiiText', 'Ce site utilise des cookies nécessaires à son fonctionnement, ils permettent de fluidifier son fonctionnement par exemple en mémorisant les données de connexion, la langue que vous avez choisie ou la validation de ce message.']);
	$this->setData(['locale', 'cookies', 'cookiesTitleText', 'Gérer les cookies']);
	$this->setData(['locale', 'cookies', 'cookiesLinkMlText', 'Consulter les mentions légales']);
	$this->setData(['locale', 'cookies', 'cookiesButtonText', 'J\'ai compris']);

	// Supppression de l'option de traduction en mode connecté
	$this->setData(['config', 'i18n', 'admin', false]);

	// Option de dévoilement du mdp
	$this->setData(['config', 'connect', 'showPassword', true]);

	// Mise à jour
	$this->setData(['core', 'dataVersion', 11200]);
}

// Version 11.2.02
if ($this->getData(['core', 'dataVersion']) < 11202) {

	// Renommer les champs
	$this->setData(['locale', 'cookies', 'mainLabel', $this->getData(['locale', 'cookies', 'cookiesZwiiText'])]);
	$this->setData(['locale', 'cookies', 'gaLabel', $this->getData(['locale', 'cookies', 'cookiesGaText'])]);
	$this->setData(['locale', 'cookies', 'titleLabel', $this->getData(['locale', 'cookies', 'cookiesTitleText'])]);
	$this->setData(['locale', 'cookies', 'linkLegalLabel', $this->getData(['locale', 'cookies', 'cookiesLinkMlText'])]);
	$this->setData(['locale', 'cookies', 'checkboxGaLabel', $this->getData(['locale', 'cookies', 'cookiesCheckboxGaText'])]);
	$this->setData(['locale', 'cookies', 'buttonValidLabel', $this->getData(['locale', 'cookies', 'cookiesButtonText'])]);
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

// Version 11.2.03
if ($this->getData(['core', 'dataVersion']) < 11203) {
	// Supprimer l'information de redirection
	$old = str_replace('?', '', $this->getData(['core', 'baseUrl']));
	$new = '';
	$c3 = 0;
	$success = false;
	// Boucler sur les pages
	foreach ($this->getHierarchy() as $parentId => $childIds) {
		$content = $this->getPage($parentId, self::$i18nContent);
		$titre = $this->getData(['page', $parentId, 'title']);
		$content = $titre . ' ' . $content;
		$replace = str_replace('href="' . $old, 'href="' . $new, stripslashes($content), $c1);
		$replace = str_replace('src="' . $old, 'src="' . $new, stripslashes($replace), $c2);

		if ($c1 > 0 || $c2 > 0) {
			$success = true;
			$this->setPage($parentId, $replace, self::$i18nContent);
			$c3 += $c1 + $c2;
		}
		foreach ($childIds as $childId) {
			$content = $this->getPage($childId, self::$i18nContent);
			$content = $titre . ' ' . $content;
			$replace = str_replace('href="' . $old, 'href="' . $new, stripslashes($content), $c1);
			$replace = str_replace('src="' . $old, 'src="' . $new, stripslashes($replace), $c2);
			if ($c1 > 0 || $c2 > 0) {
				$success = true;
				$this->setPage($childId, $replace, self::$i18nContent);
				$c3 += $c1 + $c2;
			}
		}
	}
	// Traiter les modules dont la redirection
	$content = $this->getdata(['module']);
	$replace = $this->recursive_array_replace('href="' . $old, 'href="' . $new, $content, $c1);
	$replace = $this->recursive_array_replace('src="' . $old, 'src="' . $new, $replace, $c2);
	if ($content !== $replace) {
		$this->setdata(['module', $replace]);
		$c3 += $c1 + $c2;
		$success = true;
	}

	// Effacer la baseUrl
	$this->deleteData(['core', 'baseUrl']);

	// Mise à jour
	$this->setData(['core', 'dataVersion', 11203]);
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
		'Indie+Flower' => 'indie-flower',
		'Josefin+Slab' => 'josefin-sans-std',
		'Lobster' => 'lobster-2',
		'Lora' => 'lora',
		'Lato' => 'lato',
		'Marvel' => 'montserrat-ace',
		'Old+Standard+TT' => 'old-standard-tt-3',
		'Open+Sans' => 'open-sans',
		// Corriger l'erreur de nom de police installée par défaut, il manquait un O en majuscule
		'open+Sans' => 'open-sans',
		'Oswald' => 'oswald-4',
		'PT+Mono' => 'pt-mono',
		'PT+Serif' => 'pt-serif',
		'Raleway' => 'raleway-5',
		'Rancho' => 'rancho',
		'Roboto' => 'Roboto',
		'Signika' => 'signika',
		'Ubuntu' => 'ubuntu',
		'Vollkorn' => 'vollkorn'
	];

	$this->setData(['theme', 'footer', 'font', $fonts[$this->getData(['theme', 'footer', 'font'])]]);
	$this->setData(['theme', 'header', 'font', $fonts[$this->getData(['theme', 'header', 'font'])]]);
	$this->setData(['theme', 'menu', 'font', $fonts[$this->getData(['theme', 'menu', 'font'])]]);
	$this->setData(['theme', 'text', 'font', $fonts[$this->getData(['theme', 'text', 'font'])]]);
	$this->setData(['theme', 'title', 'font', $fonts[$this->getData(['theme', 'title', 'font'])]]);
	$this->setData(['admin', 'fontTitle', $fonts[$this->getData(['admin', 'fontTitle'])]]);
	$this->setData(['admin', 'fontText', $fonts[$this->getData(['admin', 'fontText'])]]);

	unlink(self::DATA_DIR . 'admin.css');
	unlink(self::DATA_DIR . 'theme.css');

	// Mise à jour
	$this->setData(['core', 'dataVersion', 11300]);
}

// Version 11.3.03
if ($this->getData(['core', 'dataVersion']) < 11303) {

	// Ajout de la variable shortTitle basée sur Title
	foreach ($this->getHierarchy() as $parentKey => $parentValue) {
		$hierarchy[] = $parentKey;
		foreach ($parentValue as $childKey) {
			$hierarchy[] = $childKey;
		}
	}
	foreach ($hierarchy as $parentKey => $parent) {
		$this->setData(['page', $parent, 'extraPosition', false]);
	}

	// Mise à jour
	$this->setData(['core', 'dataVersion', 11303]);
}


// Version 11.3.06
if ($this->getData(['core', 'dataVersion']) < 11306) {

	// Supprime les fontes déclarées en double par la version précédentes
	$files = $this->getData(['font', 'files']);
	foreach ($files as $fontId => $fontFile) {
		if (!is_null($this->getData(['font', 'imported', $fontId]))) {
			$this->deleteData(['font', 'imported', $fontId]);
		}
	}
	// Mise à jour
	$this->setData(['core', 'dataVersion', 11306]);
}

// Version 11.4.00
if ($this->getData(['core', 'dataVersion']) < 11400) {

	// Effacer le dossier
	if (is_dir('core/module/addon')) {
		$this->deleteDir('core/module/addon');
	}


	$fonts = [
		'arimo' => [
			'name' => 'Arimo',
			'font-family' => 'Arimo,  sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/arimo'
		],
		'dancing-script' => [
			'name' => 'Dancing Script',
			'font-family' => '\'Dancing Script\', sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/dancing-script'
		],
		'droid-sans-2' => [
			'name' => 'Droid Sans',
			'font-family' => '\'Droid Sans\', sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/droid-sans-2'
		],
		'droid-serif-2' => [
			'name' => 'Droid Serif',
			'font-family' => '\'Droid Serif\', serif',
			'resource' => 'https://fonts.cdnfonts.com/css/droid-serif-2'
		],
		'indie-flower' => [
			'name' => 'Indie Flower',
			'font-family' => '\'Indie Flower\', sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/indie-flower'
		],
		'fira-sans' => [
			'name' => 'Fira Sans',
			'font-family' => '\'Fira Sans\', sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/fira-sans'
		],
		'liberation-sans' => [
			'name' => 'Liberation Sans',
			'font-family' => '\'Liberation Sans\', sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/liberation-sans'
		],
		'liberation-serif' => [
			'name' => 'Liberation Serif',
			'font-family' => '\'Liberation Serif\', serif',
			'resource' => 'https://fonts.cdnfonts.com/css/liberation-serif'
		],
		'lobster-2' => [
			'name' => 'Lobster',
			'font-family' => 'Lobster, sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/lobster-2'
		],
		'lato' => [
			'name' => 'lato',
			'font-family' => 'Lato, sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/lato'
		],
		'old-standard-tt-3' => [
			'name' => 'Old Standard TT',
			'font-family' => '\'Old Standard TT\', serif',
			'resource' => 'https://fonts.cdnfonts.com/css/old-standard-tt-3'
		],
		'open-sans' => [
			'name' => 'Open Sans',
			'font-family' => '\'Open Sans\', sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/open-sans'
		],
		'oswald-4' => [
			'name' => 'Oswald',
			'font-family' => 'Oswald, sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/oswald-4'
		],
		'pt-mono' => [
			'name' => 'PT Mono',
			'font-family' => '\'PT Mono\', monospace',
			'resource' => 'https://fonts.cdnfonts.com/css/pt-mono'
		],
		'pt-serif' => [
			'name' => 'PR Serif',
			'font-family' => '\'PT Serif\', serif',
			'resource' => 'https://fonts.cdnfonts.com/css/pt-serif'
		],
		'rancho' => [
			'name' => 'Rancho',
			'font-family' => 'Rancho, sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/rancho'
		],
		'ubuntu' => [
			'name' => 'Ubuntu',
			'font-family' => 'Ubuntu, sans-serif',
			'resource' => 'https://fonts.cdnfonts.com/css/ubuntu'
		],
		'vollkorn' => [
			'name' => 'Vollkorn',
			'font-family' => 'Vollkorn, serif',
			'resource' => 'https://fonts.cdnfonts.com/css/vollkorn'
		]
	];

	// Conversion des fontes locales

	$files = $this->getData(['font', 'files']);

	if (is_array($files)) {
		foreach ($files as $fontId => $fontName) {
			if (
				gettype($fontName) === 'string'
				&& file_exists(self::DATA_DIR . 'fonts/' . $fontName)
			) {
				$this->setData([
					'font',
					'files',
					$fontId,
					[
						'name' => ucfirst($fontId),
						'font-family' => '\'' . ucfirst($fontId) . '\', sans-serif',
						'resource' => $fontName
					]
				]);
			}
		}
	}

	// Consersion des fontes importées
	$imported = $this->getData(['font', 'imported']);
	if (is_array($imported)) {
		foreach ($imported as $fontId => $fontUrl) {
			if (gettype($fontUrl) === 'string') {
				$this->setData([
					'font',
					'imported',
					$fontId,
					[
						'name' => ucfirst($fontId),
						'font-family' => '\'' . ucfirst($fontId) . '\', sans-serif',
						'resource' => 'https:\\fonts.cdnfonts.com\css' . $fontUrl
					]
				]);
			}
		}
	}
	// Importation des fontes exemples
	$template = $fonts;
	foreach ($template as $fontId => $fontValue) {
		$this->setData(['font', 'imported', $fontId, $fontValue]);
	}

	// Redirection des pages d'administration vers la bannière de connexion
	$this->setData(['config', 'connect', 'redirectLogin', true]);



	// Suppression de la variable URL dans core
	$this->deleteData(['core', 'baseUrl']);

	// Mise à jour
	$this->setData(['core', 'dataVersion', 11400]);
}


// Version 11.5.06
if ($this->getData(['core', 'dataVersion']) < 11506) {

	// Renommage de la barre de membre dans le pied de page
	$data = $this->getData(['theme', 'footer', 'displayMemberBar']);
	$this->setData(['theme', 'footer', 'memberBar', $data]);
	$this->deleteData(['theme', 'footer', 'displayMemberBar']);

	// Mise à jour
	$this->setData(['core', 'dataVersion', 11506]);
}


// Version 12.0.00
if ($this->getData(['core', 'dataVersion']) < 12000) {

	// Supprime un cookie non nécessaire
	helper::deleteCookie('ZWII_USER_LONGTIME');

	// Suppression de la variable URL dans core
	$this->deleteData(['core', 'baseUrl']);

	// Suppression de GA
	$this->deleteData(['config', 'seo', 'analyticsId']);
	$this->deleteData(['config', 'analyticsId']);
	$this->deleteData(['locale', 'cookies', 'gaLabel']);
	$this->deleteData(['locale', 'cookies', 'checkboxGaLabel']);

	// Suppression du booléen de langue, désormais toujours actif et de Google Translate
	$this->deleteData(['config', 'i18n', 'enable']);
	$this->deleteData(['config', 'i18n', 'scriptGoogle']);
	$this->deleteData(['config', 'i18n', 'showCredits']);
	$this->deleteData(['config', 'i18n', 'autoDetect']);
	helper::deleteCookie('ZWII_CONTENTSCRIPT');

	// Nettoyage de fichiers inutiles
	if (file_exists('core/module/user/view/import/import.help.html')) {
		unlink('core/module/user/view/import/import.help.html');
	}

	// Supprimer les fichier associés
	if (is_dir('core/module/translate/ressource')) {
		$this->deleteDir('core/module/translate/ressource');
	}
	if (is_dir('core/vendor/i18n/css')) {
		$this->deleteDir('core/vendor/i18n/css');
	}
	if (file_exists('core/vendor/i18n/inc.json')) {
		unlink('core/vendor/i18n/inc.json');
	}
	if (file_exists('core/vendor/i18n/translate.js')) {
		unlink('core/vendor/i18n/translate.js');
	}
	if (file_exists('core/vendor/i18n/translation.js')) {
		unlink('core/vendor/i18n/translation.js');
	}
	// Copier les fichiers de l'interface
	if (!is_dir(self::I18N_DIR)) {
		// Créer le dossier
		$this->copyDir('core/module/install/ressource/i18n', self::I18N_DIR);
	}
	// Langue de l'interface
	$this->deleteData(['config', 'i18n']);

	// Pas à l'installation
	if ($this->getUser('id')) {
		$this->setData(['user', $this->getUser('id'), 'language', 'fr_FR']);
	}
	// Mise à jour
	$this->setData(['core', 'dataVersion', 12000]);
}

// Version 12.3.01
if ($this->getData(['core', 'dataVersion']) < 12301) {
	// Valeur par défaut du délai de recherche de mise à jour en ligne
	$this->setData(['config', 'autoUpdateDelay', 86400]);


	// Nettoyage de flatPickr
	if (is_dir('core/vendor/flatpickr')) {
		$this->deleteDir('core/vendor/flatpickr');
	}

	// email reply
	$this->deleteData(['config', 'smtp', 'sender']);
	$this->setData(['config', 'smtp', 'from', 'no-reply@' . str_replace('www.', '', $_SERVER['HTTP_HOST'])]);

	// Mise à jour
	$this->setData(['core', 'dataVersion', 12301]);
}

// Version 12.3.08
if ($this->getData(['core', 'dataVersion']) < 12308) {

	// Langue par défaut
	$l = [
		'fr_FR' => 'Français',
		'en_EN' => 'English',
		'es' => 'Español',
	];
	foreach ($l as $key => $value) {
		if (is_dir(self::DATA_DIR . $key)) {
			touch(self::DATA_DIR . $key . '/.default');
			break;
		}
	}

	// Mise à jour
	$this->setData(['core', 'dataVersion', 12308]);
}

// Version 12.3.09
if ($this->getData(['core', 'dataVersion']) < 12309) {

	// Mettre à jour les locales
	foreach (self::$languages as $key => $value) {
		// tableau des langues installées
		if (is_dir(self::DATA_DIR . $key)) {
			$d = json_decode(file_get_contents(self::DATA_DIR . $key . '/locale.json'), true);
			$d = array_merge($d['locale'], ['poweredPageLabel' => 'Motorisé par']);
			$t['locale'] = $d;
			file_put_contents(self::DATA_DIR . $key . '/locale.json', json_encode($t));
		}
	}

	// Mise à jour
	$this->setData(['core', 'dataVersion', 12309]);
}

// Version 12.4.00
if ($this->getData(['core', 'dataVersion']) < 13000) {

	// Nettoyage du dossier de langue d'installation'
	if (file_exists('core/vendor/tinymce/langs/langs.zip'))
		unlink('core/vendor/tinymce/langs/langs.zip');
	if (file_exists('core/module/install/ressource/i18n/de.json'))
		unlink('core/module/install/ressource/i18n/de.json');
	if (file_exists('core/module/install/ressource/i18n/it.json'))
		unlink('core/module/install/ressource/i18n/it.json');
	if (file_exists('core/module/install/ressource/i18n/pt_PT.json'))
		unlink('core/module/install/ressource/i18n/pt_PT.json');
	if (file_exists('core/module/install/ressource/i18n/gr_GR.json'))
		unlink('core/module/install/ressource/i18n/gr_GR.json');

	// Création du dossier partage pour les nouveaux droits
	if (!is_dir(self::FILE_DIR . 'source/partage')) {
		mkdir(self::FILE_DIR . 'source/partage');
	}

	// Efface le dossier translate 
	if (is_dir('core/module/translate')) {
		$this->deleteDir('core/module/translate');
	}

	// Renomme le fichier et le dossier des fontes
	if (file_exists(self::DATA_DIR . 'fonts/fonts.html')) {
		rename(self::DATA_DIR . 'fonts/fonts.html', self::DATA_DIR . 'fonts/font.html');
	}
	if (is_dir(self::DATA_DIR . 'fonts')) {
		rename(self::DATA_DIR . 'fonts', self::DATA_DIR . 'font');
	}


	// Ajouter le prénom comme pseudo et le pseudo comme signature
	foreach ($this->getData(['user']) as $userId => $userIds) {
		switch ($this->getData(['user', $userId, 'group'])) {
			case '1':
			case '2':
				$this->setData(['user', $userId, 'profil', 1]);
				break;
			default:
				$this->setData(['user', $userId, 'profil', 0]);
				break;
		}
	}

	// Récupérer la liste de toutes les pages dans toutes langues
	$hierarchy = array();
	$languages = array();
	// Tableau des langues non installées
	foreach (self::$languages as $key => $value) {
		if (is_dir(self::DATA_DIR . $key))
			$languages[] = $key;
		foreach ($this->getHierarchy() as $parentKey => $parentValue) {
			$hierarchy[] = $parentKey;
			foreach ($parentValue as $childKey) {
				$hierarchy[] = $childKey;
			}
		}
	}

	// Mise à jour des pages, le profil est mis à 0 pour les groupes sans profil et 1 pour es groupes avec profil
	$currentlanguage = self::$i18nContent;
	foreach ($languages as $langId) {

		foreach ($hierarchy as $parentKey => $parent) {
			switch ($this->getData(['page', $parent, 'group'])) {
				case 1:
				case 2:
					$this->setData(['page', $parent, 'profil', 1]);
					break;
				default:
					$this->setData(['page', $parent, 'profil', 0]);
					break;
			}
		}
	}
	$_SESSION['ZWII_CONTENT'] = $currentlanguage;

	// Supprime la clé OpenOgraph
	$this->deleteData(['config', 'seo', 'keyApi']);

	// Mise à jour
	$this->setData(['core', 'dataVersion', 13000]);
}