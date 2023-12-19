<?php
class init extends news {
    public static $defaultData = [
        'feeds'              => true,
        'feedsLabel'         => 'RSS',
        'itemsperPage'       => 8,
        'itemsperCol'        => 12,
        'height'             => -1,
        'versionData'        => '5.3',
        'dateFormat'         => '%d %B %Y',
        'timeFormat'         =>'%H:%M',
        'buttonBack'        => true
    ];

    public static $defaultTheme = [
        'style'               => '',
        'borderStyle'         => 'none',
        'borderColor'         => 'rgba(33, 34, 35, 1)',
        'backgroundColor'     => 'rgba(255, 255, 255, 1)',
        'borderWidth'          => '0',
        'itemsBlur'          =>  '0%'
    ];

}