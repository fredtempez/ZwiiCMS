<?php
class autoload {
    public static function autoloader () {
        // Chargement des classes du noyau
        require_once 'core/core.php';
        require_once 'core/class/router.class.php';
        require_once 'core/class/helper.class.php';
        require_once 'core/class/template.class.php';
        require_once 'core/layout/layout.class.php';
        // Chargement des classes de base de données
        require_once 'core/class/jsondb/Dot.class.php';
        require_once 'core/class/jsondb/JsonDb.class.php';
        // Chargement des classes utilitaires
        require_once 'core/class/sitemap/SitemapGenerator.class.php';
        require_once 'core/class/sitemap/FileSystem.class.php';
        require_once 'core/class/sitemap/Runtime.class.php';
        require_once 'core/class/strftime/php-8.1-strftime.class.php';
        // Classe php mailer
        require_once 'core/class/phpmailer/PHPMailer.class.php';
        require_once 'core/class/phpmailer/Exception.class.php';
        require_once 'core/class/phpmailer/SMTP.class.php';
    }
}