<?php $layout = new layout($this); ?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php $layout->showMetaTitle(); ?>
		<?php $layout->showMetaDescription(); ?>
		<?php $layout->showMetaType(); ?>			
		<?php $layout->showMetaImage(); ?>		
		<?php $layout->showFavicon(); ?>
		<?php $layout->showVendor(); ?>
		<?php $layout->showAnalytics(); ?>	
		<link rel="stylesheet" href="<?php echo helper::baseUrl(false); ?>core/layout/common.css">
		<link rel="stylesheet" href="<?php echo helper::baseUrl(false) . self::DATA_DIR; ?>theme.css?<?php echo md5_file(self::DATA_DIR.'theme.css'); ?>">
		<link rel="stylesheet" href="<?php echo helper::baseUrl(false) . self::DATA_DIR; ?>custom.css?<?php echo md5_file(self::DATA_DIR.'custom.css'); ?>">
		<?php $layout->showStyle(); ?>
		<?php if (file_exists(self::DATA_DIR .'head.inc.html')) {
			include(self::DATA_DIR .'head.inc.html'); 
		}?>
	</head>
	<body>
		<?php $layout->showBar(); ?>
		<?php $layout->showNotification(); ?>
		<?php if($this->getData(['theme', 'menu', 'position']) === 'body-first' || $this->getData(['theme', 'menu', 'position']) === 'top' ): ?>
			<!-- Menu dans le fond du site avant la bannière -->
			<nav
			<?php 
			// Détermine si le menu est fixe en haut de page lorsque l'utilisateur n'est pas connecté
			// 
			if($this->getData(['theme', 'menu', 'position']) === 'top' &&
				$this->getData(['theme', 'menu', 'fixed']) === true) {
					if ($this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD'))
						{echo 'id="navfixedlogout"';}
					elseif ($this->getUrl(0) !== 'theme') 
						{echo 'id="navfixedconnected"';} 
				}
			?>
			>
				<div id="toggle">
				<?php if ($this->getData(['theme','menu','burgerTitle']) === true ): ?>
					<div id="burgerText"><?php echo $this->getData(['config','title']);?></div>
				<?php endif; ?>
				<?php echo template::ico('menu',null,null,'2em'); ?></div>
				<div id="menu" class="
				<?php if($this->getData(['theme', 'menu', 'position']) === 'top'){echo 'container-large';}else{echo'container';}
				?>">

					<?php $layout->showMenu(); ?>
				</div> <!--fin menu -->
			</nav>
		<?php endif; ?>
		<?php if($this->getData(['theme', 'header', 'position']) === 'body'): ?>
			<!-- Bannière dans le fond du site -->
			<header>	
				<?php	
				if ($this->getData(['theme','header','linkHomePage'])){
				echo "<a href='" . helper::baseUrl(false) . "'>" ;}	?>
				<div id="headerContainer" class="container">
				<?php if(
					$this->getData(['theme', 'header', 'textHide']) === false
					// Affiche toujours le titre de la bannière pour l'édition du thème
					OR ($this->getUrl(0) === 'theme' AND $this->getUrl(1) === 'header')
				): ?>
						<span id="themeHeaderTitle"><?php echo $this->getData(['config', 'title']); ?></span>
				<?php else: ?>
						<span id="themeHeaderTitle">&nbsp;</span>
				<?php endif; ?>
				</div> <!--fin container -->
				<?php
				if ($this->getData(['theme','header','linkHomePage'])){echo "</a>";}
				?>	
			</header>
		<?php endif; ?>

		<?php if($this->getData(['theme', 'menu', 'position']) === 'body-second'): ?>
			<!-- Menu dans le fond du site après la bannière -->
			<nav>
				<div id="toggle">
				<?php if ($this->getData(['theme','menu','burgerTitle']) === true ): ?>
					<div id="burgerText"><?php echo $this->getData(['config','title']);?></div>
				<?php endif; ?>
				<?php echo template::ico('menu',null,null,'2em'); ?></div>
				<div id="menu" class="container"><?php $layout->showMenu(); ?></div>
			</nav>
		<?php endif; ?>
		<!-- Site -->
		<div id="site" class="container">
			<?php if($this->getData(['theme', 'menu', 'position']) === 'site-first'): ?>
				<!-- Menu dans le site avant la bannière -->
				<nav>
					<div id="toggle">
					<?php if ($this->getData(['theme','menu','burgerTitle']) === true ): ?>
						<div id="burgerText"><?php echo $this->getData(['config','title']);?></div>
					<?php endif; ?>
					<?php echo template::ico('menu',null,null,'2em'); ?></div>
					<div id="menu" class="container"><?php $layout->showMenu(); ?></div>
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
				<?php	
				if ($this->getData(['theme','header','linkHomePage'])){
				echo "<a href='" . helper::baseUrl(false) . "'>" ;}	?>
				<header <?php if($this->getData(['theme', 'header', 'position']) === 'hide'): ?>class="displayNone"<?php endif; ?>>
					<div id="headerContainer" class="container">
						<?php if(
							$this->getData(['theme', 'header', 'textHide']) === false
							// Affiche toujours le titre de la bannière pour l'édition du thème
							OR ($this->getUrl(0) === 'theme' AND $this->getUrl(1) === 'header')
						): ?>
							<span id="themeHeaderTitle"><?php echo $this->getData(['config', 'title']); ?></span>
						<?php else: ?>
								<span id="themeHeaderTitle">&nbsp;</span>
						<?php endif; ?>
					</div> <!--fin container -->
				</header>
				<?php
				if ($this->getData(['theme','header','linkHomePage'])){echo "</a>";}	?>
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
				<?php if ($this->getData(['theme','menu','burgerTitle']) === true ): ?>
						<div id="burgerText"><?php echo $this->getData(['config','title']);?></div>
					<?php endif; ?>
				<?php echo template::ico('menu',null,null,'2em'); ?></div>
				<div id="menu" class="container"><?php $layout->showMenu(); ?></div>
			</nav>
			<?php endif; ?>
			<!-- Corps de page -->
			<section>
			<?php 
				// Gabarit :
				// Récupérer la config de la page courante
				$blocks = explode('-',$this->getData(['page',$this->getUrl(0),'block']));
				// Initialiser
				$blockleft=$blockright="";
				switch (sizeof($blocks)) {
					case 1 :  // une colonne
						$content    = 'col'. $blocks[0] ; 
						break;			
					case 2 :  // 2 blocks 
						if ($blocks[0] < $blocks[1]) { // détermine la position de la colonne
							$blockleft = 'col'. $blocks[0];
							$content    = 'col'. $blocks[1] ;
						} else {
							$content    = 'col' . $blocks[0];
							$blockright  = 'col' . $blocks[1];						
						}
					break;
					case 3 :  // 3 blocks
							$blockleft  = 'col' . $blocks[0];
							$content    = 'col' . $blocks[1];
							$blockright = 'col' . $blocks[2];	
				}
				// Page pleine pour la configuration des modules et l'édition des pages sauf l'affichae d'un article de blog
				$pattern = ['config','edit','add','comment','data'];
				if ((sizeof($blocks) === 1 || 
					in_array($this->getUrl(1),$pattern)  )
					) { // Pleine page en mode configuration
						$layout->showContent();
						if (file_exists(self::DATA_DIR . 'body.inc.html')) {
							include( self::DATA_DIR . 'body.inc.html'); 
						}				
				} else {
				?>
				<div class="row siteContainer"> 
					<?php 
						if ($blockleft !== "") :?> 
						<div class="<?php echo $blockleft; ?>" id="contentLeft"><?php 	$layout->showBarContentLeft(); ?></div> 
						<?php endif; ?>
						<div class="<?php echo $content; ?>" id="contentSite"><?php $layout->showContent();
							if (file_exists(self::DATA_DIR . 'body.inc.html')) {
								include(self::DATA_DIR . 'body.inc.html'); 
							}
						?>
						</div>
					<?php 
						if ($blockright !== "") :?> 
						<div class="<?php echo $blockright; ?>" id="contentRight"><?php $layout->showBarContentRight(); ?></div>
						<?php endif; ?>	
				</div>
				<?php }
				?>
			</section>
			<!-- footer -->
			<?php
			// Déterminer la position
			$positionFixed = '';
			if(
				$this->getData(['theme', 'footer', 'position']) === 'site'
				// Affiche toujours le pied de page pour l'édition du thème
				OR (
					$this->getData(['theme', 'footer', 'position']) === 'hide'
					AND $this->getUrl(0) === 'theme'
				)
			) 	{	$position = 'site';						
			  	} else {
					$position = 'body';
					if ( $this->getData(['theme', 'footer', 'fixed']) === true) {
						$positionFixed = 'footerbodyFixed';
					}
 					echo '</div>';
			}
			?>
			<!-- Pied de page -->		
			<footer <?php if($this->getData(['theme', 'footer', 'position']) === 'hide'): ?>class="displayNone"<?php endif; ?>>
				<?php
				if ($position === 'site'): ?>
					<div class="container"><div class="row" id="footersite">
				<?php else: ?>
					<div class="container-large <?php echo $positionFixed; ?>"><div class="row" id="footerbody">
				<?php endif?>
					<!-- Mise en page -->
					<?php switch($this->getData(['theme', 'footer', 'template'])) {
						case '1' :
							$class['left'] 	 = "displayNone";
							$class['center'] = "col12";
							$class['right']  = "displayNone";
							break;
						case '2' :
							$class['left'] 	 = "col6";
							$class['center'] = "displayNone";
							$class['right']  = "col6";
							break;
						case '3' :
							$class['left'] 	 = "col4";
							$class['center'] = "col4";
							$class['right']  = "col4";
							break;
						case '4' :
							$class['left'] 	 = "col12";
							$class['center'] = "col12";
							$class['right']  = "col12";
							break;
					}?>
						<div class="<?php echo $class['left'];?>" id="footer<?php echo $position;?>Left">
							<?php if($this->getData(['theme', 'footer', 'textPosition']) === 'left') { $layout->showFooterText(); }
									if($this->getData(['theme', 'footer', 'socialsPosition']) === 'left') {	$layout->showSocials(); }
									if($this->getData(['theme', 'footer', 'copyrightPosition']) === 'left') {$layout->showCopyright(); }
							?>
						</div>
						<div class="<?php echo $class['center'];?>" id="footer<?php echo $position;?>Center">
							<?php if($this->getData(['theme', 'footer', 'textPosition']) === 'center') { $layout->showFooterText(); }
									if($this->getData(['theme', 'footer', 'socialsPosition']) === 'center') { $layout->showSocials(); }
									if($this->getData(['theme', 'footer', 'copyrightPosition']) === 'center') { $layout->showCopyright(); }
							?>
						</div>
						<div class="<?php echo $class['right'];?>" id="footer<?php echo $position;?>Right">
							<?php if($this->getData(['theme', 'footer', 'textPosition']) === 'right') { $layout->showFooterText(); }
									if($this->getData(['theme', 'footer', 'socialsPosition']) === 'right') { $layout->showSocials(); }
									if($this->getData(['theme', 'footer', 'copyrightPosition']) === 'right') { $layout->showCopyright(); }
							?>
						</div>
					</div>
				</div>
			</footer>
		<?php
		if ($this->getData(['theme', 'footer', 'position']) === 'site') {
			echo '</div>';
		} ?>
		<!-- Lien remonter en haut -->
		<div id="backToTop"><?php echo template::ico('up'); ?></div>
		<?php $layout->showScript();?>
</body>
</html>
