<?php echo template::formOpen('userLoginForm'); ?>
<div class="row">
	<div class="col6">
		<?php echo template::text('userLoginId', [
			'label' => 'Identifiant',
			'value' => $module::$userId
		]); ?>
	</div>
	<div class="col6">
		<?php if ($this->getData(['config', 'connect', 'showPassword']) === true) {
			$passwordLabel = '<span id="passwordLabel">' . helper::translate('Mot de passe') . '</span><span id="passwordIcon">' .  template::ico('eye') . '</span>';
		} else {
			$passwordLabel = helper::translate('Mot de passe');
		}
		?>
		<?php echo template::password('userLoginPassword', [
			'label' => $passwordLabel
		]); ?>
	</div>
</div>
<?php if ($this->getData(['config', 'connect', 'captcha'])) : ?>
	<div class="row">
		<div class="col12 textAlignCenter">
			<?php echo template::captcha('userLoginCaptcha', [
				'limit' => $this->getData(['config', 'connect', 'captchaStrong']),
				'type' => $this->getData(['config', 'connect', 'captchaType'])
			]); ?>
		</div>
	</div>
<?php endif; ?>
<div class="row">
	<div class="col8">
		<?php echo template::checkbox('userLoginLongTime', true, 'Rester connecté sur ce navigateur', [
			'checked' => $module::$userLongtime
		]);	?>
	</div>
	<div class="col4 textAlignRight">
		<a href="<?php echo helper::baseUrl(); ?>user/forgot/<?php echo $this->getUrl(2);?>"><?php echo helper::translate('Mot de passe perdu'); ?></a>
	</div>
</div>
<div class="row" id="buttonsContainer">
	<div class="col2" id="backContainer">
		<?php echo template::button('userLoginBack', [
			'href' => $this->getUrl(2) ? helper::baseUrl() . str_replace('_', '/', str_replace('__', '#', $this->getUrl(2))) : helper::baseUrl(),
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset8" id="loginContainer">
		<?php echo template::submit('userLoginSubmit', [
            'value' => template::ico('check'),
            'ico' => '',
		]); ?>
	</div>
</div>
<?php echo template::formClose(); ?>