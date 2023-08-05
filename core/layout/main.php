<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="<?php echo substr(self::$i18nContent, 0, 2); ?>">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta meta="description=" content="ZwiiCMS le CMS multilingue sans base de données">
	<meta name="generator" content="ZiiCMS https://forge.chapril.org/ZwiiCMS-Team/ZwiiCMS">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php $layout->showMetaTitle(); ?>
	<?php $layout->showMetaDescription(); ?>
	<?php $layout->showMetaType(); ?>
	<?php $layout->showMetaImage(); ?>
	<?php $layout->showFavicon(); ?>
	<?php $layout->showVendor(); ?>
	<?php $layout->showFonts(); ?>
	<link rel="stylesheet" href="<?php echo helper::baseUrl(false); ?>core/layout/common.css?<?php echo md5_file('core/layout/common.css'); ?>">
	<link rel="stylesheet" href="<?php echo helper::baseUrl(false) . self::DATA_DIR; ?>theme.css?<?php echo md5_file(self::DATA_DIR . 'theme.css'); ?>">
	<link rel="stylesheet" href="<?php echo helper::baseUrl(false) . self::DATA_DIR; ?>custom.css?<?php echo md5_file(self::DATA_DIR . 'custom.css'); ?>">
	<!-- Détection RSS -->
	<?php if (($this->getData(['page', $this->getUrl(0), 'moduleId']) === 'blog'
			or $this->getData(['page', $this->getUrl(0), 'moduleId']) === 'news')
		and $this->getData(['module', $this->getUrl(0), 'config', 'feeds']) === TRUE
	) : ?>
		<link rel="alternate" type="application/rss+xml" href="'<?php echo helper::baseUrl() . $this->getUrl(0) . '/rss'; ?>" title="fLUX rss">
	<?php endif; ?>
	<?php $layout->showStyle(); ?>
	<?php $layout->showInlineStyle(); ?>
	<!-- Script perso dans le header -->
	<?php if (file_exists(self::DATA_DIR . 'head.inc.html')) {
		include(self::DATA_DIR . 'head.inc.html');
	} ?>
</head>
<body>
	<!-- Barre d'administration -->
	<?php if ($this->getUser('group') > self::GROUP_MEMBER) : ?>
		<?php $layout->showBar(); ?>
	<?php endif; ?>
	<!-- Notifications -->
	<?php $layout->showNotification(); ?>
	<!-- Menu dans le fond du site avant la bannière -->
	<?php if ($this->getData(['theme', 'menu', 'position']) === 'body-first' || $this->getData(['theme', 'menu', 'position']) === 'top') : ?>
		<!-- Détermine si le menu est fixe en haut de page lorsque l'utilisateur n'est pas connecté -->
		<?php
		if (
			$this->getData(['theme', 'menu', 'position']) === 'top'
			and $this->getData(['theme', 'menu', 'fixed']) === true
			and $this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
			and $this->getUser('group') > self::GROUP_MEMBER
		) {
			echo '<nav id="navfixedconnected" >';
		} else {
			echo '<nav id="navfixedlogout" >';
		}
		?>
		<!-- Menu Burger -->
		<div id="toggle">
			<?php echo $this->getData(['theme', 'menu', 'burgerContent']) === 'title'  ? '<div  id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : ''; ?>
			<?php echo $this->getData(['theme', 'menu', 'burgerContent']) === 'logo'   ? '<div  id="burgerLogo"><img src="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['theme', 'menu', 'burgerLogo']) . '"></div>' : ''; ?>
			<?php echo template::ico('menu', ['fontSize' => '2em']); ?></div>
		<!-- fin du menu burger -->
		<?php
		$menuClass = $this->getData(['theme', 'menu', 'position']) === 'top' ? 'class="container-large"'  : 'class="container"';
		$menuClass = $this->getData(['theme', 'menu', 'wide']) === 'none' ? 'class="container-large"'  : 'class="container"';
		?>
		<div id="menu" <?php echo $menuClass; ?>>
			<?php $layout->showMenu(); ?>
		</div> <!--fin menu -->
		</nav>
	<?php endif; ?>
	<!-- Bannière dans le fond du site -->
	<?php if ($this->getData(['theme', 'header', 'position']) === 'body') : ?>
		<?php echo ($this->getData(['theme', 'header', 'linkHomePage']) && $this->getData(['theme', 'header', 'feature']) === 'wallpaper') ?  '<a href="' . helper::baseUrl(false) . '">' : ''; ?>
		<?php
		$headerClass =  $this->getData(['theme', 'header', 'position']) === 'hide' ? 'displayNone' : '';
		$headerClass .= $this->getData(['theme', 'header', 'tinyHidden']) ? ' bannerDisplay ' : '';
		$headerClass .= $this->getData(['theme', 'header', 'wide']) === 'none' ? '' : 'container';
		?>
		<header <?php echo empty($headerClass) ? '' : 'class="' . $headerClass . '"'; ?>>
			<?php if ($this->getData(['theme', 'header', 'feature']) === 'wallpaper') : ?>
				<?php if (
					$this->getData(['theme', 'header', 'textHide']) === false
					// Affiche toujours le titre de la bannière pour l'édition du thème
					or ($this->getUrl(0) === 'theme' and $this->getUrl(1) === 'header')
				) : ?>
					<span id="themeHeaderTitle"><?php echo $this->getData(['locale', 'title']); ?></span>
				<?php else : ?>
					<span id="themeHeaderTitle">&nbsp;</span>
				<?php endif; ?>
			<?php else : ?>
				<div id="featureContent">
					<?php echo $this->getData(['theme', 'header', 'featureContent']); ?>
				</div>
			<?php endif; ?>
		</header>
		<?php echo ($this->getData(['theme', 'header', 'linkHomePage']) && $this->getData(['theme', 'header', 'feature']) === 'wallpaper') ?  '</a>' : ''; ?>
	<?php endif; ?>
	<!-- Menu dans le fond du site après la bannière -->
	<?php if ($this->getData(['theme', 'menu', 'position']) === 'body-second') : ?>
		<nav>
			<!-- Menu burger -->
			<div id="toggle">
				<?php echo $this->getData(['theme', 'menu', 'burgerContent']) === 'title'  ? '<div  id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : ''; ?>
				<?php echo $this->getData(['theme', 'menu', 'burgerContent']) === 'logo'   ? '<div  id="burgerLogo"><img src="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['theme', 'menu', 'burgerLogo']) . '"></div>' : ''; ?>
				<?php echo template::ico('menu', ['fontSize' => '2em']); ?></div>
			<!-- fin du menu burger -->
			<?php
			$menuClass = $this->getData(['theme', 'menu', 'wide']) === 'none' ? 'class="container-large"'  : 'class="container"';
			?>
			<div id="menu" <?php echo $menuClass; ?>>
				<?php $layout->showMenu(); ?></div>
		</nav>
	<?php endif; ?>
	<!-- Site -->
	<div id="site" class="container">
		<?php if ($this->getData(['theme', 'menu', 'position']) === 'site-first') : ?>
			<!-- Menu dans le site avant la bannière -->
			<nav>
				<div id="toggle">
					<?php echo $this->getData(['theme', 'menu', 'burgerContent']) === 'title'  ? '<div  id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : ''; ?>
					<?php echo $this->getData(['theme', 'menu', 'burgerContent']) === 'logo'   ? '<div  id="burgerLogo"><img src="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['theme', 'menu', 'burgerLogo']) . '"></div>' : ''; ?>
					<?php echo template::ico('menu', ['fontSize' => '2em']); ?></div>
				<div id="menu" class="container"><?php $layout->showMenu(); ?></div>
			</nav>
		<?php endif; ?>
		<?php if (
			$this->getData(['theme', 'header', 'position']) === 'site'
			// Affiche toujours la bannière pour l'édition du thème
			or ($this->getData(['theme', 'header', 'position']) === 'hide'
				and $this->getUrl(0) === 'theme'
			)
		) : ?>
			<!-- Bannière dans le site -->
			<?php echo ($this->getData(['theme', 'header', 'linkHomePage']) &&  $this->getData(['theme', 'header', 'feature']) === 'wallpaper') ? '<a href="' . helper::baseUrl(false) . '">' : ''; ?>
			<?php
			$headerClass =  $this->getData(['theme', 'header', 'position']) === 'hide' ? 'displayNone' : '';
			$headerClass .= $this->getData(['theme', 'header', 'tinyHidden']) ? ' bannerDisplay ' : '';
			?>
			<header <?php echo empty($headerClass) ? '' : 'class="' . $headerClass . '"'; ?>>
				<?php if ($this->getData(['theme', 'header', 'feature']) === 'wallpaper') : ?>
					<?php if (
						$this->getData(['theme', 'header', 'textHide']) === false
						// Affiche toujours le titre de la bannière pour l'édition du thème
						or ($this->getUrl(0) === 'theme' and $this->getUrl(1) === 'header')
					) : ?>
						<span id="themeHeaderTitle"><?php echo $this->getData(['locale', 'title']); ?></span>
					<?php else : ?>
						<span id="themeHeaderTitle">&nbsp;</span>
					<?php endif; ?>
				<?php else : ?>
					<div id="featureContent">
						<?php echo $this->getData(['theme', 'header', 'featureContent']); ?>
					</diV>
				<?php endif; ?>
			</header>
			<?php echo ($this->getData(['theme', 'header', 'linkHomePage']) &&  $this->getData(['theme', 'header', 'feature']) === 'wallpaper') ? '</a>' : ''; ?>
		<?php endif; ?>
		<?php if (
			$this->getData(['theme', 'menu', 'position']) === 'site-second' ||
			$this->getData(['theme', 'menu', 'position']) === 'site'
			// Affiche toujours le menu pour l'édition du thème
			or ($this->getData(['theme', 'menu', 'position']) === 'hide'
				and $this->getUrl(0) === 'theme'
			)
		) : ?>
			<!-- Menu dans le site après la bannière -->
			<nav <?php if ($this->getData(['theme', 'menu', 'position']) === 'hide') : ?>class="displayNone" <?php endif; ?>>
				<div id="toggle">
					<?php echo $this->getData(['theme', 'menu', 'burgerContent']) === 'title'  ? '<div  id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : ''; ?>
					<?php echo $this->getData(['theme', 'menu', 'burgerContent']) === 'logo'   ? '<div  id="burgerLogo"><img src="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['theme', 'menu', 'burgerLogo']) . '"></div>' : ''; ?>
					<?php echo template::ico('menu', ['fontSize' => '2em']); ?></div>
				<div id="menu" class="container"><?php $layout->showMenu(); ?></div>
			</nav>
		<?php endif; ?>
		<!-- Corps de page -->
		<?php $layout->showMain(); ?>
		<!-- footer -->
		<?php $layout->showFooter(); ?>
		<!-- Fin du site -->
		<?php echo $this->getData(['theme', 'footer', 'position']) === 'site' ? '</div>' : ''; ?>
		<!-- Lien remonter en haut -->
		<div id="backToTop"><?php echo template::ico('up'); ?></div>
		<!-- Affichage du consentement aux cookies-->
		<?php $layout->showCookies(); ?>
		<!-- Les scripts -->
		<?php $layout->showScript(); ?>
		<!-- Script perso dans body -->
		<?php if (file_exists(self::DATA_DIR . 'body.inc.html')) {
			include(self::DATA_DIR . 'body.inc.html');
		}?>
</body>

</html>