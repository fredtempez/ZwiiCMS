<div id="formConfigCopy" class="displayNone">
	<div class="formConfigInput">
		<?php echo template::hidden('formConfigPosition[]', [
			'class' => 'formConfigPosition'
		]); ?>
		<div class="row">
			<div class="col1">
				<?php echo template::button('formConfigMove[]', [
					'value' => template::ico('sort'),
					'class' => 'formConfigMove'
				]); ?>
			</div>
			<div class="col5">
				<?php echo template::text('formConfigName[]', [
					'placeholder' => 'Intitulé'
				]); ?>
			</div>
			<div class="col4">
				<?php echo template::select('formConfigType[]', $module::$types, [
					'class' => 'formConfigType'
				]); ?>
			</div>
			<div class="col1">
				<?php echo template::button('formConfigMoreToggle[]', [
					'value' => template::ico('gear'),
					'class' => 'formConfigMoreToggle'
				]); ?>
			</div>
			<div class="col1">
				<?php echo template::button('formConfigDelete[]', [
					'value' => template::ico('minus'),
					'class' => 'formConfigDelete'
				]); ?>
			</div>
		</div>
		<div class="formConfigMoreLabel displayNone">
			<?php echo template::label('formConfigLabel', 'Aucune option pour une étiquette', [
					'class' => 'displayNone formConfigLabelWrapper'
				]); ?>
		</div>
		<div class="formConfigMore displayNone">
			<?php echo template::text('formConfigValues[]', [
				'placeholder' => 'Liste des valeurs séparées par des virgules (valeur1,valeur2,...)',
				'class' => 'formConfigValues',
				'classWrapper' => 'displayNone formConfigValuesWrapper'
			]); ?>
			<?php echo template::checkbox('formConfigRequired[]', true, 'Champ obligatoire'); ?>
		</div>
	</div>
</div>
<?php echo template::formOpen('formConfigForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('formConfigBack', [
            'class' => 'buttonGrey',
			'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
			'value' => template::ico('left')
        ]); ?>
	</div>
	<div class="col1 offset7">
		<?php echo template::button('formConfigData', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/data',
			'value' => template::ico('code'),
			'help' => 'Voir et exporter les données du formulaire'
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('formConfigLayout', [
			'href' => helper::baseUrl() . $this->getUrl(0) . '/option',
			'value' => template::ico('sliders'),
			'help' => 'Options de configuration'
		]); ?>
	</div>
	<div class="col2">
			<?php echo template::submit('formConfigSubmit'); ?>
		</div>
</div>
<div class="block">
	<h4><?php echo helper::translate('Liste des champs');?></h4></h4>
	<div id="formConfigNoInput">
		<?php echo template::speech('Le formulaire ne contient aucun champ.'); ?>
	</div>
	<div id="formConfigInputs"></div>
	<div class="row">
		<div class="col1 offset11">
			<?php echo template::button('formConfigAdd', [
				'value' => template::ico('plus'),
				'class' => 'buttonGreen',
			]); ?>
		</div>
	</div>
</div>
</div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°
	<?php echo $module::VERSION; ?>
</div>
