<?php echo template::formOpen('userLoginForm'); ?>
	<div class="row">
		<div class="col6">
			<?php echo template::text('userLoginId', [
				'label' => 'Identifiant',
				'value' => $module::$userId
			]); ?>
		</div>
		<div class="col6">
			<?php echo template::password('userLoginPassword', [
				'label' => 'Mot de passe'
			]); ?>
		</div>
	</div>
	<?php if ($this->getData(['config', 'connect','captcha'])): ?>
		<div class="row">
			<div class="col12 textAlignCenter">
				<?php echo template::captcha('userLoginCaptcha', [
					'limit' => $this->getData(['config','captchaStrong'])
				]); ?>
			</div>
		</div>
	<?php endif;?>
	<div class="row">
		<div class="col6">
			<?php echo template::checkbox('userLoginLongTime', true, 'Se souvenir de moi', [
				'checked' => $module::$userLongtime
			]);	?>
		</div>
		<div class="col6 textAlignRight">
			<a href="<?php echo helper::baseUrl(); ?>user/forgot/<?php echo $this->getUrl(2); ?>">Mot de passe perdu ?</a>
		</div>
	</div>
	<div class="row">
		<div class="col3 offset6">
			<?php echo template::button('userLoginBack', [
				'href' => helper::baseUrl() . str_replace('_', '/', str_replace('__', '#', $this->getUrl(2))),
				'ico' => 'left',
				'value' => 'Annuler'
			]); ?>
		</div>
		<div class="col3">
			<?php echo template::submit('userLoginSubmit', [
				'value' => 'Connexion',
				'ico' => 'lock'
			]); ?>
		</div>
	</div>
<?php echo template::formClose(); ?>