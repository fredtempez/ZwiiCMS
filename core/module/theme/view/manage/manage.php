<?php echo template::formOpen('themeManageForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('themeManageBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . 'theme',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col6">
			<div class="block">
			<h4>Installer un thème archivé</h4>
				<div class="row">
					<div class="col12">
						<?php echo template::file('themeManageImport', [
								'label' => 'Archive ZIP :',
								'type' => 2
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col5 offset3">
						<?php echo template::submit('themeImportSubmit', [
							'value' => 'Appliquer'
						]); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col6">
			<div class="block">
			<h4>Sauvegarder le thème</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::button('themeSave', [
							'href' => helper::baseUrl() . 'theme/save/theme',
							'ico' => 'download-cloud',
							'value' => 'Thème site'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::button('themeSaveAdmin', [
							'href' => helper::baseUrl() . 'theme/save/admin',
							'ico' => 'download-cloud',
							'value' => 'Thème administration'
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col12">
						<em>Le fichier de sauvegarde est généré dans <a href="<?php echo helper::baseUrl(false); ?>core/vendor/filemanager/dialog.php?fldr=theme&type=0&akey=<?php echo md5_file(self::DATA_DIR.'core.json'); ?>"  data-lity>le dossier Thème</a> du gestionnaire de fichiers.</em>
					</div>
				</div>
			</div>
			<div class="block">
			<h4>Télécharger le thème</h4>
				<div class="row">
					<div class="col6">
						<?php echo template::button('themeExport', [
							'href' => helper::baseUrl() . 'theme/export/theme',
							'ico' => 'download',
							'value' => 'Thème site'
						]); ?>
					</div>
					<div class="col6">
						<?php echo template::button('themeExport', [
							'href' => helper::baseUrl() . 'theme/export/admin',
							'ico' => 'download',
							'value' => 'Thème administration'
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
