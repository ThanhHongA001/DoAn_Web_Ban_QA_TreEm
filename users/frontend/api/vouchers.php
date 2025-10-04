<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT * FROM vouchers WHERE expiry_date >= CURDATE() AND quantity > 0");
    $vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($vouchers);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Lỗi khi lấy dữ liệu voucher']);
}
?>