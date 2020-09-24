<?php
class theme extends search {
	public static $defaultData = [
        'keywordColor'      => 'rgba(229, 229, 1, 1)'
    ];
}
class data extends search {
    public static $defaultData = [
        'previewLength'      => 100,
        'resultHideContent'  => false,
        'placeHolder'        => 'Un ou plusieurs mots-clés séparés par un espace ou par +',
        'submitText'         => 'Rechercher'
    ];
}