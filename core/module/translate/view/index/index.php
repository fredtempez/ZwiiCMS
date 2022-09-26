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
		<?php echo template::button('translateUiButton', [
			'value' => 'Langue de l\'interface',
			'class' => 'buttonTab'
		]); ?>
		<?php echo template::button('translateContentButton', [
			'value' => 'Langues du contenu',
			'class' => 'buttonTab'
		]); ?>

	</div>

	<div id="uiContainer" class="tabContent">
		<div class="row">
			<div class="col12">
				<div class="block">
					<h4>
						<?php echo template::topic('Langue de l\'administration'); ?>
					</h4>
					<div class="row">
						<div class="col4 offset4">
							<?php echo template::select('translateUI', $module::$i18nFiles, [
								'label' =>  'Traductions installées',
								'selected' => $this->getData(['config', 'i18n' , 'interface']),
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="contentContainer" class="tabContent">
		<div class="row">
			<div class="col12">
				<div class="block">
				<h4>
					<?php echo template::topic('Ajouter une langue'); ?>
				</h4>
					<div class="row">
						<div class="col12">
								Poouet !
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col12">
				<?php if($module::$languagesInstalled): ?>
					<?php echo template::table([2, 2, 6, 1, 1], $module::$languagesInstalled, ['Langue', '', '', '', '']); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php echo template::formClose(); ?>
