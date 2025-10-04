<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $product_code = isset($_GET['code']) ? $_GET['code'] : '';

    if (empty($product_code)) {
        echo json_encode(['error' => 'Mã sản phẩm không hợp lệ']);
        exit;
    }

    $sql = "SELECT pr.id, pr.user_id, pr.rating, pr.comment, pr.created_at 
            FROM product_reviews pr 
            WHERE pr.code = :code 
            ORDER BY pr.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['code' => $product_code]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($reviews);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Lỗi khi lấy đánh giá: ' . $e->getMessage()]);
}
?>