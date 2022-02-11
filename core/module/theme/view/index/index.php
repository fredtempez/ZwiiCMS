<?php if(
	$this->getData(['theme', 'header', 'position']) === 'hide'
	OR $this->getData(['theme', 'menu', 'position']) === 'hide'
	OR $this->getData(['theme', 'footer', 'position']) === 'hide'
): ?>
	<?php echo template::speech('Cliquez sur une zone afin d\'accéder à ses options de personnalisation. Vous pouvez également afficher les zones cachées à l\'aide du bouton ci-dessous.'); ?>
	<div class="row">
		<div class="col2 offset3">
			<?php echo template::button('themeBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl(false),
				'ico' => 'home',
				'value' => 'Accueil'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::button('themeHelp', [
				'href' => 'https://doc.zwiicms.fr/theme-2',
				'target' => '_blank',
				'ico' => 'help',
				'value' => 'Aide',
				'class' => 'buttonHelp'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::button('themeShowAll', [
				'ico' => 'eye',
				'value' => 'Zones cachées'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col2 offset2">
			<?php echo template::button('themeFonts', [
				'ico' => 'code',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/fonts',
				'value' => 'Fontes'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::button('themeManage', [
				'ico' => 'sliders',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/manage',
				'value' => 'Gestion'
			]); ?>
		</div>
		<div class="col2">
		<?php echo template::button('themeAdmin', [
				'ico' => 'brush',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/admin',
				'value' => 'Administration'
			]); ?>

		</div>
		<div class="col2">
			<?php echo template::button('themeAdvanced', [
				'ico' => 'code',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/advanced',
				'value' => 'Éditeur CSS'
			]); ?>
		</div>
	</div>
<?php else: ?>
	<?php echo template::speech('Cliquez sur une zone afin d\'accéder à ses options de personnalisation.'); ?>
	<div class="row">
		<div class="col2 offset4">
			<?php echo template::button('themeBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl(false),
				'ico' => 'home',
				'value' => 'Accueil'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::button('themeHelp', [
				'href' => 'https://doc.zwiicms.fr/theme-2',
				'target' => '_blank',
				'ico' => 'help',
				'value' => 'Aide',
				'class' => 'buttonHelp'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col2 offset2">
			<?php echo template::button('themeFonts', [
				'ico' => 'code',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/fonts',
				'value' => 'Fontes'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::button('themeManage', [
				'ico' => 'sliders',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/manage',
				'value' => 'Gestion'
			]); ?>
		</div>
		<div class="col2">
		<?php echo template::button('themeAdmin', [
				'ico' => 'brush',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/admin',
				'value' => 'Administration'
			]); ?>
		</div>
		<div class="col2">
			<?php echo template::button('themeAdvanced', [
				'ico' => 'code',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/advanced',
				'value' => 'Éditeur CSS'
			]); ?>
		</div>
	</div>
<?php endif; ?>
