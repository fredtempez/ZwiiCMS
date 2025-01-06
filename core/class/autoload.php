<?php

class autoload {
    public static function autoloader () {
        require_once 'core/core.php';
        require_once 'core/class/router.class.php';
        require_once 'core/class/helper.class.php';
        require_once 'core/class/template.class.php';
        require_once 'core/class/layout.class.php';
        require_once 'core/class/sitemap/IConfig.class.php';
        require_once 'core/class/sitemap/Config.class.php';
        require_once 'core/class/sitemap/IRuntime.class.php';
        require_once 'core/class/sitemap/Runtime.class.php';
        require_once 'core/class/sitemap/IFileSystem.class.php';
        require_once 'core/class/sitemap/FileSystem.class.php';
        require_once 'core/class/sitemap/SitemapGenerator.class.php';
        require_once 'core/class/phpmailer/PHPMailer.class.php';
        require_once 'core/class/phpmailer/Exception.class.php';
        require_once 'core/class/phpmailer/SMTP.class.php';
        require_once 'core/class/jsondb/Dot.class.php';
        require_once 'core/class/jsondb/JsonDb.class.php';
    }
}