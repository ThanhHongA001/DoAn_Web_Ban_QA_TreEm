<?php
header('Content-Type: application/json');
require_once '../config.php';

$q = trim($_GET['q'] ?? '');
if ($q === '') { echo json_encode([]); exit; }

try {
    $stmt = $pdo->prepare("
        SELECT p.code, p.name, p.description, p.image, p.price, p.discount, p.featured, p.created_at, c.name AS category
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.status = 1 AND (p.name LIKE :kw OR p.description LIKE :kw)
        ORDER BY p.featured DESC, p.created_at DESC
        LIMIT 60
    ");
    $stmt->execute([':kw' => "%{$q}%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $variantStmt = $pdo->prepare("SELECT size, color, stock FROM product_variants WHERE product_code = ?");
    foreach ($products as &$p) {
        // Decode image field if it's JSON and take the first image
        if (!empty($p['image'])) {
            $decoded = json_decode($p['image'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $p['image'] = $decoded[0] ?? null;
            }
        }
        $variantStmt->execute([$p['code']]);
        $p['variants'] = $variantStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($products, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed']);
}
?>