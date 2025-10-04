<?php
require_once 'config.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';

if ($type === 'featured') {
    $stmt = $pdo->prepare('SELECT code, name, image, price AS original_price, price * (1 - discount / 100) AS discounted_price FROM products WHERE featured = 1 AND status = 1 LIMIT 8');
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
} elseif ($type === 'best-selling') {
    $stmt = $pdo->prepare('
        SELECT p.code, p.name, p.image, p.price AS original_price, p.price * (1 - p.discount / 100) AS discounted_price
        FROM products p
        JOIN order_items oi ON p.code = (SELECT product_code FROM product_variants WHERE id = oi.variant_id)
        GROUP BY p.code
        ORDER BY SUM(oi.quantity) DESC
        LIMIT 8
    ');
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
}
?>