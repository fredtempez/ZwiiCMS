<?php
class init extends search {
    public static $defaultData = [
        'previewLength'      => 100,
        'resultHideContent'  => false,
        'placeHolder'        => 'Un ou plusieurs mots-clés séparés par un espace ou par +',
        'submitText'         => 'Rechercher',
        'versionData'        => '2.0'
    ];
    public static $defaultTheme = [
        'keywordColor'       => 'rgba(229, 229, 1, 1)'
    ];
}