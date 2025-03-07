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
						'value' => $this->getData(['module', $this->getUrl(0), 'title'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php echo template::select('folderConfigiconPack', $module::$iconPack, [
						'label' => 'Thème des icônes',
						'selected' => $this->getData(['module', $this->getUrl(0), 'iconpack'])
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::select('folderConfigIconSize', $module::$iconSize, [
						'label' => 'Taille des icônes',
						'selected' => $this->getData(['module', $this->getUrl(0), 'iconsize'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::select('folderConfigTarget', $module::$target, [
						'label' => 'Cible des liens',
						'selected' => $this->getData(['module', $this->getUrl(0), 'target'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::checkbox('folderConfigSort', true, 'Trier', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'sort'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::checkbox('folderConfigSubfolder', true, 'Inclure les sous-dossiers', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'subfolder'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::checkbox('folderConfigDetails', true, 'Information des fichiers', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'details'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::checkbox('folderConfigFolderState', true, 'Dossiers repliés', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'folderstate'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::checkbox('folderConfigExpandControl', true, 'Icônes de contrôle', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'expandcontrol'])
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