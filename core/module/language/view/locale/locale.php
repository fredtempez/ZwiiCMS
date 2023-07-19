<?php echo template::formOpen('translateLocaleForm'); ?>
<div class="row">
    <div class="col1">
        <?php echo template::button('translateFormBack', [
            'class' => 'buttonGrey',
            'href' => helper::baseUrl() . 'translate',
            'value' => template::ico('left')
        ]); ?>
    </div>
    <div class="col2 offset9">
        <?php echo template::submit('translateFormSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Identité du site'); ?>
                <!--<span id="localeHelpButton" class="helpDisplayButton" title="Cliquer pour consulter l'aide en ligne">
                    <a href="https://doc.zwiicms.fr/localisation-et-identite" target="_blank">
                        <?php //echo template::ico('help', ['margin' => 'left']); ?>
                    </a>
                </span>-->
            </h4>
            <div class="row">
                <div class="col12">
                    <?php echo template::text('localeTitle', [
                        'label' => 'Titre',
                        'value' => $module::$locales[$this->getUrl(2)]['locale']['title'],
                        'help'  => 'Il apparaît dans la barre de titre et les partages sur les réseaux sociaux.'
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col12">
                    <?php echo template::textarea('localeMetaDescription', [
                        'label' => 'Description',
                        'value' => $module::$locales[$this->getUrl(2)]['locale']['metaDescription'],
                        'help'  => 'La description d\'une page participe à son référencement, chaque page doit disposer d\'une description différente.'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Assignation des pages spéciales') ?>
                <!--<span id="localeHelpButton" class="helpDisplayButton" title="Cliquer pour consulter l'aide en ligne">
                    <a href="https://doc.zwiicms.fr/localisation-et-identite" target="_blank">
                        <?php //echo template::ico('help', ['margin' => 'left']); ?>
                    </a>
                </span>-->
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::select('localeHomePageId', helper::arrayColumn($module::$pagesList, 'title', 'SORT_ASC'), [
                        'label' => 'Accueil',
                        'selected' => $module::$locales[$this->getUrl(2)]['locale']['homePageId'],
                        'help' => 'La première page que vos visiteurs verront.'
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('localePage403', array_merge(['none' => 'Page par défaut'], helper::arrayColumn($module::$orphansList, 'title', 'SORT_ASC')), [
                        'label' => 'Accès interdit, erreur 403',
                        'selected' => $module::$locales[$this->getUrl(2)]['locale']['page403'],
                        'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('localePage404', array_merge(['none' => 'Page par défaut'], helper::arrayColumn($module::$orphansList, 'title', 'SORT_ASC')), [
                        'label' => 'Page inexistante, erreur 404',
                        'selected' => $module::$locales[$this->getUrl(2)]['locale']['page404'],
                        'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::select('localeLegalPageId', array_merge(['none' => 'Aucune'], helper::arrayColumn($module::$pagesList, 'title', 'SORT_ASC')), [
                        'label' => 'Mentions légales',
                        'selected' => $module::$locales[$this->getUrl(2)]['locale']['legalPageId'],
                        'help' => 'Les mentions légales sont obligatoires en France. Une option du pied de page ajoute un lien discret vers cette page.'
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::select('localeSearchPageId', array_merge(['none' => 'Aucune'], helper::arrayColumn($module::$pagesList, 'title', 'SORT_ASC')), [
                        'label' => 'Recherche dans le site',
                        'selected' => $module::$locales[$this->getUrl(2)]['locale']['searchPageId'],
                        'help' => 'Sélectionnez une page contenant le module \'Recherche\'. Une option du pied de page ajoute un lien discret vers cette page.'
                    ]); ?>
                </div>
                <div class="col4">
                    <?php
                    echo template::select('localePage302', array_merge(['none' => 'Page par défaut'], helper::arrayColumn($module::$orphansList, 'title', 'SORT_ASC')), [
                        'label' => 'Site en maintenance',
                        'selected' => $module::$locales[$this->getUrl(2)]['locale']['page302'],
                        'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Étiquettes des pages spéciales'); ?>
               <!--<span id="labelHelpButton" class="helpDisplayButton" title="Cliquer pour consulter l'aide en ligne">
                    <a href="https://doc.zwiicms.fr/etiquettes-des-pages-speciales" target="_blank">
                        <?php //echo template::ico('help', ['margin' => 'left']); ?>
                    </a>
                </span>-->
            </h4>
            <div class="row">
                <div class="col4">
                    <?php echo template::text('localePoweredPageLabel', [
                        'label' => 'Motorisé par',
                        'placeholder' => 'Motorisé par',
                        'value' => $module::$locales[$this->getUrl(2)]['locale']['poweredPageLabel']
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::text('localeLegalPageLabel', [
                        'label' => 'Mentions légales',
                        'placeholder' => 'Mentions légales',
                        'value' => $module::$locales[$this->getUrl(2)]['locale']['legalPageLabel']
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::text('localeSearchPageLabel', [
                        'label' => 'Rechercher',
                        'placeholder' => 'Rechercher',
                        'value' => $module::$locales[$this->getUrl(2)]['locale']['searchPageLabel']
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4 offset2">
                    <?php echo template::text('localeSitemapPageLabel', [
                        'label' => 'Plan du site',
                        'placeholder' => 'Plan du site',
                        'value' => $module::$locales[$this->getUrl(2)]['locale']['sitemapPageLabel'],
                    ]); ?>
                </div>
                <div class="col4">
                    <?php echo template::text('localeCookiesFooterText', [
                        'label' => 'Cookies',
                        'value' => $module::$locales[$this->getUrl(2)]['locale']['cookies']['cookiesFooterText'],
                        'placeHolder' => 'Cookies'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo helper::translate('Message d\'acceptation des Cookies'); ?>
                <!--<span id="specialeHelpButton" class="helpDisplayButton" title="Cliquer pour consulter l'aide en ligne">
                    <a href="https://doc.zwiicms.fr/cookies" target="_blank">
                        <?php //echo template::ico('help', ['margin' => 'left']); ?>
                    </a>
                </span>-->
            </h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::text('localeCookiesTitleText', [
                        'help' => 'Saisissez le Titre de gestion des cookies.',
                        'label' => 'Titre',
                        'value' => $module::$locales[$this->getUrl(2)]['locale']['cookies']['titleLabel'],
                        'placeHolder' => 'Cookies essentiels'
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::text('localeCookiesButtonText', [
                        'label' => 'Bouton de validation',
                        'value' => $module::$locales[$this->getUrl(2)]['locale']['cookies']['buttonValidLabel'],
                        'placeHolder' => 'J\'ai compris'
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col8">
                    <?php echo template::textarea('localeCookiesZwiiText', [
                        'help' => 'Saisissez le message pour les cookies déposés par ZwiiCMS, nécessaires au fonctionnement et qui ne nécessitent pas de consentement.',
                        'label' => 'Cookies Zwii',
                        'value' => $module::$locales[$this->getUrl(2)]['locale']['cookies']['mainLabel'],
                        'placeHolder' => 'Ce site utilise des cookies nécessaires à son fonctionnement, ils permettent de fluidifier son fonctionnement par exemple en mémorisant les données de connexion, la langue que vous avez choisie ou la validation de ce message.'
                    ]); ?>
                </div>

                <div class="col4">
                    <?php echo template::text('localeCookiesLinkMlText', [
                        'help' => 'Saisissez le texte du lien vers les mentions légales,la page doit être définie dans la configuration du site.',
                        'label' => 'Lien page des mentions légales.',
                        'value' => $module::$locales[$this->getUrl(2)]['locale']['cookies']['linkLegalLabel'],
                        'placeHolder' => 'Consulter  les mentions légales'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>