<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="<?php echo substr(self::$i18nContent, 0, 2); ?>">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php $this->showMetaTitle(); ?>
	<?php $this->showMetaDescription(); ?>
	<?php $this->showMetaType(); ?>
	<?php $this->showMetaImage(); ?>
	<?php $this->showFavicon(); ?>
	<?php $this->showVendor(); ?>
	<?php $this->showStyle(); ?>
	<?php $this->showFonts(); ?>
	<link rel="stylesheet" href="<?php echo helper::baseUrl(false); ?>core/layout/common.css">
	<link rel="stylesheet" href="<?php echo helper::baseUrl(false); ?>core/layout/light.css">
	<link rel="stylesheet" href="<?php echo helper::baseUrl(false) . self::DATA_DIR; ?>theme.css?<?php echo md5_file(self::DATA_DIR.'theme.css'); ?>">
	<link rel="stylesheet" href="<?php echo helper::baseUrl(false) . self::DATA_DIR; ?>custom.css?<?php echo md5_file(self::DATA_DIR.'custom.css'); ?>">
</head>
<body>
<?php $this->showNotification(); ?>
<div id="site" class="container light">
	<section><?php $this->showContent(); ?></section>
</div>
<?php $this->showScript(); ?>
</body>
</html>