<?php
require_once '../config.php';

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Define the upload directory
$uploadDir = 'C:/xampp/htdocs/web2/users/frontend/uploads/';
$relativePath = 'uploads/';

// Ensure the uploads directory exists
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        respond('error', 'Failed to create upload directory');
    }
}

function respond($status, $message = '', $data = []) {
    echo json_encode(['status' => $status, 'message' => $message, 'settings' => $data]);
    exit;
}

// Handle GET request to fetch settings
if ($action === 'get') {
    try {
        $stmt = $pdo->query("SELECT key_name, value FROM settings WHERE key_name IN ('logo', 'banner')");
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['key_name']] = $row['value'];
        }
        respond('success', 'Settings retrieved successfully', $settings);
    } catch (PDOException $e) {
        respond('error', 'Database error: ' . $e->getMessage());
    }
}

// Handle POST request to update settings
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $logoPath = null;
    $bannerPath = null;

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo = $_FILES['logo'];
        $ext = pathinfo($logo['name'], PATHINFO_EXTENSION);
        $logoFileName = 'logo_' . time() . '.' . $ext;
        $logoPath = $uploadDir . $logoFileName;
        if (!move_uploaded_file($logo['tmp_name'], $logoPath)) {
            respond('error', 'Failed to upload logo');
        }
        $logoPath = $relativePath . $logoFileName;
    }

    // Handle banner upload
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $banner = $_FILES['banner'];
        $ext = pathinfo($banner['name'], PATHINFO_EXTENSION);
        $bannerFileName = 'banner_' . time() . '.' . $ext;
        $bannerPath = $uploadDir . $bannerFileName;
        if (!move_uploaded_file($banner['tmp_name'], $bannerPath)) {
            respond('error', 'Failed to upload banner');
        }
        $bannerPath = $relativePath . $bannerFileName;
    }

    try {
        // Update or insert logo
        if ($logoPath) {
            $stmt = $pdo->prepare("INSERT INTO settings (key_name, value) VALUES ('logo', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$logoPath, $logoPath]);
        }

        // Update or insert banner
        if ($bannerPath) {
            $stmt = $pdo->prepare("INSERT INTO settings (key_name, value) VALUES ('banner', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$bannerPath, $bannerPath]);
        }

        // Fetch updated settings
        $stmt = $pdo->query("SELECT key_name, value FROM settings WHERE key_name IN ('logo', 'banner')");
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['key_name']] = $row['value'];
        }
        respond('success', 'Settings updated successfully', $settings);
    } catch (PDOException $e) {
        respond('error', 'Database error: ' . $e->getMessage());
    }
}

respond('error', 'Invalid action');
?>