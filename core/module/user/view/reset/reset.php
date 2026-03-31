<?php
echo Template::formOpen('userResetForm'); ?>
<div class="row">
	<div class="col6">
		<?php
echo Template::password('userResetNewPassword', [ 'label' => 'Nouveau mot de passe' ]); ?>
	</div>
	<div class="col6">
		<?php
echo Template::password('userResetConfirmPassword', [ 'label' => 'Confirmation' ]); ?>
	</div>
</div>
<div class="row">
	<div class="col3">
		<?php
echo Template::button('userResetBack', [ 'href' => Helper::baseUrl(), 'value' => Template::ico('left') ]); ?>
	</div>
	<div class="col3 offset6">
		<?php
echo Template::submit('userResetSubmit', [ 'value' => 'Valider' ]); ?>
	</div>
</div>
<?php
echo Template::formClose(); ?>
