<div id="localeContainer" class="tabContent">
    <div class="row">
        <div class="col12">
            <div class="block">
                <h4><?php
echo Helper::translate('Identité du site'); ?></h4>
                <div class="row">
                    <div class="col12">
                        <?php
echo Template::text('configLocaleTitle', [ 'label' => 'Titre', 'value' => $this->getData(['locale', 'title']), 'help' => 'Il apparaît dans la barre de titre et les partages sur les réseaux sociaux.' ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col12">
                        <?php
echo Template::textarea('configLocaleMetaDescription', [ 'label' => 'Description', 'value' => $this->getData(['locale', 'metaDescription']), 'help' => 'La description d\'une page participe à son référencement, chaque page doit disposer d\'une description différente.' ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col12">
            <div class="block">
                <h4><?php
echo Helper::translate('Assignation des pages spéciales') ?></h4>
                <div class="row">
                    <div class="col4">
                        <?php
echo Template::select('configLocaleHomePageId', Helper::arrayColumn(config::$pagesList, 'title', 'SORT_ASC'), [ 'label' => 'Accueil', 'selected' => $this->getData(['locale', 'homePageId']), 'help' => 'La première page que vos visiteurs verront.' ]); ?>
                    </div>
                    <div class="col4">
                        <?php
echo Template::select('configLocalePage403', array_merge(['none' => 'Page par défaut'], Helper::arrayColumn(config::$orphansList, 'title', 'SORT_ASC')), [ 'label' => 'Accès interdit, erreur 403', 'selected' => $this->getData(['locale', 'page403']), 'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.' ]); ?>
                    </div>
                    <div class="col4">
                        <?php
echo Template::select('configLocalePage404', array_merge(['none' => 'Page par défaut'], Helper::arrayColumn(config::$orphansList, 'title', 'SORT_ASC')), [ 'label' => 'Page inexistante, erreur 404', 'selected' => $this->getData(['locale', 'page404']), 'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.' ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col4">
                        <?php
echo Template::select('configLocaleLegalPageId', array_merge(['none' => 'Aucune'], Helper::arrayColumn(config::$pagesList, 'title', 'SORT_ASC')), [ 'label' => 'Mentions légales', 'selected' => $this->getData(['locale', 'legalPageId']), 'help' => 'Les mentions légales sont obligatoires en France. Une option du pied de page ajoute un lien discret vers cette page.' ]); ?>
                    </div>
                    <div class="col4">
                        <?php
echo Template::select('configLocaleSearchPageId', array_merge(['none' => 'Aucune'], Helper::arrayColumn(config::$pagesList, 'title', 'SORT_ASC')), [ 'label' => 'Recherche dans le site', 'selected' => $this->getData(['locale', 'searchPageId']), 'help' => 'Sélectionnez une page contenant le module \'Recherche\'. Une option du pied de page ajoute un lien discret vers cette page.' ]); ?>
                    </div>
                    <div class="col4">
                        <?php
echo Template::select('configLocalePage302', array_merge(['none' => 'Page par défaut'], Helper::arrayColumn(config::$orphansList, 'title', 'SORT_ASC')), [ 'label' => 'Site en maintenance', 'selected' => $this->getData(['locale', 'page302']), 'help' => 'Cette page ne doit pas apparaître dans l\'arborescence du menu. Créez une page orpheline.' ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col12">
            <div class="block">
                <h4><?php
echo Helper::translate('Étiquettes des pages spéciales'); ?></h4>
                <div class="row">
                    <div class="col4">
                        <?php
echo Template::text('configLocalePoweredPageLabel', [ 'label' => 'Motorisé par', 'placeholder' => 'Motorisé par', 'value' => $this->getData(['locale', 'poweredPageLabel']) ]); ?>
                    </div>
                    <div class="col4">
                        <?php
echo Template::text('configLocaleLegalPageLabel', [ 'label' => 'Mentions légales', 'placeholder' => 'Mentions légales', 'value' => $this->getData(['locale', 'legalPageLabel']) ]); ?>
                    </div>
                    <div class="col4">
                        <?php
echo Template::text('configLocaleSearchPageLabel', [ 'label' => 'Rechercher', 'placeholder' => 'Rechercher', 'value' => $this->getData(['locale', 'searchPageLabel']) ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col4 offset2">
                        <?php
echo Template::text('configLocaleSitemapPageLabel', [ 'label' => 'Plan du site', 'placeholder' => 'Plan du site', 'value' => $this->getData(['locale', 'sitemapPageLabel']) ]); ?>
                    </div>
                    <div class="col4">
                        <?php
echo Template::text('configLocaleCookiesFooterText', [ 'label' => 'Cookies', 'value' => $this->getData(['locale', 'cookies', 'cookiesFooterText']), 'placeHolder' => 'Cookies' ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col12">
            <div class="block">
                <h4><?php
echo Helper::translate('Message d\'acceptation des Cookies'); ?></h4>
                <div class="row">
                    <div class="col6">
                        <?php
echo Template::text('configLocaleCookiesTitleText', [ 'label' => 'Titre', 'value' => $this->getData(['locale', 'cookies', 'titleLabel']), 'placeHolder' => 'Cookies essentiels' ]); ?>
                    </div>
                    <div class="col6">
                        <?php
echo Template::text('configLocaleCookiesButtonText', [ 'label' => 'Bouton de validation', 'value' => $this->getData(['locale', 'cookies', 'buttonValidLabel']), 'placeHolder' => 'J\'ai compris' ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col8">
                        <?php
echo Template::textarea('configLocaleCookiesZwiiText', [ 'help' => 'Saisissez le message pour les cookies déposés par ZwiiCMS, nécessaires au fonctionnement et qui ne nécessitent pas de consentement.', 'label' => 'Cookies Zwii', 'value' => $this->getData(['locale', 'cookies', 'mainLabel']), 'placeHolder' => 'Ce site utilise des cookies nécessaires à son fonctionnement, ils permettent de fluidifier son fonctionnement par exemple en mémorisant les données de connexion, la langue que vous avez choisie ou la validation de ce message.' ]); ?>
                    </div>
                    <div class="col4">
                        <?php
echo Template::text('configLocaleCookiesLinkMlText', [ 'help' => 'Saisissez le texte du lien vers les mentions légales, la page doit être définie dans la configuration du site.', 'label' => 'Lien page des mentions légales.', 'value' => $this->getData(['locale', 'cookies', 'linkLegalLabel']), 'placeHolder' => 'Consulter les mentions légales' ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
