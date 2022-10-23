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
            <h4>Validation du formulaire</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('formOptionCaptcha', true, 'Captcha', [
                        'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'captcha'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::text('formOptionButton', [
                        'help' => 'Laissez vide afin de conserver le texte par défaut.',
                        'label' => 'Etiquette du bouton de soumission',
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
                    <?php echo template::select('formOptionPageId', $module::$pages, [
                        'classWrapper' => 'displayNone',
                        'label' => 'Page du site :',
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
            <h4>Gabarit</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('formOptionAlign', $module::$optionAlign, [
                        'label' => 'Alignement du formulaire',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'align'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('formOptionOffset', $module::$optionOffset, [
                        'label' => 'Décalage à gauche',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'offset'])
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::select('formOptionWidth', $module::$optionWidth, [
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
            <h4>Courriel</h4>
            <?php echo template::checkbox('formOptionMailOptionsToggle', true, 'Envoyer par mail les données saisies :', [
                'checked' => (bool) $this->getData(['module', $this->getUrl(0), 'config', 'group']) ||
                    !empty($this->getData(['module', $this->getUrl(0), 'config', 'user'])) ||
                    !empty($this->getData(['module', $this->getUrl(0), 'config', 'mail'])),
                'help' => 'Sélectionnez au moins un groupe, un utilisateur ou saisissez un email. Votre serveur doit autoriser les envois de mail.'
            ]); ?>
            <div id="formOptionMailOptions" class="displayNone">
                <div class="row">
                    <div class="col12">
                        <?php echo template::text('formOptionSubject', [
                            'help' => 'Laissez vide afin de conserver le texte par défaut.',
                            'label' => 'Sujet du mail',
                            'value' => $this->getData(['module', $this->getUrl(0), 'config', 'subject'])
                        ]); ?>
                    </div>
                </div>
                <?php
                // Element 0 quand aucun membre a été sélectionné
                $groupMembers = [''] + $module::$groupNews;
                ?>
                <div class="row">
                    <div class="col4">
                        <?php echo template::select('formOptionGroup', $groupMembers, [
                            'label' => 'Aux groupes à partir de',
                            'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'group']),
                            'help' => 'Éditeurs = éditeurs + administrateurs<br/> Membres = membres + éditeurs + administrateurs'
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('formOptionUser', $module::$listUsers, [
                            'label' => 'A un membre',
                            'selected' => array_search($this->getData(['module', $this->getUrl(0), 'config', 'user']), $module::$listUsers)
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::text('formOptionMail', [
                            'label' => 'A une adresse email',
                            'value' => $this->getData(['module', $this->getUrl(0), 'config', 'mail']),
                            'help' => 'Un email ou une liste de diffusion'
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col4">
                        <?php echo template::select('formOptionSignature', $module::$signature, [
                            'label' => 'Sélectionner le type de signature',
                            'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'signature'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::file('formOptionLogo', [
                            'help' => 'Sélectionnez le logo du site',
                            'language' => $this->getData(['user', $this->getUser('id'), 'language']),
                            'label' => 'Logo',
                            'value' => $this->getData(['module', $this->getUrl(0), 'config', 'logoUrl'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('formOptionLogoWidth', $module::$logoWidth, [
                            'label' => 'Sélectionner la largeur du logo',
                            'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'logoWidth'])
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col6">
                        <?php echo template::checkbox('formOptionMailReplyTo', true, 'Répondre à l\'expéditeur depuis le mail de notification', [
                            'checked' => (bool) $this->getData(['module', $this->getUrl(0), 'config', 'replyto']),
                            'help' => 'Cette option permet de réponse directement à l\'expéditeur du message si celui-ci a indiqué un email valide.'
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>