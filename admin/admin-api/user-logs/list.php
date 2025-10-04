<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isSuperAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Chỉ superadmin có thể truy cập']);
    exit;
}

try {
    $stmt = $pdo->query("
        SELECT ul.*, u.name as user_name
        FROM user_logs ul
        JOIN users u ON ul.user_id = u.id
    ");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'logs' => $logs]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>