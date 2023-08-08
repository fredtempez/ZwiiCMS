<?php echo template::formOpen('dashboard'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('dashboardFormBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(false),
			'value' => template::ico('home')
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>
				<?php echo helper::translate('Système'); ?>
			</h4>
			<div class="row">
				<div class="col6">
					<p>
						<?php echo helper::translate('Serveur Web'); ?>
					</p>
					<p>
						<?php echo $module::$infos['webserver']; ?>
					</p>
				</div>
				<div class="col6">
					<p>
						<?php echo helper::translate('PHP') . ' ' . $module::$infos['php']['version']; ?>
					</p>
					<p>
						<?php echo implode(' - ', $module::$infos['php']['extension']); ?>
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col12">
					<p>
						<?php echo helper::translate('Mémoire'); ?>
					</p>
					<p>
						<?php echo $module::$infos['system']['memory']; ?>
					</p>
					<p>
						<?php echo $module::$infos['system']['charge']; ?>
					</p>
					<p>
						<?php echo $module::$infos['system']['peek']; ?>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>