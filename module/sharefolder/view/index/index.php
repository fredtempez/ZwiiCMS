<?php

include('module/sharefolder/vendor/php-dirlister/php-dirlister.php');

date_default_timezone_set($pdl->getConfig('timezone', 'UTC'));

$reverse = isset($_GET['r']) && $_GET['r'] === '1';
$items = $reverse ? array_reverse($items) : $items;


if ($pdl->getConfig('show_parent') && $pdl->getPath() !== '/' && empty($pdl->getBrowse())) {
    array_unshift($items, array('name' => '..', 'is_parent' => true, 'is_dir' => true, 'size' => 0, 'time' => 0));
}
?>
<body <?php if ($pdl->getConfig('content_alignment') === 'left') echo 'id="left"' ?>>

    <div id="wrapper">

        <h1><?php echo $pdl->generateTitle() ?></h1>
        <h2><?php echo $pdl->generateTitle(true) ?></h2>

        <ul id="header">
            <li>
                <a href="<?php echo $pdl->buildLink(array('s' => 'size', 'r' => (!$reverse && $sorting === 'size') ? '1' : null)) ?>" class="size <?php if ($sorting == 'size') echo $reverse ? 'desc' : 'asc' ?>"><span>Size</span></a>
                <a href="<?php echo $pdl->buildLink(array('s' => 'time', 'r' => (!$reverse && $sorting === 'time') ? '1' : null)) ?>" class="date <?php if ($sorting == 'time') echo $reverse ? 'desc' : 'asc' ?>"><span>Last Modified</span></a>
                <a href="<?php echo $pdl->buildLink(array('s' =>  null, 'r' => (!$reverse && $sorting === 'name') ? '1' : null)) ?>" class="name <?php if ($sorting == 'name') echo $reverse ? 'desc' : 'asc' ?>"><span>Name</span></a>
            </li>
        </ul>

        <ul>
            <?php foreach ($items as $item) : ?>
                <li class="item">
                    <span class="size">
                        <?php echo $item['is_dir'] ? '-' : $pdl->humanizeFilesize($item['size']) ?>
                    </span>
                    <span class="date">
                        <?php echo ((isset($item['is_parent']) && $item['is_parent']) || empty($item['time'])) ? '-' : date($pdl->getConfig('date_format'), $item['time']) ?>
                    </span>

                    <?php
                    if ($item['is_dir'] && $pdl->getConfig('browse_directories') && (!isset($item['is_parent']) || !$item['is_parent'])) {
                        if ($item['name'] === '..') {
                            $link = $pdl->buildLink(array('b' => substr($pdl->getBrowse(), 0, strrpos($pdl->getBrowse(), '/'))));
                        } else {
                            $link = $pdl->buildLink(array('b' => (empty($pdl->getBrowse()) ? '' : (string) $pdl->getBrowse() . '/') . $item['name']));
                        }
                    } else {
                        $link = (empty($pdl->getBrowse()) ? '' : str_replace(['%2F', '%2f'], '/', rawurlencode((string)$pdl->getBrowse())) . '/') . rawurlencode($item['name']);
                    }
                    ?>
                    <a href="<?php echo htmlentities($link) ?>" class="name <?php echo $item['is_dir'] ? 'dir' : 'file' ?>"><?php echo htmlentities($item['name']) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($pdl->getConfig('show_footer')) : ?>
            <p id="footer">
                Powered by <a href="https://github.com/esyede/php-dirlister" target="_blank">PHPDirLister</a>, simple directory indexer
                <br>
                Icons by <a href="https://github.com/markjames/famfamfam-silk-icons" target="_blank">FamFamFam (Mark James)</a>
            </p>
        <?php endif; ?>
    </div>=
</body>

</html>