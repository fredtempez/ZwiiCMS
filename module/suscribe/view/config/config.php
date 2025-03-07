<?php echo template::formOpen('registrationConfig'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('registrationConfigBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0) . '/' . self::$siteContent,
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset7">
        <?php echo template::button('registrationConfigBack', [
            'href' => helper::baseUrl() . $this->getUrl(0) . '/users',
            'value' => 'Inscriptions'
        ]); ?>
    </div>
    <div class="col2">
        <?php echo template::submit('registrationConfigSubmit', [
            'class' => 'green'
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>Paramètres</h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::select('registrationConfigLayout', suscribe::$layout, [
                        'label' => 'Disposition',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'layout'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('registrationConfigSuccess', helper::arraycollumn($this->getData(['page']), 'title', 'SORT_ASC'), [
                        'label' => 'Redirection après confirmation',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'pageSuccess'])
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('registrationConfigError', helper::arraycollumn($this->getData(['page']), 'title', 'SORT_ASC'), [
                        'label' => 'Redirection après erreur',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'pageError'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>Communication</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('registrationConfigTimeOut', suscribe::$timeLimit, [
                        'label' => 'Validité du lien',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'timeOut']) / 60
                    ]); ?>
                </div>
                <div class="col6">
                <?php echo template::text('registrationConfigFilter', [
                        'label' => 'Domaines acceptés', 
                        'value' =>  $this->getData(['module', $this->getUrl(0), 'config', 'filter']),
                        'help' => 'Champ vide sinon limitation à des domaines spécifiques, le séparateur est ;'
                ]); ?>          
                </div>
            </div>
            <div class="row">
                <div class="col12">
                    <?php $messageDefault = '<p>Confirmez votre inscription en cliquant sur ce lien dans les ... minutes.</p>'; ?>
                    <?php echo template::textarea('registrationconfigMailRegisterContent', [
                        'label' => 'Corps du mail de confirmation',
                        'value' => !empty($this->getData(['module', $this->getUrl(0), 'config', 'mailRegisterContent'])) ? $this->getData(['module', $this->getUrl(0), 'config', 'mailRegisterContent']) : $messageDefault,
                        'class' => 'editorWysiwyg',
                        'help' => 'Précisez la durée de validité. Le lien sera inséré après ces explications.'
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6 verticalAlignMiddle">
                    <?php echo template::checkbox('registrationConfigState', true, 'Approbation préalable', [
                        'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'approval']),
                        'help' => 'Les comptes sont inactifs tant que les inscriptions ne sont pas approuvées par un administrateur.',
                        'check' => true
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col12">
                    <?php $messageDefault = '<p>Votre inscription a été approuvée par un administrateur.</p>'; ?>
                    <?php echo template::textarea('registrationconfigMailValidateContent', [
                        'label' => 'Corps du mail d\'approbation',
                        'value' => !empty($this->getData(['module', $this->getUrl(0), 'config', 'mailValidateContent'])) ? $this->getData(['module', $this->getUrl(0), 'config', 'mailValidateContent']) : $messageDefault,
                        'class' => 'editorWysiwyg'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>
<div class="moduleVersion">Version n°<?php echo suscribe::VERSION; ?>
</div>