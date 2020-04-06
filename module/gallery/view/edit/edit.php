<?php echo template::formOpen('galleryEditForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('galleryEditBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::submit('galleryEditSubmit'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Informations générales</h4>
				<div class="row">
					<div class="col5">
						<?php echo template::text('galleryEditName', [
							'label' => 'Nom',
							'value' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'config', 'name'])
						]); ?>
					</div>
					<div class="col4">
						<?php echo template::hidden('galleryEditDirectoryOld', [
							'value' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'config', 'directory']),
							'noDirty' => true // Désactivé à cause des modifications en ajax
						]); ?>
						<?php echo template::select('galleryEditDirectory', [], [
							'label' => 'Dossier cible',
							'noDirty' => true // Désactivé à cause des modifications en ajax
						]); ?>
					</div>
					<div class="col3">
						<?php echo template::select('galleryEditSort', $module::$sort, [
							'selected' => $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'config', 'sort']),
							'label' => 'Tri des images',
							'help' => 'Les images sont triées par nom de fichier grâce à la méthode naturelle qui donne de meilleurs résultats lorsque les images sont numérotées.'
						]); ?>	
					</div>				
				</div>
			</div>
		</div>
	</div>
	<?php if($module::$pictures): ?>
		<?php echo template::table([4, 1, 6, 1], $module::$pictures, ['Image', 'Couverture','Légende','']); ?>
	<?php endif; ?>
<?php echo template::formClose(); ?>