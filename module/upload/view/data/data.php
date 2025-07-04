<div class="row">
	<div class="col1">
		<?php echo template::button('uploadDataBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col1 offset8">
		<?php echo template::button('uploadDataDownload', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/download',
			'value' => template::ico('download'),
			'help' => 'Télécharger les fichiers remis'
		]); ?>
	</div>
	<div class="col1">
	<?php echo template::button('uploadDataDeleteAll', [
			'class' => 'uploadDataDeleteAll buttonRed',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/deleteAll',
			'value' => template::ico('trash'),
			'help' => 'Effacer toutes les données'
		]); ?>
	</div>
	<div class="col1">
	<?php echo template::button('uploadDataBack', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/download',
			'value' => template::ico('download'),
			'help' => 'Télécharger tous les fichiers'
		]); ?>
	</div>
</div>
<?php if(upload::$datas): ?>
		<?php echo template::table([2 ,4, 1, 1, 1], upload::$datas, ['Auteur', 'Fichier', 'Taille',  'Date', '']); ?>
	<?php else: ?>
		<?php echo template::speech('Aucune fichier remis !'); ?>
	<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo form::VERSION; ?>
</div>