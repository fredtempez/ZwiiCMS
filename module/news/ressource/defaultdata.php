<?php
class init extends news {
    public static $defaultData = [
        'feeds'              => false,
        'feedsLabel'         => '',
        'itemsperPage'       => 8,
        'itemsperCol'        => 12,
        'height'             => -1,
        'versionData'        => '3.5',
        'dateFormat'         => '%d %B %Y',
        'timeFormat'         =>'%H:%M',
    ];

    public static $defaultTheme = [
        'style'               => '',
        'borderStyle'         => 'none',
        'borderColor'         => 'rgba(33, 34, 35, 1)',
        'backgroundColor'     => 'rgba(255, 255, 255, 1)',
        'borderWidth'          => '0'
    ];

}