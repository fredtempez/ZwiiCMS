<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="<?php echo substr(self::$i18nContent, 0, 2); ?>">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php $layout->showMetaTitle(); ?>
	<?php $layout->showMetaDescription(); ?>
	<?php $layout->showMetaType(); ?>
	<?php $layout->showMetaImage(); ?>
	<?php $layout->showFavicon(); ?>
	<?php $layout->showVendor(); ?>
	<?php $layout->showStyle(); ?>
	<?php $layout->showFonts(); ?>
	<link rel="stylesheet" href="<?php echo helper::baseUrl(false); ?>core/layout/common.css">
	<link rel="stylesheet" href="<?php echo helper::baseUrl(false); ?>core/layout/light.css">
	<link rel="stylesheet" href="<?php echo helper::baseUrl(false) . self::DATA_DIR; ?>theme.css?<?php echo md5_file(self::DATA_DIR.'theme.css'); ?>">
	<link rel="stylesheet" href="<?php echo helper::baseUrl(false) . self::DATA_DIR; ?>custom.css?<?php echo md5_file(self::DATA_DIR.'custom.css'); ?>">
</head>
<body>
<?php $layout->showNotification(); ?>
<div id="site" class="container light">
	<section><?php $layout->showContent(); ?></section>
</div>
<?php $layout->showScript(); ?>
</body>
</html>