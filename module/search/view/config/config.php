<div class="row">
	<div class="col2">
		<?php echo template::button('newsConfigBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>
</div>
<div class="row">
    <div class="col12">
        <h2 class="textAlignCenter">Aucun paramètre de configuration</h2>
    </div>
</div>