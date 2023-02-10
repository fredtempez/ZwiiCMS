<div class="row">
	<div class="col2">
		<?php echo template::button('registrationUserBack', [
			'class' => '',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => 'Retour'
		]); ?>
	</div>
</div>

<?php if($module::$users): ?>
<?php echo template::table([3, 3, 2,21, 1, 1], $module::$users, ['Identifiant', 'Nom', 'Etat', 'Date', '', '']); ?>
<?php else: ?>
	<?php echo template::speech('Pas d\'inscription en attente.'); ?>
<?php endif; ?>