<?php
class init extends search {
    public static $defaultConfig = [
        'previewLength'      => 100,
        'resultHideContent'  => false,
        'placeHolder'        => 'Un ou plusieurs mots-clés séparés par un espace ou par +',
        'submitText'         => 'Rechercher',
        'versionData'        => '2.2'
    ];
    public static $defaultTheme = [
        'keywordColor'       => 'rgba(229, 229, 1, 1)'
    ];
}