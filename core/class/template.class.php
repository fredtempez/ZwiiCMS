<?php

class template
{

    /**
     * Crée un bouton
     * @param string $nameId Nom et id du champ
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function button($nameId, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'class' => '',
            'disabled' => false,
            'href' => 'javascript:void(0);',
            'ico' => '',
            'id' => $nameId,
            'name' => $nameId,
            'target' => '',
            'uniqueSubmission' => false,
            'value' => 'Bouton',
            'help' => ''
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        $attributes['value'] = helper::translate($attributes['value']);
        $attributes['help'] = helper::translate($attributes['help']);
        // Retourne le html
        return sprintf(
            '<a %s class="button %s %s %s" %s>%s</a>',
            helper::sprintAttributes($attributes, ['class', 'disabled', 'ico', 'value']),
            $attributes['disabled'] ? 'disabled' : '',
            $attributes['class'],
            $attributes['uniqueSubmission'] ? 'uniqueSubmission' : '',
            $attributes['help'] ? ' title="' . $attributes['help'] . '" ' : '',
            ($attributes['ico'] ? template::ico($attributes['ico'], ['margin' => 'right']) : '') . $attributes['value']
        );
    }

    /**
     * Crée un champ captcha
     * @param string $nameId Nom et id du champ
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function captcha($nameId, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'class' => '',
            'classWrapper' => '',
            'help' => '',
            'id' => $nameId,
            'name' => $nameId,
            'value' => '',
            'limit' => false, // captcha simple
            'type' => 'alpha' // num(érique) ou alpha(bétique)
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        // $attributes['value'] = helper::translate($attributes['value']);
        $attributes['help'] = helper::translate($attributes['help']);
        // Captcha quatre opérations
        // Limite addition et soustraction selon le type de captcha
        $numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14, 15, 16, 17, 18, 19, 20];
        $letters = ['u', 't', 's', 'r', 'q', 'p', 'o', 'n', 'm', 'l', 'k', 'j', 'i', 'h', 'g', 'f', 'e', 'd', 'c', 'b', 'a'];
        $limit = $attributes['limit'] ? count($letters) - 1 : 10;

        // Tirage de l'opération
        mt_srand();
        // Captcha simple limité à l'addition
        $operator = $attributes['limit'] ? mt_rand(1, 4) : 1;

        // Limite si multiplication ou division
        if ($operator > 2) {
            $limit = 10;
        }

        // Tirage des nombres
        mt_srand();
        $firstNumber = mt_rand(1, $limit);
        mt_srand();
        $secondNumber = mt_rand(1, $limit);

        // Permutation si addition ou soustraction
        if (($operator < 3) and ($firstNumber < $secondNumber)) {
            $temp = $firstNumber;
            $firstNumber = $secondNumber;
            $secondNumber = $temp;
        }

        // Icône de l'opérateur et calcul du résultat
        switch ($operator) {
            case 1:
                $operator = template::ico('plus', ['fontSize' => '2em;']);
                $result = $firstNumber + $secondNumber;
                break;
            case 2:
                $operator = template::ico('minus', ['fontSize' => '2em;']);
                $result = $firstNumber - $secondNumber;
                break;
            case 3:
                $operator = template::ico('cancel', ['fontSize' => '2em;']);
                $result = $firstNumber * $secondNumber;
                break;
            case 4:
                $operator = template::ico('divide', ['fontSize' => '2em;']);
                $limit2 = [10, 10, 6, 5, 4, 3, 2, 2, 2, 2];
                for ($i = 1; $i <= $firstNumber; $i++) {
                    $limit = $limit2[$i - 1];
                }
                mt_srand();
                $secondNumber = mt_rand(1, $limit);
                $firstNumber = $firstNumber * $secondNumber;
                $result = $firstNumber / $secondNumber;
                break;
        }

        // Hashage du résultat
        $result = password_hash($result, PASSWORD_BCRYPT);

        // Codage des valeurs de l'opération
        $firstLetter = uniqid();
        $secondLetter = uniqid();

        // Masquage image source pour éviter un décodage
        copy('core/vendor/zwiico/png/' . $attributes['type'] . '/' . $letters[$firstNumber] . '.png', 'site/tmp/' . $firstLetter . '.png');
        copy('core/vendor/zwiico/png/' . $attributes['type'] . '/' . $letters[$secondNumber] . '.png', 'site/tmp/' . $secondLetter . '.png');


        // Début du wrapper
        $html = '<div id="' . $attributes['id'] . 'Wrapper" class="captcha inputWrapper ' . $attributes['classWrapper'] . '">';
        // Label
        $html .= self::label(
            $attributes['id'],
            '<img class="captcha' . ucFirst($attributes['type']) . '"  src="' . helper::baseUrl(false) . 'site/tmp/' . $firstLetter . '.png" />&nbsp;<strong>' . $operator . '</strong>&nbsp;<img class="captcha' . ucFirst($attributes['type']) . '" src="' . helper::baseUrl(false) . 'site/tmp/' . $secondLetter . '.png" />' . template::ico('eq', ['fontSize' => '2em;']),
            [
                'help' => $attributes['help']
            ]
        );

        // Notice
        $notice = '';
        if (array_key_exists($attributes['id'], common::$inputNotices)) {
            $notice = common::$inputNotices[$attributes['id']];
            $attributes['class'] .= ' notice';
        }
        $html .= self::notice($attributes['id'], $notice);

        // captcha
        $html .= sprintf(
            '<input type="text" %s>',
            helper::sprintAttributes($attributes)
        );

        // Champ résultat codé
        $html .= self::hidden($attributes['id'] . 'Result', [
            'value' => $result,
            'before' => false
        ]);

        // Fin du wrapper
        $html .= '</div>';

        // Retourne le html
        return $html;
    }

    /**
     * Crée une case à cocher à sélection multiple
     * @param string $nameId Nom et id du champ
     * @param string $value Valeur de la case à cocher
     * @param string $label Label de la case à cocher
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function checkbox($nameId, $value, $label, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'before' => true,
            'checked' => '',
            'class' => '',
            'classWrapper' => '',
            'disabled' => false,
            'help' => '',
            'id' => $nameId,
            'name' => $nameId
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        $label = helper::translate($label);
        $attributes['help'] = helper::translate($attributes['help']);
        // Sauvegarde des données en cas d'erreur
        if ($attributes['before'] and array_key_exists($attributes['id'], common::$inputBefore)) {
            $attributes['checked'] = (bool) common::$inputBefore[$attributes['id']];
        }
        // Début du wrapper
        $html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
        // Notice
        $notice = '';
        if (array_key_exists($attributes['id'], common::$inputNotices)) {
            $notice = common::$inputNotices[$attributes['id']];
            $attributes['class'] .= ' notice';
        }
        $html .= self::notice($attributes['id'], $notice);
        // Case à cocher
        $html .= sprintf(
            '<input type="checkbox" value="%s" %s>',
            $value,
            helper::sprintAttributes($attributes)
        );
        // Label
        $html .= self::label($attributes['id'], '<span>' . $label . '</span>', [
            'help' => $attributes['help']
        ]);
        // Fin du wrapper
        $html .= '</div>';
        // Retourne le html
        return $html;
    }

    /**
     * Crée un champ date
     * @param string $nameId Nom et id du champ
     * @param array $attributes Attributs ($key => $value)
     * @param string type date seule ;  time heure seule ;  datetime-local (jour et heure)
     * @return string
     */
    public static function date($nameId, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'autocomplete' => 'on',
            'before' => true,
            'class' => '',
            'classWrapper' => '',
            'noDirty' => false,
            'disabled' => false,
            'help' => '',
            'id' => $nameId,
            'label' => '',
            'name' => $nameId,
            'placeholder' => '',
            'readonly' => false,
            'value' => '',
            'type' => 'date',
            'required' => false,
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        $attributes['label'] = helper::translate($attributes['label']);
        $attributes['help'] = helper::translate($attributes['help']);
        //$attributes['placeholder'] = helper::translate($attributes['placeholder']);
        // Filtre selon le type 
        switch ($attributes['type']) {
            case 'datetime-local':
                $filter = helper::FILTER_TIMESTAMP;
                break;
            case 'date':
                $filter = helper::FILTER_DATE; // Pour générer une valeur uniquement sur la date
                break;
            case 'time':
                $filter = helper::FILTER_TIME; // Pour générer une valeur uniquement sur l'heure
                break;
            default:
                $filter = null; // pas de filtre pour month and year
                break;
        }
        // Sauvegarde des données en cas d'erreur
        if ($attributes['before'] and array_key_exists($attributes['id'], common::$inputBefore)) {
            $attributes['value'] = common::$inputBefore[$attributes['id']];
        } else {
            $attributes['value'] = ($attributes['value'] ? helper::filter($attributes['value'], $filter) : '');
        }
        // Gestion du champ obligatoire 
        if (isset($attributes['required']) && $attributes['required']) {
            // Affiche l'astérisque dans le label
            $required = ' required-field';
            // Ajoute l'attribut required au champ input
            $attributes['required'] = 'required';
        }
        // Début du wrapper
        $html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
        // Label
        if ($attributes['label']) {
            $html .= self::label($attributes['id'], $attributes['label'], [
                'help' => $attributes['help'],
                // Ajoute la classe required-field si le champ est obligatoire
                'class' => isset($required) ? $required : ''
            ]);
        }
        // Notice
        $notice = '';
        if (array_key_exists($attributes['id'], common::$inputNotices)) {
            $notice = common::$inputNotices[$attributes['id']];
            $attributes['class'] .= ' notice';
        }
        $html .= self::notice($attributes['id'], $notice);
        // Date visible
        $html .= '<div class="inputDateManagerWrapper">';
        $html .= sprintf(
            '<input type="' . $attributes['type'] . '" class="datepicker %s" value="%s" %s>',
            $attributes['class'],
            $attributes['value'],
            helper::sprintAttributes($attributes, ['class', 'value'])
        );
        $html .= '</div>';
        // Fin du wrapper
        $html .= '</div>';
        // Retourne le html
        return $html;
    }


    /**
     * Crée un champ d'upload de fichier
     * @param string $nameId Nom et id du champ
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function file($nameId, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'before' => true,
            'class' => '',
            'classWrapper' => '',
            'noDirty' => false,
            'disabled' => false,
            'extensions' => '',
            'help' => '',
            'id' => $nameId,
            'label' => '',
            'maxlength' => '500',
            'name' => $nameId,
            'type' => 2,
            'value' => '',
            'folder' => '',
            'language' => 'fr_FR',
            'required' => false,
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        $attributes['value'] = helper::translate($attributes['value']);
        $attributes['help'] = helper::translate($attributes['help']);
        // Sauvegarde des données en cas d'erreur
        if ($attributes['before'] and array_key_exists($attributes['id'], common::$inputBefore)) {
            $attributes['value'] = common::$inputBefore[$attributes['id']];
        }
        // Gestion du champ obligatoire 
        if (isset($attributes['required']) && $attributes['required']) {
            // Affiche l'astérisque dans le label
            $required = ' required-field';
            // Ajoute l'attribut required au champ input
            $attributes['required'] = 'required';
        }
        // Début du wrapper
        $html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
        // Notice
        $notice = '';
        if (array_key_exists($attributes['id'], common::$inputNotices)) {
            $notice = common::$inputNotices[$attributes['id']];
            $attributes['class'] .= ' notice';
        }
        $html .= self::notice($attributes['id'], $notice);
        // Label
        if ($attributes['label']) {
            $html .= self::label($attributes['id'], $attributes['label'], [
                'help' => $attributes['help'],
                // Ajoute la classe required-field si le champ est obligatoire
                'class' => isset($required) ? $required : ''
            ]);
        }
        // Champ caché contenant l'url de la page
        $html .= self::hidden($attributes['id'], [
            'class' => 'inputFileHidden',
            'disabled' => $attributes['disabled'],
            'maxlength' => $attributes['maxlength'],
            'value' => $attributes['value']
        ]);
        // Champ d'upload
        $html .= '<div class="inputFileManagerWrapper">';
        $html .= sprintf(
            '<a
                href="' .
                helper::baseUrl(false) . 'core/vendor/filemanager/dialog.php' .
                '?relative_url=1' .
                '&lang=' . $attributes['language'] .
                '&field_id=' . $attributes['id'] .
                '&type=' . $attributes['type'] .
                '&akey=' . md5_file(core::DATA_DIR . 'core.json') .
                // Ajoute le nom du dossier si la variable est passée
                (empty($attributes['folder']) ? '&fldr=/' : '&fldr=' . $attributes['folder']) .
                ($attributes['extensions'] ? '&extensions=' . $attributes['extensions'] : '')
                . '"
                class="inputFile %s %s"
                %s
                data-lity
            >
                ' . self::ico('upload-cloud', ['margin' => 'right']) . '
                <span class="inputFileLabel"></span>
            </a>',
            $attributes['class'],
            $attributes['disabled'] ? 'disabled' : '',
            helper::sprintAttributes($attributes, ['class', 'extensions', 'type', 'maxlength'])

        );
        $html .= self::button($attributes['id'] . 'Delete', [
            'class' => 'inputFileDelete',
            'value' => self::ico('cancel')
        ]);
        $html .= '</div>';
        // Fin du wrapper
        $html .= '</div>';
        // Retourne le html
        return $html;
    }

    /**
     * Ferme un formulaire
     * @return string
     */
    public static function formClose()
    {
        return '</form>';
    }

    /**
     * Ouvre un formulaire protégé par CSRF
     * @param string $id Id du formulaire
     * @return string
     */
    public static function formOpen($id)
    {
        // Ouverture formulaire
        $html = '<form id="' . $id . '" method="post">';
        // Stock le token CSRF
        $html .= self::hidden('csrf', [
            'value' => htmlentities($_SESSION['csrf'], ENT_QUOTES | ENT_HTML5, 'UTF-8')
        ]);
        // Retourne le html
        return $html;
    }



    /**
     * Crée une aide qui s'affiche au survole
     * @param string $text Texte de l'aide
     * @return string
     */
    public static function help($text)
    {
        return '<span class="helpButton" data-tippy-content="' . $text . '">' . self::ico('help') . '<!----></span>';
    }

    /**
     * Crée un champ caché
     * @param string $nameId Nom et id du champ
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function hidden($nameId, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'before' => true,
            'class' => '',
            'noDirty' => false,
            'id' => $nameId,
            //'maxlength' => '500',
            'name' => $nameId,
            'value' => ''
        ], $attributes);
        // Sauvegarde des données en cas d'erreur
        if ($attributes['before'] and array_key_exists($attributes['id'], common::$inputBefore)) {
            $attributes['value'] = common::$inputBefore[$attributes['id']];
        }
        // Texte
        $html = sprintf('<input type="hidden" %s>', helper::sprintAttributes($attributes, ['before']));
        // Retourne le html
        return $html;
    }

    /**
     * Crée un icône
     * @Array :
     * @param string $ico Classe de l'icône
     * @param string $margin Ajoute un margin autour de l'icône (choix : left, right, all)
     * @param bool $animate Ajoute une animation à l'icône
     * @param string $fontSize Taille de la police
     * @param string $href lien vers une url
     * @param string $help popup d'aide
     * @param string $id de l'élement
     * @return string
     */
    // public static function ico($ico, $margin = '', $animate = false, $fontSize = '1em') {
    public static function ico($ico, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'margin' => '',
            'animate' => false,
            'fontSize' => '1em',
            'href' => '',
            'attr' => '',
            'help' => '',
            'id' => '',
        ], $attributes);
        // Traduction de l'aide
        $attributes['help'] = helper::translate($attributes['help']);
        // Contenu de l'icône
        $alt = $attributes['help'] ? $attributes['help'] : $ico;
        $item = $attributes['href'] ? '<a id="' . $attributes['id'] . '" data-tippy-content="' . $attributes['help'] . '" alt="' . $alt . '" href="' . $attributes['href'] . '" ' . $attributes['attr'] . ' >' : '';
        $item .= '<span class="zwiico-' . $ico . ($attributes['margin'] ? ' zwiico-margin-' . $attributes['margin'] : '') . ($attributes['animate'] ? ' animate-spin' : '') . '" style="font-size:' . $attributes['fontSize'] . '"><!----></span>';
        $item .= ($attributes['href']) ? '</a>' : '';
        return $item;
    }

    /**
     * Crée un drapeau du site courante
     * @param string $langId Id de la langue à affiche ou selected pour la langue courante
     * @param string size en pixels ou en rem
     * @return string
     */
    public static function flag($langId, $size = 'auto')
    {
        $lang = 'fr_FR';
        switch ($langId) {
            case '':
                break;
            case array_key_exists($langId, core::$languages):
                $lang = $langId;
                break;
            case 'selected':
                if (isset($_SESSION['ZWII_SITE_CONTENT'])) {
                    $lang = $_SESSION['ZWII_SITE_CONTENT'];
                } else {
                    $lang = 'fr_FR';
                }
        }
        return '<img class="flag" src="' . helper::baseUrl(false) . 'core/vendor/i18n/png/' . $lang . '.png"
                width="' . $size . '"
                height="' . $size . '"
                title="' . $lang . '"
                alt="(' . $lang . ')"/>';
    }

    /**
     * Crée un label
     * @param string $for For du label
     * @param array $attributes Attributs ($key => $value)
     * @param string $text Texte du label
     * @return string
     */
    public static function label($for, $text, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'class' => '',
            'for' => $for,
            'help' => ''
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        $text = helper::translate($text);
        $attributes['help'] = helper::translate($attributes['help']);

        if (
            get_called_class() !== 'template'
        ) {
            $attributes['help'] = helper::translate($attributes['help']);
        }
        if ($attributes['help'] !== '') {
            $text = $text . self::help($attributes['help']);
        }
        // Retourne le html
        return sprintf(
            '<label %s>%s</label>',
            helper::sprintAttributes($attributes),
            $text
        );
    }

    /**
     * Crée un champ mail
     * @param string $nameId Nom et id du champ
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function mail($nameId, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'autocomplete' => 'on',
            'before' => true,
            'class' => '',
            'classWrapper' => '',
            'noDirty' => false,
            'disabled' => false,
            'help' => '',
            'id' => $nameId,
            'label' => '',
            //'maxlength' => '500',
            'name' => $nameId,
            'placeholder' => '',
            'readonly' => false,
            'value' => '',
            'required' => false,
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        $attributes['label'] = helper::translate($attributes['label']);
        $attributes['help'] = helper::translate($attributes['help']);
        //$attributes['placeholder'] = helper::translate($attributes['placeholder']);
        // Sauvegarde des données en cas d'erreur
        if ($attributes['before'] and array_key_exists($attributes['id'], common::$inputBefore)) {
            $attributes['value'] = common::$inputBefore[$attributes['id']];
        }
        // Gestion du champ obligatoire 
        if (isset($attributes['required']) && $attributes['required']) {
            // Affiche l'astérisque dans le label
            $required = ' required-field';
            // Ajoute l'attribut required au champ input
            $attributes['required'] = 'required';
        }
        // Début du wrapper
        $html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
        // Label
        if ($attributes['label']) {
            $html .= self::label($attributes['id'], $attributes['label'], [
                'help' => $attributes['help'],
                // Ajoute la classe required-field si le champ est obligatoire
                'class' => isset($required) ? $required : ''
            ]);
        }
        // Notice
        $notice = '';
        if (array_key_exists($attributes['id'], common::$inputNotices)) {
            $notice = common::$inputNotices[$attributes['id']];
            $attributes['class'] .= ' notice';
        }
        $html .= self::notice($attributes['id'], $notice);
        // Texte
        $html .= sprintf(
            '<input type="email" %s>',
            helper::sprintAttributes($attributes)
        );
        // Fin du wrapper
        $html .= '</div>';
        // Retourne le html
        return $html;
    }

    /**
     * Crée une notice
     * @param string $id Id du champ
     * @param string $notice Notice
     * @return string
     */
    public static function notice($id, $notice)
    {
        return ' <span id="' . $id . 'Notice" class="notice ' . ($notice ? '' : 'displayNone') . '">' . $notice . '</span>';
    }

    /**
     * Crée un champ mot de passe
     * @param string $nameId Nom et id du champ
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function password($nameId, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'autocomplete' => 'on',
            'class' => '',
            'classWrapper' => '',
            'noDirty' => false,
            'disabled' => false,
            'help' => '',
            'id' => $nameId,
            'label' => '',
            //'maxlength' => '500',
            'name' => $nameId,
            'placeholder' => '',
            'readonly' => false,
            'required' => false,
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        $attributes['label'] = helper::translate($attributes['label']);
        //$attributes['placeholder'] = helper::translate($attributes['placeholder']);
        $attributes['help'] = helper::translate($attributes['help']);
        // Gestion du champ obligatoire 
        if (isset($attributes['required']) && $attributes['required']) {
            // Affiche l'astérisque dans le label
            $required = ' required-field';
            // Ajoute l'attribut required au champ input
            $attributes['required'] = 'required';
        }
        // Début du wrapper
        $html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
        // Label
        if ($attributes['label']) {
            $html .= self::label($attributes['id'], $attributes['label'], [
                'help' => $attributes['help'],
                // Ajoute la classe required-field si le champ est obligatoire
                'class' => isset($required) ? $required : ''
            ]);
        }
        // Notice
        $notice = '';
        if (array_key_exists($attributes['id'], common::$inputNotices)) {
            $notice = common::$inputNotices[$attributes['id']];
            $attributes['class'] .= ' notice';
        }
        $html .= self::notice($attributes['id'], $notice);
        // Mot de passe
        $html .= sprintf(
            '<input type="password" %s>',
            helper::sprintAttributes($attributes)
        );
        // Fin du wrapper
        $html .= '</div>';
        // Retourne le html
        return $html;
    }

    /**
     * Crée un champ sélection
     * @param string $nameId Nom et id du champ
     * @param array $options Liste des options du champ de sélection ($value => $text)
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function select($nameId, array $options, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'before' => true,
            'class' => '',
            'classWrapper' => '',
            'noDirty' => false,
            'disabled' => false,
            'help' => '',
            'id' => $nameId,
            'label' => '',
            'name' => $nameId,
            'selected' => '',
            'font' => [],
            'multiple' => '',
            'required' => false,
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        $attributes['label'] = helper::translate($attributes['label']);
        $attributes['help'] = helper::translate($attributes['help']);
        // Stocker les fontes et remettre à zéro le tableau des fontes transmis pour éviter une erreur de sprintAttributes
        if (empty($attributes['font']) === false) {
            $fonts = $attributes['font'];
            $attributes['font'] = [];
        }
        // Sauvegarde des données en cas d'erreur
        if ($attributes['before'] and array_key_exists($attributes['id'], common::$inputBefore)) {
            $attributes['selected'] = common::$inputBefore[$attributes['id']];
        }
        // Gestion du champ obligatoire 
        if (isset($attributes['required']) && $attributes['required']) {
            // Affiche l'astérisque dans le label
            $required = ' required-field';
            // Ajoute l'attribut required au champ input
            $attributes['required'] = 'required';
        }
        // Début du wrapper
        $html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
        // Label
        if ($attributes['label']) {
            $html .= self::label($attributes['id'], $attributes['label'], [
                'help' => $attributes['help'],
                // Ajoute la classe required-field si le champ est obligatoire
                'class' => isset($required) ? $required : ''
            ]);
        }
        // Notice
        $notice = '';
        if (array_key_exists($attributes['id'], common::$inputNotices)) {
            $notice = common::$inputNotices[$attributes['id']];
            $attributes['class'] .= ' notice';
        }
        $html .= self::notice($attributes['id'], $notice);
        // Attribut multiple
        if ($attributes['multiple'] === true) {
            echo "ppp";
            $attributes['multiple'] = 'multiple';
        }
        // Début sélection
        $html .= sprintf(
            '<select %s>',
            helper::sprintAttributes($attributes)
        );
        foreach ($options as $value => $text) {
            // Select des liste de fontes
            $html .= isset($fonts) ? sprintf(
                '<option value="%s"%s style="font-family: %s;">%s</option>',
                $value,
                $attributes['selected'] == $value ? ' selected' : '', // Double == pour ignorer le type de variable car $_POST change les types en string
                $fonts[$value],
                $text
                // Select standard
            ) : sprintf(
                '<option value="%s"%s>%s</option>',
                $value,
                $attributes['selected'] == $value ? ' selected' : '', // Double == pour ignorer le type de variable car $_POST change les types en string
                helper::translate($text)
            );
        }
        // Fin sélection
        $html .= '</select>';
        // Fin du wrapper
        $html .= '</div>';
        // Retourne le html
        return $html;
    }


    /**
     * Crée une bulle de dialogue
     * @param string $text Texte de la bulle
     * @return string
     */
    public static function speech($text)
    {
        return '<div class="speech"><div class="speechBubble">' . helper::translate($text) . '</div>' . template::ico('mimi speechMimi', ['fontSize' => '7em']) . '</div>';
    }

    /**
     * Crée un bouton validation
     * @param string $nameId Nom & id du bouton validation
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function submit($nameId, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'class' => '',
            'disabled' => false,
            'ico' => 'check',
            'id' => $nameId,
            'name' => $nameId,
            'uniqueSubmission' => false, //true avant 9.1.08
            'value' => 'Enregistrer'
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        $attributes['value'] = helper::translate($attributes['value']);
        // Retourne le html
        return sprintf(
            '<button type="submit" class="%s%s" %s>%s</button>',
            $attributes['class'],
            $attributes['uniqueSubmission'] ? 'uniqueSubmission' : '',
            helper::sprintAttributes($attributes, ['class', 'ico', 'value']),
            ($attributes['ico'] ? template::ico($attributes['ico'], ['margin' => 'right']) : '') . $attributes['value']
        );
    }

    /**
     * Crée un tableau
     * @param array $cols Cols des colonnes (format: [col colonne1, col colonne2, etc])
     * @param array $body Contenu (format: [[contenu1, contenu2, etc], [contenu1, contenu2, etc]])
     * @param array $head Entêtes (format : [[titre colonne1, titre colonne2, etc])
     * @param array $rowsId Id pour la numérotation des rows (format : [id colonne1, id colonne2, etc])
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function table(array $cols = [], array $body = [], array $head = [], array $attributes = [], array $rowsId = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'class' => '',
            'classWrapper' => '',
            'id' => ''
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        foreach ($head as $value) {
            $head[array_search($value, $head)] = helper::translate($value);
        }
        // Début du wrapper
        $html = '<div id="' . $attributes['id'] . 'Wrapper" class="tableWrapper ' . $attributes['classWrapper'] . '">';
        // Début tableau
        $html .= '<table id="' . $attributes['id'] . '" class="table ' . $attributes['class'] . '">';
        // Entêtes
        if ($head) {
            // Début des entêtes
            $html .= '<thead>';
            $html .= '<tr class="nodrag">';
            $i = 0;
            foreach ($head as $th) {
                $html .= '<th class="col' . $cols[$i++] . '">' . $th . '</th>';
            }
            // Fin des entêtes
            $html .= '</tr>';
            $html .= '</thead>';
        }
        // Pas de tableau d'Id transmis, générer une numérotation
        if (empty($rowsId)) {
            $rowsId = range(0, count($body));
        }
        // Début contenu
        $j = 0;
        foreach ($body as $tr) {
            // Id de ligne pour les tableaux drag and drop
            $html .= '<tr id="' . $rowsId[$j++] . '">';
            $i = 0;
            foreach ($tr as $td) {
                $html .= '<td class="col' . $cols[$i++] . '">' . $td . '</td>';
            }
            $html .= '</tr>';
        }
        // Fin contenu
        $html .= '</tbody>';
        // Fin tableau
        $html .= '</table>';
        // Fin container
        $html .= '</div>';
        // Retourne le html
        return $html;
    }

    /**
     * Crée un champ texte court
     * @param string $nameId Nom et id du champ
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function text($nameId, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'autocomplete' => 'on',
            'before' => true,
            'class' => '',
            'classWrapper' => '',
            'noDirty' => false,
            'disabled' => false,
            'help' => '',
            'id' => $nameId,
            'label' => '',
            //'maxlength' => '500',
            'name' => $nameId,
            'placeholder' => '',
            'readonly' => false,
            'value' => '',
            'type' => 'text',
            'required' => false,
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        $attributes['label'] = helper::translate($attributes['label']);
        $attributes['help'] = helper::translate($attributes['help']);
        //$attributes['placeholder'] = helper::translate($attributes['placeholder']);
        // Sauvegarde des données en cas d'erreur
        if ($attributes['before'] and array_key_exists($attributes['id'], common::$inputBefore)) {
            $attributes['value'] = common::$inputBefore[$attributes['id']];
        }
        // Gestion du champ obligatoire 
        if (isset($attributes['required']) && $attributes['required']) {
            // Affiche l'astérisque dans le label
            $required = ' required-field';
            // Ajoute l'attribut required au champ input
            $attributes['required'] = 'required';
        }
        // Début du wrapper
        $html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
        // Label
        if ($attributes['label']) {
            $html .= self::label($attributes['id'], $attributes['label'], [
                'help' => $attributes['help'],
                // Ajoute la classe required-field si le champ est obligatoire
                'class' => isset($required) ? $required : ''
            ]);
        }
        // Notice
        $notice = '';
        if (array_key_exists($attributes['id'], common::$inputNotices)) {
            $notice = common::$inputNotices[$attributes['id']];
            $attributes['class'] .= ' notice';
        }
        $html .= self::notice($attributes['id'], $notice);
        // Texte
        $html .= sprintf(
            '<input type="' . $attributes['type'] . '" %s>',
            helper::sprintAttributes($attributes)
        );
        // Fin du wrapper
        $html .= '</div>';
        // Retourne le html
        return $html;
    }

    /**
     * Crée un champ texte long
     * @param string $nameId Nom et id du champ
     * @param array $attributes Attributs ($key => $value)
     * @return string
     */
    public static function textarea($nameId, array $attributes = [])
    {
        // Attributs par défaut
        $attributes = array_merge([
            'before' => true,
            'class' => '', // editorWysiwyg et editor possible pour utiliser un éditeur (il faut également instancier les librairies)
            'classWrapper' => '',
            'disabled' => false,
            'noDirty' => false,
            'help' => '',
            'id' => $nameId,
            'label' => '',
            //'maxlength' => '500',
            'name' => $nameId,
            'readonly' => false,
            'value' => '',
            'required' => false,
        ], $attributes);
        // Traduction de l'aide et de l'étiquette
        $attributes['label'] = helper::translate($attributes['label']);
        $attributes['help'] = helper::translate($attributes['help']);
        // Sauvegarde des données en cas d'erreur
        if ($attributes['before'] and array_key_exists($attributes['id'], common::$inputBefore)) {
            $attributes['value'] = common::$inputBefore[$attributes['id']];
        }
        // Gestion du champ obligatoire 
        if (isset($attributes['required']) && $attributes['required']) {
            // Affiche l'astérisque dans le label
            $required = ' required-field';
            // Ajoute l'attribut required au champ input
            $attributes['required'] = 'required';
        }
        // Début du wrapper
        $html = '<div id="' . $attributes['id'] . 'Wrapper" class="inputWrapper ' . $attributes['classWrapper'] . '">';
        // Label
        if ($attributes['label']) {
            $html .= self::label($attributes['id'], $attributes['label'], [
                'help' => $attributes['help'],
                // Ajoute la classe required-field si le champ est obligatoire
                'class' => isset($required) ? $required : ''
            ]);
        }
        // Notice
        $notice = '';
        if (array_key_exists($attributes['id'], common::$inputNotices)) {
            $notice = common::$inputNotices[$attributes['id']];
            $attributes['class'] .= ' notice';
        }
        $html .= self::notice($attributes['id'], $notice);
        // Texte long
        $html .= sprintf(
            '<textarea %s>%s</textarea>',
            helper::sprintAttributes($attributes, ['value']),
            $attributes['value']
        );
        // Fin du wrapper
        $html .= '</div>';
        // Retourne le html
        return $html;
    }
}
