<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$code = $data['code'] ?? '';

if (!$code) {
    echo json_encode(['status' => 'error', 'message' => 'Thông tin không đầy đủ']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT image FROM products WHERE code = ?");
    $stmt->execute([$code]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        $images = json_decode($product['image'] ?? '[]', true);
        foreach ($images as $image) {
            if ($image && file_exists('../../' . $image)) {
                unlink('../../' . $image);
            }
        }
    }

    $stmt = $pdo->prepare("DELETE FROM product_variants WHERE product_code = ?");
    $stmt->execute([$code]);

    $stmt = $pdo->prepare("DELETE FROM products WHERE code = ?");
    $stmt->execute([$code]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>