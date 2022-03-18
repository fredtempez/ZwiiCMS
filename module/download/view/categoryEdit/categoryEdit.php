<?php echo template::formOpen('categoryEditForm'); ?>
	<div class="row">
		<div class="col2">
			<?php echo template::button('categoryEditBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/categories',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::submit('categoryEditSubmit', [
				'value' => 'Valider'
			]); ?>
		</div>
    <div class="row">
		<div class="col12">
            <div class="block">
                <h4>Editer la catégorie</h4>
                <div class="row">
                    <div class="col12">
                            <?php echo template::text('categoryEditTitle', [
                                'label' => 'Nom',
                                'value' => $this->getData(['module', $this->getUrl(0), 'categories', $this->getUrl(2)])
                            ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo template::formClose(); ?>