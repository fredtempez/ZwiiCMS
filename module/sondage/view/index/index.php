<?php if($this->getData(['module', $this->getUrl(0), 'input'])): ?>
	<?php echo template::formOpen('formForm'); ?>
		<?php foreach($this->getData(['module', $this->getUrl(0), 'input']) as $index => $input): ?>
			<?php if($input['type'] === $module::TYPE_MAIL): ?>
				<?php echo template::mail('formInput[' . $index . ']', [
					'id' => 'formInput_' . $index,
					'label' => $input['name']
				]); ?>
			<?php elseif($input['type'] === $module::TYPE_SELECT): ?>
				<?php
				$values = array_flip(explode(',', $input['values']));
				foreach($values as $value => $key) {
					$values[$value] = trim($value);
				}
				?>
				<?php echo template::select('formInput[' . $index . ']', $values, [
					'id' => 'formInput_' . $index,
					'label' => $input['name']
				]); ?>
			<?php elseif($input['type'] === $module::TYPE_TEXT): ?>
				<?php echo template::text('formInput[' . $index . ']', [
					'id' => 'formInput_' . $index,
					'label' => $input['name']
				]); ?>
			<?php elseif($input['type'] === $module::TYPE_TEXTAREA): ?>
				<?php echo template::textarea('formInput[' . $index . ']', [
					'id' => 'formInput_' . $index,
					'label' => $input['name']
				]); ?>
			<?php elseif($input['type'] === $module::TYPE_DATETIME): ?>
				<?php echo template::date('formInput[' . $index . ']', [
					'id' => 'formInput_' . $index,
					'label' => $input['name'],
					'vendor' => 'flatpickr'
				]); ?>
			<?php elseif($input['type'] === $module::TYPE_CHECKBOX): ?>
				<?php echo template::checkbox('formInput[' . $index . ']', true, $input['name']
				); ?>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if($this->getData(['module', $this->getUrl(0), 'config', 'captcha'])): ?>
			<div class="row">
				<div class="col4">
					<?php echo template::captcha('formCapcha'); ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col2">
				<?php echo template::button('sondageResult', [
					'value' => 'Réponses',
					'class' => 'blue',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/result' . '/' . $_SESSION['csrf']
				]); ?>
			</div>
			<div class="col2 offset8">
				<?php echo template::submit('formSubmit', [
					'value' => $this->getData(['module', $this->getUrl(0), 'config', 'button']) ? $this->getData(['module', $this->getUrl(0), 'config', 'button']) : 'Envoyer',
					'ico' => '',
					'class' => 'green'
				]); ?>
			</div>
		</div>
	<?php echo template::formClose(); ?>
<?php else: ?>
	<?php echo template::speech('Le formulaire ne contient aucun champ.'); ?>
<?php endif; ?>