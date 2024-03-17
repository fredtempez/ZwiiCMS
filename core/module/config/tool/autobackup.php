<?php
// Creation du ZIP
$filter = $filter = ['backup', 'tmp'];
$fileName =  date('Y-m-d-H-i-s', time()) . '-rolling-backup.zip';
$zip = new ZipArchive();
$zip->open('../../../../site/backup/' . $fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
$directory = '../../../../site';
$files = new RecursiveIteratorIterator(
    new RecursiveCallbackFilterIterator(
        new RecursiveDirectoryIterator(
            $directory,
            RecursiveDirectoryIterator::SKIP_DOTS
        ),
        function ($fileInfo, $key, $iterator) use ($filter) {
            return $fileInfo->isFile() || !in_array($fileInfo->getBaseName(), $filter);
        }
    )
);
foreach ($files as $name => $file) {
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen(realpath($directory)) + 1);
        $zip->addFile($filePath, $relativePath);
    }
}
$zip->close();