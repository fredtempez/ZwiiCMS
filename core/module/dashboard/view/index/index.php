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
		<?php echo 'Version de PHP : ' . phpversion(); ?>
		<?php
		$extensions = get_loaded_extensions();

		echo '<p>Extensions activées :</p>';
		foreach ($extensions as $extension) {
			echo $extension . ' - ';
		}
		?>
		<?php
		$serverSoftware = $_SERVER['SERVER_SOFTWARE'];
		echo '<p>';
		if (stripos($serverSoftware, 'apache') !== false) {
			echo 'Serveur web : Apache';
		} elseif (stripos($serverSoftware, 'nginx') !== false) {
			echo 'Serveur web : Nginx';
		} elseif (stripos($serverSoftware, 'tomcat') !== false) {
			echo 'Serveur web : Tomcat';
		} else {
			echo 'Serveur web non identifié : ' . $serverSoftware;

		}
		echo '</p>';
		?>

		<?php
		echo 'Mémoire utilisée : ' . memory_get_usage() . ' octets</p>';
		echo 'Pic de mémoire utilisée : ' . memory_get_peak_usage() . ' octets</p>';
		?>

		<?php
		$loadAverage = sys_getloadavg();
		echo 'Charge moyenne (1 min / 5 min / 15 min) : ' . implode(' / ', $loadAverage) . '</P>';
		?>

		<?php
		$diskSpace = shell_exec('df -h'); // Linux example
		echo 'Espace disque :</p>' . $diskSpace;
		?>

	</div>
</div>