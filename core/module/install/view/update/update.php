<p><strong>
	<?php echo template::transcribe('Mise à jour de ZwiiCMS'); ?>
	&nbsp;
	<?php echo self::ZWII_VERSION; ?> 
	<?php echo template::transcribe('vers ZwiiCMS'); ?>
	&nbsp;
	<?php echo $module::$newVersion; ?>.
</strong></p>
<p>
<?php echo template::transcribe('Afin d\'assurer le bon fonctionnement de Zwii, veuillez ne pas fermer cette page avant la fin de l\'opération.'); ?>
</p>
<div class="row">
	<div class="col9 verticalAlignMiddle">
		<div id="installUpdateProgress">
			<?php echo template::ico('spin', ['animate' => true]); ?>
			<span class="installUpdateProgressText" data-id="1">
				<?php echo template::transcribe('1/4 : Préparation...'); ?>
			</span>
			<span class="installUpdateProgressText displayNone" data-id="2">
				<?php echo template::transcribe('2/4 : Téléchargement...'); ?>
			</span>
			<span class="installUpdateProgressText displayNone" data-id="3">
				<?php echo template::transcribe('3/4 : Installation...'); ?>
			</span>
			<span class="installUpdateProgressText displayNone" data-id="4">
				<?php echo template::transcribe('4/4 : Configuration...'); ?>
			</span>
		</div>
		<div id="installUpdateError" class="colorRed displayNone">
			<?php echo template::ico('cancel'); ?>
			<strong>
				<?php echo template::transcribe('Une erreur est survenue lors de l\'étape :'); ?>
				&nbsp;
				<span id="installUpdateErrorStep"> </span>.
			</strong>
		</div>
		<div id="installUpdateSuccess" class="colorGreen displayNone">
			<?php echo template::ico('check'); ?>
			<?php echo template::transcribe('Mise à jour terminée avec succès.'); ?>
		</div>
	</div>
	<div class="col3 verticalAlignTop">
		<?php echo template::button('installUpdateEnd', [
			'value' => 'Terminer',
			'href' => helper::baseUrl() . 'config',
			'ico' => 'check',
			'class' => 'disabled'
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<p><em><span class="colorRed" id="installUpdateErrorMessage"></span></em></p>
	</div>
</div