<p>
	<?php echo Helper::translate('Renseignez les champs ci-dessous pour finaliser l\'installation.'); ?>
</p>
<?php echo Template::formOpen('installForm'); ?>
<div class="row">
	<div class="col12">
		<details open>
			<summary>
				<span class="title">
					<?php echo Helper::translate('Compte administrateur'); ?>
				</span>
			</summary>
			<div class="row">
				<div class="col6">
					<?php echo Template::text('installFirstname', [
						'autocomplete' => 'off',
						'label' => 'Prénom'
					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::text('installLastname', [
						'autocomplete' => 'off',
						'label' => 'Nom'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo Template::text('installId', [
						'autocomplete' => 'off',
						'label' => 'Identifiant'
					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::mail('installMail', [
						'autocomplete' => 'off',
						'label' => 'Adresse électronique'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo Template::password('installPassword', [
						'autocomplete' => 'off',
						'label' => 'Mot de passe'
					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::password('installConfirmPassword', [
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
					<?php echo Helper::translate('Options avancées'); ?>
				</span>
			</summary>
			<?php if ($_COOKIE['ZWII_UI'] === 'fr_FR'): ?>
				<div class="row">
					<div class="col12">
						<?php echo Template::checkbox('installDefaultData', true, 'Ne pas charger l\'exemple de site (utilisateurs avancés)', [
							'checked' => false
						]);
						?>
					</div>
				</div>
			<?php endif; ?>
			<div class="row">
				<div class="col3">
					<?php echo Template::select('installProxyType', install::$proxyType, [
						'label' => 'Type de proxy'
					]); ?>
				</div>
				<div class="col6">
					<?php echo Template::text('installProxyUrl', [
						'label' => 'Adresse du proxy',
						'placeholder' => 'cache.proxy.fr'
					]); ?>
				</div>
				<div class="col3">
					<?php echo Template::text('installProxyPort', [
						'label' => 'Port du proxy',
						'placeholder' => '6060'
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col12">
					<?php echo Template::hidden('installLanguage', [
						'value' => $this->getUrl(2)
					]); ?>
				</div>
			</div>
	</div>
</div>
</details>
<div class="row">
	<div class="col2">
		<?php echo Template::button('installPrevious', [
			'class' => 'buttonGrey',
			'href' => Helper::baseUrl(true) . 'install',
			'value' => Template::ico('left')
		]); ?>
	</div>
	<div class="col3 offset7">
		<?php echo Template::submit('installSubmit', [
			'value' => 'Installer'
		]); ?>
	</div>
</div>
<?php echo Template::formClose(); ?>