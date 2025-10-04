<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$id = $_POST['id'] ?? '';
$product_code = $_POST['product_code'] ?? '';
$size = $_POST['size'] ?? '';
$color = $_POST['color'] ?? '';
$stock = $_POST['stock'] ?? 0;

if (!$id || !$product_code || !$size || !$color || !$stock) {
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

    // Validate variant exists
    $stmt = $pdo->prepare("SELECT id FROM product_variants WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Biến thể không tồn tại']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE product_variants SET product_code = ?, size = ?, color = ?, stock = ? WHERE id = ?");
    $stmt->execute([$product_code, $size, $color, $stock, $id]);
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>