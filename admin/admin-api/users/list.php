<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$search = $_GET['search'] ?? '';

try {
    $query = "SELECT DISTINCT * FROM users WHERE 1=1";
    $params = [];

    if ($search) {
        $query .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'users' => $users]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>