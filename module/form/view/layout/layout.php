<?php echo template::formOpen('formLayoutForm'); ?>
<div class="row">
    <div class="col2">
        <?php echo template::button('formLayoutBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
            'ico' => 'left',
            'value' => 'Retour'
        ]); ?>
    </div>
    <div class="col2 offset8">
        <?php echo template::submit('formLayoutSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>Validation du formulaire</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('formLayoutCaptcha', true, 'Captcha', [
                        'checked' => $this->getData(['module', $this->getUrl(0), 'config', 'captcha'])                
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::text('formLayoutButton', [
                        'help' => 'Laissez vide afin de conserver le texte par défaut.',
                        'label' => 'Etiquette du bouton de soumission',
                        'value' => $this->getData(['module', $this->getUrl(0), 'config', 'button'])
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::checkbox('formLayoutPageIdToggle', true, 'Redirection après soumission du formulaire', [
                        'checked' => (bool) $this->getData(['module', $this->getUrl(0), 'config', 'pageId'])
                    ]); ?>
                </div>
                <div class="col5">
                    <?php echo template::select('formLayoutPageId', $module::$pages, [
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
            <h4>Courriel</h4>
            <?php echo template::checkbox('formLayoutMailOptionsToggle', true, 'Envoyer par mail les données saisies :', [
                'checked' => (bool) $this->getData(['module', $this->getUrl(0), 'config', 'group']) ||
                                    !empty($this->getData(['module', $this->getUrl(0), 'config', 'user'])) ||
                                    !empty($this->getData(['module', $this->getUrl(0), 'config', 'mail'])),
                'help' => 'Sélectionnez au moins un groupe, un utilisateur ou saisissez un email. Votre serveur doit autoriser les envois de mail.'
            ]); ?>
            <div id="formLayoutMailOptions" class="displayNone">
                <div class="row">
                    <div class="col12">
                        <?php echo template::text('formLayoutSubject', [
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
                        <?php echo template::select('formLayoutGroup', $groupMembers, [
                            'label' => 'Aux groupes à partir de',
                            'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'group']),
                            'help' => 'Editeurs = éditeurs + administrateurs<br/> Membres = membres + éditeurs + administrateurs'
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('formLayoutUser', $module::$listUsers, [
                            'label' => 'A un membre',
                            'selected' => array_search($this->getData(['module', $this->getUrl(0), 'config', 'user']),$module::$listUsers)
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::text('formLayoutMail', [
                            'label' => 'A une adresse email',
                            'value' => $this->getData(['module', $this->getUrl(0), 'config', 'mail']),
                            'help' => 'Un email ou une liste de diffusion'
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col4">
                        <?php echo template::select('formLayoutSignature', $module::$signature, [
                            'label' => 'Sélectionner le type de signature',
                            'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'signature'])
                        ]); ?>
                    </div>
                    <div class="col4">
                                                <?php echo template::file('formLayoutLogo', [
                            'help' => 'Sélectionnez le logo du site',
                                                        'label' => 'Logo',
                                                        'value' => $this->getData(['module', $this->getUrl(0), 'config', 'logoUrl'])
                                                ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('formLayoutLogoWidth', $module::$logoWidth, [
                            'label' => 'Sélectionner la largeur du logo',
                            'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'logoWidth'])
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col6">
                        <?php echo template::checkbox('formLayoutMailReplyTo', true, 'Répondre à l\'expéditeur depuis le mail de notification', [
                                'checked' => (bool) $this->getData(['module', $this->getUrl(0), 'config', 'replyto']),
                                'help' => 'Cette option permet de réponse directement à l\'expéditeur du message si celui-ci a indiqué un email valide.'
                            ]); ?>
                    </div>
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
                            
                    </div>
                    <div class="col6">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>