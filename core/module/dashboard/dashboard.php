<?php

namespace Zwii\Cms;

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @Copyright (C) 2026, Frédéric Tempez
 * @Licensed under the GNU General Public License v3.0 or later.
 * @link http://zwiicms.fr/
 */

class dashboard extends Core
{

    public static $actions = [
        'index' => self::ROLE_ADMIN,
    ];

    public static $infos = [];

    public function index()
    {
        // === Serveur / OS ===
        self::$infos['server'] = $_SERVER['SERVER_SOFTWARE'] ?? 'N/A';
        self::$infos['os']     = php_uname('s') . ' ' . php_uname('r');

        // === PHP ===
        self::$infos['php']['version']    = phpversion();
        self::$infos['php']['extensions'] = implode(', ', get_loaded_extensions());
        self::$infos['php']['memLimit']   = ini_get('memory_limit');
        self::$infos['php']['uploadMax']  = ini_get('upload_max_filesize');
        self::$infos['php']['execTime']   = ini_get('max_execution_time') . 's';

        $memUsed  = memory_get_usage(true);
        $memPeak  = memory_get_peak_usage(true);
        $memLimit = self::parseIniBytes(ini_get('memory_limit'));
        self::$infos['php']['memUsedBytes']  = $memUsed;
        self::$infos['php']['memPeakBytes']  = $memPeak;
        self::$infos['php']['memLimitBytes'] = $memLimit;
        self::$infos['php']['memUsedMb']     = round($memUsed / 1048576, 1);
        self::$infos['php']['memPeakMb']     = round($memPeak / 1048576, 1);
        self::$infos['php']['memLimitMb']    = $memLimit > 0 ? round($memLimit / 1048576, 1) : 0;

        // === Charge système ===
        $load = sys_getloadavg();
        self::$infos['system']['load1']  = round($load[0], 2);
        self::$infos['system']['load5']  = round($load[1], 2);
        self::$infos['system']['load15'] = round($load[2], 2);

        // === Espace disque ===
        $diskTotal = disk_total_space('.');
        $diskFree  = disk_free_space('.');
        self::$infos['disk']['totalGb']  = round($diskTotal / 1073741824, 1);
        self::$infos['disk']['freeGb']   = round($diskFree / 1073741824, 1);
        self::$infos['disk']['usedGb']   = round(($diskTotal - $diskFree) / 1073741824, 1);
        $siteBytes   = self::dirSize(self::FILE_DIR);
        $coreBytes   = self::dirSize('core/');
        $moduleBytes = self::dirSize(self::MODULE_DIR);
        self::$infos['disk']['siteBytes']   = $siteBytes;
        self::$infos['disk']['siteSize']    = self::formatSize($siteBytes);
        self::$infos['disk']['coreBytes']   = $coreBytes;
        self::$infos['disk']['coreSize']    = self::formatSize($coreBytes);
        self::$infos['disk']['moduleBytes'] = $moduleBytes;
        self::$infos['disk']['moduleSize']  = self::formatSize($moduleBytes);
        self::$infos['disk']['backSize']    = self::formatSize(self::dirSize(self::BACKUP_DIR));
        self::$infos['disk']['tmpSize']     = self::formatSize(self::dirSize(self::TEMP_DIR));

        // === Infos CMS ===
        self::$infos['cms']['version']         = self::ZWII_VERSION;
        self::$infos['cms']['updateAvailable'] = $this->getData(['core', 'updateAvailable']) ? 'Oui' : 'Non';
        $lastBackup = $this->getData(['core', 'lastBackup']);
        self::$infos['cms']['lastBackup']      = $lastBackup ? date('d/m/Y H:i', $lastBackup) : '-';
        $lastClear = $this->getData(['core', 'lastClearTmp']);
        self::$infos['cms']['lastClearTmp']    = $lastClear ? date('d/m/Y H:i', $lastClear) : '-';
        self::$infos['cms']['siteTitle']       = $this->getData(['locale', 'title']) ?? '';

        // === Langues / Sites localisés ===
        $langs = [];
        foreach (array_keys(self::$languages) as $code) {
            if (is_dir(self::DATA_DIR . $code)) {
                $langs[] = $code;
            }
        }
        self::$infos['cms']['languageCount'] = count($langs);

        $sitesData = [];
        foreach ($langs as $langCode) {
            $pageData  = $this->fetchDataFile('page', $langCode) ?? [];
            $pageCount = count($pageData);
            $modCount  = 0;
            foreach ($pageData as $p) {
                if (!empty($p['moduleId'])) $modCount++;
            }
            $sitesData[] = [
                'id'      => $langCode,
                'title'   => self::$languages[$langCode] ?? $langCode,
                'pages'   => $pageCount,
                'modules' => $modCount,
            ];
        }
        self::$infos['sites'] = $sitesData;

        // === Utilisateurs ===
        $users = $this->getData(['user']) ?? [];
        self::$infos['cms']['userCount'] = count($users);

        // === Journal : erreurs 30j ===
        $logErrors = 0;
        $logFile   = self::DATA_DIR . 'journal.log';
        if (file_exists($logFile)) {
            $lines  = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $cutoff = strtotime('-30 days');
            foreach ($lines as $line) {
                $parts = explode(';', $line, 6);
                if (count($parts) >= 5) {
                    $ts = \DateTime::createFromFormat('Ymd', $parts[0]);
                    if ($ts && $ts->getTimestamp() >= $cutoff) {
                        if (stripos($parts[4], 'erreur') !== false || stripos($parts[4], 'error') !== false) {
                            $logErrors++;
                        }
                    }
                }
            }
        }
        self::$infos['logs']['errors'] = $logErrors;

        // === Santé des fichiers de base de données ===
        $dbFilesConfig = $this->getData(['config', 'database']) ?? [];
        $defaultDbCfg  = ['minify' => false, 'compression' => false, 'encryption' => false];

        $globalFiles = ['config', 'user', 'core', 'group', 'admin', 'blacklist', 'font', 'language'];
        $dbStats = [];
        foreach ($globalFiles as $name) {
            $path = self::DATA_DIR . $name . '.json';
            $cfg  = array_merge($defaultDbCfg, $dbFilesConfig[$name] ?? []);
            $dbStats[] = [
                'name'     => $name . '.json',
                'scope'    => 'global',
                'size'     => file_exists($path) ? filesize($path) : 0,
                'exists'   => file_exists($path),
                'minify'   => !empty($cfg['minify']),
                'compress' => !empty($cfg['compression']),
                'encrypt'  => !empty($cfg['encryption']),
            ];
        }
        $contentFiles = ['page', 'module', 'theme'];
        foreach ($langs as $langCode) {
            foreach ($contentFiles as $name) {
                $path = self::DATA_DIR . $langCode . '/' . $name . '.json';
                $cfg  = array_merge($defaultDbCfg, $dbFilesConfig[$name] ?? []);
                $dbStats[] = [
                    'name'     => $langCode . '/' . $name . '.json',
                    'scope'    => $langCode,
                    'size'     => file_exists($path) ? filesize($path) : 0,
                    'exists'   => file_exists($path),
                    'minify'   => !empty($cfg['minify']),
                    'compress' => !empty($cfg['compression']),
                    'encrypt'  => !empty($cfg['encryption']),
                ];
            }
        }
        usort($dbStats, fn($a, $b) => $b['size'] - $a['size']);
        self::$infos['db'] = $dbStats;

        $this->addOutput([
            'title'  => Helper::translate('Tableau de bord'),
            'vendor' => ['plotly'],
            'view'   => 'index',
        ]);
    }

    private static function parseIniBytes(string $val): int
    {
        $val  = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        $num  = (int) $val;
        switch ($last) {
            case 'g': $num *= 1024;
            // fall through
            case 'm': $num *= 1024;
            // fall through
            case 'k': $num *= 1024;
        }
        return $num;
    }

    private static function dirSize(string $dir): int
    {
        $size = 0;
        if (!is_dir($dir)) return 0;
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($it as $file) {
            $size += $file->getSize();
        }
        return $size;
    }

    private static function formatSize(int $bytes): string
    {
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' Go';
        if ($bytes >= 1048576)    return round($bytes / 1048576, 2) . ' Mo';
        if ($bytes >= 1024)       return round($bytes / 1024, 1) . ' Ko';
        return $bytes . ' o';
    }
}