<?php echo template::formOpen('folderConfig'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('folderConfigBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('folderConfigSubmit'); ?>
	</div>
</div>
<div class='row'>
	<div class="col12">
		<div class="block">
			<h4>
				<?php echo helper::translate('Paramètres'); ?>
			</h4>
			<div class="row">
				<div class="col6">
					<?php echo template::select('folderConfigPath', $module::$sharePath, [
						'label' => 'Dossier',
						'class' => 'filemanager',
						'selected' => $this->getData(['module', $this->getUrl(0), 'path'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::text('folderConfigTitle', [
						'label' => 'Titre',
						'placeholder' => 'Répertoire',
						'value' => empty ($this->getData(['module', $this->getUrl(0), 'title'])) ? 'Répertoire' : $this->getData(['module', $this->getUrl(0), 'title'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::checkbox('folderConfigSort', true, 'Trier les dossiers et les fichiers', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'sort'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::checkbox('folderConfigSubfolder', true, 'Descendre dans l\'arboresence', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'subfolder'])
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