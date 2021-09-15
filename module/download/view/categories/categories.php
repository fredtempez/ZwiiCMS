<?php echo template::formOpen('categoriesForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('categoriesBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::submit('categoriesSubmit', [
				'value' => 'Valider'
			]); ?>
		</div>
	</div>
    <div class="row">
		<div class="col12">
			<div class="block" id="params">
				<h4>Nouvelle catégorie
					<div class="openClose">
						<?php
						echo template::ico('plus-circled','right');
						echo template::ico('minus-circled','right');
						?>
					</div>
				</h4>
				<div class="blockContainer">
					<div class="row">
						<div class="col12">
								<?php echo template::text('categoriesTitle', [
									'label' => 'Nom',
									'value' => $this->getData(['module', $this->getUrl(0), 'categories', $this->getUrl(2), 'title'])
								]); ?>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
    <?php echo template::formClose(); ?>
<?php if($module::$categories): ?>
	<?php echo template::table([2, 6, 1, 1], $module::$categories, ['Nom', 'URL', '','']); ?>
	<?php echo $module::$pages; ?>
<?php else: ?>
	<?php echo template::speech('Aucune catégorie'); ?>
<?php endif; ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>