<?php

/*
|--------------------------------------------------------------------------
| CONFIGURATION
|--------------------------------------------------------------------------
|
| Modify this to suits your need.
|
*/

$config = array(
    'page_title' => 'Index of [path]',
    'page_subtitle' => 'Total: [items] items, [size]',
    'browse_directories' => true,
    'show_breadcrumbs' => true,
    'show_directories' => true,
    'show_footer' => true,
    'show_parent' => false,
    'show_hidden' => false,
    'directory_first' => true,
    'content_alignment' => 'center',
    'date_format' => 'd M Y H:i',
    'timezone' => 'Asia/Jakarta',
    'ignore_list' => array(
        '.DS_Store',
        '.git',
        '.gitmodules',
        '.gitignore',
        '.vscode',
        'vendor',
        'node_modules',
    ),
);


/*
|--------------------------------------------------------------------------
| ACTUAL PROGRAM STARTS HERE
|--------------------------------------------------------------------------
*/

class PHPDirLister
{
    private $self;
    private $path;
    private $browse;
    private $total;
    private $totalSize;
    private $config = array();

    public function __construct(array $config = array())
    {
        $this->config = $config;
        $this->self = basename($_SERVER['PHP_SELF']);
        $this->path = str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));
        $this->total = 0;
        $this->totalSize = 0;

        if ($this->config['browse_directories']) {
            $_GET['b'] = trim(str_replace('\\', '/', (string) isset($_GET['b']) ? $_GET['b'] : ''), '/ ');
            $_GET['b'] = str_replace(array('/..', '../'), '', (string) isset($_GET['b']) ? $_GET['b'] : '');

            if (!empty($_GET['b']) && $_GET['b'] !== '..' && is_dir($_GET['b'])) {
                $ignored = false;
                $names = explode('/', $_GET['b']);

                foreach ($names as $name) {
                    if (in_array($name, $this->config['ignore_list'])) {
                        $ignored = true;
                        break;
                    }
                }

                if (!$ignored) {
                    $this->browse = $_GET['b'];
                }

                if (!empty($this->browse)) {
                    $index = null;

                    if (is_file($this->browse . '/index.htm')) {
                        $index = '/index.htm';
                    } elseif (is_file($this->browse . '/index.html')) {
                        $index = '/index.html';
                    } elseif (is_file($this->browse . '/index.php')) {
                        $index = '/index.php';
                    }

                    if (!is_null($index)) {
                        header('Location: ' . $this->browse . $index);
                        exit;
                    }
                }
            }
        }
    }

    public function getSelf()
    {
        return $this->self;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getBrowse()
    {
        return $this->browse;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getTotalSize()
    {
        return $this->totalSize;
    }

    public function getConfig($key, $default = null)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }

    public function getListings($path)
    {
        $ls = array();
        $lsDir = array();

        if (($dh = @opendir($path)) === false) {
            return $ls;
        }

        $path .= (substr($path, -1) !== '/') ? '/' : '';

        while (($file = readdir($dh)) !== false) {
            if (
                $file === $this->self
                || $file === '.'
                || $file == '..'
                || (!$this->config['show_hidden'] && substr($file, 0, 1) === '.')
                || in_array($file, $this->config['ignore_list'])
            ) {
                continue;
            }

            $isDir = is_dir($path . $file);

            if (!$this->config['show_directories'] && $isDir) {
                continue;
            }

            $item = array(
                'name' => $file,
                'is_dir' => $isDir,
                'size' => $isDir ? 0 : filesize($path . $file),
                'time' => filemtime($path . $file),
            );

            if ($isDir) {
                $lsDir[] = $item;
            } else {
                $ls[] = $item;
            }

            $this->total++;
            $this->totalSize += $item['size'];
        }

        return array_merge($lsDir, $ls);
    }

    public function sortByName($a, $b)
    {
        return (
            ($a['is_dir'] === $b['is_dir'])
            || ($this->config['directory_first']) ? ($a['is_dir'] < $b['is_dir']) : (strtolower($a['name']) > strtolower($b['name']))
        ) ? 1 : -1;
    }

    public function sortBySize($a, $b)
    {
        return (
            ($a['is_dir'] === $b['is_dir']) ? ($a['size'] > $b['size']) : ($a['is_dir'] < $b['is_dir'])
        ) ? 1 : -1;
    }

    public function sortByTime($a, $b)
    {
        return ($a['time'] > $b['time']) ? 1 : -1;
    }

    public function humanizeFilesize($val)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = min(floor(($val ? log($val) : 0) / log(1024)), count($units) - 1);
        $val = sprintf('%.1f %s', round($val / pow(1024, $power), 1), $units[$power]);

        return str_replace('.0 ', ' ', $val);
    }

    public function generateTitle($forSubtitle = false)
    {
        $path = htmlentities($this->path);
        $title = htmlentities(str_replace(
            array('[items]', '[size]'),
            array($this->total, $this->humanizeFilesize($this->totalSize)),
            $this->config[$forSubtitle ? 'page_subtitle' : 'page_title']
        ));

        if ($this->config['show_breadcrumbs']) {
            $path = sprintf('<a href="%s">%s</a>', htmlentities($this->buildLink(array('b' => ''))), $path);
        }

        if (!empty($this->getBrowse())) {
            $path .= ($this->path !== '/') ? '/' : '';
            $items = explode('/', trim($this->browse, '/'));

            foreach ($items as $i => $item) {
                $path .= $this->config['show_breadcrumbs']
                    ? sprintf(
                        '<a href="%s">%s</a>',
                        htmlentities($this->buildLink(array('b' => implode('/', array_slice($items, 0, $i + 1))))),
                        htmlentities($item)
                    )
                    : htmlentities($item);
                $path .= (count($items) > ($i + 1)) ? '/' : '';
            }
        }

        return str_replace('[path]', $path, $title);
    }

    public function buildLink($changes)
    {
        $params = $_GET;

        foreach ($changes as $k => $v) {
            if (is_null($v)) {
                unset($params[$k]);
            } else {
                $params[$k] = $v;
            }
        }

        foreach ($params as $k => $v) {
            $params[$k] = urlencode($k) . '=' . urlencode($v);
        }

        return empty($params) ? $this->self : $this->self . '?' . implode('&', $params);
    }
}

$pdl = new PHPDirLister($config);
$items = $pdl->getListings('.' . (empty($pdl->getBrowse()) ? '' : '/' . $pdl->getBrowse()));
$sorting = isset($_GET['s']) ? $_GET['s'] : null;

switch ($sorting) {
    case 'size':
        $sorting = 'size';
        usort($items, function ($a, $b) use ($pdl) {
            return $pdl->sortBySize($a, $b);
        });
        break;

    case 'time':
        $sorting = 'time';
        usort($items, function ($a, $b) use ($pdl) {
            return $pdl->sortByTime($a, $b);
        });
        break;

    default:
        $sorting = 'name';
        usort($items, function ($a, $b) use ($pdl) {
            return $pdl->sortByName($a, $b);
        });
        break;
}
