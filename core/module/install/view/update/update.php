<div id="updateContainer">
	<p><strong>
			<?php echo helper::translate('Version'); ?>
			&nbsp;
			<?php echo self::ZWII_VERSION; ?>
			<?php echo helper::translate('vers'); ?>
			&nbsp;
			<?php echo $module::$newVersion; ?>.
		</strong></p>
	<p>
		<?php echo helper::translate('Afin d\'assurer le bon fonctionnement de Zwii, veuillez ne pas fermer cette page avant la fin de l\'opération.'); ?>
	</p>
	<div class="row">
		<div class="col9 verticalAlignMiddle">
			<div id="installUpdateProgress">
				<?php echo template::ico('spin', ['animate' => true]); ?>
				<span class="installUpdateProgressText" data-id="1">
					<?php echo helper::translate('1/4 : Préparation...'); ?>
				</span>
				<span class="installUpdateProgressText displayNone" data-id="2">
					<?php echo helper::translate('2/4 : Téléchargement...'); ?>
				</span>
				<span class="installUpdateProgressText displayNone" data-id="3">
					<?php echo helper::translate('3/4 : Installation...'); ?>
				</span>
				<span class="installUpdateProgressText displayNone" data-id="4">
					<?php echo helper::translate('4/4 : Configuration...'); ?>
				</span>
			</div>
			<div id="installUpdateError" class="message colorRed displayNone">
				<?php echo template::ico('cancel'); ?>
				<strong>
					<?php echo helper::translate('Une erreur est survenue lors de l\'étape :') . '<br>'; ?>
					<span id="installUpdateErrorStep"> </span>.
				</strong>
			</div>
			<div id="installUpdateSuccess" class="message colorGreen displayNone">
				<?php echo template::ico('check'); ?>
				<?php echo helper::translate('Mise à jour terminée avec succès.'); ?>
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
	</div>
</div>