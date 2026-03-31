<?php
if ( $this->getData(['theme', 'header', 'position']) === 'hide' or $this->getData(['theme', 'menu', 'position']) === 'hide' or $this->getData(['theme', 'footer', 'position']) === 'hide' ): ?>
	<?php
echo Template::speech('Cliquez sur une zone afin d\'accéder à ses options de personnalisation. Vous pouvez également afficher les zones cachées à l\'aide du bouton ci-dessous.'); ?>
	<div class="row">
		<div class="col4 offset4">
			<?php
echo Template::button('themeBack', [ 'href' => Helper::baseUrl(false), 'ico' => 'home', 'value' => 'Accueil' ]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col2 offset4">
			<?php
echo Template::button('themeFonts', [ 'ico' => 'font', 'href' => Helper::baseUrl() . $this->getUrl(0) . '/font', 'value' => 'Fontes' ]); ?>
		</div>
		<div class="col2">
			<?php
echo Template::button('themeAdvanced', [ 'ico' => 'code', 'href' => Helper::baseUrl() . $this->getUrl(0) . '/advanced', 'value' => 'Éditeur CSS' ]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col2 offset4">
			<?php
echo Template::button('themeAdmin', [ 'ico' => 'brush', 'href' => Helper::baseUrl() . $this->getUrl(0) . '/admin', 'value' => 'Administration' ]); ?>
		</div>
		<div class="col2">
			<?php
echo Template::button('themeManage', [ 'ico' => 'sliders', 'href' => Helper::baseUrl() . $this->getUrl(0) . '/manage', 'value' => 'Gestion' ]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col4 offset4">
			<?php
echo Template::button('themeShowAll', [ 'ico' => 'eye', 'value' => 'Zones cachées' ]); ?>
		</div>
	</div>
<?php
else: ?>
	<?php
echo Template::speech('Cliquez sur une zone afin d\'accéder à ses options de personnalisation.'); ?>
	<div class="row">
		<div class="col4 offset4">
			<?php
echo Template::button('themeBack', [ 'href' => Helper::baseUrl(false), 'ico' => 'home', 'value' => 'Accueil' ]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col2 offset4">
			<?php
echo Template::button('themeFonts', [ 'ico' => 'font', 'href' => Helper::baseUrl() . $this->getUrl(0) . '/font', 'value' => 'Fontes' ]); ?>
		</div>
		<div class="col2">
			<?php
echo Template::button('themeAdvanced', [ 'ico' => 'code', 'href' => Helper::baseUrl() . $this->getUrl(0) . '/advanced', 'value' => 'Éditeur CSS' ]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col2 offset4">
			<?php
echo Template::button('themeAdmin', [ 'ico' => 'brush', 'href' => Helper::baseUrl() . $this->getUrl(0) . '/admin', 'value' => 'Administration' ]); ?>
		</div>
		<div class="col2">
			<?php
echo Template::button('themeManage', [ 'ico' => 'sliders', 'href' => Helper::baseUrl() . $this->getUrl(0) . '/manage', 'value' => 'Gestion' ]); ?>
		</div>
	</div>
<?php
endif; ?>
