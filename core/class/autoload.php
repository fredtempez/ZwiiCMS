<?php

class autoload {
    public static function autoloader () {
        require_once 'core/class/helper.class.php';
        require_once 'core/class/template.class.php';
        require_once 'core/class/SitemapGenerator.class.php';
        require_once 'core/class/phpmailer/phpmailer.class.php';
        require_once 'core/class/phpmailer/exception.class.php';
        require_once "core/class/jsondb/Dot.class.php";
        require_once "core/class/jsondb/JsonDb.class.php";
    }
}