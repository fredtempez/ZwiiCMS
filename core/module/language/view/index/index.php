<?php echo template::formOpen('translateForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('translateFormBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(),
			'value' => template::ico('home')
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>
				<?php echo helper::translate('Langues installées'); ?>
			</h4>
			<?php if (language::$languagesUiInstalled): ?>
				<?php echo template::table([3, 3, 3, 1, 1, 1], language::$languagesUiInstalled, ['Langues', 'Version', 'Date', '', '', '']); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php if (language::$languagesStore): ?>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>
				<?php echo helper::translate('Catalogue'); ?>
			</h4>
				<?php echo template::table([2, 1, 2, 6, 1], language::$languagesStore, ['Langues', 'Version', 'Date', '', '']); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php echo template::formClose(); ?>