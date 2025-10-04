<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? '';
$status = $data['status'] ?? '';

if (!$id || !$status) {
    echo json_encode(['status' => 'error', 'message' => 'Thông tin không đầy đủ']);
    exit;
}

try {
    // Check if order exists
    $stmt = $pdo->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt->execute([$id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order) {
        echo json_encode(['status' => 'error', 'message' => 'Đơn hàng không tồn tại']);
        exit;
    }

    $currentStatus = $order['status'] ?: 'pending';
    if ($currentStatus === 'cancelled') {
        echo json_encode(['status' => 'error', 'message' => 'Đơn hàng đã bị hủy, không thể cập nhật trạng thái']);
        exit;
    }

    $validStatuses = ['pending', 'confirmed', 'in transit', 'shipped', 'completed'];
    $currentIndex = array_search($currentStatus, $validStatuses);
    $newIndex = array_search($status, $validStatuses);
    if ($newIndex === false || $newIndex < $currentIndex) {
        echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật trạng thái về trạng thái trước đó']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>