<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$code = $_POST['code'] ?? '';
$updates = [];
$params = [];

if ($code) {
    $updates[] = 'code = ?';
    $params[] = $code;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Mã sản phẩm không hợp lệ']);
    exit;
}

if (isset($_POST['name']) && $_POST['name'] !== '') {
    $updates[] = 'name = ?';
    $params[] = $_POST['name'];
}
if (isset($_POST['category_id']) && $_POST['category_id'] !== '') {
    $updates[] = 'category_id = ?';
    $params[] = $_POST['category_id'];
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
    $stmt->execute([$params[count($params) - 1]]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Danh mục không tồn tại']);
        exit;
    }
}
if (isset($_POST['description'])) {
    $updates[] = 'description = ?';
    $params[] = $_POST['description'];
}
if (isset($_POST['price']) && is_numeric($_POST['price'])) {
    $updates[] = 'price = ?';
    $params[] = $_POST['price'];
}
if (isset($_POST['discount']) && is_numeric($_POST['discount'])) {
    $updates[] = 'discount = ?';
    $params[] = $_POST['discount'];
}
if (isset($_POST['featured']) && is_numeric($_POST['featured'])) {
    $updates[] = 'featured = ?';
    $params[] = $_POST['featured'];
}

if (empty($updates) || count($updates) === 1) {
    echo json_encode(['status' => 'error', 'message' => 'Không có trường nào để cập nhật']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT image FROM products WHERE code = ?");
    $stmt->execute([$code]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không tồn tại']);
        exit;
    }
    $existingImages = json_decode($product['image'] ?? '[]', true);

    // Handle multiple image uploads
    $newImages = [];
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
        $upload_dir = 'D:/Doan/web2/users/frontend/assets/images/';
        foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['image']['error'][$key] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!in_array($_FILES['image']['type'][$key], $allowed_types)) {
                    echo json_encode(['status' => 'error', 'message' => 'Chỉ chấp nhận file .jpg, .jpeg, .png, .webp']);
                    exit;
                }
                if ($_FILES['image']['size'][$key] > 5 * 1024 * 1024) {
                    echo json_encode(['status' => 'error', 'message' => 'Hình ảnh không được vượt quá 5MB']);
                    exit;
                }
                $file_name = uniqid() . '-' . basename($_FILES['image']['name'][$key]);
                $file_path = $upload_dir . $file_name;
                if (move_uploaded_file($tmp_name, $file_path)) {
                    $newImages[] = 'assets/images/' . $file_name;
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Lỗi khi tải lên hình ảnh']);
                    exit;
                }
            }
        }
    }
    $updatedImages = array_merge($existingImages ?: [], $newImages);
    if (!empty($updatedImages)) {
        $updates[] = 'image = ?';
        $params[] = json_encode($updatedImages);
    }

    $sql = "UPDATE products SET " . implode(', ', $updates) . " WHERE code = ?";
    $params[] = $code;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    foreach ($newImages as $image) {
        if (file_exists('../../' . $image)) {
            unlink('../../' . $image);
        }
    }
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>