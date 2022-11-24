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
		<?php /**echo template::button('translateHelp', [
				'href' => 'https://doc.zwiicms.fr/prise-en-charge-des-langues-etrangeres',
				'target' => '_blank',
				'value' => template::ico('help'),
				'class' => 'buttonHelp',
				'help' => 'Consulter l\'aide en ligne'
			]);*/ ?>
	</div>
	<div class="col1 offset8">
		<?php echo template::button('translateButtonAddContent', [
			'href' => helper::baseUrl() . 'translate/add',
			'value' => template::ico('plus'),
			'class' => 'buttonGreen',
			'help' => 'Nouveau contenu localisé'
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('translateButtonCopyContent', [
			'href' => helper::baseUrl() . 'translate/copy',
			'value' => template::ico('docs'),
			'disabled' => $module::$siteCopy,
			'help' => 'Copie de contenus localisés'
		]); ?>
	</div>
</div>

<div class="tab">
<?php echo template::button('translateContentButton', [
		'value' => 'Site',
		'class' => 'buttonTab'
	]); ?>
	<?php echo template::button('translateUiButton', [
		'value' => 'Interface',
		'class' => 'buttonTab'
	]); ?>
</div>

<div id="uiContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<?php if ($module::$languagesUiInstalled) : ?>
				<?php echo template::table([3, 1, 1, 6, 1], $module::$languagesUiInstalled, ['Langues', '', '', '', '']); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<div id="contentContainer" class="tabContent">
	<div class="row">
		<div class="col12">
			<?php if ($module::$languagesInstalled) : ?>
				<?php echo template::table([1, 3, 6, 1, 1], $module::$languagesInstalled, ['Langues', '', '', '', '']); ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php echo template::formClose(); ?>