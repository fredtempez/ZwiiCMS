<div id="updateContainer">
	<p><strong>
			<?php
echo Helper::translate('Version'); ?>
			&nbsp;
			<?php
echo self::ZWII_VERSION; ?>
			<?php
echo Helper::translate('vers'); ?>
			&nbsp;
			<?php
echo install::$newVersion; ?>
		</strong></p>
	<p>
		<?php
echo Helper::translate('Afin d\'assurer le bon fonctionnement de Zwii, veuillez ne pas fermer cette page avant la fin de l\'opération.'); ?>
	</p>
	<div class="row">
		<div class="col9 verticalAlignMiddle">
			<div id="installUpdateProgress">
				<?php
echo Template::ico('spin', ['animate' => true]); ?>
				<span class="installUpdateProgressText" data-id="1">
					<?php
echo Helper::translate('1/4 : Préparation...'); ?>
				</span>
				<span class="installUpdateProgressText displayNone" data-id="2">
					<?php
echo Helper::translate('2/4 : Téléchargement...'); ?>
				</span>
				<span class="installUpdateProgressText displayNone" data-id="3">
					<?php
echo Helper::translate('3/4 : Installation...'); ?>
				</span>
				<span class="installUpdateProgressText displayNone" data-id="4">
					<?php
echo Helper::translate('4/4 : Configuration...'); ?>
				</span>
			</div>
			<div id="installUpdateError" class="message colorRed displayNone">
				<?php
echo Template::ico('cancel'); ?>
				<strong>
					<?php
echo Helper::translate('Une erreur est survenue lors de l\'étape :') . '<br>'; ?>
					<span id="installUpdateErrorStep"> </span>.
				</strong>
			</div>
			<div id="installUpdateSuccess" class="message colorGreen displayNone">
				<?php
echo Template::ico('check'); ?>
				<?php
echo Helper::translate('Mise à jour terminée avec succès.'); ?>
			</div>
			<div id="installUpdateMessage" class="displayNone" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
				<div style="background: #fff; padding: 20px; border-radius: 8px; max-width: 600px; max-height: 80vh; overflow: auto; margin: auto; position: relative; top: 10%;">
					<h4>Message de mise à jour</h4>
					<div id="installUpdateMessageBody"></div>
					<div style="text-align: right; margin-top: 15px;">
						<a href="#" id="installUpdateMessageClose" class="button">Fermer</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col3 verticalAlignTop">
			<?php
echo Template::button('installUpdateEnd', [ 'value' => 'Terminer', 'href' => Helper::baseUrl() . 'config', 'ico' => 'check', 'class' => 'disabled' ]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<p><em><span class="colorRed" id="installUpdateErrorMessage"></span></em></p>
		</div>
	</div>
</div>
