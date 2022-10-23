<?php echo template::formOpen('configModulesUpload'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('configModulesBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl()  . 'plugin',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col1">
        <?php /**echo template::button('pluginIndexHelp', [
          'href' => 'https://doc.zwiicms.fr/installation-d-un-module',
          'target' => '_blank',
          'class' => 'buttonHelp',
          'value' => template::ico('help'),
          'help' => 'Consulter l\'aide en ligne'
        ]);*/ ?>
    </div>
    <div class="col2 offset8">
        <?php echo template::submit('configModulesSubmit', [
            'value' => 'Valider',
            'ico' => 'check'
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Installer ou mettre à jour un module téléchargé'); ?>
            </h4>
            <div class="row">
                <div class="col6 offset3">
                    <?php echo template::file('configModulesInstallation', [
                        'language' => $this->getData(['user', $this->getUser('id'), 'language']),
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
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>