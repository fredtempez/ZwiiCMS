<div class="row">
	<div class="col1">
        <?php echo template::button('userGroupBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'user',
            'value' => template::ico('left')
        ]); ?>
	</div>
    <div class="col1 offset10">
		<?php echo template::button('userAdd', [
			'href' => helper::baseUrl() . 'user/profilAdd',
			'value' => template::ico('plus'),
			'class' => 'buttonGreen',
			'help' => 'Ajouter un profil'
		]); ?>
	</div>
</div>
<?php echo template::table([1, 4, 5, 1, 1], user::$userGroups, ['#', 'Nom', 'Commentaire', '', '']); ?>