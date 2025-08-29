<?php echo template::formOpen('redirectionConfig'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('redirectionConfigBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0) . '/' . self::$siteContent,
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('redirectionConfigSubmit'); ?>
	</div>
</div>
<div class="row">
	<div class="col6">
		<div class="block">
			<h4>
				<?php echo helper::translate('Paramètres'); ?>
			</h4>
			<?php echo template::text('redirectionConfigUrl', [
				'label' => 'Lien de redirection',
				'placeholder' => 'http://',
				'value' => $this->getData(['module', $this->getUrl(0), 'url']),
				'help' => 'Le lien de redirection peut contenir une URL standard, ou pointer vers l\'ancre d\'une page du site'
			]); ?>
		</div>
	</div>
	<div class="col6">
		<div class="block">
			<h4>
				<?php echo helper::translate('Statistiques'); ?>
			</h4>
			<?php echo template::text('redirectionConfigCount', [
				'disabled' => true,
				'label' => 'Nombre de redirections',
				'value' => helper::filter($this->getData(['module', $this->getUrl(0), 'count']), helper::FILTER_INT)
			]); ?>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>