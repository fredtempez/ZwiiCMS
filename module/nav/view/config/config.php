<?php echo template::formOpen('navConfig'); ?>
	<div class="row">
		<div class="col1">
			<?php echo template::button('navConfigBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
				'value' => template::ico('left')
			]); ?>
		</div>
		<div class="col2 offset9">
				<?php echo template::submit('navConfigSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>
				<?php echo helper::translate('Thème'); ?>
			</h4>
				<div class="row">
					<div class="col4 offset4 ">
                    <?php echo template::select('navConfigIconTemplate', $module::$iconTemplateName, [
							'label' => 'Icônes',
							'selected' => $this->getData(['module', $this->getUrl(0), 'iconTemplate'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>