<?php
echo template::formOpen('galleryUpdateForm'); ?>
<div class="row">
	<div class="col1">
		<?php
echo template::button('galleryUpdateBack', [ 'class' => 'buttonGrey', 'href' => helper::baseUrl() . $this->getUrl(0) . '/config', 'value' => template::ico('left') ]); ?>
	</div>
	<div class="col2 offset9">
		<?php
echo template::submit('galleryUpdateSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Dossier de la galerie</h4>
			<div class="row">
				<div class="col12">
					<?php
echo template::select('galleryUpdateDirectory', slider::$directories, [ 'label' => 'Dossier cible', 'selected' => $this->getData(['module', $this->getUrl(0), 'directory']), ]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
echo template::formClose(); ?>
<div class="moduleVersion">Module Slider version n°
	<?php
echo slider::VERSION; ?>
</div>
