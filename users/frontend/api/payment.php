<?php
header('Content-Type: application/json');
// Assume session and PDO setup from previous examples
$data = json_decode(file_get_contents('php://input'), true);
$orderId = $data['order_id'];

// Mock payment validation (replace with gateway response)
if ($orderId) {
    $stmt = $pdo->prepare("UPDATE orders SET status = 'confirmed' WHERE id = ? AND status = 'pending'");
    $stmt->execute([$orderId]);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin đơn hàng']);
}
?>