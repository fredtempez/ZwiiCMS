<?php echo template::formOpen('configModulesUpload'); ?>
  <div class="row">
      <div class="col2">
          <?php echo template::button('configModulesBack', [
              'class' => 'buttonGrey',
              'href' => helper::baseUrl()  . 'addon',
              'ico' => 'left',
              'value' => 'Retour'
          ]); ?>
      </div>
      <div class="col2">
        <?php echo template::button('addonIndexHelp', [
          'class' => 'buttonHelp',
          'ico' => 'help',
          'value' => 'Aide'
        ]); ?>
      </div>
  </div>
  <!-- Aide à propos de la gestion des modules, view upload -->
  <div class="helpDisplayContent">
    <?php echo file_get_contents( 'core/module/addon/view/upload/upload.help.html') ;?>
  </div>
  <div class="row">
    <div class="col12">
      <div class="block">
        <h4>Télécharger un module du catalogue en ligne </h4>
          <div class="row">
            <div class="col4 offset4">
                <?php echo template::button('configModulesStore', [
                    'href' => helper::baseUrl() . 'addon/store',
                    'value' => 'Catalogue en ligne'
                  ]); ?>
            </div>
          </div>
        </div>
    </div>
  </div>
	<div class="row">
		<div class="col12">
			<div class="block">
			<h4>Installer ou mettre à jour un module téléchargé </h4>
				<div class="row">
					<div class="col6 offset3">
						<?php echo template::file('configModulesInstallation', [
								'label' => 'Archive ZIP :',
								'type' => 2
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col4 offset3">
						<?php echo template::checkbox('configModulesCheck', true, 'Mise à jour forcée', [
								'checked' => false,
								'help' => 'Permet de forcer une mise à jour même si la version du module est inférieure ou égale à celle du module installé.',
							]); ?>
					</div>
          <div class="col2">
            <?php echo template::submit('configModulesSubmit',[
                'value' => 'Valider',
                'ico' => 'check'
            ]); ?>
      </div>
				</div>
			</div>
		</div>
	</div>
<?php echo template::formClose(); ?>
