<?php echo template::formOpen('configBackupForm'); ?>
<div class="row">
	<div class="col1">
		<?php echo template::button('configBackupBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'config',
			'value' => template::ico('left')
		]); ?>
	</div>
	<div class="col2 offset9">
		<?php echo template::submit('configBackupSubmit', [
			'value' => 'Sauvegarder',
			'uniqueSubmission' => true
		]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php echo helper::translate('Paramètres de la sauvegarde'); ?>
			</h4>
			<div class="row">
				<div class="col12">
					<?php echo template::checkbox('configBackupOption', true, 'Inclure le contenu du gestionnaire de fichiers', [
						'checked' => true,
						'help' => 'Si le contenu du gestionnaire de fichiers est très volumineux, mieux vaut une copie par FTP.'
					]); ?>
				</div>
				<div class="col12">
					<em>L'archive est générée dans <a href="<?php echo helper::baseUrl(false); ?>core/vendor/filemanager/dialog.php?fldr=backup&type=0&akey=<?php echo md5_file(self::DATA_DIR . 'core.json'); ?>&lang=<?php echo $this->getData(['user', $this->getUser('id'), 'language']);?>" data-lity>le dossier Backup</a> du gestionnaire de fichiers.</em>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>