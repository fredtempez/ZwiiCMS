<?php echo template::formOpen('translateForm'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('translateFormBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl(),
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col1">
			<?php echo template::button('translateHelp', [
				'href' => 'https://doc.zwiicms.fr/prise-en-charge-des-langues-etrangeres',
				'target' => '_blank',
				'value' => template::ico('help'),
				'class' => 'buttonHelp',
				'help' => 'Consulter l\'aide en ligne'
			]); ?>
		</div>
		<div class="col1 offset7">
		<?php echo template::button('translateButton', [
			'href' => helper::baseUrl() . 'translate/copy',
			'value' => template::ico('docs'),
			'disabled' => $module::$siteTranslate,
			'help' => 'Copie de sites inter-langues'
		]); ?>
		</div>
		<div class="col2">
			<?php echo template::submit('translateFormSubmit'); ?>
		</div>
	</div>

	<div class="tab">
		<?php echo template::button('translateFormUIButton', [
			'value' => 'UI',
			'class' => 'buttonTab'
		]); ?>
		<?php echo template::button('translateFormContentButton', [
			'value' => 'Contenu',
			'class' => 'buttonTab'
		]); ?>

	</div>

	<?php include ('core/module/translate/view/ui/ui.php') ?>
	<?php include ('core/module/translate/view/content/content.php') ?>
<?php echo template::formClose(); ?>
