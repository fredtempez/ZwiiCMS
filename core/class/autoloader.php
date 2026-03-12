<?php

/**
 * Autoloader unifié pour ZwiiCMS
 * Basé sur PSR-4 avec optimisations spécifiques
 */
class Autoloader
{
    private static $loadedClasses = [];
    
    // Mapping correct des classes essentielles
    private static $coreClasses = [
        'common' => 'core/core.php',
        'core' => 'core/class/router.class.php',
        'router' => 'core/class/router.class.php',
        'helper' => 'core/class/helper.class.php',
        'template' => 'core/class/template.class.php',
        'layout' => 'core/layout/layout.class.php',
        'jsondb' => 'core/class/jsondb/JsonDb.class.php',
        'dot' => 'core/class/jsondb/Dot.class.php',
        'phpmailer' => 'core/class/phpmailer/PHPMailer.class.php',
        'smtp' => 'core/class/phpmailer/SMTP.class.php',
        'exception' => 'core/class/phpmailer/Exception.class.php',
        'sitemapgenerator' => 'core/class/sitemap/SitemapGenerator.class.php',
        'filesystem' => 'core/class/sitemap/FileSystem.class.php',
        'runtime' => 'core/class/sitemap/Runtime.class.php',
        'strftime' => 'core/class/strftime/php-8.1-strftime.class.php',
    ];

    public static function register()
    {
        spl_autoload_register([self::class, 'load']);
    }

    public static function load($className)
    {
        if (isset(self::$loadedClasses[$className])) {
            return true;
        }

        $success = false;
        $lowerClass = strtolower($className);

        // 1. Classes essentielles
        if (isset(self::$coreClasses[$lowerClass])) {
            $file = self::$coreClasses[$lowerClass];
            if (self::requireFile($file)) {
                self::$loadedClasses[$className] = $file;
                $success = true;
            }
        }

        // 2. Classes avec namespace
        if (!$success && strpos($className, '\\') !== false) {
            $success = self::loadNamespacedClass($className);
        }

        // 3. Modules
        if (!$success) {
            $success = self::loadModule($lowerClass);
        }

        if ($success) {
            self::$loadedClasses[$className] = $success;
        }

        return $success;
    }

    private static function loadNamespacedClass($className)
    {
        // Cas spéciaux pour les classes avec namespace connus
        $namespaceMap = [
            'Icamys\\SitemapGenerator\\SitemapGenerator' => 'core/class/sitemap/SitemapGenerator.class.php',
            'Icamys\\SitemapGenerator\\FileSystem' => 'core/class/sitemap/FileSystem.class.php',
            'Icamys\\SitemapGenerator\\Runtime' => 'core/class/sitemap/Runtime.class.php',
            'Prowebcraft\\JsonDb' => 'core/class/jsondb/JsonDb.class.php',
            'Prowebcraft\\Dot' => 'core/class/jsondb/Dot.class.php',
        ];

        if (isset($namespaceMap[$className])) {
            return self::requireFile($namespaceMap[$className]);
        }

        // Cas spécial pour le namespace PHP81_BC (strftime)
        if (strpos($className, 'PHP81_BC\\') === 0) {
            return self::requireFile('core/class/strftime/php-8.1-strftime.class.php');
        }

        return false;
    }

    private static function loadModule($className)
    {
        $locations = [
            'core/module/',
            'module/',
            'core/vendor/',
        ];

        foreach ($locations as $location) {
            $file = $location . $className . '/' . $className . '.php';
            if (self::requireFile($file)) {
                return $file;
            }
        }

        return false;
    }

    private static function requireFile($file)
    {
        if (file_exists($file) && is_readable($file)) {
            require_once $file;
            return true;
        }
        return false;
    }

    public static function preloadEssentialClasses()
    {
        $essential = ['common', 'core', 'helper', 'template'];
        foreach ($essential as $class) {
            self::load($class);
        }
        
        // Précharger les classes namespace essentielles
        $namespaceEssential = ['Prowebcraft\\Dot', 'Prowebcraft\\JsonDb', 'PHP81_BC\\strftime'];
        foreach ($namespaceEssential as $class) {
            self::load($class);
        }
    }
}
