<?php
echo Template::formOpen('userForgotForm'); ?>
<?php
echo Template::text('userForgotId', [ 'label' => 'Votre identifiant ou votre email' ]); ?>
<div class="row">
	<div class="col2">
		<?php
echo Template::button('userForgotBack', [ 'href' => Helper::baseUrl() . 'user/login/' . $this->getUrl(2), 'value' => Template::ico('left') ]); ?>
	</div>
	<div class="col3 offset7">
		<?php
echo Template::submit('userForgotSubmit', [ 'value' => 'Valider' ]); ?>
	</div>
</div>
<?php
echo Template::formClose(); ?>
