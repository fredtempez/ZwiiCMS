<div class="row">
	<div class="col1">
		<?php echo template::button('registrationUserBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
            'value' => template::ico('left')
		]); ?>
	</div>
</div>

<?php if(suscribe::$users): ?>
<?php echo template::table([3, 3, 2,21, 1, 1], suscribe::$users, ['Identifiant', 'Nom', 'Etat', 'Date', '', '']); ?>
<?php else: ?>
	<?php echo template::speech('Pas d\'inscription en attente.'); ?>
<?php endif; ?>