<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$code = $_POST['code'] ?? '';
$description = $_POST['description'] ?? '';
$discount_percent = $_POST['discount_percent'] ?? 0;
$quantity = $_POST['quantity'] ?? 0;
$expiry_date = $_POST['expiry_date'] ?? '';

if (!$code || !$discount_percent || !$quantity || !$expiry_date) {
    echo json_encode(['status' => 'error', 'message' => 'Thông tin không đầy đủ']);
    exit;
}

if ($discount_percent < 1 || $discount_percent > 100) {
    echo json_encode(['status' => 'error', 'message' => 'Phần trăm giảm giá phải từ 1 đến 100']);
    exit;
}

if ($quantity < 1) {
    echo json_encode(['status' => 'error', 'message' => 'Số lượng phải lớn hơn 0']);
    exit;
}

try {
    // Check for duplicate code
    $stmt = $pdo->prepare("SELECT code FROM vouchers WHERE code = ?");
    $stmt->execute([$code]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Mã voucher đã tồn tại']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO vouchers (code, description, discount_percent, quantity, expiry_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$code, $description, $discount_percent, $quantity, $expiry_date]);
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>