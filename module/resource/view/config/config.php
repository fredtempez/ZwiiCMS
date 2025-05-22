<?php echo template::formOpen('resourceConfig'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('resourceConfigBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('resourceConfigSubmit'); ?>
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
				<div class="displayNone">
					<?php echo template::hidden('resourceConfigPathOld', [
						'noDirty' => true, // Désactivé à cause des modifications en ajax,
						'value' => $this->getData(['module', $this->getUrl(0), 'path'])
					]); ?>
						</div>
					<?php echo template::select('resourceConfigPath', [], [
						'label' => 'Dossier',
						'class' => 'filemanager'
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::text('resourceConfigTitle', [
						'label' => 'Titre',
						'value' => $this->getData(['module', $this->getUrl(0), 'title'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php echo template::select('resourceConfigiconPack', resource::$iconPack, [
						'label' => 'Thème des icônes',
						'selected' => $this->getData(['module', $this->getUrl(0), 'iconpack'])
					]); ?>
				</div>
				<div class="col3">
					<?php echo template::select('resourceConfigIconSize', resource::$iconSize, [
						'label' => 'Taille des icônes',
						'selected' => $this->getData(['module', $this->getUrl(0), 'iconsize'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::select('resourceConfigTarget', resource::$target, [
						'label' => 'Cible des liens',
						'selected' => $this->getData(['module', $this->getUrl(0), 'target'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col6">
					<?php echo template::checkbox('resourceConfigSort', true, 'Trier', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'sort'])
					]); ?>
				</div>
				<div class="col6">
					<?php echo template::checkbox('resourceConfigSubfolder', true, 'Inclure les sous-dossiers', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'subfolder'])
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php echo template::checkbox('resourceConfigDetails', true, 'Information des fichiers', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'details'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::checkbox('resourceConfigFolderState', true, 'Dossiers repliés', [
						'checked' => $this->getData(['module', $this->getUrl(0), 'folderstate'])
					]); ?>
				</div>
				<div class="col4">
					<?php echo template::checkbox('resourceConfigExpandControl', true, 'Icônes de contrôle', [
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