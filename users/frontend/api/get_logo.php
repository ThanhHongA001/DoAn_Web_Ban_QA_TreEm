<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $stmt = $pdo->prepare("SELECT value FROM settings WHERE key_name = 'logo' LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Nếu không có thì lấy logo mặc định
    $logo = $result ? $result['value'] : 'uploads/logo_1759414381.jpg';

    echo json_encode(['logo' => $logo]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Lỗi truy vấn: ' . $e->getMessage()]);
}
?>