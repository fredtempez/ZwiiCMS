<div class="row">
	<div class="col1">
        <?php echo template::button('userGroupBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'user',
            'value' => template::ico('left')
        ]); ?>
	</div>
</div>
<?php echo template::table([1, 4, 5, 1, 1], $module::$userGroups, ['#', 'Groupe et profil', 'Commentaire', '', '']); ?>