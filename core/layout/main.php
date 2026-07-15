<!DOCTYPE html>
<html class="no-js" prefix="og: http://ogp.me/ns#" lang="<?php
echo substr(self::$siteContent, 0, 2); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta meta="description=" content="ZwiiCMS le CMS multilingue sans base de données">
	<meta name="generator" content="ZiiCMS https://forge.chapril.org/ZwiiCMS-Team/ZwiiCMS">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
echo $layout->showMetaTitle(); ?>
	<?php
echo $layout->showMetaDescription(); ?>
	<?php
echo $layout->showMetaType(); ?>
	<?php
echo $layout->showMetaImage(); ?>
	<?php
echo $layout->showFavicon(); ?>
	<?php
echo $layout->showVendor(); ?>
	<?php
echo $layout->showFonts(); ?>
	<?php
if (file_exists(self::DATA_DIR . 'font/font.css')): $fontCssPath = Helper::baseUrl(false) . self::DATA_DIR . 'font/font.css?' . md5_file(self::DATA_DIR . 'font/font.css'); ?>
		<link rel="preload" href="<?php
echo $fontCssPath; ?>" as="style" onload="this.rel='stylesheet'">
		<noscript><link rel="stylesheet" href="<?php
echo $fontCssPath; ?>"></noscript>
	<?php
endif; ?>
	
	<?php
$commonCssPath = Helper::baseUrl(false) . 'core/layout/common.css?' . md5_file('core/layout/common.css'); $themeCssPath = Helper::baseUrl(false) . self::DATA_DIR . 'theme.css?' . md5_file(self::DATA_DIR . 'theme.css'); $customCssFile = self::DATA_DIR . 'custom.css'; if (file_exists($customCssFile)) { $customCssPath = Helper::baseUrl(false) . $customCssFile . '?' . md5_file($customCssFile); } else { $customCssPath = ''; } ?>
	
	<!-- CSS critique chargé de manière synchrone -->
	<link rel="stylesheet" href="<?php
echo $commonCssPath; ?>">
	
	<!-- CSS non critiques chargés en différé -->
	<link rel="preload" href="<?php
echo $themeCssPath; ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<noscript><link rel="stylesheet" href="<?php
echo $themeCssPath; ?>"></noscript>
	
	<?php
if (!empty($customCssPath)): ?>
	<link rel="preload" href="<?php
echo $customCssPath; ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<noscript><link rel="stylesheet" href="<?php
echo $customCssPath; ?>"></noscript>
	<?php
endif; ?>
	
	<style>
	/* Prévention du FOUC */
	body { visibility: hidden; opacity: 0; transition: opacity 0.15s ease-in; }
	body.ready { visibility: visible; opacity: 1; }
	</style>
	<script>document.documentElement.classList.remove('no-js');</script>
	<!-- Détection RSS -->
	<?php
if ( ($this->getData(['page', $this->getUrl(0), 'moduleId']) === 'blog' or $this->getData(['page', $this->getUrl(0), 'moduleId']) === 'news') and $this->getData(['module', $this->getUrl(0), 'config', 'feeds']) === TRUE ): ?>
		<link rel="alternate" type="application/rss+xml"
			href="'<?php
echo Helper::baseUrl() . $this->getUrl(0) . '/rss'; ?>" title="fLUX rss">
	<?php
endif; ?>
	<?php
echo $layout->showStyle(); ?>
	<?php
echo $layout->showInlineStyle(); ?>
	<!-- Script perso dans le header -->
	<?php
if (file_exists(self::DATA_DIR . 'head.inc.html')) { include(self::DATA_DIR . 'head.inc.html'); } ?>
</head>

<body>
	<!-- Barre d'administration -->
	<?php
if ($this->getUser('role') > self::ROLE_MEMBER): ?>
		<?php
echo $layout->showBar(); ?>
	<?php
endif; ?>
	<!-- Notifications -->
	<?php
echo $layout->showNotification(); ?>
	<!-- Menu dans le fond du site avant la bannière -->
	<?php
if ($this->getData(['theme', 'menu', 'position']) === 'body-first' || $this->getData(['theme', 'menu', 'position']) === 'top'): ?>
		<!-- Détermine si le menu est fixe en haut de page lorsque l'utilisateur n'est pas connecté -->
		<?php
if ( $this->isConnected() === true and $this->getUser('role') > self::ROLE_MEMBER ) { echo '<nav id="navfixedconnected" >'; } else { echo '<nav id="navfixedlogout" >'; } ?>
		<!-- Menu Burger (ouverture CSS, sans JS) -->
		<input type="checkbox" id="toggleCheck" class="toggleCheck" aria-hidden="true">
		<label id="toggle" for="toggleCheck" role="button" aria-label="Menu">
			<?php
echo $this->getData(['theme', 'menu', 'burgerContent']) === 'title' ? '<div  id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : ''; ?>
			<?php
echo $this->getData(['theme', 'menu', 'burgerContent']) === 'logo' ? '<div  id="burgerLogo"><img src="' . Helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['theme', 'menu', 'burgerLogo']) . '"></div>' : ''; ?>
			<?php
echo Template::ico('menu', ['fontSize' => '2em']); ?>
		</label>
		<!-- fin du menu burger -->
		<?php
$menuClass = $this->getData(['theme', 'menu', 'position']) === 'top' ? 'class="container-large"' : 'class="container"'; $menuClass = $this->getData(['theme', 'menu', 'wide']) === 'none' ? 'class="container-large"' : 'class="container"'; ?>
		<div id="menu" <?php
echo $menuClass; ?>>
			<?php
echo $layout->showMenu(); ?>
		</div> <!--fin menu -->
		</nav>
	<?php
endif; ?>
	<!-- Bannière dans le fond du site -->
	<?php
if ($this->getData(['theme', 'header', 'position']) === 'body'): ?>
		<?php
echo ($this->getData(['theme', 'header', 'linkHomePage']) && $this->getData(['theme', 'header', 'feature']) === 'wallpaper') ? '<a href="' . Helper::baseUrl(false) . '">' : ''; ?>
		<?php
$headerClass = $this->getData(['theme', 'header', 'position']) === 'hide' ? 'displayNone' : ''; $headerClass .= $this->getData(['theme', 'header', 'tinyHidden']) ? ' bannerDisplay ' : ''; $headerClass .= $this->getData(['theme', 'header', 'wide']) === 'none' ? '' : 'container'; ?>
		<header <?php
echo empty($headerClass) ? '' : 'class="' . $headerClass . '"'; ?>>
			<?php
if ($this->getData(['theme', 'header', 'feature']) === 'wallpaper'): ?>
				<?php
if ( $this->getData(['theme', 'header', 'textHide']) === false or ($this->getUrl(0) === 'theme' and $this->getUrl(1) === 'header') ): ?>
					<span id="themeHeaderTitle">
						<?php
echo $this->getData(['locale', 'title']); ?>
					</span>
				<?php
else: ?>
					<span id="themeHeaderTitle">&nbsp;</span>
				<?php
endif; ?>
			<?php
else: ?>
				<div id="featureContent">
					<?php
echo $this->getData(['theme', 'header', 'featureContent']); ?>
				</div>
			<?php
endif; ?>
		</header>
		<?php
echo ($this->getData(['theme', 'header', 'linkHomePage']) && $this->getData(['theme', 'header', 'feature']) === 'wallpaper') ? '</a>' : ''; ?>
	<?php
endif; ?>
	<!-- Menu dans le fond du site après la bannière -->
	<?php
if ($this->getData(['theme', 'menu', 'position']) === 'body-second'): ?>
		<nav>
			<!-- Menu burger -->
			<input type="checkbox" id="toggleCheck" class="toggleCheck" aria-hidden="true">
			<label id="toggle" for="toggleCheck" role="button" aria-label="Menu">
				<?php
echo $this->getData(['theme', 'menu', 'burgerContent']) === 'title' ? '<div  id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : ''; ?>
				<?php
echo $this->getData(['theme', 'menu', 'burgerContent']) === 'logo' ? '<div  id="burgerLogo"><img src="' . Helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['theme', 'menu', 'burgerLogo']) . '"></div>' : ''; ?>
				<?php
echo Template::ico('menu', ['fontSize' => '2em']); ?>
			</label>
			<!-- fin du menu burger -->
			<?php
$menuClass = $this->getData(['theme', 'menu', 'wide']) === 'none' ? 'class="container-large"' : 'class="container"'; ?>
			<div id="menu" <?php
echo $menuClass; ?>>
				<?php
echo $layout->showMenu(); ?>
			</div>
		</nav>
	<?php
endif; ?>
	<!-- Site -->
	<div id="site" class="container">
		<?php
if ($this->getData(['theme', 'menu', 'position']) === 'site-first'): ?>
			<!-- Menu dans le site avant la bannière -->
			<nav>
				<input type="checkbox" id="toggleCheck" class="toggleCheck" aria-hidden="true">
				<label id="toggle" for="toggleCheck" role="button" aria-label="Menu">
					<?php
echo $this->getData(['theme', 'menu', 'burgerContent']) === 'title' ? '<div  id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : ''; ?>
					<?php
echo $this->getData(['theme', 'menu', 'burgerContent']) === 'logo' ? '<div  id="burgerLogo"><img src="' . Helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['theme', 'menu', 'burgerLogo']) . '"></div>' : ''; ?>
					<?php
echo Template::ico('menu', ['fontSize' => '2em']); ?>
				</label>
				<div id="menu" class="container">
					<?php
echo $layout->showMenu(); ?>
				</div>
			</nav>
		<?php
endif; ?>
		<?php
if ( $this->getData(['theme', 'header', 'position']) === 'site' or ($this->getData(['theme', 'header', 'position']) === 'hide' and $this->getUrl(0) === 'theme' ) ): ?>
			<!-- Bannière dans le site -->
			<?php
echo ($this->getData(['theme', 'header', 'linkHomePage']) && $this->getData(['theme', 'header', 'feature']) === 'wallpaper') ? '<a href="' . Helper::baseUrl(false) . '">' : ''; ?>
			<?php
$headerClass = $this->getData(['theme', 'header', 'position']) === 'hide' ? 'displayNone' : ''; $headerClass .= $this->getData(['theme', 'header', 'tinyHidden']) ? ' bannerDisplay ' : ''; ?>
			<header <?php
echo empty($headerClass) ? '' : 'class="' . $headerClass . '"'; ?>>
				<?php
if ($this->getData(['theme', 'header', 'feature']) === 'wallpaper'): ?>
					<?php
if ( $this->getData(['theme', 'header', 'textHide']) === false or ($this->getUrl(0) === 'theme' and $this->getUrl(1) === 'header') ): ?>
						<span id="themeHeaderTitle">
							<?php
echo $this->getData(['locale', 'title']); ?>
						</span>
					<?php
else: ?>
						<span id="themeHeaderTitle">&nbsp;</span>
					<?php
endif; ?>
				<?php
else: ?>
					<div id="featureContent">
						<?php
echo $this->getData(['theme', 'header', 'featureContent']); ?>
					</diV>
				<?php
endif; ?>
			</header>
			<?php
echo ($this->getData(['theme', 'header', 'linkHomePage']) && $this->getData(['theme', 'header', 'feature']) === 'wallpaper') ? '</a>' : ''; ?>
		<?php
endif; ?>
		<?php
if ( $this->getData(['theme', 'menu', 'position']) === 'site-second' || $this->getData(['theme', 'menu', 'position']) === 'site' or ($this->getData(['theme', 'menu', 'position']) === 'hide' and $this->getUrl(0) === 'theme' ) ): ?>
			<!-- Menu dans le site après la bannière -->
			<nav <?php
if ($this->getData(['theme', 'menu', 'position']) === 'hide'): ?>class="displayNone" <?php
endif; ?>>
				<input type="checkbox" id="toggleCheck" class="toggleCheck" aria-hidden="true">
				<label id="toggle" for="toggleCheck" role="button" aria-label="Menu">
					<?php
echo $this->getData(['theme', 'menu', 'burgerContent']) === 'title' ? '<div  id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : ''; ?>
					<?php
echo $this->getData(['theme', 'menu', 'burgerContent']) === 'logo' ? '<div  id="burgerLogo"><img src="' . Helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['theme', 'menu', 'burgerLogo']) . '"></div>' : ''; ?>
					<?php
echo Template::ico('menu', ['fontSize' => '2em']); ?>
				</label>
				<div id="menu" class="container">
					<?php
echo $layout->showMenu(); ?>
				</div>
			</nav>
		<?php
endif; ?>
		<!-- Corps de page -->
		<?php
echo $layout->showMain(); ?>
		<!-- footer -->
		<?php
echo $layout->showFooter(); ?>
		<!-- Fin du site -->
		<?php
echo $this->getData(['theme', 'footer', 'position']) === 'site' ? '</div>' : ''; ?>
		<!-- Lien remonter en haut -->
		<div id="backToTop">
			<?php
echo Template::ico('up'); ?>
		</div>
		<!-- Affichage du consentement aux cookies-->
		<?php
echo $layout->showCookies(); ?>
		<!-- Les scripts -->
		<?php
echo $layout->showScript(); ?>
		<!-- Script perso dans body -->
		<?php
if (file_exists(self::DATA_DIR . 'body.inc.html')) { include(self::DATA_DIR . 'body.inc.html'); } ?>
</body>

</html>
