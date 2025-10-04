<?php
require_once 'config.php';

header('Content-Type: application/json');

$product_code = isset($_GET['code']) ? trim($_GET['code']) : '';

if (empty($product_code)) {
    echo json_encode(['status' => 'error', 'error' => 'Mã sản phẩm không hợp lệ']);
    exit;
}

try {
    $stmt = $pdo->prepare('
        SELECT p.code, p.name, p.description, p.image, p.price, p.discount
        FROM products p
        WHERE p.code = ? AND p.status = 1
    ');
    $stmt->execute([$product_code]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(['status' => 'error', 'error' => 'Không tìm thấy sản phẩm']);
        exit;
    }

    $stmt = $pdo->prepare('
        SELECT id, product_code, size, color, stock
        FROM product_variants
        WHERE product_code = ? AND stock > 0
        ORDER BY size, color
    ');
    $stmt->execute([$product_code]);
    $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'code' => $product['code'],
        'name' => $product['name'],
        'description' => $product['description'],
        'image' => $product['image'],
        'price' => floatval($product['price']),
        'discount' => floatval($product['discount']),
        'variants' => $variants
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'error' => 'Lỗi khi truy vấn dữ liệu: ' . $e->getMessage()]);
}
?>