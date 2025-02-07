<?php echo template::formOpen('formOptionForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('formOptionBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('formOptionSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Validation du formulaire'); ?></h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('formOptionCaptcha', true, 'Captcha', [
                        'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'captcha'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::text('formOptionButton', [
                        'help' => 'Vide affiche le texte par défaut',
                        'label' => 'Étiquette du bouton de soumission',
                        'value' => $this->getData(['module', $this->getUrl(0), 'config', 'button'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('formOptionPageIdToggle', true, 'Redirection après soumission du formulaire', [
                        'checked' => (bool) $this->getData(['module', $this->getUrl(0), 'config', 'pageId'])
                    ]); ?>
                </div>
                <div class="col5">
                    <?php echo template::select('formOptionPageId', form::$pages, [
                        'classWrapper' => 'displayNone',
                        'label' => 'Page du site',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'pageId'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Gabarit'); ?></h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('formOptionAlign', form::$optionAlign, [
                        'label' => 'Alignement du formulaire',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'align'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('formOptionOffset', form::$optionOffset, [
                        'label' => 'Décalage à gauche',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'offset'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('formOptionWidth', form::$optionWidth, [
                        'label' => 'Largeur',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'width'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Courriel'); ?></h4>
            <?php echo template::checkbox('formOptionMailOptionsToggle', true, 'Envoyer par mail les données saisies :', [
                'checked' => (bool) $this->getData(['module', $this->getUrl(0), 'config', 'role']) ||
                    !empty($this->getData(['module', $this->getUrl(0), 'config', 'user'])) ||
                    !empty($this->getData(['module', $this->getUrl(0), 'config', 'mail'])),
                'help' => 'Sélectionnez au moins un groupe, un utilisateur ou saisissez un email. Votre serveur doit autoriser les envois de mail.'
            ]); ?>
            <div id="formOptionMailOptions" class="displayNone">
                <div class="row">
                    <div class="col12">
                        <?php echo template::text('formOptionSubject', [
                            'help' => 'Vide affiche le texte par défaut',
                            'label' => 'Sujet du mail',
                            'value' => $this->getData(['module', $this->getUrl(0), 'config', 'subject'])
                        ]); ?>
                    </div>
                </div>
                <?php
                // Element 0 quand aucun membre a été sélectionné
                $roleMembers = [''] + form::$roleNews;
                ?>
                <div class="row">
                    <div class="col4">
                        <?php echo template::select('formOptionGroup', $roleMembers, [
                            'label' => 'A tous les rôles depuis',
                            'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'role']),
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('formOptionUser', form::$listUsers, [
                            'label' => 'A un membre',
                            'selected' => array_search($this->getData(['module', $this->getUrl(0), 'config', 'user']), form::$listUsers)
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::text('formOptionMail', [
                            'label' => 'A une Adresse électronique',
                            'value' => $this->getData(['module', $this->getUrl(0), 'config', 'mail']),
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col4">
                        <?php echo template::select('formOptionSignature', form::$signature, [
                            'label' => 'Type de signature',
                            'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'signature'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::file('formOptionLogo', [
                            'language' => $this->getData(['user', $this->getUser('id'), 'language']),
                            'label' => 'Logo du site',
                            'value' => $this->getData(['module', $this->getUrl(0), 'config', 'logoUrl']),
                            'folder' => $this->getData(['module', $this->getUrl(0), 'config', 'logoUrl']) ? dirname($this->getData(['module', $this->getUrl(0), 'config', 'logoUrl'])) : ''
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('formOptionLogoWidth', form::$logoWidth, [
                            'label' => 'Largeur du logo',
                            'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'logoWidth'])
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col6">
                        <?php echo template::checkbox('formOptionMailReplyTo', true, 'Répondre à l\'expéditeur depuis le mail de notification', [
                            'checked' => (bool) $this->getData(['module', $this->getUrl(0), 'config', 'replyto']),
                            'help' => 'Cette option permet de répondre directement à l\'expéditeur du message si celui-ci a indiqué un email valide.'
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>