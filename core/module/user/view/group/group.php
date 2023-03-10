<div class="row">
	<div class="col1">
        <?php echo template::button('userGroupBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'user',
            'value' => template::ico('left')
        ]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('userGroupSubmit'); ?>
	</div>
</div>