<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$search = $_GET['search'] ?? '';
$id = $_GET['id'] ?? null;

try {
    $query = "SELECT * FROM orders WHERE 1=1";
    $params = [];

    if ($search) {
        $query .= " AND (id LIKE ? OR user_id LIKE ? OR voucher_code LIKE ? OR shipping_address LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    if ($id) {
        $query .= " AND id = ?";
        $params[] = $id;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'orders' => $orders]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>