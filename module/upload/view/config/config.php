<?php echo template::formOpen('uploadConfig'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('uploadConfigBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0) . '/' . self::$siteContent,
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col1 offset8">
		<?php echo template::button('uploadConfigData', [
            'href' => helper::baseUrl() . $this->getUrl(0) . '/data',
            'value' => template::ico('table'),
            'help' => 'Voir les données'
        ]); ?>
	</div>
    <div class="col2">
        <?php echo template::submit('uploadConfigSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Destination'); ?>
            </h4>
            <div class="row">
                <div class="col12">
                    <div class="displayNone">
                        <?php echo template::hidden('uploadConfigPathOld', [
                            'noDirty' => true,  // Désactivé à cause des modifications en ajax
                            'value' => $this->getData(['module', $this->getUrl(0), 'config', 'path'])
                        ]); ?>
                    </div>
                    <?php echo template::select('uploadConfigPath', upload::$sharePath, [
                        'label' => 'Dossier de stockage',
                        'noDirty' => true  // Désactivé à cause des modifications en ajax
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Restrictions'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::text('uploadConfigAccept', [
                        'label' => 'Formats acceptés',
                        'value' => $this->getData(['module', $this->getUrl(0), 'config', 'accept']),
                        'help' => 'Les formats sont séparés par des virgules (,). <br />Sont autorisés les extensions (.pdf), les types MIMME génériques (image/*) ou complets. <br />Se reporter à la document de l\'attribut "Accept" des formulaires HTML.'
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('uploadConfigMaxSize', upload::$sizes, [
                        'label' => 'Taille maximale',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'maxSize']),
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('uploadConfigAttempt', upload::$attempts, [
                        'label' => 'Tentatives',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'maxAttempt']),
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('uploadConfigMaxFiles', upload::$multiple, [
                        'label' => 'Nombre de fichiers autorisés',
                        'selected' => $this->getData(['module', $this->getUrl(0), 'config', 'maxFiles']),
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Étiquettes du formulaire'); ?>
            </h4>   
            <div class="row">
                <div class="col4">
                    <?php echo template::text('uploadConfigLabel', [
                        'label' => 'Libellé',
                        'value' => $this->getData(['module', $this->getUrl(0), 'config', 'label']) ? $this->getData(['module', $this->getUrl(0), 'config', 'label']) : 'Document à remettre'
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::text('uploadConfigUploadtext', [
                        'label' => 'Bouton',
                        'value' => $this->getData(['module', $this->getUrl(0), 'config', 'uploadText']) ? $this->getData(['module', $this->getUrl(0), 'config', 'uploadText']) : 'Parcourir'
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::text('uploadConfigPlaceHolder', [
                        'label' => 'Fichier non sélectionné',
                        'value' => $this->getData(['module', $this->getUrl(0), 'config', 'placeholder']) ? $this->getData(['module', $this->getUrl(0), 'config', 'placeholder']) : 'Pas de sélection'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php echo template::formClose(); ?>
    <div class="moduleVersion">Version n°
        <?php echo $module::VERSION; ?>
    </div>