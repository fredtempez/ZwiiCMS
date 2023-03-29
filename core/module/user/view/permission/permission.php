<div class="row">
	<div class="col1">
        <?php echo template::button('userGroupBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'user',
            'value' => template::ico('left')
        ]); ?>
	</div>
</div>
<?php echo template::table([3, 7, 1, 1], $module::$userGroups, ['Nom', 'Commentaire', '', '']); ?>