<?php
class layout extends common
{

    private $core;

    public function __construct(core $core)
    {
        parent::__construct();
        $this->core = $core;
    }

    /**
     * Affiche le consentement aux cookies
     */
    public function showCookies()
    {
        // La gestion des cookies est externalisée
        if ($this->getData(['config', 'cookieConsent']) === false) {
            return;
        }
        // Le cookie est déjà validé
        if ($this->getInput('ZWII_COOKIE_CONSENT') === 'true') {
            return;
        }
        $item = '<div id="cookieConsent">';
        // Bouton de fermeture
        $item .= '<div class="cookieClose">';
        $item .= template::ico('cancel');
        $item .= '</div>';
        // Texte de la popup
        $item .= '<h3>' . $this->getData(['locale', 'cookies', 'titleLabel']) . '</h3>';
        $item .= '<p>' . $this->getData(['locale', 'cookies', 'mainLabel']) . '</p>';
        // Formulaire de réponse
        if (
            $this->getData(['locale', 'homePageId']) === $this->getUrl(0)
        ) {
            $item .= '<form method="POST" action="' . helper::baseUrl(false) . '" id="cookieForm">';
        } else {
            $item .= '<form method="POST" action="' . helper::baseUrl(true) . $this->getUrl() . '" id="cookieForm">';
        }
        $item .= '<br><br>';
        $item .= '<input type="submit" id="cookieConsentConfirm" value="' . $this->getData(['locale', 'cookies', 'buttonValidLabel']) . '">';
        $item .= '</form>';
        // mentions légales si la page est définie
        $legalPage = $this->getData(['locale', 'legalPageId']);
        if ($legalPage !== 'none') {
            $item .= '<p><a href="' . helper::baseUrl() . $legalPage . '">' . $this->getData(['locale', 'cookies', 'linkLegalLabel']) . '</a></p>';
        }
        $item .= '</div>';
        echo $item;
    }

    /**
     * Formate le contenu de la page selon les gabarits
     * @param Page par defaut
     */
    public function showMain()
    {
        echo '<main><section>';
        // Récupérer la config de la page courante
        $blocks = is_null($this->getData(['page', $this->getUrl(0), 'block'])) ? '12' : $this->getData(['page', $this->getUrl(0), 'block']);
        $blocks = explode('-', $blocks);
        // Initialiser
        $blockleft = '';
        $blockright = '';
        switch (sizeof($blocks)) {
            case 1: // une colonne
                $content = 'col' . $blocks[0];
                break;
            case 2: // 2 blocs
                if ($blocks[0] < $blocks[1]) { // détermine la position de la colonne
                    $blockleft = 'col' . $blocks[0];
                    $content = 'col' . $blocks[1];
                } else {
                    $content = 'col' . $blocks[0];
                    $blockright = 'col' . $blocks[1];
                }
                break;
            case 3: // 3 blocs
                $blockleft = 'col' . $blocks[0];
                $content = 'col' . $blocks[1];
                $blockright = 'col' . $blocks[2];
        }
        // Page pleine pour la configuration des modules et l'édition des pages sauf l'affichage d'un article de blog
        $pattern = ['config', 'edit', 'add', 'comment', 'data'];
        if (
            (sizeof($blocks) === 1 ||
                in_array($this->getUrl(1), $pattern))
        ) { // Pleine page en mode configuration
            if ($this->getData(['page', $this->getUrl(0), 'navLeft']) === 'top' || $this->getData(['page', $this->getUrl(0), 'navRight']) === 'top') {
                $this->showNavButtons('top');
            }
            $this->showContent();
            if ($this->getData(['page', $this->getUrl(0), 'navLeft']) === 'bottom' || $this->getData(['page', $this->getUrl(0), 'navRight']) === 'bottom') {
                $this->showNavButtons('bottom');
            }
        } else {
            echo '<div class="row siteContainer">';
            /**
             * Barre gauche
             */
            if ($blockleft !== '') {
                echo '<div class="' . $blockleft . '" id="contentLeft"><aside>';
                // Détermine si le menu est présent
                if ($this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barLeft']), 'displayMenu']) === 'none') {
                    // Pas de menu
                    echo $this->core->output['contentLeft'];
                } else {
                    // $mark contient 0 le menu est positionné à la fin du contenu
                    $contentLeft = str_replace('[]', '[MENU]', $this->core->output['contentLeft']);
                    $contentLeft = str_replace('[menu]', '[MENU]', $contentLeft);
                    $mark = strrpos($contentLeft, '[MENU]') !== false ? strrpos($contentLeft, '[MENU]') : strlen($contentLeft);
                    echo substr($contentLeft, 0, $mark);
                    echo '<div id="menuSideLeft">';
                    echo $this->showMenuSide($this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barLeft']), 'displayMenu']) === 'parents' ? false : true);
                    echo '</div>';
                    echo substr($contentLeft, $mark + 6, strlen($contentLeft));
                }
                echo "</aside></div>";
            }
            /**
             * Contenu de page
             */
            echo '<div class="' . $content . '" id="contentSite">';
            $this->showNavButtons('top');
            $this->showContent();
            $this->showNavButtons('bottom');
            echo '</div>';
            /**
             * Barre droite
             */
            if ($blockright !== '') {
                echo '<div class="' . $blockright . '" id="contentRight"><aside>';
                // Détermine si le menu est présent
                if ($this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'displayMenu']) === 'none') {
                    // Pas de menu
                    echo $this->core->output['contentRight'];
                } else {
                    // $mark contient 0 le menu est positionné à la fin du contenu
                    $contentRight = str_replace('[]', '[MENU]', $this->core->output['contentRight']);
                    $contentRight = str_replace('[menu]', '[MENU]', $contentRight);
                    $mark = strrpos($contentRight, '[MENU]') !== false ? strrpos($contentRight, '[MENU]') : strlen($contentRight);
                    echo substr($contentRight, 0, $mark);
                    echo '<div id="menuSideRight">';
                    echo $this->showMenuSide($this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'displayMenu']) === 'parents' ? false : true);
                    echo '</div>';
                    echo substr($contentRight, $mark + 6, strlen($contentRight));
                }
                echo '</aside></div>';
            }
            echo '</div>';
        }
        echo '</main></section>';
    }

    /**
     * Affiche le contenu
     * @param Page par défaut
     */
    public function showContent()
    {

        if (
            $this->core->output['title']
            and ($this->getData(['page', $this->getUrl(0)]) === null
                or $this->getData(['page', $this->getUrl(0), 'hideTitle']) === false
                or $this->getUrl(1) === 'config'
            )
        ) {
            echo '<h1 id="sectionTitle">' . $this->core->output['title'] . '</h1>';
        }

        echo $this->core->output['content'];
    }

    /**
     * Affiche le pied de page
     */
    public function showFooter()
    {
        // Déterminer la position
        $positionFixed = '';
        if (
            $this->getData(['theme', 'footer', 'position']) === 'site'
            // Affiche toujours le pied de page pour l'édition du thème
            or ($this->getData(['theme', 'footer', 'position']) === 'hide'
                and $this->getUrl(0) === 'theme'
            )
        ) {
            $position = 'site';
        } else {
            $position = 'body';
            if ($this->getData(['theme', 'footer', 'fixed']) === true) {
                $positionFixed = ' footerbodyFixed';
            }
            // Sortir de la division précédente
            echo '</div>';
        }

        echo $this->getData(['theme', 'footer', 'position']) === 'hide' ? '<footer class="displayNone">' : '<footer>';
        echo ($position === 'site') ? '<div class="container"><div class="row" id="footersite">' : '<div class="container-large' . $positionFixed . '"><div class="row" id="footerbody">';
        /**
         * Calcule la dimension des blocs selon la configuration
         */
        switch ($this->getData(['theme', 'footer', 'template'])) {
            case '1':
                $class['left'] = "displayNone";
                $class['center'] = "col12";
                $class['right'] = "displayNone";
                break;
            case '2':
                $class['left'] = "col6";
                $class['center'] = "displayNone";
                $class['right'] = "col6";
                break;
            case '3':
                $class['left'] = "col4";
                $class['center'] = "col4";
                $class['right'] = "col4";
                break;
            case '4':
                $class['left'] = "col12";
                $class['center'] = "col12";
                $class['right'] = "col12";
                break;
        }
        /**
         * Affiche les blocs
         */
        echo '<div class="' . $class['left'] . '" id="footer' . $position . 'Left">';
        if ($this->getData(['theme', 'footer', 'textPosition']) === 'left') {
            $this->showFooterText();
        }
        if ($this->getData(['theme', 'footer', 'socialsPosition']) === 'left') {
            $this->showSocials();
        }
        if ($this->getData(['theme', 'footer', 'copyrightPosition']) === 'left') {
            $this->showCopyright();
        }
        echo '</div>';
        echo '<div class="' . $class['center'] . '" id="footer' . $position . 'Center">';
        if ($this->getData(['theme', 'footer', 'textPosition']) === 'center') {
            $this->showFooterText();
        }
        if ($this->getData(['theme', 'footer', 'socialsPosition']) === 'center') {
            $this->showSocials();
        }
        if ($this->getData(['theme', 'footer', 'copyrightPosition']) === 'center') {
            $this->showCopyright();
        }
        echo '</div>';
        echo '<div class="' . $class['right'] . '" id="footer' . $position . 'Right">';
        if ($this->getData(['theme', 'footer', 'textPosition']) === 'right') {
            $this->showFooterText();
        }
        if ($this->getData(['theme', 'footer', 'socialsPosition']) === 'right') {
            $this->showSocials();
        }
        if ($this->getData(['theme', 'footer', 'copyrightPosition']) === 'right') {
            $this->showCopyright();
        }
        echo '</div>';

        // Fermeture du conteneur
        echo '</div></div>';
        echo '</footer>';
    }

    /**
     * Affiche le texte du footer
     */
    private function showFooterText()
    {
        if ($footerText = $this->getData(['theme', 'footer', 'text']) or $this->getUrl(0) === 'theme') {
            echo '<div id="footerText">' . $footerText . '</div>';
        }
    }

    /**
     * Affiche le copyright
     */
    private function showCopyright()
    {
        // Ouverture Bloc copyright
        $items = '<div id="footerCopyright">';
        $items .= '<span id="footerFontCopyright">';
        // Affichage de motorisé par
        $items .= '<span id="footerDisplayCopyright" ';
        $items .= $this->getData(['theme', 'footer', 'displayCopyright']) === false ? 'class="displayNone"' : '';
        $label = empty($this->getData(['locale', 'poweredPageLabel'])) ? 'Motorisé par' : $this->getData(['locale', 'poweredPageLabel']);
        $items .= '><wbr>&nbsp;' . $label . '&nbsp;</span>';
        // Toujours afficher le nom du CMS
        $items .= '<span id="footerZwiiCMS">';
        $items .= '<a href="https://zwiicms.fr/" onclick="window.open(this.href);return false" >ZwiiCMS</a>';
        $items .= '</span>';
        // Affichage du numéro de version
        $items .= '<span id="footerDisplayVersion"';
        $items .= $this->getData(['theme', 'footer', 'displayVersion']) === false ? ' class="displayNone"' : '';
        $items .= '><wbr>&nbsp;' . common::ZWII_VERSION;
        $items .= '</span>';
        // Affichage du sitemap
        $items .= '<span id="footerDisplaySiteMap"';
        $items .= $this->getData(['theme', 'footer', 'displaySiteMap']) === false ? ' class="displayNone"' : '';
        $label = ($this->getData(['locale', 'sitemapPageLabel']) === 'none') ? 'Plan du site' : $this->getData(['locale', 'sitemapPageLabel']);
        $items .= '><wbr>&nbsp;|&nbsp;<a href="' . helper::baseUrl() . 'sitemap"  >' . $label . '</a>';
        $items .= '</span>';
        // Affichage du module de recherche
        $items .= '<span id="footerDisplaySearch"';
        $items .= $this->getData(['theme', 'footer', 'displaySearch']) === false ? ' class="displayNone" >' : '>';
        $label = empty($this->getData(['locale', 'searchPageLabel'])) ? 'Rechercher' : $this->getData(['locale', 'searchPageLabel']);
        if ($this->getData(['locale', 'searchPageId']) !== 'none') {
            $items .= '<wbr>&nbsp;|&nbsp;<a href="' . helper::baseUrl() . $this->getData(['locale', 'searchPageId']) . '"  >' . $label . '</a>';
        }
        $items .= '</span>';
        // Affichage des mentions légales
        $items .= '<span id="footerDisplayLegal"';
        $items .= $this->getData(['theme', 'footer', 'displayLegal']) === false ? ' class="displayNone" >' : '>';
        $label = empty($this->getData(['locale', 'legalPageLabel'])) ? 'Mentions Légales' : $this->getData(['locale', 'legalPageLabel']);
        if ($this->getData(['locale', 'legalPageId']) !== 'none') {
            $items .= '<wbr>&nbsp;|&nbsp;<a href="' . helper::baseUrl() . $this->getData(['locale', 'legalPageId']) . '"  >' . $label . '</a>';
        }
        $items .= '</span>';
        // Affichage de la gestion des cookies
        $items .= '<span id="footerDisplayCookie"';
        $items .= ($this->getData(['config', 'cookieConsent']) === true && $this->getData(['theme', 'footer', 'displayCookie']) === true) ? '>' : ' class="displayNone" >';
        $label = empty($this->getData(['locale', 'cookies', 'cookiesFooterText'])) ? 'Cookies' : $this->getData(['locale', 'cookies', 'cookiesFooterText']);
        $items .= '<wbr>&nbsp;|&nbsp;<a href="javascript:void(0)" id="footerLinkCookie">' . $label . '</a>';
        $items .= '</span>';
        // Affichage du lien de connexion
        if (
            ($this->getData(['theme', 'footer', 'loginLink'])
                and $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
            )
            or $this->getUrl(0) === 'theme'
        ) {
            $items .= '<span id="footerLoginLink" ' .
                ($this->getUrl(0) === 'theme' ? 'class="displayNone">' : '>') .
                '<wbr>&nbsp;|&nbsp;<wbr>' .
                template::ico('login', [
                    'href' => helper::baseUrl() . 'user/login/' . strip_tags(str_replace('/', '_', $this->getUrl())),
                    'attr' => 'rel="nofollow"',
                    'help' => 'Connexion'
                ]) . '</span>';
        }
        // Affichage de la barre de membre simple
        if (
            $this->getUser('group') >= self::GROUP_MEMBER && $this->getUser('group') < self::GROUP_ADMIN
            && $this->getData(['theme', 'footer', 'memberBar']) === true
        ) {
            $items .= '<span id="footerDisplayMemberAccount"';
            $items .= $this->getData(['theme', 'footer', 'displaymemberAccount']) === false ? ' class="displayNone">' : '>';
            $items .= '<wbr>&nbsp;|&nbsp;';
            if (
                $this->getUser('permission', 'filemanager') === true
            ) {
                $items .= '<wbr>' . template::ico('folder', [
                    'href' => helper::baseUrl(false) . 'core/vendor/filemanager/dialog.php?type=0&akey=' . md5_file(self::DATA_DIR . 'core.json') . '&lang=' . $this->getData(['user', $this->getUser('id'), 'language']),
                    'margin' => 'all',
                    'attr' => 'data-lity',
                    'help' => 'Fichiers du site'
                ]);
            }
            if (
                $this->getUser('permission', 'user', 'edit') === true
            ) {
                $items .= '<wbr>' . template::ico('user', [
                    'margin' => 'all',
                    'help' => 'Mon compte',
                    'href' => helper::baseUrl() . 'user/edit/' . $this->getUser('id')
                ]);
            }
            $items .= '<wbr>' . template::ico('logout', [
                'margin' => 'all',
                'help' => 'Déconnecter',
                'href' => helper::baseUrl() . 'user/logout'
            ]);
            $items .= '</span>';
        }
        // Fermeture du bloc copyright
        $items .= '</span></div>';
        echo $items;
    }


    /**
     * Affiche les réseaux sociaux
     */
    private function showSocials()
    {
        $socials = '';
        foreach ($this->getData(['config', 'social']) as $socialName => $socialId) {
            switch ($socialName) {
                case 'facebookId':
                    $socialUrl = 'https://www.facebook.com/';
                    $title = 'Facebook';
                    break;
                case 'linkedinId':
                    $socialUrl = 'https://fr.linkedin.com/in/';
                    $title = 'Linkedin';
                    break;
                case 'instagramId':
                    $socialUrl = 'https://www.instagram.com/';
                    $title = 'Instagram';
                    break;
                case 'pinterestId':
                    $socialUrl = 'https://pinterest.com/';
                    $title = 'Pinterest';
                    break;
                case 'twitterId':
                    $socialUrl = 'https://twitter.com/';
                    $title = 'Twitter';
                    break;
                case 'youtubeId':
                    $socialUrl = 'https://www.youtube.com/channel/';
                    $title = 'Chaîne YouTube';
                    break;
                case 'youtubeUserId':
                    $socialUrl = 'https://www.youtube.com/user/';
                    $title = 'YouTube';
                    break;
                case 'githubId':
                    $socialUrl = 'https://www.github.com/';
                    $title = 'Github';
                    break;
                case 'redditId':
                    $socialUrl = 'https://www.reddit.com/user/';
                    $title = 'Reddit';
                    break;
                case 'twitchId':
                    $socialUrl = 'https://www.twitch.tv/';
                    $title = 'Twitch';
                    break;
                case 'vimeoId':
                    $socialUrl = 'https://vimeo.com/';
                    $title = 'Vimeo';
                    break;
                case 'steamId':
                    $socialUrl = 'https://steamcommunity.com/id/';
                    $title = 'Steam';
                    break;
                default:
                    $socialUrl = '';
            }
            if ($socialId !== '') {
                $socials .= '<a href="' . $socialUrl . $socialId . '" onclick="window.open(this.href);return false" data-tippy-content="' . $title . '" alt="' . $title . '">' . template::ico(substr(str_replace('User', '', $socialName), 0, -2)) . '</a>';
            }
        }
        if ($socials !== '') {
            echo '<div id="footerSocials">' . $socials . '</div>';
        }
    }



    /**
     * Affiche le favicon
     */
    public function showFavicon()
    {
        // Light scheme
        $favicon = $this->getData(['config', 'favicon']);
        if (
            $favicon &&
            file_exists(self::FILE_DIR . 'source/' . $favicon)
        ) {
            echo '<link rel="shortcut icon" media="(prefers-color-scheme:light)" href="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $favicon . '">';
        } else {
            echo '<link rel="shortcut icon" media="(prefers-color-scheme:light)"  href="' . helper::baseUrl(false) . 'core/vendor/zwiico/ico/favicon.ico">';
        }
        // Dark scheme
        $faviconDark = $this->getData(['config', 'faviconDark']);
        if (
            !empty($faviconDark) &&
            file_exists(self::FILE_DIR . 'source/' . $faviconDark)
        ) {
            echo '<link rel="shortcut icon" media="(prefers-color-scheme:dark)" href="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $faviconDark . '">';
            echo '<script src="' . helper::baseUrl(false) . 'core/vendor/favicon-switcher/favicon-switcher.js" crossorigin="anonymous"></script>';
        }
    }


    /**
     * Affiche le menu
     */
    public function showMenu()
    {
        // Met en forme les items du menu
        $itemsLeft = $this->formatMenu(false);

        // Menu extra
        $itemsRight = $this->formatMenu(true);
        // Lien de connexion
        if (
            ($this->getData(['theme', 'menu', 'loginLink'])
                and $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
            )
            or $this->getUrl(0) === 'theme'
        ) {
            $itemsRight .= '<li id="menuLoginLink" ' . ($this->getUrl(0) === 'theme' ? 'class="displayNone"' : '') . '>' .
                template::ico('login', [
                    'href' => helper::baseUrl() . 'user/login/' . strip_tags(str_replace('/', '_', $this->getUrl())),
                    'help' => "Connexion"
                ]) .
                '</li>';
        }
        // Commandes pour les membres simples
        if (
            $this->getUser('group') >= self::GROUP_MEMBER && $this->getUser('group') < self::GROUP_ADMIN
            && $this->getData(['theme', 'menu', 'memberBar']) === true
        ) {
            if (
                $this->getUser('group') >= self::GROUP_MEMBER &&
                $this->getUser('permission', 'filemanager') === true
            ) {
                $itemsRight .= '<li>' . template::ico('folder', [
                    'href' => helper::baseUrl(false) . 'core/vendor/filemanager/dialog.php?type=0&akey=' . md5_file(self::DATA_DIR . 'core.json') . '&lang=' . $this->getData(['user', $this->getUser('id'), 'language']),
                    'attr' => 'data-lity',
                    'help' => 'Fichiers du site'
                ]) . '</li>';
            }
            if (
                $this->getUser('permission', 'user', 'edit') === true
            ) {
                $itemsRight .= '<li>' . template::ico('user', [
                    'help' => 'Mon compte',
                    'margin' => 'right',
                    'href' => helper::baseUrl() . 'user/edit/' . $this->getUser('id')
                ]) . '</li>';
            }
            $itemsRight .= '<li>' .
                template::ico('logout', [
                    'help' => 'Déconnecter',
                    'href' => helper::baseUrl() . 'user/logout',
                    'id' => 'barLogout'
                ]) . '</li>';
        }
        // Retourne les items du menu
        echo '<ul class="navMain" id="menuLeft">' . $itemsLeft . '</ul><ul class="navMain" id="menuRight">' . $itemsRight;
        // Drapeau les langues
        foreach (self::$languages as $key => $value) {
            if (is_dir(self::DATA_DIR . $key)) {
                $t[] = $this->showi18n($key);
            }
        }
        // Pas de drapeau si la langue est unique
        if (count($t) > 1) {
            foreach ($t as $key) {
                echo $key;
            }
        }
        echo '</ul>';
    }

    /**
     * Cette fonction est appelée par showMenu
     * Elle permet de générer le menu selon qu'il s'agisse du menu principal ou du petit menu
     *  @param $menu bool false pour le menu principal, true pour le petit menu
     */
    private function formatMenu($extra = false)
    {
        $items = '';
        $currentPageId = $this->getData(['page', $this->getUrl(0)]) ? $this->getUrl(0) : $this->getUrl(2);
        foreach ($this->getHierarchy() as $parentPageId => $childrenPageIds) {
            // Menu extra ou standard

            if (
                    // Absence de la position extra, la page est toujours affichée à gauche.
                ($this->getData(['page', $parentPageId, 'extraPosition']) !== NULL || $extra === true)
                &&
                $this->getData(['page', $parentPageId, 'extraPosition']) !== $extra
            ) {
                continue;
            }
            // Propriétés de l'item
            $active = ($parentPageId === $currentPageId or in_array($currentPageId, $childrenPageIds)) ? 'active ' : '';
            $targetBlank = $this->getData(['page', $parentPageId, 'targetBlank']) ? ' target="_blank"' : '';
            // Mise en page de l'item
            $items .= '<li id="' . $parentPageId . '">';

            if (
                ($this->getData(['page', $parentPageId, 'disable']) === true
                    and $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
                ) or ($this->getData(['page', $parentPageId, 'disable']) === true
                    and $this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
                    and $this->getUser('group') < self::GROUP_EDITOR
                )
            ) {
                $pageUrl = ($this->getData(['locale', 'homePageId']) === $this->getUrl(0)) ? helper::baseUrl(false) : helper::baseUrl() . $this->getUrl(0);
                $items .= '<a href="' . $pageUrl . '">';
            } else {
                $pageUrl = ($this->getData(['locale', 'homePageId']) === $parentPageId) ? helper::baseUrl(false) : helper::baseUrl() . $parentPageId;
                $items .= '<a class="' . $active . '" href="' . $pageUrl . '"' . $targetBlank . '>';
            }

            switch ($this->getData(['page', $parentPageId, 'typeMenu'])) {
                case '':
                    $items .= $this->getData(['page', $parentPageId, 'shortTitle']);
                    break;
                case 'text':
                    $items .= $this->getData(['page', $parentPageId, 'shortTitle']);
                    break;
                case 'icon':
                    if ($this->getData(['page', $parentPageId, 'iconUrl']) != "") {
                        $items .= '<img alt="' . $this->getData(['page', $parentPageId, 'shortTitle']) . '" src="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['page', $parentPageId, 'iconUrl']) . '" />';
                    } else {
                        $items .= $this->getData(['page', $parentPageId, 'shortTitle']);
                    }
                    break;
                case 'icontitle':
                    if ($this->getData(['page', $parentPageId, 'iconUrl']) != "") {
                        $items .= '<img alt="' . $this->getData(['page', $parentPageId, 'titlshortTitlee']) . '" src="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['page', $parentPageId, 'iconUrl']) . '" data-tippy-content="';
                        $items .= $this->getData(['page', $parentPageId, 'shortTitle']) . '"/>';
                    } else {
                        $items .= $this->getData(['page', $parentPageId, 'shortTitle']);
                    }
                    break;
            }
            // Cas où les pages enfants enfant sont toutes masquées dans le menu
            // ne pas afficher de symbole lorsqu'il n'y a rien à afficher
            $totalChild = 0;
            $disableChild = 0;
            foreach ($childrenPageIds as $childKey) {
                $totalChild += 1;
            }
            if (
                $childrenPageIds && $disableChild !== $totalChild &&
                $this->getdata(['page', $parentPageId, 'hideMenuChildren']) === false
            ) {
                $items .= template::ico('down', ['margin' => 'left']);
            }
            // ------------------------------------------------
            $items .= '</a>';
            if (
                $this->getdata(['page', $parentPageId, 'hideMenuChildren']) === true ||
                empty($childrenPageIds)
            ) {
                continue;
            }
            $items .= '<ul class="navSub">';
            foreach ($childrenPageIds as $childKey) {
                // Propriétés de l'item
                $active = ($childKey === $currentPageId) ? 'active ' : '';
                $targetBlank = $this->getData(['page', $childKey, 'targetBlank']) ? ' target="_blank"' : '';
                // Mise en page du sous-item
                $items .= '<li id=' . $childKey . '>';
                if (
                    ($this->getData(['page', $childKey, 'disable']) === true
                        and $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
                    ) or ($this->getData(['page', $childKey, 'disable']) === true
                        and $this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
                        and $this->getUser('group') < self::GROUP_EDITOR
                    )
                ) {
                    $pageUrl = ($this->getData(['locale', 'homePageId']) === $this->getUrl(0)) ? helper::baseUrl(false) : helper::baseUrl() . $this->getUrl(0);
                    $items .= '<a href="' . $pageUrl . '">';
                } else {
                    $pageUrl = ($this->getData(['locale', 'homePageId']) === $childKey) ? helper::baseUrl(false) : helper::baseUrl() . $childKey;
                    $items .= '<a class="' . $active . ' ' . $parentPageId . '" href="' . $pageUrl . '"' . $targetBlank . '>';
                }

                switch ($this->getData(['page', $childKey, 'typeMenu'])) {
                    case '':
                        $items .= $this->getData(['page', $childKey, 'shortTitle']);
                        break;
                    case 'text':
                        $items .= $this->getData(['page', $childKey, 'shortTitle']);
                        break;
                    case 'icon':
                        if ($this->getData(['page', $childKey, 'iconUrl']) != "") {
                            $items .= '<img alt="' . $this->getData(['page', $parentPageId, 'shortTitle']) . '" src="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['page', $childKey, 'iconUrl']) . '" />';
                        } else {
                            $items .= $this->getData(['page', $parentPageId, 'shortTitle']);
                        }
                        break;
                    case 'icontitle':
                        if ($this->getData(['page', $childKey, 'iconUrl']) != "") {
                            $items .= '<img alt="' . $this->getData(['page', $parentPageId, 'shortTitle']) . '" src="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['page', $childKey, 'iconUrl']) . '" data-tippy-content="';
                            $items .= $this->getData(['page', $childKey, 'shortTitle']) . '"/>';
                        } else {
                            $items .= $this->getData(['page', $childKey, 'shortTitle']);
                        }
                        break;
                    case 'icontext':
                        if ($this->getData(['page', $childKey, 'iconUrl']) != "") {
                            $items .= '<img alt="' . $this->getData(['page', $parentPageId, 'shortTitle']) . '" src="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['page', $childKey, 'iconUrl']) . '" />';
                            $items .= $this->getData(['page', $childKey, 'shortTitle']);
                        } else {
                            $items .= $this->getData(['page', $childKey, 'shortTitle']);
                        }
                        break;
                }
                $items .= '</a></li>';
            }
            $items .= '</ul>';
        }
        return ($items);
    }


    /**
     * Générer un menu pour la barre latérale
     * Uniquement texte
     * @param $onlyChildren n'affiche les sous-pages de la page actuelle
     */
    private function showMenuSide($onlyChildren = null)
    {
        // Met en forme les items du menu
        $items = '';
        // Nom de la page courante
        $currentPageId = $this->getData(['page', $this->getUrl(0)]) ? $this->getUrl(0) : $this->getUrl(2);
        // Nom de la page parente
        $currentParentPageId = $this->getData(['page', $currentPageId, 'parentPageId']);
        // Détermine si on affiche uniquement le parent et les enfants
        // Filtre contient le nom de la page parente

        if ($onlyChildren === true) {
            if (empty($currentParentPageId)) {
                $filterCurrentPageId = $currentPageId;
            } else {
                $filterCurrentPageId = $currentParentPageId;
            }
        } else {
            $items .= '<ul class="menuSide">';
        }

        foreach ($this->getHierarchy() as $parentPageId => $childrenPageIds) {
            // Ne pas afficher les entrées masquées
            if ($this->getData(['page', $parentPageId, 'hideMenuSide']) === true) {
                continue;
            }
            // Filtre actif et nom de la page parente courante différente, on sort de la boucle
            if ($onlyChildren === true && $parentPageId !== $filterCurrentPageId) {
                continue;
            }
            // Propriétés de l'item
            $active = ($parentPageId === $currentPageId or in_array($currentPageId, $childrenPageIds)) ? ' class="active"' : '';
            $targetBlank = $this->getData(['page', $parentPageId, 'targetBlank']) ? ' target="_blank" ' : '';
            // Mise en page de l'item;
            // Ne pas afficher le parent d'une sous-page quand l'option est sélectionnée.
            if ($onlyChildren === false) {
                $items .= '<li class="menuSideChild">';
                if (
                    $this->getData(['page', $parentPageId, 'disable']) === true
                    and $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
                ) {
                    $items .= '<a href="' . $this->getUrl(1) . '">';
                } else {
                    $items .= '<a href="' . helper::baseUrl() . $parentPageId . '"' . $targetBlank . $active . '>';
                }
                $items .= $this->getData(['page', $parentPageId, 'shortTitle']);
                $items .= '</a>';
            }
            $itemsChildren = '';
            foreach ($childrenPageIds as $childKey) {
                // Passer les entrées masquées
                if ($this->getData(['page', $childKey, 'hideMenuSide']) === true) {
                    continue;
                }

                // Propriétés de l'item
                $active = ($childKey === $currentPageId) ? ' class="active"' : '';
                $targetBlank = $this->getData(['page', $childKey, 'targetBlank']) ? ' target="_blank"' : '';
                // Mise en page du sous-item
                $itemsChildren .= '<li class="menuSideChild">';

                if (
                    $this->getData(['page', $childKey, 'disable']) === true
                    and $this->getUser('password') !== $this->getInput('ZWII_USER_PASSWORD')
                ) {
                    $itemsChildren .= '<a href="' . $this->getUrl(1) . '">';
                } else {
                    $itemsChildren .= '<a href="' . helper::baseUrl() . $childKey . '"' . $targetBlank . $active . '>';
                }

                $itemsChildren .= $this->getData(['page', $childKey, 'shortTitle']);
                $itemsChildren .= '</a></li>';
            }
            // Concatène les items enfants
            if (!empty($itemsChildren)) {
                $items .= '<ul class="menuSideChild">';
                $items .= $itemsChildren;
                $items .= '</ul>';
            } else {
                $items .= '</li>';
            }
        }
        if ($onlyChildren === false) {
            $items .= '</ul>';
        }
        // Retourne les items du menu
        echo $items;
    }



    /**
     * Affiche le meta titre
     */
    public function showMetaTitle()
    {
        echo '<title>' . $this->core->output['metaTitle'] . '</title>';
        echo '<meta property="og:title" content="' . $this->core->output['metaTitle'] . '" />';
        if (
            $this->getData(['locale', 'homePageId']) === $this->getUrl(0)
        ) {
            echo '<link rel="canonical" href="' . helper::baseUrl(false) . '" />';
        } else {
            echo '<link rel="canonical" href="' . helper::baseUrl(true) . $this->getUrl() . '" />';
        }
    }

    /**
     * Affiche la meta description
     */
    public function showMetaDescription()
    {
        echo '<meta name="description" content="' . $this->core->output['metaDescription'] . '" />';
        echo '<meta property="og:description" content="' . $this->core->output['metaDescription'] . '" />';
    }

    /**
     * Affiche le meta type
     */
    public function showMetaType()
    {
        echo '<meta property="og:type" content="website" />';
    }

    /**
     * Affiche la meta image (site screenshot)
     */
    public function showMetaImage()
    {
        $imagePath = self::FILE_DIR . 'source/' . $this->getData(['config', 'seo', 'openGraphImage']);
        if (
            $this->getData(['config', 'seo', 'openGraphImage'])
            && file_exists($imagePath)
        ) {
            $typeMime = exif_imagetype($imagePath);
            switch ($typeMime) {
                case IMAGETYPE_JPEG:
                    $typeMime = 'image/jpeg';
                    break;
                case IMAGETYPE_PNG:
                    $typeMime = 'image/png';
                    break;
                default:
                    // Type incorrect
                    return;
            }
            $imageSize = getimagesize($imagePath);
            $wide = $imageSize[0];
            $height = $imageSize[1];
            //Sortie
            $items = '<meta property="og:image" content="' . helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['config', 'seo', 'openGraphImage']) . '" />';
            $items .= '<meta property="og:image:type" content="' . $typeMime . '" />';
            $items .= '<meta property="og:image:width" content="' . $wide . '" />';
            $items .= '<meta property="og:image:height" content="' . $height . '" />';
            echo $items;
        }
    }

    /**
     * Affiche la notification
     */
    public function showNotification()
    {
        if (common::$importNotices) {
            $notification = common::$importNotices[0];
            $notificationClass = 'notificationSuccess';
        }
        if (common::$inputNotices) {
            $notification = 'Impossible de soumettre le formulaire, car il contient des erreurs';
            $notificationClass = 'notificationError';
        }
        if (common::$coreNotices) {
            $notification = sprintf('%s <p> | ', helper::translate('Restauration des bases de données absentes'));
            foreach (common::$coreNotices as $item)
                $notification .= $item . ' | ';
            $notificationClass = 'notificationError';
        } elseif (empty($_SESSION['ZWII_NOTIFICATION_SUCCESS']) === false) {
            $notification = $_SESSION['ZWII_NOTIFICATION_SUCCESS'];
            $notificationClass = 'notificationSuccess';
            unset($_SESSION['ZWII_NOTIFICATION_SUCCESS']);
        } elseif (empty($_SESSION['ZWII_NOTIFICATION_ERROR']) === false) {
            $notification = $_SESSION['ZWII_NOTIFICATION_ERROR'];
            $notificationClass = 'notificationError';
            unset($_SESSION['ZWII_NOTIFICATION_ERROR']);
        } elseif (empty($_SESSION['ZWII_NOTIFICATION_OTHER']) === false) {
            $notification = $_SESSION['ZWII_NOTIFICATION_OTHER'];
            $notificationClass = 'notificationOther';
            unset($_SESSION['ZWII_NOTIFICATION_OTHER']);
        }
        if (isset($notification) and isset($notificationClass)) {
            echo '<div id="notification" class="' . $notificationClass . '">' . $notification . '<span id="notificationClose">' . template::ico('cancel') . '<!----></span><div id="notificationProgress"></div></div>';
        }
    }

    /**
     * Affiche la barre de membre
     */
    public function showBar()
    {
        if ($this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')) {
            // Items de gauche
            $leftItems = '';
            // Sélecteur de langues
            if ($this->getUser('group') >= self::GROUP_EDITOR) {
                $c = 0;
                $leftItem = '';
                foreach (self::$languages as $key => $value) {
                    if (is_dir(self::DATA_DIR . $key)) {
                        $c++;
                        $location = helper::baseUrl() . 'language/content/' . $key;
                        $leftItem .= '<option name="' . $key . '" value="' . $location . '" ' . ($key === self::$i18nContent ? 'selected' : '') . '>' . $value . '</option>';
                    }
                }
                if ($c > 1) {
                    $leftItems .= '<li><select id="barSelectLanguage" >';
                    $leftItems .= $leftItem;
                    $leftItems .= '</select></li>';
                }
            }
            // Liste des pages
            if ($this->getUser('group') >= self::GROUP_EDITOR) {
                $leftItems .= '<li><select id="barSelectPage">';
                $leftItems .= '<option value="">' . helper::translate('Pages du site') . '</option>';
                $leftItems .= '<optgroup label="' . helper::translate('Pages orphelines') . '">';
                $orpheline = true;
                $currentPageId = $this->getData(['page', $this->getUrl(0)]) ? $this->getUrl(0) : $this->getUrl(2);
                foreach ($this->getHierarchy(null, false) as $parentPageId => $childrenPageIds) {
                    if (
                        $this->getData(['page', $parentPageId, 'position']) !== 0 &&
                        $orpheline
                    ) {
                        $orpheline = false;
                        $leftItems .= '<optgroup label="' . helper::translate('Pages dans le menu') . '">';
                    }
                    // Exclure les barres
                    if ($this->getData(['page', $parentPageId, 'block']) !== 'bar') {
                        $leftItems .= '<option value="' .
                            helper::baseUrl() .
                            $parentPageId . '"' .
                            ($parentPageId === $currentPageId ? ' selected' : false) .
                            ' class="' .
                            ($this->getData(['page', $parentPageId, 'disable']) === true ? 'pageInactive' : '') .
                            ($this->getData(['page', $parentPageId, 'position']) === 0 ? ' pageHidden' : '') .
                            '">' .
                            $this->getData(['page', $parentPageId, 'shortTitle']) .
                            '</option>';
                        foreach ($childrenPageIds as $childKey) {
                            $leftItems .= '<option value="' .
                                helper::baseUrl() .
                                $childKey . '"' .
                                ($childKey === $currentPageId ? ' selected' : false) .
                                ' class="' .
                                ($this->getData(['page', $childKey, 'disable']) === true ? 'pageInactive' : '') .
                                ($this->getData(['page', $childKey, 'position']) === 0 ? ' pageHidden' : '') .
                                '">&nbsp;&nbsp;&nbsp;&nbsp;' .
                                $this->getData(['page', $childKey, 'shortTitle']) .
                                '</option>';
                        }
                    }
                }
                $leftItems .= '</optgroup' >
                    // Afficher les barres
                    $leftItems .= '<optgroup label="' . helper::translate('Barres latérales') . '">';
                foreach ($this->getHierarchy(null, false, true) as $parentPageId => $childrenPageIds) {
                    $leftItems .= '<option value="' . helper::baseUrl() . $parentPageId . '"' . ($parentPageId === $currentPageId ? ' selected' : false) . '>' . $this->getData(['page', $parentPageId, 'shortTitle']) . '</option>';
                    foreach ($childrenPageIds as $childKey) {
                        $leftItems .= '<option value="' . helper::baseUrl() . $childKey . '"' . ($childKey === $currentPageId ? ' selected' : false) . '>&nbsp;&nbsp;&nbsp;&nbsp;' . $this->getData(['page', $childKey, 'shortTitle']) . '</option>';
                    }
                }
                $leftItems .= '</optgroup>';
                $leftItems .= '</select></li>';
                // Bouton Ajouter une page
                if ($this->getUser('permission', 'page', 'add')) {
                    $leftItems .= '<li>' . template::ico('plus', [
                        'href' => helper::baseUrl() . 'page/add',
                        'help' => 'Nouvelle page ou barre latérale'
                    ]) . '</li>';
                }
                if (
                    // Sur un module de page qui autorise le bouton de modification de la page
                    $this->core->output['showBarEditButton']
                    // Sur une page sans module
                    or $this->getData(['page', $this->getUrl(0), 'moduleId']) === ''
                    // Sur une page avec un module invalide
                    or (!is_null($this->getData(['page', $this->getUrl(2), 'moduleId'])) &&
                        !class_exists($this->getData(['page', $this->getUrl(2), 'moduleId']))
                    )
                    // Sur une page d'accueil
                    or $this->getUrl(0) === ''
                ) {
                    // Bouton Editer une page
                    if ($this->getUser('permission', 'page', 'edit')) {
                        $leftItems .= '<li>' . template::ico('pencil', [
                            'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
                            'help' => 'Éditer la page'
                        ]) . '</li>';
                    }
                    // Bouton Editer le module d'une page
                    if (
                        $this->getUser('permission', 'page', 'module')
                        && $this->getData(['page', $this->getUrl(0), 'moduleId'])
                    ) {
                        $leftItems .= '<li>' . template::ico('gear', [
                            'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
                            'help' => 'Module de la page'
                        ]) . '</li>';
                    }
                    // Bouton dupliquer une page
                    if (
                        $this->getUser('permission', 'page', 'duplicate')
                    ) {
                        $leftItems .= '<li>' . template::ico('clone', [
                            'href' => helper::baseUrl() . 'page/duplicate/' . $this->getUrl(0),
                            'help' => 'Dupliquer la page'
                        ])
                            . '</li>';
                    }
                    // Bouton Effacer une page
                    if (
                        $this->getUser('permission', 'page', 'delete')
                    ) {
                        $leftItems .= '<li>' . template::ico('trash', [
                            'href' => helper::baseUrl() . 'page/delete/' . $this->getUrl(0),
                            'help' => 'Supprimer la page',
                            'id' => 'pageDelete'
                        ])
                            . '</li>';
                    }
                }
            }
            // Items de droite
            $rightItems = '';
            if (
                $this->getUser('group') >= self::GROUP_EDITOR
                && $this->getUser(
                    'permission',
                    'filemanager'
                )
            ) {
                $rightItems .= '<li>' . template::ico('folder', [
                    'help' => 'Fichiers',
                    'href' => helper::baseUrl(false) . 'core/vendor/filemanager/dialog.php?type=0&akey=' . md5_file(self::DATA_DIR . 'core.json') . '&lang=' . $this->getData(['user', $this->getUser('id'), 'language']),
                    'attr' => 'data-lity'
                ]) . '</li>';
            }
            if ($this->getUser('group') >= self::GROUP_ADMIN) {
                $rightItems .= '<li>' . template::ico('brush', [
                    'help' => 'Thème',
                    'href' => helper::baseUrl() . 'theme'
                ]) . '</li>';
                $rightItems .= '<li>' . template::ico('puzzle', [
                    'help' => 'Modules',
                    'href' => helper::baseUrl() . 'plugin'
                ]) . '</li>';
                $rightItems .= '<li>' . template::ico('flag', [
                    'help' => 'Langues',
                    'href' => helper::baseUrl() . 'language'
                ]) . '</li>';
                $rightItems .= '<li>' . template::ico('cog-alt', [
                    'help' => 'Configuration',
                    'href' => helper::baseUrl() . 'config'
                ]) . '</li>';
                $rightItems .= '<li>' . template::ico('users', [
                    'help' => 'Utilisateurs',
                    'href' => helper::baseUrl() . 'user'
                ]) . '</li>';
                // Mise à jour automatique
                $today = mktime(0, 0, 0);
                $checkUpdate = $this->getData(['core', 'lastAutoUpdate']);
                // Recherche d'une mise à jour si active, si une mise à jour n'est pas déjà disponible et le délai journalier est dépassé.
                if (
                    $this->getData(['config', 'autoUpdate'])
                ) {
                    if (
                        $today > $checkUpdate + $this->getData(['config', 'autoUpdateDelay', 86400])
                    ) {
                        // Dernier auto controle
                        $this->setData(['core', 'lastAutoUpdate', $today]);
                        if (
                            helper::checkNewVersion(common::ZWII_UPDATE_CHANNEL)
                        ) {
                            $this->setData(['core', 'updateAvailable', true]);
                        }
                    }
                }


                // Afficher le bouton : Mise à jour détectée + activée
                if ($this->getData(['core', 'updateAvailable'])) {
                    $rightItems .= '<li><a href="' . helper::baseUrl() . 'install/update" data-tippy-content="Mettre à jour Zwii ' . common::ZWII_VERSION . ' vers ' . helper::getOnlineVersion(common::ZWII_UPDATE_CHANNEL) . '">' . template::ico('update colorRed') . '</a></li>';
                }
            }
            if (
                $this->getUser('group') >= self::GROUP_EDITOR
                && $this->getUser('permission', 'user', 'edit')

            ) {
                $rightItems .= '<li><a href="' . helper::baseUrl() . 'user/edit/' . $this->getUser('id') .
                    '" data-tippy-content="' . helper::translate('Configurer mon compte') . '">' .
                    template::ico('user', ['margin' => 'right']) . '<span id="displayUsername">' . $this->getUser('firstname') . ' ' . $this->getUser('lastname') .
                    '</span></a></li>';
            }
            $rightItems .= '<li>' . template::ico('logout', [
                'help' => 'Déconnecter',
                'href' => helper::baseUrl() . 'user/logout',
                'id' => 'barLogout'
            ]) . '</li>';
            // Barre de membre
            echo '<div id="bar"><div class="container"><ul id="barLeft">' . $leftItems . '</ul><ul id="barRight">' . $rightItems . '</ul></div></div>';
        }
    }

    /**
     * Affiche le script
     */
    public function showScript()
    {
        ob_start();
        require 'core/core.js.php';
        $coreScript = ob_get_clean();
        $inlineScript = '';
        if ($this->core->output['inlineScript']) {
            $inlineScript = implode($this->core->output['inlineScript']);
        }
        echo '<script defer>' . helper::minifyJs($coreScript . $this->core->output['script']) . '</script>';
        echo '<script defer>' . helper::minifyJs(htmlspecialchars_decode($inlineScript)) . '</script>';
    }

    /**
     * Affiche le style
     */
    public function showStyle()
    {
        // Import des styles liés à la page
        if ($this->core->output['style']) {
            echo '<base href="' . helper::baseUrl(true) . '">';
            if (strpos($this->core->output['style'], 'admin.css') >= 1) {
                echo '<link rel="stylesheet" href="' . self::DATA_DIR . 'admin.css?' . md5_file(self::DATA_DIR . 'admin.css') . '">';
            }
            echo '<style type="text/css">' . helper::minifyCss($this->core->output['style']) . '</style>';
        }
    }

    /**
     * Affiche le style interne des pages
     */
    public function showInlineStyle()
    {
        // Import des styles liés à la page
        if ($this->core->output['inlineStyle']) {
            foreach ($this->core->output['inlineStyle'] as $style) {
                if ($style) {
                    echo '<style type="text/css">' . helper::minifyCss(htmlspecialchars_decode($style)) . '</style>';
                }

            }
        }
    }

    /**
     * Importe les polices de caractères
     */
    public function showFonts()
    {
        // Import des fontes liées au thème
        if (file_exists(self::DATA_DIR . 'font/font.html')) {
            include_once(self::DATA_DIR . 'font/font.html');
        }
    }

    /**
     * Affiche l'import des librairies
     */
    public function showVendor()
    {
        // Variables partagées
        $vars = 'var baseUrl = ' . json_encode(helper::baseUrl(false)) . ';';
        $vars .= 'var baseUrlQs = ' . json_encode(helper::baseUrl()) . ';';
        if (
            $this->getUser('password') === $this->getInput('ZWII_USER_PASSWORD')
            and $this->getUser('group') >= self::GROUP_EDITOR
        ) {
            $vars .= 'var privateKey = ' . json_encode(md5_file(self::DATA_DIR . 'core.json')) . ';';
        }
        echo '<script defer >' . helper::minifyJs($vars) . '</script>';
        // Librairies
        $moduleId = $this->getData(['page', $this->getUrl(0), 'moduleId']);
        foreach ($this->core->output['vendor'] as $vendorName) {
            // Coeur
            if (file_exists('core/vendor/' . $vendorName . '/inc.json')) {
                $vendorPath = 'core/vendor/' . $vendorName . '/';
            }
            // Module
            elseif (
                $moduleId
                and in_array($moduleId, self::$coreModuleIds) === false
                and file_exists(self::MODULE_DIR . $moduleId . '/vendor/' . $vendorName . '/inc.json')
            ) {
                $vendorPath = self::MODULE_DIR . $moduleId . '/vendor/' . $vendorName . '/';
            }
            // Sinon continue
            else {
                continue;
            }
            // Détermine le type d'import en fonction de l'extension de la librairie
            $vendorFiles = json_decode(file_get_contents($vendorPath . 'inc.json'));
            foreach ($vendorFiles as $vendorFile) {
                switch (pathinfo($vendorFile, PATHINFO_EXTENSION)) {
                    case 'css':
                        // Force le rechargement lors d'une mise à jour du jeu d'icônes
                        $reload = $vendorPath === 'core/vendor/zwiico/'
                            ? '?' . md5_file('core/vendor/zwiico/css/zwiico-codes.css')
                            : '';
                        echo '<link rel="stylesheet" href="' . helper::baseUrl(false) . $vendorPath . $vendorFile . $reload . '">';
                        break;
                    case 'js':
                        echo '<script src="' . helper::baseUrl(false) . $vendorPath . $vendorFile . '"></script>';
                        break;
                }
            }
        }
    }
    /**
     * Affiche le cadre avec les drapeaux sélectionnés
     */
    public function showi18n($lang)
    {
        if (
            (isset($_SESSION['ZWII_CONTENT'])
                and $_SESSION['ZWII_CONTENT'] === $lang
            )
        ) {
            $select = ' class="i18nFlagSelected" ';
        } else {
            $select = ' class="i18nFlag" ';
        }

        $items = '<li>';
        $items .= '<a href="' . helper::baseUrl() . 'language/content/' . $lang . '"><img ' . $select . ' alt="' . self::$languages[$lang] . '" src="' . helper::baseUrl(false) . 'core/vendor/i18n/png/' . $lang . '.png"/></a>';
        $items .= '</li>';
        return $items;
    }

    // Affiche une icône de navigation
    // @param $position string 'top' or 'bottom
    public function showNavButtons($position)
    {
        // Boutons par défaut
        $leftButton = 'left';
        $rightButton = 'right-dir';

        // Déterminer la hiérarchie des pages
        $hierarchy = array();
        foreach ($this->getHierarchy() as $parentKey => $parentValue) {
            $hierarchy[] = $parentKey;
            foreach ($parentValue as $childKey) {
                $hierarchy[] = $childKey;
            }
        }
        // Parcourir la hiérarchie et rechercher les éléments avant et après
        $elementToFind = $this->getUrl(0);

        // Trouver la clé de l'élément recherché
        $key = array_search($elementToFind, $hierarchy);

        if ($key !== false) {
            // Trouver l'élément précédent
            $previousKey = ($key > 0) ? $key - 1 : null;
            $previousValue = ($previousKey !== null) ? $hierarchy[$previousKey] : null;

            // Trouver l'élément suivant
            $nextKey = ($key < count($hierarchy) - 1) ? $key + 1 : null;
            $nextValue = ($nextKey !== null) ? $hierarchy[$nextKey] : null;

            $previousPage = $previousValue;
            $nextPage = $nextValue;
        }

        // Jeux d'icônes sinon celui par défaut
        if ($this->getData(['page', $this->getUrl(0), 'navTemplate'])) {
            $leftButton = self::$navIconTemplate[$this->getData(['page', $this->getUrl(0), 'navTemplate'])]['left'];
            $rightButton = self::$navIconTemplate[$this->getData(['page', $this->getUrl(0), 'navTemplate'])]['right'];
        }

        $items = '<div class="navButton">';
        $items .= '<div class="row">';
        $items .= '<div class="col1">';
        if ($previousPage !== null and $this->getData(['page', $this->getUrl(0), 'navLeft']) === $position) {
            $items .= template::button('navPreviousButtonLeft', [
                'href' => helper::baseUrl() . $previousPage,
                'value' => template::ico($leftButton)
            ]);
        }
        $items .= '</div>';
        $items .= '<div class="col1 offset10">';
        if ($nextPage !== null and $this->getData(['page', $this->getUrl(0), 'navRight']) === $position) {
            $items .= template::button('navNextButtonRight', [
                'href' => helper::baseUrl() . $nextPage,
                'value' => template::ico($rightButton)
            ]);
        }
        $items .= '</div></div></div>';
        echo $items;
    }
}