<?php
echo Template::formOpen('translateLocaleForm'); ?>
<div class="row">
    <div class="col1">
        <?php
echo Template::button('translateFormBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'language', 'value' => Template::ico('left') ]); ?>
    </div>
    <div class="col2 offset9">
        <?php
echo Template::submit('translateFormSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php
echo Helper::translate('Identité du site'); ?>
            </h4>
            <div class="row">
                <div class="col12">
                    <?php
echo Template::text('localeTitle', [ 'label' => 'Titre', 'value' => language::$locales[$this->getUrl(2)]['locale']['title'], 'help' => 'Il apparaît dans la barre de titre et les partages sur les réseaux sociaux.' ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col12">
                    <?php
echo Template::textarea('localeMetaDescription', [ 'label' => 'Description', 'value' => language::$locales[$this->getUrl(2)]['locale']['metaDescription'], 'help' => 'La description d\'une page participe à son référencement, chaque page doit disposer d\'une description différente.' ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php
echo Helper::translate('Assignation des pages spéciales') ?>
                <!--<span id="localeHelpButton" class="helpDisplayButton" title="Cliquer pour consulter l'aide en ligne">
                    <a href="https://doc.zwiicms.fr/localisation-et-identite" target="_blank">
                        <?php
?>
                    </a>
                </span>-->
            </h4>
            <div class="row">
                <div class="col4">
                    <?php
echo Template::select('localeHomePageId', Helper::arrayColumn(language::$pagesList, 'title', 'SORT_ASC'), [ 'label' => 'Accueil', 'selected' => language::$locales[$this->getUrl(2)]['locale']['homePageId'], 'help' => 'La première page que vos visiteurs verront.' ]); ?>
                </div>
                <div class="col4">
                    <?php
echo Template::select('localePage403', array_merge(['none' => 'Page par défaut'], Helper::arrayColumn(language::$orphansList, 'title', 'SORT_ASC')), [ 'label' => 'Accès interdit, erreur 403', 'selected' => language::$locales[$this->getUrl(2)]['locale']['page403'], 'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.' ]); ?>
                </div>
                <div class="col4">
                    <?php
echo Template::select('localePage404', array_merge(['none' => 'Page par défaut'], Helper::arrayColumn(language::$orphansList, 'title', 'SORT_ASC')), [ 'label' => 'Page inexistante, erreur 404', 'selected' => language::$locales[$this->getUrl(2)]['locale']['page404'], 'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.' ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php
echo Template::select('localeLegalPageId', array_merge(['none' => 'Aucune'], Helper::arrayColumn(language::$pagesList, 'title', 'SORT_ASC')), [ 'label' => 'Mentions légales', 'selected' => language::$locales[$this->getUrl(2)]['locale']['legalPageId'], 'help' => 'Les mentions légales sont obligatoires en France. Une option du pied de page ajoute un lien discret vers cette page.' ]); ?>
                </div>
                <div class="col4">
                    <?php
echo Template::select('localeSearchPageId', array_merge(['none' => 'Aucune'], Helper::arrayColumn(language::$pagesList, 'title', 'SORT_ASC')), [ 'label' => 'Recherche dans le site', 'selected' => language::$locales[$this->getUrl(2)]['locale']['searchPageId'], 'help' => 'Sélectionnez une page contenant le module \'Recherche\'. Une option du pied de page ajoute un lien discret vers cette page.' ]); ?>
                </div>
                <div class="col4">
                    <?php
echo Template::select('localePage302', array_merge(['none' => 'Page par défaut'], Helper::arrayColumn(language::$orphansList, 'title', 'SORT_ASC')), [ 'label' => 'Site en maintenance', 'selected' => language::$locales[$this->getUrl(2)]['locale']['page302'], 'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.' ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php
echo Helper::translate('Étiquettes des pages spéciales'); ?>
               <!--<span id="labelHelpButton" class="helpDisplayButton" title="Cliquer pour consulter l'aide en ligne">
                    <a href="https://doc.zwiicms.fr/Étiquettes-des-pages-speciales" target="_blank">
                        <?php
?>
                    </a>
                </span>-->
            </h4>
            <div class="row">
                <div class="col4">
                    <?php
echo Template::text('localePoweredPageLabel', [ 'label' => 'Motorisé par', 'placeholder' => 'Motorisé par', 'value' => language::$locales[$this->getUrl(2)]['locale']['poweredPageLabel'] ]); ?>
                </div>
                <div class="col4">
                    <?php
echo Template::text('localeLegalPageLabel', [ 'label' => 'Mentions légales', 'placeholder' => 'Mentions légales', 'value' => language::$locales[$this->getUrl(2)]['locale']['legalPageLabel'] ]); ?>
                </div>
                <div class="col4">
                    <?php
echo Template::text('localeSearchPageLabel', [ 'label' => 'Rechercher', 'placeholder' => 'Rechercher', 'value' => language::$locales[$this->getUrl(2)]['locale']['searchPageLabel'] ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4 offset2">
                    <?php
echo Template::text('localeSitemapPageLabel', [ 'label' => 'Plan du site', 'placeholder' => 'Plan du site', 'value' => language::$locales[$this->getUrl(2)]['locale']['sitemapPageLabel'], ]); ?>
                </div>
                <div class="col4">
                    <?php
echo Template::text('localeCookiesFooterText', [ 'label' => 'Cookies', 'value' => language::$locales[$this->getUrl(2)]['locale']['cookies']['cookiesFooterText'], 'placeHolder' => 'Cookies' ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php
echo Helper::translate('Message d\'acceptation des Cookies'); ?>
                <!--<span id="specialeHelpButton" class="helpDisplayButton" title="Cliquer pour consulter l'aide en ligne">
                    <a href="https://doc.zwiicms.fr/cookies" target="_blank">
                        <?php
?>
                    </a>
                </span>-->
            </h4>
            <div class="row">
                <div class="col6">
                    <?php
echo Template::text('localeCookiesTitleText', [ 'help' => 'Saisissez le Titre de gestion des cookies.', 'label' => 'Titre', 'value' => language::$locales[$this->getUrl(2)]['locale']['cookies']['titleLabel'], 'placeHolder' => 'Cookies essentiels' ]); ?>
                </div>
                <div class="col6">
                    <?php
echo Template::text('localeCookiesButtonText', [ 'label' => 'Bouton de validation', 'value' => language::$locales[$this->getUrl(2)]['locale']['cookies']['buttonValidLabel'], 'placeHolder' => 'J\'ai compris' ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col8">
                    <?php
echo Template::textarea('localeCookiesZwiiText', [ 'help' => 'Saisissez le message pour les cookies déposés par ZwiiCMS, nécessaires au fonctionnement et qui ne nécessitent pas de consentement.', 'label' => 'Cookies Zwii', 'value' => language::$locales[$this->getUrl(2)]['locale']['cookies']['mainLabel'], 'placeHolder' => 'Ce site utilise des cookies nécessaires à son fonctionnement, ils permettent de fluidifier son fonctionnement par exemple en mémorisant les données de connexion, la langue que vous avez choisie ou la validation de ce message.' ]); ?>
                </div>

                <div class="col4">
                    <?php
echo Template::text('localeCookiesLinkMlText', [ 'help' => 'Saisissez le texte du lien vers les mentions légales,la page doit être définie dans la configuration du site.', 'label' => 'Lien page des mentions légales.', 'value' => language::$locales[$this->getUrl(2)]['locale']['cookies']['linkLegalLabel'], 'placeHolder' => 'Consulter  les mentions légales' ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo Template::formClose(); ?>
