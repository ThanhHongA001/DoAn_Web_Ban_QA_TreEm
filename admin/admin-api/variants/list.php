<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$search = $_GET['search'] ?? '';
$product_code = $_GET['product_code'] ?? '';

try {
    $query = "SELECT v.*, p.name AS product_name FROM product_variants v LEFT JOIN products p ON v.product_code = p.code WHERE 1=1";
    $params = [];

    if ($search) {
        $query .= " AND (v.size LIKE ? OR v.color LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    if ($product_code) {
        $query .= " AND v.product_code = ?";
        $params[] = $product_code;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'variants' => $variants]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>