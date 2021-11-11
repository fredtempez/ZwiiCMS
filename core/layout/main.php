<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="<?php echo self::$i18n;?>">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php $this->showMetaTitle(); ?>
		<?php $this->showMetaDescription(); ?>
		<?php $this->showMetaType(); ?>
		<?php $this->showMetaImage(); ?>
		<?php $this->showFavicon(); ?>
		<?php $this->showVendor(); ?>
		<?php $this->showAnalytics(); ?>
		<link rel="stylesheet" href="<?php echo helper::baseUrl(false); ?>core/layout/common.css?<?php echo md5_file('core/layout/common.css');?>">
		<link rel="stylesheet" href="<?php echo helper::baseUrl(false) . self::DATA_DIR; ?>theme.css?<?php echo md5_file(self::DATA_DIR.'theme.css'); ?>">
		<link rel="stylesheet" href="<?php echo helper::baseUrl(false) . self::DATA_DIR; ?>custom.css?<?php echo md5_file(self::DATA_DIR.'custom.css'); ?>">
		<!-- Détection RSS -->
		<?php if (  (  $this->getData(['page', $this->getUrl(0), 'moduleId']) === 'blog'
					OR $this->getData(['page', $this->getUrl(0), 'moduleId']) === 'news' )
					AND $this->getData(['module', $this->getUrl(0), 'config', 'feeds']) === TRUE ): ?>
			<link rel="alternate" type="application/rss+xml" href="'<?php echo helper::baseUrl(). $this->getUrl(0) . '/rss';?>" title="fLUX rss">
		<?php endif; ?>
		<?php $this->showStyle(); ?>
		<?php if (file_exists(self::DATA_DIR .'head.inc.html')) {
			include(self::DATA_DIR .'head.inc.html');
		}?>
	</head>
	<body>
		<!-- Barre d'administration -->
		<?php if($this->getUser('group') > self::GROUP_MEMBER): ?>
			<?php $this->showBar(); ?>
		<?php endif;?>

		<!-- Notifications -->
		<?php $this->showNotification(); ?>

		<!-- Menu dans le fond du site avant la bannière -->
		<?php if($this->getData(['theme', 'menu', 'position']) === 'body-first' || $this->getData(['theme', 'menu', 'position']) === 'top' ): ?>	
				<!-- Détermine si le menu est fixe en haut de page lorsque l'utilisateur n'est pas connecté -->
				<?php
				if ( $this->getData(['theme', 'menu', 'position']) === 'top'
					AND $this->getData(['theme', 'menu', 'fixed']) === true
					AND $this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD',true)
					AND $this->getUser('group') > self::GROUP_MEMBER) {
						echo '<nav id="navfixedconnected" >';
					} else {
						echo '<nav id="navfixedlogout" >';
					}
				?>
				<!-- Menu Burger -->
				<div id="toggle">
				<?php echo $this->getData(['theme','menu','burgerContent']) === 'title'  ? '<div class="notranslate" id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : '' ;?>
				<?php echo $this->getData(['theme','menu','burgerContent']) === 'logo'   ? '<div class="notranslate" id="burgerLogo"><img src="'.helper::baseUrl(false).self::FILE_DIR.'source/'. $this->getData(['theme', 'menu', 'burgerLogo']) .'"></div>' : '' ;?>
				<?php echo template::ico('menu',null,null,'2em'); ?></div>
				<div id="menu" <?php echo $this->getData(['theme', 'menu', 'position']) === 'top' ? 'class="container-large"'  : 'class="container"'; ?> >
				<?php $this->showMenu(); ?>
				</div> <!--fin menu -->
			</nav>
		<?php endif; ?>

		<!-- Bannière dans le fond du site -->
		<?php if($this->getData(['theme', 'header', 'position']) === 'body'): ?>	
			<?php 
				$headerClass =  $this->getData(['theme', 'header', 'position']) === 'hide' ? 'displayNone' : ' ';
				$headerClass .= $this->getData(['theme', 'header', 'tinyHidden']) ? ' bannerDisplay ' : ' ';
				$headerClass .= $this->getData(['theme', 'header', 'container']);
			?>
			<header class="<?php echo $headerClass;?>">
			<?php echo ($this->getData(['theme','header','linkHomePage']) && $this->getData(['theme','header','feature']) === 'wallpaper' ) ?  '<a href="' . helper::baseUrl(false) . '">' : ''; ?>
				<?php if ($this->getData(['theme','header','feature']) === 'wallpaper' ): ?>
					<?php if(
						$this->getData(['theme', 'header', 'textHide']) === false
						// Affiche toujours le titre de la bannière pour l'édition du thème
						OR ($this->getUrl(0) === 'theme' AND $this->getUrl(1) === 'header')
					): ?>
							<span class="notranslate" id="themeHeaderTitle"><?php echo $this->getData(['locale', 'title']); ?></span>
					<?php else: ?>
							<span id="themeHeaderTitle">&nbsp;</span>
					<?php endif; ?>
				<?php else: ?>
					<div id="featureContent">
						<?php echo $this->getData(['theme','header','featureContent']);?>
					</div>
			<?php endif; ?>
			</header>
			<?php echo ( $this->getData(['theme','header','linkHomePage']) && $this->getData(['theme','header','feature']) === 'wallpaper' ) ? '</a>' : ''; ?>
		<?php endif; ?>

		<!-- Menu dans le fond du site après la bannière -->
		<?php if($this->getData(['theme', 'menu', 'position']) === 'body-second'): ?>
			
			<nav>
				<div id="toggle">
				<?php echo $this->getData(['theme','menu','burgerContent']) === 'title'  ? '<div class="notranslate" id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : '' ;?>
				<?php echo $this->getData(['theme','menu','burgerContent']) === 'logo'   ? '<div class="notranslate" id="burgerLogo"><img src="'.helper::baseUrl(false).self::FILE_DIR.'source/'. $this->getData(['theme', 'menu', 'burgerLogo']) .'"></div>' : '' ;?>
				<?php echo template::ico('menu',null,null,'2em'); ?></div>
				<div id="menu" class="container"><?php $this->showMenu(); ?></div>
			</nav>
		<?php endif; ?>

		<!-- Site -->
		<div id="site" class="container">
			<?php if($this->getData(['theme', 'menu', 'position']) === 'site-first'): ?>
				<!-- Menu dans le site avant la bannière -->
				<nav>
					<div id="toggle">
					<?php echo $this->getData(['theme','menu','burgerContent']) === 'title'  ? '<div class="notranslate" id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : '' ;?>
					<?php echo $this->getData(['theme','menu','burgerContent']) === 'logo'   ? '<div class="notranslate" id="burgerLogo"><img src="'.helper::baseUrl(false).self::FILE_DIR.'source/'. $this->getData(['theme', 'menu', 'burgerLogo']) .'"></div>' : '' ;?>
					<?php echo template::ico('menu',null,null,'2em'); ?></div>
					<div id="menu" class="container"><?php $this->showMenu(); ?></div>
				</nav>
			<?php endif; ?>
			<?php if(
						$this->getData(['theme', 'header', 'position']) === 'site'
						// Affiche toujours la bannière pour l'édition du thème
						OR (
							$this->getData(['theme', 'header', 'position']) === 'hide'
							AND $this->getUrl(0) === 'theme'
						)
					): ?>
						<!-- Bannière dans le site -->
						<?php echo  ( $this->getData(['theme','header','linkHomePage']) &&  $this->getData(['theme','header','feature']) === 'wallpaper' ) ? '<a href="' . helper::baseUrl(false) . '">' : ''; ?>
						<header class="<?php echo $this->getData(['theme', 'header', 'position']) === 'hide' ? 'displayNone' : ($this->getData(['theme', 'header', 'tinyHidden']) ? ' bannerDisplay' : ''); ?>">
							<?php if ($this->getData(['theme','header','feature']) === 'wallpaper' ): ?>
								<?php if(
									$this->getData(['theme', 'header', 'textHide']) === false
									// Affiche toujours le titre de la bannière pour l'édition du thème
									OR ($this->getUrl(0) === 'theme' AND $this->getUrl(1) === 'header')
								): ?>
									<span class="notranslate" id="themeHeaderTitle"><?php echo $this->getData(['locale', 'title']); ?></span>
								<?php else: ?>
										<span id="themeHeaderTitle">&nbsp;</span>
								<?php endif; ?>
							<?php else: ?>
								<div id="featureContent">
								<?php echo $this->getData(['theme','header','featureContent']);?>
								</diV>
							<?php endif; ?>
						</header>
						<?php echo ( $this->getData(['theme','header','linkHomePage']) &&  $this->getData(['theme','header','feature']) === 'wallpaper' ) ? '</a>' : ''; ?>
				<?php endif; ?>
			<?php if(
				$this->getData(['theme', 'menu', 'position']) === 'site-second' ||
				$this->getData(['theme', 'menu', 'position']) === 'site'
				// Affiche toujours le menu pour l'édition du thème
				OR (
					$this->getData(['theme', 'menu', 'position']) === 'hide'
					AND $this->getUrl(0) === 'theme'
				)
			): ?>
			<!-- Menu dans le site après la bannière -->
			<nav <?php if($this->getData(['theme', 'menu', 'position']) === 'hide'): ?>class="displayNone"<?php endif; ?>>
				<div id="toggle">
				<?php echo $this->getData(['theme','menu','burgerContent']) === 'title'  ? '<div class="notranslate" id="burgerText">' . $this->getData(['locale', 'title']) . '</div>' : '' ;?>
				<?php echo $this->getData(['theme','menu','burgerContent']) === 'logo'   ? '<div class="notranslate" id="burgerLogo"><img src="'.helper::baseUrl(false).self::FILE_DIR.'source/'. $this->getData(['theme', 'menu', 'burgerLogo']) .'"></div>' : '' ;?>
				<?php echo template::ico('menu',null,null,'2em'); ?></div>
				<div id="menu" class="container"><?php $this->showMenu(); ?></div>
			</nav>
			<?php endif; ?>

			<!-- Corps de page -->
			<?php $this->showSection();?>

			<!-- footer -->
			<?php $this->showFooter();?>

		<!-- Fin du site -->
		<?php echo $this->getData(['theme', 'footer', 'position']) === 'site'? '</div>' : '';?>

		<!-- Lien remonter en haut -->
		<div id="backToTop"><?php echo template::ico('up'); ?></div>

		<!-- Les scripts -->
		<?php $this->showScript();?>

	</body>
</html>