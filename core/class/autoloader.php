<?php

/**
 * Autoloader unifié pour ZwiiCMS
 * Basé sur PSR-4 avec optimisations spécifiques
 * Supporte le namespace Zwii\Cms pour migration progressive
 */
class Autoloader
{
    private static $loadedClasses = [];
    
    // Namespace racine du projet
    private const NAMESPACE_ROOT = 'Zwii\\Cms';
    
    // Mapping namespace -> chemin fichier (PSR-4)
    private static $namespaceMapping = [
        'Zwii\\Cms\\Core' => 'core/core.php',
        'Zwii\\Cms\\Router' => 'core/class/router.class.php',
        'Zwii\\Cms\\Helper' => 'core/class/helper.class.php',
        'Zwii\\Cms\\Template' => 'core/class/template.class.php',
        'Zwii\\Cms\\Layout' => 'core/layout/layout.class.php',
        'Zwii\\Cms\\Asset' => 'core/class/asset.class.php',
    ];
    
    // Mapping correct des classes essentielles (sans namespace)
    private static $coreClasses = [
        'core' => 'core/core.php',
        'router' => 'core/class/router.class.php',
        'helper' => 'core/class/helper.class.php',
        'template' => 'core/class/template.class.php',
        'layout' => 'core/layout/layout.class.php',
        'asset' => 'core/class/asset.class.php',
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

        // 1. Classes essentielles (demandées sans namespace)
        if (isset(self::$coreClasses[$lowerClass])) {
            $file = self::$coreClasses[$lowerClass];
            if (self::requireFile($file)) {
                self::$loadedClasses[$className] = $file;
                // Créer alias global -> namespace si la classe est namespacée
                self::createGlobalAlias($className);
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
        // 1. Namespace Zwii\Cms (classes du projet)
        if (isset(self::$namespaceMapping[$className])) {
            if (self::requireFile(self::$namespaceMapping[$className])) {
                // Créer un alias vers la classe sans namespace si elle existe
                $shortName = self::extractShortName($className);
                self::createCompatibilityAlias($className, $shortName);
                return self::$namespaceMapping[$className];
            }
        }
        
        // 2. Auto-découverte namespace Zwii\Cms\Module\*
        if (strpos($className, self::NAMESPACE_ROOT . '\\Module\\') === 0) {
            $moduleName = self::extractModuleName($className);
            if ($moduleName) {
                $file = self::findModuleFile($moduleName);
                if ($file && self::requireFile($file)) {
                    return $file;
                }
            }
        }
        
        // 3. Cas spéciaux pour les classes avec namespace connus (vendors)
        $namespaceMap = [
            'Icamys\\SitemapGenerator\\SitemapGenerator' => 'core/class/sitemap/SitemapGenerator.class.php',
            'Icamys\\SitemapGenerator\\FileSystem' => 'core/class/sitemap/FileSystem.class.php',
            'Icamys\\SitemapGenerator\\Runtime' => 'core/class/sitemap/Runtime.class.php',
            'Prowebcraft\\JsonDb' => 'core/class/jsondb/JsonDb.class.php',
            'Prowebcraft\\Dot' => 'core/class/jsondb/Dot.class.php',
            'PHPMailer\\PHPMailer\\PHPMailer' => 'core/class/phpmailer/PHPMailer.class.php',
            'PHPMailer\\PHPMailer\\SMTP' => 'core/class/phpmailer/SMTP.class.php',
            'PHPMailer\\PHPMailer\\Exception' => 'core/class/phpmailer/Exception.class.php',
        ];

        if (isset($namespaceMap[$className])) {
            return self::requireFile($namespaceMap[$className]);
        }

        // 4. Cas spécial pour le namespace PHP81_BC (strftime)
        if (strpos($className, 'PHP81_BC\\') === 0) {
            return self::requireFile('core/class/strftime/php-8.1-strftime.class.php');
        }

        return false;
    }
    
    /**
     * Extrait le nom court d'une classe namespacée
     */
    private static function extractShortName($className)
    {
        $parts = explode('\\', $className);
        return end($parts);
    }
    
    /**
     * Extrait le nom du module depuis un namespace Zwii\Cms\Module\NomModule
     */
    private static function extractModuleName($className)
    {
        $prefix = self::NAMESPACE_ROOT . '\\Module\\';
        if (strpos($className, $prefix) === 0) {
            $remaining = substr($className, strlen($prefix));
            $parts = explode('\\', $remaining);
            return strtolower($parts[0]);
        }
        return null;
    }
    
    /**
     * Trouve le fichier d'un module par son nom
     */
    private static function findModuleFile($moduleName)
    {
        $locations = ['core/module/', 'module/'];
        foreach ($locations as $location) {
            $file = $location . $moduleName . '/' . $moduleName . '.php';
            if (file_exists($file)) {
                return $file;
            }
        }
        return null;
    }
    
    /**
     * Crée un alias de compatibilité entre classe namespacée et classe simple
     */
    private static function createCompatibilityAlias($namespacedClass, $simpleClass)
    {
        // Ne créer l'alias que si la classe simple n'existe pas déjà
        if (class_exists($namespacedClass, false) && !class_exists($simpleClass, false)) {
            class_alias($namespacedClass, $simpleClass);
        }
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
                // Créer un alias pour la compatibilité avec les anciens modules
                // Si le module est dans le namespace Zwii\Cms, créer un alias global
                $namespacedClass = self::NAMESPACE_ROOT . '\\' . $className;
                if (class_exists($namespacedClass, false) && !class_exists($className, false)) {
                    class_alias($namespacedClass, $className);
                }
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
        // Chargement des traits en premier
        require_once('core/trait/Data.trait.php');
        require_once('core/trait/InputOutput.trait.php');
        require_once('core/trait/PageContent.trait.php');
        require_once('core/trait/Tools.trait.php');
        require_once('core/trait/UserPermission.trait.php');
        require_once('core/trait/Security.trait.php');
        
        $essential = ['core', 'router', 'helper', 'template'];
        foreach ($essential as $class) {
            self::load($class);
        }
        
        // Précharger les classes namespace essentielles
        $namespaceEssential = ['Prowebcraft\\Dot', 'Prowebcraft\\JsonDb', 'PHP81_BC\\strftime'];
        foreach ($namespaceEssential as $class) {
            self::load($class);
        }
        
        // Créer les alias de compatibilité
        self::registerCompatibilityAliases();
    }
    
    /**
     * Enregistre les alias de compatibilité bidirectionnels
     * Permet d'utiliser les classes via namespace ou sans namespace
     */
    private static function registerCompatibilityAliases()
    {
        // Alias global -> namespace pour les classes du noyau
        $globalToNamespace = [
            'Core' => 'Zwii\\Cms\\Core',
            'Router' => 'Zwii\\Cms\\Router',
            'Helper' => 'Zwii\\Cms\\Helper',
            'Template' => 'Zwii\\Cms\\Template',
            'Layout' => 'Zwii\\Cms\\Layout',
            'Asset' => 'Zwii\\Cms\\Asset',
            'init' => 'Zwii\\Cms\\init',
        ];
        
        foreach ($globalToNamespace as $global => $namespaced) {
            if (class_exists($namespaced, false) && !class_exists($global, false)) {
                class_alias($namespaced, $global);
            }
        }
        
        // Alias historique common -> Core
        if (class_exists('Core', false) && !class_exists('common', false)) {
            class_alias('Core', 'common');
        }
        
        // Alias rétrocompatibilité minuscules (legacy)
        $legacyAliases = [
            'Asset' => 'asset',
            'Helper' => 'helper',
            'Template' => 'template',
            'Layout' => 'layout',
        ];
        
        foreach ($legacyAliases as $pascalCase => $lowercase) {
            if (class_exists($pascalCase, false) && !class_exists($lowercase, false)) {
                class_alias($pascalCase, $lowercase);
            }
        }
    }
    
    /**
     * Crée un alias global pour une classe chargée depuis un namespace
     */
    private static function createGlobalAlias($className)
    {
        $globalToNamespace = [
            'core' => 'Zwii\\Cms\\Core',
            'router' => 'Zwii\\Cms\\Router',
            'helper' => 'Zwii\\Cms\\Helper',
            'template' => 'Zwii\\Cms\\Template',
            'layout' => 'Zwii\\Cms\\Layout',
            'asset' => 'Zwii\\Cms\\Asset',
        ];
        
        $lowerClass = strtolower($className);
        if (isset($globalToNamespace[$lowerClass])) {
            $namespaced = $globalToNamespace[$lowerClass];
            $pascalCase = ucfirst($lowerClass);
            
            // Créer alias PascalCase -> Namespace
            if (class_exists($namespaced, false) && !class_exists($pascalCase, false)) {
                class_alias($namespaced, $pascalCase);
            }
            
            // Créer alias lowercase -> PascalCase (pour legacy)
            if ($lowerClass !== $pascalCase && class_exists($pascalCase, false) && !class_exists($lowerClass, false)) {
                class_alias($pascalCase, $lowerClass);
            }
        }
    }
    
    /**
     * Retourne le namespace racine du projet
     */
    public static function getNamespaceRoot()
    {
        return self::NAMESPACE_ROOT;
    }
}
