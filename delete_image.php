<?php
header('Content-Type: application/json');

$filename = $_POST['filename'] ?? '';

if (!$filename) {
    echo json_encode(['success' => false, 'message' => 'No filename provided']);
    exit;
}

$uploadDir = 'uploads/';
$imagesFile = 'images.json';

// Delete the file
if (!file_exists($uploadDir . $filename)) {
    echo json_encode(['success' => false, 'message' => 'File does not exist']);
    exit;
}

unlink($uploadDir . $filename);

// Update images.json
try {
    $images = json_decode(file_get_contents($imagesFile), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON format in images.json');
    }
    $images = array_filter($images, function($image) use ($filename) {
        return $image['filename'] !== $filename;
    });
    file_put_contents($imagesFile, json_encode(array_values($images)));
    echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>