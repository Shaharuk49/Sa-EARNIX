<?php
// Simple PNG processor: convert near-black pixels to transparent using GD
$src = __DIR__ . '/../public/images/logo.png';
if (!file_exists($src)) {
    echo "Source not found: $src\n";
    exit(1);
}

$img = imagecreatefrompng($src);
if (!$img) {
    echo "Failed to open image\n";
    exit(1);
}

$w = imagesx($img);
$h = imagesy($img);

$out = imagecreatetruecolor($w, $h);
imagesavealpha($out, true);
$transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
imagefill($out, 0, 0, $transparent);

$threshold = 60; // distance from black under which pixel becomes transparent

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgba = imagecolorat($img, $x, $y);
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;
        $dist = sqrt($r * $r + $g * $g + $b * $b);
        if ($dist < $threshold) {
            // keep transparent
            imagesetpixel($out, $x, $y, $transparent);
        } else {
            $c = imagecolorallocatealpha($out, $r, $g, $b, 0);
            imagesetpixel($out, $x, $y, $c);
        }
    }
}

// Overwrite original
if (imagepng($out, $src)) {
    echo "Converted and saved: $src\n";
    imagedestroy($img);
    imagedestroy($out);
    exit(0);
} else {
    echo "Failed to save output\n";
    imagedestroy($img);
    imagedestroy($out);
    exit(1);
}
