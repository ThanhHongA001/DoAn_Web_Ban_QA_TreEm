<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? '';

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Thông tin không đầy đủ']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ? AND status != 'cancelled'");
    $stmt->execute([$id]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Đơn hàng không tồn tại hoặc đã bị hủy']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>