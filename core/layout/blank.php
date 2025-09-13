<!DOCTYPE html>
<html class="no-js" prefix="og: http://ogp.me/ns#" lang="<?php echo substr(self::$siteContent, 0, 2); ?>">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html;">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php $layout->showMetaTitle(); ?>
	<?php $layout->showMetaDescription(); ?>
	<?php $layout->showMetaType(); ?>
	<?php $layout->showMetaImage(); ?>
	<?php $layout->showFavicon(); ?>
	<?php $layout->showVendor(); ?>
	<?php $layout->showStyle(); ?>
	<?php $layout->showFonts(); ?>
	<?php 
	// Préchargement des polices si elles existent
	if (file_exists(self::DATA_DIR . 'font/font.css')): 
		$fontCssPath = helper::baseUrl(false) . self::DATA_DIR . 'font/font.css?' . md5_file(self::DATA_DIR . 'font/font.css');
	?>
		<link rel="preload" href="<?php echo $fontCssPath; ?>" as="style" onload="this.rel='stylesheet'">
		<noscript><link rel="stylesheet" href="<?php echo $fontCssPath; ?>"></noscript>
	<?php endif; ?>
	
	<?php 
	// Préchargement des CSS principaux
	$commonCssPath = helper::baseUrl(false) . 'core/layout/common.css';
	$blankCssPath = helper::baseUrl(false) . 'core/layout/blank.css';
	$themeCssPath = helper::baseUrl(false) . self::DATA_DIR . 'theme.css?' . md5_file(self::DATA_DIR . 'theme.css');
	$customCssPath = helper::baseUrl(false) . self::DATA_DIR . 'custom.css?' . md5_file(self::DATA_DIR . 'custom.css');
	?>
	
	<!-- Préchargement des CSS critiques -->
	<!-- CSS critique chargé de manière synchrone -->
	<link rel="stylesheet" href="<?php echo $commonCssPath; ?>">
	<link rel="stylesheet" href="<?php echo $blankCssPath; ?>">
	
	<!-- CSS non critiques chargés en différé -->
	<link rel="preload" href="<?php echo $themeCssPath; ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<noscript><link rel="stylesheet" href="<?php echo $themeCssPath; ?>"></noscript>
	
	<link rel="preload" href="<?php echo $customCssPath; ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<noscript><link rel="stylesheet" href="<?php echo $customCssPath; ?>"></noscript>
	
	<style>
	/* Prévention du FOUC */
	body { visibility: hidden; opacity: 0; transition: opacity 0.15s ease-in; }
	body.ready { visibility: visible; opacity: 1; }
	</style>
	<script>document.documentElement.classList.remove('no-js');</script>
</head>
<body>
<?php $layout->showContent(); ?>
<?php $layout->showScript(); ?>
</body>
</html>