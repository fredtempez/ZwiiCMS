<?php
$sliderHeightMode = slider::$selectedHeightMode; $sliderShowHeightPx = $sliderHeightMode === 'fixed' ? '' : 'displayNone'; $sliderShowRatio = $sliderHeightMode === 'ratio' ? '' : 'displayNone'; $sliderShowFit = in_array($sliderHeightMode, ['fixed', 'ratio'], true) ? '' : 'displayNone'; ?>
<?php
echo template::formOpen('sliderThemeForm'); ?>
<div class="row">
	<div class="col1">
		<?php
echo template::button('sliderThemeBack', [ 'class' => 'buttonGrey', 'href' => helper::baseUrl() . $this->getUrl(0) . '/config', 'value' => template::ico('left') ]); ?>
	</div>
	<div class="col2 offset9">
		<?php
echo template::submit('sliderThemeSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Paramètres</h4>
			<div class="row">
				<div class="col4">
					<?php
echo template::select('sliderThemeAuto', slider::$auto, [ 'label' => 'Automatisation', 'selected' => $this->getData(['module', $this->getUrl(0), 'theme', 'auto']), ]); ?>
				</div>
				<div class="col4">
					<?php
echo template::select('sliderThemeDiapoTime', slider::$timeout, [ 'label' => 'Image fixe', 'selected' => $this->getData(['module', $this->getUrl(0), 'theme', 'timeout']) ]); ?>
				</div>
				<div class="col4">
					<?php
echo template::select('sliderThemespeed', slider::$speed, [ 'label' => 'Transition ', 'help' => 'Cette durée doit être inférieure au temps fixe', 'selected' => $this->getData(['module', $this->getUrl(0), 'theme', 'speed']) ]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php
echo template::select('sliderThememaxWidth', slider::$screenWidth, [ 'label' => 'Largeur', 'selected' => slider::$selectedMaxwidth, ]); ?>
				</div>
				<div class="col3">
					<?php
echo template::select('sliderThemeHeightMode', slider::$heightMode, [ 'label' => 'Rapport de dimensions', 'selected' => slider::$selectedHeightMode, ]); ?>
				</div>
				<div class="col3 <?php
echo $sliderShowHeightPx; ?>" id="sliderThemeHeightPxCol">
					<?php
echo template::select('sliderThemeHeightPx', slider::$heightPx, [ 'label' => 'Hauteur fixe', 'selected' => slider::$selectedHeightPx, ]); ?>
				</div>
				<div class="col3 <?php
echo $sliderShowRatio; ?>" id="sliderThemeHeightRatioCol">
					<?php
echo template::select('sliderThemeHeightRatio', slider::$heightRatio, [ 'label' => 'Ratio', 'selected' => slider::$selectedHeightRatio, ]); ?>
				</div>
				<div class="col3 <?php
echo $sliderShowFit; ?>" id="sliderThemeFitModeCol">
					<?php
echo template::select('sliderThemeFitMode', slider::$fitMode, [ 'label' => 'Ajustement', 'help' => 'Mode d\'ajustement de l\'image dans le cadre (hauteur fixe ou ratio)', 'selected' => slider::$selectedFitMode, ]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Navigation</h4>
			<div class="row">
				<div class="col3">
					<?php
echo template::select('sliderThemeSort', slider::$sort, [ 'label' => 'Tri des images', 'selected' => $this->getData(['module', $this->getUrl(0), 'theme', 'sort']) ]); ?>
				</div>
				<div class="col3">
					<?php
echo template::select('sliderThemePager', slider::$pager, [ 'label' => 'Puces horizontales', 'selected' => $this->getData(['module', $this->getUrl(0), 'theme', 'pager']), ]); ?>
				</div>
				<div class="col3">
					<?php
echo template::select('sliderThemeNameSpace', slider::$namespace, [ 'label' => 'Boutons latéraux', 'selected' => $this->getData(['module', $this->getUrl(0), 'theme', 'namespace']) ]); ?>
				</div>
				<div class="col3">
					<?php
echo template::select('sliderThemeCaption', slider::$caption, [ 'label' => 'Légendes', 'selected' => $this->getData(['module', $this->getUrl(0), 'theme', 'caption']) ]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php
echo template::formClose(); ?>
