<?php if ($this->getData(['module', $this->getUrl(0), 'input'])): ?>
	<div class="row <?php echo $this->getData(['module', $this->getUrl(0), 'config', 'align']); ?>">
		<div class="<?php
		echo 'col' . $this->getData(['module', $this->getUrl(0), 'config', 'width']) . ' ';
		echo $this->getData(['module', $this->getUrl(0), 'config', 'offset']) !== 0 ? 'offset' . $this->getData(['module', $this->getUrl(0), 'config', 'offset']) : '';
		?>">
			<?php echo template::formOpen('formForm'); ?>
			<?php foreach ($this->getData(['module', $this->getUrl(0), 'input']) as $index => $input): ?>
				<?php if ($input['type'] === $module::TYPE_MAIL): ?>
					<?php echo template::mail('formInput[' . $index . ']', [
						'id' => 'formInput_' . $index,
						'label' => $input['name']
					]); ?>
				<?php elseif ($input['type'] === $module::TYPE_SELECT): ?>
					<?php
					$values = array_flip(explode(',', $input['values']));
					foreach ($values as $value => $key) {
						$values[$value] = trim($value);
					}
					?>
					<?php echo template::select('formInput[' . $index . ']', $values, [
						'id' => 'formInput_' . $index,
						'label' => $input['name']
					]); ?>
				<?php elseif ($input['type'] === $module::TYPE_TEXT): ?>
					<?php echo template::text('formInput[' . $index . ']', [
						'id' => 'formInput_' . $index,
						'label' => $input['name']
					]); ?>
				<?php elseif ($input['type'] === $module::TYPE_TEXTAREA): ?>
					<?php echo template::textarea('formInput[' . $index . ']', [
						'id' => 'formInput_' . $index,
						'label' => $input['name']
					]); ?>
				<?php elseif ($input['type'] === $module::TYPE_DATETIME): ?>
					<?php echo template::date('formInput[' . $index . ']', [
						'id' => 'formInput_' . $index,
						'label' => $input['name'],
						'type' => 'date',
					]); ?>
				<?php elseif ($input['type'] === $module::TYPE_CHECKBOX): ?>
					<?php echo template::checkbox(
						'formInput[' . $index . ']',
						true, $input['name']
					); ?>
				<?php elseif ($input['type'] === $module::TYPE_LABEL): ?>
					<h3 class='formLabel'>
						<?php echo $input['name']; ?>
						<hr class="formLabel">
					</h3>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="row">
		<div class="col12 textAlignCenter">
			<?php if ($this->getData(['module', $this->getUrl(0), 'config', 'captcha'])): ?>
				<div class="row">
					<div class="col12 textAlignCenter">
						<?php echo template::captcha('formCaptcha', [
							'limit' => $this->getData(['config', 'connect', 'captchaStrong']),
							'type' => $this->getData(['config', 'connect', 'captchaType'])
						]); ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="row">
				<div class="col2">
					<?php echo template::submit('formSubmit', [
						'value' => $this->getData(['module', $this->getUrl(0), 'config', 'button']) ? $this->getData(['module', $this->getUrl(0), 'config', 'button']) : 'Envoyer',
						'ico' => ''
					]); ?>
				</div>
			</div>
		</div>
	</div>
	<?php echo template::formClose(); ?>
<?php else: ?>
	<?php echo template::speech('Le formulaire ne contient aucun champ.'); ?>
<?php endif; ?>