<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config.php'; // đã có $pdo trong này

try {
    // Lấy danh sách sản phẩm + danh mục
    $stmt = $pdo->prepare("
        SELECT p.code, p.name, p.description, p.image, p.price, p.discount, p.featured, p.created_at, c.name as category
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.status = 1
    ");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Lấy toàn bộ variants 1 lần
    $variantStmt = $pdo->prepare("SELECT product_code, size, color, stock FROM product_variants");
    $variantStmt->execute();
    $variants = $variantStmt->fetchAll(PDO::FETCH_ASSOC);

    // Gom variants theo product_code
    $variantMap = [];
    foreach ($variants as $v) {
        $variantMap[$v['product_code']][] = [
            'size'  => $v['size'],
            'color' => $v['color'],
            'stock' => $v['stock']
        ];
    }

    // Gắn variants vào từng sản phẩm
    foreach ($products as &$product) {
        // Nếu image lưu JSON thì decode, lấy ảnh đầu tiên
        if (!empty($product['image'])) {
            $decoded = json_decode($product['image'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $product['image'] = $decoded[0] ?? null;
            }
        }
        $product['variants'] = $variantMap[$product['code']] ?? [];
    }

    echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed', 'details' => $e->getMessage()]);
}
?>