<!DOCTYPE html>
<html class="no-js" prefix="og: http://ogp.me/ns#" lang="<?php
echo substr(self::$siteContent, 0, 2); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
$commonCssPath = Helper::baseUrl(false) . 'core/layout/common.css?' . md5_file('core/layout/common.css'); $editCssPath = Helper::baseUrl(false) . 'core/layout/edit.css?' . md5_file('core/layout/edit.css'); $themeCssPath = Helper::baseUrl(false) . self::DATA_DIR . 'theme.css?' . md5_file(self::DATA_DIR . 'theme.css'); $customCssFile = self::DATA_DIR . 'custom.css'; if (file_exists($customCssFile)) { $customCssPath = Helper::baseUrl(false) . $customCssFile . '?' . md5_file($customCssFile); } else { $customCssPath = ''; } ?>

	<!-- CSS critique chargé de manière synchrone -->
	<link rel="stylesheet" href="<?php
echo $commonCssPath; ?>">
	<link rel="stylesheet" href="<?php
echo $editCssPath; ?>">

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
	<!-- Site (contenu de l'éditeur, sans habillage du thème) -->
	<div id="site" class="container edit">
		<?php
echo $layout->showMain(); ?>
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
	</div>
</body>

</html>
