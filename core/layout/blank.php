<!DOCTYPE html>
<html class="no-js" prefix="og: http://ogp.me/ns#" lang="<?php
echo substr(self::$siteContent, 0, 2); ?>">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html;">
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
echo $layout->showStyle(); ?>
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
$commonCssPath = Helper::baseUrl(false) . 'core/layout/common.css'; $blankCssPath = Helper::baseUrl(false) . 'core/layout/blank.css'; $themeCssPath = Helper::baseUrl(false) . self::DATA_DIR . 'theme.css?' . md5_file(self::DATA_DIR . 'theme.css'); $customCssFile = self::DATA_DIR . 'custom.css'; if (file_exists($customCssFile)) { $customCssPath = Helper::baseUrl(false) . $customCssFile . '?' . md5_file($customCssFile); } else { $customCssPath = ''; } ?>
	
	<!-- Préchargement des CSS critiques -->
	<!-- CSS critique chargé de manière synchrone -->
	<link rel="stylesheet" href="<?php
echo $commonCssPath; ?>">
	<link rel="stylesheet" href="<?php
echo $blankCssPath; ?>">
	
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
</head>
<body>
<?php
echo $layout->showContent(); ?>
<?php
echo $layout->showScript(); ?>
</body>
</html>
