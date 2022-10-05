<?php
class theme extends gallery {
	public static $defaultTheme = [
            'thumbAlign' 	=> 'center',
            'thumbWidth' 	=> '18em',
            'thumbHeight'	=> '15em',
            'thumbMargin'	=> '.5em',
            'thumbBorder'	=> '.1em',
            'thumbOpacity'	=> '.7',
            'thumbBorderColor'  => 'rgba(221, 221, 221, 1)',
            'thumbRadius'       => '.3em',
            'thumbShadows'      => '1px 1px 10px',
            'thumbShadowsColor' => 'rgba(125, 125, 125, 1)',
            'legendHeight'	=> '.375em',
            'legendAlign'	=> 'center',
            'legendTextColor'   => 'rgba(255, 255, 255, 1)',
            'legendBgColor'     => 'rgba(0, 0, 0, .6)'
    ];
    public static $defaultData = [
            "showUniqueGallery" => false,
            "backPosition"      => "top",
            "backAlign"         => "center",
            'versionData'       => '3.0'
    ];
}
