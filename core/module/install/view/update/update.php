<p><strong>
	<?php echo template::topic('Mise à jour de ZwiiCMS'); ?>
	&nbsp;
	<?php echo self::ZWII_VERSION; ?> 
	<?php echo template::topic('vers ZwiiCMS'); ?>
	&nbsp;
	<?php echo $module::$newVersion; ?>.
</strong></p>
<p>
<?php echo template::topic('Afin d\'assurer le bon fonctionnement de Zwii, veuillez ne pas fermer cette page avant la fin de l\'opération.'); ?>
</p>
<div class="row">
	<div class="col9 verticalAlignMiddle">
		<div id="installUpdateProgress">
			<?php echo template::ico('spin', ['animate' => true]); ?>
			<span class="installUpdateProgressText" data-id="1">
				<?php echo template::topic('1/4 : Préparation...'); ?>
			</span>
			<span class="installUpdateProgressText displayNone" data-id="2">
				<?php echo template::topic('2/4 : Téléchargement...'); ?>
			</span>
			<span class="installUpdateProgressText displayNone" data-id="3">
				<?php echo template::topic('3/4 : Installation...'); ?>
			</span>
			<span class="installUpdateProgressText displayNone" data-id="4">
				<?php echo template::topic('4/4 : Configuration...'); ?>
			</span>
		</div>
		<div id="installUpdateError" class="colorRed displayNone">
			<?php echo template::ico('cancel'); ?>
			<strong>
				<?php echo template::topic('Une erreur est survenue lors de l\'étape :'); ?>
				&nbsp;
				<span id="installUpdateErrorStep"> </span>.
			</strong>
		</div>
		<div id="installUpdateSuccess" class="colorGreen displayNone">
			<?php echo template::ico('check'); ?>
			<?php echo template::topic('Mise à jour terminée avec succès.'); ?>
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