<p>
	<?php echo helper::translate('Renseignez les champs ci-dessous pour finaliser l\'installation.'); ?>
</p>
<?php echo template::formOpen('installForm'); ?>
<div class="row">
	<div class="col12">
		<details open>
			<summary>
				<span class="title">
					<?php echo helper::translate('Compte administrateur'); ?>
				</span>
			</summary>
			<div class="row">
				<div class="col6">
					<?php echo template::text('installFirstname', [
						'autocomplete' => 'off',
						'label' => 'Prénom'
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::text('installLastname', [
						'autocomplete' => 'off',
						'label' => 'Nom'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::text('installId', [
						'autocomplete' => 'off',
						'label' => 'Identifiant'
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::mail('installMail', [
						'autocomplete' => 'off',
						'label' => 'Adresse électronique'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::password('installPassword', [
						'autocomplete' => 'off',
						'label' => 'Mot de passe'
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::password('installConfirmPassword', [
						'autocomplete' => 'off',
						'label' => 'Confirmation'
					]); ?>
				</div>
			</div>
		</details>
	</div>
</div>
<div class="row">
	<div class="col12">
		<details close>
			<summary>
				<span class="title">
					<?php echo helper::translate('Options avancées'); ?>
				</span>
			</summary>
			<?php if ($_SESSION['ZWII_UI'] === 'fr_FR'): ?>
				<div class="row">
					<div class="col12">
						<?php echo template::checkbox('installDefaultData', true, 'Ne pas charger l\'exemple de site (utilisateurs avancés)', [
							'checked' => false
						]);
						?>
					</div>
				</div>
			<?php endif; ?>
			<div class="row">
				<div class="col3">
					<?php echo template::select('installProxyType', $module::$proxyType, [
						'label' => 'Type de proxy'
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::text('installProxyUrl', [
						'label' => 'Adresse du proxy',
						'placeholder' => 'cache.proxy.fr'
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::text('installProxyPort', [
						'label' => 'Port du proxy',
						'placeholder' => '6060'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo template::select('installTheme', $module::$themes, [
						'label' => 'Thème'
					]); ?>
					<?php echo template::hidden('installLanguage', [
						'value' => $this->getUrl(2)
					]); ?>
				</div>
			</div>
	</div>
</div>
</details>
<div class="row">
	<div class="col2">
		<?php echo template::button('installPrevious', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(true) . '?install',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col3 offset7">
		<?php echo template::submit('installSubmit', [
			'value' => 'Installer'
		]); ?>
	</div>
</div>
<?php echo template::formClose(); ?>