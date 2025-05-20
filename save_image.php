<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';
$imagesFile = 'images.json';

// Check if uploads directory exists
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Check if images.json exists
if (!file_exists($imagesFile)) {
    file_put_contents($imagesFile, '[]');
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $imageFile = $_FILES['image'];
    $date = $_POST['date'] ?? '';
    $place = $_POST['place'] ?? '';

    // Validate image
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($imageFile['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        exit;
    }

    // Generate unique filename
    $fileName = uniqid() . '.' . pathinfo($imageFile['name'], PATHINFO_EXTENSION);
    $uploadPath = $uploadDir . $fileName;

    // Move uploaded file
    if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
        // Update images.json
        $images = json_decode(file_get_contents($imagesFile), true);
        $images[] = [
            'filename' => $fileName,
            'date' => $date,
            'place' => $place
        ];
        file_put_contents($imagesFile, json_encode($images));

        echo json_encode(['success' => true, 'message' => 'Image uploaded successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>