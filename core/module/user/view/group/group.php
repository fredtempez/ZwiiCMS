<div class="row">
	<div class="col1">
        <?php echo template::button('userGroupBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'user',
            'value' => template::ico('left')
        ]); ?>
	</div>
</div>
<?php echo template::table([1, 3, 6, 1, 1], $module::$userGroups, ['#', 'Nom', 'Commentaire', '', '']); ?>