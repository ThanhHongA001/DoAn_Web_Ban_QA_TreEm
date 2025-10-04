<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config.php'; // Assumes $pdo is defined here

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_categories':
            // Fetch categories
            $stmt = $pdo->prepare("SELECT id, name FROM categories ORDER BY name");
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($categories, JSON_UNESCAPED_UNICODE);
            break;

        case 'get_sizes':
            // Fetch unique sizes from product_variants
            $stmt = $pdo->prepare("SELECT DISTINCT size FROM product_variants ORDER BY size");
            $stmt->execute();
            $sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($sizes, JSON_UNESCAPED_UNICODE);
            break;

        case 'get_colors':
            // Fetch unique colors from product_variants
            $stmt = $pdo->prepare("SELECT DISTINCT color FROM product_variants ORDER BY color");
            $stmt->execute();
            $colors = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($colors, JSON_UNESCAPED_UNICODE);
            break;

        case 'get_variants':
            // Fetch variants for a specific product
            $productCode = $_GET['code'] ?? '';
            if (!$productCode) {
                http_response_code(400);
                echo json_encode(['error' => 'Product code is required']);
                exit;
            }
            $stmt = $pdo->prepare("SELECT size, color, stock FROM product_variants WHERE product_code = ?");
            $stmt->execute([$productCode]);
            $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($variants, JSON_UNESCAPED_UNICODE);
            break;

        case 'filter_products':
            // Filter products based on categories, price, and search query
            $categories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];
            $price = $_GET['price'] ?? '';
            $searchQuery = trim($_GET['q'] ?? '');

            $query = "
                SELECT p.code, p.name, p.description, p.image, p.price, p.discount, p.featured, p.created_at, c.name AS category
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 1
            ";
            $params = [];

            // Apply category filter
            if (!empty($categories)) {
                $placeholders = implode(',', array_fill(0, count($categories), '?'));
                $query .= " AND p.category_id IN ($placeholders)";
                $params = array_merge($params, $categories);
            }

            // Apply price filter
            if ($price) {
                $priceRanges = [
                    '0-50000' => ['min' => 0, 'max' => 50000],
                    '50000-100000' => ['min' => 50000, 'max' => 100000],
                    '100000-250000' => ['min' => 100000, 'max' => 250000],
                    '250000+' => ['min' => 250000, 'max' => PHP_INT_MAX]
                ];
                if (isset($priceRanges[$price])) {
                    $range = $priceRanges[$price];
                    $query .= " AND p.price BETWEEN ? AND ?";
                    $params[] = $range['min'];
                    $params[] = $range['max'];
                }
            }

            // Apply search query
            if ($searchQuery) {
                $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
                $params[] = "%{$searchQuery}%";
                $params[] = "%{$searchQuery}%";
            }

            $query .= " ORDER BY p.featured DESC, p.created_at DESC";
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Decode image field for each product
            foreach ($products as &$product) {
                if (!empty($product['image'])) {
                    $decoded = json_decode($product['image'], true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $product['image'] = $decoded[0] ?? null;
                    }
                }
            }

            echo json_encode(['success' => true, 'data' => $products], JSON_UNESCAPED_UNICODE);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed', 'details' => $e->getMessage()]);
}
?>