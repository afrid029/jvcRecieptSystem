<?php
// thumb.php - Simple Image Resizer/Compressor
// Usage: thumb.php?src=path/to/image.jpg&w=200&q=70

// Basic security checks
$src = $_GET['src'] ?? '';
$width = isset($_GET['w']) ? (int) $_GET['w'] : 200;
$quality = isset($_GET['q']) ? (int) $_GET['q'] : 70;

// Prevent directory traversal
$src = str_replace(['..', '//'], '', $src);
// Remove leading slash if present to make relative to current dir
$src = ltrim($src, '/');

// Absolute path to source
$sourcePath = __DIR__ . '/' . $src;

// If file doesn't exist or is not an image, return a placeholder or 404
if (!$src || !file_exists($sourcePath)) {
    // Return a 1x1 pixel empty image or 404
    header("HTTP/1.0 404 Not Found");
    exit;
}

// Allowed extensions
$ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
    header("HTTP/1.0 400 Bad Request");
    exit;
}

// Generate Cache Filename
// Cache dir: cache/thumbs/
$cacheDir = __DIR__ . '/cache/thumbs';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

// Hash params to create unique filename
$cacheFile = $cacheDir . '/' . md5($src . $width . $quality . filemtime($sourcePath)) . '.' . $ext;

// If cached file exists, serve it
if (file_exists($cacheFile)) {
    serveImage($cacheFile, $ext);
    exit;
}

// Process Image
try {
    // Get original dimensions
    list($origW, $origH) = getimagesize($sourcePath);
    if (!$origW || !$origH)
        throw new Exception("Invalid Image");

    // Calculate new height maintaining aspect ratio
    $height = (int) (($origH / $origW) * $width);

    // Create resource
    $image = null;
    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case 'png':
            $image = imagecreatefrompng($sourcePath);
            break;
        case 'gif':
            $image = imagecreatefromgif($sourcePath);
            break;
        case 'webp':
            $image = imagecreatefromwebp($sourcePath);
            break;
    }

    if (!$image)
        throw new Exception("Could not create image");

    // Create new blank image
    $newImage = imagecreatetruecolor($width, $height);

    // Handle transparency for PNG/WEBP/GIF
    if (in_array($ext, ['png', 'webp', 'gif'])) {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, $width, $height, $transparent);
    }

    // Resample
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $origW, $origH);

    // Save to Cache
    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($newImage, $cacheFile, $quality);
            break;
        case 'png':
            // PNG quality is 0-9, inverted logic. 70/100 -> ~3
            $pngQ = 9 - (int) (($quality / 100) * 9);
            imagepng($newImage, $cacheFile, $pngQ);
            break;
        case 'gif':
            imagegif($newImage, $cacheFile);
            break;
        case 'webp':
            imagewebp($newImage, $cacheFile, $quality);
            break;
    }

    imagedestroy($image);
    imagedestroy($newImage);

    // Serve
    serveImage($cacheFile, $ext);

} catch (Exception $e) {
    // If failed, just serve original as fallback
    serveImage($sourcePath, $ext);
}

function serveImage($file, $ext)
{
    $mime = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp'
    ];
    header('Content-Type: ' . ($mime[$ext] ?? 'application/octet-stream'));
    header('Content-Length: ' . filesize($file));
    readfile($file);
}
?>