<?php

class autoload {
    public static function autoloader () {
        require 'core/core.php';
        require 'core/class/helper.class.php';
        require 'core/class/template.class.php';
        require 'core/class/layout.class.php';
        require 'core/class/sitemap/Runtime.class.php';
        require 'core/class/sitemap/FileSystem.class.php';
        require 'core/class/sitemap/SitemapGenerator.class.php';
        require 'core/class/phpmailer/PHPMailer.class.php';
        require 'core/class/phpmailer/Exception.class.php';
        require 'core/class/phpmailer/SMTP.class.php';
        require 'core/class/jsondb/Dot.class.php';
        require 'core/class/jsondb/JsonDb.class.php';
    }
}