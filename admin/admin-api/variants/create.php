<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$product_code = $_POST['product_code'] ?? '';
$size = $_POST['size'] ?? '';
$color = $_POST['color'] ?? '';
$stock = $_POST['stock'] ?? 0;

if (!$product_code || !$size || !$color || !$stock) {
    echo json_encode(['status' => 'error', 'message' => 'Thông tin không đầy đủ']);
    exit;
}

try {
    // Validate product_code exists
    $stmt = $pdo->prepare("SELECT code FROM products WHERE code = ?");
    $stmt->execute([$product_code]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không tồn tại']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO product_variants (product_code, size, color, stock) VALUES (?, ?, ?, ?)");
    $stmt->execute([$product_code, $size, $color, $stock]);
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>