<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $stmt = $pdo->prepare("SELECT value FROM settings WHERE key_name = 'banner' LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Nếu chưa có thì dùng ảnh mặc định
    $banner = $result ? $result['value'] : 'uploads/banner_1759414667.webp';

    echo json_encode(['banner' => $banner]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Lỗi truy vấn: ' . $e->getMessage()]);
}
?>