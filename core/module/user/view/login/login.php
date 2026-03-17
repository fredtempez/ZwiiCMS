<?php echo Template::formOpen('userLoginForm'); ?>
<div class="row">
	<div class="col6">
		<?php echo Template::text('userLoginId', [
			'label' => 'Identifiant',
			'value' => user::$userId
		]); ?>
	</div>
	<div class="col6">
		<?php if ($this->getData(['config', 'connect', 'showPassword']) === true) {
			$passwordLabel = '<span id="passwordLabel">' . Helper::translate('Mot de passe') . '</span><span id="passwordIcon">' .  Template::ico('eye') . '</span>';
		} else {
			$passwordLabel = Helper::translate('Mot de passe');
		}
		?>
		<?php echo Template::password('userLoginPassword', [
			'label' => $passwordLabel
		]); ?>
	</div>
</div>
<?php if ($this->getData(['config', 'connect', 'captcha'])) : ?>
	<div class="row">
		<div class="col12 textAlignCenter">
			<?php echo Template::captcha('userLoginCaptcha', [
				'limit' => $this->getData(['config', 'connect', 'captchaStrong']),
				'type' => $this->getData(['config', 'connect', 'captchaType'])
			]); ?>
		</div>
	</div>
<?php endif; ?>
<div class="row">
	<div class="col8">
		<?php echo Template::checkbox('userLoginLongTime', true, Helper::translate('Rester connecté sur ce navigateur'), [
			'checked' => user::$userLongtime
		]);	?>
	</div>
	<div class="col4 textAlignRight">
		<a href="<?php echo Helper::baseUrl(); ?>user/forgot/<?php echo $this->getUrl(2);?>"><?php echo Helper::translate('Mot de passe perdu'); ?></a>
	</div>
</div>
<div class="row" id="buttonsContainer">
	<div class="col2" id="backContainer">
		<?php echo Template::button('userLoginBack', [
			'href' => $this->getUrl(2) ? Helper::baseUrl() . str_replace('_', '/', str_replace('__', '#', $this->getUrl(2))) : Helper::baseUrl(),
			'value' => Template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset8" id="loginContainer">
		<?php echo Template::submit('userLoginSubmit', [
            'value' => Template::ico('check'),
            'ico' => '',
		]); ?>
	</div>
</div>
<?php echo Template::formClose(); ?>