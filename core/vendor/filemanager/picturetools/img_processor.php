<?php
header('Content-Type: application/json');

$base_dir = __DIR__ . "/../../../../site/file/source/"; 
$dest_dir = __DIR__ . "/../../../../site/file/source/optimized/";

$src_path = str_replace(['../', './'], '', $_POST['src'] ?? '');
$format   = $_POST['format'] ?? 'avif';
$newWidth = (int)($_POST['width'] ?? 1600);
$full_src_path = $base_dir . $src_path;

if (!file_exists($full_src_path)) {
    echo json_encode(['status' => 'error', 'error' => 'Source introuvable: ' . $src_path]);
    exit;
}

// 1. Chargement
$info = getimagesize($full_src_path);
switch ($info['mime']) {
    case 'image/jpeg': $image = imagecreatefromjpeg($full_src_path); break;
    case 'image/png':  $image = imagecreatefrompng($full_src_path); break;
    case 'image/webp': $image = imagecreatefromwebp($full_src_path); break;
    default: die(json_encode(['error' => 'Format non supporté']));
}

// 2. Redimensionnement si nécessaire
$width = imagesx($image);
$height = imagesy($image);

if ($newWidth > 0 && $newWidth < $width) {
    $newHeight = floor($height * ($newWidth / $width));
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    
    // Préservation de la transparence
    imagealphablending($tmp, false);
    imagesavealpha($tmp, true);
    
    imagecopyresampled($tmp, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    imagedestroy($image);
    $image = $tmp;
}

// 3. Sauvegarde
if (!is_dir($dest_dir)) mkdir($dest_dir, 0755, true);

// Vérification support AVIF, fallback vers WebP si non disponible
if ($format === 'avif' && !function_exists('imageavif')) {
    $format = 'webp';
}

$filename = pathinfo($src_path, PATHINFO_FILENAME) . '.' . $format;
$outputPath = $dest_dir . $filename;

$success = ($format === 'avif') ? imageavif($image, $outputPath, 65) : imagewebp($image, $outputPath, 75);

if ($success) {
    echo json_encode([
        'status' => 'success',
        'format' => $format,
        'gain' => round((1 - (filesize($outputPath) / filesize($full_src_path))) * 100) . '%'
    ]);
} else {
    echo json_encode(['status' => 'error', 'error' => 'Erreur PHP conversion']);
}
imagedestroy($image);