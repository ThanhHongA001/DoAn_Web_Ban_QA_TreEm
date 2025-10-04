<?php
require_once '../dp.php';
header('Content-Type: application/json');

// Kiểm tra đăng nhập admin
if (!isAdminLoggedIn()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Chưa đăng nhập'
    ]);
    exit;
}

$search = trim($_GET['search'] ?? '');
$category_id = $_GET['category_id'] ?? '';

try {
    $sql = "
        SELECT p.code, p.name, p.description, p.image, p.price, p.discount, p.featured, 
               p.category_id, c.name AS category_name, p.created_at
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE 1
    ";
    $params = [];

    // Tìm theo tên hoặc code sản phẩm
    if ($search !== '') {
        $sql .= " AND (p.name LIKE ? OR p.code LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    // Lọc theo danh mục (chỉ nhận số nguyên)
    if ($category_id !== '' && ctype_digit($category_id)) {
        $sql .= " AND p.category_id = ?";
        $params[] = (int)$category_id;
    }

    $sql .= " ORDER BY p.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Giải mã ảnh JSON (nếu có lưu nhiều ảnh)
    foreach ($products as &$p) {
        if (!empty($p['image'])) {
            $decoded = json_decode($p['image'], true);
            $p['image'] = $decoded ?: [$p['image']];
        } else {
            $p['image'] = [];
        }
    }

    echo json_encode([
        'status' => 'success',
        'products' => $products
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi hệ thống: ' . $e->getMessage()
    ]);
}
?>