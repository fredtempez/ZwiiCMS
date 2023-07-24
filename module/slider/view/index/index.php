<?php if ($module::$pictures): ?>
	<div id="wrapper">
		<div class="rslides_container">
			<ul class="rslides" id="sliders">
				<!--id="<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'boutonsVisibles']); ?>"> -->
				<?php foreach ($module::$pictures as $picture => $options): ?>
					<?php if (!empty($options['uri'])): ?>
						<a href="<?php echo helper::baseUrl() . $options['uri']; ?>">
					<?php endif; ?>
					<li>
						<img src="<?php echo helper::baseUrl(false) . $picture; ?>" alt="<?php echo $options['legend']; ?>">
					</li>
					<?php if (!empty($options['uri'])): ?>
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	</div>
<?php else: ?>
	<?php echo template::speech('Aucune image dans le dossier sélectionné.'); ?>
<?php endif; ?>
<div id="div1">
</div>
<!--Pour liaison entre variables php et javascript-->