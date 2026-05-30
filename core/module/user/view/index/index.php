<div class="row">
	<div class="col1">
		<?php
echo Template::button('userAddBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl(false), 'value' => Template::ico('home') ]); ?>
	</div>
	<div class="col1 offset8">
		<?php
echo Template::button('userImport', [ 'href' => Helper::baseUrl() . 'user/import', 'value' => Template::ico('upload'), 'help' => 'Importer des utilisateurs en masse' ]); ?>
	</div>
	<div class="col1">
		<?php
echo Template::button('userRole', [ 'href' => Helper::baseUrl() . 'user/profil', 'value' => Template::ico('lock'), 'help' => 'Profils' ]); ?>
	</div>
	<div class="col1">
		<?php
echo Template::button('userAdd', [ 'href' => Helper::baseUrl() . 'user/add', 'value' => Template::ico('plus'), 'class' => 'buttonGreen', 'help' => 'Ajouter un utilisateur' ]); ?>
	</div>
</div>
<?php
echo Template::formOpen('userFilterUserForm'); ?>
<div class="row">
	<div class="col3">
		<?php
echo Template::select('userFilterRole', user::$usersGroups, [ 'label' => 'Rôles / Profils', 'selected' => isset($_POST['userFilterRole']) ? $_POST['userFilterRole'] : 'all', ]); ?>
	</div>
	<div class="col3">
		<?php
echo Template::select('userFilterFirstName', user::$alphabet, [ 'label' => 'Prénom commence par', 'selected' => isset($_POST['userFilterFirstName']) ? $_POST['userFilterFirstName'] : 'all', ]); ?>
	</div>
	<div class="col3">
		<?php
echo Template::select('userFilterLastName', user::$alphabet, [ 'label' => 'Nom commence par', 'selected' => isset($_POST['userFilterLastName']) ? $_POST['userFilterLastName'] : 'all', ]); ?>
	</div>
</div>
<?php
echo Template::formClose(); ?>
<?php
echo Template::table([2, 3, 2, 2, 2, 1], user::$users, ['Nom', 'Courriel', 'Rôle/Profil', 'Étiquettes', 'Dernière Connexion', ''], ['id' => 'dataTables']); ?>
