<?php echo template::formOpen('configBackupForm'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('configBackupBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'config',
				'ico' => 'left',
				'value' => 'Retour'
		]); ?>
	</div>
	<div class="col2 offset8">
		<?php echo template::submit('configBackupSubmit',[
			'value' => 'Sauvegarder'
		]); ?>
	</div>
	<div class="modal">Merci de patienter, je travaille pour vous.<!-- Emplacement pour l'animation --></div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4>Paramètre</h4>
			<div class="row">
				<div class="col12">
					<?php echo template::checkbox('configBackupOption', true, 'Inclure le contenu du gestionnaire de fichiers', [
						'checked' => true,
						'help' => 'Si le contenu du gestionnaire de fichiers est très volumineux, mieux vaut une copie par FTP.'
					]); ?>
				</div>
				<div class="col12">
					<em>L'archive est générée dans <a href="<?php echo helper::baseUrl(false); ?>core/vendor/filemanager/dialog.php?fldr=backup&type=0&akey=<?php echo md5_file(core::$data_dir.'core.json'); ?>"  data-lity>le dossier Backup</a> du gestionnaire de fichiers.</em>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo template::formClose(); ?>
