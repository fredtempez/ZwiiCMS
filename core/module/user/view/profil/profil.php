<div class="row">
	<div class="col1">
        <?php
echo Template::button('userGroupBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'user', 'value' => Template::ico('left') ]); ?>
	</div>
    <div class="col1 offset10">
		<?php
echo Template::button('userAdd', [ 'href' => Helper::baseUrl() . 'user/profilAdd', 'value' => Template::ico('plus'), 'class' => 'buttonGreen', 'help' => 'Ajouter un profil' ]); ?>
	</div>
</div>
<?php
echo Template::table([1, 4, 5, 1, 1], user::$userGroups, ['#', 'Nom', 'Commentaire', '', '']); ?>
