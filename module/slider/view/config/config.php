<?php echo template::formOpen('galleryConfigForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('galleryConfigBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0) . '/' . self::$siteContent,
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1 offset7">
		<?php echo template::button('galleryConfigTheme', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/theme',
			'value' => template::ico('brush')
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('galleryConfigUpdate', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/update',
			'value' => template::ico('folder')
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::submit('galleryConfigSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Galerie  
				<?php
				echo $this->getData(['module', $this->getUrl(0), 'directory']);
				?>
			</h4>
			<div class="row">
				<div class="col12">

				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php if ($module::$pictures): ?>
						<?php echo template::table([3, 4, 4, 1], $module::$pictures, ['Image', 'Texte alternatif', 'Hyperlien vers une page', '']); ?>
					<?php else: ?>
						<?php echo template::speech('Aucune image dans ce dossier'); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Module Slider version n°
	<?php echo $module::VERSION; ?>
</div>